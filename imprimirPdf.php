<!DOCTYPE html>
<html lang="pt-br">
<?php

include_once 'superServidor.php';


if (empty($_COOKIE['emp'])) {
    header("Location:painelEmpreendimento.php");
} else {
    $empreendimento = strtolower(str_replace(" ", "_", $_COOKIE['emp'])); //Captura o Empreendimento escolhido
}

require __DIR__ . '/vendor/autoload.php';
require_once 'dompdf/autoload.inc.php';
$verAgent = new classe();
if (isset($_POST['outro_relatorio'])) {
    header("Location:outrosRelatorios.php");
    exit(0);
}
ob_implicit_flush(true);
$separador = explode("_", $_COOKIE['pdf']);
$pdf2 = $verAgent->stringToPdf($separador[1]);

$pegaTudo = $verAgent->retornaAgenteCpf($pdf2, $empreendimento);
$ponto = $pegaTudo["ponto"];

$ponto_Find = $pegaTudo['pontoFind'];

$todosPontos = explode("_", $ponto);

$pontosFind = explode("_", $pegaTudo['pontoFind']);
if (empty($pontosFind[1])) {
    $pontosFind[1] = null;
}

//Usado para verificar se o Site contem Cookies salvo
//e depois analisar se o Adm está logado.
if (empty($_COOKIE["adm"])) {
    header("Location:painelAdministrativo-senha.php");
    exit(0);
}

if (empty($_COOKIE["data"])) {
    $pegaData = explode("-", $verAgent->pegarData());
    $ano = $pegaData[2];
    $mes = $pegaData[1];
} else {
    $pg = explode("_", $_COOKIE['data']);
    $mes = $pg[0];
    $ano = $pg[1];
}

$mostrarTodosMes = $verAgent->getTodosMes($ano);
$mesAtual = $mostrarTodosMes[$mes-1][1];

$pegaEmp = $_COOKIE['emp'];


$nomeCompleto = $pegaTudo['nome'] . " " . $pegaTudo['sobrenome'];


if (!empty($_COOKIE['pdf'])) {
    $pegaCookies = explode("_", $_COOKIE['pdf']);
    $pegaPdf = $verAgent->stringToPdf($pegaCookies[1]);

    $pegarValor = $verAgent->pegarPontoAgente("t_" . $_COOKIE['id'] . "_" . $pegaCookies[1]);
    $pegarValor = array_reverse($pegarValor);

    $pegaCadastro = $verAgent->retornaAgenteCpf($pegaPdf, $empreendimento);
}
$pdData = explode("-", $verAgent->pegarData());
$horarioPonto = $pegaCadastro['ponto'];


function imprimirpdf($pdf, $conteudo)
{
    $dompdf = new Dompdf\Dompdf();
    $dompdf->loadHtml($conteudo);
    $dompdf->setPaper('A4', 'Landscape');
    $dompdf->render();
    $dompdf->output();
    ob_end_clean();
    $dompdf->stream($pdf . ".pdf", array("Attachment" => true));
}
$pegaDataCookies = explode("_", $_COOKIE['data']);
$nomePdf = $pegaCadastro['nome'] . "-" . $pegaDataCookies[0] . "_" . $pegaDataCookies[1] . "-" . $pegaEmp . "-" . $pegaCadastro['cpf'];

?>

<read>
    <link rel="Stylesheet" href="_css/estilo.css">
</read>

