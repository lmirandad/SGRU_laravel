<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class CargaArchivoPlanilla extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'carga_archivo_planilla';
	protected $softDelete = true;
	protected $primaryKey = 'idcarga_archivo_planilla';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	public function scopeListarCargasArchivoPlanillaMes($query,$mes,$anho)
	{
		$query->join('users','carga_archivo_planilla.iduser_registrador','=','users.id');
		$query->whereMonth('carga_archivo_planilla.fecha_carga_archivo','=',$mes)
			  ->whereYear('carga_archivo_planilla.fecha_carga_archivo','=',$anho);

		$query->select('carga_archivo_planilla.*','users.*');
		return $query;
	}


}
