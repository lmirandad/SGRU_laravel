<?php

class RequerimientoController extends BaseController {

	public function cargar_requerimientos()
	{

		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1 || $data["user"]->idrol == 2){
			
				
				//Lectura del archivo
				$file_name = $_FILES['file']['tmp_name'];

				 if($file_name == null )
			    {
			    	Session::flash('error','No hay archivo adjunto');
			    	return Redirect::to('/principal_gestor');
			    }

			    $solicitud_id = Input::get('solicitud_id');

			    $resultado = Excel::load($file_name)->get();

			 	$cantidad_requerimientos = count($resultado);
			    $herramientas = Herramienta::listarHerramientas()->get();
			    $array_log = array();
			   // echo '<pre>'; var_dump('cantidad_requerimientos '.$cantidad_requerimientos);echo '</pre>';

			    for($i = 0; $i < $cantidad_requerimientos; $i++)
			    {
			    	//revisar dato por dato
			    	$canal = $resultado[$i][0];
			    	$accion = $resultado[$i][1];
			    	$documento =strval($resultado[$i][2]);
			    	$nombre = $resultado[$i][3];
			    	$cargo = $resultado[$i][4];
			    	$entidad = $resultado[$i][5];
			    	$punto_venta = $resultado[$i][6];
			    	$aplicativo = $resultado[$i][7];
			    	$codigo_requerimiento = $resultado[$i][8];

			    	$obj_log = [
			    		"numero_fila" => ($i+1),
			    		"descripcion" => null,
			    	];

			    	//Validar si el codigo de requerimiento está vacio, sin ello no se puede crear o revisar la información
			    	if($codigo_requerimiento == null || strcmp($codigo_requerimiento,'') == 0)
			    	{
			    		$codigo_requerimiento = 'SIN_REQ';
			    	}

			    	//Validar si el usuario tiene dni correcto, sin ello no se puede crear la transaccion ni menos el requerimiento
			    	$usuario_valido = true;
			    	$usuario_bloqueado = false;
			    	$observacion_requerimiento = "";
			    	$observacion_transaccion = "";
			    	$crear_requerimiento = true;
			    	//3. documento
			    	if($documento == null || !ctype_digit($documento))
			    	{
			    		$observacion_requerimiento = $observacion_requerimiento."Requerimiento sin DNI válido.";
			    		$obj_log["descripcion"] = $obj_log["descripcion"].$observacion_requerimiento.'/';
			    		$usuario_valido = false;

			    	}else{			    		
			    		//validacion lista vena u observados
			    		$usuario_observado = UsuarioObservado::buscarUsuarioPorDocumento($documento)->get();
			    		if($usuario_observado == null || $usuario_observado->isEmpty())
			    		{
			    			$usuario_vena = UsuarioVena::buscarUsuarioPorDocumento($documento)->get();
			    			if($usuario_vena == null || $usuario_vena->isEmpty())
				    		{
				    			//
				    			
				    		}else
				    		{
				    			$observacion_transaccion = $observacion_transaccion."Usuario DNI ".$documento." bloqueado (Lista Vena).|";
				    			$obj_log["descripcion"] = $obj_log["descripcion"].$observacion_transaccion.'/';
				    			$usuario_bloqueado = true;

				    		}

			    		}else
			    		{
			    			$observacion_transaccion = $observacion_transaccion."Usuario DNI ".$documento." bloqueado (Lista de Observados).|";
			    			$obj_log["descripcion"] = $obj_log["descripcion"].$observacion_transaccion.'/';
			    			$usuario_bloqueado = true;

			    		}
			    	}

			    	//echo '<pre>'; var_dump('usuario_bloqueado '.$usuario_bloqueado);echo '</pre>';

			    	//validar si el codigo de requerimiento ya fue creado anteriormente
			    	$busqueda_requerimiento = Requerimiento::BuscarRequerimientosPorCodigoRequerimiento($codigo_requerimiento,$solicitud_id)->get();
			    	$requerimiento = null;


			    	//En caso que no sea encontrado, se creará uno nuevo pero también se validará si se puede registrar.
			    	if($busqueda_requerimiento == null || $busqueda_requerimiento->isEmpty())
			    	{
			    		//es un nuevo requerimiento
			    		$requerimiento = new Requerimiento;
			    		$requerimiento->fecha_registro = date('Y-m-d H:i:s');
				    	$requerimiento->codigo_requerimiento = $codigo_requerimiento;
				    	$requerimiento->accion_requerimiento = $accion;

				    	//2. Aplicativo
				    	if($aplicativo == null || strcmp($aplicativo,'')==0)
				    	{
				    		$observacion_requerimiento = $observacion_requerimiento."Requerimiento sin aplicativo (Requerimiento no creado)|";
				    		$obj_log["descripcion"] = $obj_log["descripcion"].$observacion_requerimiento.'-';
				    		$crear_requerimiento = false;
				    	}else
				    	{
				    		$idherramienta = RequerimientoController::buscarHerramienta($aplicativo,$herramientas);

				    		if($idherramienta == 0)
				    		{
				    			$observacion_requerimiento = $observacion_requerimiento."Herramienta no existente (Requerimiento no creado)|";
				    			$crear_requerimiento = false;
				    			$obj_log["descripcion"] = $obj_log["descripcion"].$observacion_requerimiento.'-';
				    		}else
				    			$requerimiento->idherramienta = $idherramienta;
				    	}

				    	//4. Punto de Venta
				    	if($punto_venta == null || strcmp($punto_venta,'')==0)
				    	{
				    		$observacion_requerimiento = $observacion_requerimiento."Requerimiento sin punto de venta (Requerimiento no creado)|";
				    		$crear_requerimiento = false;
				    		$obj_log["descripcion"] = $obj_log["descripcion"].$observacion_requerimiento.'-';
				    	}else{
				    		$idpunto_venta = RequerimientoController::buscarPuntoVenta($punto_venta);
				    		if($idpunto_venta == 0)
				    		{
				    			$observacion_requerimiento = $observacion_requerimiento."Punto de Venta no existente (Requerimiento no creado)|";
				    			$crear_requerimiento = false;
				    			$obj_log["descripcion"] = $obj_log["descripcion"].$observacion_requerimiento.'-';
				    		}else{
				    			$requerimiento->idpunto_venta =$idpunto_venta;
				    		}
				    	}

				    	//si el flag es true, entonces se termina de completar el requerimiento con las observaciones pendientes
				    	if($crear_requerimiento == false)
				    	{
				    		/*$requerimiento->observaciones = $resultado_registro;
				    		$requerimiento->idestado_requerimiento = 2;
							$requerimiento->fecha_cierre = date('Y-m-d H:i:s');*/
							//No se registrará el requerimiento, se hace el salto
							$log = new LogCargaFur;
							$log->numero_fila = $obj_log["numero_fila"];
							$log->resultado = $obj_log["descripcion"];
							$log->nombre_archivo = $file_name;
							$log->idrequerimiento = $requerimiento->idrequerimiento;
							$log->iduser_created_by = $data["user"]->id;
							$log->save();
							//array_push($array_log, $obj_log);
							continue;
				    	}else
				    	{
				    		//se creará el requerimiento pero tambien hay que validar si el usuario a crear en la trx es valido
				    		$requerimiento->observaciones = null;
				    		$requerimiento->idestado_requerimiento = 3;
				    	}

				    	$requerimiento->idsolicitud = $solicitud_id;
				    	$requerimiento->iduser_created_by = $data["user"]->id;
				    	$obj_log["descripcion"] = $obj_log["descripcion"].'Requerimiento nuevo creado|';
				    	$requerimiento->save();



			    	}else
			    	{
			    		//quiere decir que el requerimiento ya existe por lo tanto no se modificará su información
			    		$requerimiento = $busqueda_requerimiento[0];
			    	}

			    	//CREACION DE LA TRANSACCION
			    	// Validamos nuevamente si el usuario era válido
			    	if($usuario_valido == true)
			    	{

				    	$transaccion = new Transaccion;
				    	$transaccion->fecha_registro = date('Y-m-d H:i:s');
				    	$transaccion->cargo_canal = $cargo;
				    	$transaccion->numero_documento = $documento;
				    	$transaccion->nombre_usuario = $nombre;
				    	if($usuario_bloqueado == true)
				    	{
				    		$transaccion->usuario_bloqueado = 1;
				    		$transaccion->idestado_transaccion = 2;
				    		$transaccion->fecha_cierre = date('Y-m-d H:i:s');
				    		$transaccion->observaciones = $observacion_transaccion;
				    		$obj_log["descripcion"] = $obj_log["descripcion"].'Transaccion nueva creada (con estado rechazado)|';

				    	}else
				    	{
				    		$transaccion->usuario_bloqueado = 0;
				    		$transaccion->idestado_transaccion = 3;
				    		$transaccion->observaciones = null;
				    	}
				    	$obj_log["descripcion"] = $obj_log["descripcion"].'Transaccion nueva creada|';
				    	$transaccion->iduser_created_by = $data["user"]->id;
				    	$transaccion->idrequerimiento = $requerimiento->idrequerimiento;
				    	$transaccion->save();
			    	
			    	}else
			    	{
			    		// en caso que no sea valido se creará la transaccion pero rechazada
			    		$transaccion = new Transaccion;
				    	$transaccion->fecha_registro = date('Y-m-d H:i:s');
				    	$transaccion->cargo_canal = $cargo;
				    	$transaccion->numero_documento = null;
				    	$transaccion->nombre_usuario = $nombre;
				    	$transaccion->usuario_bloqueado = 0;
			    		$transaccion->idestado_transaccion = 2;
			    		$transaccion->observaciones = null;
			    		$transaccion->iduser_created_by = $data["user"]->id;
			    		$transaccion->idrequerimiento = $requerimiento->idrequerimiento;
			    		$transaccion->save();
			    		$obj_log["descripcion"] = $obj_log["descripcion"].'Transaccion nueva creada (con estado rechazado)|';
			    	}

			    	//array_push($array_log,$obj_log);
			    	$log = new LogCargaFur;
					$log->numero_fila = $obj_log["numero_fila"];
					$log->resultado = $obj_log["descripcion"];
					$log->nombre_archivo = $file_name;
					$log->idrequerimiento = $requerimiento->idrequerimiento;
					$log->iduser_created_by = $data["user"]->id;
					$log->save();
			    }

			    $solicitud = Solicitud::find($solicitud_id);
		    	$solicitud->fur_cargado = 1;
		    	$solicitud->idestado_solicitud = 4;
		    	$solicitud->fecha_inicio_procesando = date('Y-m-d H:i:s');
		    	$solicitud->save();


		    	//COMO TODOS LOS REQUERIMIENTOS ESTAN PENDIENTES ENTONCES VALIDAMOS SUS TRANSACCIONES
		    	$requerimientos = Requerimiento::buscarRequerimientosPorSolicitud($solicitud->idsolicitud)->get();

		    	if($requerimientos != null && !$requerimientos->isEmpty())
		    	{
		    		$cantidad_reques = count($requerimientos);
		    		for($i = 0; $i < $cantidad_reques; $i++)
		    		{
		    			$transacciones = Transaccion::buscarTransaccionesPorRequerimiento($requerimientos[$i]->idrequerimiento)->get();
		    			$transacciones_rechazadas = Transaccion::buscarTransaccionesEstadoPorRequerimiento($requerimientos[$i]->idrequerimiento,2)->get();
		    			if(($transacciones != null && !$transacciones->isEmpty()) && ($transacciones_rechazadas != null && !$transacciones_rechazadas->isEmpty()) )
		    			{
		    				if(count($transacciones) == count($transacciones_rechazadas))
		    				{
		    					//quiere decir que todos están rechazados, entonces rechazamos el requerimiento
		    					$requerimientos[$i]->observaciones = $requerimientos[$i]->observaciones.'\n'.'Transacciones rechazadas'; 
		    					$requerimientos[$i]->idestado_requerimiento = 2;
		    					$requerimientos[$i]->fecha_cierre = date('Y-m-d H:i:s');
		    					$requerimientos[$i]->save();
		    				}
		    			}
		    		}		    		
		    	}else{
		    		$solicitud->idestado_solicitud = 3;
    				$solicitud->fecha_inicio_procesando = null;
    				$solicitud->save();
    				return Redirect::to('/principal_gestor')->with('error','No Se procedieron a cargar los requerimientos de la solicitud '.$solicitud->codigo_solicitud.'.<br>Posibles motivos:<br>- No existen los puntos de venta asociados.<br>- Los aplicativos no existen en el sistema.');
		    	}

		    	//ahora comparamos la cantidad de requerimientos rechazadas vs los totales
	    		//comparamos a nivel de transacciones para definir si es cerrado con observaciones
	    		$transacciones_solicitud = Transaccion::buscarTransaccionesPorSolicitud($solicitud->idsolicitud)->get();
	    		$transacciones_rechazadas = Transaccion::buscarTransaccionesEstadoPorSolicitud($solicitud->idsolicitud,2)->get();
	    		if($transacciones_rechazadas != null && !$transacciones_rechazadas->isEmpty())
	    		{
	    			if(count($transacciones_rechazadas) == count($transacciones_solicitud))
	    			{
	    				//se cierra la solicitud puesto que tiene todos sus solicitudes rechazadas
	    				$solicitud->idestado_solicitud = 2;
	    				$solicitud->fecha_cierre = date('Y-m-d H:i:s');
	    				$solicitud->save();
	    			}
	    		}

						    	
				return Redirect::to('/principal_gestor')->with('message','Se procedieron a cargar los requerimientos de la solicitud '.$solicitud->codigo_solicitud);
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}


