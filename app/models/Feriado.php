<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Feriado extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'fecha_feriado';
	protected $softDelete = true;
	protected $primaryKey = 'idfecha_feriado';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function scopeListarFeriados($query)
	{
		$query->select('fecha_feriado.*');
		return $query;
	}
	
	public function scopeBuscarFeriados($query,$anho)
	{
		$query->whereYear('fecha_feriado.valor_fecha','=',$anho);
		$query->select('fecha_feriado.*');
		return $query;
	}

	public function scopeBuscarDiasFeriados($query,$fecha_inicio,$fecha_fin)
	{
		$query->where('fecha_feriado.valor_fecha','<=',date('Y-m-d H:i:s',strtotime($fecha_fin)));
		$query->where('fecha_feriado.valor_fecha','>=',date('Y-m-d H:i:s',strtotime($fecha_inicio)));
		$query->whereDay('fecha_feriado.valor_fecha','!=','00');
		$query->select('fecha_feriado.*');
		return $query;
	}
}
