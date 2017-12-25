<?php

class AsignacionController extends BaseController {

	public function submit_asignacion()
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un Webmaster
			if($data["user"]->idrol == 1){
				
				// 0. Recepcionar información:

				$codigos_solicitud = Input::get('codigos_solicitud');
				$ids_entidad = Input::get('ids_entidad');
				$idstipo_solicitud_general = Input::get('idstipo_solicitud_general');
				$idstipo_solicitud = Input::get('idstipo_solicitud');
				$idsestado_solicitud = Input::get('idsestado_solicitud');
				$fechas_solicitud = Input::get('fechas_solicitud');
				$asuntos = Input::get('asuntos');
				$idherramientas = Input::get('idherramientas');
				
				$cantidad_registros = count($codigos_solicitud);

				// 1. Registrar la carga del archivo
				$carga_archivo = new CargaArchivo;
				$carga_archivo->fecha_carga_archivo = date('Y-m-d H:i:s');
				$carga_archivo->iduser_registrador = $data["user"]->id;
				$carga_archivo->iduser_created_by = $data["user"]->id;
				$carga_archivo->idestado_carga_archivo = 1;
				$carga_archivo->idtipo_carga_archivo = 1;

				$carga_archivo->save();

				$array_codigos_no_procesados = array();
				$array_codigos_procesados = array();

				// Por cada solicitud realizar los pasos 2 y 3

				for($i=0; $i<$cantidad_registros; $i++)
				{
					// 2. Registrar nueva solicitud
					$solicitud = new Solicitud;
					$solicitud->asunto = $asuntos[$i];
					$solicitud->codigo_solicitud = $codigos_solicitud[$i];
					$solicitud->identidad = $ids_entidad[$i];
					$solicitud->idtipo_solicitud = $idstipo_solicitud[$i];
					$solicitud->idestado_solicitud = $idsestado_solicitud[$i];
					$solicitud->iduser_created_by = $data["user"]->id;
					$solicitud->idcarga_archivo = $carga_archivo->idcarga_archivo;
					$partes = explode('-',$fechas_solicitud[$i]);
					$solicitud->fecha_solicitud = date('Y-m-d H:i:s',strtotime($partes[2]."-".$partes[1]."-".$partes[0]));
					$solicitud->idtipo_solicitud_general = $idstipo_solicitud_general[$i];


					//Determinar SLA
					
					//a. Herramienta

					$idherramienta = $idherramientas[$i];

					//PRIMER CAMBIO
					if($idherramienta == 0)
						$solicitud->idherramienta = null;
					else
						$solicitud->idherramienta = $idherramienta;

					//b. Sector
					$entidad = Entidad::find($ids_entidad[$i]);
					$canal = Canal::find($entidad->idcanal);
					$sector = Sector::find($canal->idsector);
					$idsector = $sector->idsector;

					//c. Accion
					$idaccion = $idstipo_solicitud[$i];

					//En caso que no se tiene un SLA para una herramienta no detectada se asumará el SLA como si existieran varios aplicativos
					if($idherramienta == 0)
						$sla = TipoSolicitudXSla::buscarSlaPorSectorHerramientaAccion($idsector,39,$idaccion)->get();
					else
						$sla = TipoSolicitudXSla::buscarSlaPorSectorHerramientaAccion($idsector,$idherramienta,$idaccion)->get();

					if($sla==null || $sla->isEmpty())
					{
						array_push($array_codigos_no_procesados, $solicitud->codigo_solicitud);
						continue;
					}

					$solicitud->idsla = $sla[0]->idsla;

					//Determinar Asignacion
					$usuarios = null;

					//En caso solo se tenga varias herramientas, se debe buscar a los usuarios del sector, que tengan menos solicitudes pendientes y en proceso.
					$usuario_apto = null;
					if($idherramienta == 39 || $idherramienta == 0){
						//herramienta representada para "VARIOS" o "NO DETECTADO"
						
						$usuarios = User::buscarUsuariosAsignacionPorSector($sector->idsector);	
				
						if(is_array($usuarios) == true) //hay usuarios
						{
							$usuario_apto = User::find($usuarios[0]->id_usuario);
						}else
						{
							$usuario_apto = null;
						}
						
					}else{

						//como solo tiene una sola herramienta, buscamos a los usuarios especializados y que tengan menos solicitudes pendientes y en proceso.

						$usuarios = User::buscarUsuariosAsignacionPorHerramienta($idherramienta,$idaccion);	

						if(is_array($usuarios) == true) //hay usuarios
						{
							$usuario_apto = User::find($usuarios[0]->id_usuario);
						}else
						{
							$usuario_apto = null;
						}	
						
					}

					
					if($usuario_apto == null){
						array_push($array_codigos_no_procesados,$solicitud->codigo_solicitud);
						continue;
					}

					$solicitud->save();

					// 4. Realizar las asignaciones
					$asignacion = new Asignacion;
					$asignacion->fecha_asignacion = date('Y-m-d H:i:s');
					$asignacion->idestado_asignacion = 2;//Realizado
					$asignacion->iduser_created_by = $data["user"]->id;
					$asignacion->idsolicitud = $solicitud->idsolicitud;
					$asignacion->save();

					
					$usuariosxasignacion = new UsuariosXAsignacion;
					$usuariosxasignacion->idusuario_asignado = $usuario_apto->id;
					$usuariosxasignacion->idasignacion = $asignacion->idasignacion;
					$usuariosxasignacion->motivo_asignacion = "Primera asignación";
					$usuariosxasignacion->estado_usuario_asignado = 1; //1: activo 0: inactivado (se hace reasignacion)
					$usuariosxasignacion->iduser_created_by = $data["user"]->id;
					$usuariosxasignacion->save();

					array_push($array_codigos_procesados,$solicitud->codigo_solicitud);
				}


				if(count($array_codigos_no_procesados) > 0){
					$texto = '';
					for($i=0;$i<count($array_codigos_no_procesados);$i++){
						$texto=$texto.$array_codigos_no_procesados[$i].'<br>';
					}
					Session::flash('error','No se realizaron las asignaciones para los siguientes códigos:'.'<br>'.$texto
						.'<br>'.'Posibles Motivos:'.'<br>'.'1. El aplicativo de la solicitud no cuenta con un SLA definido.<br>'.
						'2. No existen usuarios aptos o disponibles para la asignación a la solicitud.');
				}

				if(count($array_codigos_procesados) > 0){
					$texto = '';
					for($i=0;$i<count($array_codigos_procesados);$i++){
						$texto=$texto.$array_codigos_procesados[$i].'<br>';
					}
					Session::flash('message','Se realizaron las asignaciones para los siguientes códigos:'.'<br>'.$texto);
				}
								
				return Redirect::to('solicitudes/listar_solicitudes');
				
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}
	
}