	public function buscarHerramienta($nombre,$herramientas)
	{
		$contador_aplicativos = 0;
    	$resultados = null;

    	$cantidad_herramientas = count($herramientas);

		for($z=0;$z<$cantidad_herramientas;$z++)
		{
			$equivalencias = HerramientaEquivalencia::buscarEquivalenciasPorIdHerramienta($herramientas[$z]->idherramienta)->get();
			$cantidad_equivalencias = count($equivalencias);
			$aplicativo_encontrado = false;
			for($w=0;$w<$cantidad_equivalencias;$w++)
			{
				similar_text(strtolower($nombre), strtolower($equivalencias[$w]->nombre_equivalencia),$porcentaje);
				//si es mayor a 90% entonces contamos con una herramienta
    			if($porcentaje>90)
    			{
    				$aplicativo_encontrado = true;
    				break;
    				
    			}	
			}

			if($aplicativo_encontrado == true)
			{
				return $herramientas[$z]->idherramienta;
    		}
    			
		}

    	return 0;
	}


	public function buscarPuntoVenta($nombre)
	{
		$punto_venta = PuntoVenta::buscarPorNombre($nombre)->get();
		if($punto_venta == null || $punto_venta->isEmpty())
			return 0;
		else
			return $punto_venta[0]->idpunto_venta;
	}

