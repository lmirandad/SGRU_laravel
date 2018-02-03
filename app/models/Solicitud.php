<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Solicitud extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'solicitud';
	protected $softDelete = true;
	protected $primaryKey = 'idsolicitud';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	//Query para buscar solicitudes pendientes y procesando
	public function scopeBuscarSolicitudesPendientesProcesando($query,$identidad)
	{
		$query->where('solicitud.identidad','=',$identidad)
		      ->whereNested(function($query){
		      	$query->where('solicitud.idestado_solicitud','=',3)
		      		  ->orWhere('solicitud.idestado_solicitud','=',4);
		      });

		$query->select('solicitud.*');

		return $query;
	}

	//Query para buscar solicitudes de un determinado anho y usuario asignado
	public function scopeBuscarSolicitudSemaforo($query,$anho,$idusuario)
	{
		$query->leftJoin('sla','sla.idsla','=','solicitud.idsla')
			  ->leftJoin('tipo_solicitudxsla','tipo_solicitudxsla.idsla','=','sla.idsla')
			  ->leftJoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->leftJoin('usuariosxasignacion','usuariosxasignacion.idasignacion','=','asignacion.idasignacion')
			  ->leftJoin('carga_archivo','solicitud.idcarga_archivo','=','carga_archivo.idcarga_archivo');

		$query->where('solicitud.idestado_solicitud','!=',5)
			  ->where('usuariosxasignacion.idusuario_asignado','=',$idusuario)
			  ->where('usuariosxasignacion.estado_usuario_asignado','=',1)
			  ->whereYear('solicitud.fecha_solicitud','=',$anho);

		//Se asume que usuariosxasignacion.estado_usuario_asignado = 1 puesto que es el usuario asignado actual considerando que existen reasignaciones

		$query->select('solicitud.idsolicitud','solicitud.idestado_solicitud','tipo_solicitudxsla.sla_pendiente','tipo_solicitudxsla.sla_procesando','asignacion.fecha_asignacion','solicitud.fecha_inicio_procesando','solicitud.fecha_cierre','carga_archivo.*');

	}

	//Query para buscar solicitudes por id solicitud
	public function scopeBuscarSolicitudPorIdSolicitud($query,$idsolicitud)
	{
		$query->leftJoin('sla','sla.idsla','=','solicitud.idsla')
			  ->leftJoin('tipo_solicitudxsla','tipo_solicitudxsla.idsla','=','sla.idsla')
			  ->leftJoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->leftJoin('usuariosxasignacion','usuariosxasignacion.idasignacion','=','asignacion.idasignacion')
			  ->leftJoin('carga_archivo','solicitud.idcarga_archivo','=','carga_archivo.idcarga_archivo');

		$query->where('usuariosxasignacion.estado_usuario_asignado','=',1)
			  ->where('solicitud.idsolicitud','=',$idsolicitud);

		$query->select('solicitud.idsolicitud','solicitud.idestado_solicitud','solicitud.fecha_solicitud','tipo_solicitudxsla.sla_pendiente','tipo_solicitudxsla.sla_procesando','asignacion.fecha_asignacion','solicitud.fecha_inicio_procesando','solicitud.fecha_cierre','carga_archivo.*');

	}

	//QUery para buscar solicitudes pendientes y procesando por usuario asignado
	public function scopeBuscarSolicitudesPendientesProcesandoPorIdUsuario($query,$idusuario)
	{
		$query->join('asignacion','solicitud.idsolicitud','=','asignacion.idsolicitud');
		$query->where('asignacion.iduser_asignado','=',$idusuario)
		      ->whereNested(function($query){
		      	$query->where('solicitud.idestado_solicitud','=',3)
		      		  ->orWhere('solicitud.idestado_solicitud','=',4);
		      });

		$query->select('solicitud.*','asignacion.*');

		

		return $query;	
	}

	//Query para buscar solicitudes pendientes y procesando por sla
	public function scopeBuscarSolicitudesPendientesProcesandoPorIdSla($query,$idsla)
	{
		$query->join('asignacion','solicitud.idsolicitud','=','asignacion.idsolicitud');
		$query->where('solicitud.idsla','=',$idsla)
		      ->whereNested(function($query){
		      	$query->where('solicitud.idestado_solicitud','=',3)
		      		  ->orWhere('solicitud.idestado_solicitud','=',4);
		      });

		$query->select('solicitud.*','asignacion.*');

		

		return $query;	
	}
	
	//Query para buscar solicitudes por codigo de solicitud (no ID solicitud)
	public function scopeBuscarPorCodigoSolicitud($query,$codigo_solicitud)
	{
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('entidad','entidad.identidad','=','solicitud.identidad')
			  ->leftjoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->leftjoin('sector','sector.idsector','=','canal.idsector')
			  ->leftjoin('herramienta','herramienta.idherramienta','=','solicitud.idherramienta')
			  ->leftjoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->leftJoin('carga_archivo','solicitud.idcarga_archivo','=','carga_archivo.idcarga_archivo');

		$query->where('solicitud.codigo_solicitud','LIKE',$codigo_solicitud);

		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion','herramienta.nombre as nombre_herramienta','carga_archivo.*');
		
		
		return $query;
	}

	/**********************************************ADMIN******************************************************************/

	//Query de admin para listar todas las solicitudes
	public function scopeListarSolicitudes($query)
	{
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->leftJoin('carga_archivo','solicitud.idcarga_archivo','=','carga_archivo.idcarga_archivo');

		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion','carga_archivo.numero_corte as numero_corte');

		$query->orderBy('solicitud.fecha_solicitud','ASC');
		$query->orderBy('solicitud.ticket_reasignado','DESC');
		return $query;
	}

	//Query para buscar solicitudes por determinados criterios de busqueda (criterios: codigo_solicitud, fecha_asignacion_desde,fecha_asignacion_hasta, tipo_solicitud (accion), estado_solicitud (estado), sector)
	public function scopeBuscarSolicitudes($query,$codigo_solicitud,$fecha_asignacion_desde,$fecha_asignacion_hasta,$idtipo_solicitud,$idestado_solicitud,$idsector)
	{
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('entidad','entidad.identidad','=','solicitud.identidad')
			  ->leftjoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->leftjoin('sector','sector.idsector','=','canal.idsector')
			  ->leftjoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->leftJoin('carga_archivo','solicitud.idcarga_archivo','=','carga_archivo.idcarga_archivo');

		if($codigo_solicitud != null)
			$query->where('solicitud.codigo_solicitud','LIKE',$codigo_solicitud);
		
		if($fecha_asignacion_desde != "")
			$query->where('asignacion.fecha_asignacion','>=',date('Y-m-d H:i:s',strtotime($fecha_asignacion_desde)));
		
		if($fecha_asignacion_hasta != "")
			$query->where('asignacion.fecha_asignacion','<=',date('Y-m-d H:i:s',strtotime('+23 hours +59 minutes +59 seconds',strtotime($fecha_asignacion_hasta))));

		if($idtipo_solicitud != 0)
			$query->where('solicitud.idtipo_solicitud','=',$idtipo_solicitud);

		if($idestado_solicitud != 0)
			$query->where('solicitud.idestado_solicitud','=',$idestado_solicitud);

		if($idsector != 0)
			$query->where('sector.idsector','=',$idsector);

		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion','carga_archivo.numero_corte as numero_corte');
		$query->orderBy('asignacion.fecha_asignacion','ASC');
		$query->orderBy('solicitud.ticket_reasignado','DESC');
		return $query;
	}

	/**********************************************************GESTOR**********************************************************************/

	//Query para listar las solicitudes por un determinado usuario asignado
	public function scopeListarSolicitudesGestor($query,$idusuario)
	{
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->leftJoin('usuariosxasignacion','usuariosxasignacion.idasignacion','=','asignacion.idasignacion')
			  ->leftJoin('carga_archivo','solicitud.idcarga_archivo','=','carga_archivo.idcarga_archivo');

		$query->where('usuariosxasignacion.idusuario_asignado','=',$idusuario);
			  $query->where('usuariosxasignacion.estado_usuario_asignado','=',1);

		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion','carga_archivo.numero_corte as numero_corte');
		$query->orderBy('solicitud.fecha_solicitud','ASC');
		$query->orderBy('solicitud.ticket_reasignado','DESC');
		return $query;
	}


	//Query para buscar las solicitudes de un usuario asignado por determinados criterios de busqueda (criterios: codigo_solicitud, fecha_asignacion_desde, fecha_asignacion_hasta, tipo_solicitud (accion), estado_solicitud, sector)
	public function scopeBuscarSolicitudesGestor($query,$idusuario,$codigo_solicitud,$fecha_asignacion_desde,$fecha_asignacion_hasta,$idtipo_solicitud,$idestado_solicitud,$idsector)
	{
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('entidad','entidad.identidad','=','solicitud.identidad')
			  ->leftjoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->leftjoin('sector','sector.idsector','=','canal.idsector')
			  ->leftjoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->leftJoin('usuariosxasignacion','usuariosxasignacion.idasignacion','=','asignacion.idasignacion')
			  ->leftJoin('carga_archivo','solicitud.idcarga_archivo','=','carga_archivo.idcarga_archivo');

		$query->where('usuariosxasignacion.idusuario_asignado','=',$idusuario);
		$query->where('usuariosxasignacion.estado_usuario_asignado','=',1);

		if($codigo_solicitud != null)
			$query->where('solicitud.codigo_solicitud','LIKE',$codigo_solicitud);
		
		if($fecha_asignacion_desde != "")
			$query->where('asignacion.fecha_asignacion','>=',date('Y-m-d H:i:s',strtotime($fecha_asignacion_desde)));
		
		if($fecha_asignacion_hasta != "")
			$query->where('asignacion.fecha_asignacion','<=',date('Y-m-d H:i:s',strtotime('+23 hours +59 minutes +59 seconds',strtotime($fecha_asignacion_hasta))));

		if($idtipo_solicitud != 0)
			$query->where('solicitud.idtipo_solicitud','=',$idtipo_solicitud);

		if($idestado_solicitud != 0)
			$query->where('solicitud.idestado_solicitud','=',$idestado_solicitud);

		if($idsector != 0)
			$query->where('sector.idsector','=',$idsector);

		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion','carga_archivo.*');
		$query->orderBy('asignacion.fecha_asignacion','ASC');
		$query->orderBy('solicitud.ticket_reasignado','DESC');
		return $query;
	}

	//Query para buscar solicitudes por estado de solicitud en un determinado mes y anho
	public function scopeBuscarPorIdEstado($query,$idestado_solicitud,$mes_actual,$anho_actual){
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('entidad','entidad.identidad','=','solicitud.identidad')
			  ->leftjoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->leftjoin('sector','sector.idsector','=','canal.idsector')
			  ->leftjoin('herramienta','herramienta.idherramienta','=','solicitud.idherramienta')
			  ->leftjoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->leftJoin('carga_archivo','solicitud.idcarga_archivo','=','carga_archivo.idcarga_archivo');

		$query->where('solicitud.idestado_solicitud','=',$idestado_solicitud);
		
		if($mes_actual != null)
			$query->whereMonth('solicitud.fecha_solicitud','=',$mes_actual);
		if($anho_actual != null)
			$query->whereYear('solicitud.fecha_solicitud','=',$anho_actual);

		
		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion','herramienta.nombre as nombre_herramienta','carga_archivo.*');
		$query->orderBy('solicitud.fecha_solicitud','ASC');
		$query->orderBy('solicitud.ticket_reasignado','DESC');
		return $query;
		
	}

	//Query para buscar solicitudes por estado y por usuario en un determinado mes y anho
	public function scopeBuscarPorIdEstadoPorUsuario($query,$idestado_solicitud,$idusuario,$mes_actual,$anho_actual){
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('entidad','entidad.identidad','=','solicitud.identidad')
			  ->leftjoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->leftjoin('sector','sector.idsector','=','canal.idsector')
			  ->leftjoin('herramienta','herramienta.idherramienta','=','solicitud.idherramienta')
			  ->leftjoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->leftjoin('usuariosxasignacion','usuariosxasignacion.idasignacion','=','asignacion.idasignacion')
			  ->leftJoin('carga_archivo','solicitud.idcarga_archivo','=','carga_archivo.idcarga_archivo');

		$query->where('solicitud.idestado_solicitud','=',$idestado_solicitud);
		
		if($idestado_solicitud != 5)
			$query->where('usuariosxasignacion.estado_usuario_asignado','=',1);

		if($idusuario != null)
			$query->where('usuariosxasignacion.idusuario_asignado','=',$idusuario);
		if($mes_actual != null)
			$query->whereMonth('solicitud.fecha_solicitud','=',$mes_actual);
		if($anho_actual != null)

			$query->whereYear('solicitud.fecha_solicitud','=',$anho_actual);
		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion','herramienta.nombre as nombre_herramienta','usuariosxasignacion.idusuario_asignado as idusuario_asignado','carga_archivo.*');

		$query->orderBy('solicitud.fecha_solicitud','ASC');
		$query->orderBy('solicitud.ticket_reasignado','DESC');
		return $query;
	}



	public function scopeResumenSolicitudesPorUsuario($query)
	{
		return DB::select('Select CONCAT(users.nombre,\' \',users.apellido_paterno,\' \',users.apellido_materno) nombre_usuario,count(codigo_solicitud) cantidad_total, sum(case when solicitud.idestado_solicitud = 3 then 1 else 0 end) cantidad_pendientes,sum(case when solicitud.idestado_solicitud = 4 then 1 else 0 end) cantidad_procesando
			FROM solicitud
			join asignacion on (solicitud.idsolicitud = asignacion.idsolicitud)
			join usuariosxasignacion on (usuariosxasignacion.idasignacion = asignacion.idasignacion)
			join users on (users.id = usuariosxasignacion.idusuario_asignado)
			where usuariosxasignacion.estado_usuario_asignado = 1
			and (solicitud.idestado_solicitud = 3 OR solicitud.idestado_solicitud = 4) 
			group by CONCAT(users.nombre,\' \',users.apellido_paterno,\' \',users.apellido_materno)
			order by cantidad_total DESC');
	}

	public function scopeResumenSolicitudesPorSectorPorUsuario($query)
	{
		return DB::select('Select sector.nombre nombre_sector, CONCAT(users.nombre,\' \',users.apellido_paterno,\' \',users.apellido_materno) nombre_usuario ,count(codigo_solicitud) cantidad_total, sum(case when solicitud.idestado_solicitud = 3 then 1 else 0 end) cantidad_pendientes,sum(case when solicitud.idestado_solicitud = 4 then 1 else 0 end) cantidad_procesando
			FROM solicitud
			join asignacion on (solicitud.idsolicitud = asignacion.idsolicitud)
			join usuariosxasignacion on (usuariosxasignacion.idasignacion = asignacion.idasignacion)
			join users on (users.id = usuariosxasignacion.idusuario_asignado)
			join entidad on (entidad.identidad = solicitud.identidad)
			join canal on (canal.idcanal = entidad.idcanal)
			join sector on (sector.idsector = canal.idsector)
			where usuariosxasignacion.estado_usuario_asignado = 1
			and (solicitud.idestado_solicitud = 3 OR solicitud.idestado_solicitud = 4) 
			group by sector.nombre ,CONCAT(users.nombre,\' \',users.apellido_paterno,\' \',users.apellido_materno)
			order by nombre_sector DESC');
	}

	public function scopeResumenSolicitudesPorSector($query)
	{
		return DB::select('Select sector.nombre nombre_sector,count(codigo_solicitud) cantidad_total, sum(case when solicitud.idestado_solicitud = 3 				then 1 else 0 end) cantidad_pendientes,sum(case when solicitud.idestado_solicitud = 4 then 1 else 0 end) cantidad_procesando 
			FROM solicitud
		join entidad on (entidad.identidad = solicitud.identidad)
		join canal on (canal.idcanal = entidad.idcanal)
		join sector on (sector.idsector = canal.idsector)
		where (solicitud.idestado_solicitud = 3 OR solicitud.idestado_solicitud = 4) 
		group by sector.nombre
		order by nombre_sector DESC');
	}

	public function scopeBuscarResumenSolicitudesPorUsuarioPorFecha($query,$mes,$anho)
	{
		return DB::select('Select CONCAT(users.nombre,\' \',users.apellido_paterno,\' \',users.apellido_materno) nombre_usuario,count(codigo_solicitud) cantidad_total, sum(case when solicitud.idestado_solicitud = 3 then 1 else 0 end) cantidad_pendientes,sum(case when solicitud.idestado_solicitud = 4 then 1 else 0 end) cantidad_procesando
			FROM solicitud
			join asignacion on (solicitud.idsolicitud = asignacion.idsolicitud)
			join usuariosxasignacion on (usuariosxasignacion.idasignacion = asignacion.idasignacion)
			join users on (users.id = usuariosxasignacion.idusuario_asignado)
			where usuariosxasignacion.estado_usuario_asignado = 1
			and month(solicitud.fecha_solicitud) = '.$mes.
			' and year(solicitud.fecha_solicitud) = '.$anho.
			' and (solicitud.idestado_solicitud = 3 OR solicitud.idestado_solicitud = 4)  
			group by CONCAT(users.nombre,\' \',users.apellido_paterno,\' \',users.apellido_materno)
			order by cantidad_total DESC');
	}

	public function scopeBuscarResumenSolicitudesPorSectorPorUsuarioPorFecha($query,$mes,$anho)
	{
		return DB::select('Select sector.nombre nombre_sector, CONCAT(users.nombre,\' \',users.apellido_paterno,\' \',users.apellido_materno) nombre_usuario ,count(codigo_solicitud) cantidad_total, sum(case when solicitud.idestado_solicitud = 3 then 1 else 0 end) cantidad_pendientes,sum(case when solicitud.idestado_solicitud = 4 then 1 else 0 end) cantidad_procesando
			FROM solicitud
			join asignacion on (solicitud.idsolicitud = asignacion.idsolicitud)
			join usuariosxasignacion on (usuariosxasignacion.idasignacion = asignacion.idasignacion)
			join users on (users.id = usuariosxasignacion.idusuario_asignado)
			join entidad on (entidad.identidad = solicitud.identidad)
			join canal on (canal.idcanal = entidad.idcanal)
			join sector on (sector.idsector = canal.idsector)
			where usuariosxasignacion.estado_usuario_asignado = 1
			and (solicitud.idestado_solicitud = 3 OR solicitud.idestado_solicitud = 4) 
			and month(solicitud.fecha_solicitud) = '.$mes.
			' and year(solicitud.fecha_solicitud) = '.$anho.
			' group by sector.nombre ,CONCAT(users.nombre,\' \',users.apellido_paterno,\' \',users.apellido_materno)
			order by nombre_sector DESC');
	}

	public function scopeBuscarResumenSolicitudesPorSectorPorFecha($query,$mes,$anho)
	{
		return DB::select('Select sector.nombre nombre_sector,count(codigo_solicitud) cantidad_total, sum(case when solicitud.idestado_solicitud = 3 				then 1 else 0 end) cantidad_pendientes,sum(case when solicitud.idestado_solicitud = 4 then 1 else 0 end) cantidad_procesando 
			FROM solicitud
			join entidad on (entidad.identidad = solicitud.identidad)
			join canal on (canal.idcanal = entidad.idcanal)
			join sector on (sector.idsector = canal.idsector)
			where month(solicitud.fecha_solicitud) = '.$mes.
			' and year(solicitud.fecha_solicitud) = '.$anho.
			' and (solicitud.idestado_solicitud = 3 OR solicitud.idestado_solicitud = 4) 
			group by sector.nombre
			order by nombre_sector DESC');
	}

	//Query para buscar solicitudes pendientes y procesando por herramienta y usuario
	public function scopeBuscarSolicitudesPendientesProcesandoPorHerramientaUsuario($query,$idherramienta,$idusuario)
	{
		$query->join('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->join('usuariosxasignacion','usuariosxasignacion.idasignacion','=','asignacion.idasignacion');
		$query->where('solicitud.idherramienta','=',$idherramienta);
		$query->where('usuariosxasignacion.estado_usuario_asignado','=',1);
		$query->where('usuariosxasignacion.idusuario_asignado','=',$idusuario);
		$query->whereNested(function($query){
		      	$query->where('solicitud.idestado_solicitud','=',3)
		      		  ->orWhere('solicitud.idestado_solicitud','=',4);
		      });
		$query->select('solicitud.*');
		return $query;

	}

	//Query para buscar solicitudes pendientes y procesando por sector y usuario
	public function scopeBuscarSolicitudesPendientesProcesandoPorSectorUsuario($query,$idsector,$idusuario)
	{
		$query->join('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->join('usuariosxasignacion','usuariosxasignacion.idasignacion','=','asignacion.idasignacion')
			  ->join('entidad','entidad.identidad','=','solicitud.identidad')
			  ->join('canal','canal.idcanal','=','entidad.idcanal')
			  ->join('sector','sector.idsector','=','canal.idsector');
		$query->where('sector.idsector','=',$idsector);
		$query->where('usuariosxasignacion.estado_usuario_asignado','=',1);
		$query->where('usuariosxasignacion.idusuario_asignado','=',$idusuario);
		$query->whereNested(function($query){
		      	$query->where('solicitud.idestado_solicitud','=',3)
		      		  ->orWhere('solicitud.idestado_solicitud','=',4);
		      });
		$query->select('solicitud.*');
		return $query;

	}

	//Query para buscar solicitudes pendientes y procesando por sector
	public function scopeBuscarSolicitudesPendientesProcesandoPorSector($query,$idsector)
	{
		$query->join('entidad','entidad.identidad','=','solicitud.identidad')
			  ->join('canal','canal.idcanal','=','entidad.idcanal')
			  ->join('sector','sector.idsector','=','canal.idsector');
		$query->where('sector.idsector','=',$idsector);
		$query->whereNested(function($query){
		      	$query->where('solicitud.idestado_solicitud','=',3)
		      		  ->orWhere('solicitud.idestado_solicitud','=',4);
		      });
		$query->select('solicitud.*');
		return $query;

	}

	//Query para buscar solicitudes por canal
	public function scopeBuscarSolicitudesPorCanal($query,$idcanal)
	{
		$query->join('entidad','entidad.identidad','=','solicitud.identidad')
			  ->join('canal','canal.idcanal','=','entidad.idcanal');
		$query->where('canal.idcanal','=',$idcanal);
		
		$query->select('solicitud.*');
		return $query;

	}

	//Query para buscar solicitudes por entidad
	public function scopeBuscarSolicitudesPorEntidad($query,$identidad)
	{
		
		$query->where('solicitud.identidad','=',$identidad);
		
		$query->select('solicitud.*');
		return $query;

	}

	//Query para buscar solicitudes pendientes y procesando por herramienta y sector
	public function scopeBuscarSolicitudesPendientesProcesandoPorHerramientaSector($query,$idherramienta,$idsector)
	{
		$query->join('entidad','entidad.identidad','=','solicitud.identidad')
			  ->join('canal','canal.idcanal','=','entidad.idcanal')
			  ->join('sector','sector.idsector','=','canal.idsector');
		$query->where('sector.idsector','=',$idsector);
		$query->where('solicitud.idherramienta','=',$idherramienta);
		$query->whereNested(function($query){
		      	$query->where('solicitud.idestado_solicitud','=',3)
		      		  ->orWhere('solicitud.idestado_solicitud','=',4);
		      });
		$query->select('solicitud.*');
		return $query;
	}
	
	//Query para buscar solicitudes por un rango de fechas (Fecha desde y fecha_hasta)
	public function scopeBuscarSolicitudesPorFechas($query,$fecha_desde,$fecha_hasta)
	{
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('entidad','entidad.identidad','=','solicitud.identidad')
			  ->leftjoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->leftjoin('sector','sector.idsector','=','canal.idsector')
			  ->leftJoin('carga_archivo','solicitud.idcarga_archivo','=','carga_archivo.idcarga_archivo')
			  ->leftJoin('asignacion','solicitud.idsolicitud','=','asignacion.idsolicitud');

		
		if($fecha_desde != "")
			$query->where('solicitud.fecha_solicitud','>=',date('Y-m-d H:i:s',strtotime($fecha_desde)));
		
		if($fecha_hasta != "")
			$query->where('solicitud.fecha_solicitud','<=',date('Y-m-d H:i:s',strtotime('+23 hours +59 minutes +59 seconds',strtotime($fecha_hasta))));


		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','carga_archivo.*','asignacion.fecha_asignacion as fecha_asignacion');

		return $query;
	}


}
