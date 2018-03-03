<?php

class UsuariosObservadosVenaController extends BaseController {

	public function cargar_usuarios_observados_vena()
	{
		$data["inside_url"] = Config::get('app.inside_url');
		$data["user"]= Session::get('user');

		$data["cantidad_total_observados"] = null;
		$data["cantidad_total_vena"] = null;

		$data["cantidad_procesados_observados"] = null;
		$data["cantidad_procesados_vena"] = null;

		$data["vista_previa_ejecutado_observados"] = 0;
		$data["vista_previa_ejecutado_vena"] = 0;

		$data["resultados_observados"] = array();
		$data["resultados_vena"] = array();

		$data["usuarios_observados_ya_cargados"] = 0;
		$data["usuarios_vena_ya_cargados"] = 0;

		//validar si existen usuarios cargados en el día de hoy:
		$fecha_actual = date('Y-m-d');
		$buscar_usuario_observado = UsuarioObservado::listarUsuariosObservadosUltimo()->first();

		$data["fecha_registro_usuarios_observados"] = null;
		$data["fecha_registro_usuarios_vena"] = null;

		if($buscar_usuario_observado != null){
			$data["usuarios_observados_ya_cargados"] = 1;
			$data["fecha_registro_usuarios_observados"] = date('d-m-Y H:i:s',strtotime($buscar_usuario_observado->fecha_registro));
		}


		$buscar_usuario_vena = UsuarioVena::listarUsuariosVenaUltimo($fecha_actual)->first();

		if($buscar_usuario_vena != null){
			$data["usuarios_vena_ya_cargados"] = 1;
			$data["fecha_registro_usuarios_vena"] = date('d-m-Y H:i:s',strtotime($buscar_usuario_vena->fecha_registro));
		}

		return View::make('Mantenimientos/UsuariosObservados_Vena/cargarUsuariosObservadosVena',$data);
	}

