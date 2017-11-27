<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class HerramientaXTipoSolicitud extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'herramientaxtipo_solicitud';
	protected $softDelete = true;
	protected $primaryKey = 'idherramientaxtipo_solicitud';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	

	public function scopeListarTipoSolicitudHerramienta($query,$search_criteria)
	{
		$query->where('herramientaxtipo_solicitud.idherramienta','=',$search_criteria);

		$query->select('herramientaxtipo_solicitud.*');

		return $query;
	}

	
}
