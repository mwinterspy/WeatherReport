<?php
require 'Classes/Models/Clima.php';
date_default_timezone_set('America/Sao_Paulo');
use GuzzleHttp\Client;

class OpenWeather
{
    public $cidade = 'Brusque';
    public $appid = '7d712060c61b56ec5dcbbc68d6b2b146';


    public function getTempoAtual()
    {
        try{
            $recurso = "https://api.openweathermap.org/data/2.5/weather?q=" .$this->cidade. "&appid=" .$this->appid . "&units=metric&lang=pt_br"; 

        $client = new GuzzleHttp\Client();
        $resposta = $client->request('GET', $recurso, []);

        //Status code
        //$resposta->getStatusCode();

        //Header
        //$resposta->getHeader('content-type')[0];

        $objJson = json_decode($resposta->getBody());
        $clima = $this->mapear($objJson);
        $this->guardarEmCache($clima);
        }catch(Exception $e){
            $clima = $this->obterCache();
        }
        
        return $clima;
    }

    private function mapear($objJson)
    {
        $clima = new Clima();

        $clima->atualizacao = time();
        $clima->temperatura = $objJson->main->temp;
        $clima->cidade = $objJson->name;
        $clima->humidade = $objJson->main->humidity;
        $clima->direcaoVento = $objJson->wind->deg;
        $clima->velocidadeVento = $objJson->wind->speed;
        $clima->descricao = $objJson->weather[0]->description;
        $clima->temperaturaMinima = $objJson->main->temp_min;
        $clima->temperaturaMaxima = $objJson->main->temp_max;
        $clima->icone = $objJson->weather[0]->icon;
        $clima->nascerDoSol = $objJson->sys->sunrise;
        $clima->porDoSol = $objJson->sys->sunset;
        $clima->lat = $objJson->coord->lat;
        $clima->lon = $objJson->coord->lon;

        if((int)$clima->direcaoVento > 0 && (int)$clima->direcaoVento < 90){
            $clima->direcaoVento = 'Norte';
        }else if((int)$clima->direcaoVento > 35 && (int)$clima->direcaoVento < 165){
            $clima->direcaoVento = 'Nordeste';
        }else if((int)$clima->direcaoVento > 90 && (int)$clima->direcaoVento < 180){
            $clima->direcaoVento = 'Leste';
        }
        else if((int)$clima->direcaoVento > 180 && (int)$clima->direcaoVento < 270){
            $clima->direcaoVento = 'Sul';
        }else if((int)$clima->direcaoVento > 270 && (int)$clima->direcaoVento < 360){
            $clima->direcaoVento = 'Oeste';
        }

        return $clima;
    }

    public function guardarEmCache($clima){

        $dadosSerializados = serialize($clima);
        $caminhoArquivoCache = 'cache/clima.bin';
        file_put_contents($caminhoArquivoCache, $dadosSerializados);

    }
    public function obterCache(){
        $caminhoArquivoCache = 'cache/clima.bin';
        $dadosSerializados = file_get_contents($caminhoArquivoCache);
        $dadosUnserializados = unserialize($dadosSerializados);

        return $dadosUnserializados;

    }
}
