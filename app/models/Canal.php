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
	
}
