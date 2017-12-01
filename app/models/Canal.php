<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Canal extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'canal';
	protected $softDelete = true;
	protected $primaryKey = 'idcanal';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	

	public function scopeListarCanales($query)
	{
		$query->join('sector','sector.idsector','=','canal.idsector');

		$query->select('canal.*','sector.nombre as nombre_sector');

		return $query;
	}

	public function scopeBuscarCanalesPorIdSector($query,$idsector){
		$query->where('canal.idsector','=',$idsector);
		$query->select('canal.*');

		return $query;	
	}

	public function scopeBuscarCanales($query,$nombre_canal,$idsector){
		
		$query->join('sector','canal.idsector','=','sector.idsector');
		if($nombre_canal != null)
			$query->where('canal.nombre','LIKE',"%$nombre_canal%");
		if(strcmp($idsector,"")!=0)
			$query->where('canal.idsector','=',$idsector);

		$query->select('canal.*','sector.nombre as nombre_sector');

	}
	
}
