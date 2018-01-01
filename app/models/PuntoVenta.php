<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class PuntoVenta extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'punto_venta';
	protected $softDelete = true;
	protected $primaryKey = 'idpunto_venta';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	public function scopeBuscarPuntosVentaPorEntidad($query,$identidad)
	{
		$query->where('punto_venta.identidad','=',$identidad);
		$query->select('punto_venta.*');
		return $query;
	}

	public function scopeBuscarPorNombre($query,$nombre)
	{
		$query->where('punto_venta.nombre','LIKE',"$nombre");
		$query->select('punto_venta.*');
		return $query;
	}

}
