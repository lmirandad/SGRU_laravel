<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class CanalAgrupado extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'canal_agrupado';
	protected $softDelete = true;
	protected $primaryKey = 'idcanal_agrupado';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	//Query para listar todos los canales agrupados de un determinado sector
	public function scopeBuscarCanalAgrupadoPorIdSector($query,$idsector)
	{
		$query->where('canal_agrupado.idsector','=',$idsector);
		$query->select('canal_agrupado.*');
		return $query;
	}
	


}
