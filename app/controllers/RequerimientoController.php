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

			    for($i = 0; $i < $cantidad_requerimientos; $i++)
			    {
			    	//revisar dato por dato
			    	$accion = $resultado[$i][0];
			    	$aplicativo = $resultado[$i][1];
			    	$canal = $resultado[$i][2];
			    	$entidad = $resultado[$i][3];
			    	$cargo = $resultado[$i][4];
			    	$perfil = $resultado[$i][5];
			    	$documento =strval($resultado[$i][6]);
			    	$nombre = $resultado[$i][7];
			    	$punto_venta = $resultado[$i][8];
			    	$codigo_requerimiento = $resultado[$i][9];

			    	

			    	$resultado_registro = "";
			    	$procesar = true;

			    	//validar si el dato está vacío

			    	$requerimiento = new Requerimiento;

			    	$requerimiento->fecha_registro = date('Y-m-d H:i:s');
			    	$requerimiento->codigo_requerimiento = $codigo_requerimiento;
			    	$requerimiento->cargo_canal = $cargo;
			    	$requerimiento->perfil_aplicativo = $perfil;
			    	$requerimiento->nombre_usuario = $nombre;
			    	$requerimiento->accion_requerimiento = $accion;

			    	//2. Aplicativo
			    	if($aplicativo == null || strcmp($aplicativo,'')==0)
			    	{
			    		$resultado_registro = $resultado_registro."Requerimiento sin aplicativo.\n";
			    		$procesar = false;
			    	}else
			    	{
			    		$idherramienta = RequerimientoController::buscarHerramienta($aplicativo,$herramientas);
			    		if($idherramienta == 0)
			    		{
			    			$resultado_registro = $resultado_registro."Herramienta no existente.\n";
			    			$procesar = false;
			    		}else
			    			$requerimiento->idherramienta = $idherramienta;
			    	}

			    	//3. documento
			    	if($documento == null || !ctype_digit($documento))
			    	{
			    		$resultado_registro = $resultado_registro."Requerimiento sin DNI válido.\n";
			    		$procesar = false;

			    		$requerimiento->numero_documento = null;
			    	}else{			    		
			    		//validacion lista vena u observados
			    		$usuario_observado = UsuarioObservado::buscarUsuarioPorDocumento($documento)->get();
			    		if($usuario_observado == null || $usuario_observado->isEmpty())
			    		{
			    			$usuario_vena = UsuarioVena::buscarUsuarioPorDocumento($documento)->get();
			    			if($usuario_vena == null || $usuario_vena->isEmpty())
				    		{
				    			$requerimiento->numero_documento = $documento;
				    			
				    		}else
				    		{
				    			$resultado_registro = $resultado_registro."Usuario DNI <strong> ".$documento."</strong> bloqueado (Lista Vena).\n";
				    			$procesar = false;

				    		}

			    		}else
			    		{
			    			$resultado_registro = $resultado_registro."Usuario DNI <strong> ".$documento."</strong> bloqueado (Lista de Observados).\n";
			    			$procesar = false;

			    		}
			    	}

			    	//4. Punto de Venta
			    	if($punto_venta == null || strcmp($punto_venta,'')==0)
			    	{
			    		$resultado_registro = $resultado_registro."Requerimiento sin punto de venta.\n";
			    		$procesar = false;
			    	}else{
			    		$idpunto_venta = RequerimientoController::buscarPuntoVenta($punto_venta);
			    		if($idpunto_venta == 0)
			    		{
			    			$resultado_registro = $resultado_registro."Punto de Venta no existente.\n";
			    			$procesar = false;
			    		}else{
			    			$requerimiento->idpunto_venta =$idpunto_venta;
			    		}
			    	}



			    	if($procesar == false)
			    	{
			    		$requerimiento->observaciones = $resultado_registro;
			    		$requerimiento->idestado_requerimiento = 2;
						$requerimiento->fecha_cierre = date('Y-m-d H:i:s');			    		
			    	}else
			    	{
			    		$requerimiento->observaciones = null;
			    		$requerimiento->idestado_requerimiento = 3;
			    	}

			    	$requerimiento->idsolicitud = $solicitud_id;
			    	$requerimiento->iduser_created_by = $data["user"]->id;

			    	$requerimiento->save();
			    }

			    $solicitud = Solicitud::find($solicitud_id);
		    	$solicitud->fur_cargado = 1;
		    	$solicitud->idestado_solicitud = 4;
		    	$solicitud->fecha_inicio_procesando = date('Y-m-d H:i:s');
		    	$solicitud->save();

		    	//pero que pasa si todos se rechazaron: habrá que validarlo
		    	//Si valida si existen requerimientos rechazadas
				$requerimientos = Requerimiento::buscarRequerimientosPorEstadoPorIdSolicitud($solicitud->idsolicitud,2)->get();
				if($requerimientos != null)
				{
					
					$cantidad_encontrada = count($requerimientos);
					if($cantidad_encontrada == $cantidad_requerimientos)
					{
						$solicitud->idestado_solicitud = 2;
						$solicitud->fecha_cierre = date('Y-m-d H:i:s');
						$solicitud->save();
						
						return Redirect::to('/principal_gestor')->with('message','Se cerró la solicitud N°'.$solicitud->codigo_solicitud.'. Todos los requerimientos se encuentran rechazados.<br> ');
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
			$requerimientos = Requerimiento::buscarRequerimientosPorSolicitud($solicitud_id)->get();

			if($requerimientos == null || $requerimientos->isEmpty())
				return Response::json(array( 'success' => true,'tiene_requerimientos'=>false,'requerimientos' => null),200);
			
			return Response::json(array( 'success' => true,'tiene_requerimientos' => true,'requerimientos' => $requerimientos),200);
			
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
			
			$idrequerimiento = Input::get('idrequerimiento');
			$requerimiento = Requerimiento::find($idrequerimiento);

			if($requerimiento == null )
				return Response::json(array( 'success' => true,'requerimiento' => null),200);
			
			return Response::json(array( 'success' => true,'requerimiento' => $requerimiento),200);
			
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
			
			$idrequerimiento = Input::get('idrequerimiento');
			$requerimiento = Requerimiento::find($idrequerimiento);

			if($requerimiento == null )
				return Response::json(array( 'success' => true,'requerimiento' => null),200);
			
			return Response::json(array( 'success' => true,'requerimiento' => $requerimiento),200);
			
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
				$idrequerimientos = Input::get('idrequerimientos');
				$idsolicitud = Input::get('solicitud_id_mostrar');

				$solicitud = Solicitud::find($idsolicitud);

				$cantidad_requerimientos = count($codigos);
				for($i = 0; $i<$cantidad_requerimientos; $i++)
				{
					$requerimiento = Requerimiento::find($idrequerimientos[$i]);
					if($requerimiento->idestado_requerimiento == 3){
						$requerimiento->codigo_requerimiento = $codigos[$i];
						// buscar si existe otro requerimiento con el mismo codigo
						/*$requerimientos_repetidos = Requerimiento::buscarRequerimientosPorCodigo($codigos[$i],$requerimiento->idrequerimiento,$idsolicitud)->get();

						if($requerimientos_repetidos == null || $requerimientos_repetidos->isEmpty())
						{*/
							//si no hay otro entonces podemos guardar
							$requerimiento->save();
						/*}else
						{
							//como ya existe otro usuario no se actualizará este requerimiento
							

						}*/

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
					
					$idrequerimiento = Input::get('requerimiento_id_rechazar');
					$observacion = Input::get('observacion');
					$requerimiento = Requerimiento::find($idrequerimiento);
					$requerimiento->observaciones = $observacion;
					$requerimiento->idestado_requerimiento = 2;
					$requerimiento->fecha_cierre = date('Y-m-d H:i:s');
					$requerimiento->save();
					//validamos si ya no hay mas requerimientos pendientes de atencion
					$solicitud = Solicitud::find($requerimiento->idsolicitud);
					$requerimientos = Requerimiento::buscarRequerimientosPorEstadoPorIdSolicitud($solicitud->idsolicitud,3)->get();
					if($requerimientos == null || $requerimientos->isEmpty())
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
				
				
					
				$idrequerimiento = Input::get('requerimiento_id_finalizar');
				$requerimiento = Requerimiento::find($idrequerimiento);
				$requerimiento->idestado_requerimiento = 1;
				$requerimiento->fecha_cierre = date('Y-m-d H:i:s');
				$requerimiento->save();
				//validamos si ya no hay mas requerimientos pendientes de atencion
				$solicitud = Solicitud::find($requerimiento->idsolicitud);
				$requerimientos = Requerimiento::buscarRequerimientosPorEstadoPorIdSolicitud($solicitud->idsolicitud,3)->get();
				if($requerimientos == null || $requerimientos->isEmpty())
				{
					//Si valida si existen requerimientos rechazadas
					$requerimientos = Requerimiento::buscarRequerimientosPorEstadoPorIdSolicitud($solicitud->idsolicitud,2)->get();
					if($requerimientos == null || $requerimientos->isEmpty())
					{
						//no existen requerimientos rechazado entonces se cerrará con estado Atendido
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

				return Redirect::to('/principal_gestor')->with('message','Se finalizó la atención del requerimiento '.$requerimiento->codigo_requerimiento);
				

			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

}
