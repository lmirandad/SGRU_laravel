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
				$data["fecha_solicitud"] = null;
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
				$logs_errores_keys = array();
				
				for($i = 1; $i < $cantidad_registros_totales-1; $i++)
				{
					array_push($logs_errores_keys,$i);
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
					//2.2. Validar datos vacíos y válidos
					
					//CODIGO ENTIDAD y NOMBRE ENTIDAD
					if(strcmp($codigo_entidad,'') != 0)
					{
						//validar si existe el codigo
						$entidad = Entidad::buscarPorCodigoEntidad($codigo_entidad)->get();
						if($entidad == null || $entidad->isEmpty()){
							//NO PROCEDE
							array_push($logs_errores,"La entidad ".$nombre_entidad." del registro no existe");
							continue; //(LOGS)	
						} 
						else{
							$nombre_entidad_encontrada = $entidad[0]->nombre;
							if( strcmp($nombre_entidad_encontrada, $nombre_entidad) != 0 ){
								//NO PROCEDE
								array_push($logs_errores,"La entidad ".$nombre_entidad." no coincide con el código registrado en el sistema.");
								continue; //(LOGS)
							} 
						}
					}else{
						//NO PROCEDE
						array_push($logs_errores,"El campo Entidad está vacío.");
						continue; //(LOGS)
					}
					//TIPO SOLICITUD GRAL
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
						array_push($logs_errores,"El tipo de solicitud no existe.");
						//NO PROCEDE
						continue; //(LOGS)
					}

					//CODIGO SOLICITUD
					//Validar si el codigo es vacío y posteriomente se valida si es numérico
					if(strcmp($codigo_solicitud,'') == 0 || !ctype_digit($codigo_solicitud))
					{
						array_push($logs_errores,"El código de solicitud no existe o no es numérico");
						continue; //(LOGS)
					}
					else
						$codigo_solicitud_ingresar = (int)$codigo_solicitud;

					//ASUNTO
					if(strcmp($asunto,'') == 0){
						array_push($logs_errores,"El campo asunto no existe.");
						continue; //(LOGS)
					}

					//FECHA SOLICITUD
					if(strcmp($fecha_solicitud,'') != 0)
					{
						if(DateTime::createFromFormat('d/m/Y', $fecha_solicitud) == false)
						{
							array_push($logs_errores,"La fecha de solicitud no cuenta con el formato de fecha correcto");
							//FECHA CON FORMATO ERRADO
							continue; //(LOGS)
						} 
						else{	
							$partes = explode("/",$fecha_solicitud);
							$fecha_solicitud_date = date('Y-m-d',strtotime($partes[2]."-".$partes[1]."-".$partes[0]));
						}
					}else //NO PROCEDE
					{
						array_push($logs_errores,"El campo Fecha de Solicitud está vacío.");
						continue; //(LOGS)
					}
					//ESTADO SOLICITUD
					if(strcmp($estado_solicitud,'') != 0)
					{	//validar si el estado de solicitud existe
						$estado_solicitud_obj = EstadoSolicitud::buscarPorNombre($estado_solicitud)->get();
						if($estado_solicitud_obj == null || $estado_solicitud_obj->isEmpty()) //NO PROCEDE
						{
							array_push($logs_errores,"El estado de la solicitud no se encuentra registrado en el sistema.");
							continue; //(LOGS)
						}

						if($estado_solicitud_obj[0]->idestado_solicitud != 3){
							array_push($logs_errores,"La solicitud ya se encuentra procesada. No se asignan solicitudes que ya están en proceso.");
							continue;
						}
					}else //NO PROCEDE
					{
						array_push($logs_errores,"El campo Estado de Solicitud está vacío.");
						continue; //(LOGS)
					}

					//FECHA ESTADO
					if(strcmp($fecha_estado,'') != 0)
					{
						if(DateTime::createFromFormat('d/m/Y', $fecha_estado) == false) //FECHA CON FORMATO ERRADO
						{
							array_push($logs_errores,"La fecha de estado no cuenta con el formato de fecha correcto.");
							continue; //(LOGS)
						}
						else{
							$partes = explode("/",$fecha_estado);
							$fecha_estado_date = date('Y-m-d',strtotime($partes[2]."-".$partes[1]."-".$partes[0]));
						}
					}else //NO PROCEDE (LOGS)
					{	
						array_push($logs_errores,"El campo Fecha de Estado está vacío.");				
						continue;
					}

					
					//2.4 Se debe validar el asunto

					//VALIDACION TIPO DE ACCION
					$idtipo_accion = SolicitudController::obtener_tipo_solicitud($asunto);
					if($idtipo_accion == 0){
						array_push($logs_errores,"La solicitud no describe ninguna acción registrada en el sistema.");
						continue; //NO PROCEDE porque no existe la creacion (NO SE ACEPTARA MAS DE UNA ACCION) - LOGS
					}
					else		
						$tipo_accion = TipoSolicitud::find($idtipo_accion);
					
					//VALIDACION DE LA APLICACION
					$array_herramientas = SolicitudController::obtener_herramienta($asunto,$herramientas);
					$codigos_herramientas = '';
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

						

					}else if(count($array_herramientas) == 1)
					{
						//solo existe 1
						$nombre_herramienta = $array_herramientas[0]->nombre;
						$codigos_herramientas = $array_herramientas[0]->idherramienta;
					}else
					{
						//quiere decir que no existen herramientas
						$nombre_herramienta = "NO DETECTADO";
					}

					//2.5 Luego de estas validaciones se deberá revisar si la solicitud ya existe 
					$solicitud = Solicitud::buscarPorCodigoSolicitud($codigo_solicitud_ingresar)->get();
					if($solicitud == null || $solicitud->isEmpty())
					{
						//solicitud no existe, es una nueva
						
					}else
					{
						array_push($logs_errores,"solicitud ya fue registrada en el sistema.");
						continue;
						//Esta solicitud ya existe en el sistema
						//¿Si tiene diferente estado que en el portal de canales se debe actualizar el estado?.
						/****************POR CONFIRMAR*************************************************
						if($solicitud->idestado_solicitud != $estado_solicitud_obj->idestado_solicitud)
						{
							$solicitud->idestado_solicitud = $estado_solicitud_obj->idestado_solicitud;
							$solicitud->save();
						}
						*******************************************************************************/
					}
					
					$cantidad_registros_procesados++;
					$solicitud_arreglo = [
						"codigo" => $codigo_solicitud_ingresar,
						"entidad" => $nombre_entidad_encontrada,
						"tipo_solicitud" => $tipo_solicitud_obj[0]->nombre,
						"tipo_accion" => $tipo_accion->nombre,
						"fecha_solicitud" => $fecha_solicitud_date,
						"estado_solicitud" => $estado_solicitud_obj[0]->nombre,
						"nombre_herramienta" => $nombre_herramienta,
						"identidad" => $entidad[0]->identidad,
						"idtipo_solicitud" => $tipo_accion->idtipo_solicitud,
						"idtipo_solicitud_general" => $tipo_solicitud_obj[0]->idtipo_solicitud_general,
						"idestado_solicitud" => $estado_solicitud_obj[0]->idestado_solicitud,
						"asunto" => $asunto,
						"codigos_herramientas" => $codigos_herramientas,
					];

					array_push($logs_errores,"solicitud correcta");

					array_push($data["resultados"],$solicitud_arreglo);
				}
				
				$data["logs"] = array_combine($logs_errores_keys,$logs_errores);
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

	public function obtener_herramienta($cadena,$herramientas)
	{
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

    	return $resultados;
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
				
				return Excel::create('Reporte Logs '.$result, function($excel) {
						$excel->sheet('Reporte', function($sheet) {
							$sheet->row(1, array(
								     'N° Registro','Resultado'
								));
							$logs = Input::get('logs');
							$tamano_logs = count($logs);
							for($i = 0;$i<$tamano_logs;$i++){
								$sheet->row($i+2, array(
								     $i+1, $logs[$i],
								));
							}

						});
					})->download('xls');
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

				$data["usuario_asignado"] = User::find($data["asignacion"][0]->iduser_asignado);

				$data["herramientas"] = SolicitudXHerramienta::buscarHerramientasPorIdSolicitud($idsolicitud)->get();

				

				return View::make('Solicitudes/mostrarSolicitud',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	
}
