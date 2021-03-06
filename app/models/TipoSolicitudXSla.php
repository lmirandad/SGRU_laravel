<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class TipoSolicitudXSla extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'tipo_solicitudxsla';
	protected $softDelete = true;
	protected $primaryKey = 'idtipo_solicitudxsla';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	//Query para listar las acciones (Tipos de solicitud) por sla
	public function scopeListarAccionesPorSla($query,$idsla){
		$query->join('herramientaxsectorxtipo_solicitud','tipo_solicitudxsla.idherramientaxsectorxtipo_solicitud','=','herramientaxsectorxtipo_solicitud.idherramientaxsectorxtipo_solicitud')
			 ->join('tipo_solicitud','herramientaxsectorxtipo_solicitud.idtipo_solicitud','=','tipo_solicitud.idtipo_solicitud');
		$query->where('tipo_solicitudxsla.idsla','=',$idsla);

		$query->select('tipo_solicitudxsla.*','tipo_solicitud.nombre as nombre_accion','tipo_solicitud.idtipo_solicitud as idtipo_solicitud');
		return $query;
	}

	//Query para buscar sla por herramienta x sector x tipo solicitud (Objeto Tipo_solicitudxSla)
	public function scopeBuscarPorSlaPorHerramientaXSectorXTipoSolicitud($query,$idherramientaxsectorxtipo_solicitud,$idsla){
		$query->where('tipo_solicitudxsla.idherramientaxsectorxtipo_solicitud','=',$idherramientaxsectorxtipo_solicitud);
		$query->where('tipo_solicitudxsla.idsla','=',$idsla);
		$query->select('tipo_solicitudxsla.*');
		return $query;
	}
	
	//Query para buscar slas por sector herramienta y accion
	public function scopeBuscarSlaPorSectorHerramientaAccion($query,$idsector,$idherramienta,$idaccion)
	{
		$query->join('sla','sla.idsla','=','tipo_solicitudxsla.idsla')
			  ->join('herramientaxsectorxtipo_solicitud','herramientaxsectorxtipo_solicitud.idherramientaxsectorxtipo_solicitud','=','tipo_solicitudxsla.idherramientaxsectorxtipo_solicitud')
			  ->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','herramientaxsectorxtipo_solicitud.idtipo_solicitud')
			  ->join('herramientaxsector','herramientaxsector.idherramientaxsector','=','herramientaxsectorxtipo_solicitud.idherramientaxsector')
			  ->join('herramienta','herramienta.idherramienta','=','herramientaxsector.idherramienta')
			  ->join('sector','sector.idsector','=','herramientaxsector.idsector');

		$query->where('sector.idsector','=',$idsector);
		$query->where('herramienta.idherramienta','=',$idherramienta);
		$query->where('tipo_solicitud.idtipo_solicitud','=',$idaccion);

		$query->select('sla.*');
	}

	
}
