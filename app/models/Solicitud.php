<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Solicitud extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'solicitud';
	protected $softDelete = true;
	protected $primaryKey = 'idsolicitud';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function scopeBuscarSolicitudesPendientesProcesando($query,$identidad)
	{
		$query->where('solicitud.identidad','=',$identidad)
		      ->whereNested(function($query){
		      	$query->where('solicitud.idestado_solicitud','=',3)
		      		  ->orWhere('solicitud.idestado_solicitud','=',4);
		      });

		$query->select('solicitud.*');

		return $query;
	}

	public function scopeBuscarSolicitudesPendientesProcesandoPorIdUsuario($query,$idusuario)
	{
		$query->join('asignacion','solicitud.idasignacion','=','asignacion.idasignacion');
		$query->where('asignacion.iduser_asignado','=',$idusuario)
		      ->whereNested(function($query){
		      	$query->where('solicitud.idestado_solicitud','=',3)
		      		  ->orWhere('solicitud.idestado_solicitud','=',4);
		      });

		$query->select('solicitud.*','asignacion.*');

		

		return $query;	
	}

	public function scopeBuscarSolicitudesPendientesProcesandoPorIdSla($query,$idsla)
	{
		$query->join('asignacion','solicitud.idasignacion','=','asignacion.idasignacion');
		$query->where('solicitud.idsla','=',$idsla)
		      ->whereNested(function($query){
		      	$query->where('solicitud.idestado_solicitud','=',3)
		      		  ->orWhere('solicitud.idestado_solicitud','=',4);
		      });

		$query->select('solicitud.*','asignacion.*');

		

		return $query;	
	}
	

}
