<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class EstadoSolicitud extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'estado_solicitud';
	protected $softDelete = true;
	protected $primaryKey = 'idestado_solicitud';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	//Query para buscar estados de solicitud por nombre
	public function scopeBuscarPorNombre($query,$nombre)
	{
		$query->where('estado_solicitud.nombre','LIKE',$nombre);
		$query->select('estado_solicitud.*');
		return $query;
	}

	//Query para listar todos los estados de solicitud
	public function scopeListarEstados($query)
	{
		$query->select('estado_solicitud.*');
		return $query;
	}
	

}
