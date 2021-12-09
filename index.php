<?php 

$aovivo = "";
//verifica se o arquivo urlaovivo esta vazio, se não estiver executa
$path = "urlaovivo.txt";;
if(file_exists($path) && !filesize($path) < 16 && !empty(trim(file_get_contents($path))) ) {

    
    $pathArquivo = "attResultado/aovivo.txt";

    if (file_exists($pathArquivo) && !filesize($pathArquivo) < 16 && !empty(trim(file_get_contents($pathArquivo))) ) {

        $placar = file_get_contents($pathArquivo);
        $placar = trim(explode('|| {};', str_replace('window.transmissao = ', '', $placar))[0]);

        $placar = json_decode($placar, true);


        if(!empty($placar)){

            $visitante = $placar['jogo']['visitante'];
            $mandante = $placar['jogo']['mandante'];

            $gol = [];

            if($placar['jogo_sde']['resultados']){

                $gol[] = $placar['jogo_sde']['resultados']['placar_oficial_mandante'] ?? '';
                $gol[] = $placar['jogo_sde']['resultados']['placar_penaltis_mandante'] ?? '';
                $gol[] = $placar['jogo_sde']['resultados']['placar_oficial_visitante'] ?? '';
                $gol[] = $placar['jogo_sde']['resultados']['placar_penaltis_visitante'] ?? '';
            }else{

                $gol[] = $mandante['placarOficial'] ?? '';
                $gol[] = $mandante['placarPenaltisOficial'] ?? '';
                $gol[] = $visitante['placarOficial'] ?? '';
                $gol[] = $visitante['placarPenaltisOficial'] ?? '';
            }

            $aovivo = [
                "time1" => $mandante['nome'] ?? '',
                "time1img" => $mandante['escudos']['30x30'] ?? '',
                "time2" => $visitante['nome'] ?? '',
                "time2img" => $visitante['escudos']['30x30'] ?? '',
                "golsT1" => $gol[0],
                "golsT1Penalt" => $gol[1],
                "golsT2" => $gol[2],
                "golsT2Penalt" => $gol[3],
                "data" => $placar['momento_corrente'] != '00:00' ? $placar['momento_corrente'] : '',
                "campeonato" => $placar['campeonato'] ?? '',
                "urlTime" => ''
            ];
        }else{

            $placar = file_get_contents($pathArquivo);
            $placar = trim(explode('|| {};', str_replace('window.transmissao = ', '', $placar))[0]);


            $placar = str_replace('transmission:', '"transmission":', $placar);
            $placar = str_replace('plays:', '"plays":', $placar);
            $placar = str_replace('playsVideos:', '"playsVideos":', $placar);
            $placar = str_replace('classification:', '"classification":', $placar);
            $placar = str_replace(',  };', '}', $placar);

            $placar = json_decode($placar, true);


            if($placar){

                $visitante = $placar['transmission']['match']['awayTeam'];
                $mandante = $placar['transmission']['match']['homeTeam'];

                $gol = $placar['transmission']['match'];

                $golNome1 = '';
                $golNome2 = '';

    
                foreach ($placar['plays'] as $item){

                    if($item['playType']['id'] == "GOAL"){

                        if( $item['details']['team']['abbreviation'] == $mandante['abbreviation']){
                            $golNome1 = explode( ':', $item['moment'] )[0] . "' " . $item['details']['athlete']['slug'] . ', ' . $golNome1;
                        }else{
                            $golNome2 =  explode( ':', $item['moment'] )[0] . "' " . $item['details']['athlete']['slug'] . ', ' . $golNome2;
                        }
                    }
                }


                $golNome1 = substr($golNome1, 0, -2);
                $golNome2 = substr($golNome2, 0, -2);
                
                $aovivo = [
                    "time1" => $mandante['abbreviation'] ?? '',
                    "time1img" => $mandante['badgePng'] ?? '',
                    "time2" => $visitante['abbreviation'] ?? '',
                    "time2img" => $visitante['badgePng'] ?? '',
                    "golsT1" => $gol['scoreboard']['home'] ?? '',
                    "golsT1Penalt" => $mandante['placarPenaltisOficial'] ?? '',
                    "golsT2" => $gol['scoreboard']['away'] ?? '',
                    "golsT2Penalt" => $visitante['placarPenaltisOficial'] ?? '',
                    "golNome1" => $golNome1 ?? '',
                    "golNome2" => $golNome2 ?? '',
                    "data" => $placar['transmission']['period']['label'] ?? '',
                    "campeonato" => $placar['classification']['currentPhase']['championshipEdition']['name'] ?? '',
                    "urlTime" => ''
                ];
            }

        }

        echo  $aovivo["time1"];
        echo  $aovivo["time1img"];
        echo  $aovivo["time2"];
        echo  $aovivo["time2img"];
        echo  $aovivo["golsT1"];
        echo  $aovivo["golsT1Penalt"]; 
        echo  $aovivo["golsT2"]; 
        echo  $aovivo["golsT2Penalt"];
        echo  $aovivo["golNome1"];
        echo  $aovivo["golNome2"]; 
        echo  $aovivo["data"]; 
        echo  $aovivo["campeonato"];

    }
    
}

 
