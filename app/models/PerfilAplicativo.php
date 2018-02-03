<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class PerfilAplicativo extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'perfil_aplicativo';
	protected $softDelete = true;
	protected $primaryKey = 'idperfil_aplicativo';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	//Query para listar todos los perfiles por herramienta
	public function scopeBuscarPerfilesPorHerramienta($query,$idherramienta)
	{
		$query->where('perfil_aplicativo.idherramienta','=',$idherramienta);
		$query->select('perfil_aplicativo.*');
		return $query;
	}
	

}
