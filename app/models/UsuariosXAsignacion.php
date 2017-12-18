<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class UsuariosXAsignacion extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'usuariosxasignacion';
	protected $softDelete = true;
	protected $primaryKey = 'idusuariosxasignacion';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function scopeBuscarUsuarioActual($query,$idasignacion)
	{
		$query->where('usuariosxasignacion.idasignacion','=',$idasignacion)
			  ->where('usuariosxasignacion.estado_usuario_asignado','=',1);
		$query->select('usuariosxasignacion.*');
		return $query;

	}

	public function scopeBuscarPorIdAsignacion($query,$idasignacion)
	{
		$query->where('usuariosxasignacion.idasignacion','=',$idasignacion);
		$query->select('usuariosxasignacion.*');
		return $query;

	}
	

}
