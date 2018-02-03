<?php

class SectorCanalEntidadController extends BaseController {

	public function listar_sectores_canales_entidades($flag_seleccion=null)
	{
		if(Auth::check()){
			$data["inside_url"] = Config::get('app.inside_url');
			$data["user"] = Session::get('user');
			// Verifico si el usuario es un WEBMASTER (ADMINISTRADOR DEL SISTEMA)
			if($data["user"]->idrol == 1){
				
				if($flag_seleccion == 0) //INICIO
				{
					$data["flag_seleccion"] = 0;
					return View::make('Mantenimientos/Sectores_Canales_Entidades/listarSectoresCanalesEntidades',$data);
				}
				else if($flag_seleccion == 1) //SECTOR
				{
					$data["sector_search"] = null;					
					$data["flag_seleccion"] = 1;
					$data["sectores_data"] = Sector::withTrashed()->listarSectores()->paginate(10);

					return View::make('Mantenimientos/Sectores_Canales_Entidades/listarSectoresCanalesEntidades',$data);
				}
				else if($flag_seleccion == 2) //CANAL
				{ 
					$data["canal_search"] = null;					
					$data["canal_search_sector"] = null;
					$data["canal_search_canal_agrupado"] = null;
					$data["sectores"] = Sector::lists('nombre','idsector');
					$data["canales_agrupados"] = CanalAgrupado::lists('nombre','idcanal_agrupado');
					$data["canales_data"] = Canal::withTrashed()->listarCanales()->paginate(10);
					$data["flag_seleccion"] = 2;

					return View::make('Mantenimientos/Sectores_Canales_Entidades/listarSectoresCanalesEntidades',$data);
				}
				else if($flag_seleccion == 3) //ENTIDAD
				{
					$data["codigo_entidad_search"] = null;
					$data["entidad_search"] = null;					
					$data["entidad_search_canal"] = null;
					$data["entidad_search_sector"] = null;
					$data["sectores"] = Sector::lists('nombre','idsector');
					$data["canales"] = Canal::lists('nombre','idcanal');
					$data["entidades_data"] = Entidad::withTrashed()->listarEntidades()->paginate(10);
					$data["flag_seleccion"] = 3;

					return View::make('Mantenimientos/Sectores_Canales_Entidades/listarSectoresCanalesEntidades',$data);

				}else
					return View::make('error/error',$data);

			}else{
				return View::make('error/error',$data);
			}

		}else{
			return View::make('error/error',$data);
		}
	}

	

}
