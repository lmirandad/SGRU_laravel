<?php

class MenuPrincipalAdminPlanillaController extends BaseController {

	public function home_admin_planilla()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Admin Planilla
			if($data["user"]->idrol == 5){
				
				$data["search_usuario"] = null;
				$data["search_fecha"] = null;

				$data["usuarios"] = User::buscarGestoresPlanilla()->lists('nombre','id');
				
				$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

				$mes_actual = date('m');
				$anho_actual = date('Y');

				$data["cargas"] = CargaArchivoPlanilla::listarCargasArchivoPlanillaMes($mes_actual,$anho_actual)->get();
				
				return View::make('Planilla/AdminPlanilla/menuPrincipalAdminPlanilla',$data);
			}else
				return View::make('error/error',$data);
		}else{
			return View::make('error/error',$data);
		}
		
	}

	public function buscar_cargas_usuario()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 5){
				$data["search_usuario"] = Input::get('search_usuario');
				$data["search_fecha"] = Input::get('search_fecha');

				if(Input::get('search_usuario') == null && $data["search_fecha"] == null){
					$data["cargas"] = CargaArchivoPlanilla::listarCargasArchivoPlanillaMes($mes_actual,$anho_actual)->get();
				}else
				{	

					if(strcmp($data["search_fecha"],'') == 0)
					{
						$mes_busqueda = null;
						$anho_busqueda = null;
					}else
					{
						$mes_busqueda = date('m',strtotime(DateTime::createFromFormat('d-m-Y','01-'.$data["search_fecha"])->format('d-m-Y')));
						$anho_busqueda = date('Y',strtotime(DateTime::createFromFormat('d-m-Y','01-'.$data["search_fecha"])->format('d-m-Y')));	
					}
					
					$data["cargas"] = CargaArchivoPlanilla::buscarCargasArchivoPlanilla($mes_busqueda,$anho_busqueda,$data["search_usuario"])->get();
				}

				$data["usuarios"] = User::buscarGestoresPlanilla()->lists('nombre','id');

				$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

				
				return View::make('Planilla/AdminPlanilla/menuPrincipalAdminPlanilla',$data);
								
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function descargar_planilla()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Admin Planilla
			if($data["user"]->idrol == 5){
				
				$usuarios = UsuarioPlanilla::listarUsuariosPlanilla()->get();

				if($usuarios == null || $usuarios->isEmpty() || count($usuarios) == 0)
				{
					return Redirect::to('/principal_admin_planilla')->with('error','No hay planilla registrada.');
				}

				$fecha_reporte = date('Y-m-d H:i:s');
				$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

				$value = Excel::create('REPORTE PLANILLA', function($excel) use  ($usuarios,$meses){
					$excel->sheet('BASE', function($sheet) use ($usuarios,$meses)  {
						
						$sheet->row(1, array(
							     'N°','TIPO DOCUMENTO','NUMERO DOCUMENTO','NOMBRES','APELLIDO PATERNO','APELLIDO MATERNO','CANAL','DETALLE CANAL','SUBDETALLE_CANAL','SOCIO','RUC','ENTIDAD','PUNTO DE VENTA','ROL','FECHA REGISTRO','MES','MES_NUMERO','ANHO','RESPONSABLE'
							));
						
						$cantidad_registros = count($usuarios);

						for($i = 0; $i<$cantidad_registros; $i++)
						{
							
							$tipo_documento = $usuarios[$i]->tipo_documento;
							$numero_documento = $usuarios[$i]->numero_documento;
							$nombre = $usuarios[$i]->nombre;
							$apellido_paterno = $usuarios[$i]->apellido_paterno;
							$apellido_materno = $usuarios[$i]->apellido_materno;
							$canal = $usuarios[$i]->canal;
							$detalle_canal = $usuarios[$i]->detalle_canal;
							$subdetalle_canal = $usuarios[$i]->subdetalle_canal;
							$socio = $usuarios[$i]->socio;
							$ruc = $usuarios[$i]->ruc_socio;
							$entidad = $usuarios[$i]->entidad;
							$punto_venta = $usuarios[$i]->punto_venta;
							$rol = $usuarios[$i]->rol;

							$carga_archivo = CargaArchivoPlanilla::find($usuarios[$i]->idcarga_archivo_planilla);

							$responsable = User::find($carga_archivo->iduser_registrador);

							$nombre_responsable = $responsable->nombre.' '.$responsable->apellido_paterno.' '.$responsable->apellido_materno;

							$fecha_registro = date('d-m-Y',strtotime($usuarios[$i]->created_at));

							$anho = date('Y',strtotime($usuarios[$i]->created_at));
							$mes = date('m',strtotime($usuarios[$i]->created_at));

							$nombre_mes = $anho.' - '.$meses[$mes-1];
							$valor_mes = $anho.' - '.$mes;



							$sheet->row($i+2, array(
							     $i+1,$tipo_documento,$numero_documento,$nombre,$apellido_paterno,$apellido_materno,$canal,$detalle_canal,$subdetalle_canal,$socio,$ruc,$entidad,$punto_venta,$rol,$fecha_registro,$nombre_mes,$valor_mes,$anho,$nombre_responsable
							));

						}

						

					});
				})->download('xlsx');
				return true;
			}else
				return View::make('error/error',$data);
		}else{
			return View::make('error/error',$data);
		}
	}

	public function descargar_planilla_usuario($idcarga_archivo=null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Admin Planilla
			if($data["user"]->idrol == 5 && $idcarga_archivo){
				
				$carga_archivo = CargaArchivoPlanilla::find($idcarga_archivo);

				if($carga_archivo == null)
				{
					return Redirect::to('/principal_admin_planilla')->with('error','Carga no existe.');
				}				

				$usuarios = UsuarioPlanilla::buscarUsuariosPlanilla($idcarga_archivo)->get();

				if($usuarios == null || $usuarios->isEmpty() || count($usuarios) == 0)
				{
					return Redirect::to('/principal_admin_planilla')->with('error','No hay planilla registrada.');
				}

				$username_gestor = User::find($carga_archivo->iduser_registrador)->username;

				$fecha_reporte = date('Y-m-d H:i:s');
				$meses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

				$value = Excel::create('REPORTE PLANILLA USUARIO'.$username_gestor, function($excel) use  ($usuarios,$meses){
					$excel->sheet('BASE', function($sheet) use ($usuarios,$meses)  {
						
						$sheet->row(1, array(
							     'N°','TIPO DOCUMENTO','NUMERO DOCUMENTO','NOMBRES','APELLIDO PATERNO','APELLIDO MATERNO','CANAL','DETALLE CANAL','SUBDETALLE_CANAL','SOCIO','RUC','ENTIDAD','PUNTO DE VENTA','ROL','FECHA REGISTRO','MES','MES_NUMERO','ANHO'
							));
						
						$cantidad_registros = count($usuarios);

						for($i = 0; $i<$cantidad_registros; $i++)
						{
							
							$tipo_documento = $usuarios[$i]->tipo_documento;
							$numero_documento = $usuarios[$i]->numero_documento;
							$nombre = $usuarios[$i]->nombre;
							$apellido_paterno = $usuarios[$i]->apellido_paterno;
							$apellido_materno = $usuarios[$i]->apellido_materno;
							$canal = $usuarios[$i]->canal;
							$detalle_canal = $usuarios[$i]->detalle_canal;
							$subdetalle_canal = $usuarios[$i]->subdetalle_canal;
							$socio = $usuarios[$i]->socio;
							$ruc = $usuarios[$i]->ruc_socio;
							$entidad = $usuarios[$i]->entidad;
							$punto_venta = $usuarios[$i]->punto_venta;
							$rol = $usuarios[$i]->rol;

							$fecha_registro = date('d-m-Y',strtotime($usuarios[$i]->created_at));

							$anho = date('Y',strtotime($usuarios[$i]->created_at));
							$mes = date('m',strtotime($usuarios[$i]->created_at));

							$nombre_mes = $anho.' - '.$meses[$mes-1];
							$valor_mes = $anho.' - '.$mes;



							$sheet->row($i+2, array(
							     $i+1,$tipo_documento,$numero_documento,$nombre,$apellido_paterno,$apellido_materno,$canal,$detalle_canal,$subdetalle_canal,$socio,$ruc,$entidad,$punto_venta,$rol,$fecha_registro,$nombre_mes,$valor_mes,$anho
							));

						}

						

					});
				})->download('xlsx');
				return true;
			}else
				return View::make('error/error',$data);
		}else{
			return View::make('error/error',$data);
		}
	}

	public function eliminar_base_carga()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Admin Planilla
			if($data["user"]->idrol == 5){
				
				$data["search_usuario"] = null;
				$data["search_fecha"] = null;

				$data["usuarios"] = User::buscarGestoresPlanilla()->lists('nombre','id');
				
				$data["meses"] = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");

				$mes_actual = date('m');
				$anho_actual = date('Y');				

				$idcarga_archivo = Input::get('carga_archivo_id_eliminar');
				$carga_archivo = CargaArchivoPlanilla::find($idcarga_archivo);
				$usuario_responsable = User::find($carga_archivo->iduser_registrador);
				$fecha_registro = date('d-m-Y H:i:s',strtotime($carga_archivo->fecha_carga_archivo));
				
				$lista_usuarios = UsuarioPlanilla::buscarUsuariosPlanilla($idcarga_archivo)->get();
				if($lista_usuarios == null || $lista_usuarios->isEmpty())
				{
					$carga_archivo->forceDelete();
				}else
				{
					$cantidad_registros = count($lista_usuarios);
					for($i=0;$i<$cantidad_registros;$i++)
					{
						$lista_usuarios[$i]->forceDelete();
					}
					$carga_archivo->forceDelete();
				}

				$data["cargas"] = CargaArchivoPlanilla::listarCargasArchivoPlanillaMes($mes_actual,$anho_actual)->get();

				Session::flash('message','Se ha eliminado la base subida por '.$usuario_responsable->nombre.' '.$usuario_responsable->apellido_paterno.' '.$usuario_responsable->apellido_materno.' en la fecha: '.$fecha_registro);

				return View::make('Planilla/AdminPlanilla/menuPrincipalAdminPlanilla',$data);
			}else
				return View::make('error/error',$data);
		}else{
			return View::make('error/error',$data);
		}
	}
}
