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
	
	public function scopeBuscarPorCodigoSolicitud($query,$codigo_solicitud)
	{
		$query->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->join('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->join('entidad','entidad.identidad','=','solicitud.identidad')
			  ->join('canal','canal.idcanal','=','entidad.idcanal')
			  ->join('sector','sector.idsector','=','canal.idsector')
			  ->leftjoin('herramienta','herramienta.idherramienta','=','solicitud.idherramienta')
			  ->join('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud');

		$query->where('solicitud.codigo_solicitud','LIKE',$codigo_solicitud);

		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion','herramienta.nombre as nombre_herramienta');
		
		
		return $query;
	}

	public function scopeListarSolicitudes($query)
	{
		$query->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->join('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->join('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud');

		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion');
		return $query;
	}

	public function scopeBuscarSolicitudes($query,$codigo_solicitud,$fecha_solicitud_desde,$fecha_solicitud_hasta,$idtipo_solicitud,$idestado_solicitud,$idsector)
	{
		$query->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->join('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->join('entidad','entidad.identidad','=','solicitud.identidad')
			  ->join('canal','canal.idcanal','=','entidad.idcanal')
			  ->join('sector','sector.idsector','=','canal.idsector')
			  ->join('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud');

		if($codigo_solicitud != null)
			$query->where('solicitud.codigo_solicitud','LIKE',$codigo_solicitud);
		
		if($fecha_solicitud_desde != "")
			$query->where('solicitud.fecha_solicitud','>=',date('Y-m-d H:i:s',strtotime($fecha_solicitud_desde)));
		
		if($fecha_solicitud_hasta != "")
			$query->where('solicitud.fecha_solicitud','<=',date('Y-m-d H:i:s',strtotime($fecha_solicitud_hasta)));

		if($idtipo_solicitud != 0)
			$query->where('solicitud.idtipo_solicitud','=',$idtipo_solicitud);

		if($idestado_solicitud != 0)
			$query->where('solicitud.idestado_solicitud','=',$idestado_solicitud);

		if($idsector != 0)
			$query->where('sector.idsector','=',$idsector);

		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion');
		return $query;
	}

	public function scopeBuscarPorIdEstado($query,$idestado_solicitud,$mes_actual,$anho_actual){
		$query->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->join('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->join('entidad','entidad.identidad','=','solicitud.identidad')
			  ->join('canal','canal.idcanal','=','entidad.idcanal')
			  ->join('sector','sector.idsector','=','canal.idsector')
			  ->leftjoin('herramienta','herramienta.idherramienta','=','solicitud.idherramienta')
			  ->join('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud');

		$query->where('solicitud.idestado_solicitud','=',$idestado_solicitud);
		
		if($mes_actual != null)
			$query->whereMonth('solicitud.fecha_solicitud','=',$mes_actual);
		if($anho_actual != null)
			$query->whereYear('solicitud.fecha_solicitud','=',$anho_actual);

		
		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion','herramienta.nombre as nombre_herramienta');
		return $query;
		
	}

	public function scopeBuscarPorIdEstadoPorUsuario($query,$idestado_solicitud,$idusuario,$mes_actual,$anho_actual){
		$query->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->join('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->join('entidad','entidad.identidad','=','solicitud.identidad')
			  ->join('canal','canal.idcanal','=','entidad.idcanal')
			  ->join('sector','sector.idsector','=','canal.idsector')
			  ->leftjoin('herramienta','herramienta.idherramienta','=','solicitud.idherramienta')
			  ->join('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->join('usuariosxasignacion','usuariosxasignacion.idasignacion','=','asignacion.idasignacion');

		$query->where('solicitud.idestado_solicitud','=',$idestado_solicitud);
		$query->where('usuariosxasignacion.estado_usuario_asignado','=',1);
		if($idusuario != null)
			$query->where('usuariosxasignacion.idusuario_asignado','=',$idusuario);
		if($mes_actual != null)
			$query->whereMonth('solicitud.fecha_solicitud','=',$mes_actual);
		if($anho_actual != null)
			$query->whereYear('solicitud.fecha_solicitud','=',$anho_actual);
		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion','herramienta.nombre as nombre_herramienta');
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
			' group by CONCAT(users.nombre,\' \',users.apellido_paterno,\' \',users.apellido_materno)
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
			' group by sector.nombre
			order by nombre_sector DESC');
	}

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

	public function scopeBuscarSolicitudesPorCanal($query,$idcanal)
	{
		$query->join('entidad','entidad.identidad','=','solicitud.identidad')
			  ->join('canal','canal.idcanal','=','entidad.idcanal');
		$query->where('canal.idcanal','=',$idcanal);
		
		$query->select('solicitud.*');
		return $query;

	}

	public function scopeBuscarSolicitudesPorEntidad($query,$identidad)
	{
		
		$query->where('solicitud.identidad','=',$identidad);
		
		$query->select('solicitud.*');
		return $query;

	}

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

}