	public function submit_cargar_observados()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1){
			
				$data["cantidad_total_observados"] = null;
				$data["cantidad_total_vena"] = null;

				$data["cantidad_procesados_observados"] = null;
				$data["cantidad_procesados_vena"] = null;

				$data["logs_observados"] = null;
				$data["logs_vena"] = null;		

				$data["vista_previa_ejecutado_observados"] = 0;
				$data["vista_previa_ejecutado_vena"] = 0;		

				//Lectura del archivo
				$file_name = $_FILES['file']['tmp_name'];

				if(strcmp($file_name,'')==0){
					Session::flash('error', 'Archivo sin adjuntar.');
					return Redirect::to('usuarios_observados_vena/cargar_usuarios_observados_vena');	
				}
				
				/*$file_handle = fopen($file_name, 'r');
			    
			    while (!feof($file_handle) ) {
			        $line_of_text[] = fgetcsv($file_handle, 1024,"|");
			    }
			    fclose($file_handle);*/

			    $lista_observados = Excel::load($file_name)->get();

			    /*echo '<pre>';
			    var_dump($lista_observados);
			    echo '</pre>';*/

			    //inicio del algoritmo
			    
				$cantidad_registros_totales = count($lista_observados);
				$cantidad_registros_procesados = 0;

				$data["resultados_observados"] = array();
				$data["resultados_vena"] = null;

				
				//VALIDACIONES INICIALES
				if($cantidad_registros_totales == 0)
				{
					Session::flash('error', 'Archivo vacío.');
					return Redirect::to('usuarios_observados_vena/cargar_usuarios_observados_vena');
				}

				if(count($lista_observados[0]) != 6 )
				{
					Session::flash('error', 'No es posible realizar la lectura del archivo puesto que la cantidad de campos no es correcta.');
					return Redirect::to('usuarios_observados_vena/cargar_usuarios_observados_vena');
				}

				$logs_errores = array();
				
				for($i = 0; $i < $cantidad_registros_totales; $i++)
				{
					//2.1. Leer Valores
					$fecha_bloqueo = $lista_observados[$i][0];
					$sistema = $lista_observados[$i][1];
					$codigo_usuario = $lista_observados[$i][2];
					$nombre_usuario = $lista_observados[$i][3];
					$tipo_documento = $lista_observados[$i][4];
					$numero_documento = $lista_observados[$i][5];

					//algunas variables adicionales
					$array_herramientas = array();
					$idherramienta = null;
					//2.2. Validar datos vacíos y válidos

					//CREACION DEL ARREGLO LOG
					$obj_log = [
						"numero" => $i,
						"descripcion" => null,
						"herramienta" => "no reconocido",
					];
					
					$array_log_text = '';

					//ALGORITMO DE VALIDACION

					//1. VALIDACION FECHA DE BLOQUEO
					if( $fecha_bloqueo != null && strcmp($fecha_bloqueo,'') != 0 )
					{
						if(DateTime::createFromFormat('Y-m-d H:i:s', $fecha_bloqueo) == false)
						{
							$obj_log["descripcion"] = "La fecha de bloqueo no cuenta con el formato de fecha correcto";
							array_push($logs_errores,$obj_log);
							//FECHA CON FORMATO ERRADO
							continue; //(LOGS)
						} 
						else{	
							$fecha_bloqueo_date = $fecha_bloqueo;
						}
					}else //NO PROCEDE
					{
						$obj_log["descripcion"] = "El campo Fecha de Bloqueo está vacío.";
						array_push($logs_errores,$obj_log);
						continue; //(LOGS)
					}

					if($numero_documento == null || strcmp($numero_documento, '') == 0 || strcmp($numero_documento, '99999999')==0)
					{
						continue;
					}


					$cantidad_registros_procesados++;
					$observados_arreglos = [
						"fecha_bloqueo" => $fecha_bloqueo_date,
						"herramienta" => $sistema,
						//"codigo_usuario" => $codigo_usuario,
						//"nombre_usuario" => $nombre_usuario,
						//"tipo_documento" => $tipo_documento,
						"numero_documento" => $numero_documento,
					];

					array_push($data["resultados_observados"],$observados_arreglos);
				}
				
				$data["vista_previa_ejecutado_observados"] = 1;
				$data["vista_previa_ejecutado_vena"] = 0;
				
				$data["cantidad_procesados_observados"] = $cantidad_registros_procesados;
				$data["cantidad_total_observados"] = $cantidad_registros_totales;

				//validar si existen usuarios cargados en el día de hoy:
				$fecha_actual = date('Y-m-d');

				$data["usuarios_observados_ya_cargados"] = 0;

				$data["usuarios_vena_ya_cargados"] = 0;

				//validar si existen usuarios cargados en el día de hoy:
				$fecha_actual = date('Y-m-d');
				$buscar_usuario_observado = UsuarioObservado::listarUsuariosObservadosUltimo($fecha_actual)->first();

				$data["fecha_registro_usuarios_observados"] = null;
				$data["fecha_registro_usuarios_vena"] = null;

				if($buscar_usuario_observado != null){
					$data["usuarios_observados_ya_cargados"] = 1;
					$data["fecha_registro_usuarios_observados"] = date('d-m-Y H:i:s',strtotime($buscar_usuario_observado->fecha_registro));
				}


				$buscar_usuario_vena = UsuarioVena::listarUsuariosVenaUltimo($fecha_actual)->first();

				if($buscar_usuario_vena != null){
					$data["usuarios_vena_ya_cargados"] = 1;
					$data["fecha_registro_usuarios_vena"] = date('d-m-Y H:i:s',strtotime($buscar_usuario_vena->fecha_registro));
				}	

				return View::make('Mantenimientos/UsuariosObservados_Vena/cargarUsuariosObservadosVena',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}
	
	

	public function submit_cargar_vena()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1){
			
				$data["cantidad_total_observados"] = null;
				$data["cantidad_total_vena"] = null;

				$data["cantidad_procesados_observados"] = null;
				$data["cantidad_procesados_vena"] = null;

				$data["vista_previa_ejecutado_observados"] = 0;
				$data["vista_previa_ejecutado_vena"] = 0;

				//Lectura del archivo
				$file_name = $_FILES['file']['tmp_name'];

				if(strcmp($file_name,'')==0){
					Session::flash('error', 'Archivo sin adjuntar.');
					return Redirect::to('usuarios_observados_vena/cargar_usuarios_observados_vena');	
				}
				
				/*$file_handle = fopen($file_name, 'r');
			    
			    while (!feof($file_handle) ) {
			        $line_of_text[] = fgetcsv($file_handle, 1024,"|");
			    }
			    fclose($file_handle);*/

			    //inicio del algoritmo
			    $lista_vena =  Excel::load($file_name)->get();
				$cantidad_registros_totales = count($lista_vena);
				$cantidad_registros_procesados = 0;

				$data["resultados_observados"] = null;
				$data["resultados_vena"] = array();

				
				//VALIDACIONES INICIALES
				if($cantidad_registros_totales == 0)
				{
					Session::flash('error', 'Archivo vacío.');
					return Redirect::to('usuarios_observados_vena/cargar_usuarios_observados_vena');
				}

				if(count($lista_vena[0]) < 23)
				{
					Session::flash('error', 'No es posible realizar la lectura del archivo puesto que la cantidad de campos no es correcta.');
					return Redirect::to('usuarios_observados_vena/cargar_usuarios_observados_vena');
				}

				
				$logs_errores = array();
				
				
				for($i = 0; $i < $cantidad_registros_totales; $i++)
				{
					//2.1. Leer Valores
					$numero_documento = $lista_vena[$i][2];
					$fecha_bloqueo = $lista_vena[$i][12];
					$motivo = $lista_vena[$i][14];


					//CREACION DEL ARREGLO LOG
					$obj_log = [
						"numero" => $i,
						"descripcion" => null,
						"motivo de veto" => null,
					];
					
					$array_log_text = '';

					//ALGORITMO DE VALIDACION

					//1. VALIDACION FECHA DE BLOQUEO
					if(strcmp($numero_documento,'') == 0)
					{
						$obj_log["descripcion"] = "El campo Numero de Documento está vacío.";
						array_push($logs_errores,$obj_log);
						continue; //(LOGS)
					}


					$cantidad_registros_procesados++;
					$observados_arreglos = [
						"numero_documento" => $numero_documento,
						"fecha_bloqueo" => $fecha_bloqueo,
						//"codigo_usuario" => $codigo_usuario,
						//"nombre_usuario" => $nombre_usuario,
						//"tipo_documento" => $tipo_documento,
						"motivo" => $motivo,
					];

					$obj_log["descripcion"] = "Registro de usuario vena correcta";
					array_push($logs_errores,$obj_log);

					array_push($data["resultados_vena"],$observados_arreglos);
				}
				
				$data["vista_previa_ejecutado_observados"] = 0;
				$data["vista_previa_ejecutado_vena"] = 1;
				
				$data["cantidad_procesados_vena"] = $cantidad_registros_procesados;
				$data["cantidad_total_vena"] = $cantidad_registros_totales;

				$data["usuarios_vena_ya_cargados"] = 0;
				$data["usuarios_observados_ya_cargados"] = 0;


				//validar si existen usuarios cargados en el día de hoy:
				
				$fecha_actual = date('Y-m-d');
				$buscar_usuario_observado = UsuarioObservado::buscarUsuarioCargadoHoy($fecha_actual)->first();

				$data["fecha_registro_usuarios_observados"] = null;
				$data["fecha_registro_usuarios_vena"] = null;

				if($buscar_usuario_observado != null){
					$data["usuarios_observados_ya_cargados"] = 1;
					$data["fecha_registro_usuarios_observados"] = date('d-m-Y H:i:s',strtotime($buscar_usuario_observado->fecha_registro));
				}


				$buscar_usuario_vena = UsuarioVena::buscarUsuarioCargadoHoy($fecha_actual)->first();

				if($buscar_usuario_vena != null){
					$data["usuarios_vena_ya_cargados"] = 1;
					$data["fecha_registro_usuarios_vena"] = date('d-m-Y H:i:s',strtotime($buscar_usuario_vena->fecha_registro));
				}	
				
				return View::make('Mantenimientos/UsuariosObservados_Vena/cargarUsuariosObservadosVena',$data);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_base_observados()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				
				// 0. Recepcionar información:

				$datos = Input::get('datos');
				$cantidad_registros = count($datos);

				UsuarioObservado::truncate();

				// Por cada solicitud realizar los pasos 2 y 3

				for($i=0; $i<$cantidad_registros; $i++)
				{
					
					$arr_datos = explode( "?" , $datos[$i] );
					
					$fechas_bloqueo = $arr_datos[0];
					$herramientas = $arr_datos[1];
					$numeros_documento = $arr_datos[2];

					$usuario_observado = new UsuarioObservado;
					$usuario_observado->numero_documento = $numeros_documento;
					$usuario_observado->fecha_bloqueo = $fechas_bloqueo;
					$usuario_observado->nombre_herramienta = $herramientas;
					$usuario_observado->fecha_registro = date('Y-m-d H:i:s');
					$usuario_observado->save();
				}

				

				Session::flash('message','Se realizó la carga de la lista de usuarios observados con éxito');
						
				return Redirect::to('usuarios_observados_vena/cargar_usuarios_observados_vena');
				
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_base_vena()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				
				// 0. Recepcionar información:

				$datos = Input::get('datos');
				$cantidad_registros = count($datos);

				UsuarioVena::truncate();

				// Por cada solicitud realizar los pasos 2 y 3

				for($i=0; $i<$cantidad_registros; $i++)
				{
					
					$arr_datos = explode( "?" , $datos[$i] );
					
					$numeros_documento = $arr_datos[0];
					$fecha_bloqueo = $arr_datos[1];
					$motivo = $arr_datos[2];

					$usuario_vena = new UsuarioVena;
					$usuario_vena->numero_documento = $numeros_documento;
					$usuario_vena->fecha_bloqueo = $fecha_bloqueo;
					$usuario_vena->motivo = $motivo;
					$usuario_vena->fecha_registro = date('Y-m-d H:i:s');
					$usuario_vena->save();
				}

				Session::flash('message','Se realizó la carga de la lista de usuarios vena con éxito');
						
				return Redirect::to('usuarios_observados_vena/cargar_usuarios_observados_vena');
				
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}


}
