<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Dia extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'dia';
	protected $softDelete = true;
	protected $primaryKey = 'iddia';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	//Query para listar los dias de la semana (utilizado para el dashboard - NO POWER BI)
	public function scopeListarDias($query)
	{
		$query->select('dia.*');
		
		return $query;
	}
	
}
