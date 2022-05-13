<!DOCTYPE html>
<html lang="pt-br">
<?php
include_once 'superServidor.php';
if (empty($_COOKIE['emp'])) {
    header("Location:painelEmpreendimento.php");
} else if (!empty($_COOKIE['emp2'])) {
    $empreendimento = strtolower(str_replace(" ", "_", $_COOKIE['emp2'])); //Captura o Empreendimento escolhido

} else {
    $empreendimento = strtolower(str_replace(" ", "_", $_COOKIE['emp'])); //Captura o Empreendimento escolhido
}

$verAgent = new classe();

//Usado para verificar se o Site contem Cookies salvo
//e depois analisar se o Adm está logado.
if (empty($_COOKIE["adm"])) {
    header("Location:painelAdministrativo-senha.php");
    exit(0);
} else {
    $nomeAdm = $_COOKIE["adm"];
}

$pegacookies = explode("_", $_COOKIE['pdf']);
$pegaPdf = $pegacookies[1]; //pega PDF
$pegaId = $_COOKIE['id'];
$pdf2 = $verAgent->stringToPdf($pegaPdf);

$pegarValor = $verAgent->pegarPontoAgente("t_" . $pegaId . "_" . $pegaPdf);
$pegarValor = array_reverse($pegarValor);




$separador = explode("_", $_COOKIE['pdf']);
$pegaTudo = $verAgent->retornaAgenteCpf($pdf2, $empreendimento);
$pdfCompleto = $verAgent->stringToPdf($separador[1]);
$pegaCadastro = $verAgent->verificarAgenteCriado($empreendimento, $pdfCompleto);
$nomeCompleto = $pegaCadastro['nome'] . " " . $pegaCadastro['sobrenome'];
$ponto = $pegaTudo['ponto'];
$todosPontos = explode("_", $ponto);
$ponto_Find = $pegaTudo['pontoFind'];

$pontosFind = explode("_", $pegaTudo['pontoFind']);
if (empty($pontosFind[1])) {
    $pontosFind[1] = null;
}

$totaldeHora = $verAgent->somarhoraTrabalho($ponto);


if (isset($_COOKIE["data"])) {
    $pegaData = explode("_", $_COOKIE["data"]);
    $ano = $pegaData[1];
    $mes = $pegaData[0];        
} else {
    $pegaData = $verAgent->pegarData();
    $cortaData = explode("-", $pegaData);
    $ano = $cortaData[2];
    $mes = $cortaData[1];
}

if (isset($_COOKIE['data'])) {
    $dt = explode ("_", $_COOKIE['data']);
    $dataFinal = $verAgent->getTodosMes($dt[1]);
    $ms = $dt[0] - 1;
    $dt2 = $dataFinal[$ms][1];
    $data = explode("-", $dt2 . "-" . $dt[0] . "-" . $dt[1]);
    $dataMesAno = $data[1] . "-" . $data[2];   
} else {
    $data = explode("-", $verAgent->pegarData());
    $dataMesAno = $data[1] . "-" . $data[2];
}

$dataMesAno = $data[1] . "-" . $data[2];





if (empty($pegaTudo['email'])) {
    $pegaEmail = "Não Possui Email";
} else {
    $pegaEmail = $pegaTudo['email'];
}

//Usado para verificar se tem Ponto não batido corretamente
$pegaCookiesErro = 0;

if ($pegaCookiesErro != 0) {
    setcookie("inc", 0, 3600);
}

?>
<read>
    <title>Outros Registros do Funcionário</title>
    <link rel="Stylesheet" href="_css/estilo.css">

</read>

