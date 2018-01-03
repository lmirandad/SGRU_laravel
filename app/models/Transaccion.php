<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Transaccion extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'transaccion';
	protected $softDelete = true;
	protected $primaryKey = 'idtransaccion';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function scopeBuscarTransaccionesPorRequerimiento($query,$idrequerimiento)
	{
		$query->where('transaccion.idrequerimiento','=',$idrequerimiento);
		$query->select('transaccion.*');
		return $query;
	}

	public function scopeBuscarTransaccionesEstadoPorRequerimiento($query,$idrequerimiento,$idestado)
	{
		$query->where('transaccion.idrequerimiento','=',$idrequerimiento);
		$query->where('transaccion.idestado_transaccion','=',$idestado);
		$query->select('transaccion.*');
		return $query;
	}

	public function scopeBuscarTransaccionesEstadoPorSolicitud($query,$idsolicitud,$idestado)
	{
		$query->join('requerimiento','requerimiento.idrequerimiento','=','transaccion.idrequerimiento')
			  ->join('solicitud','solicitud.idsolicitud','=','requerimiento.idsolicitud');
		$query->where('solicitud.idsolicitud','=',$idsolicitud)
			  ->where('transaccion.idestado_transaccion','=',$idestado);
		$query->select('transaccion.*');
		return $query;	 
	}

	public function scopeBuscarTransaccionesPorSolicitud($query,$solicitud)
	{
		$query->join('requerimiento','requerimiento.idrequerimiento','=','transaccion.idrequerimiento')
			  ->join('estado_transaccion','estado_transaccion.idestado_transaccion','=','transaccion.idestado_transaccion')
			  ->join('solicitud','solicitud.idsolicitud','=','requerimiento.idsolicitud')
			  ->leftJoin('herramienta','herramienta.idherramienta','=','requerimiento.idherramienta')
			  ->leftJoin('denominacion_herramienta','denominacion_herramienta.iddenominacion_herramienta','=','herramienta.iddenominacion_herramienta')
			  ->leftJoin('tipo_requerimiento','tipo_requerimiento.idtipo_requerimiento','=','herramienta.idtipo_requerimiento')
			  ->leftJoin('punto_venta','punto_venta.idpunto_venta','=','requerimiento.idpunto_venta')
			  ->leftJoin('entidad','entidad.identidad','=','punto_venta.identidad')
			  ->leftJoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->where('solicitud.idsolicitud','=',$solicitud)
			  ->select('requerimiento.*','transaccion.*','estado_transaccion.nombre as nombre_estado_transaccion','herramienta.nombre as nombre_herramienta','denominacion_herramienta.nombre as nombre_denominacion','tipo_requerimiento.nombre as nombre_tipo_requerimiento','punto_venta.nombre as nombre_punto_venta','entidad.nombre as nombre_entidad','canal.nombre as nombre_canal');
		return $query;
	}

}
