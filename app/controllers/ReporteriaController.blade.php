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
				$data["fecha_desde"] = null;
				$data["fecha_hasta"] = null;
				
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
			if($data["user"]->idrol == 1 || $data["user"]->idrol == 2){

				$data["fecha_desde"] = Input::get('fecha_desde');
				$data["fecha_hasta"] = Input::get('fecha_hasta');
				
				if($data["fecha_desde"] == null || $data["fecha_hasta"] == null)
				{
					Session::flash('error','Para generar la base de solicitudes se deben ingresar las fechas de corte.');
					return View::make('Reporteria/reporteria',$data);
				}

				$fecha_desde = $data["fecha_desde"];
				$fecha_hasta = $data["fecha_hasta"];

				

				$value = Excel::create('Reporte Logs ', function($excel) use  ($fecha_desde,$fecha_hasta){
					$excel->sheet('Reporte', function($sheet) use ($fecha_desde,$fecha_hasta)  {
						$sheet->row(1, array(
							     'N°','CODIGO_SOLICITUD','FECHA_SOLICITUD','FECHA_ASIGNACION','FECHA_INICIO_PROCESANDO','FECHA_CIERRE','TIPO_SOLICITUD','ESTADO_SOLICITUD','HERRAMIENTA_SOLICITADA','APLICATIVO_AGRUPADO','ENTIDAD','CANAL','SECTOR','USUARIO_ASIGNADO','ASUNTO','SLA_PENDIENTE','SLA_PROCESANDO'
							));
						$solicitudes = Solicitud::buscarSolicitudesPorFechas($fecha_desde,$fecha_hasta)->get();

						$cantidad_solicitudes = count($solicitudes);

						for($i = 0; $i<$cantidad_solicitudes; $i++)
						{
							$codigo_solicitud = $solicitudes[$i]->codigo_solicitud;
							$fecha_solicitud = date('Y-m-d',strtotime($solicitudes[$i]->fecha_solicitud));
							$fecha_asignacion = null;
							$fecha_inicio_procesando = null;
							if($fecha_inicio_procesando != null)
								$fecha_inicio_procesando = date('Y-m-d',strtotime($solicitudes[$i]->fecha_inicio_procesando));
							
							$fecha_cierre = null;
							if($fecha_cierre != null)
								$fecha_cierre = date('Y-m-d',strtotime($solicitudes[$i]->fecha_cierre));

							$nombre_tipo_solicitud = $solicitudes[$i]->nombre_tipo_solicitud;
							$nombre_estado_solicitud = $solicitudes[$i]->nombre_estado_solicitud;

							$nombre_usuario_asignado = null;
							$nombre_herramienta = null;
							$nombre_denominacion = null;
							$nombre_entidad = null;
							$nombre_canal = null;
							$nombre_sector = null;
							
							$asunto = $solicitudes[$i]->asunto;
							$sla_pendiente = null;
							$sla_procesando = null;
							
							if($solicitudes[$i]->idestado_solicitud != 5)
							{
								//fecha_asignacion
								//usuario_asignado
								$asignacion = Asignacion::buscarPorIdSolicitud($solicitudes[$i]->idsolicitud)->get();
								if($asignacion != null && !$asignacion->isEmpty()){
									$fecha_asignacion = date('Y-m-d',strtotime($asignacion[0]->fecha_asignacion));
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
							
							$sheet->row($i+2, array(
							     $i+1,$codigo_solicitud,$fecha_solicitud,$fecha_asignacion,$fecha_inicio_procesando,$fecha_cierre,$nombre_tipo_solicitud,$nombre_estado_solicitud,$nombre_herramienta,$nombre_denominacion,$nombre_entidad,$nombre_canal,$nombre_sector,$nombre_usuario_asignado,$asunto,$sla_pendiente,$sla_procesando
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



}
