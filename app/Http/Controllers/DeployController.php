<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Playlist;
use App\Models\Upload;

class DeployController extends Controller {
    public function __invoke($id) {
        date_default_timezone_set("Atlantic/Canary");

        $playlist = Playlist::where('id', $id)->first();
        $user = User::where('id', $playlist->userId)->first();
        $videos = Upload::where('playlistId', $id)->where('active', 1)->orderBy('position','ASC')->get();
        $musicJSON = !!$playlist->musicURL ? @json_decode(file_get_contents($playlist->musicURL)) : null;
        $resp = Array();

    // ================== < DEVICE INFO ==================
        
        $resp['info']['id'] = $id;
        $resp['info']['storage']['media'] = env('APP_URL').'storage/media/';
        $resp['info']['storage']['music'] = $musicJSON;
        $resp['info']['device']['name'] = $playlist->name;
        $resp['info']['device']['type'] = '#';
        $resp['info']['device']['shop'] = $user->name;
        $resp['info']['version'] = time();
        
    // ================== DEVICE INFO > ==================
        

    // ================== < MEDIA ==================

        $contenidos = array();

        foreach($videos as $vid){
            $now = date("Y-m-d H:i:s",time());

            $contenidos[] = array(
                'duration' => $vid->duration,
                'volume' => $vid->volume,
                'id' => $vid->id,
				'nombre' => $vid->title, 
                'transition' => $vid->transition,
                'archivo' => $vid->filename,
                'lista' => $playlist->name,
                'desde' => $vid->dateFrom,
                'hasta' => $vid->dateTo,
                'horaDesde' => $vid->timeFrom,
                'horaHasta' => $vid->timeTo
            );
        }

        $list = Array();
        $catalog = Array();

        foreach ($contenidos as $contenido) {
            $cont = Array();
            $cont['file']           = $contenido['archivo'];
			$cont['playlist']       = $contenido['lista'];
            $cont['name']           = $contenido['nombre'];
            $cont['dateFrom']       = $contenido['desde'];
            $cont['dateTo']         = $contenido['hasta'];
            $cont['timeFrom']       = $contenido['horaDesde'];
            $cont['timeTo']         = $contenido['horaHasta'];
            $cont['duration']       = $contenido['duration'];
            $cont['volume']         = $contenido['volume'];  
			$cont['transition']     = $contenido['transition'];  

            if (empty($cont['dateFrom']))   { unset($cont['dateFrom']); }
            if (empty($cont['dateTo']))     { unset($cont['dateTo']); }
            if (empty($cont['timeFrom']))   { unset($cont['timeFrom']); }   else { $cont['timeFrom'] = substr($cont['timeFrom'], 0, -3); }
            if (empty($cont['timeTo']))     { unset($cont['timeTo']); }     else { $cont['timeTo'] = substr($cont['timeTo'], 0, -3); }

            array_push($list, $contenido['id']);
            $catalog[$contenido['id']] = $cont;
        }

        $resp['media'] = $list;
        $resp['catalog']['media'] = $catalog;

    // ================== MEDIA > ==================
        

    // ================== < MUSIC ==================

        $resp['music'] = $musicJSON->music??[];
        $resp['catalog']['music'] = $musicJSON->catalog->music??[];

    // ================== MUSIC > ==================
    
    // ================== < EVENTS ==================

        $resp['events'] = [];

    // ================== EVENTS > ==================
    
    // ================== < POWER ==================

        $power = json_decode( $user->power );

        $resp['power']['mode'] = 'mem';
		$resp['power']['exclude'] = [0]; // Excluir dias - TEMPORAL

		// ------- Encendido ------
		$resp['power']['on'][0] = $power[0]->ON??null; // Lunes
		$resp['power']['on'][1] = $power[1]->ON??null; // Martes
		$resp['power']['on'][2] = $power[2]->ON??null; // Miercoles
		$resp['power']['on'][3] = $power[3]->ON??null; // Jueves
		$resp['power']['on'][4] = $power[4]->ON??null; // Viernes
		$resp['power']['on'][5] = $power[5]->ON??null; // Sabado
		$resp['power']['on'][6] = $power[6]->ON??null; // Domingo
		
		
		// ------- Apagado ------
		$resp['power']['off'][0] = $power[0]->OFF??null; // Lunes
		$resp['power']['off'][1] = $power[1]->OFF??null; // Martes
		$resp['power']['off'][2] = $power[2]->OFF??null; // Miercoles
		$resp['power']['off'][3] = $power[3]->OFF??null; // Jueves
		$resp['power']['off'][4] = $power[4]->OFF??null; // Viernes
		$resp['power']['off'][5] = $power[5]->OFF??null; // Sabado
		$resp['power']['off'][6] = $power[6]->OFF??null; // Domingo

    // ================== POWER > ==================
    
    
    // ================== < GUARDIAS ==================

        if ($playlist->zonaGuardias != 0) {
            $guardiasJson = @json_decode(file_get_contents('https://api.farmavisioncanarias.com/guardias/'.$playlist->zonaGuardias));
            $resp['guardias'] = $guardiasJson->guardias;
        }

    // ================== GUARDIAS > ==================



        return response()->json($resp);
    }
}
