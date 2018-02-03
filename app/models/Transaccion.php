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

	//Query para buscar transacciones por estado y por solicitud asociado
	public function scopeBuscarTransaccionesEstadoPorSolicitud($query,$idsolicitud,$idestado)
	{
		$query->join('solicitud','solicitud.idsolicitud','=','transaccion.idsolicitud');
		$query->where('solicitud.idsolicitud','=',$idsolicitud)
			  ->where('transaccion.idestado_transaccion','=',$idestado);
		$query->select('transaccion.*');
		return $query;	 
	}

	//Query para buscar transacciones pendientes y procesando por solicitud asociada
	public function scopeBuscarTransaccionesPendientesProcesandoPorSolicitud($query,$idsolicitud)
	{
		$query->join('solicitud','solicitud.idsolicitud','=','transaccion.idsolicitud');
		$query->where('solicitud.idsolicitud','=',$idsolicitud)
			  ->whereNested(function($query){
		      	$query->where('transaccion.idestado_transaccion','=',3)
		      		  ->orWhere('transaccion.idestado_transaccion','=',4);
		      });
		$query->select('transaccion.*');
		return $query;	 
	}

	//Query para buscar transacciones por solicitud
	public function scopeBuscarTransaccionesPorSolicitud($query,$solicitud)
	{
		$query->join('estado_transaccion','estado_transaccion.idestado_transaccion','=','transaccion.idestado_transaccion')
			  ->join('solicitud','solicitud.idsolicitud','=','transaccion.idsolicitud')
			  ->leftJoin('herramienta','herramienta.idherramienta','=','transaccion.idherramienta')
			  ->leftJoin('denominacion_herramienta','denominacion_herramienta.iddenominacion_herramienta','=','herramienta.iddenominacion_herramienta')
			  ->leftJoin('tipo_requerimiento','tipo_requerimiento.idtipo_requerimiento','=','herramienta.idtipo_requerimiento')
			  ->leftJoin('punto_venta','punto_venta.idpunto_venta','=','transaccion.idpunto_venta')
			  ->leftJoin('entidad','entidad.identidad','=','punto_venta.identidad')
			  ->leftJoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->where('solicitud.idsolicitud','=',$solicitud)
			  ->select('transaccion.*','estado_transaccion.nombre as nombre_estado_transaccion','herramienta.nombre as nombre_herramienta','denominacion_herramienta.nombre as nombre_denominacion','tipo_requerimiento.nombre as nombre_tipo_requerimiento','punto_venta.nombre as nombre_punto_venta','entidad.nombre as nombre_entidad','canal.nombre as nombre_canal');
		return $query;
	}

	//Query para buscar transacciones por rango de fechas (Reporteria)
	public function scopeBuscarTransaccionesPorFechas($query,$fecha_desde,$fecha_hasta)
	{
		$query->leftJoin('herramienta','herramienta.idherramienta','=','transaccion.idherramienta')
			  ->leftJoin('denominacion_herramienta','denominacion_herramienta.iddenominacion_herramienta','=','herramienta.iddenominacion_herramienta')
			  ->leftJoin('tipo_requerimiento','tipo_requerimiento.idtipo_requerimiento','=','herramienta.idtipo_requerimiento')
			  ->leftJoin('punto_venta','punto_venta.idpunto_venta','=','transaccion.idpunto_venta')
			  ->leftJoin('entidad','entidad.identidad','=','punto_venta.identidad')
			  ->leftJoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->leftJoin('estado_transaccion','estado_transaccion.idestado_transaccion','=','transaccion.idestado_transaccion')
			  ->leftJoin('solicitud','solicitud.idsolicitud','=','transaccion.idsolicitud');
		
		if($fecha_desde != "")
			$query->where('transaccion.fecha_registro','>=',date('Y-m-d H:i:s',strtotime($fecha_desde)));
		
		if($fecha_hasta != "")
			$query->where('transaccion.fecha_registro','<=',date('Y-m-d H:i:s',strtotime('+23 hours +59 minutes +59 seconds',strtotime($fecha_hasta))));

		$query->select('transaccion.*','estado_transaccion.nombre as nombre_estado_transaccion','herramienta.nombre as nombre_herramienta','denominacion_herramienta.nombre as nombre_denominacion','tipo_requerimiento.nombre as nombre_tipo_requerimiento','punto_venta.nombre as nombre_punto_venta','entidad.nombre as nombre_entidad','canal.nombre as nombre_canal','solicitud.*','transaccion.fecha_inicio_procesando as fecha_inicio_procesando_transaccion');

		return $query;
	}

	
}
