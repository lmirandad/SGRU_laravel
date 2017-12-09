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
				$nombres_herramientas = Input::get('nombres_herramientas');

				$cantidad_registros = count($codigos_solicitud);

				// 1. Registrar la carga del archivo
				$carga_archivo = new CargaArchivo;
				$carga_archivo->fecha_carga_archivo = date('Y-m-d H:i:s');
				$carga_archivo->iduser_registrador = $data["user"]->id;
				$carga_archivo->iduser_created_by = $data["user"]->id;
				$carga_archivo->idestado_carga_archivo = 1;
				$carga_archivo->idtipo_carga_archivo = 1;

				//$carga_archivo->save();

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
					$solicitud->fecha_solicitud = date('Y-m-d H:i:s',strtotime($fechas_solicitud[$i]));
					$solicitud->idstipo_solicitud_general = $idstipo_solicitud_general[$i];

					//Determinar SLA
					
					//a. Herramienta

					$nombre_herramienta = $nombres_herramientas[$i];
					$idherramienta = 1;
					if(strcmp($nombre_herramienta, "VARIOS") == 0){
						//POR CONFIRMAR (ASUMIR LA PRIMERA HERRAMIENTA DEL SISTEMA)
					}else{
						$herramienta->Herramienta::buscarPorCodigo($nombre_herramienta)->get();
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

					$solicitud->idsla = $sla[0]->idsla;

					//Determinar Asignacion

					//En caso solo se tenga varias herramientas, se debe buscar a los usuarios del sector, que tengan menos solicitudes pendientes y en proceso.
					if(strcmp($nombre_herramienta, "VARIOS") == 0){
						$usuarios = User::buscarUsuariosDisponiblesPorSector($sector->idsector)->get();
						$usuario_apto = $usuarios[0];
					}else{
						//como solo tiene una sola herramienta, buscamos a los usuarios especializados y que tengan menos solicitudes pendientes y en proceso
						$usuarios = User::buscarUsuariosDisponiblesPorHerramienta($herramienta->idherramienta)->get();
						$usuario_apto = $usuarios[0];
					}

					// 3. Realizar las asignaciones
					$asignacion = new Asignacion;
					$asignacion->fecha_asignacion = date('Y-m-d H:i:s');
					$asignacion->idestado_asignacion = 2;//Realizado
					$asignacion->iduser_asignado = $usuario_apto->id;
					$asignacion->iduser_created_by = $data["user"]->id;
					$asignacion->idsolicitud = $solicitud->idsolicitud;

					//$asignacion->save();

					$solicitud->idasignacion = $asignacion->idasignacion;
					//$solicitud->save();

				}

				Session::flash('message', 'Se realizaron las asignaciones con éxito.');
				
				return Redirect::to('sectores/crear_sector');
				
			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}
	
}