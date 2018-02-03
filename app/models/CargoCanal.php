<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class CargoCanal extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'cargo_canal';
	protected $softDelete = true;
	protected $primaryKey = 'idcargo_canal';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	//Query para listar los cargos aceptados por un determinado canal
	public function scopeBuscarCargosPorCanal($query,$idcanal)
	{
		$query->where('cargo_canal.idcanal','=',$idcanal);
		$query->select('cargo_canal.*');
		return $query;
	}


}
