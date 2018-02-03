<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class HerramientaXTipoSolicitudXUser extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'herramientaxtipo_solicitudxuser';
	protected $softDelete = true;
	protected $primaryKey = 'idherramientaxtipo_solicitudxuser';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
	//Query para listar todos los tipos de solicitudes (acciones) por determinado usuario y herramienta especializada
	public function scopeListarTipoSolicitudUsuario($query,$idusuario,$idherramienta)
	{
		$query->withTrashed()
			  ->join('herramientaxtipo_solicitud','herramientaxtipo_solicitudxuser.idherramientaxtipo_solicitud','=','herramientaxtipo_solicitud.idherramientaxtipo_solicitud')
			  ->join('tipo_solicitud','herramientaxtipo_solicitud.idtipo_solicitud','=','tipo_solicitud.idtipo_solicitud');

		$query->where('herramientaxtipo_solicitudxuser.iduser','=',$idusuario);
		$query->where('herramientaxtipo_solicitud.idherramienta','=',$idherramienta);

		$query->select('herramientaxtipo_solicitudxuser.*','herramientaxtipo_solicitudxuser.deleted_at as eliminado','tipo_solicitud.nombre as nombre_solicitud');



		return $query;
	}

	
}
