<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class TipoSolicitudGeneral extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tipo_solicitud_general';
	protected $softDelete = true;
	protected $primaryKey = 'idtipo_solicitud_general';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	public function scopeListarTiposSolicitudGeneral($query)
	{
		$query->select('tipo_solicitud_general.*');

		return $query;
	}

	public function scopeBuscarPorNombre($query,$nombre_tipo)
	{
		$query->where('tipo_solicitud_general.nombre','LIKE',$nombre_tipo);
		$query->select('tipo_solicitud_general.*');

		return $query;
	}

}
