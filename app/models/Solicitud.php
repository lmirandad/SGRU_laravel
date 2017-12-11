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
		$query->join('asignacion','solicitud.idasignacion','=','asignacion.idasignacion');
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
		$query->join('asignacion','solicitud.idasignacion','=','asignacion.idasignacion');
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
		$query->where('solicitud.codigo_solicitud','LIKE',$codigo_solicitud);
		$query->select('solicitud.*');
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

	public function scopeBuscarPorIdEstado($query,$idestado_solicitud){
		$query->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->join('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->join('entidad','entidad.identidad','=','solicitud.identidad')
			  ->join('canal','canal.idcanal','=','entidad.idcanal')
			  ->join('sector','sector.idsector','=','canal.idsector')
			  ->join('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud');

		$query->where('solicitud.idestado_solicitud','=',$idestado_solicitud);
		
		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion');
		return $query;
	}

	public function scopeBuscarPorIdEstadoPorUsuario($query,$idestado_solicitud,$idusuario){
		$query->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','solicitud.idtipo_solicitud')
			  ->join('estado_solicitud','estado_solicitud.idestado_solicitud','=','solicitud.idestado_solicitud')
			  ->join('entidad','entidad.identidad','=','solicitud.identidad')
			  ->join('canal','canal.idcanal','=','entidad.idcanal')
			  ->join('sector','sector.idsector','=','canal.idsector')
			  ->join('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud');

		$query->where('solicitud.idestado_solicitud','=',$idestado_solicitud);
		$query->where('asignacion.iduser_asignado','=',$idusuario);
		$query->select('solicitud.*','tipo_solicitud.nombre as nombre_tipo_solicitud','estado_solicitud.nombre as nombre_estado_solicitud','asignacion.fecha_asignacion as fecha_asignacion');;
		return $query;
	}

}
