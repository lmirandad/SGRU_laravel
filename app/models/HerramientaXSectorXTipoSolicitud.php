<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class HerramientaXSectorXTipoSolicitud extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'herramientaxsectorxtipo_solicitud';
	protected $softDelete = true;
	protected $primaryKey = 'idherramientaxsectorxtipo_solicitud';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	//Query para listar los tipos de solicitudes (acciones) por determiando sector y determinada herramienta
	public function scopeListarTipoSolicitudSector($query,$idsector,$idherramienta)
	{
		$query->withTrashed()
			  ->join('herramientaxsector','herramientaxsectorxtipo_solicitud.idherramientaxsector','=','herramientaxsector.idherramientaxsector')
			  ->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','herramientaxsectorxtipo_solicitud.idtipo_solicitud');
			  

		$query->where('herramientaxsector.idsector','=',$idsector);
		$query->where('herramientaxsector.idherramienta','=',$idherramienta);

		$query->select('herramientaxsectorxtipo_solicitud.*','herramientaxsectorxtipo_solicitud.deleted_at as eliminado','tipo_solicitud.nombre as nombre_solicitud');



		return $query;
	}

	//Query para buscar objetos herramientaxsectorxtipo_solicitud por id herramientaxsector
	public function scopeBuscarPorId($query,$idherramientaxsector)
	{
		$query->withTrashed()
			  ->where('herramientaxsectorxtipo_solicitud.idherramientaxsector','=',$idherramientaxsector);

		$query->select('herramientaxsectorxtipo_solicitud.*');

		return $query;
	}

	
}
