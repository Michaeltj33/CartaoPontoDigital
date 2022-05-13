<!DOCTYPE html>
<html lang="pt-br">
<?php

include_once 'superServidor.php';

if (empty($_COOKIE['emp'])) {
    header("Location:painelEmpreendimento.php");
} else {
    $empreendimento = strtolower(str_replace(" ", "_", $_COOKIE['emp'])); //Captura o Empreendimento escolhido
}
require __DIR__ . '/vendor/autoload.php'; //usado para PDF e para Planilha do Excel
require_once 'dompdf/autoload.inc.php'; //Usado para PDF
use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory}; //Usado para Excel

$excel = new Spreadsheet();
$excel->getProperties()->setCreator("Michael Anderson da Silva")->setTitle("Cartão Ponto");
$excel->setActiveSheetIndex(0);
$excel->getDefaultStyle()->getFont()->setName("Calibri");
$excel->getDefaultStyle()->getFont()->setSize(11);

$planilha = $excel->getActiveSheet();
$planilha->setCellValue('A1', 'Data');
$planilha->setCellValue('B1', 'E1');
$planilha->setCellValue('C1', 'S1');
$planilha->setCellValue('D1', 'E2');
$planilha->setCellValue('E1', 'S2');
$planilha->setCellValue('F1', 'Total Hora');
$planilha->setCellValue('G1', 'Ex50%');
$planilha->setCellValue('H1', 'Ex100%');
$planilha->setCellValue('I1', 'HoraFalta');
$planilha->setCellValue('J1', 'Semana');
$planilha->getColumnDimension('A')->setWidth('11');
$planilha->getColumnDimension('B')->setWidth('11');
$planilha->getColumnDimension('C')->setWidth('11');
$planilha->getColumnDimension('D')->setWidth('11');
$planilha->getColumnDimension('E')->setWidth('11');
$planilha->getColumnDimension('F')->setWidth('13');
$planilha->getColumnDimension('G')->setWidth('11');
$planilha->getColumnDimension('H')->setWidth('11');
$planilha->getColumnDimension('I')->setWidth('11');
$planilha->getColumnDimension('J')->setWidth('14');
$planilha->getStyle("A1:J1")->getFont()->setBold(true);




$verAgent = new classe();

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
$mesAtual = $mostrarTodosMes[$mes - 1][1];

$pegaEmp = $_COOKIE['emp'];
$nomeCompleto = $pegaTudo['nome'] . " " . $pegaTudo['sobrenome'];

$totalPonto = ($verAgent->somarhoraTrabalho($ponto));

ob_implicit_flush(true);
if (!empty($_COOKIE['pdf'])) {
    $pegaCookies = explode("_", $_COOKIE['pdf']);
    $pegaPdf = $verAgent->stringToPdf($pegaCookies[1]);

    $pegarValor = $verAgent->pegarPontoAgente("t_" . $_COOKIE['id'] . "_" . $pegaCookies[1]);
    $pegarValor = array_reverse($pegarValor);

    $pegaCadastro = $verAgent->retornaAgenteCpf($pegaPdf, $empreendimento);
}
$pdData = explode("-", $verAgent->pegarData());
$horarioPonto = $pegaCadastro['ponto'];
$pegaDataCookies = explode("_", $_COOKIE['data']);

$nomeExcel = $pegaTudo['nome'] . "-" . $pegaDataCookies[0] . "_" . $pegaDataCookies[1] . "-" . $pegaEmp . "-" . $pegaTudo['cpf'];

