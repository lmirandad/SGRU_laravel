<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class HerramientaXSector extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'herramientaxsector';
	protected $softDelete = true;
	protected $primaryKey = 'idherramientaxsector';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	//Query buscar herramientas que son utilizadas por determinado sector
	public function scopeBuscarHerramientasPorIdSector($query,$search_criteria)
	{
		$query->join('herramienta','herramienta.idherramienta','=','herramientaxsector.idherramienta')		
			  ->join('sector','sector.idsector','=','herramientaxsector.idsector')		
			  ->join('denominacion_herramienta','denominacion_herramienta.iddenominacion_herramienta','=','herramienta.iddenominacion_herramienta')
			  ->where('herramientaxsector.idsector','=',$search_criteria);

		$query->select('denominacion_herramienta.nombre as nombre_denominacion','herramienta.*','herramientaxsector.idherramientaxsector');
		
		return $query;
	}

	//Query para buscar objetos herramientaxsector por id sector y id herramienta
	public function scopeBuscarHerramientasPorIdSectorIdHerramienta($query,$search_idsector,$search_idherramienta)
	{
		$query->withTrashed()
			  ->join('herramienta','herramienta.idherramienta','=','herramientaxsector.idherramienta')		
			  ->join('sector','sector.idsector','=','herramientaxsector.idsector')		
			  ->join('denominacion_herramienta','denominacion_herramienta.iddenominacion_herramienta','=','herramienta.iddenominacion_herramienta')
			  ->where('herramientaxsector.idsector','=',$search_idsector)
			  ->where('herramientaxsector.idherramienta','=',$search_idherramienta);

		$query->select('herramientaxsector.*');
		
		return $query;
	}

	//Query para buscar sectores por determinado id herramienta
	public function scopeBuscarSectorPorIdHerramienta($query,$idherramienta)
	{
		$query->join('sector','sector.idsector','=','herramientaxsector.idsector');

		$query->where('herramientaxsector.idherramienta','=',$idherramienta);
		$query->select('sector.*');
		return $query;
	}

	

	
}
