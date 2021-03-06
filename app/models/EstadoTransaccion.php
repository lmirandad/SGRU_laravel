<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class EstadoTransaccion extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'estado_transaccion';
	protected $softDelete = true;
	protected $primaryKey = 'idestado_transaccion';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	

}
