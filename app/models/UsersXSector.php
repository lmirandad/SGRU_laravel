<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class UsersXSector extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'usersxsector';
	protected $softDelete = true;
	protected $primaryKey = 'idusersxsector';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public function scopeBuscarSectoresPorIdUsuario($query,$search_criteria)
	{
		$query->join('sector','sector.idsector','=','usersxsector.idsector')		
			  ->join('users','users.id','=','usersxsector.iduser')		
			  ->where('usersxsector.iduser','=',$search_criteria);

		$query->select('sector.*','usersxsector.idusersxsector');
		
		return $query;
	}

	

	
}
