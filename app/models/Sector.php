<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Sector extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'sector';
	protected $softDelete = true;
	protected $primaryKey = 'idsector';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	

	public function scopeListarSectores($query)
	{
		$query->select('sector.*');

		return $query;
	}
	
}
