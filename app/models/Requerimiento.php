<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Requerimiento extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'requerimiento';
	protected $softDelete = true;
	protected $primaryKey = 'idrequerimiento';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function scopeBuscarRequerimientosPorSolicitud($query,$idsolicitud)
	{
		$query->leftJoin('herramienta','herramienta.idherramienta','=','requerimiento.idherramienta')
			  ->leftJoin('denominacion_herramienta','denominacion_herramienta.iddenominacion_herramienta','=','herramienta.iddenominacion_herramienta')
			  ->leftJoin('tipo_requerimiento','tipo_requerimiento.idtipo_requerimiento','=','herramienta.idtipo_requerimiento')
			  ->leftJoin('punto_venta','punto_venta.idpunto_venta','=','requerimiento.idpunto_venta')
			  ->leftJoin('entidad','entidad.identidad','=','punto_venta.identidad')
			  ->leftJoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->leftJoin('estado_requerimiento','estado_requerimiento.idestado_requerimiento','=','requerimiento.idestado_requerimiento');

		$query->where('requerimiento.idsolicitud','=',$idsolicitud);

		$query->select('requerimiento.*','estado_requerimiento.nombre as nombre_estado_requerimiento','herramienta.nombre as nombre_herramienta','denominacion_herramienta.nombre as nombre_denominacion','tipo_requerimiento.nombre as nombre_tipo_requerimiento','punto_venta.nombre as nombre_punto_venta','entidad.nombre as nombre_entidad','canal.nombre as nombre_canal');
		
		return $query;
	}

	public function scopebuscarRequerimientosPorCodigo($query,$codigo,$idrequerimiento,$idsolicitud)
	{
		$query->where('requerimiento.codigo_requerimiento','=',"$codigo");
		$query->where('requerimiento.idrequerimiento','!=',$idrequerimiento);
		$query->where('requerimiento.idsolicitud','=',$idsolicitud);
		$query->select('requerimiento.*');
		return $query;
	}

	public function scopebuscarRequerimientosPorCodigoRequerimiento($query,$codigo,$idsolicitud)
	{
		$query->where('requerimiento.codigo_requerimiento','=',"$codigo");
		$query->where('requerimiento.idsolicitud','=',$idsolicitud);
		$query->select('requerimiento.*');
		return $query;
	}

	public function scopeBuscarRequerimientosNoRechazados($query,$idsolicitud)
	{
		$query->where('requerimiento.idsolicitud','=',$idsolicitud);
		$query->where('requerimiento.idestado_solicitud','!=',2);
		$query->select('requerimiento.*');
		return $query;
	}

	public function scopeBuscarRequerimientosPorEstadoPorIdSolicitud($query,$idsolicitud,$idestado)
	{
		$query->where('requerimiento.idsolicitud','=',$idsolicitud);
		$query->where('requerimiento.idestado_requerimiento','=',$idestado);
		$query->select('requerimiento.*');
		return $query;
	}

	public function scopeMostrarRequerimientoEstadoAnual($query,$estado,$anho)
	{
		return DB::select('Select sum(case when YEAR(requerimiento.fecha_registro) = '.$anho.' and requerimiento.idestado_requerimiento = '.$estado.' then 1 else 0 end) as cantidad ,meses.idmes from requerimiento 
			right join meses on (MONTH(requerimiento.fecha_registro) = meses.idmes)
			group by meses.idmes');
	}

	public function scopeMostrarRequerimientoEstadoUsuarioAnual($query,$estado,$anho,$usuario)
	{
		return DB::select('Select sum(case when YEAR(requerimiento.fecha_registro) = '.$anho.' and requerimiento.idestado_requerimiento = '.$estado.' 
							and usuariosxasignacion.idusuario_asignado = '.$usuario.' and usuariosxasignacion.estado_usuario_asignado = 1  then 1 else 0 end) as cantidad ,meses.idmes from requerimiento 
							right join meses on (MONTH(requerimiento.fecha_registro) = meses.idmes)
							left join solicitud on (solicitud.idsolicitud = requerimiento.idsolicitud)
							left join asignacion on (solicitud.idsolicitud = asignacion.idsolicitud)
							left join usuariosxasignacion on (asignacion.idasignacion = usuariosxasignacion.idusuariosxasignacion)
							group by meses.idmes');
	}


	public function scopeMostrarRequerimientoEstadoMes($query,$estado,$mes,$anho)
	{
		return DB::select('Select sum(case when YEAR(requerimiento.fecha_registro) = '.$anho.' and MONTH(requerimiento.fecha_registro) = '.$mes.' and requerimiento.idestado_requerimiento = '.$estado.' then 1 else 0 end) as cantidad from requerimiento');
	}

	public function scopeMostrarRequerimientoEstadoUsuarioMes($query,$estado,$mes,$anho,$usuario)
	{
		return DB::select('Select sum(case when YEAR(requerimiento.fecha_registro) = '.$anho.' and MONTH(requerimiento.fecha_registro) = '.$mes.'  and requerimiento.idestado_requerimiento = '.$estado.' 
							and usuariosxasignacion.idusuario_asignado = '.$usuario.' and usuariosxasignacion.estado_usuario_asignado = 1  then 1 else 0 end) as cantidad  from requerimiento 
							left join solicitud on (solicitud.idsolicitud = requerimiento.idsolicitud)
							left join asignacion on (solicitud.idsolicitud = asignacion.idsolicitud)
							left join usuariosxasignacion on (asignacion.idasignacion = usuariosxasignacion.idusuariosxasignacion)');
	}

	public function scopeBuscarRequerimientosPorFechas($query,$fecha_desde,$fecha_hasta)
	{
		$query->leftJoin('herramienta','herramienta.idherramienta','=','requerimiento.idherramienta')
			  ->leftJoin('denominacion_herramienta','denominacion_herramienta.iddenominacion_herramienta','=','herramienta.iddenominacion_herramienta')
			  ->leftJoin('tipo_requerimiento','tipo_requerimiento.idtipo_requerimiento','=','herramienta.idtipo_requerimiento')
			  ->leftJoin('punto_venta','punto_venta.idpunto_venta','=','requerimiento.idpunto_venta')
			  ->leftJoin('entidad','entidad.identidad','=','punto_venta.identidad')
			  ->leftJoin('canal','canal.idcanal','=','entidad.idcanal')
			  ->leftJoin('estado_requerimiento','estado_requerimiento.idestado_requerimiento','=','requerimiento.idestado_requerimiento')
			  ->leftJoin('solicitud','solicitud.idsolicitud','=','requerimiento.idsolicitud');
		
		if($fecha_desde != "")
			$query->where('requerimiento.fecha_registro','>=',date('Y-m-d H:i:s',strtotime($fecha_desde)));
		
		if($fecha_hasta != "")
			$query->where('requerimiento.fecha_registro','<=',date('Y-m-d H:i:s',strtotime('+23 hours +59 minutes +59 seconds',strtotime($fecha_hasta))));

		$query->select('requerimiento.*','estado_requerimiento.nombre as nombre_estado_requerimiento','herramienta.nombre as nombre_herramienta','denominacion_herramienta.nombre as nombre_denominacion','tipo_requerimiento.nombre as nombre_tipo_requerimiento','punto_venta.nombre as nombre_punto_venta','entidad.nombre as nombre_entidad','canal.nombre as nombre_canal','solicitud.*');

		return $query;
	}

}
