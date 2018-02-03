<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Meses extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'meses';
	protected $softDelete = true;
	protected $primaryKey = 'idmes';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	//Query para listar todos los meses
	public function scopeListarMeses($query)
	{
		$query->select('meses.*');
		
		return $query;
	}
	
}
