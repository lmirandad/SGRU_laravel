<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class UsuarioVena extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'usuario_vena';
	protected $softDelete = true;
	protected $primaryKey = 'idusuario_vena';
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function scopeBuscarUsuarioCargadoHoy($query,$fecha_actual)
	{
		$query->where('usuario_vena.fecha_registro','>=',date('Y-m-d H:i:s',strtotime('+0 hours +0 minutes +0 seconds',strtotime($fecha_actual))))
			->where('usuario_vena.fecha_registro','<=',date('Y-m-d H:i:s',strtotime('+23 hours +59 minutes +59 seconds',strtotime($fecha_actual))));
		$query->select('usuario_vena.*');
		return $query;
	}

	public function scopeBuscarUsuarioPorDocumento($query,$documento)
	{
		$query->where('usuario_vena.numero_documento','=',"$documento");
		$query->select('usuario_vena.*');
		return $query;	
	}

	

}
