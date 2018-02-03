<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Entidad extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'entidad';
	protected $softDelete = true;
	protected $primaryKey = 'identidad';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	//Query para listar todas las entidades registradas en el sistema. Se incluye el canal y el nombre del sector asociado a dicho canal.
	public function scopeListarEntidades($query)
	{
		$query->join('canal','canal.idcanal','=','entidad.idcanal')
			  ->join('sector','sector.idsector','=','canal.idsector');

		$query->select('entidad.*','sector.nombre as nombre_sector','canal.nombre as nombre_canal');
		$query->orderBy(DB::raw('CONVERT(integer,entidad.codigo_enve)'),'ASC');

		return $query;
	}

	//Query para listar todas las entidades de un determinado canal
	public function scopeBuscarEntidadesPorIdCanal($query,$idcanal)
	{
		$query->where('entidad.idcanal','=',$idcanal);
		$query->select('entidad.*');
	}
	
	//Query para buscar las entidades por determinados criterios de busqueda (Codigo Entidad - Nombre - Id Canal - Id Sector)
	public function scopeBuscarEntidades($query,$codigo_enve,$nombre,$idcanal,$idsector){
		$query->join('canal','entidad.idcanal','=','canal.idcanal')
			  ->join('sector','canal.idsector','=','sector.idsector');

		if($codigo_enve != null)
			$query->where('entidad.codigo_enve','=',"$codigo_enve");

		if($nombre != null )
			$query->where('entidad.nombre','LIKE',"%$nombre%");


		if($idcanal != null )
		{
			$query->where('entidad.idcanal','=',$idcanal);
		}
		else{
			if($idsector != null) {
				$query->where('sector.idsector','=',$idsector);
			}
		}

		$query->select('entidad.*','sector.nombre as nombre_sector','canal.nombre as nombre_canal');
		$query->orderBy(DB::raw('CONVERT(integer,entidad.codigo_enve)'),'ASC');

		return $query;

	}

	//Query para buscar una entidad por codigo de entidad (cod_enve)
	public function scopeBuscarPorCodigoEntidad($query,$codigo)
	{
		$query->where('entidad.codigo_enve','LIKE',$codigo);
		$query->select('entidad.*');
		return $query;
	}

	//Query para buscar entidades por nombre
	public function scopeBuscarPorNombre($query,$nombre)
	{
		$query->where('entidad.nombre','LIKE',"$nombre");
		$query->select('entidad.*');
		return $query;
	}
}
