<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class TipoSolicitudXSla extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tipo_solicitudxsla';
	protected $softDelete = true;
	protected $primaryKey = 'idtipo_solicitudxsla';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	public function scopeListarAccionesPorSla($query,$idsla){
		$query->join('herramientaxsectorxtipo_solicitud','tipo_solicitudxsla.idherramientaxsectorxtipo_solicitud','=','herramientaxsectorxtipo_solicitud.idherramientaxsectorxtipo_solicitud')
			 ->join('tipo_solicitud','herramientaxsectorxtipo_solicitud.idtipo_solicitud','=','tipo_solicitud.idtipo_solicitud');
		$query->where('tipo_solicitudxsla.idsla','=',$idsla);

		$query->select('tipo_solicitudxsla.*','tipo_solicitud.nombre as nombre_accion','tipo_solicitud.idtipo_solicitud as idtipo_solicitud');
		return $query;
	}

	public function scopeBuscarPorSlaPorHerramientaXSectorXTipoSolicitud($query,$idherramientaxsectorxtipo_solicitud,$idsla){
		$query->where('tipo_solicitudxsla.idherramientaxsectorxtipo_solicitud','=',$idherramientaxsectorxtipo_solicitud);
		$query->where('tipo_solicitudxsla.idsla','=',$idsla);
		$query->select('tipo_solicitudxsla.*');
		return $query;
	}
	

	
}
