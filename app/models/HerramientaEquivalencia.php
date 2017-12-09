<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class HerramientaEquivalencia extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'herramienta_equivalencia';
	protected $softDelete = true;
	protected $primaryKey = 'idherramienta_equivalencia';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function scopeBuscarEquivalenciasPorIdHerramienta($query,$idherramienta)
	{
		$query->where('herramienta_equivalencia.idherramienta','=',$idherramienta);
		$query->select('herramienta_equivalencia.*');
		return $query;
	}
	

}
