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
   		$query->join('tipo_herramienta','herramienta.idtipo_herramienta','=','tipo_herramienta.idtipo_herramienta');		

		$query->select('hu.idherramientaxusers as idherramientaxusers','herramienta.*','tipo_herramienta.nombre as nombre_tipo_herramienta');
		
		return $query;
	}
	
}
