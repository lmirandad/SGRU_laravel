<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class EquivalenciaTipoSolicitud extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'equivalencia_tipo_solicitud';
	protected $softDelete = true;
	protected $primaryKey = 'idequivalencia_tipo_solicitud';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	//Query para buscar equivalencias de nombres de tipo de solicitud (Accion) por idtipo_solicitud
	public function scopeBuscarEquivalenciasPorIdTipoSolicitud($query,$idtipo_solicitud)
	{
		$query->where('equivalencia_tipo_solicitud.idtipo_solicitud','=',$idtipo_solicitud);
		$query->select('equivalencia_tipo_solicitud.*');
		return $query;
	}

	//Query para buscar equivalencias de nombres de tipo de solicitud (Accion) por nombre
	public function scopeBuscarPorTipoSolicitudPorNombre($query,$nombre,$idtipo_solicitud)
	{
		$query->where('equivalencia_tipo_solicitud.nombre_equivalencia','LIKE','$nombre');
		$query->where('equivalencia_tipo_solicitud.idtipo_solicitud','=',$idtipo_solicitud);
		$query->select('equivalencia_tipo_solicitud.*');
		return $query;
	}

}
