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
	
	//Query para listar todos los canales registrados en el sistema, incluido su sector y canal_agrupado
	public function scopeListarCanales($query)
	{
		$query->join('sector','sector.idsector','=','canal.idsector')
			   ->join('canal_agrupado','canal_agrupado.idcanal_agrupado','=','canal.idcanal_agrupado');

		$query->select('canal.*','sector.nombre as nombre_sector','canal_agrupado.nombre as nombre_canal_agrupado');

		return $query;
	}

	//Query para listar todos los canales que han solicitado Tickets (solicitudes) en un determinado mes y año
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

	//Query para listar todos los canales que han solicitado Tickets (solicitudes) en un determinado mes y año y de un determinado usuario (gestor)
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

	//Query para listar todos los canales de un determinado sector
	public function scopeBuscarCanalesPorIdSector($query,$idsector){
		$query->where('canal.idsector','=',$idsector);
		$query->select('canal.*');

		return $query;	
	}

	//Query para buscar los canales por determinados criterios de busqueda (Criterios: Nombre Canal - Id Sector - Id Canal Agrupado)
	public function scopeBuscarCanales($query,$nombre_canal,$idsector,$idcanal_agrupado){
		
		$query->join('sector','canal.idsector','=','sector.idsector')
			  ->join('canal_agrupado','canal_agrupado.idcanal_agrupado','=','canal.idcanal_agrupado');
		if($nombre_canal != null)
			$query->where('canal.nombre','LIKE',"%$nombre_canal%");
		if(strcmp($idsector,"")!=0)
			$query->where('canal.idsector','=',$idsector);
		if($idcanal_agrupado != null)
			$query->where('canal.idcanal_agrupado','=',$idcanal_agrupado);

		$query->select('canal.*','sector.nombre as nombre_sector','canal_agrupado.nombre as nombre_canal_agrupado');

	}

	//Query para buscar los canales por determinado usuario responsable
	public function scopeBuscarCanalesPorIdUsuarioResponsable($query,$idusuario)
	{
		$query->where('canal.idusuario_responsable','=',$idusuario);
		$query->select('canal.*');
		return $query;
	}

	//Query para buscar los canales que no poseen usuarios responsables.
	public function scopeListarCanalesSinResponsable($query)
	{
		$query->whereNull('canal.idusuario_responsable');
		$query->select('canal.*');
		return $query;	
	}
	
}
