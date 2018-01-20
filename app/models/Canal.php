<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Canal extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'canal';
	protected $softDelete = true;
	protected $primaryKey = 'idcanal';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	

	public function scopeListarCanales($query)
	{
		$query->join('sector','sector.idsector','=','canal.idsector')
			   ->join('canal_agrupado','canal_agrupado.idcanal_agrupado','=','canal.idcanal_agrupado');

		$query->select('canal.*','sector.nombre as nombre_sector','canal_agrupado.nombre as nombre_canal_agrupado');

		return $query;
	}

	public function scopeListarCanalesConSolicitudesMesAnho($query,$mes,$anho)
	{
		$query->join('entidad','entidad.idcanal','=','canal.idcanal')
		      ->join('solicitud','solicitud.identidad','=','entidad.identidad');

		$query->whereYear('solicitud.fecha_solicitud','=',$anho);
		$query->whereMonth('solicitud.fecha_solicitud','=',$mes);
		$query->select('canal.*');
		$query->distinct();
		return $query;
	}

	public function scopeListarCanalesConSolicitudesMesAnhoUsuario($query,$mes,$anho,$usuario)
	{
		$query->join('entidad','entidad.idcanal','=','canal.idcanal')
		      ->join('solicitud','solicitud.identidad','=','entidad.identidad')
		      ->join('asignacion','asignacion.idsolicitud','=','solicitud.idsolicitud')
		      ->join('usuariosxasignacion','usuariosxasignacion.idasignacion','=','asignacion.idasignacion');

		$query->whereYear('solicitud.fecha_solicitud','=',$anho);
		$query->whereMonth('solicitud.fecha_solicitud','=',$mes);
		$query->where('usuariosxasignacion.idusuario_asignado','=',$usuario);
		$query->where('usuariosxasignacion.estado_usuario_asignado','=',1);
		$query->select('canal.*');
		$query->distinct();
		return $query;
	}

	public function scopeBuscarCanalesPorIdSector($query,$idsector){
		$query->where('canal.idsector','=',$idsector);
		$query->select('canal.*');

		return $query;	
	}

	public function scopeBuscarCanales($query,$nombre_canal,$idsector){
		
		$query->join('sector','canal.idsector','=','sector.idsector')
			  ->join('canal_agrupado','canal_agrupado.idcanal_agrupado','=','canal.idcanal_agrupado');
		if($nombre_canal != null)
			$query->where('canal.nombre','LIKE',"%$nombre_canal%");
		if(strcmp($idsector,"")!=0)
			$query->where('canal.idsector','=',$idsector);

		$query->select('canal.*','sector.nombre as nombre_sector','canal_agrupado.nombre as nombre_canal_agrupado');

	}
	
}
