<!DOCTYPE html>
<html lang="pt-br">
<?php
include_once 'superServidor.php';
if (empty($_COOKIE['emp'])) {
    header("Location:painelEmpreendimento.php");
} else {
    $empreendimento = strtolower(str_replace(" ", "_", $_COOKIE['emp'])); //Captura o Empreendimento escolhido
}

$retornaTudo = new classe();

//Usado para verificar se o Site contem Cookies salvo
//e depois analisar no servidor para buscar as informações do consultor
if (empty($_COOKIE["adm"])) {
    header("Location:painelAdministrativo-senha.php");
    exit(0);
} else {
    $nomeAdm = $_COOKIE["adm"];
}

if (!empty($_COOKIE["aviso"])) {
    $arrayAgente = $_COOKIE["aviso"];
    setcookie("aviso", null);
}



$separador = explode("_", $_COOKIE['pdf']);
$pdfCompleto = $retornaTudo->stringToPdf($separador[1]);
$pegaCadastro = $retornaTudo->verificarAgenteCriado($empreendimento, $pdfCompleto);

$nomeCompleto = $pegaCadastro['nome'] . " " . $pegaCadastro['sobrenome'];
$todosPontos = explode("_", $pegaCadastro['ponto']);

$pontosFind = explode("_", $pegaCadastro['pontoFind']);
if (empty($pontosFind[1])) {
    $pontosFind[1] = "";
}

$dataAtual = $retornaTudo->pegarDataBanco();
$pegaHora = $retornaTudo->pegarMinutoHora();

if (empty($pegaCadastro['email'])) {
    $pegaEmail = "Não Possui Email";
} else {
    $pegaEmail = $pegaCadastro['email'];
}

?>
<read>
    <title>Painel de Envio de Mensagens</title>
    <link rel="Stylesheet" href="_css/estilo.css">

</read>

<body>


    <form action=registrarMensagem.php method="POST" class="form-style-6" name="form1" id="formEditar">
        <h1><b>PAINEL DE ENVIO DE MENSAGENS</b></h1>
        <div>
            <img src="_imagens/TincDig.png" width="100px">
        </div>
        <div>
            <h2> Administrador </h2>
            <h3> <?php echo $nomeAdm ?> </h3>
        </div>
        <div>
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
                        <td><?php echo $pegaCadastro['funcao'] ?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?php echo $pegaEmail; ?></td>
                    </tr>
                    <tr>
                        <td>Cpf</td>
                        <td><?php echo $pegaCadastro['cpf']; ?></td>
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
            <hr size=10>
            <div>
                <b>Escreva sua Mensagem :</b>
                <textarea id="msg" name="msg" maxlength="200" onkeyUp="redigitarTodasMsg()" style="resize: none" rows="4" cols="50" required></textarea>
            </div>
            <br>
            <p>Select a Opção:</p>

            <div>
                <input type="radio" id="txtInfo" name="drone" value="txtInfo" checked>
                <b for="huey">Texto informativo</b>
                <textarea id="textoInfo" name="textoInfo" onkeydown="vazio('textoInfo')" onkeyup="vazio('textoInfo')" style="resize: none" onmousedown="check('txtInfo')" rows="4" cols="50"></textarea>
            </div>
            <br>

            <div>
                <input type="radio" id="txtAlt" name="drone" value="txtAlt">
                <b for="dewey">Texto alternativo</b>
                <textarea id="textoAlt" name="textoAlt" onkeydown="vazio('textoAlt')" onkeyup="vazio('textoAlt')" style="resize: none" onmousedown="check('txtAlt')" style="resize: none" rows="4" cols="50"></textarea>
            </div>
            <br>
            <div>
                <input type="radio" id="txtResp" name="drone" value="txtResp">
                <b for="louie">Texto com Respostas</b>
                <textarea id="textoResp" name="textoResp" onkeydown="vazio('textoResp')" onkeyup="vazio('textoResp')" style="resize: none" onmousedown="check('txtResp')" style="resize: none" rows="4" cols="50"></textarea>
            </div>
            <br>
            <div>
                <input type="submit" class="form-style-6" id="submitUl2" name="MANDAR_MENSAGENS" value="MANDAR MENSAGENS" onclick="desabilitarBtn()" onkeyup="desabilitarBtn()">
            </div>

    </form>
    <hr size=20>

    <form action="listaspontoFuncionario.php?" name="form3" class="form-style-6" id=formAlinhar>
        <div>
            <input type="submit" id="submit3" value="VOLTAR">
        </div>
        <hr size=20>
    </form>
</body>

<script> 

    function desabilitarBtn() {
        document.getElementById("submitUl2").id = "submitUl3";
    }

    function redigitarTodasMsg() {
        var texto = document.getElementById("msg").value;
        document.getElementById("textoInfo").value = texto;
        document.getElementById("textoAlt").value = texto;
        document.getElementById("textoResp").value = texto;

    }

    function check(ck) {
        document.getElementById(ck).checked = true;
    }

    function vazio(vz) {
        var texto = document.getElementById("msg").value;
        document.getElementById(vz).value = texto;
    }
</script>


</html>