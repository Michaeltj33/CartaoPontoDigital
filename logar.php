<!DOCTYPE html>
<html lang="pt-br">
<read>
    <title>Verificando Senha</title>
    <link rel="Stylesheet" href="_css/estilo.css">

</read>
<?php

use FontLib\Table\Type\head;

include_once 'superServidor.php';
$logar = new classe();

$cpf = "";
$tabelaEmpr = "";

if (!empty($_COOKIE['cpf'])) {
    $tb = explode("_", $_COOKIE['cpf']);
    $dadosCompletos = $logar->verificarCpfAgenteCriado($tb[1], $tb[0]);
} else {
    $pgTabela = $logar->verFuncCadastrado($_POST['cpf']);
    $dadosCompletos = $logar->verificarCpfAgenteCriado($pgTabela, $_POST['cpf']);
}

if(isset($_POST['senhaM'])){    
    if($dadosCompletos['senha'] == $_POST['senhaM']){
        header("Location:agente.php");
    }else {
        header("Location:suaSenha.php");
        setcookie("aviso", "A Senha está incorreta");
    }
    exit(0);
}


if (!empty($_COOKIE['cpf'])) {
    $pegaCookies = explode("_", $_COOKIE['cpf']);
    $cpf = $pegaCookies[0];
    $tabelaEmpr = $pegaCookies[1];
}

if (!empty($_COOKIE['aviso'])) {
    setcookie("aviso", null);
}


$aviso = "";
$aviso1 = "";
$verFuncCriado = "";
$tabela = "agentes"; // manter esse nome
$tempo = time() + (3600 * 24 * 30 * 3);
if (!empty($_POST['senha1']) && !empty($_POST['senha2'])) {
    $senha1 = $_POST['senha1'];
    $senha2 = $_POST['senha2'];
} else {
    $senha1 = "1";
    $senha2 = "2";
}
$senhaPr = "Mudar@123";

if (!empty($_POST['cpf'])) {
    $cpf = $_POST['cpf'];
} else {
    $pegGeral = explode("_", $_COOKIE['cpf']);
    $cpf = $pegGeral[0];
}
$verFuncCriado = $logar->verFuncCadastrado($cpf);
if ($verFuncCriado != "") {
    $dadosComp = $logar->retornaAgenteCpf($cpf, strtolower($verFuncCriado));
    if ($dadosComp != "") {
        if ($dadosComp['senha'] == $senhaPr) {
            if ($senha1 == "1" && $senha2 == "2") {
                setcookie("cpf", $cpf . "_" . $verFuncCriado, $tempo);
                header("Location:index.php");
            } else if ($senha1 == $senha2) {
                $id = $dadosCompletos['id_agente'];
                $tabelaId = $pegaCookies[1];               
                $logar->mudarSenha($tabelaId, $senha1, $id);
                setcookie($tabela, $dadosComp['id_agente'] . "_" . $verFuncCriado, $tempo);
                header("Location:agente.php");
            } else {
                setcookie("aviso", "As Senhas estão diferentes");
                header("Location:index.php");
            }
        } else {
            $id = $dadosCompletos['id_agente'];
            $tabelaId = $pegaCookies[1];
            setcookie($tabela, $dadosComp['id_agente'] . "_" . $verFuncCriado, $tempo);
            setcookie("cpf", $cpf . "_" . $verFuncCriado , $tempo);
            header("Location:suaSenha.php");
        }
    } else {
        setcookie("cpf", $cpf . "_" . $verFuncCriado);
        header("Location:index.php");
        exit(0);
    }
} else {
    $aviso = "Usuário Não encontrado";
?>
    <form action=desconectado.php method="POST" class="form-style-6" name="form4" id="form4">
        <div>
            <img src="_imagens/TincDig.png" width="100px">
        </div>
        <div>
            <b style="color: #eb2113;"> <?php echo $aviso ?> </b>
        </div>
    </form>
<?php }


?>
<form action="index.php" class="form-style-6" name="form2" id="form2">
    <div>
        <input type="submit" id="submit2" value="VOLTAR">
    </div>

</form>


</html>