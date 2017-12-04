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
			  ->join('denominacion_herramienta','denominacion_herramienta.iddenominacion_herramienta','=','herramienta.iddenominacion_herramienta')
			  ->where('herramientaxusers.iduser','=',$search_criteria);

		$query->select('denominacion_herramienta.nombre as nombre_denominacion','herramienta.*','herramientaxusers.idherramientaxusers');
		
		return $query;
	}

	public function scopeBuscarHerramientasPorIdUsuarioIdHerramienta($query,$search_idusuario,$search_idherramienta)
	{
		$query->withTrashed()
			  ->join('herramienta','herramienta.idherramienta','=','herramientaxusers.idherramienta')		
			  ->join('users','users.id','=','herramientaxusers.iduser')		
			  ->join('denominacion_herramienta','denominacion_herramienta.iddenominacion_herramienta','=','herramienta.iddenominacion_herramienta')
			  ->where('herramientaxusers.iduser','=',$search_idusuario)
			  ->where('herramientaxusers.idherramienta','=',$search_idherramienta);

		$query->select('herramientaxusers.*');
		
		return $query;
	}

	public function scopeBuscarUsuariosPorIdHerramienta($query,$idherramienta)
	{
		$query->join('users','users.id','=','herramientaxusers.iduser');

		$query->where('herramientaxusers.idherramienta','=',$idherramienta);
		$query->select('users.*');
		return $query;
	}

	
}
