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

			    $resultado = Excel::load($file_name)->get()[0];

			 	$cantidad_transacciones = count($resultado);
			    $herramientas = Herramienta::listarHerramientas()->get();
			    $array_log = array();
			    
			    //cantidad provisoria
			    $cantidad = 9;
			    if(count($resultado[0]) < $cantidad || count($resultado[0]) > $cantidad )
			    	return Redirect::to('/principal_gestor')->with('error','La cantidad de columnas del archivo adjuntado no coincide con el estandar.');

			    $mensaje_error = '';
			    $mensaje_error_solicitud = '';
			    $mensaje_error_solicitud = '';
			   	$mensaje_error_canal = '';
			   	$mensaje_error_accion = '';
			   	$mensaje_error_dni = '';
			   	$mensaje_error_nombre = '';
			   	$mensaje_error_apellido_materno = '';
			   	$mensaje_error_apellido_paterno = '';
			   	$mensaje_error_cargo = '';
			   	$mensaje_error_entidad = '';
			   	$mensaje_error_punto_venta = '';
			   	$mensaje_error_aplicativo = '';
			   	$mensaje_final = '';

			   	$rechazar_archivo = false;
			   	$rechazar_registro = false;
			   	$mensaje_final = '<strong>EL ARCHIVO SUBIDO PARA EL TICKET N°'.$solicitud->codigo_solicitud.' NO SE CARGADO POR LOS SIGUIENTES MOTIVOS:<br></strong><br>';

			   	$contador_registros_datos_llenos = 0;

			    //primera validacion total de todos los codigos
			    
			    for($i = 0; $i < $cantidad_transacciones; $i++)
			    {
			    	$codigo_solicitud_fur = $resultado[$i][0];
			    	$accion = $resultado[$i][1];
			    	$documento = $resultado[$i][2];
			    	$nombre = $resultado[$i][3];
			    	$apellido_paterno = $resultado[$i][4];
			    	$apellido_materno = $resultado[$i][5];
			    	$cargo = $resultado[$i][6];
			    	$punto_venta = $resultado[$i][7];
			    	$aplicativo = $resultado[$i][8];

			    	//VALIDAR SI ES UN REGISTRO VACIO

			    	if( ($codigo_solicitud_fur == null || strcmp($codigo_solicitud_fur,'') == 0) && 
				    	($accion == null || strcmp($accion,'') == 0) && 
				    	($documento == null || strcmp($documento,'') == 0) && 
						($nombre == null || strcmp($nombre,'') == 0) && 
						($apellido_paterno == null || strcmp($apellido_paterno,'') == 0) && 
						($apellido_materno == null || strcmp($apellido_materno,'') == 0) && 
						($cargo == null || strcmp($cargo,'') == 0) && 
						($punto_venta == null || strcmp($punto_venta,'') == 0) &&
						($aplicativo == null || strcmp($aplicativo,'') == 0)){

			    		continue;
			    	}

			    	$contador_registros_datos_llenos++;
			    	$mensaje_final = $mensaje_final.'<strong>REGISTRO N°'.($i+1).':<br></strong>';
			    	$rechazar_registro = false;

			    	//SI NO ES UN REGISTRO VACIO COMENZAMOS A VALIDAR EL REGISTRO PROPIAMENTE DICHO
	
			    	if($codigo_solicitud_fur == null || strcmp($codigo_solicitud_fur,'') == 0 )
			    	{
			    		//si son vacios, se rechaza de inmediato todo el archivo
			    		//return Redirect::to('/principal_gestor')->with('error','El registro N° '.($i+1).' del archivo adjuntado no cuenta con el código de solicitud (Ticket) asociado a la solicitud actual.');
			    		$mensaje_error_solicitud = 'No cuenta con el código de solicitud (Ticket) asociado a la solicitud actual.';
			    		$mensaje_final = $mensaje_final.$mensaje_error_solicitud.'<br>';
			    		$rechazar_archivo = true;
			    		$rechazar_registro = true;
			    	}

			    	$codigo_solicitud_fur = (int)$codigo_solicitud_fur;

					$solicitud_fur = Solicitud::buscarPorCodigoSolicitud($codigo_solicitud_fur)->get();

					if($solicitud_fur == null || $solicitud_fur->isEmpty())
					{
						//return Redirect::to('/principal_gestor')->with('error','El registro N° '.($i+1).' del archivo adjuntado tiene un código de ticket no registrado en el sistema.');	
						$mensaje_error_solicitud = 'Código de ticket no registrado en el sistema.';
						$mensaje_final = $mensaje_final.$mensaje_error_solicitud.'<br>';
						$rechazar_archivo = true;
						$rechazar_registro = true;
					}else
					{
						$id_solicitud_fur = $solicitud_fur[0]->idsolicitud;
						if($id_solicitud_fur != $solicitud_id){
							$mensaje_error_solicitud = 'Código de ticket no asociado a la solicitud actual.';
							$mensaje_final = $mensaje_final.$mensaje_error_solicitud.'<br>';
							$rechazar_archivo = true;
							$rechazar_registro = true;
						}
							//return Redirect::to('/principal_gestor')->with('error','El registro N° '.($i+1).' del archivo adjuntado tiene un código de ticket no asociado a la solicitud actual.');	
					}

					//2. Accion (validar si el dato no es vacio)
					
					if($accion == null || strcmp($accion,'') == 0)
					{
						$mensaje_error_accion = 'Campo acción vacío.';
						$rechazar_archivo = true;
						$mensaje_final = $mensaje_final.$mensaje_error_accion.'<br>';
						$rechazar_registro = true;
					}

					//3. Documento (validar si el dato no es vacio o no tiene el formato correcto)
					$documento_como_entero = (int)$documento;
					if($documento == null || $documento_como_entero == 0 || strlen($documento) < 8)
			    	{
			    		$mensaje_error_dni = 'DNI no válido';
			    		$rechazar_archivo = true;
			    		$mensaje_final = $mensaje_final.$mensaje_error_dni.'<br>';
			    		$rechazar_registro = true;
			    	}

			    	//4. Nombre (validar si el dato no es vacio)
			    	
			    	if($nombre == null || strcmp($nombre,'') == 0)
			    	{
			    		$mensaje_error_nombre = 'Campo Nombre vacío.';
			    		$rechazar_archivo = true;
			    		$mensaje_final = $mensaje_final.$mensaje_error_nombre.'<br>';
			    		$rechazar_registro = true;
			    	}

			    	//5. Apellido Paterno (validar si el dato no es vacio)
			    	
			    	if($apellido_paterno == null || strcmp($apellido_paterno,'') == 0)
			    	{
			    		$mensaje_error_apellido_paterno = 'Campo Apellido Paterno vacío.';
			    		$rechazar_archivo = true;
			    		$mensaje_final = $mensaje_final.$mensaje_error_apellido_paterno.'<br>';
			    		$rechazar_registro = true;
			    	}

			    	//6. Apellido Materno (validar si el dato no es vacio)
			    	
			    	if($apellido_materno == null || strcmp($apellido_materno,'') == 0)
			    	{
			    		$mensaje_error_apellido_materno = 'Campo Apellido Materno vacío.';
			    		$rechazar_archivo = true;
			    		$mensaje_final = $mensaje_final.$mensaje_error_apellido_materno.'<br>';
			    		$rechazar_registro = true;
			    	}

			    	//7. Cargo (validar si el dato no es vacio)
			    	
					if($cargo == null || strcmp($cargo,'') == 0)
			    	{
			    		$mensaje_error_cargo = 'Campo Cargo vacío.';
			    		$rechazar_archivo = true;
			    		$mensaje_final = $mensaje_final.$mensaje_error_cargo.'<br>';
			    		$rechazar_registro = true;
			    	}

			    	
			    	//8. Punto Venta (validar si el dato no es vacio)
			    	//$punto_venta = $resultado[$i][8];
			    	
			    	//SIN VALIDACION

			    	//9. Aplicativo (validar si el dato no es vacio)
			    	
			    	if($aplicativo == null || strcmp($aplicativo,'')==0 )
			    	{
			    		$mensaje_error_aplicativo = 'Campo Aplicativo vacío.<br>';
			    		$rechazar_archivo = true;
			    		$mensaje_final = $mensaje_final.$mensaje_error_aplicativo;
			    		$rechazar_registro = true;
			    	}else
			    	{
			    		$idherramienta = RequerimientoController::buscarHerramienta($aplicativo,$herramientas);

			    		if($idherramienta == 0)
			    		{
			    			$mensaje_error_aplicativo = 'Aplicativo no existente <br>';
			    			$rechazar_archivo = true;
			    			$mensaje_final = $mensaje_final.$mensaje_error_aplicativo.'<br>';
			    			$rechazar_registro = true;
			    		}
			    	}

			    	if($rechazar_registro == false)
			    		$mensaje_final = $mensaje_final.'SIN ERRORES<br><br>';
			    	else
			    		$mensaje_final = $mensaje_final.'<br>';
			    }

			    if($contador_registros_datos_llenos == 0 )
			    {
			    	return Redirect::to('/principal_gestor')->with('error','ARCHIVO FUR SIN REGISTROS');		
			    }

			    if($rechazar_archivo == true)
			    {
			    	return Redirect::to('/principal_gestor')->with('error',$mensaje_final);	
			    }

			    
			    		    
			    for($i = 0; $i < $contador_registros_datos_llenos; $i++)
			    {
			    	

			    	//revisar dato por dato
			    	$codigo_solicitud_fur = $resultado[$i][0];
			    	$accion = $resultado[$i][1];
			    	$documento = $resultado[$i][2];
			    	$nombre = $resultado[$i][3];
			    	$apellido_paterno = $resultado[$i][4];
			    	$apellido_materno = $resultado[$i][5];
			    	$cargo = $resultado[$i][6];
			    	$punto_venta = $resultado[$i][7];
			    	$aplicativo = $resultado[$i][8];

			    	$obj_log = [
			    		"numero_fila" => ($i+1),
			    		"descripcion" => null,
			    	];

			    	$codigo_requerimiento = 'SIN_REQ';			    	

			    	//Validar si el usuario tiene dni correcto, sin ello no se puede crear la transaccion ni menos el requerimiento
			    	$usuario_valido = true;
			    	$usuario_bloqueado = false;
			    	$observacion_requerimiento = "";
			    	$observacion_transaccion = "";
			    	$crear_requerimiento = true;
			    	//3. documento
			    				    		
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
			    	
			    	
			    	//es un nuevo transaccion
		    		$transaccion = new Transaccion;
		    		$transaccion->fecha_registro = date('Y-m-d H:i:s');
			    	$transaccion->accion_requerimiento = $accion;

			    	//2. Aplicativo
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
			    	
			    	
		    		/*$idpunto_venta = RequerimientoController::buscarPuntoVenta($punto_venta);
		    		if($idpunto_venta == 0)
		    		{
		    			$observacion_requerimiento = $observacion_requerimiento."Punto de Venta no existente (Requerimiento no creado)|";
		    			$crear_requerimiento = false;
		    			$obj_log["descripcion"] = $obj_log["descripcion"].$observacion_requerimiento.'-';
		    			$mensaje_error = $mensaje_error.'Registro N° '.($i+1).' no registrado. Punto de Venta no existente <br>';
		    			continue;
		    		}else{
		    			$transaccion->idpunto_venta =$idpunto_venta;
		    		}*/
		    		//CAMBIO PARA PONER EL PUNTO DE VENTA COMO NOMBRE
		    		$transaccion->nombre_punto_venta = $punto_venta;

			    	//si el flag es true, entonces se termina de completar el requerimiento con las observaciones pendientes
			    	if($crear_requerimiento == false)
			    	{
			    		continue;
			    	}else
			    	{
			    		//se creará el requerimiento pero tambien hay que validar si el usuario a crear en la trx es valido
			    		$transaccion->observaciones = null;
			    		$transaccion->idestado_transaccion = 3;
			    	}

			    	$transaccion->idsolicitud = $solicitud_id;
			    	$transaccion->iduser_created_by = $data["user"]->id;
			    	


			    	$transaccion->save();

			    	//TRAZABILIDAD POR TRANSACCION REGISTRADA EN EL SISTEMA
			    	$trazabilidad = new Trazabilidad;
			    	$trazabilidad->descripcion = 'Transacción registrada';
			    	$trazabilidad->fecha_registro = date('Y-m-d H:i:s');
			    	$trazabilidad->idtransaccion = $transaccion->idtransaccion;
			    	$trazabilidad->save();

			    	//CREACION DE LA TRANSACCION
			    	// Validamos nuevamente si el usuario era válido
			    	if($usuario_valido == true)
			    	{

				    	$transaccion->cargo_canal = $cargo;
				    	$transaccion->numero_documento = $documento;
				    	$transaccion->nombre_usuario = $nombre.' '.$apellido_paterno.' '.$apellido_materno;
				    	if($usuario_bloqueado == true)
				    	{
				    		$transaccion->usuario_bloqueado = 1;
				    		$transaccion->idestado_transaccion = 2;
				    		$transaccion->fecha_cierre = date('Y-m-d H:i:s');
				    		$transaccion->observaciones = $observacion_transaccion;
				    		
				    		//TRAZABILIDAD POR TRANSACCION RECHAZADA EN EL SISTEMA
					    	$trazabilidad = new Trazabilidad;
					    	$trazabilidad->descripcion = 'Transacción rechazada - Usuario presente en Lista Observados y/o Vena';
					    	$trazabilidad->fecha_registro = date('Y-m-d H:i:s');
					    	$trazabilidad->idtransaccion = $transaccion->idtransaccion;
					    	$trazabilidad->save();

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
				    	$transaccion->nombre_usuario = $nombre.' '.$apellido_paterno.' '.$apellido_materno;
				    	$transaccion->usuario_bloqueado = 0;
			    		$transaccion->idestado_transaccion = 2;
			    		$transaccion->observaciones = null;
			    		$transaccion->iduser_created_by = $data["user"]->id;
			    		$transaccion->save();

			    		//TRAZABILIDAD POR TRANSACCION RECHAZADA EN EL SISTEMA
				    	$trazabilidad = new Trazabilidad;
				    	$trazabilidad->descripcion = 'Transacción rechazada - Datos del usuario no válido.';
				    	$trazabilidad->fecha_registro = date('Y-m-d H:i:s');
				    	$trazabilidad->idtransaccion = $transaccion->idtransaccion;
				    	$trazabilidad->save();
			    		//$obj_log["descripcion"] = $obj_log["descripcion"].'Transaccion nueva creada (con estado rechazado)|';
			    	}

			    	
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
					    	
				return Redirect::to('/principal_gestor_procesando/'.$solicitud->idsolicitud)->with('message','Se procedieron a cargar las transacciones de la solicitud '.$solicitud->codigo_solicitud);
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

    	$palabras = preg_split('/[;,. :()_-]/',$nombre);
    	$cantidad_palabras = count($palabras);

    	$cantidad_herramientas = count($herramientas);

		for( $z=0 ; $z < $cantidad_herramientas ; $z++)
    	{
    		$equivalencias = HerramientaEquivalencia::buscarEquivalenciasPorIdHerramienta($herramientas[$z]->idherramienta)->get();
			$cantidad_equivalencias = count($equivalencias);
			$aplicativo_encontrado = false;
			for($w=0;$w<$cantidad_equivalencias;$w++)
			{
				$nombre_herramienta = strtolower($equivalencias[$w]->nombre_equivalencia);
				$tamano_herramienta = count(explode(' ',$nombre_herramienta));
				
				$pivot_inicial = $tamano_herramienta;
				if($pivot_inicial > $cantidad_palabras){					
					continue;
				}elseif($pivot_inicial == $cantidad_palabras && $cantidad_palabras == 1)
				{
					$cadena_a_comparar = strtolower($palabras[0]);					
					
					similar_text($nombre_herramienta,$cadena_a_comparar,$porcentaje);
    				//si es mayor a 90% entonces contamos con una herramienta    				
	    			
	    			if($porcentaje>=90)
	    			{
	    				$aplicativo_encontrado = true;
	    				break;	    				
	    			}
				}
				else{
					//generamos la palabra a comparar				
					for($j = $pivot_inicial-1 ; $j < $cantidad_palabras ; $j++)
					{
						$cadena_a_comparar = '';
						$inicial = $j - $tamano_herramienta + 1;
						$flag_no_comparar = 0;
						for($k = $inicial; $k < $inicial + $tamano_herramienta; $k++)
						{
							if(strcmp($palabras[$k],'')==0)
							{
								$flag_no_comparar = 1;
								break;
							}

							if($k == $inicial)
								$cadena_a_comparar = $cadena_a_comparar.strtolower($palabras[$k]);
							else
								$cadena_a_comparar = $cadena_a_comparar.' '.strtolower($palabras[$k]);
							
						}
						if($flag_no_comparar == 0)	
						{
							similar_text($nombre_herramienta,$cadena_a_comparar,$porcentaje);
		    				//si es mayor a 90% entonces contamos con una herramienta
		    				
			    			
			    			if($porcentaje>=90)
			    			{
			    				$aplicativo_encontrado = true;
			    				break;	    				
			    			}		
						}
					}
				}				
				
				if($aplicativo_encontrado == true)
					break;					
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

	public function buscarEntidad($nombre)
	{
		$entidad = Entidad::buscarPorNombre($nombre)->get();
		if($entidad == null || $entidad->isEmpty())
			return 0;
		else
			return $entidad[0]->identidad;	
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
			$entidad = Entidad::find($solicitud->identidad);
			$puntos_venta = PuntoVenta::buscarPuntosVentaPorEntidad($entidad->identidad)->get();

			if($transacciones == null || $transacciones->isEmpty())
				return Response::json(array( 'success' => true,'tiene_transacciones'=>false,'transacciones' => null),200);
			
			return Response::json(array( 'success' => true,'tiene_transacciones' => true,'transacciones' => $transacciones,'solicitud'=>$solicitud,'puntos_venta'=>$puntos_venta,'entidad'=>$entidad),200);
			
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
				return Response::json(array( 'success' => true,'transaccion' => null,'trazabilidad'=>null),200);

			$trazabilidad = Trazabilidad::listarTrazabilidadPorTransaccion($transaccion->idtransaccion)->get();

			if($trazabilidad == null )
				return Response::json(array( 'success' => true,'transaccion' => $transaccion,'trazabilidad'=>null),200);

			$cantidad_trazabilidad = count($trazabilidad);
			for($i=0;$i<$cantidad_trazabilidad;$i++)
			{
				$trazabilidad[$i]->fecha_registro = date('d-m-Y H:i:s',strtotime($trazabilidad[$i]->fecha_registro));
			}
			
			$solicitud = Solicitud::find($transaccion->idsolicitud);

			return Response::json(array( 'success' => true,'transaccion' => $transaccion,'trazabilidad'=>$trazabilidad,'solicitud'=>$solicitud),200);
			
		}else{
			return Response::json(array( 'success' => false),200);
		}
	}

	public function ver_observacion_transaccion()
	{
		if(!Request::ajax() || !Auth::check()){
			return Response::json(array( 'success' => false ),200);
		}

		$id = Auth::id();
		
		$data["inside_url"] = Config::get('app.inside_url');

		$data["user"] = Session::get('user');

		if($data["user"]->idrol == 1 || $data["user"]->idrol == 2 ){
			// Check if the current user is the "System Admin"
			
			$idtrazabilidad = Input::get('idobservacion');
			$trazabilidad = Trazabilidad::find($idtrazabilidad);

			if($trazabilidad == null )
				return Response::json(array( 'success' => true,'trazabilidad' => null),200);

			
			
			return Response::json(array( 'success' => true,'trazabilidad' => $trazabilidad),200);
			
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
							/*if(strlen($codigos[$i]) <= 3 || strcmp(substr($codigos[$i],0,3),'REQ') != 0 )
							{
								$mensaje = $mensaje.'Código de la transacción N° '.$idtransacciones[$i].' no corresponde a un REMEDY';
							}else{*/
								$codigo_anterior = $transaccion->codigo_requerimiento;
								$transaccion->codigo_requerimiento = $codigos[$i];
								$transaccion->save();
								if(strcmp($codigo_anterior, $codigos[$i]) != 0){
									//TRAZABILIDAD POR TRANSACCION ACTUALIZADA EN EL SISTEMA
							    	$trazabilidad = new Trazabilidad;
							    	$trazabilidad->descripcion = 'Transacción actualizada - Código Anterior: '.$codigo_anterior.' - Nuevo Código: '.$codigos[$i];
							    	$trazabilidad->fecha_registro = date('Y-m-d H:i:s');
							    	$trazabilidad->idtransaccion = $transaccion->idtransaccion;
							    	$trazabilidad->save();	
								}
								
							//} 
						}else
						{
							$codigo_anterior = $transaccion->codigo_requerimiento;
							$transaccion->codigo_requerimiento = $codigos[$i];

							$transaccion->save();

							if(strcmp($codigo_anterior, $codigos[$i]) != 0){
								//TRAZABILIDAD POR TRANSACCION ACTUALIZADA EN EL SISTEMA
						    	$trazabilidad = new Trazabilidad;
						    	$trazabilidad->descripcion = 'Transacción actualizada - Código Anterior: '.$codigo_anterior.' - Nuevo Código: '.$codigos[$i];
						    	$trazabilidad->fecha_registro = date('Y-m-d H:i:s');
						    	$trazabilidad->idtransaccion = $transaccion->idtransaccion;
						    	$trazabilidad->save();	
							}

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
					$estado_anterior = $transaccion->idestado_transaccion;
					$transaccion->idestado_transaccion = 2;
					$transaccion->fecha_cierre = date('Y-m-d H:i:s');
					$transaccion->save();

					//TRAZABILIDAD POR TRANSACCION ACTUALIZADA EN EL SISTEMA
					$estado = '';
					if($estado_anterior == 3) $estado = 'PENDIENTE'; else if($estado_anterior == 4) $estado = 'PROCESANDO';
			    	$trazabilidad = new Trazabilidad;
			    	$trazabilidad->descripcion = 'Transacción cambio de estado '.$estado.' a RECHAZADO. Motivo: '.$observacion;
			    	$trazabilidad->fecha_registro = date('Y-m-d H:i:s');
			    	$trazabilidad->idtransaccion = $transaccion->idtransaccion;
			    	$trazabilidad->save();
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
							//TRAZABILIDAD POR TRANSACCION ACTUALIZADA EN EL SISTEMA
					    	$trazabilidad = new Trazabilidad;
					    	$trazabilidad->descripcion = 'Transacción cambio de estado PROCESANDO a ATENDIDO';
					    	$trazabilidad->fecha_registro = date('Y-m-d H:i:s');
					    	$trazabilidad->idtransaccion = $transacciones[$i]->idtransaccion;
					    	$trazabilidad->save();			
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
								/*if(strlen($transacciones[$i]->codigo_requerimiento) <= 3 || strcmp(substr($transacciones[$i]->codigo_requerimiento,0,3),'REQ') != 0 )
								{
									$mensaje = $mensaje.'Código de la transacción N° '.$transacciones[$i]->idtransaccion.' no corresponde a un REMEDY <strong>Actualizar código</strong><br>';

									$cantidad_errores++;
								}else{*/
									$transacciones[$i]->idestado_transaccion = 4;
									$transacciones[$i]->fecha_inicio_procesando = date('Y-m-d H:i:s');
									$transacciones[$i]->save();
									$codigos_transacciones = $codigos_transacciones.' Transacción N° '.$transacciones[$i]->idtransaccion.' (Cod. Requerimiento: '.$transacciones[$i]->codigo_requerimiento.')<br>';			
									//TRAZABILIDAD POR TRANSACCION ACTUALIZADA EN EL SISTEMA
							    	$trazabilidad = new Trazabilidad;
							    	$trazabilidad->descripcion = 'Transacción cambio de estado PENDIENTE a PROCESANDO';
							    	$trazabilidad->fecha_registro = date('Y-m-d H:i:s');
							    	$trazabilidad->idtransaccion = $transacciones[$i]->idtransaccion;
							    	$trazabilidad->save();
								//} 
							}else
							{
								$transacciones[$i]->idestado_transaccion = 4;
								$transacciones[$i]->fecha_inicio_procesando = date('Y-m-d H:i:s');
								$transacciones[$i]->save();
								$codigos_transacciones = $codigos_transacciones.' Transacción N° '.$transacciones[$i]->idtransaccion.' (Cod. Requerimiento: '.$transacciones[$i]->codigo_requerimiento.')<br>';	
								//TRAZABILIDAD POR TRANSACCION ACTUALIZADA EN EL SISTEMA
						    	$trazabilidad = new Trazabilidad;
						    	$trazabilidad->descripcion = 'Transacción cambio de estado PENDIENTE a PROCESANDO';
						    	$trazabilidad->fecha_registro = date('Y-m-d H:i:s');
						    	$trazabilidad->idtransaccion = $transacciones[$i]->idtransaccion;
						    	$trazabilidad->save();		
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
			//$transaccion->observaciones = 'Transacción reactivada.';
			$transaccion->fecha_cierre = null;
			$transaccion->save();

			//TRAZABILIDAD POR TRANSACCION ACTUALIZADA EN EL SISTEMA
	    	$trazabilidad = new Trazabilidad;
	    	$trazabilidad->descripcion = 'Transacción reactivada a estado PENDIENTE';
	    	$trazabilidad->fecha_registro = date('Y-m-d H:i:s');
	    	$trazabilidad->idtransaccion = $transaccion->idtransaccion;
	    	$trazabilidad->save();	


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
			if($data["user"]->idrol == 1){
				
				
				$solicitud_id = Input::get('solicitud_id_eliminar_base');
				$solicitud = Solicitud::find($solicitud_id);
				
		 		// TRANSACCIONES
	    		$transacciones = Transaccion::buscarTransaccionesPorSolicitud($solicitud->idsolicitud)->get();
	    		if($transacciones != null && !$transacciones->isEmpty())
	    		{
	    			$cantidad_transacciones = count($transacciones);
	    			for($i=0;$i<$cantidad_transacciones;$i++)
	    			{
	    				$arr_trazabilidad = Trazabilidad::listarTrazabilidadPorTransaccion($transacciones[$i]->idtransaccion)->get();
	    				if($arr_trazabilidad != null && !$arr_trazabilidad->isEmpty())
	    				{
	    					$cantidad_trazabilidad = count($arr_trazabilidad);
	    					for($j=0;$j<$cantidad_trazabilidad;$j++)
	    					{
	    						$trazabilidad = Trazabilidad::find($arr_trazabilidad[$j]->idtrazabilidad_transaccion);
	    						$trazabilidad->forceDelete();			
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

			    return Redirect::to('solicitudes/mostrar_solicitud/'.$solicitud_id)->with('message','Se eliminó la base de transacciones de la solicitud '.$solicitud->codigo_solicitud);

			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_crear_transaccion()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2){
				// Validate the info, create rules for the inputs
				$solicitud_id = Input::get('solicitud_id_nueva_transaccion');

				$attributes = array(
					'accion' => 'Accion',
					'nombre_usuario' => 'Nombre Usuario',
					'numero_documento' => 'Numero Documento',
					'cargo' => 'Cargo Usuario',
					'aplicativo' => 'Aplicativo',
					'punto_venta' => 'Punto Venta'
				);

				$messages = array();

				$rules = array(
					'accion' => 'required',
					'nombre_usuario' => 'required|alpha_num_spaces_slash_dash_enter|max:200',
					'numero_documento' => 'required|numeric|digits_between:8,16',
					'cargo' => 'required|alpha_num_spaces_slash_dash_enter|max:200',
					'aplicativo'=>'required'
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('/principal_gestor_procesando/'.$solicitud_id)->withErrors($validator)->withInput(Input::all());
				}else{
					
					$accion = Input::get('accion');
					$nombre = Input::get('nombre_usuario');
					$documento = Input::get('numero_documento');
					$cargo = Input::get('cargo');
					$idherramienta = Input::get('aplicativo');
					//$idpunto_venta = Input::get('punto_venta');
					$punto_venta = Input::get('punto_venta');
					$fecha_registro = date('Y-m-d H:i:s');
					$idsolicitud = $solicitud_id;
					$idestado_transaccion = 3;

					$transaccion = new Transaccion;
					$transaccion->codigo_requerimiento = 'SIN_REQ';
					$transaccion->fecha_registro = $fecha_registro;
					$transaccion->cargo_canal = $cargo;
					$transaccion->numero_documento = $documento;
					$transaccion->nombre_usuario = $nombre;
					$transaccion->idherramienta = $idherramienta;
					//$transaccion->idpunto_venta = $idpunto_venta;
					$transaccion->nombre_punto_venta = $punto_venta;
					$transaccion->idsolicitud = $idsolicitud;
					$transaccion->accion_requerimiento = $accion;
					$transaccion->idestado_transaccion = $idestado_transaccion;
					$transaccion->iduser_created_by = $data["user"]->id;

					$transaccion->save();

					//TRAZABILIDAD POR TRANSACCION ACTUALIZADA EN EL SISTEMA
			    	$trazabilidad = new Trazabilidad;
			    	$trazabilidad->descripcion = 'Transacción registrada';
			    	$trazabilidad->fecha_registro = date('Y-m-d H:i:s');
			    	$trazabilidad->idtransaccion = $transaccion->idtransaccion;
			    	$trazabilidad->save();	

					//VALIDAR SI EL USUARIO ES BLOQUEADO LISTA VENA U OBSERVADOS
					$usuario_bloqueado = UsuarioObservado::buscarUsuarioPorDocumento($documento)->get();
					if($usuario_bloqueado == null || $usuario_bloqueado->isEmpty())
					{
						$usuario_bloqueado = UsuarioVena::buscarUsuarioPorDocumento($documento)->get();
						if($usuario_bloqueado == null || $usuario_bloqueado->isEmpty())
						{
							$transaccion->usuario_bloqueado = 0;
							$transaccion->save();
						}else
						{
							//CANCELAR
							$transaccion->usuario_bloqueado = 1;
							$transaccion->idestado_transaccion = 2;
							$transaccion->fecha_cierre = $fecha_registro;
							$transaccion->save();
						}
					}

					//validamos si ya no hay mas requerimientos pendientes de atencion
					$solicitud = Solicitud::find($idsolicitud);
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


					Session::flash('message', 'Se registró correctamente la transacción en la solicitud N° <strong>'.$solicitud->codigo_solicitud.'</strong>');
					
					return Redirect::to('/principal_gestor_procesando/'.$solicitud_id);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_eliminar_transaccion()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2){
				
				
				$idtransaccion = Input::get('transaccion_id_eliminar');
				$transaccion = Transaccion::find($idtransaccion);
				$solicitud = Solicitud::find($transaccion->idsolicitud);
				
				$arr_trazabilidad = Trazabilidad::listarTrazabilidadPorTransaccion($idtransaccion)->get();
				if($arr_trazabilidad != null && !$arr_trazabilidad->isEmpty())
				{
					$cantidad_trazabilidad = count($arr_trazabilidad);
					for($j=0;$j<$cantidad_trazabilidad;$j++)
					{
						$trazabilidad = Trazabilidad::find($arr_trazabilidad[$j]->idtrazabilidad_transaccion);
						$trazabilidad->forceDelete();			
					}
				}

 				$transaccion = Transaccion::find($idtransaccion);
				$transaccion->forceDelete();
    			
		 		// TRANSACCIONES
	    		$transacciones = Transaccion::buscarTransaccionesPorSolicitud($solicitud->idsolicitud)->get();
	    		if($transacciones == null || $transacciones->isEmpty())
	    		{
	    			//Regresar el ticket a su estado de pendiente;
				    $solicitud = Solicitud::find($solicitud->idsolicitud);
				    $solicitud->idestado_solicitud = 3;
				    $solicitud->fecha_inicio_procesando = null;
				    $solicitud->fur_cargado = 0;
				    $solicitud->save();
				    Session::flash('message', 'Se eliminó correctamente la transacción ID '.$idtransaccion.' en la solicitud N° <strong>'.$solicitud->codigo_solicitud.'</strong><br> La solicitud ha regresado al estado <strong>PENDIENTE</strong>');
					
					return Redirect::to('/principal_gestor_procesando/'.$solicitud->idsolicitud);
	    		}else
	    		{
	    			
	    			//VALIDAMOS POR SOLICITUD (A NIVEL TRANSACCION)
					$transacciones_procesando = Transaccion::buscarTransaccionesEstadoPorSolicitud($solicitud->idsolicitud,4)->get();
					$transacciones_pendientes = Transaccion::buscarTransaccionesEstadoPorSolicitud($solicitud->idsolicitud,3)->get();
					
					if(($transacciones_procesando == null || $transacciones_procesando->isEmpty()) && ($transacciones_pendientes == null || $transacciones_pendientes->isEmpty()) )
					{
						//¿TODOS ESTAN ATENDIDOS? o ¿TODOS ESTAN RECHAZADOS? o ¿HAY ALGUN RECHAZADO?
						$transacciones_atendidas = Transaccion::buscarTransaccionesEstadoPorSolicitud($solicitud->idsolicitud,1)->get();
						$transacciones_rechazadas = Transaccion::buscarTransaccionesEstadoPorSolicitud($solicitud->idsolicitud,2)->get();

						$cantidad_atendidas = 0;
						if($transacciones_atendidas == null || $transacciones_atendidas->isEmpty() || count($transacciones_atendidas) == 0)
							$cantidad_atendidas = 0;
						else
							$cantidad_atendidas = count($transacciones_atendidas);

						$cantidad_rechazadas = 0;
						if($transacciones_rechazadas == null || $transacciones_rechazadas->isEmpty() || count($transacciones_rechazadas) == 0)
							$cantidad_rechazadas = 0;
						else
							$cantidad_rechazadas = count($transacciones_rechazadas);

						$transacciones_totales = Transaccion::buscarTransaccionesPorSolicitud($solicitud->idsolicitud)->get();
						$cantidad_transacciones_totales = count($transacciones_totales);

						if($cantidad_transacciones_totales == $cantidad_atendidas)
						{
							//TODO ESTA ATENDIDO
							//quiere decir que ya no hay mas pendientes se cierra el ticket con estado atendido
							$solicitud->idestado_solicitud = 1;
							$solicitud->fecha_cierre = date('Y-m-d H:i:s');
							$solicitud->save();
							return Redirect::to('/principal_gestor')->with('message','Se rechazó la transacción N° '.$transaccion->idtransaccion.' (Cod. Requerimiento: '.$transaccion->codigo_requerimiento.')<br> '.'Se cerró la solicitud N°'.$solicitud->codigo_solicitud.' con estado <strong>ATENDIDO</strong>');

						}else
						{
							//si no se asumo que por lo menos hay 1 rechazado
							//quiere decir que ya no hay mas pendientes se cierra el ticket con estado cerrado con observaciones
							$solicitud->idestado_solicitud = 2;
							$solicitud->fecha_cierre = date('Y-m-d H:i:s');
							$solicitud->save();
							return Redirect::to('/principal_gestor')->with('message','Se rechazó la transacción N° '.$transaccion->idtransaccion.' (Cod. Requerimiento: '.$transaccion->codigo_requerimiento.')<br> '.'Se cerró la solicitud N°'.$solicitud->codigo_solicitud.' con estado <strong>CERRADO CON OBSERVACIONES</strong>');
						}

						
					}


					Session::flash('message', 'Se eliminó correctamente la transacción ID '.$idtransaccion.' en la solicitud N° <strong>'.$solicitud->codigo_solicitud.'</strong>');
					return Redirect::to('/principal_gestor_procesando/'.$solicitud->idsolicitud);
	    		}
			    
			    

			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_crear_trazabilidad()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2){
				// Validate the info, create rules for the inputs
				$transaccion_id = Input::get('transaccion_id_trazabilidad');
				$solicitud = Solicitud::find(Transaccion::find($transaccion_id)->idsolicitud);
				$attributes = array(
					'observacion' => 'Descripción de Observación'
				);

				$messages = array();

				$rules = array(
					'observacion' => 'required|alpha_num_spaces_slash_dash_enter|max:1000'
				);
				// Run the validation rules on the inputs from the form
				$validator = Validator::make(Input::all(), $rules,$messages,$attributes);
				// If the validator fails, redirect back to the form
				if($validator->fails()){
					return Redirect::to('/principal_gestor_procesando/'.$solicitud->idsolicitud)->withErrors($validator)->withInput(Input::all());
				}else{
					
					$observacion = Input::get('observacion');
					$trazabilidad_id = Input::get('trazabilidad_id_editar');
					if($trazabilidad_id != null)
					{
						//quiere decir que se hará una edición
						$trazabilidad = Trazabilidad::find($trazabilidad_id);
					}else
					{
						$trazabilidad = new Trazabilidad;	
					}
					
					$trazabilidad->descripcion = $observacion;
					$trazabilidad->fecha_registro = date('Y-m-d H:i:s');
					$trazabilidad->iduser_created_by = $data["user"]->id;
					$trazabilidad->idtransaccion = $transaccion_id;
					$trazabilidad->save();

					Session::flash('message', 'Se registró correctamente la observacion en la transacción ID '.$transaccion_id.' en la solicitud N° <strong>'.$solicitud->codigo_solicitud.'</strong>');
					
					return Redirect::to('/principal_gestor_procesando/'.$solicitud->idsolicitud);
				}
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	public function submit_eliminar_observacion()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2){
				
				
				$idtrazabilidad = Input::get('trazabilidad_id_eliminar');
				$trazabilidad = Trazabilidad::find($idtrazabilidad);
				$transaccion = Transaccion::find($trazabilidad->idtransaccion);
				$solicitud = Solicitud::find($transaccion->idsolicitud);
			
 				$trazabilidad->forceDelete();


 				Session::flash('message', 'Se eliminó correctamente la observacion en la transacción ID '.$transaccion->idtransaccion.' en la solicitud N° <strong>'.$solicitud->codigo_solicitud.'</strong>');
					
				return Redirect::to('/principal_gestor_procesando/'.$solicitud->idsolicitud);
    			
		 		
			    
			    

			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}
}
