<?php

class MenuPrincipalGestorPlanillaController extends BaseController {

	public function home_gestor_planilla()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Admin Planilla
			if($data["user"]->idrol == 6){
				
				$data["cantidad_registros"] = 0;
				$data["cantidad_registros_procesados"] = 0;
				$data["logs"] = null;
				$data["registros"] = null;
				$data["archivo_subido"] = false;
				
				//validar si el gestor ya hizo una carga:
				$mes = date('m');
				$anho = date('Y');

				$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

				$nombre_mes = $anho.' - '.$meses[$mes-1];

				$carga_mes = CargaArchivoPlanilla::listarCargasArchivoPlanillaMesUsuario($mes,$anho,$data["user"]->id)->get();

				if($carga_mes == null || $carga_mes->isEmpty())
				{
					Session::flash('info','Base de Personal Autorizado '.$nombre_mes.' pendiente de cargar');
					$data["base_cargada"] = false;
					
				}else
				{
					$data["base_cargada"] = false;
					$fecha_carga_archivo = date('d-m-Y H:i:s',strtotime($carga_mes[0]->fecha_carga_archivo));
					Session::flash('info','Base de Personal Autorizado '.$nombre_mes.' cargada el '.$fecha_carga_archivo);
				}

				return View::make('Planilla/GestorPlanilla/menuPrincipalGestorPlanilla',$data);
			}else
				return View::make('error/error',$data);
		}else{
			return View::make('error/error',$data);
		}
		
	}

	public function descargar_plantilla()
	{

		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Admin Planilla
			if($data["user"]->idrol == 6){
				
				$ruta = '../res/formato_planilla/Lista de personal autorizado v1.1.xlsm';
				$headers = array(
		              'Content-Type',mime_content_type($ruta),
		            );
		        return Response::download($ruta,basename(Input::get('nombre_archivo')),$headers);

			}else
				return View::make('error/error',$data);
		}else{
			return View::make('error/error',$data);
		}

		
	}

	public function probar_carga()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 6){
			
				
				//Lectura del archivo
				$file_name = $_FILES['file']['tmp_name'];

				 if($file_name == null )
			    {
			    	Session::flash('error','No hay archivo adjunto');
			    	return Redirect::to('/principal_gestor_planilla');
			    }

			    $resultado = Excel::load($file_name)->get()[0];

			 	$cantidad_registros = count($resultado);
			 	$logs_errores = array();
			 	$array_registros = array();

			 	$cantidad = 11;
			    if(count($resultado[0]) < $cantidad || count($resultado[0]) > $cantidad )
			    	return Redirect::to('/principal_gestor_planilla')->with('error','La cantidad de columnas del archivo adjuntado no coincide con el estandar.');

			    $cantidad_registros_procesados = 0;
			    $registros_reales = 0;

			    //primera validacion total de todos los codigos
			    for($i = 0; $i < $cantidad_registros; $i++)
			    {
			    	
			    	//Crear objeto para el log

					$obj_log = [
						"numero" => $i,
						"descripcion" => null,
					];

					$tipo_documento = $resultado[$i][0];

					if($tipo_documento == null || strcmp($tipo_documento, "") == 0)
					{
						//rechazar
						$obj_log["descripcion"] = 'Registro en Blanco o Campo TIPO DOCUMENTO no seleccionado';
						array_push($logs_errores, $obj_log);
						continue;
					}

					$registros_reales++;

					//2. Numero documento (validar si el dato no es vacio)

					$numero_documento = $resultado[$i][1];
					$numero_documento_como_numero = (int)$numero_documento;
					if($numero_documento == null || strcmp($numero_documento,'') == 0 )
					{
						$obj_log["descripcion"] = 'Campo NUMERO DOCUMENTO vacío';
						array_push($logs_errores, $obj_log);
						continue;
					}else if(preg_match('/[^0-9]/', $numero_documento) )
					{
						$obj_log["descripcion"] = 'Campo NUMERO DOCUMENTO con formato incorrecto';
						array_push($logs_errores, $obj_log);
						continue;
					}else
					{
						//validar de acuerdo al tipo de documento
						if(strcmp($tipo_documento, 'DNI') == 0)
						{
							//si es DNI
							if(strlen($numero_documento) != 8)
							{
								$obj_log["descripcion"] = 'Campo NUMERO DOCUMENTO no corresponde a un DNI';
								array_push($logs_errores, $obj_log);
								continue;
							}

						}else if(strcmp($tipo_documento, 'CE') == 0)
						{
							//si es Carnet de extranjeria
							if(strlen($numero_documento) != 12)
							{
								$obj_log["descripcion"] = 'Campo NUMERO DOCUMENTO no corresponde a un CARNET DE EXTRANJERIA';
								array_push($logs_errores, $obj_log);
								continue;
							}

						}
					}

					//3. NOMBRE (validar si el dato no es vacio)
					$nombre = $resultado[$i][2];
					if($nombre == null || strcmp($nombre,'') == 0)
					{
						$obj_log["descripcion"] = 'Campo NOMBRE vacío';
						array_push($logs_errores, $obj_log);
						continue;
					}else if(preg_match('/\\d/', $nombre))
					{
						$obj_log["descripcion"] = 'Campo NOMBRE contiene números';
						array_push($logs_errores, $obj_log);
						continue;
					}

					//4. APELLIDO_PATERNO (validar si el dato no es vacio)
					$apellido_paterno = $resultado[$i][3];
					if($apellido_paterno == null || strcmp($apellido_paterno,'') == 0)
					{
						$obj_log["descripcion"] = 'Campo APELLIDO PATERNO vacío';
						array_push($logs_errores, $obj_log);
						continue;
					}else if(preg_match('/\\d/', $apellido_paterno))
					{
						$obj_log["descripcion"] = 'Campo APELLIDO PATERNO contiene números';
						array_push($logs_errores, $obj_log);
						continue;
					}

					//5. APELLIDO_MATERNO (validar si el dato no es vacio)
					$apellido_materno = $resultado[$i][4];
					if($apellido_materno == null || strcmp($apellido_materno,'') == 0)
					{
						$obj_log["descripcion"] = 'Campo APELLIDO MATERNO vacío';
						array_push($logs_errores, $obj_log);
						continue;
					}else if(preg_match('/\\d/', $apellido_materno))
					{
						$obj_log["descripcion"] = 'Campo APELLIDO MATERNO contiene números';
						array_push($logs_errores, $obj_log);
						continue;
					}

					//6. CANAL (validar si el dato no es vacio)
					$canal = $resultado[$i][5];
					if($canal == null || strcmp($canal, "") == 0)
					{
						//rechazar
						$obj_log["descripcion"] = 'Campo AREA - CANAL no seleccionado';
						array_push($logs_errores, $obj_log);
						continue;
					}

					//7. DETALLE CANAL (validar si el dato no es vacio)
					$detalle_canal = $resultado[$i][6];
					if($detalle_canal == null || strcmp($detalle_canal, "") == 0)
					{
						//rechazar
						$obj_log["descripcion"] = 'Campo DETALLE AREA - CANAL no seleccionado';
						array_push($logs_errores, $obj_log);
						continue;
					}

					/*//8. SUBDETALLE CANAL (validar si el dato no es vacio)
					$subdetalle_canal = $resultado[$i][7];
					if($subdetalle_canal == null || strcmp($subdetalle_canal, "") == 0)
					{
						//rechazar
						$obj_log["descripcion"] = 'Campo SUBDETALLE AREA - CANAL no seleccionado';
						array_push($logs_errores, $obj_log);
						continue;
					}*/

					//9. SOCIO (validar si el dato no es vacio)
					$socio = $resultado[$i][7];
					if($socio == null || strcmp($socio, "") == 0)
					{
						//rechazar
						$obj_log["descripcion"] = 'Campo SOCIO (Razon Social) no seleccionado';
						array_push($logs_errores, $obj_log);
						continue;
					}

					//10. NUMERO RUC (validar si el dato no es vacio)
					$ruc = $resultado[$i][8];
					$ruc_como_entero = (int)$ruc;
					
					if($ruc == null || strcmp($ruc, "") == 0)
					{
						//rechazar
						$obj_log["descripcion"] = 'Campo NUMERO RUC no seleccionado';
						array_push($logs_errores, $obj_log);
						continue;
					}else if(preg_match('/[a-zA-Z]/i', $ruc))
					{
						//rechazar
						$obj_log["descripcion"] = 'Campo NUMERO RUC no tiene el formato correcto';
						array_push($logs_errores, $obj_log);
						continue;
					}else if(strlen($ruc) != 11)
					{
						//rechazar
						$obj_log["descripcion"] = 'Campo NUMERO RUC no tiene 11 dígitos.';
						array_push($logs_errores, $obj_log);
						continue;
					}

					/*//11. ENTIDAD (validar si el dato no es vacio)
					$entidad = $resultado[$i][9];
					if($entidad == null || strcmp($entidad, "") == 0)
					{
						//rechazar
						$obj_log["descripcion"] = 'Campo ENTIDAD no seleccionado';
						array_push($logs_errores, $obj_log);
						continue;
					}*/

					//12. PUNTO DE VENTA (validar si el dato no es vacio)
					$punto_venta = $resultado[$i][9];
					if($punto_venta == null || strcmp($punto_venta, "") == 0)
					{
						//rechazar
						$obj_log["descripcion"] = 'Campo PUNTO DE VENTA no seleccionado';
						array_push($logs_errores, $obj_log);
						continue;
					}

					//13. ROL (validar si el dato no es vacio)
					$rol = $resultado[$i][10];
					if($rol == null || strcmp($rol, "") == 0)
					{
						//rechazar
						$obj_log["descripcion"] = 'Campo ROL no seleccionado';
						array_push($logs_errores, $obj_log);
						continue;
					}

					$cantidad_registros_procesados++;

					//rechazar
					$obj_log["descripcion"] = 'Registro Correcto';
					array_push($logs_errores, $obj_log);

					$obj_registro = [
						"tipo_documento" => $tipo_documento,
						"numero_documento" => $numero_documento,
						"nombre" => $nombre,
						"apellido_paterno" => $apellido_paterno,
						"apellido_materno" => $apellido_materno,
						"canal" => $canal,
						"detalle_canal" => $detalle_canal,
						//"subdetalle_canal" => $subdetalle_canal,
						"socio" => $socio,
						"ruc" => $ruc,
						//"entidad" => $entidad,
						"punto_venta" => $punto_venta,
						"rol" => $rol,
					];

					array_push($array_registros, $obj_registro);

			    }
				
			    $data["archivo_subido"] = true;
			    $data["base_cargada"] = false;
			    $data["cantidad_registros"] = $registros_reales;
			    $data["cantidad_registros_procesados"] = $cantidad_registros_procesados;

			    $array_log_text = MenuPrincipalGestorPlanillaController::transformar_log_texto($logs_errores);
				$data["logs"] = $array_log_text;

			    $data["registros"] = $array_registros;

				return View::make('Planilla/GestorPlanilla/menuPrincipalGestorPlanilla',$data);
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
			$cadena=$cadena.$logs[$i]["descripcion"].'?';
		}
		return $cadena;
	}

	public function descargar_logs()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (GESTOR DE PLANILLA)
			if($data["user"]->idrol == 6){
				
				
				$date=new DateTime(); //this returns the current date time
				$result = $date->format('Y.m.d H.i.s');
				$value = Excel::create('Reporte Logs '.$result, function($excel) {
						$excel->sheet('Reporte', function($sheet)  {
							$sheet->row(1, array(
								     'N° Registro','Resultado'
								));
							$logs = Input::get('logs');
							
							$registros = explode('?',$logs);
							$tamano_logs = count($registros)-1;

							for($i = 0;$i<$tamano_logs;$i++){
								$partes = explode('/',$registros[$i]);
								$sheet->row($i+2, array(
								     $i+1, $partes[0]
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
	
	public function submit_carga()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 6){
				
				// 0. Recepcionar información:

				$tipos_documento = Input::get('tipos_documento');
				$numeros_documento = Input::get('numeros_documento');
				$nombres = Input::get('nombres');
				$apellidos_paterno = Input::get('apellidos_paterno');
				$apellidos_materno = Input::get('apellidos_materno');
				$canales = Input::get('canales');
				$detalle_canales = Input::get('detalle_canales');
				//$subdetalle_canales = Input::get('subdetalle_canales');
				$socios = Input::get('socios');
				$rucs = Input::get('rucs');
				//$entidades = Input::get('entidades');
				$puntos_venta = Input::get('puntos_venta');
				$roles = Input::get('roles');
				
				$cantidad_registros = count($tipos_documento);

				
				// 1. Registrar la carga del archivo
				$carga_archivo = new CargaArchivoPlanilla;
				$carga_archivo->fecha_carga_archivo = date('Y-m-d H:i:s');
				$carga_archivo->iduser_registrador = $data["user"]->id;
				$carga_archivo->iduser_created_by = $data["user"]->id;
				
				$carga_archivo->save();

				for($i=0; $i<$cantidad_registros; $i++)
				{
					// 2. Registrar nuevo usuario planilla
					$usuario_planilla = new UsuarioPlanilla;
					$usuario_planilla->nombre = $nombres[$i];
					$usuario_planilla->apellido_paterno = $apellidos_paterno[$i];
					$usuario_planilla->apellido_materno = $apellidos_materno[$i];
					$usuario_planilla->tipo_documento = $tipos_documento[$i];
					$usuario_planilla->numero_documento = $numeros_documento[$i];
					$usuario_planilla->canal = $canales[$i];
					$usuario_planilla->detalle_canal = $detalle_canales[$i];
					//$usuario_planilla->subdetalle_canal = $subdetalle_canales[$i];
					$usuario_planilla->socio = $socios[$i];
					$usuario_planilla->ruc_socio = $rucs[$i];
					//$usuario_planilla->entidad = $entidades[$i];
					$usuario_planilla->punto_venta = $puntos_venta[$i];
					$usuario_planilla->rol = $roles[$i];
					$usuario_planilla->idcarga_archivo_planilla = $carga_archivo->idcarga_archivo_planilla;
					$usuario_planilla->iduser_created_by = $data["user"]->id;
					$usuario_planilla->save();				
					
				}
	
				return Redirect::to('/principal_gestor_planilla')->with('message','Se realizó la carga de la lista del personal autorizado con éxito');
				
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

}
