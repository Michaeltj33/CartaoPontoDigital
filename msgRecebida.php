<!DOCTYPE html>
<html lang="pt-br">
<?php
include_once 'superServidor.php';
$verAgent = new classe();

//Usado para verificar se o Site contem Cookies salvo
//e depois analisar se o Adm está logado.
if (empty($_COOKIE["adm"])) {
    header("Location:painelAdministrativo-senha.php");
    exit(0);
} else {
    $nomeAdm = $_COOKIE["adm"];
}

if (isset($_POST['CRIAR_EMP'])) {    
    header("Location:criarEmpreendimento.php");
    exit(0);
}
//retorna todo Registro de movimentação do Administrador 
$meuRegistro = $verAgent->retornaListaTabela("tabelaMovimentacao", "administrador", $nomeAdm);


if (empty($_COOKIE["data"])) {
    $pegaData = explode("-", $verAgent->pegarData());
    $ano = $pegaData[2];
    $mes = $pegaData[1];
} else {
    $pg = explode("_", $_COOKIE['data']);
    $mes = $pg[0];
    $ano = $pg[1];
}

$pegarTabelaLida = $verAgent->lerMsgLidaAdm("tabelaMsg", $nomeAdm);

$verAgent->listarConteudo($pegarTabelaLida);

?>
<read>
    <title>Meus Registros</title>
    <link rel="Stylesheet" href="_css/estilo.css">

</read>

<body>
    <form action="registrarPonto.php" method="POST" class="form-style-text" name="form1" id="formAdm">
        <h1><b>MEUS REGISTROS</b></h1>
        <div>
            <img src="_imagens/TincDig.png" width="100px">
        </div>
        <div>
            <h2> Administrador </h2>
            <h3> <?php echo $nomeAdm ?> </h3>
        </div>
        <div>
            <input type="submit" name="VOLTAR_MENU_EMP_REG" id="submit3" value="VOLTAR">
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
            <input type="submit" name="PESQUISA_DATA_ADM" id="submitUl2" value="PESQUISAR">
        </div>      
       
        <hr size="20">
        <div>
            <?php            
            $arrayData = array();
            $dataMesAno = $mes . "-" . $ano;
            for ($x = 0; $x < sizeof($meuRegistro); $x++) {
                if (strpos($meuRegistro[$x]['data'], $dataMesAno) !== false) { //verifica o mês e ano
                    for ($y = 1; $y <= 31; $y++) {
                        if ($y < 10) {
                            $y1 = "0" . $y;
                        } else {
                            $y1 = $y;
                        }
                        if ($meuRegistro[$x]['data'] == $y1 . "-" . $dataMesAno) {
                            $arrayData[$y1][] = [$meuRegistro[$x]['registro'], $meuRegistro[$x]['processo'], $meuRegistro[$x]['empreendimento']];
                        }
                    }
                }
            }
            ksort($arrayData); ?>
            <div id="overFlow-text">
                <ul type="square">
                    <h3><?php
                        for ($z = 31; $z > 0; $z--) {

                            if ($z < 10) {
                                $z1 = "0" . $z;
                            } else {
                                $z1 = $z;
                            }
                            $pegarNome = "";
                            if (isset($arrayData[$z1][0])) {
                                $pegarNome = "Data:" . $z1 . "-" . $dataMesAno . "   -   Tipo de processo: " . $arrayData[$z1][0][1] . "  - Empreendimento:" . $arrayData[$z1][0][2] . "<br>";                              
                                for ($zz = 0; $zz < sizeof($arrayData[$z1]); $zz++) {
                                    $pegarNome .= "<ul><li>" . $arrayData[$z1][$zz][0] . "</li></ul> <br> ";                                   
                                }
                              

                                $pegarNome .=  "<hr>";
                            }



                            if ($pegarNome != "") {
                        ?> <li> <?php
                                echo $pegarNome; //Usado para Mostrar na tela                                                     
                            }
                        }
                                ?> </li>
                    </h3>
                </ul>
            </div>
        </div>
    </form>

    <script>
        document.getElementById("selecionar1").value = "<?php echo $ano ?>";
        document.getElementById("selecionar").value = "<?php echo $mes ?>";
    </script>

</body>


</html>