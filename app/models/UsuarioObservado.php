<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class UsuarioObservado extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'usuario_observado';
	protected $softDelete = true;
	protected $primaryKey = 'idusuario_observado';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function scopeBuscarUsuarioCargadoHoy($query,$fecha_actual)
	{
		$query->where('usuario_observado.fecha_registro','=',date('Y-m-d',strtotime($fecha_actual)));
		$query->select('usuario_observado.*');
		return $query;
	}
}
