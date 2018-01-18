<?php
class MenuPrincipalController extends BaseController {
	
	public function home_admin()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				
				$data["search_usuario"] = null;
				$data["solicitudes_data"] = array();
				$data["idusuario"] = null;
				$data["search_fecha"] = null;
				$data["usuario_busqueda"] = null;
				$data["fecha_busqueda"] = null;
				$mes_actual = date('m');
				$anho_actual = date('Y');

				$data["solicitudes_atendidos"] = Solicitud::buscarPorIdEstado(1,$mes_actual,$anho_actual)->get(); 

				if($data["solicitudes_atendidos"] == null || $data["solicitudes_atendidos"]->isEmpty()){
					$data["solicitudes_atendidos"] = array();
				}else				
				{
					
					$data["diferencia_fechas_atendidos"] = array();
					$cantidad_solicitudes = count($data["solicitudes_atendidos"]);					
					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_atendidos"][$i]->fecha_asignacion);				
						$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								
						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_atendidos"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
						$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
						array_push($data["diferencia_fechas_atendidos"],$diferencia_dias);
					}
				}

				$data["solicitudes_cerrados"] = Solicitud::buscarPorIdEstado(2,$mes_actual,$anho_actual)->get(); 
				if($data["solicitudes_cerrados"] == null || $data["solicitudes_cerrados"]->isEmpty())
					$data["solicitudes_cerrados"] = array();
				else
				{
					$data["diferencia_fechas_cerrados"] = array();
					$cantidad_solicitudes = count($data["solicitudes_cerrados"]);					
					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_cerrados"][$i]->fecha_asignacion);				
						$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								
						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_cerrados"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
						$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
						array_push($data["diferencia_fechas_cerrados"],$diferencia_dias);
					}
					
				}
				$data["solicitudes_pendientes"] = Solicitud::buscarPorIdEstado(3,$mes_actual,$anho_actual)->get();
				$data["slas_data_pendiente"] = array();
				$data["diferencia_fechas_pendiente"] = array();
				$data["diferencia_fechas_trabajo_pendiente"] = array();

				if($data["solicitudes_pendientes"] == null || $data["solicitudes_pendientes"]->isEmpty()){
					$data["solicitudes_pendientes"] = array();					
				}
				else
				{
					$cantidad_solicitudes = count($data["solicitudes_pendientes"]);					
					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						$sla = Sla::buscarSlaSolicitud($data["solicitudes_pendientes"][$i]->idsolicitud,$data["solicitudes_pendientes"][$i]->idtipo_solicitud)->get()[0];
						array_push($data["slas_data_pendiente"], $sla);
						$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_pendientes"][$i]->fecha_asignacion);				
						$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								
						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_pendientes"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
						$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
						array_push($data["diferencia_fechas_pendiente"],$diferencia_dias);
						//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
						$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
						$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
						
						//Obtener los dias feriados entre la fecha de hoy y la asignacion
						$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
						$cantidad_dias = 0;
						if($feriados != null )
						{
							$tamano = count($feriados);											
							for($j=0;$j<$tamano;$j++)
							{
								$dia = date('N',strtotime($feriados[$j]->valor_fecha));
								//Validar si el feriado coincide con un fin de semana para no contar dos veces
								if($dia != 6 && $dia != 7)
									$cantidad_dias++;							
							}
						}
						$diferencia_dias_fecha_trabajo -= $cantidad_dias;
						array_push($data["diferencia_fechas_trabajo_pendiente"],$diferencia_dias_fecha_trabajo);
					}
				}
				
				$data["solicitudes_procesando"] = Solicitud::buscarPorIdEstado(4,$mes_actual,$anho_actual)->get();
				$data["slas_data_procesando"] = array();
				$data["diferencia_fechas_procesando"] = array();
				$data["diferencia_fechas_trabajo_procesando"] = array(); 
				
				if($data["solicitudes_procesando"] == null || $data["solicitudes_procesando"]->isEmpty()){
					$data["solicitudes_procesando"] = array();
				}
				else{
					$cantidad_solicitudes = count($data["solicitudes_procesando"]);
					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						$sla = Sla::buscarSlaSolicitud($data["solicitudes_procesando"][$i]->idsolicitud,$data["solicitudes_procesando"][$i]->idtipo_solicitud)->get()[0];
						array_push($data["slas_data_procesando"], $sla);
						
						$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_procesando"][$i]->fecha_asignacion);				
						$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));

						$fecha_inicio_procesando=Carbon\Carbon::parse($data["solicitudes_procesando"][$i]->fecha_inicio_procesando);				
						$fecha_inicio_procesando_formateada = Carbon\Carbon::parse(date_format($fecha_inicio_procesando,'Y-m-d'));								
						
						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_procesando"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
						
						$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
						array_push($data["diferencia_fechas_procesando"],$diferencia_dias);
						//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
						$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
						$diferencia_dias_fecha_trabajo= $fecha_inicio_procesando_formateada->diffInWeekdays($fecha_actual);
						//Obtener los dias feriados entre la fecha de hoy y la asignacion
						$feriados = Feriado::buscarDiasFeriados($fecha_inicio_procesando_formateada,$fecha_actual)->get();
						
						$cantidad_dias = 0;
						if($feriados != null )
						{	
							$tamano = count($feriados);											
							for($j=0;$j<$tamano;$j++)
							{

								$dia = date('N',strtotime($feriados[$j]->valor_fecha));
								//Validar si el feriado coincide con un fin de semana para no contar dos veces
								if($dia != 6 && $dia != 7)
									$cantidad_dias++;							
							}
						}
						$diferencia_dias_fecha_trabajo -= $cantidad_dias;
						array_push($data["diferencia_fechas_trabajo_procesando"],$diferencia_dias_fecha_trabajo);
					}
				}
				
				$data["diferencia_fechas_rechazadas"] = null;
				$data["solicitudes_rechazadas"] = Solicitud::buscarPorIdEstado(5,$mes_actual,$anho_actual)->get(); 

				if($data["solicitudes_rechazadas"] == null || $data["solicitudes_rechazadas"]->isEmpty()){
					$data["solicitudes_rechazadas"] = array();
				}else
				{
					$cantidad_solicitudes = count($data["solicitudes_rechazadas"]);
					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						//$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_rechazadas"][$i]->fecha_asignacion);				
						//$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								
						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_rechazadas"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
						//$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
						
					}
					
				}

				$data["diferencia_fechas_anuladas"] = array();
				$data["solicitudes_anuladas"] = Solicitud::buscarPorIdEstado(6,$mes_actual,$anho_actual)->get(); 
				if($data["solicitudes_anuladas"] == null || $data["solicitudes_anuladas"]->isEmpty()){
					$data["solicitudes_anuladas"] = array();
				}else{
					$cantidad_solicitudes = count($data["solicitudes_anuladas"]);
					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_anuladas"][$i]->fecha_asignacion);				
						$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								
						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_anuladas"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
						$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
						array_push($data["diferencia_fechas_anuladas"],$diferencia_dias);
					}
					
				}
				$data["origen"] = 1; //1: sin usuario //2: con usuario
				$mensaje = '';
				$fecha_actual = date ('Y-m-d');
				$usuarios_observados = UsuarioObservado::buscarUsuarioCargadoHoy($fecha_actual)->get();
				
				if($usuarios_observados == null || $usuarios_observados->isEmpty())
					$mensaje = $mensaje.'Lista de Usuarios Observados no ha sido cargada hoy.<br>';
				else
					if(count($usuarios_observados) == 0)
						$mensaje = $mensaje.'Lista de Usuarios Observados no ha sido cargada hoy.<br>';

				$usuarios_vena = UsuarioVena::buscarUsuarioCargadoHoy($fecha_actual)->get();

				if($usuarios_vena == null || $usuarios_vena->isEmpty())
					$mensaje = $mensaje.'Lista de Usuarios Vena no ha sido cargada hoy.<br>';
				else
					if(count($usuarios_vena) == 0)
						$mensaje = $mensaje.'Lista de Usuarios Vena no ha sido cargada hoy.<br>';

				if(strcmp($mensaje,'')!=0)
					Session::flash('error','<strong>AVISO</strong><br>'.$mensaje);
				
				return View::make('MenuPrincipal/menuPrincipal',$data);
			}else
				return View::make('error/error',$data);
		}else{
			return View::make('error/error',$data);
		}
		
	}

	public function home_gestor(){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2)
			{
				$data["solicitud_id"] = null;
				$mes_actual = null;
				$anho_actual = null;
				$data["solicitudes_pendiente_data"] = Solicitud::buscarPorIdEstadoPorUsuario(3,$data["user"]->id,$mes_actual,$anho_actual)->get();
				
				$data["solicitudes_procesando_data"] = Solicitud::buscarPorIdEstadoPorUsuario(4,$data["user"]->id,$mes_actual,$anho_actual)->get();
				
				$data["idusuario"] = $data["user"]->id;
				$data["solicitudes_pendientes"] = count($data["solicitudes_pendiente_data"]);
				$data["solicitudes_procesando"] = count($data["solicitudes_procesando_data"]);
				
				$data["slas_data_pendiente"] = array();
				$data["diferencia_fechas_pendiente"] = array();
				$data["diferencia_fechas_trabajo_pendiente"] = array();
				$cantidad_solicitudes = count($data["solicitudes_pendiente_data"]);
				for($i=0;$i<$cantidad_solicitudes;$i++)
				{
					$sla = Sla::buscarSlaSolicitud($data["solicitudes_pendiente_data"][$i]->idsolicitud,$data["solicitudes_pendiente_data"][$i]->idtipo_solicitud)->get()[0];
					array_push($data["slas_data_pendiente"], $sla);
					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_pendiente_data"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								
					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_pendiente_data"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
					array_push($data["diferencia_fechas_pendiente"],$diferencia_dias);
					//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
					$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
					$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
					
					//Obtener los dias feriados entre la fecha de hoy y la asignacion
					$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
					$cantidad_dias = 0;
					if($feriados != null )
					{
						$tamano = count($feriados);											
						for($j=0;$j<$tamano;$j++)
						{
							$dia = date('N',strtotime($feriados[$j]->valor_fecha));
							//Validar si el feriado coincide con un fin de semana para no contar dos veces
							if($dia != 6 && $dia != 7)
								$cantidad_dias++;							
						}
					}
					$diferencia_dias_fecha_trabajo -= $cantidad_dias;
					array_push($data["diferencia_fechas_trabajo_pendiente"],$diferencia_dias_fecha_trabajo);
				}
				$data["slas_data_procesando"] = array();
				$data["diferencia_fechas_procesando"] = array();
				$data["diferencia_fechas_trabajo_procesando"] = array();
				$cantidad_solicitudes = count($data["solicitudes_procesando_data"]);
				for($i=0;$i<$cantidad_solicitudes;$i++)
				{
					$sla = Sla::buscarSlaSolicitud($data["solicitudes_procesando_data"][$i]->idsolicitud,$data["solicitudes_procesando_data"][$i]->idtipo_solicitud)->get()[0];
					array_push($data["slas_data_procesando"], $sla);
					
					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_procesando_data"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_inicio_procesando=Carbon\Carbon::parse($data["solicitudes_procesando_data"][$i]->fecha_inicio_procesando);				
					$fecha_inicio_procesando_formateada = Carbon\Carbon::parse(date_format($fecha_inicio_procesando,'Y-m-d'));					

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_procesando_data"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));

					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
					array_push($data["diferencia_fechas_procesando"],$diferencia_dias);
					//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
					$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
					$diferencia_dias_fecha_trabajo= $fecha_inicio_procesando_formateada->diffInWeekdays($fecha_actual);
					
					//Obtener los dias feriados entre la fecha de hoy y la asignacion
					$feriados = Feriado::buscarDiasFeriados($fecha_inicio_procesando_formateada,$fecha_actual)->get();
					$cantidad_dias = 0;
					if($feriados != null )
					{
						$tamano = count($feriados);											
						for($j=0;$j<$tamano;$j++)
						{
							$dia = date('N',strtotime($feriados[$j]->valor_fecha));
							//Validar si el feriado coincide con un fin de semana para no contar dos veces
							if($dia != 6 && $dia != 7)
								$cantidad_dias++;							
						}
					}
					$diferencia_dias_fecha_trabajo -= $cantidad_dias;
					array_push($data["diferencia_fechas_trabajo_procesando"],$diferencia_dias_fecha_trabajo);
				}
				$data["search_codigo_solicitud"] = null;
				/*echo '<pre>';
				var_dump(Transaccion::buscarTransaccionesPorSolicitud(1)->get());
				echo '</pre>';*/
				return View::make('MenuPrincipal/menuPrincipalGestor',$data);
			}else
				return View::make('error/error',$data);
		}else{
			return View::make('error/error',$data);
		}
	}

	public function buscar_solicitudes_usuario()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if(($data["user"]->idrol == 1) ){
				
				$data["search_usuario"] = Input::get('search_usuario');
				$data["search_fecha"] = Input::get('search_fecha');
				$mes_busqueda = null;
				$anho_busqueda = null;
				$data["fecha_busqueda"] = null;
				$data["usuario_busqueda"] = null;

				if(Input::get('search_usuario') == null && $data["search_fecha"] == null){
					return Redirect::to('/principal_admin');
				}

				if($data["search_fecha"] != null){
					$mes_busqueda = date('m',strtotime(DateTime::createFromFormat('d-m-Y','01-'.$data["search_fecha"])->format('d-m-Y')));
					$anho_busqueda = date('Y',strtotime(DateTime::createFromFormat('d-m-Y','01-'.$data["search_fecha"])->format('d-m-Y')));
					$mes_busqueda_nombre = $mes_busqueda;
					$data["fecha_busqueda"] = $mes_busqueda_nombre.'-'.$anho_busqueda;
				}else{
					if($data["search_usuario"] == null)
					{
						$mes_busqueda = date('m');
						$anho_busqueda = date('Y');	
						$mes_busqueda_nombre = $mes_busqueda;
						$data["fecha_busqueda"] = $mes_busqueda_nombre.'-'.$anho_busqueda;
					}else
					{
						$mes_busqueda = null;
						$anho_busqueda = null;
					}
					
				}
				
				if($data["search_usuario"] == null){
					$idusuario = null;
				}else{
					$usuario = User::buscarPorNombre($data["search_usuario"])->get();
					if($usuario!=null && !$usuario->isEmpty())
					{
						$idusuario = $usuario[0]->id;
						$data["usuario_busqueda"] = $usuario[0]->nombre.' '.$usuario[0]->apellido_paterno.' '.$usuario[0]->apellido_materno;	
					}else
						$idusuario = null;
					
				}		


				
				$data["solicitudes_atendidos"] = Solicitud::buscarPorIdEstadoPorUsuario(1,$idusuario,$mes_busqueda,$anho_busqueda)->get(); 

				if($data["solicitudes_atendidos"] == null || $data["solicitudes_atendidos"]->isEmpty()){
					$data["solicitudes_atendidos"] = array();
				}else				
				{
					
					$data["diferencia_fechas_atendidos"] = array();
					$cantidad_solicitudes = count($data["solicitudes_atendidos"]);					
					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_atendidos"][$i]->fecha_asignacion);				
						$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								
						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_atendidos"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
						$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
						array_push($data["diferencia_fechas_atendidos"],$diferencia_dias);
					}
				}

				$data["solicitudes_cerrados"] = Solicitud::buscarPorIdEstadoPorUsuario(2,$idusuario,$mes_busqueda,$anho_busqueda)->get(); 
				if($data["solicitudes_cerrados"] == null || $data["solicitudes_cerrados"]->isEmpty())
					$data["solicitudes_cerrados"] = array();
				else
				{
					$data["diferencia_fechas_cerrados"] = array();
					$cantidad_solicitudes = count($data["solicitudes_cerrados"]);					
					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_cerrados"][$i]->fecha_asignacion);				
						$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								
						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_cerrados"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
						$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
						array_push($data["diferencia_fechas_cerrados"],$diferencia_dias);
					}
					
				}
				$data["solicitudes_pendientes"] = Solicitud::buscarPorIdEstadoPorUsuario(3,$idusuario,$mes_busqueda,$anho_busqueda)->get(); 
				$data["slas_data_pendiente"] = array();
				$data["diferencia_fechas_pendiente"] = array();
				$data["diferencia_fechas_trabajo_pendiente"] = array();
				if($data["solicitudes_pendientes"] == null || $data["solicitudes_pendientes"]->isEmpty()){
					$data["solicitudes_pendientes"] = array();					
				}
				else
				{
					$cantidad_solicitudes = count($data["solicitudes_pendientes"]);					
					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						$sla = Sla::buscarSlaSolicitud($data["solicitudes_pendientes"][$i]->idsolicitud,$data["solicitudes_pendientes"][$i]->idtipo_solicitud)->get()[0];
						array_push($data["slas_data_pendiente"], $sla);
						
						$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_pendientes"][$i]->fecha_asignacion);				
						$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_pendientes"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));

						$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
						array_push($data["diferencia_fechas_pendiente"],$diferencia_dias);
						//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
						$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
						$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
						
						//Obtener los dias feriados entre la fecha de hoy y la asignacion
						$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
						$cantidad_dias = 0;
						if($feriados != null )
						{
							$tamano = count($feriados);											
							for($j=0;$j<$tamano;$j++)
							{
								$dia = date('N',strtotime($feriados[$j]->valor_fecha));
								//Validar si el feriado coincide con un fin de semana para no contar dos veces
								if($dia != 6 && $dia != 7)
									$cantidad_dias++;							
							}
						}
						$diferencia_dias_fecha_trabajo -= $cantidad_dias;
						array_push($data["diferencia_fechas_trabajo_pendiente"],$diferencia_dias_fecha_trabajo);
					}
				}
				
				$data["solicitudes_procesando"] = Solicitud::buscarPorIdEstadoPorUsuario(4,$idusuario,$mes_busqueda,$anho_busqueda)->get(); 
				$data["slas_data_procesando"] = array();
				$data["diferencia_fechas_procesando"] = array();
				$data["diferencia_fechas_trabajo_procesando"] = array(); 
				
				if($data["solicitudes_procesando"] == null || $data["solicitudes_procesando"]->isEmpty()){
					$data["solicitudes_procesando"] = array();
				}
				else{
					$cantidad_solicitudes = count($data["solicitudes_procesando"]);
					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						$sla = Sla::buscarSlaSolicitud($data["solicitudes_procesando"][$i]->idsolicitud,$data["solicitudes_procesando"][$i]->idtipo_solicitud)->get()[0];
						array_push($data["slas_data_procesando"], $sla);
						
						$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_procesando"][$i]->fecha_asignacion);				
						$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								
						
						$fecha_inicio_procesando=Carbon\Carbon::parse($data["solicitudes_procesando"][$i]->fecha_inicio_procesando);
						$fecha_inicio_procesando_formateada = Carbon\Carbon::parse(date_format($fecha_inicio_procesando,'Y-m-d'));

						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_procesando"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
						
						$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
						array_push($data["diferencia_fechas_procesando"],$diferencia_dias);
						//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
						$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
						$diferencia_dias_fecha_trabajo= $fecha_inicio_procesando_formateada->diffInWeekdays($fecha_actual);
						
						//Obtener los dias feriados entre la fecha de hoy y la asignacion
						$feriados = Feriado::buscarDiasFeriados($fecha_inicio_procesando_formateada,$fecha_actual)->get();
						$cantidad_dias = 0;
						if($feriados != null )
						{
							$tamano = count($feriados);											
							for($j=0;$j<$tamano;$j++)
							{
								$dia = date('N',strtotime($feriados[$j]->valor_fecha));
								//Validar si el feriado coincide con un fin de semana para no contar dos veces
								if($dia != 6 && $dia != 7)
									$cantidad_dias++;							
							}
						}
						$diferencia_dias_fecha_trabajo -= $cantidad_dias;
						array_push($data["diferencia_fechas_trabajo_procesando"],$diferencia_dias_fecha_trabajo);
					}
				}

				$data["diferencia_fechas_rechazadas"] = array();
				$data["solicitudes_rechazadas"] = Solicitud::buscarPorIdEstadoPorUsuario(5,$idusuario,$mes_busqueda,$anho_busqueda)->get(); 
				//echo '<pre>'; var_dump($data["solicitudes_rechazadas"]); echo '</pre>';
				if($data["solicitudes_rechazadas"] == null || $data["solicitudes_rechazadas"]->isEmpty()){
					$data["solicitudes_rechazadas"] = array();
				}else
				{
					$cantidad_solicitudes = count($data["solicitudes_rechazadas"]);
					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_rechazadas"][$i]->fecha_asignacion);				
						$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								
						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_rechazadas"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
						$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
						array_push($data["diferencia_fechas_rechazadas"],$diferencia_dias);
					}
					
				}

				$data["diferencia_fechas_anuladas"] = array();
				$data["solicitudes_anuladas"] = Solicitud::buscarPorIdEstadoPorUsuario(6,$idusuario,$mes_busqueda,$anho_busqueda)->get(); 
				if($data["solicitudes_anuladas"] == null || $data["solicitudes_anuladas"]->isEmpty()){
					$data["solicitudes_anuladas"] = array();
				}else{
					$cantidad_solicitudes = count($data["solicitudes_anuladas"]);
					for($i=0;$i<$cantidad_solicitudes;$i++)
					{
						$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_anuladas"][$i]->fecha_asignacion);				
						$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								
						$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_anuladas"][$i]->fecha_solicitud);				
						$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
						$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
						array_push($data["diferencia_fechas_anuladas"],$diferencia_dias);
					}
					
				}
				$data["idusuario"] = $idusuario;
				
				
				return View::make('MenuPrincipal/menuPrincipal',$data);
			}else
				return View::make('error/error',$data);
		}else{
			return View::make('error/error',$data);
		}
		
	}

	public function buscar_solicitud_codigo()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2)
			{
				$data["search_codigo_solicitud"] = Input::get('search_codigo_solicitud');
				if($data["search_codigo_solicitud"]==null)
					return MenuPrincipalController::home_gestor();
				$solicitud = Solicitud::buscarPorCodigoSolicitud($data["search_codigo_solicitud"] )->get();
				
				if($solicitud == null || $solicitud->isEmpty())
				{
					Session::flash('error','Solicitud no encontrada');
					$data["idusuario"] = $data["user"]->id;
					$data["solicitudes_pendientes"] = 0;
					$data["solicitudes_procesando"] = 0; 
					$data["solicitudes_pendiente_data"] = array();  
					$data["solicitudes_procesando_data"] = array(); 				
					$data["slas_data_pendiente"] = array();
					$data["diferencia_fechas_pendiente"] = array();
					$data["diferencia_fechas_trabajo_pendiente"] = array();
					$data["slas_data_procesando"] = array();
					$data["diferencia_fechas_procesando"] = array();
					$data["diferencia_fechas_trabajo_procesando"] = array();
					return View::make('MenuPrincipal/menuPrincipalGestor',$data);
				}
				if($solicitud[0]->idestado_solicitud == 3)
				{
					$data["idusuario"] = $data["user"]->id;
					$data["solicitudes_pendientes"] = 1;
					$data["solicitudes_procesando"] = 0;  
					
					$data["solicitudes_pendiente_data"] = $solicitud;
					$data["solicitudes_procesando_data"] = array();
					$data["slas_data_pendiente"] = array();
					$data["diferencia_fechas_pendiente"] = array();
					$data["diferencia_fechas_trabajo_pendiente"] = array();
					$data["slas_data_procesando"] = array();
					$data["diferencia_fechas_procesando"] = array();
					$data["diferencia_fechas_trabajo_procesando"] = array();
					$sla = Sla::buscarSlaSolicitud($data["solicitudes_pendiente_data"][0]->idsolicitud,$data["solicitudes_pendiente_data"][0]->idtipo_solicitud)->get()[0];
					array_push($data["slas_data_pendiente"], $sla);
					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_pendiente_data"][0]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								
					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_pendiente_data"][0]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
					array_push($data["diferencia_fechas_pendiente"],$diferencia_dias);
					//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
					$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
					$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
					
					//Obtener los dias feriados entre la fecha de hoy y la asignacion
					$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
					$cantidad_dias = 0;
					if($feriados != null )
					{
						$tamano = count($feriados);											
						for($j=0;$j<$tamano;$j++)
						{
							$dia = date('N',strtotime($feriados[$j]->valor_fecha));
							//Validar si el feriado coincide con un fin de semana para no contar dos veces
							if($dia != 6 && $dia != 7)
								$cantidad_dias++;							
						}
					}
					$diferencia_dias_fecha_trabajo -= $cantidad_dias;
					array_push($data["diferencia_fechas_trabajo_pendiente"],$diferencia_dias_fecha_trabajo);
				}else if($solicitud[0]->idestado_solicitud == 4)
				{
					$data["idusuario"] = $data["user"]->id;
					$data["solicitudes_pendientes"] = 0;
					$data["solicitudes_procesando"] = 1;  
					
					$data["solicitudes_pendiente_data"] = array();
					$data["solicitudes_procesando_data"] = $solicitud; 
					$data["slas_data_pendiente"] = array();
					$data["diferencia_fechas_pendiente"] = array();
					$data["diferencia_fechas_trabajo_pendiente"] = array();
					$data["slas_data_procesando"] = array();
					$data["diferencia_fechas_procesando"] = array();
					$data["diferencia_fechas_trabajo_procesando"] = array();
					$sla = Sla::buscarSlaSolicitud($data["solicitudes_procesando_data"][0]->idsolicitud,$data["solicitudes_procesando_data"][0]->idtipo_solicitud)->get()[0];
					array_push($data["slas_data_procesando"], $sla);
					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_procesando_data"][0]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));
					
					$fecha_inicio_procesando=Carbon\Carbon::parse($data["solicitudes_procesando"][$i]->fecha_inicio_procesando);
					$fecha_inicio_procesando_formateada = Carbon\Carbon::parse(date_format($fecha_inicio_procesando,'Y-m-d'));

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_procesando_data"][0]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
					array_push($data["diferencia_fechas_procesando"],$diferencia_dias);
					//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
					$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
					$diferencia_dias_fecha_trabajo= $fecha_inicio_procesando_formateada->diffInWeekdays($fecha_actual);
					
					//Obtener los dias feriados entre la fecha de hoy y la asignacion
					$feriados = Feriado::buscarDiasFeriados($fecha_inicio_procesando_formateada,$fecha_actual)->get();
					$cantidad_dias = 0;
					if($feriados != null )
					{
						$tamano = count($feriados);											
						for($j=0;$j<$tamano;$j++)
						{
							$dia = date('N',strtotime($feriados[$j]->valor_fecha));
							//Validar si el feriado coincide con un fin de semana para no contar dos veces
							if($dia != 6 && $dia != 7)
								$cantidad_dias++;							
						}
					}
					$diferencia_dias_fecha_trabajo -= $cantidad_dias;
					array_push($data["diferencia_fechas_trabajo_procesando"],$diferencia_dias_fecha_trabajo);
				}else
				{
					Session::flash('error','Solicitud no se encuentra en pendiente ni procesando.');
					$data["idusuario"] = $data["user"]->id;
					$data["solicitudes_pendientes"] = 0;
					$data["solicitudes_procesando"] = 0; 
					$data["solicitudes_pendiente_data"] = array();  
					$data["solicitudes_procesando_data"] = array();   				
					$data["slas_data_pendiente"] = array();
					$data["diferencia_fechas_pendiente"] = array();
					$data["diferencia_fechas_trabajo_pendiente"] = array();
					$data["slas_data_procesando"] = array();
					$data["diferencia_fechas_procesando"] = array();
					$data["diferencia_fechas_trabajo_procesando"] = array();
					return View::make('MenuPrincipal/menuPrincipalGestor',$data);
				}
				
				return View::make('MenuPrincipal/menuPrincipalGestor',$data);
			}else
				return View::make('error/error',$data);
		}else{
			return View::make('error/error',$data);
		}
	}

	public function resumen_usuarios()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1)
			{
				$data["resumen_usuario"] = Solicitud::resumenSolicitudesPorUsuario();
				$data["resumen_sector"] = Solicitud::resumenSolicitudesPorSectorPorUsuario();
				$data["resumen_sector_total"] = Solicitud::resumenSolicitudesPorSector();
				$data["contador_sector_total"] = 0;
				$data["search_fecha"] = null;
				return View::make('MenuPrincipal/resumenSolicitudesUsuarios',$data);
			}else
				return View::make('error/error',$data);
		}else{
			return View::make('error/error',$data);
		}
	}

	public function resumen_usuarios_mes()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1)
			{
				$data["search_fecha"] = Input::get('search_fecha');
				
				if($data["search_fecha"] == null)
					return MenuPrincipalController::resumen_usuarios();
				$fecha = DateTime::createFromFormat('d-m-Y','01-'.$data["search_fecha"]);
				
				$mes = date('m',strtotime($fecha->format('d-m-Y')));
				$anho = date('Y',strtotime($fecha->format('d-m-Y')));
				$data["resumen_usuario"] = Solicitud::buscarResumenSolicitudesPorUsuarioPorFecha($mes,$anho);
				$data["resumen_sector"] = Solicitud::buscarResumenSolicitudesPorSectorPorUsuarioPorFecha($mes,$anho);
				$data["resumen_sector_total"] = Solicitud::buscarResumenSolicitudesPorSectorPorFecha($mes,$anho);
				$data["contador_sector_total"] = 0;
				return View::make('MenuPrincipal/resumenSolicitudesUsuarios',$data);
			}else
				return View::make('error/error',$data);
		}else{
			return View::make('error/error',$data);
		}
	}

	public function generar_reporte_gestor()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2)
			{
				$mes_actual = null;
				$anho_actual = null;
				$id_usuario = $data["user"]->id;
				$fecha_actual = date('Y-m-d H:i:s');

				$value = Excel::create('BASE TICKETS '.$fecha_actual, function($excel) use ($mes_actual,$anho_actual,$id_usuario){
					$excel->sheet('TICKETS PENDIENTES', function($sheet) use ($mes_actual,$anho_actual,$id_usuario){
						
						$sheet->row(1, array(
							     'N°','CODIGO_SOLICITUD','FECHA_SOLICITUD','NUMERO_DE_CORTE','TIPO_SOLICITUD','ESTADO_SOLICITUD','HERRAMIENTA_SOLICITADA','APLICATIVO_AGRUPADO','ENTIDAD','CANAL','SECTOR','USUARIO_ASIGNADO','ASUNTO','FECHA_ASIGNACION','DIAS_ASIGNACION','SLA_PENDIENTE','DIAS_LABORALES','SEMAFORO'
							));

						$solicitudes_pendientes = Solicitud::buscarPorIdEstadoPorUsuario(3,$id_usuario,$mes_actual,$anho_actual)->get();
						
						$cantidad_solicitudes = count($solicitudes_pendientes);

						for($i=0;$i<$cantidad_solicitudes;$i++)
						{
							$sla = Sla::buscarSlaSolicitud($solicitudes_pendientes[$i]->idsolicitud,$solicitudes_pendientes[$i]->idtipo_solicitud)->get()[0];
							
							$fecha_asignacion=Carbon\Carbon::parse($solicitudes_pendientes[$i]->fecha_asignacion);				
							$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								
							$fecha_solicitud=Carbon\Carbon::parse($solicitudes_pendientes[$i]->fecha_solicitud);				
							$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
							$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
							
							//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
							$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
							$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
							
							//Obtener los dias feriados entre la fecha de hoy y la asignacion
							$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
							$cantidad_dias = 0;
							if($feriados != null )
							{
								$tamano = count($feriados);											
								for($j=0;$j<$tamano;$j++)
								{
									$dia = date('N',strtotime($feriados[$j]->valor_fecha));
									//Validar si el feriado coincide con un fin de semana para no contar dos veces
									if($dia != 6 && $dia != 7)
										$cantidad_dias++;							
								}
							}
							$diferencia_dias_fecha_trabajo -= $cantidad_dias;
							$fecha_solicitud = date('Y-m-d',strtotime($solicitudes_pendientes[$i]->fecha_solicitud));
							$fecha_asignacion = date('Y-m-d',strtotime($solicitudes_pendientes[$i]->fecha_asignacion));
							$nombre_tipo_solicitud = $solicitudes_pendientes[$i]->nombre_tipo_solicitud;
							$nombre_estado_solicitud = $solicitudes_pendientes[$i]->nombre_estado_solicitud;
							if($solicitudes_pendientes[$i]->ticket_reasignado == 1)
								$nombre_estado_solicitud = $nombre_estado_solicitud.' - reasignado';
							$nombre_aplicativo = "NO DETECTADO";
							$nombre_denominacion = "NO DETECTADO";
							if($solicitudes_pendientes[$i]->idherramienta != NULL){
								$herramienta = Herramienta::find($solicitudes_pendientes[$i]->idherramienta);
								$nombre_aplicativo = $herramienta->nombre;
								$nombre_denominacion = DenominacionHerramienta::find($herramienta->iddenominacion_herramienta)->nombre;
							}
							if($solicitudes_pendientes[$i]->numero_corte != null)
								$numero_corte = 'CORTE N° '.$solicitudes_pendientes[$i]->numero_corte;
							else
								$numero_corte = 'SOLICITUD MANUAL';
							$entidad = Entidad::find($solicitudes_pendientes[$i]->identidad);
							$canal = Canal::find($entidad->idcanal);
							$sector = Sector::find($canal->idsector);
							$usuario = User::find($solicitudes_pendientes[$i]->idusuario_asignado);
							$asunto = $solicitudes_pendientes[$i]->asunto;
							$sla_pendiente = $sla->sla_pendiente;

							$sheet->row($i+2, array(
							     $i+1,$solicitudes_pendientes[$i]->codigo_solicitud,$fecha_solicitud,$numero_corte,$nombre_tipo_solicitud,$nombre_estado_solicitud,$nombre_aplicativo,$nombre_denominacion,$entidad->nombre,$canal->nombre,$sector->nombre,$usuario->nombre.' '.$usuario->apellido_paterno.' '.$usuario->apellido_materno,$solicitudes_pendientes[$i]->asunto,$fecha_asignacion,$diferencia_dias,$sla_pendiente,$diferencia_dias_fecha_trabajo,
							));

							$backGround = NULL;

							if($diferencia_dias_fecha_trabajo < $sla_pendiente - 1)
							{
								$backGround = "#42f00d";
							}elseif($sla_pendiente - $diferencia_dias_fecha_trabajo <= 1 &&  $sla_pendiente - $diferencia_dias_fecha_trabajo >= 0)
							{
								$backGround = "#f0a20d";
							}else
							{
								$backGround = "#f00d0d";
							}

							$sheet->cells('R'.($i+2).':R'.($i+2),function($cells) use ($backGround) {
								$cells->setBackground($backGround);
							});
						}

					});

					$excel->sheet('TICKETS PROCESANDO', function($sheet) use ($mes_actual,$anho_actual,$id_usuario)  {
						
						$sheet->row(1, array(
							     'N°','CODIGO_SOLICITUD','FECHA_SOLICITUD','NUMERO_DE_CORTE','TIPO_SOLICITUD','ESTADO_SOLICITUD','HERRAMIENTA_SOLICITADA','APLICATIVO_AGRUPADO','ENTIDAD','CANAL','SECTOR','USUARIO_ASIGNADO','ASUNTO','FECHA_ASIGNACION','DIAS_ASIGNACION','FECHA_INICIO_PROCESANDO','SLA_PROCESANDO','DIAS_LABORALES','SEMAFORO'
							));

						$solicitudes_procesando = Solicitud::buscarPorIdEstadoPorUsuario(4,$id_usuario,$mes_actual,$anho_actual)->get();
						
						$cantidad_solicitudes = count($solicitudes_procesando);

						for($i=0;$i<$cantidad_solicitudes;$i++)
						{
							$sla = Sla::buscarSlaSolicitud($solicitudes_procesando[$i]->idsolicitud,$solicitudes_procesando[$i]->idtipo_solicitud)->get()[0];
							
							$fecha_asignacion=Carbon\Carbon::parse($solicitudes_procesando[$i]->fecha_asignacion);				
							$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								
							$fecha_solicitud=Carbon\Carbon::parse($solicitudes_procesando[$i]->fecha_solicitud);				
							$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
							$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
							
							$fecha_inicio_procesando=Carbon\Carbon::parse($solicitudes_procesando[$i]->fecha_inicio_procesando);
							$fecha_inicio_procesando_formateada = Carbon\Carbon::parse(date_format($fecha_inicio_procesando,'Y-m-d'));

							//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
							$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
							$diferencia_dias_fecha_trabajo= $fecha_inicio_procesando_formateada->diffInWeekdays($fecha_actual);
							
							//Obtener los dias feriados entre la fecha de hoy y la asignacion
							$feriados = Feriado::buscarDiasFeriados($fecha_inicio_procesando_formateada,$fecha_actual)->get();
							$cantidad_dias = 0;
							if($feriados != null )
							{
								$tamano = count($feriados);											
								for($j=0;$j<$tamano;$j++)
								{
									$dia = date('N',strtotime($feriados[$j]->valor_fecha));
									//Validar si el feriado coincide con un fin de semana para no contar dos veces
									if($dia != 6 && $dia != 7)
										$cantidad_dias++;							
								}
							}
							if($solicitudes_procesando[$i]->numero_corte != null)
								$numero_corte = 'CORTE N° '.$solicitudes_procesando[$i]->numero_corte;
							else
								$numero_corte = 'SOLICITUD MANUAL';
							$diferencia_dias_fecha_trabajo -= $cantidad_dias;
							$fecha_solicitud = date('Y-m-d',strtotime($solicitudes_procesando[$i]->fecha_solicitud));
							$fecha_asignacion = date('Y-m-d',strtotime($solicitudes_procesando[$i]->fecha_asignacion));
							$fecha_inicio_procesando = date('Y-m-d',strtotime($solicitudes_procesando[$i]->fecha_inicio_procesando));
							$nombre_tipo_solicitud = $solicitudes_procesando[$i]->nombre_tipo_solicitud;
							$nombre_estado_solicitud = $solicitudes_procesando[$i]->nombre_estado_solicitud;
							if($solicitudes_procesando[$i]->ticket_reasignado == 1)
								$nombre_estado_solicitud = $nombre_estado_solicitud.' - reasignado';
							$nombre_aplicativo = "NO DETECTADO";
							$nombre_denominacion = "NO DETECTADO";
							if($solicitudes_procesando[$i]->idherramienta != NULL){
								$herramienta = Herramienta::find($solicitudes_procesando[$i]->idherramienta);
								$nombre_aplicativo = $herramienta->nombre;
								$nombre_denominacion = DenominacionHerramienta::find($herramienta->iddenominacion_herramienta)->nombre;
							}

							$entidad = Entidad::find($solicitudes_procesando[$i]->identidad);
							$canal = Canal::find($entidad->idcanal);
							$sector = Sector::find($canal->idsector);
							$usuario = User::find($solicitudes_procesando[$i]->idusuario_asignado);
							$asunto = $solicitudes_procesando[$i]->asunto;
							$sla_procesando = $sla->sla_procesando;

							$sheet->row($i+2, array(
							     $i+1,$solicitudes_procesando[$i]->codigo_solicitud,$fecha_solicitud,$numero_corte,$nombre_tipo_solicitud,$nombre_estado_solicitud,$nombre_aplicativo,$nombre_denominacion,$entidad->nombre,$canal->nombre,$sector->nombre,$usuario->nombre.' '.$usuario->apellido_paterno.' '.$usuario->apellido_materno,$solicitudes_procesando[$i]->asunto,$fecha_asignacion,$diferencia_dias,$fecha_inicio_procesando,$sla_procesando,$diferencia_dias_fecha_trabajo,
							));

							$backGround = NULL;


							if($diferencia_dias_fecha_trabajo < $sla_procesando - 1)
							{
								$backGround = "#42f00d";

							}elseif($sla_procesando - $diferencia_dias_fecha_trabajo  <= 1 &&  $sla_procesando - $diferencia_dias_fecha_trabajo  >= 0)
							{
								$backGround = "#f0a20d";
							}else
							{
								$backGround = "#f00d0d";
							}

							$sheet->cells('S'.($i+2).':S'.($i+2),function($cells) use ($backGround) {
								$cells->setBackground($backGround);
							});
						}

					});

				})->download('xls');

				
				
				//return View::make('MenuPrincipal/menuPrincipalGestor',$data);
			}else
				return View::make('error/error',$data);
		}else{
			return View::make('error/error',$data);
		}
	}

	public function home_gestor_procesando($idsolicitud=null){
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 2)
			{
				$mes_actual = null;
				$anho_actual = null;
				$data["solicitudes_pendiente_data"] = Solicitud::buscarPorIdEstadoPorUsuario(3,$data["user"]->id,$mes_actual,$anho_actual)->get();
				
				$data["solicitudes_procesando_data"] = Solicitud::buscarPorIdEstadoPorUsuario(4,$data["user"]->id,$mes_actual,$anho_actual)->get();
				
				$data["idusuario"] = $data["user"]->id;
				$data["solicitudes_pendientes"] = count($data["solicitudes_pendiente_data"]);
				$data["solicitudes_procesando"] = count($data["solicitudes_procesando_data"]);
				
				$data["slas_data_pendiente"] = array();
				$data["diferencia_fechas_pendiente"] = array();
				$data["diferencia_fechas_trabajo_pendiente"] = array();
				$cantidad_solicitudes = count($data["solicitudes_pendiente_data"]);
				for($i=0;$i<$cantidad_solicitudes;$i++)
				{
					$sla = Sla::buscarSlaSolicitud($data["solicitudes_pendiente_data"][$i]->idsolicitud,$data["solicitudes_pendiente_data"][$i]->idtipo_solicitud)->get()[0];
					array_push($data["slas_data_pendiente"], $sla);
					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_pendiente_data"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								
					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_pendiente_data"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));
					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
					array_push($data["diferencia_fechas_pendiente"],$diferencia_dias);
					//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
					$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
					$diferencia_dias_fecha_trabajo= $fecha_asignacion_formateada->diffInWeekdays($fecha_actual);
					
					//Obtener los dias feriados entre la fecha de hoy y la asignacion
					$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_actual)->get();
					$cantidad_dias = 0;
					if($feriados != null )
					{
						$tamano = count($feriados);											
						for($j=0;$j<$tamano;$j++)
						{
							$dia = date('N',strtotime($feriados[$j]->valor_fecha));
							//Validar si el feriado coincide con un fin de semana para no contar dos veces
							if($dia != 6 && $dia != 7)
								$cantidad_dias++;							
						}
					}
					$diferencia_dias_fecha_trabajo -= $cantidad_dias;
					array_push($data["diferencia_fechas_trabajo_pendiente"],$diferencia_dias_fecha_trabajo);
				}
				$data["slas_data_procesando"] = array();
				$data["diferencia_fechas_procesando"] = array();
				$data["diferencia_fechas_trabajo_procesando"] = array();
				$cantidad_solicitudes = count($data["solicitudes_procesando_data"]);
				for($i=0;$i<$cantidad_solicitudes;$i++)
				{
					$sla = Sla::buscarSlaSolicitud($data["solicitudes_procesando_data"][$i]->idsolicitud,$data["solicitudes_procesando_data"][$i]->idtipo_solicitud)->get()[0];
					array_push($data["slas_data_procesando"], $sla);
					
					$fecha_asignacion=Carbon\Carbon::parse($data["solicitudes_procesando_data"][$i]->fecha_asignacion);				
					$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion,'Y-m-d'));								

					$fecha_inicio_procesando=Carbon\Carbon::parse($data["solicitudes_procesando_data"][$i]->fecha_inicio_procesando);				
					$fecha_inicio_procesando_formateada = Carbon\Carbon::parse(date_format($fecha_inicio_procesando,'Y-m-d'));					

					$fecha_solicitud=Carbon\Carbon::parse($data["solicitudes_procesando_data"][$i]->fecha_solicitud);				
					$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud,'Y-m-d'));

					$diferencia_dias = $fecha_solicitud_formateada->diffInDays($fecha_asignacion_formateada);
					array_push($data["diferencia_fechas_procesando"],$diferencia_dias);
					//Para determinar el valor del semaforo se debe realizar en funcion a la fecha de asignacion
					$fecha_actual = Carbon\Carbon::parse(date_format(Carbon\Carbon::now(),'Y-m-d'));
					$diferencia_dias_fecha_trabajo= $fecha_inicio_procesando_formateada->diffInWeekdays($fecha_actual);
					
					//Obtener los dias feriados entre la fecha de hoy y la asignacion
					$feriados = Feriado::buscarDiasFeriados($fecha_inicio_procesando_formateada,$fecha_actual)->get();
					$cantidad_dias = 0;
					if($feriados != null )
					{
						$tamano = count($feriados);											
						for($j=0;$j<$tamano;$j++)
						{
							$dia = date('N',strtotime($feriados[$j]->valor_fecha));
							//Validar si el feriado coincide con un fin de semana para no contar dos veces
							if($dia != 6 && $dia != 7)
								$cantidad_dias++;							
						}
					}
					$diferencia_dias_fecha_trabajo -= $cantidad_dias;
					array_push($data["diferencia_fechas_trabajo_procesando"],$diferencia_dias_fecha_trabajo);
				}
				$data["search_codigo_solicitud"] = null;
				
				if($idsolicitud == null)
					$data["solicitud_id"] = null;
				else
					$data["solicitud_id"] = $idsolicitud;

				return View::make('MenuPrincipal/menuPrincipalGestor',$data);
			}else
				return View::make('error/error',$data);
		}else{
			return View::make('error/error',$data);
		}
	}

}