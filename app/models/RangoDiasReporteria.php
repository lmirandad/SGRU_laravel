<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class RangoDiasReporteria extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'rango_dias_reporteria';
	protected $softDelete = true;
	protected $primaryKey = 'idrango_dias_reporteria';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	//Query para listar rangos de dias para la reporteria (grupo de dias) --> NO UTILIZADO
	public function scopeListarRangos($query)
	{
		$query->select('rango_dias_reporteria.*');
		
		return $query;
	}
	
}
