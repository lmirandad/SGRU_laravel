<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Asignacion extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'asignacion';
	protected $softDelete = true;
	protected $primaryKey = 'idasignacion';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	//Query para buscar las asignaciones por Id de solicitud.
	public function scopeBuscarPorIdSolicitud($query,$idsolicitud)
	{
		$query->where('asignacion.idsolicitud','=',$idsolicitud);
		$query->select('asignacion.*');

		return $query;
	}

	
	

}