<body>
    <form action="registrarPonto.php" method="POST" class="form-style-text" name="form1" id="formAdm">
        <h1><b>SISTEMA DE PAINEL ADMINISTRATIVO</b></h1>
        <div>
            <img src="_imagens/TincDig.png" width="100px">
        </div>
        <div>
            <h2> Administrador </h2>
            <h3> <?php echo $nomeAdm ?> </h3>
        </div>
        <div>
            <input type="submit" name="VOLTAR_MENU" id="submit3" value="VOLTAR">
        </div>
        <hr size="5"><br>
        <h2>EMPREENDIMENTO: <?php echo strtoupper($_COOKIE['emp']) ?></h2>
        <br>
        <hr size="5">
        <div id="divTableFunc">
            <table>
                <tr>
                    <td>Funcionário</td>
                    <td><?php echo $nomeCompleto; ?></td>
                </tr>
                <tr>
                    <td>Função</td>
                    <td><?php echo $pegaTudo['funcao'] ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo $pegaEmail; ?></td>
                </tr>
                <tr>
                    <td>Cpf</td>
                    <td><?php echo $pegaTudo['cpf']; ?></td>
                </tr>

            </table>
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
        <hr size="20">
        <div>
            <a href="imprimirPdf.php" target="_blank"><img src="_imagens/pdf.png"></img></a>
            <a href="imprimirRelatorio.php" target="_blank"><img src="_imagens/excel.png"></img></a>
        </div>
        <hr size="20">
        <h3 id="centro">Escolha o ano e o mês para visualizar outros Registros</h3><br>

        <div>
            <b>Selecione o Mês</b><br>
            <select name="mes" id="selecionar">
                <option value="01">Janeiro</option>
                <option value="02">Fevereiro</option>
                <option value="03">Março</option>
                <option value="04">Abril</option>
                <option value="05">Maio</option>
                <option value="06">Junho</option>
                <option value="07">Julho</option>
                <option value="08">Agosto</option>
                <option value="09">Setembro</option>
                <option value="10">Outubro</option>
                <option value="11">Novembro</option>
                <option value="12">Dezembro</option>
            </select>
        </div>
        <div>
            <b>Selecione o Ano</b><br>
            <select name="ano" id="selecionar1">
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
                <option value="2027">2027</option>
                <option value="2028">2028</option>
                <option value="2029">2029</option>
                <option value="2030">2030</option>
            </select>
        </div>
        <div>
            <input type="submit" name="PESQUISA_DATA" id="submitUl2" value="PESQUISAR">
        </div>

        <hr size="20">
        <div>
            <?php            
            $arrayData = array();
            $horaExtra100 = 0;
            $somarHoraTotal = 0;
            $somarHoraExtraTotal = 0;
            $somarHoraFaltaTotal = 0;
            $mostrarTodosMes = $verAgent->getTodosMes($data[2]);
            $mesAtual = $mostrarTodosMes[$data[1] - 1][1];
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


            ?>

            <div id="overFlow-text">
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
                    <tr>
                        <h3><?php
                            // copiar para o agente                        
                            for ($z = $mesAtual; $z > 0; $z--) {

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
                                                        var_dump("aki1");  
                                                    } else {
                                                        $pgSomar = $verAgent->somaHoraTotal($somar, $ponto_Find);
                                                        var_dump("aki2");                                                       
                                                    }
                                                    $mostrar100 = $pgSomar[0] + $pgSomar[1] + $pgSomar[2];
                                                    $horaExtra100 += $pgSomar[0];
                                                    $horaExtra100 += $pgSomar[1];
                                                    $horaExtra100 += $pgSomar[2];
                                                    $pgSomar[2] = 0; //uso para mostrar apenas o Ex100%
                                                    var_dump("aki3");  

                                                } else {
                                                    $pgSomar = $verAgent->somaHoraTotal($somar, $ponto);
                                                    $somarHoraTotal += $pgSomar[0];
                                                    $somarHoraExtraTotal += $pgSomar[1];
                                                    $somarHoraExtraTotal += $pgSomar[2];
                                                    var_dump("aki4");  
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
                                            } else {
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
                                        } else {
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


                            if ($somarHoraExtraTotal > 187200) {
                                $horaExtra100 = $somarHoraExtraTotal - 187200;
                                $somarHoraExtraTotal = 187200;
                            }
            ?>
            </h3>
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
                </table>
            </div>
        </div>
    </form>
    <script>
        document.getElementById("selecionar1").value = "<?php echo $ano ?>";
        document.getElementById("selecionar").value = "<?php echo $mes ?>";
    </script>

</body>


</html>