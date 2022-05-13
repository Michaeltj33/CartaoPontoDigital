<!DOCTYPE html>
<html lang="pt-br">    
<?php
include_once 'superServidor.php';
$verAgent = new classe();
if (empty($_COOKIE["adm"])) {
    header("Location:painelAdministrativo-senha.php");
} else {
    $nomeAdm = $_COOKIE["adm"];
}
if (empty($_COOKIE['emp'])) {
    header("Location:painelEmpreendimento.php");
} else if (!empty($_COOKIE['emp2'])) {
    $empreendimento = strtolower(str_replace(" ", "_", $_COOKIE['emp2'])); //Captura o Empreendimento escolhido

} else {
    $empreendimento = strtolower(str_replace(" ", "_", $_COOKIE['emp'])); //Captura o Empreendimento escolhido
}


$separador = explode("_", $_COOKIE['pdf']);

$pdfCompleto = $verAgent->stringToPdf($separador[1]);
$pegaCadastro = $verAgent->verificarAgenteCriado($empreendimento, $pdfCompleto);

//Usado para verificar se o Site contem Cookies salvo
//e depois analisar no servidor para buscar as informações do consultor

$dataAtual = $verAgent->pegarData();

?>

<read>
    <title>Resetar Senha</title>
    <link rel="Stylesheet" href="_css/estilo.css">

</read>

<body>
    <!Formulário usado para desconectar o Administrador" –>
        <?php if (!empty($_COOKIE["adm"])) { ?>
            <form action="desconectado.php" method="POST" name="form2" id="ladoA">
                <div>
                    <input type="submit" name="DESLOGA_ADM" id="submit2" value="DESLOGAR">
                </div>
            </form>
        <?php } ?>

        <!Formulário usado para registrar o Ponto "registrarPonto.php" –>

            <form action=registrarPonto.php method="POST" name="form3" class="form-style-6" id="formAlinhar">
                <h1><b>SISTEMA DE PAINEL ADMINISTRATIVO</b></h1>
                <div>
                    <img src="_imagens/TincDig.png" width="100px">
                </div>
                <div>
                    <h2> Administrador </h2>
                    <h3> <?php echo $nomeAdm ?> </h3>
                </div>
                <hr size="10">
                <h3>VOCÊ TEM CERTEZA QUE DESEJA RESETAR A SENHA?</h3>
                <b>A Senha do <?php echo $pegaCadastro['nome'] ?> sera excluída, Tendo que criar uma nova senha assim que logar.</b>
                <br><br>
                <div>
                    <b id="lt"></b>
                    <input type="submit" name="DELETAR_SENHA" id="submitregistrar" value="RESETAR" onclick="desabilitarBtn()">
                </div>
                <div>
                    <input type="submit" name="CANCELAR_DELETAR_SENHA" id="submitcancelar" value="CANCELAR">
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