	public function mostrar_lista_requerimientos()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}

		$id = Auth::id();
		
		$data["inside_url"] = Config::get('app.inside_url');

		$data["user"] = Session::get('user');

		if($data["user"]->idrol == 2){
			// Check if the current user is the "System Admin"
			
			$solicitud_id = Input::get('idsolicitud');
			$transacciones =Transaccion::buscarTransaccionesPorSolicitud($solicitud_id)->get();

			if($transacciones == null || $transacciones->isEmpty())
				return Response::json(array( 'success' => true,'tiene_transacciones'=>false,'transacciones' => null),200);
			
			return Response::json(array( 'success' => true,'tiene_transacciones' => true,'transacciones' => $transacciones),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}
	
	public function ver_observacion()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}

		$id = Auth::id();
		
		$data["inside_url"] = Config::get('app.inside_url');

		$data["user"] = Session::get('user');

		if($data["user"]->idrol == 1 || $data["user"]->idrol == 2 ){
			// Check if the current user is the "System Admin"
			
			$idtransaccion = Input::get('idtransaccion');
			$transaccion = Transaccion::find($idtransaccion);

			if($transaccion == null )
				return Response::json(array( 'success' => true,'transaccion' => null),200);
			
			return Response::json(array( 'success' => true,'transaccion' => $transaccion),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function rechazar_requerimiento()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}

		$id = Auth::id();
		
		$data["inside_url"] = Config::get('app.inside_url');

		$data["user"] = Session::get('user');

		if($data["user"]->idrol == 2){
			// Check if the current user is the "System Admin"
			
			$idtransaccion = Input::get('idtransaccion');
			$transaccion = Transaccion::find($idtransaccion);

			if($transaccion == null )
				return Response::json(array( 'success' => true,'transaccion' => null),200);
			
			return Response::json(array( 'success' => true,'transaccion' => $transaccion),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function submit_actualizar_codigos()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2){
				
				$codigos = Input::get('codigos');
				$idtransacciones = Input::get('idtransacciones');
				$idsolicitud = Input::get('solicitud_id_mostrar');

				$solicitud = Solicitud::find($idsolicitud);

				$cantidad_transacciones = count($idtransacciones);

				for($i = 0;$i < $cantidad_transacciones; $i++)
				{
					
					$transaccion = Transaccion::find($idtransacciones[$i]);
					
					$requerimiento = Requerimiento::find($transaccion->idrequerimiento);
					
					//Comarar si el codigo de requerimiento es igual al nuevo que se está tipiando
					if(strcmp($requerimiento->codigo_requerimiento,$codigos[$i]) != 0)
					{
						//Son diferentes, validamos si existe el requerimiento de esa solicitud;
						$requerimiento_codigo = Requerimiento::buscarRequerimientosPorCodigoRequerimiento($codigos[$i],$idsolicitud)->get();
						
						if($requerimiento_codigo == null || $requerimiento_codigo->isEmpty())
						{
							//no existe la solicitud que se ha ingresado en los forms, entonces se debe proceder a crear
							
							$nuevo_req = new Requerimiento;
							$nuevo_req->fecha_registro = $requerimiento->fecha_registro;
							$nuevo_req->fecha_cierre = $requerimiento->fecha_cierre;
							$nuevo_req->idherramienta = $requerimiento->idherramienta;
							$nuevo_req->idpunto_venta = $requerimiento->idpunto_venta;
							$nuevo_req->observaciones = $requerimiento->observaciones;
							$nuevo_req->idestado_requerimiento = $requerimiento->idestado_requerimiento;
							$nuevo_req->idsolicitud = $requerimiento->idsolicitud;
							$nuevo_req->accion_requerimiento = $requerimiento->accion_requerimiento;
							$nuevo_req->iduser_created_by = $requerimiento->iduser_created_by;
							$nuevo_req->codigo_requerimiento = $codigos[$i];
							$nuevo_req->save();
							$transaccion->idrequerimiento = $nuevo_req->idrequerimiento;
							$transaccion->save();
						}else
						{
							//si existe, debemos mover la transacción a este nuevo requerimiento
							$transaccion->idrequerimiento = $requerimiento_codigo[0]->idrequerimiento;
							$transaccion->save();
						}
					}
				}

				//validamos si existen registros vacíos.
				$requerimientos = Requerimiento::buscarRequerimientosPorSolicitud($idsolicitud)->get();
				if($requerimientos != null && !$requerimientos->isEmpty())
				{
					$cantidad_requerimientos = count($requerimientos);

					for($i=0;$i<$cantidad_requerimientos;$i++)
					{
						$transacciones_requerimiento = Transaccion::buscarTransaccionesPorRequerimiento($requerimientos[$i]->idrequerimiento)->get();

						if($transacciones_requerimiento == null || $transacciones_requerimiento->isEmpty() || count($transacciones_requerimiento) == 0)
						{
							//se borra el requerimiento
							$logs = LogCargaFur::buscarLogCargaPorIdRequerimiento($requerimientos[$i]->idrequerimiento)->get();
							if($logs == null || $logs->isEmpty())
								$requerimientos[$i]->forceDelete();
							else
							{
								$cantidad_logs = count($logs);
								for($j=0;$j<$cantidad_logs;$j++)
								{
									$logs[$j]->forceDelete();
								}
								$requerimientos[$i]->forceDelete();
							}
						}
					}
				}


				Session::flash('message', 'Se actualizaron correctamente los códigos de los requerimientos del ticket '.$solicitud->codigo_solicitud);
				
				return Redirect::to('/principal_gestor');

			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_rechazar_requerimiento()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2){
				
				$attributes = array(
					'observacion' => 'Observacion',
				);

				$messages = array();

				$rules = array(
					'observacion' => 'required|alpha_num_spaces_slash_dash_enter|max:400',
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('/principal_gestor')->withErrors($validator)->withInput(Input::all());
				}else{
					
					$idtransaccion = Input::get('requerimiento_id_rechazar');
					$observacion = Input::get('observacion');
					$transaccion = Transaccion::find($idtransaccion);
					$transaccion->observaciones = $observacion;
					$transaccion->idestado_transaccion = 2;
					$transaccion->fecha_cierre = date('Y-m-d H:i:s');
					$transaccion->save();
					//validamos si ya no hay mas requerimientos pendientes de atencion

					//VALIDAMOS POR REQUERIMIENTO
					$requerimiento = Requerimiento::find($transaccion->idrequerimiento);
					$transacciones = Transaccion::buscarTransaccionesEstadoPorRequerimiento($requerimiento->idrequerimiento,3)->get();

					//validar si NO hay transacciones pendientes
					if($transacciones == null || $transacciones->isEmpty())
					{
						$transacciones_totales = Transaccion::buscarTransaccionesPorRequerimiento($requerimiento->idrequerimiento)->get();
						//quiere decir que no hay pendientes, se valida si todos están rechazados
						$transacciones_rechazadas = Transaccion::buscarTransaccionesEstadoPorRequerimiento($requerimiento->idrequerimiento,2)->get();

						//si: Numero_trx_totales = Numero_trx_rechazadas -> todas estan rechazadas (se rechaza el requerimiento)
						if(count($transacciones_totales) == count($transacciones_rechazadas))
						{
							$requerimiento->idestado_requerimiento = 2;
							$requerimiento->fecha_cierre = date('Y-m-d H:i:s');
							$requerimiento->save();
						}else
						{
							//existe al menos uno que esté atendido, entonces el requerimiento se da como atendido
							$requerimiento->idestado_requerimiento = 1;
							$requerimiento->fecha_cierre = date('Y-m-d H:i:s');
							$requerimiento->save();
						}
					}

					//VALIDAMOS POR SOLICITUD (A NIVEL TRANSACCION)
					$solicitud = Solicitud::find($requerimiento->idsolicitud);
					$transacciones = Transaccion::buscarTransaccionesEstadoPorSolicitud($solicitud->idsolicitud,3)->get();
					
					if($transacciones == null || $transacciones->isEmpty())
					{
						//quiere decir que ya no hay mas pendientes se cierra el ticket con estado cerrado con observaciones
						$solicitud->idestado_solicitud = 2;
						$solicitud->fecha_cierre = date('Y-m-d H:i:s');
						$solicitud->save();
						return Redirect::to('/principal_gestor')->with('message','Se rechazó el requerimiento '.$requerimiento->codigo_requerimiento.'.<br> '.'Se cerró la solicitud N°'.$solicitud->codigo_solicitud);
					}

					return Redirect::to('/principal_gestor')->with('message','Se rechazó el requerimiento '.$requerimiento->codigo_requerimiento);
				}

			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_finalizar_requerimiento()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2){
				
				
					
				$idtransaccion = Input::get('requerimiento_id_finalizar');
				$transaccion = Transaccion::find($idtransaccion);
				$transaccion->idestado_transaccion = 1;
				$transaccion->fecha_cierre = date('Y-m-d H:i:s');
				$transaccion->save();
				//validamos si ya no hay mas requerimientos pendientes de atencion

				//validamo si el requerimiento se puede cerrar
				//VALIDAR REQUERIMIENTO

				$requerimiento = Requerimiento::find($transaccion->idrequerimiento);
				$transacciones = Transaccion::buscarTransaccionesEstadoPorRequerimiento($requerimiento->idrequerimiento,3)->get();

				//validar si NO hay transacciones pendientes
				if($transacciones == null || $transacciones->isEmpty())
				{
					$transacciones_totales = Transaccion::buscarTransaccionesPorRequerimiento($requerimiento->idrequerimiento)->get();
					//quiere decir que no hay pendientes, se valida si todos están rechazados
					$transacciones_rechazadas = Transaccion::buscarTransaccionesEstadoPorRequerimiento($requerimiento->idrequerimiento,2)->get();

					//si: Numero_trx_totales = Numero_trx_rechazadas -> todas estan rechazadas (se rechaza el requerimiento)
					if(count($transacciones_totales) == count($transacciones_rechazadas))
					{
						$requerimiento->idestado_requerimiento = 2;
						$requerimiento->fecha_cierre = date('Y-m-d H:i:s');
						$requerimiento->save();
					}else
					{
						//existe al menos uno que esté atendido, entonces el requerimiento se da como atendido
						$requerimiento->idestado_requerimiento = 1;
						$requerimiento->fecha_cierre = date('Y-m-d H:i:s');
						$requerimiento->save();
					}
				}

				//VALIDAR LA SOLICITUD

				$solicitud = Solicitud::find($requerimiento->idsolicitud);
				//buscar todas los requerimientos pendientes
				$transacciones = Transaccion::buscarTransaccionesEstadoPorSolicitud($solicitud->idsolicitud,3)->get();
				//si no hay pendientes revisamos si existen rechazadas
				if($transacciones == null || $transacciones->isEmpty())
				{
					//Si valida si existen transacciones rechazadas
					$transacciones = Transaccion::buscarTransaccionesEstadoPorSolicitud($solicitud->idsolicitud,2)->get();
					if($transacciones == null || $transacciones->isEmpty())
					{
						//no existen transacciones rechazado entonces se cerrará con estado Atendido
						$solicitud->idestado_solicitud = 1;
						$solicitud->fecha_cierre = date('Y-m-d H:i:s');
						$solicitud->save();
						return Redirect::to('/principal_gestor')->with('message','Se finalizó la atención del requerimiento '.$requerimiento->codigo_requerimiento.'.<br> '.'Se cerró la solicitud N°'.$solicitud->codigo_solicitud);
					}else
					{
						//quiere decir que ya no hay mas pendientes se cierra el ticket con estado cerrado con observaciones
						$solicitud->idestado_solicitud = 2;
						$solicitud->fecha_cierre = date('Y-m-d H:i:s');
						$solicitud->save();
						return Redirect::to('/principal_gestor')->with('message','Se finalizó la atención del requerimiento '.$requerimiento->codigo_requerimiento.'.<br> '.'Se cerró la solicitud N°'.$solicitud->codigo_solicitud);
					}
					
				}

				return Redirect::to('/principal_gestor')->with('message','Se finalizó la atención de la transaccion N°'.$transaccion->idtransaccion. ' del requerimiento '.$requerimiento->codigo_requerimiento);
				

			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function reactivar_transaccion()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}

		$id = Auth::id();
		
		$data["inside_url"] = Config::get('app.inside_url');

		$data["user"] = Session::get('user');

		if($data["user"]->idrol == 1){
			// Check if the current user is the "System Admin"
			
			$idtransaccion = Input::get('idtransaccion');
			$transaccion = Transaccion::find($idtransaccion);

			if($transaccion == null)
				return Response::json(array( 'success' => true,'transaccion' => null),200);
			
			$transaccion->idestado_transaccion = 3;
			$transaccion->observaciones = 'Transacción reactivada.';
			$transaccion->fecha_cierre = null;
			$transaccion->save();


			return Response::json(array( 'success' => true,'transaccion' => $transaccion),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function submit_eliminar_base()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2){
				
				
				$solicitud_id = Input::get('solicitud_id_eliminar_base');

				
				$requerimientos = Requerimiento::buscarRequerimientosPorSolicitud($solicitud_id)->get();
			    if($requerimientos != null && !$requerimientos->isEmpty())
			    {  
			    	$cantidad_requerimientos = count($requerimientos);
			    	for($i=0;$i<$cantidad_requerimientos;$i++)
			    	{
			    		// TRANSACCIONES

			    		$transacciones = Transaccion::buscarTransaccionesPorRequerimiento($requerimientos[$i]->idrequerimiento)->get();
			    		if($transacciones != null && !$transacciones->isEmpty())
			    		{
			    			$cantidad_transacciones = count($transacciones);
			    			for($j=0;$j<$cantidad_transacciones;$j++)
			    			{
			    				$transaccion = Transaccion::find($transacciones[$j]->idtransaccion);
			    				$transaccion->forceDelete();
			    			}
			    		}

			    		//LOG FUR
			    		$logs = LogCargaFur::buscarLogCargaPorIdRequerimiento($requerimientos[$i]->idrequerimiento)->get();
						if($logs != null && !$logs->isEmpty())
			    		{
			    			$cantidad_logs = count($logs);
			    			for($j=0;$j<$cantidad_logs;$j++)
			    			{
			    				$logs[$j]->forceDelete();
			    			}
			    		}			    		

			    		$requerimiento = Requerimiento::find($requerimientos[$i]->idrequerimiento);
			    		$requerimiento->forceDelete();
			    	}
			    }

			    //Regresar el ticket a su estado de pendiente;
			    $solicitud = Solicitud::find($solicitud_id);
			    $solicitud->idestado_solicitud = 3;
			    $solicitud->fecha_inicio_procesando = null;
			    $solicitud->fur_cargado = 0;
			    $solicitud->save();

			   return Redirect::to('/principal_gestor')->with('message','Se eliminó la base de requerimientos de la solicitud '.$solicitud->codigo_solicitud);

			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}
}
