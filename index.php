<?php

require './vendor/autoload.php';
require 'Classes/OpenWeather.php';

$o = new OpenWeather();

if (isset($_POST['city'])) {
    $o->cidade = $_POST['city'];
} else {
    $o->cidade = 'Brusque';
}



$dadosClimaticos = $o->getTempoAtual();

$minutos = time() - $dadosClimaticos->atualizacao;
$minutos = $minutos / 60;
$minutos = number_format($minutos, 1, '.');


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clima</title>
    <link href="style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />
    <script src="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js"></script>
    <script src="script.js" defer></script>
</head>

<body>

    <div class="tela">

        <form action="index.php" method="POST" style="display: flex;">
            <input class=" inputCity" name='city' type="text" placeholder="Digite o nome da cidade">
            <button class="searchButton" type="submit"> <i class="fa-solid fa-magnifying-glass" style="color: white;"></i> </button>
        </form>
        <div class="display">



            <div class="carta">
                <div class="frente">
                    <div class="card bg-black text-white" style="border-radius: 2rem;">
                        <div class="card-body">
                            <h1><i class="fa-solid fa-location-dot" style="color: #ffffff; margin-right: 10px"></i> <?php echo $dadosClimaticos->cidade ?></h1>
                            <img class="icone" src="img/<?php echo $dadosClimaticos->icone ?>.png">
                            <p1><?php echo ucfirst($dadosClimaticos->descricao) ?></p1>
                            <div class="texto">
                                <h1><?php echo $dadosClimaticos->temperatura ?></h1>
                                <p1>ºC</p1>
                            </div>
                            <div class="infoPainel">
                                <img class="infoIcon" src="img/vento.png">
                                <p1><?php echo $dadosClimaticos->velocidadeVento ?> KM/h - </p1>
                                <p1><?php echo $dadosClimaticos->direcaoVento ?></p1>
                                <div class="infoSol">
                                    <div class="sunrise">
                                        <img class="iconSunrise" src="img/sunrise.png"><br>
                                        <?php echo date("H:i", $dadosClimaticos->nascerDoSol) ?>
                                    </div>
                                    <div class="sunset">
                                        <img src="img/sunset.png" class="iconSunset"><br>
                                        <?php echo date("H:i", $dadosClimaticos->nascerDoSol) ?>
                                    </div>

                                </div>
                            </div>
                            <p1 class="att">Ultima atualização: <?php echo date('H:i:s', $dadosClimaticos->atualizacao) ?></p1>
                        </div>



                    </div>
                </div>
                <div class="verso">

                    <h1>Informações detalhadas</h1>
                    <div class="infoDetalhada">
                        <div id="map">

                        </div>
                        <script>
                            var map = L.map("map", {
                                center: [<?php echo $dadosClimaticos->lat ?>, <?php echo $dadosClimaticos->lon ?>],
                                zoom: 12

                            });
                            
                            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 30,
                                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                            }).addTo(map);
                            var marker = L.marker([]).addTo(map);
                           

                        </script>
                        <ul class="listaDetalhada">

                            <li class=""><b>Cidade:</b> <?php echo $dadosClimaticos->cidade ?></li>
                            <li class=""><b>Temperatura:</b> <?php echo $dadosClimaticos->temperatura ?>ºC</li>
                            <li class=""><b>Velocidade do vento:</b> <?php echo $dadosClimaticos->humidade ?> KM/h</li>
                            <li class=""><b>Humidade:</b> <?php echo $dadosClimaticos->humidade ?></li>
                            <li class=""><b>Temperatura mínima:</b> <?php echo $dadosClimaticos->temperaturaMinima ?> </li>
                            <li class=""><b>Temperatura máxima: </b><?php echo $dadosClimaticos->temperaturaMaxima ?> </li>
                            <li class=""><b>Nascer do Sol: </b><?php echo date('H:i', $dadosClimaticos->nascerDoSol) ?> </li>
                            <li class=""><b>Por do Sol: </b><?php echo date('H:i', $dadosClimaticos->porDoSol) ?> </li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
</body>
</div>


</html>