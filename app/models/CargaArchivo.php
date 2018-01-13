<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class CargaArchivo extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'carga_archivo';
	protected $softDelete = true;
	protected $primaryKey = 'idcarga_archivo';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	public function scopeBuscarUltimoCorte($query,$fecha)
	{
		$query->where(DB::raw('CONVERT(varchar(10),carga_archivo.fecha_carga_archivo,120)'),'=',$fecha);
		$query->select('carga_archivo.*');
		$query->orderBy('carga_archivo.numero_corte','DESC');
		return $query;
	}

}
