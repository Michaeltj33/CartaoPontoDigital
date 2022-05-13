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
$dataAtual = $verAgent->pegarData();

$contarData = $verAgent->pegarSomaDataTabela($tabelaAgente, $dataAtual);

if ($contarData == 4) {
    header("Location:agente.php");
    setcookie("aviso", "LIMITE DE REGISTRO ULTRAPASSADO");
    exit(0);
}

$pegarValor = array_reverse($pegarValor);

?>

<read>
    <title>Sistema de Login</title>
    <link rel="Stylesheet" href="_css/estilo.css">

</read>

<body>

    <form action="desconectado.php" class="lado" name="form2" id="form2">
        <div>
            <input type="submit" id="submit2" value="DESLOGAR">
        </div>
    </form>

    <form class="form-style-6" name="form1" id="form1">
        <h1><b>SISTEMA DE LOGIN</b></h1>
        <div>
            <img src="_imagens/TincDig.png" width="100px">
        </div>
        <div>
            <h2><?php echo $Consultor['nome'] . " " . $Consultor['sobrenome'] ?></h2>
        </div>
    </form>
    <!Formulário usado para registrar o Ponto "registrarPonto.php" –>

        <form action=registrarPonto.php method="POST" name="form3" class="form-style-6" id="formAlinhar">
            <h3>VOCÊ TEM CERTEZA QUE DESEJA REGISTRAR SEU PONTO?</h3>
            <div>
                <b id="lt"></b>
                <input type="submit" name="REGISTRAR" id="submitregistrar" value="REGISTRAR" onclick="desabilitarBtn()">
            </div>
            <div>
                <input type="submit" name="CANCELAR" id="submitcancelar" value="CANCELAR">
            </div>
        </form>
</body>
<script>
    function desabilitarBtn() {
        document.getElementById("submitregistrar").id = "submitregistrar2";
        document.getElementById("submitregistrar").width = 10;
        document.getElementById("submitregistrar").border = 10;
        document.getElementById("lt").innerHTML = "REGISTRANDO PONTO...";
    }
</script>

</html>