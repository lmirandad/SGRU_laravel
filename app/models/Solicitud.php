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

	public function scopeBuscarSolicitudSemaforo($query,$anho,$idusuario)
	{
		$query->leftJoin('sla','sla.idsla','=','solicitud.idsla')
			  ->leftJoin('tipo_solicitudxsla','tipo_solicitudxsla.idsla','=','sla.idsla')
			  ->leftJoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->leftJoin('usuariosxasignacion','usuariosxasignacion.idasignacion','=','asignacion.idasignacion');

		$query->where('solicitud.idestado_solicitud','!=',5)
			  ->where('usuariosxasignacion.idusuario_asignado','=',$idusuario)
			  ->where('usuariosxasignacion.estado_usuario_asignado','=',1)
			  ->whereYear('solicitud.fecha_solicitud','=',$anho);

		$query->select('solicitud.idsolicitud','solicitud.idestado_solicitud','tipo_solicitudxsla.sla_pendiente','tipo_solicitudxsla.sla_procesando','asignacion.fecha_asignacion','solicitud.fecha_inicio_procesando','solicitud.fecha_cierre');

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
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('entidad','entidad.identidad','=','solicitud.identidad')
			  ->leftjoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->leftjoin('sector','sector.idsector','=','canal.idsector')
			  ->leftjoin('herramienta','herramienta.idherramienta','=','solicitud.idherramienta')
			  ->leftjoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud');

		$query->where('solicitud.codigo_solicitud','LIKE',$codigo_solicitud);

		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion','herramienta.nombre as nombre_herramienta');
		
		
		return $query;
	}

	/**********************************************ADMIN******************************************************************/

	public function scopeListarSolicitudes($query)
	{
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud');

		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion');
		return $query;
	}

	public function scopeBuscarSolicitudes($query,$codigo_solicitud,$fecha_solicitud_desde,$fecha_solicitud_hasta,$idtipo_solicitud,$idestado_solicitud,$idsector)
	{
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('entidad','entidad.identidad','=','solicitud.identidad')
			  ->leftjoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->leftjoin('sector','sector.idsector','=','canal.idsector')
			  ->leftjoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud');

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

	/**********************************************************GESTOR**********************************************************************/

	public function scopeListarSolicitudesGestor($query,$idusuario)
	{
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->leftJoin('usuariosxasignacion','usuariosxasignacion.idasignacion','=','asignacion.idasignacion');

		$query->where('usuariosxasignacion.idusuario_asignado','=',$idusuario);
			  $query->where('usuariosxasignacion.estado_usuario_asignado','=',1);

		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion');
		return $query;
	}

	public function scopeBuscarSolicitudesGestor($query,$idusuario,$codigo_solicitud,$fecha_solicitud_desde,$fecha_solicitud_hasta,$idtipo_solicitud,$idestado_solicitud,$idsector)
	{
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('entidad','entidad.identidad','=','solicitud.identidad')
			  ->leftjoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->leftjoin('sector','sector.idsector','=','canal.idsector')
			  ->leftjoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->leftJoin('usuariosxasignacion','usuariosxasignacion.idasignacion','=','asignacion.idasignacion');

		$query->where('usuariosxasignacion.idusuario_asignado','=',$idusuario);
		$query->where('usuariosxasignacion.estado_usuario_asignado','=',1);

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
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('entidad','entidad.identidad','=','solicitud.identidad')
			  ->leftjoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->leftjoin('sector','sector.idsector','=','canal.idsector')
			  ->leftjoin('herramienta','herramienta.idherramienta','=','solicitud.idherramienta')
			  ->leftjoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud');

		$query->where('solicitud.idestado_solicitud','=',$idestado_solicitud);
		
		if($mes_actual != null)
			$query->whereMonth('solicitud.fecha_solicitud','=',$mes_actual);
		if($anho_actual != null)
			$query->whereYear('solicitud.fecha_solicitud','=',$anho_actual);

		
		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion','herramienta.nombre as nombre_herramienta');
		return $query;
		
	}

	public function scopeBuscarPorIdEstadoPorUsuario($query,$idestado_solicitud,$idusuario,$mes_actual,$anho_actual){
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('entidad','entidad.identidad','=','solicitud.identidad')
			  ->leftjoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->leftjoin('sector','sector.idsector','=','canal.idsector')
			  ->leftjoin('herramienta','herramienta.idherramienta','=','solicitud.idherramienta')
			  ->leftjoin('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
			  ->leftjoin('usuariosxasignacion','usuariosxasignacion.idasignacion','=','asignacion.idasignacion');

		$query->where('solicitud.idestado_solicitud','=',$idestado_solicitud);
		
		if($idestado_solicitud != 5)
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



	public function scopeMostrarSolicitudPorEstado($query,$idestado)
	{
		return DB::select('Select sum(CASE when solicitud.idestado_solicitud = '.$idestado.' then 1 else 0 END) as cantidad, meses.idmes from solicitud
							right join meses on (MONTH(solicitud.fecha_solicitud) = meses.idmes)
							group by meses.idmes');
	}

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/* *********************************DASHBOARD ANUAL *****************************************/
	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/


	/*++++++++++++++++++++++++++++++++++++ESTADO+++++++++++++++++++++++++++++++++++++++++++++++*/

	public function scopeMostrarSolicitudPorEstadoAnual($query,$idestado,$anho)
	{
		return DB::select('Select sum(CASE when (solicitud.idestado_solicitud = '.$idestado.' and YEAR(solicitud.fecha_solicitud) = '.$anho.' )then 1 else 					0 END) as cantidad, meses.idmes from solicitud
							right join meses on (MONTH(solicitud.fecha_solicitud) = meses.idmes)
							group by meses.idmes');
	}

	public function scopeMostrarSolicitudPorEstadoAnualUsuario($query,$idestado,$anho,$usuario)
	{
		return DB::select('Select sum(CASE 
			when (solicitud.idestado_solicitud = '.$idestado.' and YEAR(solicitud.fecha_solicitud) = '.$anho.' and usuariosxasignacion.idusuario_asignado = '.$usuario.' and usuariosxasignacion.estado_usuario_asignado = 1) 
			then 1 else 0 END) as cantidad, meses.idmes from solicitud
							right join meses on (MONTH(solicitud.fecha_solicitud) = meses.idmes)
							left join asignacion on (solicitud.idsolicitud = asignacion.idsolicitud)
							left join usuariosxasignacion on (asignacion.idasignacion = usuariosxasignacion.idasignacion)
							group by meses.idmes');
	}

	/*++++++++++++++++++++++++++++++++++++CANAL+++++++++++++++++++++++++++++++++++++++++++++++*/

	public function scopeMostrarSolicitudPorCanalAnual($query,$idcanal,$anho)
	{
		return DB::select('Select sum(CASE 
							when YEAR(solicitud.fecha_solicitud) = '.$anho.' and canal.idcanal = '.$idcanal.'
							then 1 else 0 END) as cantidad, 
							meses.idmes from solicitud
							right join meses on (MONTH(solicitud.fecha_solicitud) = meses.idmes)
							left join asignacion on (solicitud.idsolicitud = asignacion.idsolicitud)
							left join usuariosxasignacion on (asignacion.idasignacion = usuariosxasignacion.idasignacion)
							left join entidad on (entidad.identidad = solicitud.identidad)
							left join canal on (canal.idcanal= entidad.idcanal)
							group by meses.idmes');
	}

	public function scopeMostrarSolicitudPorCanalAnualUsuario($query,$idcanal,$anho,$usuario)
	{
		return DB::select('Select sum(CASE 
							when YEAR(solicitud.fecha_solicitud) = '.$anho.' and canal.idcanal = '.$idcanal.' and usuariosxasignacion.idusuario_asignado = '.$usuario.' and usuariosxasignacion.estado_usuario_asignado = 1 
							then 1 else 0 END) as cantidad, 
							meses.idmes from solicitud
							right join meses on (MONTH(solicitud.fecha_solicitud) = meses.idmes)
							left join asignacion on (solicitud.idsolicitud = asignacion.idsolicitud)
							left join usuariosxasignacion on (asignacion.idasignacion = usuariosxasignacion.idasignacion)
							left join entidad on (entidad.identidad = solicitud.identidad)
							left join canal on (canal.idcanal= entidad.idcanal)
							group by meses.idmes');
	}

	
	public function scopeListarHerramientasEnSolicitudes($query)
	{
		$query->join('herramienta','herramienta.idherramienta','=','solicitud.idherramienta')
			  ->whereNotNull('solicitud.idherramienta')
			  ->select('solicitud.idherramienta','herramienta.nombre as nombre_herramienta')
			  ->distinct();

		return $query;
	}

	/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

	public function scopeMostrarSolicitudAnualDia($query,$anho)
	{
		return DB::select('select SUM( CASE WHEN YEAR(solicitud.fecha_solicitud) = '.$anho.' THEN 1 ELSE 0 END) as cantidad, dia.iddia
						   from solicitud 
						   right JOIN dia ON ( DATEPART(dw,solicitud.fecha_solicitud)-1 = dia.iddia)
						   GROUP BY dia.iddia
						   ORDER BY dia.iddia ASC');
	}

	public function scopeMostrarSolicitudAnualDiaUsuario($query,$anho,$usuario)
	{
		return DB::select('Select SUM( CASE WHEN YEAR(solicitud.fecha_solicitud) = '.$anho.' and usuariosxasignacion.idusuario_asignado = '.$usuario.' 				   and usuariosxasignacion.estado_usuario_asignado = 1  THEN 1 ELSE 0 END) as cantidad, dia.iddia
						   FROM solicitud 
						   right join dia ON ( DATEPART(dw,solicitud.fecha_solicitud)-1 = dia.iddia)
						   left join asignacion on (asignacion.idsolicitud = solicitud.idsolicitud)
						   left join usuariosxasignacion on (usuariosxasignacion.idasignacion = asignacion.idasignacion)
						   GROUP BY dia.iddia
						   ORDER BY dia.iddia ASC');
	}

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	/* *********************************DASHBOARD MENSUAL *****************************************/
	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

	/*++++++++++++++++++++++++++++++++++++ESTADO+++++++++++++++++++++++++++++++++++++++++++++++*/

	public function scopeMostrarSolicitudPorEstadoMes($query,$idestado,$mes,$anho)
	{
		return DB::select('Select sum(CASE when (solicitud.idestado_solicitud = '.$idestado.' and YEAR(solicitud.fecha_solicitud) = '.$anho.' and MONTH(							 solicitud.fecha_solicitud) = '.$mes.' 
							)then 1 else 0 END) as cantidad from solicitud
						');
	}

	public function scopeMostrarSolicitudPorEstadoMesUsuario($query,$idestado,$mes,$anho,$usuario)
	{
		return DB::select('Select sum(CASE when (solicitud.idestado_solicitud = '.$idestado.' and YEAR(solicitud.fecha_solicitud) = '.$anho.' and MONTH(							 solicitud.fecha_solicitud) = '.$mes.' and usuariosxasignacion.idusuario_asignado = '.$usuario.' and usuariosxasignacion.estado_usuario_asignado = 1
							)then 1 else 0 END) as cantidad from solicitud
							left join asignacion on (solicitud.idsolicitud = asignacion.idsolicitud)
							left join usuariosxasignacion on (asignacion.idasignacion = usuariosxasignacion.idasignacion)
						');
	}

	/*++++++++++++++++++++++++++++++++++++CANAL+++++++++++++++++++++++++++++++++++++++++++++++*/

	public function scopeMostrarSolicitudPorCanalMes($query,$idcanal,$mes,$anho)
	{
		return DB::select('Select sum(CASE 
							when YEAR(solicitud.fecha_solicitud) = '.$anho.' and MONTH(solicitud.fecha_solicitud) = '.$mes.' and canal.idcanal = '.$idcanal.'
							then 1 else 0 END) as cantidad from solicitud
							right join meses on (MONTH(solicitud.fecha_solicitud) = meses.idmes)
							left join asignacion on (solicitud.idsolicitud = asignacion.idsolicitud)
							left join usuariosxasignacion on (asignacion.idasignacion = usuariosxasignacion.idasignacion)
							left join entidad on (entidad.identidad = solicitud.identidad)
							left join canal on (canal.idcanal= entidad.idcanal)');
	}

	public function scopeMostrarSolicitudPorCanalMesUsuario($query,$idcanal,$mes,$anho,$usuario)
	{
		return DB::select('Select sum(CASE 
							when YEAR(solicitud.fecha_solicitud) = '.$anho.' and MONTH(solicitud.fecha_solicitud) = '.$mes.' and canal.idcanal = '.$idcanal.' and usuariosxasignacion.idusuario_asignado = '.$usuario.' and usuariosxasignacion.estado_usuario_asignado = 1 
							then 1 else 0 END) as cantidad from solicitud
							right join meses on (MONTH(solicitud.fecha_solicitud) = meses.idmes)
							left join asignacion on (solicitud.idsolicitud = asignacion.idsolicitud)
							left join usuariosxasignacion on (asignacion.idasignacion = usuariosxasignacion.idasignacion)
							left join entidad on (entidad.identidad = solicitud.identidad)
							left join canal on (canal.idcanal= entidad.idcanal)');
	}

	public function scopeListarHerramientasEnSolicitudesMes($query,$mes,$anho)
	{
		$query->join('herramienta','herramienta.idherramienta','=','solicitud.idherramienta')
			  ->whereMonth('solicitud.fecha_solicitud','=',$mes)
			  ->whereYear('solicitud.fecha_solicitud','=',$anho)
			  ->whereNotNull('solicitud.idherramienta')
			  ->select('solicitud.idherramienta','herramienta.nombre as nombre_herramienta')
			  ->distinct();

		return $query;
	}

	/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

	public function scopeMostrarSolicitudMesDia($query,$mes,$anho)
	{
		return DB::select('Select SUM( CASE WHEN YEAR(solicitud.fecha_solicitud) = '.$anho.' and MONTH(solicitud.fecha_solicitud) = '.$mes.' THEN 1 ELSE 			   0 END) as cantidad, dia.iddia
						  FROM solicitud 
						  right join dia ON ( DATEPART(dw,solicitud.fecha_solicitud)-1 = dia.iddia)
						  GROUP BY dia.iddia
						  ORDER BY dia.iddia ASC');
	}

	public function scopeMostrarSolicitudMesDiaUsuario($query,$mes,$anho,$usuario)
	{
		return DB::select('Select SUM( CASE WHEN YEAR(solicitud.fecha_solicitud) = '.$anho.' and MONTH(solicitud.fecha_solicitud) = '.$mes.' and 								  usuariosxasignacion.idusuario_asignado = '.$usuario.' and usuariosxasignacion.estado_usuario_asignado = 1  THEN 1 			  ELSE 0 END) as cantidad, dia.iddia
						  FROM solicitud 
						  right join dia ON ( DATEPART(dw,solicitud.fecha_solicitud)-1 = dia.iddia)
						  left join asignacion on (asignacion.idsolicitud = solicitud.idsolicitud)
						  left join usuariosxasignacion on (usuariosxasignacion.idasignacion = asignacion.idasignacion)
						  GROUP BY dia.iddia
						  ORDER BY dia.iddia ASC');
	}


	/*--------------------------*/

	
	
	public function scopeMostrarSolicitudPorSectorMesUsuario($query,$idestado,$mes,$anho,$usuario)
	{
			return DB::select('Select sum(CASE when (solicitud.idestado_solicitud = '.$idestado.' and YEAR(solicitud.fecha_solicitud) = '.$anho.' and MONTH(solicitud.fecha_solicitud) = '.$mes.'  and usuariosxasignacion.idusuario_asignado = '.$usuario.' and usuariosxasignacion.estado_usuario_asignado = 1 )then 1 else 0 END) as cantidad, sector.idsector from solicitud
			right join entidad on (entidad.identidad = solicitud.identidad)
			right join canal on (canal.idcanal= entidad.idcanal)
			right join sector on (sector.idsector= canal.idsector)
			left join asignacion on (solicitud.idsolicitud = asignacion.idsolicitud)
			left join usuariosxasignacion on (asignacion.idasignacion = usuariosxasignacion.idasignacion)
			group by sector.idsector');
	}

	
	public function scopeBuscarSolicitudesPorFechas($query,$fecha_desde,$fecha_hasta)
	{
		$query->leftjoin('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->leftjoin('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->leftjoin('entidad','entidad.identidad','=','solicitud.identidad')
			  ->leftjoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->leftjoin('sector','sector.idsector','=','canal.idsector');

		
		if($fecha_desde != "")
			$query->where('solicitud.fecha_solicitud','>=',date('Y-m-d H:i:s',strtotime($fecha_desde)));
		
		if($fecha_hasta != "")
			$query->where('solicitud.fecha_solicitud','<=',date('Y-m-d H:i:s',strtotime('+23 hours +59 minutes +59 seconds',strtotime($fecha_hasta))));


		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud');

		return $query;
	}


}
