<?php

class SolicitudController extends BaseController {

	public function cargar_solicitudes()
	{
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"]= Session::get('user');
		$data["resultados"] = null;
		$data["cantidad_procesados"] = null;
		$data["cantidad_total"] = null;
		$data["logs"] = null;
		return View::make('Solicitudes/cargarSolicitudes',$data);
	}

	public function listar_solicitudes()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1 || $data["user"]->idrol == 2){
				$data["search_solicitud"] = null;
				$data["fecha_solicitud_desde"] = null;
				$data["fecha_solicitud_hasta"] = null;
				$data["search_tipo_solicitud"] = null;
				$data["search_estado_solicitud"] = null;
				$data["search_sector"] = null;
				$data["tipos_solicitud"] = TipoSolicitud::lists('nombre','idtipo_solicitud');
				$data["estados_solicitud"] = EstadoSolicitud::lists('nombre','idestado_solicitud');
				$data["solicitudes_data"] = Solicitud::withTrashed()->listarSolicitudes()->paginate(10);
				$data["sectores"] = Sector::lists('nombre','idsector');
				return View::make('Solicitudes/listarSolicitudes',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function buscar_solicitudes()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1 || $data["user"]->idrol == 2){
				$data["search_solicitud"] = Input::get('search_solicitud');
				$data["fecha_solicitud_desde"] = Input::get('fecha_solicitud_desde');
				$data["fecha_solicitud_hasta"] = Input::get('fecha_solicitud_hasta');
				$data["search_tipo_solicitud"] = Input::get('search_tipo_solicitud');
				$data["search_estado_solicitud"] = Input::get('search_estado_solicitud');
				$data["search_sector"] = Input::get('search_sector');
				$data["tipos_solicitud"] = TipoSolicitud::lists('nombre','idtipo_solicitud');
				$data["estados_solicitud"] = EstadoSolicitud::lists('nombre','idestado_solicitud');
				$data["sectores"] = Sector::lists('nombre','idsector');

				if($data["search_solicitud"] == null && $data["fecha_solicitud_desde"]== null && $data["fecha_solicitud_hasta"]== null && $data["search_tipo_solicitud"] == 0 && $data["search_estado_solicitud"] == 0 && $data["search_sector"] == 0 ){
					$data["solicitudes_data"] = Solicitud::withTrashed()->listarSolicitudes()->paginate(10);
					return View::make('Solicitudes/listarSolicitudes',$data);

				}else{
					$data["solicitudes_data"] = Solicitud::withTrashed()->buscarSolicitudes($data["search_solicitud"],$data["fecha_solicitud_desde"],$data["fecha_solicitud_hasta"],$data["search_tipo_solicitud"],$data["search_estado_solicitud"],$data["search_sector"])->paginate(10);
					return View::make('Solicitudes/listarSolicitudes',$data);	
				}				
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	
	public function cargar_archivo_solicitudes()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1 || $data["user"]->idrol == 2){
			
			
				//Lectura del archivo
				$file_name = $_FILES['file']['tmp_name'];

				if(strcmp($file_name,'')==0){
					Session::flash('error', 'Archivo sin adjuntar.');
					return Redirect::to('solicitudes/cargar_solicitudes');	
				}
				
				$file_handle = fopen($file_name, 'r');
			    
			    while (!feof($file_handle) ) {
			        $line_of_text[] = fgetcsv($file_handle, 1024,"|");
			    }
			    fclose($file_handle);

			    //inicio del algoritmo
			    $lista_solicitudes = $line_of_text;
				$cantidad_registros_totales = count($lista_solicitudes);
				$cantidad_registros_procesados = 0;

				$data["resultados"] = array();

				$herramientas = Herramienta::listarHerramientas()->get();
				$logs_errores = array();
				
				
				for($i = 1; $i < $cantidad_registros_totales-1; $i++)
				{
					//2.1. Leer Valores
					$codigo_entidad = $lista_solicitudes[$i][0];
					$nombre_entidad = $lista_solicitudes[$i][1];
					$tipo_solicitud_gral = $lista_solicitudes[$i][2];
					$codigo_solicitud = $lista_solicitudes[$i][3];
					$asunto = $lista_solicitudes[$i][4];
					$fecha_solicitud = $lista_solicitudes[$i][5];
					$estado_solicitud = $lista_solicitudes[$i][6];
					$fecha_estado = $lista_solicitudes[$i][7];

					//algunas variables adicionales
					$codigo_solicitud_ingresar = null;
					$fecha_solicitud_date = null;
					$fecha_estado_date = null;
					$tipo_accion = null;
					$entidad = null;
					$array_herramientas = array();
					$idherramienta = null;
					//2.2. Validar datos vacíos y válidos

					//CREACION DEL ARREGLO LOG
					$obj_log = [
						"numero" => $i,
						"descripcion" => null,
						"accion" => "no detectada",
						"entidad" => "no existe",
						"canal" => "no existe",
						"sector" => "no existe",
					];
					
					$array_log_text = '';

					//1. VALIDACION DEL CODIGO DE ENTIDAD
					if(strcmp($codigo_entidad,'') != 0)
					{
						//validar si existe el codigo
						$entidad = Entidad::buscarPorCodigoEntidad($codigo_entidad)->get();
						if($entidad == null || $entidad->isEmpty()){
							//NO PROCEDE
							$obj_log["descripcion"] = "La entidad ".$nombre_entidad." del registro no existe";
							array_push($logs_errores,$obj_log);
							continue; //(LOGS)	
						} 
						else{
							$nombre_entidad_encontrada = $entidad[0]->nombre;
							if( strcmp($nombre_entidad_encontrada, $nombre_entidad) != 0 ){
								//NO PROCEDE
								$obj_log["descripcion"] = "La entidad ".$nombre_entidad." no coincide con el código registrado en el sistema.";
								array_push($logs_errores,$obj_log);
								continue; //(LOGS)
							} 
						}
					}else{
						//NO PROCEDE
						$obj_log["descripcion"] = "El campo Entidad está vacío.";
						array_push($logs_errores,$obj_log);
						continue; //(LOGS)
					}

					//1.2 EXTRACCION DE LA DATA PARA LA OBTENCION DEL CANAL Y EL SECTOR
					$canal_entidad = Canal::find($entidad[0]->idcanal);
					$sector_canal = Sector::find($canal_entidad->idsector);

					$obj_log["entidad"] = $entidad[0]->nombre;
					$obj_log["canal"] = $canal_entidad->nombre;
					$obj_log["sector"] = $sector_canal->nombre;

					//2. VALIDACION DEL ESTADO DE SOLICITUD

					if(strcmp($estado_solicitud,'') != 0)
					{	//validar si el estado de solicitud existe
						$estado_solicitud_obj = EstadoSolicitud::buscarPorNombre($estado_solicitud)->get();
						if($estado_solicitud_obj == null || $estado_solicitud_obj->isEmpty()) //NO PROCEDE
						{
							$obj_log["descripcion"] = "El estado de la solicitud no se encuentra registrado en el sistema.";
							array_push($logs_errores,$obj_log);
							continue; //(LOGS)
						}

						if($estado_solicitud_obj[0]->idestado_solicitud != 3){
							$obj_log["descripcion"] = "La solicitud ya se encuentra procesada. No se asignan solicitudes que ya están en proceso.";
							array_push($logs_errores,$obj_log);
							continue;
						}
					}else //NO PROCEDE
					{
						$obj_log["descripcion"] = "El campo Estado de Solicitud está vacío.";
						array_push($logs_errores,$obj_log);
						
						continue; //(LOGS)
					}

					//3. VALIDACION DEL TIPO DE SOLICITUD GENERAL
					if(strcmp($tipo_solicitud_gral,'') != 0)
					{
						//validar si existe el tipo de solicitud
						$tipo_solicitud_obj = TipoSolicitudGeneral::buscarPorNombre($tipo_solicitud_gral)->get();
						if($tipo_solicitud_obj == null || $tipo_solicitud_obj->isEmpty()) 
						{	
							array_push($logs_errores,"El tipo de solicitud no existe.");
							//NO PROCEDE
							continue; //(LOGS)
						}
					}else
					{   
						$obj_log["descripcion"] = "El tipo de solicitud no existe.";
						array_push($logs_errores,$obj_log);
						//NO PROCEDE
						continue; //(LOGS)
					}

					//4. VALIDACION DEL CODIGO DE LA SOLICITUD
					//Validar si el codigo es vacío y posteriomente se valida si es numérico
					if(strcmp($codigo_solicitud,'') == 0 || !ctype_digit($codigo_solicitud))
					{
						$obj_log["descripcion"] = "El código de solicitud no existe o no es numérico";
						array_push($logs_errores,$obj_log);
						continue; //(LOGS)
					}
					else
						$codigo_solicitud_ingresar = (int)$codigo_solicitud;

					//5. VALIDACION DEL ASUNTO
					if(strcmp($asunto,'') == 0){
						$obj_log["descripcion"] = "El campo asunto no existe.";
						array_push($logs_errores,$obj_log);
						continue; //(LOGS)
					}

					//6. VALIDACION DE LA FECHA DE SOLICITUD
					if(strcmp($fecha_solicitud,'') != 0)
					{
						if(DateTime::createFromFormat('d/m/Y', $fecha_solicitud) == false)
						{
							$obj_log["descripcion"] = "La fecha de solicitud no cuenta con el formato de fecha correcto";
							array_push($logs_errores,$obj_log);
							//FECHA CON FORMATO ERRADO
							continue; //(LOGS)
						} 
						else{	
							$partes = explode("/",$fecha_solicitud);
							$fecha_solicitud_date = date('Y-m-d',strtotime($partes[2]."-".$partes[1]."-".$partes[0]));
						}
					}else //NO PROCEDE
					{
						$obj_log["descripcion"] = "El campo Fecha de Solicitud está vacío.";
						array_push($logs_errores,$obj_log);
						continue; //(LOGS)
					}
					

					//7. VALIDACION DE LA FECHA DE ESTADO
					if(strcmp($fecha_estado,'') != 0)
					{
						if(DateTime::createFromFormat('d/m/Y', $fecha_estado) == false) //FECHA CON FORMATO ERRADO
						{
							$obj_log["descripcion"] = "La fecha de estado no cuenta con el formato de fecha correcto.";
							array_push($logs_errores,$obj_log);
							
							continue; //(LOGS)
						}
						else{
							$partes = explode("/",$fecha_estado);
							$fecha_estado_date = date('Y-m-d',strtotime($partes[2]."-".$partes[1]."-".$partes[0]));
						}
					}else //NO PROCEDE (LOGS)
					{	
						$obj_log["descripcion"] = "El campo Fecha de Estado está vacío.";
						array_push($logs_errores,$obj_log);
						continue;
					}

					
					//8. VALIDACION DEL TIPO DE ACCION

					//VALIDACION TIPO DE ACCION
					/*******************SUJETO A CAMBIO DE ALGORITMO***********************/
					$idtipo_accion = SolicitudController::obtener_tipo_solicitud_2($asunto);
					/**********************************************************************/
					if($idtipo_accion == 0){
						$obj_log["descripcion"] = "La solicitud no describe ninguna acción registrada en el sistema.";
						array_push($logs_errores,$obj_log);
						
						continue; //NO PROCEDE porque no existe la creacion (NO SE ACEPTARA MAS DE UNA ACCION) - LOGS
					}
					else{						
						$tipo_accion = TipoSolicitud::find($idtipo_accion);
						$obj_log["accion"] = $tipo_accion->nombre;
					}	
						
					
					//9. VALIDACION DE LA APLICACION
					/*******************SUJETO A CAMBIO DE ALGORITMO*************************************/
					$resultado = SolicitudController::obtener_herramienta($asunto,$herramientas);
					/************************************************************************************/
					/************************CAMBIO DE CODIGO (SE USARÁ ÑLA HERRAMIENTA "VARIOS")*************/
					/*$codigos_herramientas = '';
					if(count($array_herramientas)>1)
					{
						
						//quiere decir que hay mas de una herramienta
						$tamano =count($array_herramientas);
						$nombre_herramienta = "VARIOS";
						for($p=0; $p<$tamano; $p++)
						{
							if($p<$tamano-1)
								$codigos_herramientas = $codigos_herramientas.$array_herramientas[$p]->idherramienta.'|';
							else
								$codigos_herramientas = $codigos_herramientas.$array_herramientas[$p]->idherramienta;
						}
						
						$nombre_herramienta[0]->

						

					}else if(count($array_herramientas) == 1)
					{
						//solo existe 1
						$nombre_herramienta = $array_herramientas[0]->nombre;
						$codigos_herramientas = $array_herramientas[0]->idherramienta;
					}else
					{
						//quiere decir que no existen herramientas
						$nombre_herramienta = "NO DETECTADO";
					}*/
					/***********************************NUEVO CODIGO***************************************************/
					if($resultado == 0)
					{
						$idherramienta = 39; //REPRESENTA LA HERRAMIENTA VARIOS
					}else if($resultado == -1)
					{
						$idherramienta = 0; //REPRESENTA NO DETECCION DE HERRAMIENTA

					}else{
						$idherramienta = $resultado;
					}

					if($idherramienta == 0)
					{
						$obj_log["descripcion"] = "El sistema no puede detectar la herramienta(s) solicitadas.";
						//array_push($logs_errores,$obj_log);
						//continue;
						$nombre_herramienta = "NO DETECTADO";
					}else{
						$herramienta = Herramienta::find($idherramienta);
						$nombre_herramienta = $herramienta->nombre;
					}

					//2.5 Luego de estas validaciones se deberá revisar si la solicitud ya existe 
					$solicitud = Solicitud::buscarPorCodigoSolicitud($codigo_solicitud_ingresar)->get();
					if($solicitud == null || $solicitud->isEmpty())
					{
						//solicitud no existe, es una nueva
						
					}else
					{
						$obj_log["descripcion"] = "solicitud ya fue registrada en el sistema.";
						array_push($logs_errores,$obj_log);
						continue;
					}
					
					$cantidad_registros_procesados++;
					$solicitud_arreglo = [
						"codigo" => $codigo_solicitud_ingresar,
						"entidad" => $nombre_entidad_encontrada,
						"tipo_solicitud" => $tipo_solicitud_obj[0]->nombre,
						"tipo_accion" => $tipo_accion->nombre,
						"fecha_solicitud" => $fecha_solicitud_date,
						"estado_solicitud" => $estado_solicitud_obj[0]->nombre,
						"idherramienta" => $idherramienta,
						"identidad" => $entidad[0]->identidad,
						"idtipo_solicitud" => $tipo_accion->idtipo_solicitud,
						"idtipo_solicitud_general" => $tipo_solicitud_obj[0]->idtipo_solicitud_general,
						"idestado_solicitud" => $estado_solicitud_obj[0]->idestado_solicitud,
						"asunto" => $asunto,
						"nombre_herramienta" => $nombre_herramienta,
					];

					$obj_log["descripcion"] = "solicitud correcta";
					array_push($logs_errores,$obj_log);

					array_push($data["resultados"],$solicitud_arreglo);
				}
				
				$array_log_text = SolicitudController::transformar_log_texto($logs_errores);
				$data["logs"] = $array_log_text;

				
				$data["cantidad_procesados"] = $cantidad_registros_procesados;
				$data["cantidad_total"] = $cantidad_registros_totales-2;				

				
				return View::make('Solicitudes/cargarSolicitudes',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function transformar_log_texto($logs)
	{
		$tamano_log = count($logs);
		$cadena = '';
		for($i=0;$i<$tamano_log;$i++)
		{
			$cadena=$cadena.$logs[$i]["descripcion"].'/'.$logs[$i]["accion"].'/'.$logs[$i]["entidad"].'/'.$logs[$i]["canal"].'/'.$logs[$i]["sector"].'?';
		}
		return $cadena;
	}

	public function obtener_tipo_solicitud($cadena)
	{
		$palabras = explode(' ',$cadena);
	    $tamano = count($palabras);
	    for($j=0;$j<$tamano;$j++)
	    {
	    	
	    	$palabra = strtolower($palabras[$j]);

	    	if(strpos($palabra,'crea') !== false) return 1;    		
	    	else if(strpos($palabra,'desb') !== false) return 2;
	    	else if(strpos($palabra,'eli') !== false) return 3;	
	    	else if(strpos($palabra,'inac') !== false) return 4;
	    	else if(strpos($palabra,'mod') !== false) return 5;
	    	else if(strpos($palabra,'rein') !== false) return 6;
	    	else if(strpos($palabra,'rese') !== false) return 7;
	    	else if(strpos($palabra,'tras') !== false) return 8;

	    }

	    return 0;
	}

	public function obtener_tipo_solicitud_2($cadena)
	{
		$palabras = explode(' ',$cadena);
	    $tamano = count($palabras);
	    $tipos = TipoSolicitud::listarTiposSolicitud()->get();
	    $cantidad_tipo = count($tipos);
	    $accion_encontrada = false;
	    $array_tipos = array();
	   for($i=0;$i<$tamano;$i++)
	    {
	    	
	    	$palabra = strtolower($palabras[$i]);

	    	$tamano_palabras = count($palabras);

	    	for($j=0;$j<$cantidad_tipo;$j++)
	    	{
	    		$equivalencias = EquivalenciaTipoSolicitud::buscarEquivalenciasPorIdTipoSolicitud($tipos[$j]->idtipo_solicitud)->get();
	    		$cantidad_equivalencias = count($equivalencias);
	    		for($z=0;$z<$cantidad_equivalencias;$z++)
	    		{
	    			similar_text(strtolower($palabra), strtolower($equivalencias[$z]->nombre_equivalencia),$porcentaje);
    				//si es mayor a 90% entonces contamos con una herramienta
    				

    				$obj_tipo = [
    					"porcentaje" => $porcentaje,
    					"palabra" => $palabra,
    					"idtipo_solicitud" =>$tipos[$j]->idtipo_solicitud,
    				];

    				array_push($array_tipos, $obj_tipo);

	    			if($porcentaje>90)
	    			{
	    				$accion_encontrada = true;
	    				//break;
	    				
	    			}	

	    		}

	    		if($accion_encontrada == true)
    			{
    				//return $tipos[$j];
	    		} 
	    	}

	    	
	    }


	    usort($array_tipos,array($this,'cmp') );
	    return $array_tipos[0]["idtipo_solicitud"];
	}


	public static function cmp($a, $b) 
	{
	    if ($a == $b) 
	 	       return 0;
	    						
	    return ($a < $b) ? 1 : -1;
	}

	public function obtener_herramienta($cadena,$herramientas)
	{
		$contador_aplicativos = 0;
    	$resultados = null;
    	$palabras = explode(' ',$cadena);
    	$cantidad_palabras = count($palabras);
    	$cantidad_herramientas = count($herramientas);
    	for($j=0;$j<$cantidad_palabras;$j++)
    	{
    		for($z=0;$z<$cantidad_herramientas;$z++)
    		{
    			$equivalencias = HerramientaEquivalencia::buscarEquivalenciasPorIdHerramienta($herramientas[$z]->idherramienta)->get();
    			$cantidad_equivalencias = count($equivalencias);
    			$aplicativo_encontrado = false;
    			for($w=0;$w<$cantidad_equivalencias;$w++)
    			{
    				similar_text(strtolower($palabras[$j]), strtolower($equivalencias[$w]->nombre_equivalencia),$porcentaje);
    				//si es mayor a 90% entonces contamos con una herramienta
	    			if($porcentaje>90)
	    			{
	    				$aplicativo_encontrado = true;
	    				break;
	    				
	    			}	
    			}

    			if($aplicativo_encontrado == true)
    			{
    				$contador_aplicativos++;
    				if($contador_aplicativos == 1)
	    				$resultados = $herramientas[$z]->idherramienta;
	    		}

    			
    		}
    	}   	

    	//return $resultados;
    	if($contador_aplicativos > 1)
    		return 0;
    	else if($contador_aplicativos == 1)
    		return $resultados;
    	else
    		return -1;

	}

	public function obtener_herramientas()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1 || $data["user"]->idrol == 2 ){
			// Check if the current user is the "System Admin"
			$cadena = Input::get('asunto');
			$herramientas =  Herramienta::listarHerramientas()->get();
			$contador_aplicativos = 0;
	    	$resultados = array();
	    	$palabras = explode(' ',$cadena);
	    	$cantidad_palabras = count($palabras);
	    	$cantidad_herramientas = count($herramientas);
	    	for($j=0;$j<$cantidad_palabras;$j++)
	    	{
	    		for($z=0;$z<$cantidad_herramientas;$z++)
	    		{
	    			$equivalencias = HerramientaEquivalencia::buscarEquivalenciasPorIdHerramienta($herramientas[$z]->idherramienta)->get();
	    			$cantidad_equivalencias = count($equivalencias);
	    			$aplicativo_encontrado = false;
	    			for($w=0;$w<$cantidad_equivalencias;$w++)
	    			{
	    				similar_text(strtolower($palabras[$j]), strtolower($equivalencias[$w]->nombre_equivalencia),$porcentaje);
	    				//si es mayor a 90% entonces contamos con una herramienta
		    			if($porcentaje>90)
		    			{
		    				$aplicativo_encontrado = true;
		    				break;
		    				
		    			}	
	    			}
	    			if($aplicativo_encontrado == true)
	    			{
	    				$contador_aplicativos++;
		    			array_push($resultados,$herramientas[$z]);
	    			}
	    			
	    		}
	    	}  

	    	if($resultados == null)
				return Response::json(array( 'success' => true,'herramientas'=>null),200);	    		

			return Response::json(array( 'success' => true,'herramientas'=>$resultados),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function descargar_logs()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1 || $data["user"]->idrol == 2){
				
				
				$date=new DateTime(); //this returns the current date time
				$result = $date->format('Y.m.d H.i.s');

				$value = Excel::create('Reporte Logs '.$result, function($excel) {
						$excel->sheet('Reporte', function($sheet)  {
							$sheet->row(1, array(
								     'N° Registro','Resultado','Acción','Nombre Entidad (Socio)','Nombre Canal','Nombre Sector'
								));
							$logs = Input::get('logs');
							
							$registros = explode('?',$logs);
							$tamano_logs = count($registros)-1;

							for($i = 0;$i<$tamano_logs;$i++){
								$partes = explode('/',$registros[$i]);
								$sheet->row($i+2, array(
								     $i+1, $partes[0], $partes[1],$partes[2],$partes[3],$partes[4]
								));
							}

						});
					})->download('xls');
				return true;
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}
	
	public function mostrar_solicitud($idsolicitud=null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1 || $data["user"]->idrol == 2) && $idsolicitud)
			{	
				$data["solicitud"] = Solicitud::find($idsolicitud);
				
				if($data["solicitud"]==null){
					return Redirect::to('solicitudes/listar_solicitudes');
				}

				$data["tipos_solicitud"] = TipoSolicitud::lists('nombre','idtipo_solicitud');
				$data["estados_solicitud"] = EstadoSolicitud::lists('nombre','idestado_solicitud');
				$data["tipos_solicitud_general"] = TipoSolicitudGeneral::lists('nombre','idtipo_solicitud_general');
				$data["entidad"] = Entidad::find($data["solicitud"]->identidad);

				$data["asignacion"] = Asignacion::buscarPorIdSolicitud($data["solicitud"]->idsolicitud)->get();

				if($data["asignacion"]==null){
					return Redirect::to('solicitudes/listar_solicitudes');
				}

				$usuario_asignado_actual = UsuariosXAsignacion::buscarUsuarioActual($data["asignacion"][0]->idasignacion)->get();

				if($usuario_asignado_actual==null){
					return Redirect::to('solicitudes/listar_solicitudes');
				}				

				$data["usuario_asignado"] = User::withTrashed()->find($usuario_asignado_actual[0]->idusuario_asignado);

				$data["herramienta"] = Herramienta::find($data["solicitud"]->idherramienta);

				

				return View::make('Solicitudes/mostrarSolicitud',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function mostrar_usuarios_disponibles_reasignacion()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}
		$id = Auth::id();
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"] = Session::get('user');
		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			$idsolicitud = Input::get('idsolicitud');

			$solicitud = Solicitud::find($idsolicitud);

			if($solicitud == null)
				return Response::json(array( 'success' => false),200);	

			$entidad = Entidad::find($solicitud->identidad);
			$canal = Canal::find($entidad->idcanal);
			$sector = Sector::find($canal->idsector);

			$idherramienta = $solicitud->idherramienta;
			$idaccion = $solicitud->idtipo_solicitud;

			if($idherramienta == 39){
				//herramienta representada para "VARIOS"
				$usuarios = User::buscarUsuariosAsignacionPorSector($sector->idsector);	
				
				if(is_array($usuarios) == true) //hay usuarios
				{
					return Response::json(array( 'success' => true,'usuarios'=>$usuarios),200);
				}else
				{
					return Response::json(array( 'success' => true,'usuarios'=>null),200);
				}
				
			}else{

				//como solo tiene una sola herramienta, buscamos a los usuarios especializados y que tengan menos solicitudes pendientes y en proceso.

				$usuarios = User::buscarUsuariosAsignacionPorHerramienta($idherramienta,$idaccion);	

				if(is_array($usuarios) == true) //hay usuarios
				{
					return Response::json(array( 'success' => true,'usuarios'=>$usuarios),200);
				}else
				{
					return Response::json(array( 'success' => true,'usuarios'=>null),200);
				}			
			}	
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function submit_reasignar_solicitud()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				$idasignacion = Input::get('asignacion_id');
				$idsolicitud = Input::get('solicitud_id');

				$attributes = array(
					'usuario_disponible' => 'Usuario Disponible',
					'motivo_asignacion' => 'Motivo Asignación',
				);

				$messages = array();

				$rules = array(
					'usuario_disponible' => 'required',
					'motivo_asignacion' => 'alpha_num_spaces_slash_dash_enter|max:200',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('solicitudes/mostrar_solicitud/'.$idsolicitud)->withErrors($validator)->withInput(Input::all());
				}else{
					$idusuario_reasignacion = Input::get('usuario_disponible');
					$motivo_asignacion = Input::get('motivo_asignacion');

					$usuariosxasignacion = UsuariosXAsignacion::buscarPorIdAsignacion($idasignacion)->get();

					if($usuariosxasignacion == null || $usuariosxasignacion->isEmpty()){
						Session::flash('error', 'No es posible reasignar una solicitud que no posee usuario asignado.');
						return Redirect::to('solicitudes/mostrar_solicitud/'.$idsolicitud);
					}
					
					$tamano = count($usuariosxasignacion);
					for($i=0; $i<$tamano; $i++)
					{
						$usuariosxasignacion[$i]->estado_usuario_asignado = 0;
						$usuariosxasignacion[$i]->save();
					}
					
					$nuevo_usuarioxasignacion = new UsuariosXAsignacion;
					$nuevo_usuarioxasignacion->idusuario_asignado = $idusuario_reasignacion;
					$nuevo_usuarioxasignacion->idasignacion = $idasignacion;
					$nuevo_usuarioxasignacion->motivo_asignacion = $motivo_asignacion;
					$nuevo_usuarioxasignacion->estado_usuario_asignado = 1; //1: activo 0: inactivado (se hace reasignacion)
					$nuevo_usuarioxasignacion->iduser_created_by = $data["user"]->id;
					$nuevo_usuarioxasignacion->save();

					Session::flash('message', 'Se realizó correctamente la reasignación.');
					
					return Redirect::to('solicitudes/mostrar_solicitud/'.$idsolicitud);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function crear_solicitud()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				$data["sectores"] = Sector::lists('nombre','idsector');
				$data["tipos_solicitud"] = TipoSolicitud::lists('nombre','idtipo_solicitud');
				$data["estados_solicitud"] = EstadoSolicitud::lists('nombre','idestado_solicitud');
				$data["tipos_solicitud_general"] = TipoSolicitudGeneral::lists('nombre','idtipo_solicitud_general');
				return View::make('Solicitudes/crearSolicitud',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_crear_solicitud()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				// Validate the info, create rules for the inputs
				
				$attributes = array(
					'codigo_solicitud' => 'Código de Solicitud',
					'fecha_solicitud' => 'Fecha de la Solicitud',
					'tipo_solicitud_general' => 'Tipo de Solicitud',
					'estado_solicitud' => 'Estado de la Solicitud',
					'tipo_solicitud' => 'Tipo de Solicitud (Acción)',
					'asunto' => 'Asunto',
					'sector' => 'Sector',
					'canal' => 'Canal',
					'entidad' => 'Entidad',
					'herramienta' => 'Aplicativo',
				);

				$messages = array();

				$rules = array(
					'codigo_solicitud' => 'numeric|digits:6|required|unique:solicitud,codigo_solicitud',
					'fecha_solicitud' => 'required',
					'tipo_solicitud_general' => 'required',
					'estado_solicitud' => 'required',
					'tipo_solicitud' => 'required',
					'asunto' => 'alpha_num_spaces_slash_dash|max:200|required',
					'sector' => 'required',
					'canal' => 'required',
					'entidad' => 'required',
					'herramienta' => 'required',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('solicitudes/crear_solicitud')->withErrors($validator)->withInput(Input::all());
				}else{
					
					$codigo_solicitud = Input::get('codigo_solicitud');
					$fecha_solicitud = date('Y-m-d H:i:s',strtotime(Input::get('fecha_solicitud')));
					$idtipo_solicitud_general = Input::get('tipo_solicitud_general');
					$idestado_solicitud = Input::get('estado_solicitud');
					$idaccion = Input::get('tipo_solicitud');
					$asunto = Input::get('asunto');
					$idsector = Input::get('sector');
					$idcanal = Input::get('canal');
					$identidad = Input::get('entidad');
					$idherramienta = Input::get('herramienta');

					$solicitud = new Solicitud;
					$solicitud->codigo_solicitud = $codigo_solicitud;
					$solicitud->fecha_solicitud = $fecha_solicitud;
					$solicitud->asunto = $asunto;
					$solicitud->idherramienta = $idherramienta;
					$solicitud->identidad = $identidad;
					$solicitud->idtipo_solicitud_general = $idtipo_solicitud_general;
					$solicitud->idtipo_solicitud = $idaccion;

					if($idestado_solicitud != 3)
					{
						Session::flash('error', 'Solo se pueden registrar solicitudes en estado Pendiente.');				
						return Redirect::to('solicitudes/crear_solicitud');
					}

					$solicitud->idestado_solicitud = $idestado_solicitud;
					$solicitud->iduser_created_by = $data["user"]->id;

					//BUSCANDO EL SLA

					$sla = TipoSolicitudXSla::buscarSlaPorSectorHerramientaAccion($idsector,$idherramienta,$idaccion)->get();

					if($sla==null || $sla->isEmpty())
					{
						Session::flash('error', 'No existe un SLA en el sistema para realizar la creación.');				
						return Redirect::to('solicitudes/crear_solicitud');
					}

					$solicitud->idsla = $sla[0]->idsla;

					//BUSCANDO AL USUARIO PARA ASIGNAR
					$usuarios = null;

					//En caso solo se tenga varias herramientas, se debe buscar a los usuarios del sector, que tengan menos solicitudes pendientes y en proceso.
					$usuario_apto = null;
					if($idherramienta == 39){
						//herramienta representada para "VARIOS"
						
						/*$usuarios = User::buscarUsuariosAsignacionPorSector($idsector);	
					
						if(is_array($usuarios) == true) //hay usuarios
						{
							$usuario_apto = User::find($usuarios[0]->id_usuario);
						}else
						{
							$usuario_apto = null;
						}*/

						$usuario_apto = SolicitudController::buscarUsuarioAptoPorSector($idsector);
						
					}else{

						//como solo tiene una sola herramienta, buscamos a los usuarios especializados y que tengan menos solicitudes pendientes y en proceso.

						/*$usuarios = User::buscarUsuariosAsignacionPorHerramienta($idherramienta,$idaccion);	

						if(is_array($usuarios) == true) //hay usuarios
						{
							$usuario_apto = User::find($usuarios[0]->id_usuario);
						}else
						{
							$usuario_apto = null;
						}*/

						$usuario_apto = SolicitudController::buscarUsuarioAptoPorHerramienta($idherramienta,$idaccion);

						if($usuario_apto == null)
						{
							$usuario_apto = SolicitudController::buscarUsuarioAptoPorSector($idsector);
						}	
						
					}

					
					if($usuario_apto == null){
						Session::flash('error', 'No se cuenta con un usuario para asignar.');				
						return Redirect::to('solicitudes/crear_solicitud');
					}
					
					$solicitud->save();

					//ASIGNACION
					$asignacion = new Asignacion;
					$asignacion->fecha_asignacion = date('Y-m-d H:i:s');
					$asignacion->idestado_asignacion = 2;//Realizado
					$asignacion->iduser_created_by = $data["user"]->id;
					$asignacion->idsolicitud = $solicitud->idsolicitud;
					$asignacion->save();

					
					$usuariosxasignacion = new UsuariosXAsignacion;
					$usuariosxasignacion->idusuario_asignado = $usuario_apto->id;
					$usuariosxasignacion->idasignacion = $asignacion->idasignacion;
					$usuariosxasignacion->motivo_asignacion = "Primera asignación";
					$usuariosxasignacion->estado_usuario_asignado = 1; //1: activo 0: inactivado (se hace reasignacion)
					$usuariosxasignacion->iduser_created_by = $data["user"]->id;
					$usuariosxasignacion->save();

					
					Session::flash('message', 'Se creó la solicitud con éxito.');					
					return Redirect::to('solicitudes/listar_solicitudes');
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_anular_solicitud()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2){
				// Validate the info, create rules for the inputs
				$idsolicitud = Input::get('solicitud_id');

				$attributes = array(
					'motivo_anulacion' => 'Motivo Anulación',
				);

				$messages = array();

				$rules = array(
					'motivo_anulacion' => 'alpha_num_spaces_slash_dash_enter|max:200',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('solicitudes/mostrar_solicitud/'.$idsolicitud)->withErrors($validator)->withInput(Input::all());
				}else{
					
					$solicitud = Solicitud::find($idsolicitud);
					$solicitud->motivo_anulacion = Input::get('motivo_anulacion');
					$solicitud->idestado_solicitud = 6;

					$solicitud->save();

					Session::flash('message', 'Se realizó correctamente la anulación.');
					
					return Redirect::to('solicitudes/mostrar_solicitud/'.$idsolicitud);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}
	

	public function buscarUsuarioAptoPorSector($idsector)
	{
		$usuarios = User::buscarUsuariosAsignacionPorSector($idsector);	
				
		if(is_array($usuarios) == true) //hay usuarios
		{
			$usuario_apto = User::find($usuarios[0]->id_usuario);
		}else
		{
			$usuario_apto = null;
		}

		return $usuario_apto;
	}

	public function buscarUsuarioAptoPorHerramienta($idherramienta,$idaccion)
	{
		$usuarios = User::buscarUsuariosAsignacionPorHerramienta($idherramienta,$idaccion);	

		if(is_array($usuarios) == true) //hay usuarios
		{
			$usuario_apto = User::find($usuarios[0]->id_usuario);
		}else
		{
			$usuario_apto = null;
		}

		return $usuario_apto;	
	}
}
