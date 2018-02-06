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
				$fechas_estado = Input::get('fechas_estado');
				
				$cantidad_registros = count($codigos_solicitud);

				/*if($cantidad_registros == 0)
				{
					Session::flash('error','No hay solicitudes por asignar.');
					return Redirect::to('solicitudes/cargar_solicitudes');
				}*/

				// 1. Registrar la carga del archivo
				$carga_archivo = new CargaArchivo;
				$carga_archivo->fecha_carga_archivo = date('Y-m-d H:i:s');
				$carga_archivo->iduser_registrador = $data["user"]->id;
				$carga_archivo->iduser_created_by = $data["user"]->id;
				$carga_archivo->idestado_carga_archivo = 1;
				$carga_archivo->idtipo_carga_archivo = 1;

				$cargas = CargaArchivo::buscarUltimoCorte(date('Y-m-d'))->get();

				if($cargas == null || $cargas->isEmpty())
				{
					//No hay cortes, este es el primero
					$carga_archivo->numero_corte = 1;

					
				}else
				{
					$numero_corte = $cargas[0]->numero_corte;
					$carga_archivo->numero_corte = $numero_corte + 1;
					
				}

				$carga_archivo->save();

				$array_codigos_no_procesados = array();
				$array_codigos_procesados = array();

				// Por cada solicitud realizar los pasos 2 y 3
				$herramienta_varios = Herramienta::buscarPorNombre('VARIOS')->get();
				
				
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
					if($fechas_estado[$i] != null && strcmp($fechas_estado[$i],'')!=0)
					{
						$partes = explode('-',$fechas_estado[$i]);
						$solicitud->fecha_estado_portal = date('Y-m-d H:i:s',strtotime($partes[2]."-".$partes[1]."-".$partes[0]));	
					}else
					{
						$solicitud->fecha_estado_portal = null;
					}
					
					$solicitud->idtipo_solicitud_general = $idstipo_solicitud_general[$i];
					$solicitud->fur_cargado = 0;


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

					$herramienta_varios = Herramienta::buscarPorNombre('VARIOS')->get();

					//En caso que no se tiene un SLA para una herramienta no detectada se asumará el SLA como si existieran varios aplicativos
					if($idherramienta == 0)
						$sla = TipoSolicitudXSla::buscarSlaPorSectorHerramientaAccion($idsector,$herramienta_varios[0]->idherramienta,$idaccion)->get();
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

					if($idherramienta == $herramienta_varios[0]->idherramienta || $idherramienta == 0){
						//herramienta representada para "VARIOS" o "NO DETECTADO"

						$usuario_apto = AsignacionController::buscarUsuarioAptoPorSector($sector->idsector);
						
					}else{

						//como solo tiene una sola herramienta, buscamos a los usuarios especializados y que tengan menos solicitudes pendientes y en proceso.

						$usuario_apto = AsignacionController::buscarUsuarioAptoPorHerramientaV2($idherramienta,$idaccion,$sector->idsector);

						if($usuario_apto == null)
						{
							$usuario_apto = AsignacionController::buscarUsuarioAptoPorSector($sector->idsector);
							echo '<pre>';
							var_dump($usuario_apto);
							echo '</pre>';
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

				//Para los rechazados
				$codigos_rechazo = Input::get('codigos_solicitud_rechazo');
				$ids_entidad_rechazo = Input::get('ids_entidad_rechazo');
				$idstipo_solicitud_general_rechazo = Input::get('idstipo_solicitud_general_rechazo');
				$fechas_solicitud_rechazo = Input::get('fechas_solicitud_rechazo');
				$fechas_estado_rechazo = Input::get('fechas_estado_rechazo');
				$ids_herramienta = Input::get('ids_herramienta');
				$cantidad_registros_rechazados = count($codigos_rechazo);

				for($i = 0; $i< $cantidad_registros_rechazados; $i++)
				{
					$solicitud = new Solicitud;
					$solicitud->codigo_solicitud = $codigos_rechazo[$i];
					$solicitud->idcarga_archivo = $carga_archivo->idcarga_archivo;
					if(strcmp($ids_entidad_rechazo[$i],'') ==0 )
						$solicitud->identidad = null;
					else
						$solicitud->identidad = $ids_entidad_rechazo[$i];
					if(strcmp($ids_herramienta[$i],'') ==0)
						$solicitud->idherramienta = null;
					else{
						$herramienta = Herramienta::find($ids_herramienta[$i]);
						$solicitud->idherramienta = $ids_herramienta[$i];
						$solicitud->motivo_anulacion = "El aplicativo o acción ".$herramienta->nombre.' no es gestionada por el equipo de usuarios';
					}

					$solicitud->idtipo_solicitud_general = $idstipo_solicitud_general_rechazo[$i];					
					$partes = explode('-',$fechas_solicitud_rechazo[$i]);
					$solicitud->fecha_solicitud = date('Y-m-d H:i:s',strtotime($partes[2]."-".$partes[1]."-".$partes[0]));
					if($fechas_estado_rechazo[$i] != null && strcmp($fechas_estado_rechazo[$i],'') != 0)
					{
						$partes = explode('-',$fechas_estado_rechazo[$i]);
						$solicitud->fecha_estado_portal = date('Y-m-d H:i:s',strtotime($partes[2]."-".$partes[1]."-".$partes[0]));	
					}else
					{
						$solicitud->fecha_estado_portal = null;
					}
					
					$solicitud->idestado_solicitud = 5;
					$solicitud->iduser_created_by = $data["user"]->id;
					$solicitud->save();
				}

				$texto_no_procesados = '';
				$texto_final_no_procesado = '';
				$texto_rechazados = '';
				$texto_final_rechazado = '';
				if(count($array_codigos_no_procesados) > 0){
					
					for($i=0;$i<count($array_codigos_no_procesados);$i++){
						$texto_no_procesados=$texto_no_procesados.$array_codigos_no_procesados[$i].'<br>';
					}

					$texto_final_no_procesado = '<strong> SOLICITUDES NO PROCESADAS </strong>:<br>No se realizaron las asignaciones para los siguientes códigos:'.'<br>'.$texto_no_procesados
					.'<br>'.'Posibles Motivos:'.'<br>'.'1. El aplicativo de la solicitud no cuenta con un SLA definido.<br>'.
					'2. No existen usuarios aptos o disponibles para la asignación a la solicitud.<br><br>';
				}

				if(count($codigos_rechazo) > 0){
					for($i=0;$i<count($codigos_rechazo);$i++){
						$texto_rechazados=$texto_rechazados.$codigos_rechazo[$i].'<br>';
					}
					$texto_final_rechazado = '<strong> SOLICITUDES RECHAZADAS </strong>:<br>Se rechazaron los siguientes códgios:'.'<br>'.$texto_rechazados
						.'<br>'.'Motivos:'.'<br>'.'1. El canal no ha ingresado el asunto de la solicitud (PORTAL DE CANALES).<br>2. La solicitud no posee una entidad asociada (PORTAL DE CANALES).<br>3. Las herramientas asociadas no son gestionadas por el equipo de usuarios.<br>';
				}

				if( strcmp($texto_final_no_procesado,'') != 0 || strcmp($texto_final_rechazado, '') != 0 )
				{
					Session::flash('error',$texto_final_no_procesado.$texto_final_rechazado);	
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
	
	public function buscarUsuarioAptoPorSector($idsector)
	{
		$usuarios = User::buscarUsuariosAsignacionPorSector($idsector);	
				
		if(is_array($usuarios) == true) //hay usuarios
		{
			$usuario_apto = User::find($usuarios[0]->id_usuario);
		}else
		{
			$usuario_apto = null;
		}

		return $usuario_apto;
	}

	public function buscarUsuarioAptoPorHerramienta($idherramienta,$idaccion)
	{
		$usuarios = User::buscarUsuariosAsignacionPorHerramienta($idherramienta,$idaccion);	

		if(is_array($usuarios) == true) //hay usuarios
		{
			$usuario_apto = User::find($usuarios[0]->id_usuario);
		}else
		{
			$usuario_apto = null;
		}

		return $usuario_apto;	
	}

	public function buscarUsuarioAptoPorHerramientaV2($idherramienta,$idaccion,$idsector)
	{
		$usuarios = User::buscarUsuariosAsignacionPorHerramientaV2($idherramienta,$idaccion,$idsector);	

		if(is_array($usuarios) == true) //hay usuarios
		{
			$usuario_apto = User::find($usuarios[0]->id_usuario);
		}else
		{
			$usuario_apto = null;
		}

		return $usuario_apto;	
	}

}