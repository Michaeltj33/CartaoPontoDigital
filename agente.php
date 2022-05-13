<!DOCTYPE html>
<html lang="pt-br">
<?php
include_once 'superServidor.php';
$nome = "";

//Usado para verificar se o Site contem Cookies salvo
//e depois analisar no servidor para buscar as informações do consultor
if (!empty($_COOKIE["agentes"])) {
    $cookies = explode("_", $_COOKIE["agentes"]);

    $verAgent = new classe();
    $Consultor = $verAgent->retornaCookies($cookies[1], $cookies[0]);
} else {
    header("Location:index.php");
}

$pegaCpf = str_replace(".", "", $Consultor['cpf']);
$pegaCpf = str_replace("-", "", $pegaCpf);
$tabelaAgente = "t_" . $Consultor["id_agente"] . "_" .  $pegaCpf;
$pegarValor = $verAgent->pegarPontoAgente($tabelaAgente);
$pegarValor = array_reverse($pegarValor);

if (!empty($pegarValor)) {
    $ultimoPonto = strtoupper("Ultimo registro: Data: " . $pegarValor[0]['data'] . " Hora: " . $pegarValor[0]['hora'] . " - " . $pegarValor[0]['semana']);
}

if (!empty($_COOKIE['aviso'])) {
    $pegaAviso = $_COOKIE['aviso'];
    setcookie("aviso", null);
}

$ponto = $Consultor['ponto'];
$todosPontos = explode("_", $ponto);
$ponto_Find = $Consultor['pontoFind'];
$totaldeHora = $verAgent->somarhoraTrabalho($ponto);


$pontosFind = explode("_", $Consultor['pontoFind']);
if (empty($pontosFind[1])) {
    $pontosFind[1] = null;
}

$data = explode("-", $verAgent->pegarData());
$mostrarTodosMes = $verAgent->getTodosMes($data[2]);
$mesAtual = $mostrarTodosMes[$data[1] - 1][1];

//Usado para capturar mensagem enviado pelo Administrador 
$coletorMsg = $verAgent->lerMsgEnviada($Consultor['cpf'], strtolower($cookies[1]));

?>

<read>
    <title>Logado no Sistema</title>
    <link rel="Stylesheet" href="_css/estilo.css">

</read>

