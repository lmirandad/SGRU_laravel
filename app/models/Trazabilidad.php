<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Trazabilidad extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'trazabilidad_transaccion';
	protected $softDelete = true;
	protected $primaryKey = 'idtrazabilidad_transaccion';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	//Query para listar los dias de la semana (utilizado para el dashboard - NO POWER BI)
	public function scopeListarTrazabilidadPorTransaccion($query,$idtransaccion)
	{
		$query->where('trazabilidad_transaccion.idtransaccion','=',$idtransaccion);
		$query->select('trazabilidad_transaccion.*');
		
		return $query;
	}
	
}
