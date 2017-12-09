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
	

	public function scopeBuscarSlasPorIdHerramientaXSector($query,$idherramientaxsector)
	{
		$query->where('sla.idherramientaxsector','=',$idherramientaxsector);
		$query->orderBy('sla.fecha_inicio','DESC');
		$query->select('sla.*');


		return $query;
	}

	public function scopeBuscarSlaVigentePorIdHerramientaXSector($query,$idherramientaxsector)
	{
		$query->where('sla.idherramientaxsector','=',$idherramientaxsector)
			  ->whereNull('sla.fecha_fin');
		$query->orderBy('sla.fecha_inicio','DESC');

		$query->select('sla.*');
		return $query;
	}

	
	
}
