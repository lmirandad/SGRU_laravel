<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class HerramientaXUser extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'herramientaxusers';
	protected $softDelete = true;
	protected $primaryKey = 'idherramientaxusers';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public function scopeBuscarHerramientasPorIdUsuario($query,$search_criteria)
	{
		$query->join('herramienta','herramienta.idherramienta','=','herramientaxusers.idherramienta')		
			  ->join('users','users.id','=','herramientaxusers.iduser')		
			  ->join('tipo_herramienta','tipo_herramienta.idtipo_herramienta','=','herramienta.idtipo_herramienta')
			  ->where('herramientaxusers.iduser','=',$search_criteria);

		$query->select('tipo_herramienta.nombre as nombre_tipo_herramienta','herramienta.*','herramientaxusers.idherramientaxusers');
		
		return $query;
	}

	

	
}
