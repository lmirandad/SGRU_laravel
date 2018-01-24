<?php

class ReporteriaController extends BaseController {

	public function mostrar_reporteria()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1)
			{	
				
				$data["fecha_desde_solicitud"] = null;
				$data["fecha_desde_requerimiento"] = null;
				$data["fecha_hasta_solicitud"] = null;
				$data["fecha_hasta_requerimiento"] = null;
				
				return View::make('Reporteria/reporteria',$data);
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}

	public function generar_reporte_solicitudes()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1){

				$data["fecha_desde_solicitud"] = Input::get('fecha_desde_solicitud');
				$data["fecha_hasta_solicitud"] = Input::get('fecha_hasta_solicitud');
				
				$data["fecha_desde_requerimiento"] = null;		
				$data["fecha_hasta_requerimiento"] = null;

				if($data["fecha_desde_solicitud"] == null || $data["fecha_hasta_solicitud"] == null)
				{
					Session::flash('error','Para generar la base de solicitudes se deben ingresar las fechas de corte.');
					return View::make('Reporteria/reporteria',$data);
				}

				$fecha_desde_solicitud = $data["fecha_desde_solicitud"];
				$fecha_hasta_solicitud = $data["fecha_hasta_solicitud"];		

				$solicitudes = Solicitud::buscarSolicitudesPorFechas($fecha_desde_solicitud,$fecha_hasta_solicitud)->get();

				if($solicitudes == null || $solicitudes->isEmpty() || count($solicitudes) == 0)
				{
					return Redirect::to('/reportes')->with('error','No hay solicitudes registradas dentro de las fechas seleccionadas');
				}

				$fecha_reporte = date('Y-m-d H:i:s');

				$value = Excel::create('REPORTE SOLICITUDES '.$fecha_reporte, function($excel) use  ($solicitudes){
					$excel->sheet('BASE', function($sheet) use ($solicitudes)  {
						
						$sheet->row(1, array(
							     'N°','CODIGO_SOLICITUD','FECHA_SOLICITUD','FECHA_ASIGNACION','NUMERO_CORTE','FECHA_INICIO_PROCESANDO','FECHA_CIERRE','TIPO_SOLICITUD','ESTADO_SOLICITUD','HERRAMIENTA_SOLICITADA','APLICATIVO_AGRUPADO','ENTIDAD','CANAL','RESPONSABLE_CANAL','SECTOR','USUARIO_ASIGNADO','ASUNTO','SLA_PENDIENTE','SLA_PROCESANDO','SLA_REAL_ASIGNACION','SLA_REAL_RESPUESTA_GESTOR','SLA_PROCESAMIENTO','SLA_GESTION','SLA_TOTAL_TICKET'
							));
						
						$cantidad_solicitudes = count($solicitudes);

						for($i = 0; $i<$cantidad_solicitudes; $i++)
						{
							$codigo_solicitud = $solicitudes[$i]->codigo_solicitud;
							$fecha_solicitud = date('Y-m-d',strtotime($solicitudes[$i]->fecha_solicitud));
							$fecha_asignacion = null;
							$fecha_inicio_procesando = null;
							$numero_corte ='';
							if($solicitudes[$i]->numero_corte != null)
								$numero_corte = 'CORTE N° '.$solicitudes[$i]->numero_corte;
							else
								$numero_corte = 'SOLICITUD MANUAL';
							
							if($solicitudes[$i]->fecha_inicio_procesando != null)
								$fecha_inicio_procesando = date('Y-m-d H:i:s',strtotime($solicitudes[$i]->fecha_inicio_procesando));
							
							$fecha_cierre = null;
							if($solicitudes[$i]->fecha_cierre != null)
								$fecha_cierre = date('Y-m-d H:i:s',strtotime($solicitudes[$i]->fecha_cierre));

							$nombre_tipo_solicitud = $solicitudes[$i]->nombre_tipo_solicitud;
							$nombre_estado_solicitud = $solicitudes[$i]->nombre_estado_solicitud;
							if($solicitudes[$i]->ticket_reasignado == 1)
								$nombre_estado_solicitud = $nombre_estado_solicitud.' - reasignado';

							$nombre_usuario_asignado = null;
							$nombre_herramienta = null;
							$nombre_denominacion = null;
							$nombre_entidad = null;
							$nombre_canal = null;
							$nombre_sector = null;

							$nombre_responsable = 'SIN RESPONSABLE';



							
							$asunto = $solicitudes[$i]->asunto;
							$sla_pendiente = null;
							$sla_procesando = null;
							
							if($solicitudes[$i]->idestado_solicitud != 5)
							{
								//fecha_asignacion
								//usuario_asignado
								$asignacion = Asignacion::buscarPorIdSolicitud($solicitudes[$i]->idsolicitud)->get();
								if($asignacion != null && !$asignacion->isEmpty()){
									$fecha_asignacion = date('Y-m-d H:i:s',strtotime($asignacion[0]->fecha_asignacion));
									$usuario_asignado = UsuariosXAsignacion::buscarUsuarioActual($asignacion[0]->idasignacion)->get();
									if($usuario_asignado != null && !$usuario_asignado->isEmpty()){
										$usuario = User::withTrashed()->find($usuario_asignado[0]->idusuario_asignado);
										$nombre_usuario_asignado = $usuario->nombre.' '.$usuario->apellido_paterno.' '.$usuario->apellido_materno;
									}
								}						

							}
							//herramienta
							if($solicitudes[$i]->idherramienta == null){
								$nombre_herramienta = 'NO DETECTADO';
								$nombre_denominacion = 'NO DETECTADO';
							}else
							{
								$herramienta = Herramienta::find($solicitudes[$i]->idherramienta);
								$nombre_herramienta = Herramienta::find($solicitudes[$i]->idherramienta)->nombre;
								$nombre_denominacion = DenominacionHerramienta::find($herramienta->iddenominacion_herramienta)->nombre; 
							}
							
							//entidad
							if($solicitudes[$i]->identidad == null)
							{
								$nombre_entidad = '';$nombre_canal = '';$nombre_sector = '';
							}else
							{
								$entidad = Entidad::find($solicitudes[$i]->identidad);
								$canal = Canal::find($entidad->idcanal);
								$sector = Sector::find($canal->idsector);

								if($canal->idusuario_responsable != null){
									$usuario_responsable = User::find($canal->idusuario_responsable);
									$nombre_responsable = $usuario_responsable->nombre.' '.$usuario_responsable->apellido_paterno.' '.$usuario_responsable->apellido_materno;
								}
								
								$nombre_entidad = $entidad->nombre;
								$nombre_canal = $canal->nombre;
								$nombre_sector = $sector->nombre;
							}
							//sla_pendiente
							//sla_procesando
							
							if($solicitudes[$i]->idsla != null ){
								
								$sla = Sla::buscarSlaSolicitud($solicitudes[$i]->idsolicitud,$solicitudes[$i]->idtipo_solicitud)->get();
								if($sla != null && !$sla->isEmpty())
								{
									$sla_pendiente = $sla[0]->sla_pendiente;
									$sla_procesando = $sla[0]->sla_procesando;
								}
							}

							$fecha_solicitud_formateada = null;
							$fecha_asignacion_formateada = null;
							$fecha_inicio_procesando_formateada = null;
							$fecha_cierre_formateada = null;

							if($solicitudes[$i]->fecha_solicitud != null)
							{
								$fecha_solicitud_f=Carbon\Carbon::parse($solicitudes[$i]->fecha_solicitud);				
								$fecha_solicitud_formateada = Carbon\Carbon::parse(date_format($fecha_solicitud_f,'Y-m-d'));
							}

							if($solicitudes[$i]->fecha_asignacion != null)
							{
								$fecha_asignacion_f=Carbon\Carbon::parse($solicitudes[$i]->fecha_asignacion);				
								$fecha_asignacion_formateada = Carbon\Carbon::parse(date_format($fecha_asignacion_f,'Y-m-d'));
							}

							if($solicitudes[$i]->fecha_inicio_procesando != null)
							{
								$fecha_inicio_procesando_f=Carbon\Carbon::parse($solicitudes[$i]->fecha_inicio_procesando);				
								$fecha_inicio_procesando_formateada = Carbon\Carbon::parse(date_format($fecha_inicio_procesando_f,'Y-m-d'));
							}

							if($solicitudes[$i]->fecha_cierre != null)
							{
								$fecha_cierre_f=Carbon\Carbon::parse($solicitudes[$i]->fecha_cierre);				
								$fecha_cierre_formateada = Carbon\Carbon::parse(date_format($fecha_cierre_f,'Y-m-d'));
							}

							//Calculando los SLA REALES

							//1. SLA_ASIGNACION
							$sla_real_asignacion = 0;

							if($solicitudes[$i]->idestado_solicitud != 5)
							{
								$sla_real_asignacion = $fecha_solicitud_formateada->diffInWeekdays($fecha_asignacion_formateada);

								//Obtener los dias feriados entre la fecha de hoy y la asignacion
								$feriados = Feriado::buscarDiasFeriados($fecha_solicitud,$fecha_asignacion_formateada)->get();
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

								$sla_real_asignacion -= $cantidad_dias;

								if($sla_real_asignacion == 0)
									$sla_real_asignacion = 1;
							}else{
								$sla_real_asignacion = 'SOLICITUD SIN ASIGNAR';
							}
							

							//2. SLA_RESPUESTA_GESTOR
							$sla_real_respuesta_gestor = 0;

							//SON SOLICITUDES QUE YA HAN SIDO ASIGNADAS, TRABAJADAS O YA ESTÁN SIENDO PROCESADAS.
							if($solicitudes[$i]->idestado_solicitud == 4 || $solicitudes[$i]->idestado_solicitud == 1 || $solicitudes[$i]->idestado_solicitud == 2)
							{
								$sla_real_respuesta_gestor = $fecha_asignacion_formateada->diffInWeekdays($fecha_inicio_procesando_formateada);

								//Obtener los dias feriados entre la fecha de hoy y la asignacion
								$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_inicio_procesando_formateada)->get();
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

								$sla_real_respuesta_gestor -= $cantidad_dias;
								if($sla_real_respuesta_gestor == 0)
									$sla_real_respuesta_gestor = 1;								

							}else
							{
								$sla_real_respuesta_gestor = 'SOLICITUD PENDIENTE';
							}

							//3. SLA_PROCESAMIENTO
							$sla_procesamiento = 0;

							if($solicitudes[$i]->idestado_solicitud == 1 || $solicitudes[$i]->idestado_solicitud == 2 ){

								$sla_procesamiento = $fecha_inicio_procesando_formateada->diffInWeekdays($fecha_cierre_formateada);

								//Obtener los dias feriados entre la fecha de hoy y la asignacion
								$feriados = Feriado::buscarDiasFeriados($fecha_inicio_procesando_formateada,$fecha_cierre_formateada)->get();
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

								$sla_procesamiento -= $cantidad_dias;
								if($sla_procesamiento == 0)
									$sla_procesamiento = 1;

							}else if($solicitudes[$i]->idestado_solicitud == 5 || $solicitudes[$i]->idestado_solicitud == 6){
								$sla_procesamiento = 'SOLICITUD NO ATENDIDA';
							}
							else{
								$sla_procesamiento = 'SOLICITUD NO FINALIZADA';
							}

							$sla_gestion = 0;
							

							//SLA GESTION (DESDE FECHA ASIGNACION HASTA LA FECHA DE CIERRE)
							if($solicitudes[$i]->idestado_solicitud == 1 || $solicitudes[$i]->idestado_solicitud == 2 || $solicitudes[$i]->idestado_solicitud == 5 || $solicitudes[$i]->idestado_solicitud == 6)
							{
								
								$sla_gestion = $fecha_asignacion_formateada->diffInWeekdays($fecha_cierre_formateada);

								//Obtener los dias feriados entre la fecha de hoy y la asignacion
								$feriados = Feriado::buscarDiasFeriados($fecha_asignacion_formateada,$fecha_cierre_formateada)->get();
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

								$sla_gestion -= $cantidad_dias;

								if($sla_gestion == 0)
									$sla_gestion = 1;

							}else
							{
								$sla_gestion = 'NO CALCULABLE';
							}

							$sla_gestion_total_ticket = 0;
							//SLA GESTION (DESDE FECHA SOLICITUD HASTA LA FECHA DE CIERRE)
							if($solicitudes[$i]->idestado_solicitud == 1 || $solicitudes[$i]->idestado_solicitud == 2 || $solicitudes[$i]->idestado_solicitud == 5 || $solicitudes[$i]->idestado_solicitud == 6)
							{
								
								$sla_gestion_total_ticket = $fecha_solicitud_formateada->diffInWeekdays($fecha_cierre_formateada);

								//Obtener los dias feriados entre la fecha de hoy y la asignacion
								$feriados = Feriado::buscarDiasFeriados($fecha_solicitud_formateada,$fecha_cierre_formateada)->get();
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

								$sla_gestion_total_ticket -= $cantidad_dias;

								if($sla_gestion_total_ticket == 0)
									$sla_gestion_total_ticket = 1;

							}else
							{
								$sla_gestion_total_ticket = 'NO CALCULABLE';
							}

							$sheet->row($i+2, array(
							     $i+1,$codigo_solicitud,$fecha_solicitud,$fecha_asignacion,$numero_corte,$fecha_inicio_procesando,$fecha_cierre,$nombre_tipo_solicitud,$nombre_estado_solicitud,$nombre_herramienta,$nombre_denominacion,$nombre_entidad,$nombre_canal,$nombre_responsable,$nombre_sector,$nombre_usuario_asignado,$asunto,$sla_pendiente,$sla_procesando,$sla_real_asignacion,$sla_real_respuesta_gestor,$sla_procesamiento,$sla_gestion,$sla_gestion_total_ticket
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

	public function generar_reporte_requerimientos()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1){

				$data["fecha_desde_requerimiento"] = Input::get('fecha_desde_requerimiento');
				$data["fecha_hasta_requerimiento"] = Input::get('fecha_hasta_requerimiento');

				$data["fecha_desde_solicitud"] = null;
				$data["fecha_hasta_solicitud"] = null;
				
				if($data["fecha_desde_requerimiento"] == null || $data["fecha_hasta_requerimiento"] == null)
				{
					Session::flash('error','Para generar la base de solicitudes se deben ingresar las fechas de corte.');
					return View::make('Reporteria/reporteria',$data);
				}

				$fecha_desde_requerimiento = $data["fecha_desde_requerimiento"];
				$fecha_hasta_requerimiento = $data["fecha_hasta_requerimiento"];

				$fecha_reporte = date('Y-m-d H:i:s');

				$transacciones = Transaccion::buscarTransaccionesPorFechas($fecha_desde_requerimiento,$fecha_hasta_requerimiento)->get();

				if($transacciones == null || $transacciones->isEmpty() || count($transacciones) == 0)
				{
					return Redirect::to('/reportes')->with('error','No hay transacciones registradas dentro de las fechas seleccionadas');
				}

				Excel::create('REPORTE REQUERIMIENTOS '.$fecha_reporte, function($excel) use ($transacciones){
					
					$excel->sheet('BASE', function($sheet) use ($transacciones) {						

						$sheet->row(1, array(
						     'N°','CODIGO_SOLICITUD','CODIGO_REQUERIMIENTO','ID_TRANSACCION','FECHA_REGISTRO_TRANSACCION','FECHA_INICIO_PROCESANDO_TRANSACCION','FECHA_CIERRE_TRANSACCION','ACCION','APLICATIVO','APLICATIVO_AGRUPADO','TIPO_GESTION','USUARIO_ASIGNADO','CANAL','RESPONSABLE_CANAL','ENTIDAD','PUNTO_VENTA','CARGO_CANAL','DNI_USUARIO','USUARIO_BLOQUEADO','ESTADO_TRANSACCION','SLA_RESPUESTA_GESTOR','SLA_PROCESAMIENTO','SLA_TOTAL_TRANSACCION'
						));

						

						$cantidad_transacciones = count($transacciones);

						for($i = 0; $i<$cantidad_transacciones; $i++)
						{
							
							$codigo_solicitud = $transacciones[$i]->codigo_solicitud;
							$codigo_requerimiento = $transacciones[$i]->codigo_requerimiento;
							$id_transaccion = null;							
							$fecha_registro_transaccion = null;
							if($transacciones[$i]->fecha_registro != null)
								$fecha_registro_transaccion = date('Y-m-d H:i:s',strtotime($transacciones[$i]->fecha_registro));

							$fecha_inicio_procesando_transaccion = null;
							if($transacciones[$i]->fecha_inicio_procesando_transaccion != null)
								$fecha_inicio_procesando_transaccion = date('Y-m-d H:i:s',strtotime($transacciones[$i]->fecha_inicio_procesando_transaccion));

							$fecha_cierre_transaccion = null;
							if($transacciones[$i]->fecha_cierre != null)
								$fecha_cierre_transaccion = date('Y-m-d H:i:s',strtotime($transacciones[$i]->fecha_cierre));

							$accion = $transacciones[$i]->accion_requerimiento;
							$aplicativo = $transacciones[$i]->nombre_herramienta;
							$aplicativo_agrupado = $transacciones[$i]->nombre_denominacion;
							$tipo_gestion = $transacciones[$i]->nombre_tipo_requerimiento;

							$nombre_usuario_asignado = null;
							$nombre_responsable = 'SIN RESPONSABLE';
							$fecha_asignacion = null;
							$solicitud = Solicitud::buscarSolicitudPorIdSolicitud($transacciones[$i]->idsolicitud)->get()[0];

							if($transacciones[$i]->idestado_solicitud != 5)
							{
								//fecha_asignacion
								//usuario_asignado
								$asignacion = Asignacion::buscarPorIdSolicitud($solicitud->idsolicitud)->get();
								if($asignacion != null && !$asignacion->isEmpty()){
									$fecha_asignacion = date('Y-m-d H:i:s',strtotime($asignacion[0]->fecha_asignacion));
									$usuario_asignado = UsuariosXAsignacion::buscarUsuarioActual($asignacion[0]->idasignacion)->get();
									if($usuario_asignado != null && !$usuario_asignado->isEmpty()){
										$usuario = User::withTrashed()->find($usuario_asignado[0]->idusuario_asignado);
										$nombre_usuario_asignado = $usuario->nombre.' '.$usuario->apellido_paterno.' '.$usuario->apellido_materno;
									}
								}						

							}



							$canal = $transacciones[$i]->nombre_canal;
							$entidad = $transacciones[$i]->nombre_entidad;
							$punto_venta = $transacciones[$i]->nombre_punto_venta;
							$cargo_canal = $transacciones[$i]->cargo_canal;
							$id_transaccion = $transacciones[$i]->idtransaccion;



							$idpunto_venta = $transacciones[$i]->idpunto_venta;
							$punto_venta_o = PuntoVenta::find($idpunto_venta);
							$entidad_o = Entidad::find($punto_venta_o->identidad);
							$canal_o = Canal::find($entidad_o->idcanal);

							if($canal_o->idusuario_responsable != null){
								$usuario_responsable = User::find($canal_o->idusuario_responsable);
								$nombre_responsable = $usuario_responsable->nombre.' '.$usuario_responsable->apellido_paterno.' '.$usuario_responsable->apellido_materno;	
							}
							

							$usuario_bloqueado = null;
							
							$dni_usuario = $transacciones[$i]->numero_documento;
							$estado_transaccion = $transacciones[$i]->nombre_estado_transaccion;

							$flag_dependencia = null;

							if($transacciones[$i]->usuario_bloqueado == 1)
								$usuario_bloqueado = 'SI';
							else
								$usuario_bloqueado = 'NO';

							$fecha_registro_formateada = null;
							$fecha_inicio_procesando_formateada = null;
							$fecha_cierre_formateada = null;

							if($transacciones[$i]->fecha_registro != null)
							{
								$fecha_registro_f=Carbon\Carbon::parse($transacciones[$i]->fecha_registro);				
								$fecha_registro_formateada = Carbon\Carbon::parse(date_format($fecha_registro_f,'Y-m-d'));
							}

							if($transacciones[$i]->fecha_inicio_procesando != null)
							{
								$fecha_inicio_procesando_f=Carbon\Carbon::parse($transacciones[$i]->fecha_inicio_procesando);				
								$fecha_inicio_procesando_formateada = Carbon\Carbon::parse(date_format($fecha_inicio_procesando_f,'Y-m-d'));
							}

							if($transacciones[$i]->fecha_cierre != null)
							{
								$fecha_cierre_f=Carbon\Carbon::parse($transacciones[$i]->fecha_cierre);				
								$fecha_cierre_formateada = Carbon\Carbon::parse(date_format($fecha_cierre_f,'Y-m-d'));
							}

							//1. SLA_RESPUESTA_GESTOR
							$sla_real_respuesta_gestor = 0;

							if($transacciones[$i]->idestado_transaccion == 1 || $transacciones[$i]->idestado_transaccion == 2 || $transacciones[$i]->idestado_transaccion == 4)
							{
								
								$sla_real_respuesta_gestor = $fecha_registro_formateada->diffInWeekdays($fecha_inicio_procesando_formateada);

								//Obtener los dias feriados entre la fecha de hoy y la asignacion
								$feriados = Feriado::buscarDiasFeriados($fecha_registro_formateada,$fecha_inicio_procesando_formateada)->get();
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

								$sla_real_respuesta_gestor -= $cantidad_dias;
								if($sla_real_respuesta_gestor == 0)
									$sla_real_respuesta_gestor = 1;

							}else
							{
								$sla_real_respuesta_gestor = 'TRANSACCION PENDIENTE';
							}

							//2. SLA_PROCESAMIENTO
							$sla_procesamiento = 0;

							if($transacciones[$i]->idestado_transaccion == 1){

								$sla_procesamiento = $fecha_inicio_procesando_formateada->diffInWeekdays($fecha_cierre_formateada);

								//Obtener los dias feriados entre la fecha de hoy y la asignacion
								$feriados = Feriado::buscarDiasFeriados($fecha_inicio_procesando_formateada,$fecha_cierre_formateada)->get();
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

								$sla_procesamiento -= $cantidad_dias;
								if($sla_procesamiento == 0)
									$sla_procesamiento = 1;
								

							}else if($transacciones[$i]->idestado_solicitud == 2){
								$sla_procesamiento = 'TRANSACCION NO ATENDIDA';
							}
							else{
								$sla_procesamiento = 'TRANSACCION NO FINALIZADA';
							}

							$sla_gestion_total_transaccion = 0;
							//SLA GESTION (DESDE FECHA SOLICITUD HASTA LA FECHA DE CIERRE)
							if($transacciones[$i]->idestado_transaccion == 1 || $transacciones[$i]->idestado_transaccion == 2)
							{
								
								$sla_gestion_total_transaccion = $fecha_registro_formateada->diffInWeekdays($fecha_cierre_formateada);

								//Obtener los dias feriados entre la fecha de hoy y la asignacion
								$feriados = Feriado::buscarDiasFeriados($fecha_registro_formateada,$fecha_cierre_formateada)->get();
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

								$sla_gestion_total_transaccion -= $cantidad_dias;

								if($sla_gestion_total_transaccion == 0)
									$sla_gestion_total_transaccion = 1;

							}else
							{
								$sla_gestion_total_transaccion = 'NO CALCULABLE';
							}

							$sheet->row($i+2, array(
						    	$i+1,$codigo_solicitud,$codigo_requerimiento,$id_transaccion,$fecha_registro_transaccion,$fecha_inicio_procesando_transaccion,$fecha_cierre_transaccion,$accion,$aplicativo,$aplicativo_agrupado,$tipo_gestion,$nombre_usuario_asignado,$canal,$nombre_responsable,$entidad,$punto_venta,$cargo_canal,$dni_usuario,$usuario_bloqueado,$estado_transaccion,$sla_real_respuesta_gestor,$sla_procesamiento,$sla_gestion_total_transaccion));
							
						}
					});
				})->download('xlsx');
				
							
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}



}
