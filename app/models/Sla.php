<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Sla extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sla';
	protected $softDelete = true;
	protected $primaryKey = 'idsla';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	//Query para buscar los Slas por el objeto HerramientaxSector
	public function scopeBuscarSlasPorIdHerramientaXSector($query,$idherramientaxsector)
	{
		$query->where('sla.idherramientaxsector','=',$idherramientaxsector);
		$query->orderBy('sla.fecha_inicio','DESC');
		$query->select('sla.*');


		return $query;
	}

	//Query para buscar el sla vigente por el objeto HerramientaxSector
	public function scopeBuscarSlaVigentePorIdHerramientaXSector($query,$idherramientaxsector)
	{
		$query->where('sla.idherramientaxsector','=',$idherramientaxsector)
			  ->whereNull('sla.fecha_fin');
		$query->orderBy('sla.fecha_inicio','DESC');

		$query->select('sla.*');
		return $query;
	}

	//Query para buscar los Slas por idsolicitud y tipo de solicitud (considerar que existe un Sla para una solicitud en estado pendiente y un sla distinto para una solicitud en estado procesando)
	public function scopeBuscarSlaSolicitud($query,$idsolicitud,$idtipo_solicitud)
	{
		$query->join('solicitud','solicitud.idsla','=','sla.idsla')
			  ->join('tipo_solicitudxsla','tipo_solicitudxsla.idsla','=','sla.idsla')
			  ->join('herramientaxsectorxtipo_solicitud','herramientaxsectorxtipo_solicitud.idherramientaxsectorxtipo_solicitud','=','tipo_solicitudxsla.idherramientaxsectorxtipo_solicitud')
			  ->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','herramientaxsectorxtipo_solicitud.idtipo_solicitud');

		$query->where('solicitud.idsolicitud','=',$idsolicitud);	  
		$query->where('tipo_solicitud.idtipo_solicitud','=',$idtipo_solicitud);
		$query->select('sla.*','tipo_solicitudxsla.*');
		return $query;
	}

	
	
}
