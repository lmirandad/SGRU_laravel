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
				$nombres_herramientas = Input::get('nombres_herramienta');
				$codigos_herramientas = Input::get('codigos_herramientas');

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

					$nombre_herramienta = $nombres_herramientas[$i];
					$solicitud->nombre_herramienta = $nombre_herramienta;
					
					$codigos_herramientas_parseados = explode('|',$codigos_herramientas[$i]);
					
					

					if(strcmp($nombre_herramienta, "VARIOS") == 0){
						//POR CONFIRMAR (ASUMIR LA PRIMERA HERRAMIENTA DEL SISTEMA)
						$idherramienta = (int)$codigos_herramientas_parseados[0];
					}else{
						$herramienta = Herramienta::buscarPorCodigo($nombre_herramienta)->get();
						$idherramienta = $herramienta[0]->idherramienta;
					}

					//b. Sector
					$entidad = Entidad::find($ids_entidad[$i]);
					$canal = Canal::find($entidad->idcanal);
					$sector = Sector::find($canal->idsector);
					$idsector = $sector->idsector;

					//c. Accion
					$idaccion = $idstipo_solicitud[$i];

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
					if(strcmp($nombre_herramienta, "VARIOS") == 0){
						$usuarios = User::buscarUsuariosDisponiblesPorSector($sector->idsector,$idherramienta,$idaccion)->get();
						
						
						if($usuarios ==null || $usuarios->isEmpty())
						{
							//como no hay usuarios que tengan solicitudes entonces se utiliza cualquiera
							$usuarios = User::buscarUsuariosPorSector($sector->idsector,$idherramienta,$idaccion)->get(); //¿Se puede usar RANDOM?
							if(!$usuarios ==null && !$usuarios->isEmpty())
								$usuario_apto = $usuarios[0];
						}else
						{
							$usuarios_libres = User::buscarUsuariosLibresPorSector($sector->idsector,$idherramienta,$idaccion)->get();
							if($usuarios_libres == null || $usuarios_libres->isEmpty()){	

								$usuario_apto = $usuarios[0];
							}
							else
							{
								$usuario_apto = $usuarios_libres[0];
							}
						}	
						
					}else{
						//como solo tiene una sola herramienta, buscamos a los usuarios especializados y que tengan menos solicitudes pendientes y en proceso
						$usuarios = User::buscarUsuariosDisponiblesPorHerramienta($idherramienta,$idaccion)->get();	

						if($usuarios ==null || $usuarios->isEmpty())
						{
							//como no hay usuarios que tengan solicitudes entonces se utiliza cualquiera
							$usuarios = User::buscarUsuariosPorHerramienta($idherramienta,$idaccion)->get(); //¿Se puede usar RANDOM?		
							if(!$usuarios ==null && !$usuarios->isEmpty())
								$usuario_apto = $usuarios[0];
						}else{
							$usuarios_libres = User::buscarUsuariosLibresPorHerramienta($idherramienta,$idaccion)->get();
							if($usuarios_libres == null || $usuarios_libres->isEmpty()){
								

								$usuario_apto = $usuarios[0];
							}
							else
							{
								$usuario_apto = $usuarios_libres[0];
							}
						}	
						
					}

					if($usuario_apto == null){
						array_push($array_codigos_no_procesados,$solicitud->codigo_solicitud);
						continue;
					}

					$solicitud->save();

					// 3. Crear SolicitudXHerramienta

					$cantidad_herramientas = count($codigos_herramientas_parseados);
					for($w=0;$w<$cantidad_herramientas;$w++)
					{
						$solicitudxherramienta = new SolicitudXHerramienta;
						$solicitudxherramienta->idsolicitud = $solicitud->idsolicitud;
						$solicitudxherramienta->idherramienta = (int)$codigos_herramientas_parseados[$w];
						$solicitudxherramienta->save();
					}

					// 4. Realizar las asignaciones
					$asignacion = new Asignacion;
					$asignacion->fecha_asignacion = date('Y-m-d H:i:s');
					$asignacion->idestado_asignacion = 2;//Realizado
					$asignacion->iduser_asignado = $usuario_apto->iduser;
					$asignacion->iduser_created_by = $data["user"]->id;
					$asignacion->idsolicitud = $solicitud->idsolicitud;
					$asignacion->save();

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