<body>

    <!Formulário usado para desconectar o Funcionário" –>
        <form action="desconectado.php" method="POST" class="lado" name="form2" id="ladoA">
            <div>
                <input type="submit" id="submit2" name="SAIR_FUNC" value="DESLOGAR">
            </div>
        </form>

        <?php
            if(sizeof($coletorMsg) > 0){
                setcookie("lerMsg",$coletorMsg[0]['id_tabela']);
         ?>
        <form action="registrarMensagem.php" method="POST" class="form-style-alert">
            <h1>PAINEL DE AVISO</h1>
            <h3>Mensagem enviada pelo Adm: <?php echo strtoupper($coletorMsg[0]['administrador']) ?></h3>
            <textarea style="resize: none; color: blue;font-size:18px" maxlength="200" cols="100" rows="5"  disabled><?php echo $coletorMsg[0]['mensagem'] ?>
            </textarea>
            <br>
            <input type="submit" id="LER_MSG" name="LER_MSG" value="CONCORDO">
            <br><br><b>Está Mensagem irá Sumir assim que Clicar em "CONCORDO"</b>            
        </form>
        <br><br>

        <?php }//Aqui finaliza o coletorMsg ?>

        <!Formulário usado para Mostrar o Nome do Funcionário" –>
            <form class="form-style-text" name="form1" id="form1">
                <h1><b>SISTEMA DE LOGIN</b></h1>
                <div>
                    <img src="_imagens/TincDig.png" width="100px">
                </div>
                <div>
                    <h2><?php echo $Consultor['nome'] . " " . $Consultor['sobrenome'] ?></h2>
                </div>
                <div id="divTable">
                    <table>
                        <tr>
                            <td colspan="5">Registro de Pontos</td>
                        </tr>
                        <tr>
                            <td>Semana</td>
                            <td>E1</td>
                            <td>S1</td>
                            <td>E2</td>
                            <td>S2</td>
                        </tr>
                        <tr>
                            <td>Seg~Sex</td>
                            <td><?php echo $todosPontos[0] ?></td>
                            <td><?php echo $todosPontos[1] ?></td>
                            <td><?php echo $todosPontos[2] ?></td>
                            <td><?php echo $todosPontos[3] ?></td>
                        </tr>
                        <tr>
                            <td>Sab~Dom</td>
                            <td><?php echo $pontosFind[0] ?></td>
                            <td><?php echo $pontosFind[1] ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <hr size="5"><br>
                <h2>EMPREENDIMENTO: <?php echo strtoupper($cookies[1]) ?></h2>
                <br>
                <hr size="5">
            </form>


            <!Formulário usado para registrar o Ponto" –>
                <form action="verificar-ponto.php" name="form3" class="form-style-text" id=formAlinhar>

                    <div>
                        <input type="submit" id="submit3" value="REGISTRAR PONTO">
                    </div>

                    <?php if (!empty($_COOKIE['aviso'])) { ?>
                        <b style="color: Red;"> <?php echo $pegaAviso ?> </b>
                    <?php } ?>

                </form>

                <form id="form4" class="form-style-text" id="form4">
                    <div>
                        <h3> <?php
                                if (!empty($pegarValor)) {
                                    echo $ultimoPonto;
                                }
                                ?> </h3>

                        <?php
                        $pegaData = $verAgent->pegarData();                       
                        $arrayData = array();
                        $somarHoraTotal = 0;
                        $horaExtra100 = 0;
                        $somarHoraExtraTotal = 0;
                        $somarHoraFaltaTotal = 0;
                        $pegaCookiesErro = 0;
                        $tdDias = 0;
                        $dataMesAno = $data[1] . "-" . $data[2];
                        for ($x = 0; $x < sizeof($pegarValor); $x++) {
                            if (strpos($pegarValor[$x]['data'], $dataMesAno) !== false) { //verifica o mês e ano
                                for ($y = 0; $y <= $mesAtual; $y++) {
                                    if ($y < 10) {
                                        $y1 = "0" . $y;
                                    } else {
                                        $y1 = $y;
                                    }
                                    if ($pegarValor[$x]['data'] == $y1 . "-" . $dataMesAno) {
                                        $arrayData[$y1][] = [$pegarValor[$x]['hora'], $pegarValor[$x]['semana']];
                                    }
                                }
                            }
                        }
                        ksort($arrayData);

                        if (!empty($arrayData)) {
                        ?>
                            <hr size="10">

                            <table id="tableAgentes">
                                <tr>
                                    <td>Data</td>
                                    <td>E1</td>
                                    <td>S1</td>
                                    <td>E2</td>
                                    <td>S2</td>
                                    <td>Total Hora</td>
                                    <td>Ex50%</td>
                                    <td>Ex100%</td>
                                    <td>HoraFalta</td>
                                    <td>Semana</td>
                                </tr>
                                <h3><?php

                                    // copiar para o agente                        
                                    for ($z = $mesAtual; $z > 0; $z--) {
                                        $tdDias++;

                                        if ($z < 10) {
                                            $z1 = "0" . $z;
                                        } else {
                                            $z1 = $z;
                                        }
                                        $pegarNome = "";
                                        if (isset($arrayData[$z1][0])) {
                                            $arrayCorrecao = array();
                                    ?>
                                            <tr>

                                                <td> <?php echo $z1 . "-" . $dataMesAno; ?> </td>

                                                <?php
                                                for ($zz = 0; $zz < sizeof($arrayData[$z1]); $zz++) {
                                                    array_push($arrayCorrecao, $arrayData[$z1][$zz][0]);
                                                    $semana = $arrayData[$z1][$zz][1];
                                                }
                                                sort($arrayCorrecao);

                                                for ($x1 = 0; $x1 < sizeof($arrayCorrecao); $x1++) {
                                                ?> <td> <?php
                                                        if ($x1 == 0) {
                                                            $somar = array();
                                                        }

                                                        $pontoCorrigido10Min = $verAgent->limitePonto10Min($arrayCorrecao[$x1], $todosPontos[$x1]);
                                                        echo $arrayCorrecao[$x1];
                                                        array_push($somar, $pontoCorrigido10Min);
                                                        ?> </td> <?php

                                                                    if (sizeof($arrayCorrecao) == 1) {
                                                                        if ($x1 == 0) {
                                                                            $mostrar100 = 0;
                                                                            if ($semana == "Sabado" || $semana == "Domingo") {
                                                                                if ($pontosFind[0] == null || $pontosFind[1] == null) {
                                                                                    $pgSomar = $verAgent->somaHoraTotal($somar, $ponto);
                                                                                } else {
                                                                                    $pgSomar = $verAgent->somaHoraTotal($somar, $ponto_Find);
                                                                                }
                                                                                $mostrar100 = $pgSomar[0] + $pgSomar[1] + $pgSomar[2];
                                                                                $horaExtra100 += $pgSomar[0];
                                                                                $horaExtra100 += $pgSomar[1];
                                                                                $horaExtra100 += $pgSomar[2];
                                                                                $pgSomar[2] = 0; //uso para mostrar apenas o Ex100%

                                                                            } else {
                                                                                $pgSomar = $verAgent->somaHoraTotal($somar, $ponto);
                                                                                $somarHoraTotal += $pgSomar[0];
                                                                                $somarHoraExtraTotal += $pgSomar[1];
                                                                                $somarHoraExtraTotal += $pgSomar[2];
                                                                            }
                                                                            $somarHoraTotal += $pgSomar[0];
                                                                            $somarHoraFaltaTotal += $pgSomar[3];
                                                                    ?> <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <?php
                                                                            //Usado para verificar se caso seja dia atual não registrar
                                                                            if ($pegaData == $z1 . "-" . $dataMesAno) { ?>
                                                                <td></td>
                                                            <?php } else { ?>
                                                                <td id="tdRed"> <?php echo "Incalculável";
                                                                                $pegaCookiesErro++;  ?> </td>
                                                            <?php } ?>
                                                            <td><?php
                                                                            if ($pgSomar[2] != 0) {
                                                                                echo $verAgent->segundoHora($pgSomar[2]);
                                                                            }
                                                                ?></td>
                                                            <td><?php
                                                                            if ($mostrar100 != 0) {
                                                                                echo $verAgent->segundoHora($mostrar100);
                                                                            }
                                                                ?></td>
                                                            <td><?php
                                                                            if ($pgSomar[3] != 0) {
                                                                                echo $verAgent->segundoHora($pgSomar[3]);
                                                                            }
                                                                ?></td>
                                                            <td><?php echo $semana ?></td> <?php
                                                                                        }
                                                                                    } else if (sizeof($arrayCorrecao) == 2) {
                                                                                        if ($x1 == 1) {
                                                                                            $mostrar100 = 0;
                                                                                            if ($semana == "Sabado" || $semana == "Domingo") {
                                                                                                if ($pontosFind[0] == null || $pontosFind[1] == null) {
                                                                                                    $pgSomar = $verAgent->somaHoraTotal($somar, $ponto);
                                                                                                } else {
                                                                                                    $pgSomar = $verAgent->somaHoraTotal($somar, $ponto_Find);
                                                                                                }
                                                                                                if ($semana == "Sabado") {
                                                                                                    $pgSomar = $verAgent->somaHoraTotal($somar, $ponto);
                                                                                                    $somarHoraTotal += $pgSomar[0];
                                                                                                    $somarHoraExtraTotal += $pgSomar[1];
                                                                                                    $somarHoraExtraTotal += $pgSomar[2];
                                                                                                    $pgSomar[3] = 0;
                                                                                                } else {
                                                                                                    $mostrar100 = $pgSomar[0] + $pgSomar[1] + $pgSomar[2];
                                                                                                    $horaExtra100 += $pgSomar[0];
                                                                                                    $horaExtra100 += $pgSomar[1];
                                                                                                    $horaExtra100 += $pgSomar[2];
                                                                                                    $pgSomar[2] = 0; //uso para mostrar apenas o Ex100%
                                                                                                }
                                                                                            } else {
                                                                                                $pgSomar = $verAgent->somaHoraTotal($somar, $ponto);
                                                                                                $somarHoraTotal += $pgSomar[0];
                                                                                                $somarHoraExtraTotal += $pgSomar[1];
                                                                                                $somarHoraExtraTotal += $pgSomar[2];
                                                                                            }
                                                                                            $somarHoraTotal += $pgSomar[0];
                                                                                            $somarHoraFaltaTotal += $pgSomar[3];
                        
                        
                                                                                            ?> <td></td>
                                                            <td></td>
                                                            <td id="tdVer"><?php echo $verAgent->segundoHora($pgSomar[0]);  ?> </td>
                                                            <td><?php
                                                                                            if ($pgSomar[2] != 0) {
                                                                                                echo $verAgent->segundoHora($pgSomar[2]);
                                                                                            }
                                                                ?></td>
                                                            <td><?php
                                                                                            if ($mostrar100 != 0) {
                                                                                                echo $verAgent->segundoHora($mostrar100);
                                                                                            }
                                                                ?></td>
                                                            <td><?php
                                                                                            if ($pgSomar[3] != 0) {
                                                                                                echo $verAgent->segundoHora($pgSomar[3]);
                                                                                            }
                                                                ?></td>
                                                            <td><?php echo $semana ?></td> <?php
                                                                                        }
                                                                                    } else if (sizeof($arrayCorrecao) == 3) {
                                                                                        if ($x1 == 2) {
                                                                                            $pgSomar = $verAgent->somaHoraTotal($somar, $ponto);
                                                                                            $somarHoraExtraTotal += $pgSomar[2];
                                                                                            $somarHoraFaltaTotal += $pgSomar[3];
                                                                                            ?> <td></td>
                                                            <?php if ($pegaData == $z1 . "-" . $dataMesAno) { ?>
                                                                <td></td>
                                                            <?php   } else { ?>
                                                                <td id="tdRed"> <?php echo "Incalculável";  ?> </td>
                                                            <?php } ?>
                                                            <td><?php
                                                                                            if ($pgSomar[2] != 0) {
                                                                                                echo $verAgent->segundoHora($pgSomar[2]);
                                                                                            }
                                                                ?></td>
                                                            <td></td>
                                                            <td><?php
                                                                                            if ($pgSomar[3] != 0) {
                                                                                                echo $verAgent->segundoHora($pgSomar[3]);
                                                                                            }
                                                                ?></td>
                                                            <td><?php echo $semana ?></td> <?php
                                                                                        }
                                                                                    } else if (sizeof($arrayCorrecao) == 4) {
                                                                                        if ($x1 == 3) {
                                                                                            $pgSomar = $verAgent->somaHoraTotal($somar, $ponto);
                                                                                            $somarHoraTotal += $pgSomar[0];
                                                                                            //$somarHoraExtraTotal += $pgSomar[1];
                                                                                            $somarHoraExtraTotal += $pgSomar[2];
                                                                                            $somarHoraFaltaTotal += $pgSomar[3];

                                                                                            ?>
                                                            <td id="tdUnset"> <?php echo $verAgent->segundoHora($pgSomar[0]);  ?> </td>
                                                            <td><?php if ($pgSomar[2] != 0) {
                                                                                                echo $verAgent->segundoHora($pgSomar[2]);
                                                                                            }
                                                                ?>
                                                            </td>
                                                            <td></td>
                                                            <td><?php
                                                                                            if ($pgSomar[3] != 0) {
                                                                                                echo $verAgent->segundoHora($pgSomar[3]);
                                                                                            }
                                                                ?></td>
                                                            <td><?php echo $semana ?></td>
                                                <?php
                                                                                        }
                                                                                    }
                                                                                    //aqui acaba Agente.php   
                                                                                }



                                                ?>
                                            </tr>
                                            <?php
                                        } else {
                                            if ($z1 <= $data[0]) {
                                                $semanaEditada = $z1 . "-" . $data[1] . "-" . $data[2];                                                
                                                if ($verAgent->pegarSemanaEditada($semanaEditada) != "Domingo") {
                                                    if ($pegaData == $z1 . "-" . $dataMesAno) {
                                                        $mostrar = "";
                                                        $mostrar2 = "";
                                                        $faltaHora = "";
                                                    }else{
                                                        $mostrar = "F";
                                                        $mostrar2 = "Faltou";
                                                        $faltaHora = $verAgent->segundoHora($totaldeHora);
                                                    }
                                            ?>
                                                    <td><?php echo $semanaEditada ?></td>
                                                    <td><?php echo $mostrar ?></td>
                                                    <td><?php echo $mostrar ?></td>
                                                    <td><?php echo $mostrar ?></td>
                                                    <td><?php echo $mostrar ?></td>
                                                    <td><?php echo $mostrar2 ?></td>
                                                    <td><?php echo $mostrar ?></td>
                                                    <td><?php echo $mostrar ?></td>
                                                    <td><?php echo $faltaHora ?></td>
                                                    <td><?php echo $verAgent->pegarSemanaEditada($semanaEditada) ?></td>
                                                    </tr>
                                <?php
                                                    $somarHoraFaltaTotal += $totaldeHora;
                                                }else{
                                                    ?>
                                                    <td><?php echo $z1 . "-" . $dataMesAno ?></td>
                                                    <td>Folga</td>
                                                    <td>Folga</td>
                                                    <td>Folga</td>
                                                    <td>Folga</td>
                                                    <td>Folga</td>
                                                    <td>Folga</td>
                                                    <td>Folga</td>
                                                    <td>Folga</td>
                                                    <td><?php echo $verAgent->pegarSemanaEditada($semanaEditada) ?></td>
                                                    </tr>
                                <?php
        
                                                }
                                            }
                                        }
                                    }



                                    if ($pegarNome != "") {
                                        echo $pegarNome; //Usado para Mostrar na tela                                                     
                                    }
                                }
                                //termina aqui o Agente.php
                                if ($somarHoraExtraTotal > 187200) {
                                    $horaExtra100 = $somarHoraExtraTotal - 187200;
                                    $somarHoraExtraTotal = 187200;
                                }
                                
                                ?>
                                </h3>
                                <?php if (!empty($pegarValor)) { ?>
                                    <tr>
                                        <th colspan="5">RESULTADO DAS HORAS TRABALHADAS</th>
                                        <th><?php echo $verAgent->segundoHora($somarHoraTotal) ?> </th>
                                        <th><?php
                                            if ($somarHoraExtraTotal != 0) {
                                                echo $verAgent->segundoHora($somarHoraExtraTotal);
                                            }
                                            ?> </th>
                                        <th><?php
                                            if ($horaExtra100 != 0) {
                                                echo $verAgent->segundoHora($horaExtra100);
                                            }
                                            ?> </th>
                                        <th><?php echo $verAgent->segundoHora($somarHoraFaltaTotal) ?> </th>
                                        <th>-</th>
                                    </tr>
                                <?php } ?>
                            </table>
                    </div>
                    </div>
                </form>

</body>


</html>