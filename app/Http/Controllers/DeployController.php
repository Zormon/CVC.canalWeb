<?php
namespace App\Http\Controllers;

use App\User;
use App\Playlist;
use Illuminate\Support\Facades\Storage;

class DeployController extends Controller {

    private $remoteMusicJson = false;
    private $remoteGuardiasJson = false;
    private $id = '';

    private function devInfo() {
        $info['id'] = $this->id;
        $info['storage']['media'] = env('APP_URL').'storage/';
        $info['storage']['music'] = $this->remoteMusicJson? $this->remoteMusicJson->info->storage->music : null;
        $info['device']['name'] = '#';
        $info['device']['type'] = '#';
        $info['device']['shop'] = '#';
        $info['version'] = time();

        return $info;
    }

    private function devPower() {
        $playlist = Playlist::where('id',$this->id)->first();
        $user = User::where('id', $playlist->userId)->first();	 
	 
		$power = json_decode( $user->power );

        $resp['mode'] = 'mem';
		$resp['exclude'] = [0]; // Excluir dias - TEMPORAL

		// ------- Encendido ------
		$resp['on'][0] = $power[0]->ON??null; // Lunes
		$resp['on'][1] = $power[1]->ON??null; // Martes
		$resp['on'][2] = $power[2]->ON??null; // Miercoles
		$resp['on'][3] = $power[3]->ON??null; // Jueves
		$resp['on'][4] = $power[4]->ON??null; // Viernes
		$resp['on'][5] = $power[5]->ON??null; // Sabado
		$resp['on'][6] = $power[6]->ON??null; // Domingo
		
		
		// ------- Apagado ------
		$resp['off'][0] = $power[0]->OFF??null; // Lunes
		$resp['off'][1] = $power[1]->OFF??null; // Martes
		$resp['off'][2] = $power[2]->OFF??null; // Miercoles
		$resp['off'][3] = $power[3]->OFF??null; // Jueves
		$resp['off'][4] = $power[4]->OFF??null; // Viernes
		$resp['off'][5] = $power[5]->OFF??null; // Sabado
		$resp['off'][6] = $power[6]->OFF??null; // Domingo
		
        return $resp;
    }

    private function devMedia() {
        $playlist = Playlist::where('id', $this->id)->first();
        $videos = Upload::where('playlistId', $this->id)->whereRaw('active=1')->orderBy('position','ASC')->get();
        $currentContenidos = array();

        foreach($videos as $vid){
            $now = date("Y-m-d H:i:s",time());

            $currentContenidos[] = array(
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

        foreach ($currentContenidos as $contenido) {
            $cont['file'] = $contenido['archivo'];
			$cont['playlist'] = $contenido['lista'];
            $cont['name'] = $contenido['nombre'];
            $cont['dateFrom'] = $contenido['desde'];
            $cont['dateTo'] = $contenido['hasta'];
            $cont['timeFrom'] = $contenido['horaDesde'];
            $cont['timeTo'] = $contenido['horaHasta'];
            $cont['duration'] = $contenido['duration'];
            $cont['volume'] = $contenido['volume'];  
			$cont['transition'] = $contenido['transition'];  

            if (empty($cont['dateFrom']))   { unset($cont['dateFrom']); }
            if (empty($cont['dateTo']))     { unset($cont['dateTo']); }
            if (empty($cont['timeFrom']))   { unset($cont['timeFrom']); }   else { $cont['timeFrom'] = substr($cont['timeFrom'], 0, -3); }
            if (empty($cont['timeTo']))     { unset($cont['timeTo']); }     else { $cont['timeTo'] = substr($cont['timeTo'], 0, -3); }

            array_push($list, $contenido['id']);
            $catalog[$contenido['id']] = $cont;
        }

        return array('list'=>$list, 'catalog'=> $catalog);
    }

    private function devMusic() {
        if (!!!$this->remoteMusicJson) { return null; }

        $return = $this->remoteMusicJson->music;
        return $return;
    }

    private function devMusicCatalog() {
        if (!!!$this->remoteMusicJson) { return null; }

        $return = $this->remoteMusicJson->catalog->music;
        return $return;
    }

    private function devGuardias() {
        if (!!!$this->remoteGuardiasJson) { return null; }

        $return = $this->remoteGuardiasJson->guardias;
        return $return;
    }

    public function json($id) {
        date_default_timezone_set("Atlantic/Canary");

        $this->id = $id;
        $playlist = Playlist::where('id', $id)->first();
        if (!!$playlist->musicURL) {
            $this->remoteMusicJson = @json_decode(file_get_contents($playlist->musicURL));
        }

        $dMedia = $this->devMedia();

        $json['info'] = $this->devInfo();
        $json['media'] = $dMedia['list'];
        $json['music'] = $this->devMusic();
        $json['events'] = [];
        $json['catalog']['media'] = $dMedia['catalog'];
        $json['catalog']['music'] = $this->devMusicCatalog();
        $json['power'] = $this->devPower();

        if ($playlist->zonaGuardias != 0) {
            $this->remoteGuardiasJson = @json_decode(file_get_contents('https://api.farmavisioncanarias.com/guardias/'.$playlist->zonaGuardias));
            $json['guardias'] = $this->devGuardias();
        }

        header("Content-type: application/json");
        echo json_encode($json, JSON_PRETTY_PRINT);
        die();
    }
}
