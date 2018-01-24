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

			    $solicitud = Solicitud::find($solicitud_id);

			    $resultado = Excel::load($file_name)->get();

			 	$cantidad_transacciones = count($resultado);
			    $herramientas = Herramienta::listarHerramientas()->get();
			    $array_log = array();

			    $mensaje_error = '';
			   // echo '<pre>'; var_dump('cantidad_transacciones '.$cantidad_transacciones);echo '</pre>';

			    for($i = 0; $i < $cantidad_transacciones; $i++)
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
			    		$observacion_requerimiento = $observacion_requerimiento."Transaccion sin DNI válido.";
			    		$obj_log["descripcion"] = $obj_log["descripcion"].$observacion_requerimiento.'/';
			    		$usuario_valido = false;
			    		$mensaje_error = $mensaje_error.'Registro N° '.($i+1).' no registrado. Transaccion sin DNI válido<br>';
			    		continue;

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

			    	//es un nuevo transaccion
		    		$transaccion = new Transaccion;
		    		$transaccion->fecha_registro = date('Y-m-d H:i:s');
			    	$transaccion->codigo_requerimiento = $codigo_requerimiento;
			    	$transaccion->accion_requerimiento = $accion;

			    	//2. Aplicativo
			    	if($aplicativo == null || strcmp($aplicativo,'')==0)
			    	{
			    		$observacion_requerimiento = $observacion_requerimiento."Requerimiento sin aplicativo (Requerimiento no creado)|";
			    		$obj_log["descripcion"] = $obj_log["descripcion"].$observacion_requerimiento.'-';
			    		$crear_requerimiento = false;
			    		$mensaje_error = $mensaje_error.'Registro N° '.($i+1).' no registrado. Herramienta no existente <br>';
			    	}else
			    	{
			    		$idherramienta = RequerimientoController::buscarHerramienta($aplicativo,$herramientas);

			    		if($idherramienta == 0)
			    		{
			    			$observacion_requerimiento = $observacion_requerimiento."Herramienta no existente (Requerimiento no creado)|";
			    			$crear_requerimiento = false;
			    			$obj_log["descripcion"] = $obj_log["descripcion"].$observacion_requerimiento.'-';
			    			$mensaje_error = $mensaje_error.'Registro N° '.($i+1).' no registrado. Herramienta no existente <br>';
			    			continue;
			    		}else
			    			$transaccion->idherramienta = $idherramienta;
			    	}

			    	//4. Punto de Venta
			    	if($punto_venta == null || strcmp($punto_venta,'')==0)
			    	{
			    		$observacion_requerimiento = $observacion_requerimiento."Requerimiento sin punto de venta (Requerimiento no creado)|";
			    		$crear_requerimiento = false;
			    		$obj_log["descripcion"] = $obj_log["descripcion"].$observacion_requerimiento.'-';
			    		$mensaje_error = $mensaje_error.'Registro N° '.($i+1).' no registrado. Punto de Venta no existente <br>';
			    	}else{
			    		$idpunto_venta = RequerimientoController::buscarPuntoVenta($punto_venta);
			    		if($idpunto_venta == 0)
			    		{
			    			$observacion_requerimiento = $observacion_requerimiento."Punto de Venta no existente (Requerimiento no creado)|";
			    			$crear_requerimiento = false;
			    			$obj_log["descripcion"] = $obj_log["descripcion"].$observacion_requerimiento.'-';
			    			$mensaje_error = $mensaje_error.'Registro N° '.($i+1).' no registrado. Punto de Venta no existente <br>';
			    			continue;
			    		}else{
			    			$transaccion->idpunto_venta =$idpunto_venta;
			    		}
			    	}

			    	//si el flag es true, entonces se termina de completar el requerimiento con las observaciones pendientes
			    	if($crear_requerimiento == false)
			    	{
			    		$log = new LogCargaFur;
						$log->numero_fila = $obj_log["numero_fila"];
						$log->resultado = $obj_log["descripcion"];
						$log->nombre_archivo = $file_name;
						$log->idtransaccion = $transaccion->idtransaccion;
						$log->iduser_created_by = $data["user"]->id;
						$log->save();
						//array_push($array_log, $obj_log);
						continue;
			    	}else
			    	{
			    		//se creará el requerimiento pero tambien hay que validar si el usuario a crear en la trx es valido
			    		$transaccion->observaciones = null;
			    		$transaccion->idestado_transaccion = 3;
			    	}

			    	$transaccion->idsolicitud = $solicitud_id;
			    	$transaccion->iduser_created_by = $data["user"]->id;
			    	$obj_log["descripcion"] = $obj_log["descripcion"].'Requerimiento nuevo creado|';

			    	$transaccion->save();


			    	//CREACION DE LA TRANSACCION
			    	// Validamos nuevamente si el usuario era válido
			    	if($usuario_valido == true)
			    	{

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
				    		$mensaje_error = $mensaje_error.'Registro N° '.($i+1).' registrado. El usuario está bloqueado (Transacción Rechazada) <br>';
				    	}else
				    	{
				    		$transaccion->usuario_bloqueado = 0;
				    		$transaccion->idestado_transaccion = 3;
				    		$transaccion->observaciones = null;
				    		$mensaje_error = $mensaje_error.'Registro N° '.($i+1).' registrado con éxito<br>';
				    	}
				    	
				    	$transaccion->save();
			    	
			    	}else
			    	{
			    		// en caso que no sea valido se creará la transaccion pero rechazada
			    		$transaccion->fecha_cierre = date('Y-m-d H:i:s');
				    	$transaccion->cargo_canal = $cargo;
				    	$transaccion->numero_documento = null;
				    	$transaccion->nombre_usuario = $nombre;
				    	$transaccion->usuario_bloqueado = 0;
			    		$transaccion->idestado_transaccion = 2;
			    		$transaccion->observaciones = null;
			    		$transaccion->iduser_created_by = $data["user"]->id;
			    		$transaccion->save();
			    		$obj_log["descripcion"] = $obj_log["descripcion"].'Transaccion nueva creada (con estado rechazado)|';
			    	}

			    	//array_push($array_log,$obj_log);
			    	$log = new LogCargaFur;
					$log->numero_fila = $obj_log["numero_fila"];
					$log->resultado = $obj_log["descripcion"];
					$log->nombre_archivo = $file_name;
					$log->idtransaccion = $transaccion->idtransaccion;
					$log->iduser_created_by = $data["user"]->id;
					$log->save();
			    }

			    $solicitud = Solicitud::find($solicitud_id);
		    	$solicitud->fur_cargado = 1;
		    	$solicitud->idestado_solicitud = 4;
		    	$solicitud->fecha_inicio_procesando = date('Y-m-d H:i:s');
		    	$solicitud->save();

		    	Session::flash('info','<strong>RESULTADO DE LA CARGA</strong><br>'.$mensaje_error);
		    	
		    	//COMO TODOS LOS REQUERIMIENTOS ESTAN PENDIENTES ENTONCES VALIDAMOS SUS TRANSACCIONES
		    	$transacciones = Transaccion::buscarTransaccionesPorSolicitud($solicitud->idsolicitud)->get();

		    	if($transacciones != null && !$transacciones->isEmpty())
		    	{
		    		$cantidad_transacciones_totales = count($transacciones);
		    		$transacciones_rechazadas = Transaccion::buscarTransaccionesEstadoPorSolicitud($solicitud->idsolicitud,2)->get();

		    		if($transacciones_rechazadas != null && !$transacciones_rechazadas->isEmpty())
		    		{
		    			$cantidad_transacciones_rechazadas = count($transacciones_rechazadas);
		    			if($cantidad_transacciones_totales == $cantidad_transacciones_rechazadas)
		    			{
		    				//si es la misma cantidad entonces se devuelve el pedido
		    				$solicitud->idestado_solicitud = 2;
		    				$solicitud->fecha_inicio_procesando = date('Y-m-d H:i:s');
		    				$solicitud->fecha_cierre = date('Y-m-d H:i:s');
		    				$solicitud->save();
		    				return Redirect::to('/principal_gestor')->with('message','Se procedió a cerrar la solicitud '.$solicitud->codigo_solicitud.' con estado <strong>CERRADO CON OBSERVACIONES</strong>.<br>Las transacciones que se registraron en el sistema fueron rechazadas puesto que los usuarios se encuentran bloqueados en Lista Vena u Observados.');
		    			}
		    		}
		    	}else{
		    		$solicitud->idestado_solicitud = 3;
    				$solicitud->fecha_inicio_procesando = null;
    				$solicitud->save();
    				return Redirect::to('/principal_gestor')->with('error','No Se procedieron a cargar los requerimientos de la solicitud '.$solicitud->codigo_solicitud.'.<br>Posibles motivos:<br>- No existen los puntos de venta asociados.<br>- Los aplicativos no existen en el sistema.');
		    	}
						    	
				return Redirect::to('/principal_gestor')->with('message','Se procedieron a cargar las transacciones de la solicitud '.$solicitud->codigo_solicitud);
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
			$solicitud = Solicitud::find($solicitud_id);

			if($transacciones == null || $transacciones->isEmpty())
				return Response::json(array( 'success' => true,'tiene_transacciones'=>false,'transacciones' => null),200);
			
			return Response::json(array( 'success' => true,'tiene_transacciones' => true,'transacciones' => $transacciones,'solicitud'=>$solicitud),200);
			
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

				$mensaje = '';

				for($i = 0;$i < $cantidad_transacciones; $i++)
				{
					
					if(strcmp($codigos[$i], 'SIN_REQ') != 0)
					{
						$transaccion = Transaccion::find($idtransacciones[$i]);
						$herramienta = Herramienta::find($transaccion->idherramienta);
						$tipo_requerimiento = TipoRequerimiento::find($herramienta->idtipo_requerimiento);

						if($tipo_requerimiento->idtipo_requerimiento == 6)
						{
							//ES UN REMEDY, ENTONCES VALIDAMOS SI EL CODIGO INICIA EN REQ
							//entonces se valida el codigo
							if(strlen($codigos[$i]) <= 3 || strcmp(substr($codigos[$i],0,3),'REQ') != 0 )
							{
								$mensaje = $mensaje.'Código de la transacción N° '.$idtransacciones[$i].' no corresponde a un REMEDY';
							}else{
								$transaccion->codigo_requerimiento = $codigos[$i];
								$transaccion->save();
							} 
						}else
						{
							$transaccion->codigo_requerimiento = $codigos[$i];
							$transaccion->save();
						}
					}
				
				}

				$mensaje_final = '';

				if(strcmp($mensaje, '') != 0 ){
					$mensaje_final = 'Se actualizaron los códigos de las transacciones del ticket '.$solicitud->codigo_solicitud.' con excepción de los siguientes:<br>'.$mensaje;
					Session::flash('error',$mensaje_final);
				}else
				{
					$mensaje_final = 'Se actualizaron correctamente los códigos de los requerimientos del ticket '.$solicitud->codigo_solicitud; 
					Session::flash('message',$mensaje_final);
				}

				return Redirect::to('/principal_gestor_procesando/'.$solicitud->idsolicitud);

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
					$solicitud = Solicitud::find($transaccion->idsolicitud);
					$transaccion->observaciones = $observacion;
					$transaccion->idestado_transaccion = 2;
					$transaccion->fecha_cierre = date('Y-m-d H:i:s');
					$transaccion->save();
					//validamos si ya no hay mas requerimientos pendientes de atencion

					//VALIDAMOS POR SOLICITUD (A NIVEL TRANSACCION)
					$transacciones_procesando = Transaccion::buscarTransaccionesEstadoPorSolicitud($solicitud->idsolicitud,4)->get();
					$transacciones_pendientes = Transaccion::buscarTransaccionesEstadoPorSolicitud($solicitud->idsolicitud,3)->get();
					
					if(($transacciones_procesando == null || $transacciones_procesando->isEmpty()) && ($transacciones_pendientes == null || $transacciones_pendientes->isEmpty()) )
					{
						//quiere decir que ya no hay mas pendientes se cierra el ticket con estado cerrado con observaciones
						$solicitud->idestado_solicitud = 2;
						$solicitud->fecha_cierre = date('Y-m-d H:i:s');
						$solicitud->save();
						return Redirect::to('/principal_gestor')->with('message','Se rechazó la transacción N° '.$transaccion->idtransaccion.' (Cod. Requerimiento: '.$transaccion->codigo_requerimiento.')<br> '.'Se cerró la solicitud N°'.$solicitud->codigo_solicitud.' con estado <strong>CERRADO CON OBSERVACIONES</strong>');
					}

					return Redirect::to('/principal_gestor_procesando/'.$solicitud->idsolicitud)->with('message','Se rechazó la transacción N° '.$transaccion->idtransaccion.' (Cod. Requerimiento: '.$transaccion->codigo_requerimiento.')');
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
				
				
					
				$id_solicitud = Input::get('solicitud_id_finalizar');

				$transacciones = Transaccion::buscarTransaccionesPorSolicitud($id_solicitud)->get();
				
				$codigos_transacciones = '';

				if($transacciones != null && !$transacciones->isEmpty())
				{
					$cantidad_transacciones = count($transacciones);

					for($i=0;$i<$cantidad_transacciones;$i++)
					{
						
						$res_checkbox = Input::get('ids_checkbox_finalizar')[$i];

						if($res_checkbox == 1)
						{
							$transacciones[$i]->idestado_transaccion = 1;
							$transacciones[$i]->fecha_cierre = date('Y-m-d H:i:s');
							$transacciones[$i]->save();
							$codigos_transacciones = $codigos_transacciones.' Transacción N° '.$transacciones[$i]->idtransaccion.' (Cod. Requerimiento: '.$transacciones[$i]->codigo_requerimiento.')<br>';			
						}
					}
				}
				
				//VALIDAR LA SOLICITUD

				$solicitud = Solicitud::find($id_solicitud);
				//buscar todas los requerimientos pendientes
				$transacciones = Transaccion::buscarTransaccionesPendientesProcesandoPorSolicitud($solicitud->idsolicitud)->get();
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
						return Redirect::to('/principal_gestor')->with('message','Se finalizó la atención de las transacciones: <br>'.$codigos_transacciones.'<br>Se cerró la solicitud N°'.$solicitud->codigo_solicitud.' con estado <strong>ATENDIDO</strong>');
					}else
					{
						//quiere decir que ya no hay mas pendientes se cierra el ticket con estado cerrado con observaciones
						$solicitud->idestado_solicitud = 2;
						$solicitud->fecha_cierre = date('Y-m-d H:i:s');
						$solicitud->save();
						return Redirect::to('/principal_gestor')->with('message','Se finalizó la atención de las transacciones: <br>'.$codigos_transacciones.'<br>Se cerró la solicitud N°'.$solicitud->codigo_solicitud.' con estado <strong>CERRADO CON OBSERVACIONES</strong>');
					}
					
				}

				return Redirect::to('/principal_gestor_procesando/'.$id_solicitud)->with('message','Se finalizó la atención de las transacciones: <br>'.$codigos_transacciones);
				

			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_procesar_requerimiento()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2){
				
				$id_solicitud = Input::get('solicitud_id_procesar');

				$transacciones = Transaccion::buscarTransaccionesPorSolicitud($id_solicitud)->get();
				
				$codigos_transacciones = '';

				$mensaje = '';

				$cantidad_errores = 0;
				$cantidad_transacciones_seleccionadas = 0;

				if($transacciones != null && !$transacciones->isEmpty())
				{
					$cantidad_transacciones = count($transacciones);

					for($i=0;$i<$cantidad_transacciones;$i++)
					{
						
						$res_checkbox = Input::get('ids_checkbox')[$i];

						if($res_checkbox == 1)
						{
							$cantidad_transacciones_seleccionadas++;

							$herramienta = Herramienta::find($transacciones[$i]->idherramienta);
							$tipo_requerimiento = TipoRequerimiento::find($herramienta->idtipo_requerimiento);

							if($tipo_requerimiento->idtipo_requerimiento == 6)
							{
								//ES UN REMEDY, ENTONCES VALIDAMOS SI EL CODIGO INICIA EN REQ
								//entonces se valida el codigo
								if(strlen($transacciones[$i]->codigo_requerimiento) <= 3 || strcmp(substr($transacciones[$i]->codigo_requerimiento,0,3),'REQ') != 0 )
								{
									$mensaje = $mensaje.'Código de la transacción N° '.$transacciones[$i]->idtransaccion.' no corresponde a un REMEDY <strong>Actualizar código</strong><br>';

									$cantidad_errores++;
								}else{
									$transacciones[$i]->idestado_transaccion = 4;
									$transacciones[$i]->fecha_inicio_procesando = date('Y-m-d H:i:s');
									$transacciones[$i]->save();
									$codigos_transacciones = $codigos_transacciones.' Transacción N° '.$transacciones[$i]->idtransaccion.' (Cod. Requerimiento: '.$transacciones[$i]->codigo_requerimiento.')<br>';			
								} 
							}else
							{
								$transacciones[$i]->idestado_transaccion = 4;
								$transacciones[$i]->fecha_inicio_procesando = date('Y-m-d H:i:s');
								$transacciones[$i]->save();
								$codigos_transacciones = $codigos_transacciones.' Transacción N° '.$transacciones[$i]->idtransaccion.' (Cod. Requerimiento: '.$transacciones[$i]->codigo_requerimiento.')<br>';			
							}

							
						}
					}
				}
				
				if($cantidad_errores > 0)
					Session::flash('error','No se procesarán las siguientes transacciones:<br>'.$mensaje);

				if($cantidad_transacciones_seleccionadas == $cantidad_errores)
					return Redirect::to('/principal_gestor_procesando/'.$id_solicitud);
				else
					return Redirect::to('/principal_gestor_procesando/'.$id_solicitud)->with('message','Se inició la atención de las transacciones:<br>'.$codigos_transacciones);
				

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
				$solicitud = Solicitud::find($solicitud_id);
				
		 		// TRANSACCIONES
	    		$transacciones = Transaccion::buscarTransaccionesPorSolicitud($solicitud->idsolicitud)->get();
	    		if($transacciones != null && !$transacciones->isEmpty())
	    		{
	    			$cantidad_transacciones = count($transacciones);
	    			for($i=0;$i<$cantidad_transacciones;$i++)
	    			{
	    				//LOG FUR
			    		$logs = LogCargaFur::buscarLogCargaPorIdTransaccion($transacciones[$i]->idtransaccion)->get();
						if($logs != null && !$logs->isEmpty())
			    		{
			    			$cantidad_logs = count($logs);
			    			for($j=0;$j<$cantidad_logs;$j++)
			    			{
			    				$logs[$j]->forceDelete();
			    			}
			    		}			    		
	    				$transaccion = Transaccion::find($transacciones[$i]->idtransaccion);
	    				$transaccion->forceDelete();
	    			}
	    		}

			    //Regresar el ticket a su estado de pendiente;
			    $solicitud = Solicitud::find($solicitud_id);
			    $solicitud->idestado_solicitud = 3;
			    $solicitud->fecha_inicio_procesando = null;
			    $solicitud->fur_cargado = 0;
			    $solicitud->save();

			   return Redirect::to('/principal_gestor')->with('message','Se eliminó la base de transacciones de la solicitud '.$solicitud->codigo_solicitud);

			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}
}
