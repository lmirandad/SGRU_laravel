<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Herramienta extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'herramienta';
	protected $softDelete = true;
	protected $primaryKey = 'idherramienta';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public function scopeListarHerramientasDisponibles($query,$search_criteria)
	{
		$query->join('denominacion_herramienta','herramienta.iddenominacion_herramienta','=','denominacion_herramienta.iddenominacion_herramienta');
		$query->join('tipo_requerimiento','tipo_requerimiento.idtipo_requerimiento','=','herramienta.idtipo_requerimiento');
		$query->whereNotIn('herramienta.idherramienta',function($subquery) use ($search_criteria){
					$subquery->leftJoin('herramientaxusers','herramienta.idherramienta','=','herramientaxusers.idherramienta');
					$subquery->from(with(new Herramienta)->getTable());
					$subquery->where('herramientaxusers.iduser','=',$search_criteria);
					$subquery->where('herramientaxusers.deleted_at','=',NULL);
					$subquery->select('herramienta.idherramienta')->distinct();
		});

		$query->select('herramienta.*','denominacion_herramienta.nombre as nombre_denominacion','tipo_requerimiento.nombre as nombre_tipo');

		return $query;
	}

	public function scopeListarHerramientasDisponiblesSector($query,$search_criteria)
	{
		$query->join('denominacion_herramienta','herramienta.iddenominacion_herramienta','=','denominacion_herramienta.iddenominacion_herramienta');
		$query->join('tipo_requerimiento','tipo_requerimiento.idtipo_requerimiento','=','herramienta.idtipo_requerimiento');
		$query->whereNotIn('herramienta.idherramienta',function($subquery) use ($search_criteria){
					$subquery->leftJoin('herramientaxsector','herramienta.idherramienta','=','herramientaxsector.idherramienta');
					$subquery->from(with(new Herramienta)->getTable());
					$subquery->where('herramientaxsector.idsector','=',$search_criteria);
					$subquery->where('herramientaxsector.deleted_at','=',NULL);
					$subquery->select('herramienta.idherramienta')->distinct();
		});

		$query->select('herramienta.*','denominacion_herramienta.nombre as nombre_denominacion','tipo_requerimiento.nombre as nombre_tipo');

		return $query;
	}

	public function scopeListarHerramientas($query)
	{
		$query->join('denominacion_herramienta','herramienta.iddenominacion_herramienta','=','denominacion_herramienta.iddenominacion_herramienta');
		$query->join('tipo_requerimiento','tipo_requerimiento.idtipo_requerimiento','=','herramienta.idtipo_requerimiento');
		$query->select('herramienta.*','denominacion_herramienta.nombre as nombre_denominacion','tipo_requerimiento.nombre as nombre_tipo');

		return $query;
	}

	public function scopeBuscarHerramientas($query,$search_criteria,$search_denominacion_herramienta)
	{
		$query->withTrashed()
			  ->join('denominacion_herramienta','denominacion_herramienta.iddenominacion_herramienta','=','herramienta.iddenominacion_herramienta')
			  ->join('tipo_requerimiento','tipo_requerimiento.idtipo_requerimiento','=','herramienta.idtipo_requerimiento')	  
			  ->whereNested(function($query) use($search_criteria,$search_denominacion_herramienta){
			  		$query->where('herramienta.nombre','LIKE',"%$search_criteria%");
			  });

			  if($search_denominacion_herramienta!=0){
			  	$query->where('herramienta.iddenominacion_herramienta','=',$search_denominacion_herramienta);
			  }

			  $query->select('denominacion_herramienta.nombre as nombre_denominacion','herramienta.*','tipo_requerimiento.nombre as nombre_tipo');
			  
		return $query;
	}

	public function scopeBuscarPorNombre($query,$nombre)
	{
		$query->where('herramienta.nombre','LIKE',$nombre);
		$query->select('herramienta.*');
		return $query;
	}
	

}
