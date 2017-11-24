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
		$query->leftJoin('herramientaxusers as hu', function($q) use($search_criteria) {
   				  $q->on('herramienta.idherramienta', '=', 'hu.idherramienta')
   				    ->where('hu.iduser','=',$search_criteria);});
   		$query->join('denominacion_herramienta','herramienta.iddenominacion_herramienta','=','denominacion_herramienta.iddenominacion_herramienta');		

		$query->select('hu.idherramientaxusers as idherramientaxusers','herramienta.*','denominacion_herramienta.nombre as nombre_denominacion');
		
		return $query;
	}

	public function scopeListarHerramientas($query)
	{
		$query->join('denominacion_herramienta','herramienta.iddenominacion_herramienta','=','denominacion_herramienta.iddenominacion_herramienta');

		$query->select('herramienta.*','denominacion_herramienta.nombre as nombre_denominacion');

		return $query;
	}

	public function scopeBuscarHerramientas($query,$search_criteria,$search_denominacion_herramienta)
	{
		$query->withTrashed()
			  ->join('denominacion_herramienta','denominacion_herramienta.iddenominacion_herramienta','=','herramienta.iddenominacion_herramienta')			  
			  ->whereNested(function($query) use($search_criteria,$search_denominacion_herramienta){
			  		$query->where('herramienta.nombre','LIKE',"%$search_criteria%");
			  });

			  if($search_denominacion_herramienta!=0){
			  	$query->where('herramienta.iddenominacion_herramienta','=',$search_denominacion_herramienta);
			  }

			  $query->select('denominacion_herramienta.nombre as nombre_denominacion','herramienta.*');
			  
		return $query;
	}
	
}
