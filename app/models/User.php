<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';
	protected $softDelete = true;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function scopeListarUsuarios($query)
	{
		$query->withTrashed()
			  ->join('rol','rol.idrol','=','users.idrol')
			  ->select('rol.nombre as nombre_rol','users.*');
		return $query;
	}

	public function scopeBuscarUsuarioPorUsername($query,$search_username)
	{
		$query->withTrashed()	
			  ->where('users.username','=',$search_username)		  
			  ->select('users.*');
		return $query;
	}

	public function scopeBuscarUsuarioPorId($query,$search_criteria)
	{
		$query->withTrashed()
			  ->where('users.id','=',$search_criteria);
		
		return $query;
	}

	public function scopeBuscarUsuariosVistaMantenimiento($query,$search_criteria,$search_tipo_documento,$search_documento_identidad,$search_herramienta)
	{
		$query->withTrashed()
			  ->join('rol','rol.idrol','=','users.idrol')			  
			  ->whereNested(function($query) use($search_criteria,$search_documento_identidad,$search_herramienta){
			  		$query->where('users.username','LIKE',"%$search_criteria%")
			  			  ->orWhere('users.nombre','LIKE',"%$search_criteria%")
			  			  ->orWhere('users.apellido_paterno','LIKE',"%$search_criteria%")
			  			  ->orWhere('users.apellido_materno','LIKE',"%$search_criteria%");
			  });

			  if($search_tipo_documento!=0){
			  	$query->where('users.idtipo_doc_identidad','=',$search_tipo_documento);
			  	$query->where('users.numero_doc_identidad','=',$search_documento_identidad);
			  }

			   if($search_herramienta!=0){
			   	$query->join('herramientaxusers','herramientaxusers.iduser','=','users.id');
			  	$query->where('herramientaxusers.idherramienta','=',$search_herramienta);
			  }
			  $query->select('rol.nombre as nombre_rol','users.*');
		return $query;
	}

}
