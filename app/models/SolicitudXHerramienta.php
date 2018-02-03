<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class SolicitudXHerramienta extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'solicitudxherramienta';
	protected $softDelete = true;
	protected $primaryKey = 'idsolicitudxherramienta';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	//Query para buscar herramientas por solicitud
	public function scopeBuscarHerramientasPorIdSolicitud($query,$idsolicitud)
	{
		$query->join('herramienta','herramienta.idherramienta','=','solicitudxherramienta.idherramienta');
		$query->where('solicitudxherramienta.idsolicitud','=',$idsolicitud);
		$query->select('herramienta.*');
	}

}
