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

	public function scopeBuscarUsuariosPorIdSector($query,$idsector)
	{
		$query->join('usersxsector','usersxsector.iduser','=','users.id')
			  ->join('sector','sector.idsector','=','usersxsector.idsector');
		$query->where('sector.idsector','=',$idsector);
		$query->select('users.*');
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

	public function scopeBuscarUsuariosDisponiblesPorHerramienta($query,$idherramienta,$idaccion)
	{
		$query->join('herramientaxtipo_solicitudxuser','herramientaxtipo_solicitudxuser.iduser','=','users.id')
			  ->join('herramientaxtipo_solicitud','herramientaxtipo_solicitud.idherramientaxtipo_solicitud','=','herramientaxtipo_solicitudxuser.idherramientaxtipo_solicitud')
			  ->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','herramientaxtipo_solicitud.idtipo_solicitud')
			  ->join('herramienta','herramienta.idherramienta','=','herramientaxtipo_solicitud.idherramienta')
			  ->join('asignacion','asignacion.iduser_asignado','=','users.id')
			  ->join('solicitud','solicitud.idsolicitud','=','asignacion.idsolicitud');

		$query->where('herramienta.idherramienta','=',$idherramienta)
			  ->where('tipo_solicitud.idtipo_solicitud','=',$idaccion)
			  ->whereNull('herramientaxtipo_solicitudxuser.deleted_at') 
			  ->whereNested(function($query) {
                    $query->where('solicitud.idestado_solicitud','=', 3)
                          ->orWhere('solicitud.idestado_solicitud','=',4);
                });
		$query->groupBy('herramientaxtipo_solicitudxuser.iduser');
		$query->select('herramientaxtipo_solicitudxuser.iduser',DB::raw('count(solicitud.idsolicitud) as cantidad_solicitud'));
		$query->orderBy('cantidad_solicitud','DESC');

		return $query->distinct();


	}

	public function scopeBuscarUsuariosDisponiblesPorSector($query,$idsector,$idherramienta,$idaccion)
	{
		$query->join('usersxsector','usersxsector.iduser','=','users.id')
			  ->join('sector','sector.idsector','=','usersxsector.idsector')
			  ->join('herramientaxtipo_solicitudxuser','herramientaxtipo_solicitudxuser.iduser','=','usersxsector.iduser')
			  ->join('herramientaxtipo_solicitud','herramientaxtipo_solicitud.idherramientaxtipo_solicitud','=','herramientaxtipo_solicitudxuser.idherramientaxtipo_solicitud')
			  ->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','herramientaxtipo_solicitud.idtipo_solicitud')
			  ->join('herramienta','herramienta.idherramienta','=','herramientaxtipo_solicitud.idherramienta')
			  ->join('asignacion','asignacion.iduser_asignado','=','users.id')
			  ->join('solicitud','solicitud.idsolicitud','=','asignacion.idsolicitud');

		$query->where('sector.idsector','=',$idsector)
			  //->where('herramienta.idherramienta','=',$idherramienta)
			  //->where('tipo_solicitud.idtipo_solicitud','=',$idaccion)
			  ->whereNull('usersxsector.deleted_at') 
			  ->whereNested(function($query) {
                    $query->where('solicitud.idestado_solicitud','=', 3)
                          ->orWhere('solicitud.idestado_solicitud','=',4);
                });
		$query->groupBy('users.id');
		$query->select('users.id as iduser',DB::raw('count(solicitud.idsolicitud) as cantidad_solicitud'));
		$query->orderBy('cantidad_solicitud','DESC');

		return $query->distinct();

	}

	public function scopeBuscarUsuariosPorHerramienta($query,$idherramienta,$idaccion)
	{
		$query->join('herramientaxtipo_solicitudxuser','herramientaxtipo_solicitudxuser.iduser','=','users.id')
			  ->join('herramientaxtipo_solicitud','herramientaxtipo_solicitud.idherramientaxtipo_solicitud','=','herramientaxtipo_solicitudxuser.idherramientaxtipo_solicitud')
			  ->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','herramientaxtipo_solicitud.idtipo_solicitud')
			  ->join('herramienta','herramienta.idherramienta','=','herramientaxtipo_solicitud.idherramienta');

		$query->where('herramienta.idherramienta','=',$idherramienta)
			  ->where('tipo_solicitud.idtipo_solicitud','=',$idaccion)
			  ->whereNull('herramientaxtipo_solicitudxuser.deleted_at');

		$query->select('herramientaxtipo_solicitudxuser.iduser');

		return $query->distinct();
		

	}

	public function scopeBuscarUsuariosPorSector($query,$idsector,$idherramienta,$idaccion)
	{
		$query->join('usersxsector','usersxsector.iduser','=','users.id')
			  ->join('sector','sector.idsector','=','usersxsector.idsector')
			  ->join('herramientaxtipo_solicitudxuser','herramientaxtipo_solicitudxuser.iduser','=','usersxsector.iduser')
			  ->join('herramientaxtipo_solicitud','herramientaxtipo_solicitud.idherramientaxtipo_solicitud','=','herramientaxtipo_solicitudxuser.idherramientaxtipo_solicitud')
			  ->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','herramientaxtipo_solicitud.idtipo_solicitud')
			  ->join('herramienta','herramienta.idherramienta','=','herramientaxtipo_solicitud.idherramienta');

		$query->where('sector.idsector','=',$idsector)
			  //->where('herramienta.idherramienta','=',$idherramienta)
			  //->where('tipo_solicitud.idtipo_solicitud','=',$idaccion)
			  ->whereNull('usersxsector.deleted_at') ;

		$query->select('usersxsector.iduser');

		return $query->distinct();

	}
	
	public function scopeBuscarUsuariosLibresPorHerramienta($query,$idherramienta,$idaccion)
	{
		$query->join('herramientaxtipo_solicitudxuser','herramientaxtipo_solicitudxuser.iduser','=','users.id')
			  ->join('herramientaxtipo_solicitud','herramientaxtipo_solicitud.idherramientaxtipo_solicitud','=','herramientaxtipo_solicitudxuser.idherramientaxtipo_solicitud')
			  ->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','herramientaxtipo_solicitud.idtipo_solicitud')
			  ->join('herramienta','herramienta.idherramienta','=','herramientaxtipo_solicitud.idherramienta');

		$query->where('herramienta.idherramienta','=',$idherramienta)
			  ->where('tipo_solicitud.idtipo_solicitud','=',$idaccion)
			  ->whereNull('herramientaxtipo_solicitudxuser.deleted_at');

		$query->whereNotIn('herramientaxtipo_solicitudxuser.iduser',function($subquery){
					$subquery->join('asignacion','asignacion.iduser_asignado','=','users.id');
					$subquery->join('solicitud','asignacion.idsolicitud','=','solicitud.idsolicitud');
					$subquery->from(with(new User)->getTable());
					$subquery->whereNested(function($subsubquery) {
				                    $subsubquery->where('solicitud.idestado_solicitud','=', 3)
				                          ->orWhere('solicitud.idestado_solicitud','=',4);
				                });
					$subquery->select('users.id')->distinct();
		});

		$query->select('herramientaxtipo_solicitudxuser.iduser');

		return $query;
	}

	public function scopeBuscarUsuariosLibresPorSector($query,$idsector,$idherramienta,$idaccion)
	{
		$query->join('usersxsector','usersxsector.iduser','=','users.id')
			  ->join('sector','sector.idsector','=','usersxsector.idsector')
			  ->join('herramientaxtipo_solicitudxuser','herramientaxtipo_solicitudxuser.iduser','=','usersxsector.iduser')
			  ->join('herramientaxtipo_solicitud','herramientaxtipo_solicitud.idherramientaxtipo_solicitud','=','herramientaxtipo_solicitudxuser.idherramientaxtipo_solicitud')
			  ->join('tipo_solicitud','tipo_solicitud.idtipo_solicitud','=','herramientaxtipo_solicitud.idtipo_solicitud')
			  ->join('herramienta','herramienta.idherramienta','=','herramientaxtipo_solicitud.idherramienta');

		$query->where('sector.idsector','=',$idsector)
			  //->where('herramienta.idherramienta','=',$idherramienta)
			  //->where('tipo_solicitud.idtipo_solicitud','=',$idaccion)
			  ->whereNull('usersxsector.deleted_at') ;

		$query->whereNotIn('usersxsector.iduser',function($subquery){
					$subquery->join('asignacion','asignacion.iduser_asignado','=','users.id');
					$subquery->join('solicitud','asignacion.idsolicitud','=','solicitud.idsolicitud');
					$subquery->from(with(new User)->getTable());
					$subquery->whereNested(function($subsubquery) {
				                    $subsubquery->where('solicitud.idestado_solicitud','=', 3)
				                          ->orWhere('solicitud.idestado_solicitud','=',4);
				                });
					$subquery->select('users.id')->distinct();
		});

		$query->select('usersxsector.iduser');

		return $query;
	}

	public function scopeBuscarPorNombre($query,$search_criteria)
	{
		$query->withTrashed()
			  ->whereNested(function($query) use($search_criteria){
			  		$query->where('users.username','LIKE',"%$search_criteria%")
			  			  ->orWhere('users.nombre','LIKE',"%$search_criteria%")
			  			  ->orWhere('users.apellido_paterno','LIKE',"%$search_criteria%")
			  			  ->orWhere('users.apellido_materno','LIKE',"%$search_criteria%");
			  });
	  	$query->select('users.*');
		return $query;
	}

}