<body>
    <?php
    $data = explode("-", $verAgent->pegarData());
    $pegaData = $verAgent->pegarData();
    $arrayData = array();
    $somarHoraTotal = 0;
    $somarHoraExtraTotal = 0;
    $somarHoraFaltaTotal = 0;
    $horaExtra100 = 0;
    $pegaCookiesErro = 0;
    $dataMesAno = $mes . "-" . $ano;
    $input = "";
    for ($x = 0; $x < sizeof($pegarValor); $x++) {
        if (strpos($pegarValor[$x]['data'], $dataMesAno) !== false) { //verifica o mês e ano
            for ($y = 1; $y <= 31; $y++) {
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
    //copiar a partir daqui  
    $textPdf = '
   <!DOCTYPE html>
<html lang="pt-br">   

<read>
       <style>
       table, th, td {
        border: 2px solid black;
        text-align: center;
        color: #2d2280;
        font-weight: bolder;       
       }
       table {
           width: 100%;
           border-collapse: collapse;
       } 
       </style>
</read>

<h2 style="color:blue;">Funcionário: ' . $nomeCompleto . '</h2><br>
<h2 style="color:blue;">Empreendimento: ' . $pegaEmp . '</h2>
   <table>
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
   ';

                    // copiar para o agente 
                    for ($z = $mesAtual; $z > 0; $z--) {

                        if ($z < 10) {
                            $z1 = "0" . $z;
                        } else {
                            $z1 = $z;
                        }
                        $pegarNome = "";
                        if (isset($arrayData[$z1][0])) {
                            $textPdf .= "<tr>";


                            $arrayCorrecao = array();
                    
                            $textPdf .= "<td>" . $z1 . "-" . $dataMesAno . "</td>";

                            for ($zz = 0; $zz < sizeof($arrayData[$z1]); $zz++) {
                                array_push($arrayCorrecao, $arrayData[$z1][$zz][0]);
                                $semana = $arrayData[$z1][$zz][1];
                            }
                            sort($arrayCorrecao);

                            for ($x1 = 0; $x1 < sizeof($arrayCorrecao); $x1++) {              
                                if ($x1 == 0) {
                                    $somar = array();
                                }

                                $pontoCorrigido10Min = $verAgent->limitePonto10Min($arrayCorrecao[$x1], $todosPontos[$x1]);
                                echo $arrayCorrecao[$x1];
                                array_push($somar, $pontoCorrigido10Min);
                       
                                    $textPdf .= "<td>" . $arrayCorrecao[$x1] . "</td>";

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
                                                                        $pgSomar[2] = 0;//uso para mostrar apenas o Ex100%                                                                       

                                                                    } else {
                                                                        $pgSomar = $verAgent->somaHoraTotal($somar, $ponto);
                                                                        $somarHoraTotal += $pgSomar[0];
                                                                        $somarHoraExtraTotal += $pgSomar[1];
                                                                        $somarHoraExtraTotal += $pgSomar[2];
                                                                        
                                                                            
                                                                    }
                                                                    $somarHoraTotal += $pgSomar[0];
                                                                    $somarHoraFaltaTotal += $pgSomar[3];
                                    
                                            $textPdf .= "<td></td><td></td><td></td><td>Incalculável</td>";
                                            if ($pgSomar[2] != 0) {
                                                echo $verAgent->segundoHora($pgSomar[2]);
                                                $textPdf .= "<td>" . $verAgent->segundoHora($pgSomar[2]) . "</td>";
                                            } else {
                                                $textPdf .= "<td></td>";
                                            }
                                            if ($mostrar100 != 0) {
                                                echo $verAgent->segundoHora($mostrar100);
                                                $textPdf .= "<td>" . $verAgent->segundoHora($mostrar100) . "</td>";
                                            } else {
                                                $textPdf .= "<td></td>";
                                            }

                                            if ($pgSomar[3] != 0) {
                                                echo $verAgent->segundoHora($pgSomar[3]);
                                                $textPdf .= "<td>" . $verAgent->segundoHora($pgSomar[3]) . "</td>";
                                            } else {
                                                $textPdf .= "<td></td>";
                                            }


                                                            $textPdf .= "<td>" . $semana . "</td>";
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
                                                                        $mostrar100 = $pgSomar[0] + $pgSomar[1] + $pgSomar[2];
                                                                        $horaExtra100 += $pgSomar[0];
                                                                        $horaExtra100 += $pgSomar[1];
                                                                        $horaExtra100 += $pgSomar[2];
                                                                        $pgSomar[2] = 0;//uso para mostrar apenas o Ex100%                                                                       

                                                                    } else {
                                                                        $pgSomar = $verAgent->somaHoraTotal($somar, $ponto);
                                                                        $somarHoraTotal += $pgSomar[0];
                                                                        $somarHoraExtraTotal += $pgSomar[1];
                                                                        $somarHoraExtraTotal += $pgSomar[2];
                                                                        
                                                                            
                                                                    }
                                                                    $somarHoraTotal += $pgSomar[0];
                                                                    $somarHoraFaltaTotal += $pgSomar[3];

                                                            $textPdf .= "<td></td><td></td><td>" . $verAgent->segundoHora($pgSomar[0]) . "</td>";
                                                            if ($pgSomar[2] != 0) {
                                                                echo $verAgent->segundoHora($pgSomar[2]);
                                                                $textPdf .= "<td>" . $verAgent->segundoHora($pgSomar[2]) . "</td>";
                                                            } else {
                                                                $textPdf .= "<td></td>";
                                                            }

                                                            if ($mostrar100 != 0) {
                                                                echo $verAgent->segundoHora($mostrar100);
                                                                $textPdf .= "<td>" . $verAgent->segundoHora($mostrar100) . "</td>";
                                                            } else {
                                                                $textPdf .= "<td></td>";
                                                            }
                                                           


                                                            if ($pgSomar[3] != 0) {
                                                                echo $verAgent->segundoHora($pgSomar[3]);
                                                                $textPdf .= "<td>" . $verAgent->segundoHora($pgSomar[3]) . "</td>";
                                                            } else {
                                                                $textPdf .= "<td></td>";
                                                            }

                                                            $textPdf .= "<td>" . $semana . "</td>";
                                                        }
                                                    } else if (sizeof($arrayCorrecao) == 3) {
                                                        if ($x1 == 2) {
                                                            $pgSomar = $verAgent->somaHoraTotal($somar, $ponto);
                                                            $somarHoraExtraTotal += $pgSomar[2];
                                                            $somarHoraFaltaTotal += $pgSomar[3];
                                                            
                                                            $textPdf .= "<td></td><td>Incalculável</td>";
                                                            if ($pgSomar[2] != 0) {
                                                                echo $verAgent->segundoHora($pgSomar[2]);
                                                                $textPdf .= "<td>" . $verAgent->segundoHora($pgSomar[2]) . "</td>";
                                                            } else {
                                                                $textPdf .= "<td></td>";
                                                            }
                                    
                                                            if ($pgSomar[3] != 0) {
                                                                echo $verAgent->segundoHora($pgSomar[3]);
                                                                $textPdf .= "<td>" . $verAgent->segundoHora($pgSomar[3]) . "</td>";
                                                            } else {
                                                                $textPdf .= "<td></td>";
                                                            }

                                                            $textPdf .= "<td></td>";
                                                            $textPdf .= "<td>" . $semana . "</td>";
                                                        }
                                                    } else if (sizeof($arrayCorrecao) == 4) {
                                                        if ($x1 == 3) {
                                                            $pgSomar = $verAgent->somaHoraTotal($somar, $ponto);
                                                            $somarHoraTotal += $pgSomar[0];
                                                            //$somarHoraExtraTotal += $pgSomar[1];
                                                            $somarHoraExtraTotal += $pgSomar[2];
                                                            $somarHoraFaltaTotal += $pgSomar[3];


                                                            echo $verAgent->segundoHora($pgSomar[0]);
                                                            $textPdf .= "<td>" . $verAgent->segundoHora($pgSomar[0]) . "</td>";

                                                            if ($pgSomar[2] != 0) {
                                                                echo $verAgent->segundoHora($pgSomar[2]);
                                                                $textPdf .= "<td>" . $verAgent->segundoHora($pgSomar[2]) . "</td>";
                                                            } else {
                                                                $textPdf .= "<td></td>";
                                                            }
                                                            $textPdf .= "<td></td>";

                                                            if ($pgSomar[3] != 0) {
                                                                echo $verAgent->segundoHora($pgSomar[3]);
                                                                $textPdf .= "<td>" . $verAgent->segundoHora($pgSomar[3]) . "</td>";
                                                            } else {
                                                                $textPdf .= "<td></td>";
                                                            }


                                                            $textPdf .= "<td>" . $semana . "</td>";
                                                        }
                                                    }
                                                    //aqui acaba Agente.php   

                                                }




                                ?>
            </tr>
    <?php




                            $textPdf .= "</tr>";
                        }else{
                            /*
                            $textPdf .= "<td></td>";
                            $textPdf .= "<td></td>";
                            $textPdf .= "<td></td>";
                            $textPdf .= "<td></td>";
                            $textPdf .= "<td></td>";
                            $textPdf .= "<td></td>";
                            $textPdf .= "<td></td>";
                            $textPdf .= "<td></td>";
                            $textPdf .= "<td></td>";
                            $textPdf .= "<td></td>";
                            $textPdf .= "</tr>"; 
                            */                           
                        }


                        if ($pegarNome != "") {
                            echo $pegarNome; //Usado para Mostrar na tela                                                     
                        }
                    } //final for até dia 31




                    if ($somarHoraExtraTotal > 187200) {
                        $horaExtra100 = $somarHoraExtraTotal - 187200;
                        $somarHoraExtraTotal = 187200;
                    }

                    $textPdf .= "<tr><th colspan='5'>RESULTADO DAS HORAS TRABALHADAS</th>";

                    $textPdf .= "<th>" . $verAgent->segundoHora($somarHoraTotal) . "</th>";
                    if ($somarHoraExtraTotal != 0) {
                        $textPdf .= "<th>" . $verAgent->segundoHora($somarHoraExtraTotal) . "</th>";
                    } else {
                        $textPdf .= "<th></th>";
                    }
                    if ($horaExtra100 != 0) {
                        $textPdf .= "<th>" . $verAgent->segundoHora($horaExtra100) . "</th>";
                    } else {
                        $textPdf .= "<th></th>";
                    }
                    $textPdf .= "<th>" . $verAgent->segundoHora($somarHoraFaltaTotal) . "</th>";

                    $textPdf .= "<th></th></tr>";

                    $textPdf .= "</table>";
                    imprimirpdf($nomePdf, $textPdf);

                    //var_dump($textPdf);
                    //echo "<pre>";
                    //print_r($input);

    ?>

</html>