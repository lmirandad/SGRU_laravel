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

				

				$value = Excel::create('Reporte Logs ', function($excel) use  ($fecha_desde_solicitud,$fecha_hasta_solicitud){
					$excel->sheet('Reporte', function($sheet) use ($fecha_desde_solicitud,$fecha_hasta_solicitud)  {
						
						$sheet->row(1, array(
							     'N°','CODIGO_SOLICITUD','FECHA_SOLICITUD','FECHA_ASIGNACION','FECHA_INICIO_PROCESANDO','FECHA_CIERRE','TIPO_SOLICITUD','ESTADO_SOLICITUD','HERRAMIENTA_SOLICITADA','APLICATIVO_AGRUPADO','ENTIDAD','CANAL','SECTOR','USUARIO_ASIGNADO','ASUNTO','SLA_PENDIENTE','SLA_PROCESANDO'
							));
						$solicitudes = Solicitud::buscarSolicitudesPorFechas($fecha_desde_solicitud,$fecha_hasta_solicitud)->get();

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

				$transacciones = Transaccion::buscarTransaccionPorIdRequerimiento(5)->get();

				
				Excel::create('prueba', function($excel) use ($fecha_desde_requerimiento,$fecha_hasta_requerimiento){
					
					$excel->sheet('sheetname', function($sheet) use ($fecha_desde_requerimiento,$fecha_hasta_requerimiento) {						

						$sheet->row(1, array(
						     'N°','CODIGO_SOLICITUD','CODIGO_REQUERIMIENTO','ID_TRANSACCION','FECHA_REGISTRO_REQUERIMIENTO','FECHA_CIERRE_REQUERIMIENTO','FECHA_REGISTRO_TRANSACCION','FECHA_CIERRE_TRANSACCION','ACCION','APLICATIVO','APLICATIVO_AGRUPADO','TIPO_GESTION','CANAL','ENTIDAD','PUNTO_VENTA','CARGO_CANAL','PERFIL_APLICATIVO','DNI_USUARIO','USUARIO_BLOQUEADO','ESTADO_TRANSACCION','ESTADO_REQUERIMIENTO','FLAG_DEPENDENCIA'
						));

						$requerimientos = Requerimiento::buscarRequerimientosPorFechas($fecha_desde_requerimiento,$fecha_hasta_requerimiento)->get();
						$contador_filas = 1;
						$cantidad_requerimientos = count($requerimientos);

						for($i = 0; $i<$cantidad_requerimientos; $i++)
						{
							
							$transacciones = Transaccion::buscarTransaccionPorIdRequerimiento($requerimientos[$i]->idrequerimiento)->get();
							$codigo_solicitud = $requerimientos[$i]->codigo_solicitud;
							$codigo_requerimiento = $requerimientos[$i]->codigo_requerimiento;
							$id_transaccion = null;							
							$fecha_registro_requerimiento = null;
							if($requerimientos[$i]->fecha_registro != null)
								$fecha_registro_requerimiento = date('Y-m-d',strtotime($requerimientos[$i]->fecha_registro));

							$fecha_cierre_requerimiento = null;
							if($requerimientos[$i]->fecha_cierre != null)
								$fecha_cierre_requerimiento = date('Y-m-d',strtotime($requerimientos[$i]->fecha_cierre));

							$fecha_registro_transaccion = null;
							$fecha_cierre_transaccion = null;
							$accion = $requerimientos[$i]->accion_requerimiento;
							$aplicativo = $requerimientos[$i]->nombre_herramienta;
							$aplicativo_agrupado = $requerimientos[$i]->nombre_denominacion;
							$tipo_gestion = $requerimientos[$i]->nombre_tipo_requerimiento;
							$canal = $requerimientos[$i]->nombre_canal;
							$entidad = $requerimientos[$i]->nombre_entidad;
							$punto_venta = $requerimientos[$i]->nombre_punto_venta;
							$cargo_canal = null;
							$id_transaccion = null;
							$usuario_bloqueado = null;
							$perfil_aplicativo = null;
							$dni_usuario = null;
							$estado_requerimiento = null;

							if($requerimientos[$i]->idestado_requerimiento == 1)
								$estado_requerimiento = 'ATENDIDO';
							elseif($requerimientos[$i]->idestado_requerimiento == 2)
								$estado_requerimiento = 'RECHAZADO';
							else
								$estado_requerimiento = 'PENDIENTE';

							$estado_transaccion = null;
							$flag_dependencia = null;


							if($transacciones == null || $transacciones->isEmpty())
							{
								
								$sheet->row($contador_filas+1, array(
							    	$contador_filas,$codigo_solicitud,$codigo_requerimiento,'',$fecha_registro_requerimiento,$fecha_cierre_requerimiento,$accion,'','',$aplicativo,$aplicativo_agrupado,'',$canal,$entidad,$punto_venta,'','','','','',$estado_requerimiento,1
								));
								$contador_filas++;

							}else
							{
								
								$cantidad_transacciones = count($transacciones);
								for($j=0;$j<$cantidad_transacciones;$j++)
								{
									$id_transaccion = $transacciones[$j]->idtransaccion;

									if($transacciones[$j]->fecha_registro != null)
										$fecha_registro_transaccion = date('Y-m-d',strtotime($transacciones[$j]->fecha_registro));

									$fecha_cierre_requerimiento = null;
									if($transacciones[$j]->fecha_cierre != null)
										$fecha_cierre_transaccion = date('Y-m-d',strtotime($transacciones[$j]->fecha_cierre));

									$cargo_canal = $transacciones[$j]->cargo_canal;
									$perfil_aplicativo = $transacciones[$j]->perfil_aplicativo;
									$dni_usuario = $transacciones[$j]->numero_documento;
									if($transacciones[$j]->usuario_bloqueado == 1)
										$usuario_bloqueado = 'SI';
									else
										$usuario_bloqueado = 'NO';

									if($transacciones[$j]->idestado_transaccion == 1)
										$estado_transaccion = 'ATENDIDO';
									elseif ($transacciones[$j]->idestado_transaccion == 2)
										$estado_transaccion = 'RECHAZADO';
									else
										$estado_transaccion = 'PENDIENTE';

									if($j==0)
										$flag_dependencia = 1;
									else
										$flag_dependencia = 0;

									$sheet->row($contador_filas+1, array(
								    	$contador_filas,$codigo_solicitud,$codigo_requerimiento,$id_transaccion,$fecha_registro_requerimiento,$fecha_cierre_requerimiento,$fecha_registro_transaccion,$fecha_cierre_requerimiento,$accion,$aplicativo,$aplicativo_agrupado,$tipo_gestion,$canal,$entidad,$punto_venta,$cargo_canal,$perfil_aplicativo,$dni_usuario,$usuario_bloqueado,$estado_transaccion,$estado_requerimiento,$flag_dependencia
										));
									$contador_filas++;
								}	

							}

							

						}
					});
				})->download('xls');

				
							
			}else{
				return View::make('error/error',$data);
			}
		}else{
			return View::make('error/error',$data);
		}
	}



}
