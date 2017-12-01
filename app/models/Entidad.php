<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Entidad extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'entidad';
	protected $softDelete = true;
	protected $primaryKey = 'identidad';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	

	public function scopeListarEntidades($query)
	{
		$query->join('canal','canal.idcanal','=','entidad.idcanal')
			  ->join('sector','sector.idsector','=','canal.idsector');

		$query->select('entidad.*','sector.nombre as nombre_sector','canal.nombre as nombre_canal');

		return $query;
	}

	public function scopeBuscarEntidadesPorIdCanal($query,$idcanal)
	{
		$query->where('entidad.idcanal','=',$idcanal);
		$query->select('entidad.*');
	}
	
}