$fileName = $nomeExcel . ".xlsx";


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
    $valorDiaExcel = 2;
    $contarDiasUteis = 0;
    $pegaCookiesErro = 0;
    $somaDiasFaltas = 0;
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

    for ($z = 1; $z <= $mesAtual; $z++) {
        if ($z < 10) {
            $z1 = "0" . $z;
        } else {
            $z1 = $z;
        }
        $pegarNome = "";

        if (isset($arrayData[$z1][0])) {
            $arrayCorrecao = array();

            for ($zz = 0; $zz < sizeof($arrayData[$z1]); $zz++) {
                array_push($arrayCorrecao, $arrayData[$z1][$zz][0]);
                $semana = $arrayData[$z1][$zz][1];
            }
            sort($arrayCorrecao);

            $planilha->setCellValue('A' . $valorDiaExcel, $z1 . "-" . $dataMesAno);

            //Usando codigo ASCII para preencher as celulas do Excel
            foreach ($arrayCorrecao as $key => $arrC) {
                $planilha->setCellValue(chr(66 + $key) . $valorDiaExcel, $arrC);
            }

            $planilha->setCellValue('J' . $valorDiaExcel, $semana);

            for ($x1 = 0; $x1 < sizeof($arrayCorrecao); $x1++) {

                if ($x1 == 0) {
                    $somar = array();
                }
                $pontoCorrigido10Min = $verAgent->limitePonto10Min($arrayCorrecao[$x1], $todosPontos[$x1]);

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

                        //Usado para verificar se caso seja dia atual não registrar
                        if ($pegaData != $z1 . "-" . $dataMesAno) {
                            $planilha->setCellValue('F' . $valorDiaExcel, "Incalculável");
                        }

                        if ($pgSomar[2] != 0) {
                            echo $verAgent->segundoHora($pgSomar[2]);
                            $planilha->setCellValue('G' . $valorDiaExcel, $verAgent->segundoHora($pgSomar[2]));
                        }
                        if ($mostrar100 != 0) {
                            $planilha->setCellValue('H' . $valorDiaExcel, $verAgent->segundoHora($mostrar100));
                        }

                        if ($pgSomar[3] != 0) {
                            echo $verAgent->segundoHora($pgSomar[3]);
                            $planilha->setCellValue('I' . $valorDiaExcel, $verAgent->segundoHora($pgSomar[3]));
                        }
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
                            $pgSomar[2] = 0; //uso para mostrar apenas o Ex100%                                                                       

                        } else {
                            $pgSomar = $verAgent->somaHoraTotal($somar, $ponto);
                            $somarHoraTotal += $pgSomar[0];
                            $somarHoraExtraTotal += $pgSomar[1];
                            $somarHoraExtraTotal += $pgSomar[2];
                        }
                        $somarHoraTotal += $pgSomar[0];
                        $somarHoraFaltaTotal += $pgSomar[3];

                        $planilha->setCellValue('F' . $valorDiaExcel, $verAgent->segundoHora($pgSomar[0]));

                        if ($pgSomar[2] != 0) {
                            $planilha->setCellValue('G' . $valorDiaExcel, $verAgent->segundoHora($pgSomar[2]));
                        }

                        if ($mostrar100 != 0) {
                            $planilha->setCellValue('H' . $valorDiaExcel, $verAgent->segundoHora($mostrar100));
                        }

                        if ($pgSomar[3] != 0) {
                            $planilha->setCellValue('I' . $valorDiaExcel, $verAgent->segundoHora($pgSomar[3]));
                        }
                    }
                } else if (sizeof($arrayCorrecao) == 3) {
                    if ($x1 == 2) {
                        $pgSomar = $verAgent->somaHoraTotal($somar, $ponto);
                        $somarHoraExtraTotal += $pgSomar[2];
                        $somarHoraFaltaTotal += $pgSomar[3];
                ?> <td></td>
    <?php if ($pegaData != $z1 . "-" . $dataMesAno) {
                            $planilha->setCellValue('F' . $valorDiaExcel, "Incalculável");
                        }
                        if ($pgSomar[2] != 0) {
                            echo $verAgent->segundoHora($pgSomar[2]);
                            $planilha->setCellValue('G' . $valorDiaExcel, $verAgent->segundoHora($pgSomar[2]));
                        }

                        if ($pgSomar[3] != 0) {
                            echo $verAgent->segundoHora($pgSomar[3]);
                            $planilha->setCellValue('I' . $valorDiaExcel, $verAgent->segundoHora($pgSomar[2]));
                        }
                    }
                } else if (sizeof($arrayCorrecao) == 4) {
                    if ($x1 == 3) {
                        $pgSomar = $verAgent->somaHoraTotal($somar, $ponto);
                        $somarHoraTotal += $pgSomar[0];
                        $somarHoraExtraTotal += $pgSomar[2];
                        $somarHoraFaltaTotal += $pgSomar[3];


                        $planilha->setCellValue('G' . $valorDiaExcel, $verAgent->segundoHora($pgSomar[0]));

                        if ($pgSomar[2] != 0) {

                            $planilha->setCellValue('G' . $valorDiaExcel, $verAgent->segundoHora($pgSomar[2]));
                        }

                        if ($pgSomar[3] != 0) {
                            $planilha->setCellValue('I' . $valorDiaExcel, $verAgent->segundoHora($pgSomar[2]));
                        }
                    }
                }
            }

            //Usado para acresentar mais 1 para planilha Excel                        

            //ob_clean();
        } else {
            $resp = "00:00:00";
            $semanaEditada = $z1 . "-" . $mes . "-" . $ano;
            $planilha->setCellValue("A" . $z + 1, $z1 . "-" . $mes . "-" . $ano);
            $planilha->setCellValue("J" . $z + 1, $verAgent->pegarSemanaEditada($semanaEditada));
            if ($verAgent->pegarSemanaEditada($semanaEditada) != "Domingo") {
                $planilha->setCellValue("B" . $z + 1, $resp);
                $planilha->setCellValue("C" . $z + 1, $resp);
                $planilha->setCellValue("D" . $z + 1, $resp);
                $planilha->setCellValue("E" . $z + 1, $resp);
                $planilha->setCellValue("F" . $z + 1, "Faltou");
                $planilha->setCellValue("G" . $z + 1, $resp);
                $planilha->setCellValue("H" . $z + 1, $resp);
                $planilha->setCellValue("I" . $z + 1,  $verAgent->segundohora($totalPonto));
                $somarHoraFaltaTotal += $totalPonto;
            } else {
                $planilha->setCellValue("B" . $z + 1, "Folga");
                $planilha->setCellValue("C" . $z + 1, "Folga");
                $planilha->setCellValue("D" . $z + 1, "Folga");
                $planilha->setCellValue("E" . $z + 1, "Folga");
                $planilha->setCellValue("F" . $z + 1, "Folga");
                $planilha->setCellValue("G" . $z + 1, "Folga");
                $planilha->setCellValue("H" . $z + 1, "Folga");
                $planilha->setCellValue("I" . $z + 1, "Folga");
            }


            $somaDiasFaltas++;
            //ob_clean();
        }
        $valorDiaExcel++;
    }

    if ($somarHoraExtraTotal > 187200) {
        $horaExtra100 = $somarHoraExtraTotal - 187200;
        $somarHoraExtraTotal = 187200;
    }


    $ResTotal = sizeof($arrayData) + 2 + $somaDiasFaltas;
    $planilha->mergeCells('A' . $ResTotal . ':E' . $ResTotal);
    $planilha->setCellValue('A' . $ResTotal, "RESULTADO DAS HORAS TRABALHADAS");
    $planilha->setCellValue('F' . $ResTotal, $verAgent->segundoHora($somarHoraTotal));
    $planilha->setCellValue('G' . $ResTotal, $verAgent->segundoHora($somarHoraExtraTotal));
    $planilha->setCellValue('H' . $ResTotal, $verAgent->segundoHora($horaExtra100));
    $planilha->setCellValue('I' . $ResTotal, $verAgent->segundoHora($somarHoraFaltaTotal));
    $planilha->getStyle("A" . $ResTotal . ":J" . $ResTotal)->getFont()->setBold(true);





    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $fileName);
    header('Cache-Control: max-age=0');
    ob_clean();
    flush();
    $writer = IOFactory::createWriter($excel, 'Xlsx');
    $writer->save('php://output');
    exit;


    ?>



</html>