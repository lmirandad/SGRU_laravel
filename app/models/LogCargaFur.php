<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class LogCargaFur extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'log_carga_fur';
	protected $softDelete = true;
	protected $primaryKey = 'idlog_carga_fur';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function scopeBuscarLogCargaPorIdRequerimiento($query,$idrequerimiento)
	{
		$query->where('log_carga_fur.idrequerimiento','=',$idrequerimiento);
		$query->select('log_carga_fur.*');
		return $query;
	}
}
