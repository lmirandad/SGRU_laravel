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

	public function scopeListarSectoresDisponibles($query,$search_criteria)
	{
		$query->whereNotIn('sector.idsector',function($subquery) use ($search_criteria){
					$subquery->leftJoin('usersxsector','sector.idsector','=','usersxsector.idsector');
					$subquery->from(with(new Sector)->getTable());
					$subquery->where('usersxsector.iduser','=',$search_criteria);
					$subquery->where('usersxsector.deleted_at','=',NULL);
					$subquery->select('sector.idsector')->distinct();
		});

		$query->select('sector.*');

		return $query;
	}

	public function scopeBuscarSectores($query,$search_criteria)
	{
		$query->where('sector.nombre','LIKE',"%$search_criteria%");
		$query->select('sector.*');

		return $query;
	}

	



	
}
