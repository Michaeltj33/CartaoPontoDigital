<?php
include_once 'superServidor.php';
$empreendimento = strtolower(str_replace(" ", "_", $_COOKIE['emp'])); //Captura o Empreendimento escolhido

if (empty($_COOKIE["adm"])) {
    header("Location:painelAdministrativo-senha.php");
    exit(0);
} else {
    $nomeAdm = $_COOKIE["adm"];
}
$cadastro = new classe();
$separador = explode("_", $_COOKIE['pdf']);
$pdfCompleto = $cadastro->stringToPdf($separador[1]);
$pegaCadastro = $cadastro->verificarAgenteCriado($empreendimento, $pdfCompleto);
$todosPontos = explode("_", $pegaCadastro['ponto']);
$todosPontosFind = explode("_", $pegaCadastro['pontoFind']);

$nomeOriginal = $pegaCadastro['nome'];
$sobrenomeOriginal = $pegaCadastro['sobrenome'];
$cpfOriginal = $pegaCadastro['cpf'];
$nascimentoOriginal = $pegaCadastro['nascimento'];
$ponto1Original = $todosPontos[0];
$ponto2Original = $todosPontos[1];
$ponto3Original = $todosPontos[2];
$ponto4Original = $todosPontos[3];
$pontoFind1Original = $todosPontosFind[0];
$pontoFind2Original = $todosPontosFind[1];
$emailOriginal = $pegaCadastro['email'];
$funcaoOriginal = $pegaCadastro['funcao'];

$nome = $cadastro->removeE($_POST["nome"]);
$sobrenome = $cadastro->removeE($_POST['sobrenome']);
$senha = "Mudar@123";
$funcao = $_POST['funcao'];
$ponto1 = $_POST['ponto1'];
$ponto2 = $_POST['ponto2'];
$ponto3 = $_POST['ponto3'];
$ponto4 = $_POST['ponto4'];

$pontoFind1 = $_POST['pontoFind1'];
$pontoFind2 = $_POST['pontoFind2'];

$cpf = $cadastro->removeE($_POST['cpf']);
$nascimento = $_POST['nascimento'];
$tabela = $empreendimento;
$id = $_COOKIE['id'];
$email = $_POST['email'];
$verAtualizou = "";

$textoCompleto = "O Cadastro do " . $pegaCadastro['nome'] . " " . $pegaCadastro['sobrenome'] . " Foi Modificado os Seguintes itens:";

if ($nome != $nomeOriginal) {
    $verAtualizou = "atualizou";
    $textoCompleto .= " - Nome:" . $nomeOriginal . " Mudou Para:" . $nome;
}
if ($sobrenome != $sobrenomeOriginal) {
    $verAtualizou = "atualizou";
    $textoCompleto .= " - Sobrenome:" . $sobrenomeOriginal . " Mudou Para:" . $sobrenome;
}

if ($funcao != $funcaoOriginal) {
    $verAtualizou = "atualizou";
    $textoCompleto .= " - Função:" . $funcaoOriginal . " Mudou Para:" . $funcao;
}

if ($cpf != $cpfOriginal) {
    $verAtualizou = "atualizou";
    $textoCompleto .= " - Cpf:" . $cpfOriginal . " Mudou Para:" . $cpf;
}
if ($nascimento != $nascimentoOriginal) {
    $verAtualizou = "atualizou";
    $textoCompleto .= " - Nascimento:" . $nascimentoOriginal . " Mudou Para:" . $nascimento;
}

if ($email != $emailOriginal) {
    $verAtualizou = "atualizou";
    if($emailOriginal == ""){
    $textoCompleto .= " - E-mail Criado:" . $email;
    }else{
    $textoCompleto .= " - E-mail:" . $emailOriginal . " Mudou Para:" . $email;
    }
}

if ($ponto1 != $ponto1Original) {
    $verAtualizou = "atualizou";
    $textoCompleto .= " - PontoE1:" . $ponto1 . " Mudou Para:" . $ponto1Original;
}
if ($ponto2 != $ponto2Original) {
    $verAtualizou = "atualizou";
    $textoCompleto .= " - PontoS1:" . $ponto2 . " Mudou Para:" . $ponto2Original;
}
if ($ponto3 != $ponto3Original) {
    $verAtualizou = "atualizou";
    $textoCompleto .= " - PontoE2:" . $ponto3 . " Mudou Para:" . $ponto3Original;
}
if ($ponto4 != $ponto4Original) {
    $verAtualizou = "atualizou";
    $textoCompleto .= " - PontoS2:" . $ponto4 . " Mudou Para:" . $ponto4Original;
}

if ($pontoFind1Original != $pontoFind1) {
    $verAtualizou = "atualizou";
    $textoCompleto .= " - PontoFindE1:" . $pontoFind1Original . " Mudou Para:" . $pontoFind1;
}
if ($pontoFind2Original != $pontoFind2) {
    $verAtualizou = "atualizou";
    $textoCompleto .= " - PontoFindS1:" . $pontoFind2Original . " Mudou Para:" . $pontoFind2;
}


if ($verAtualizou == "atualizou") {
    $pontoFinal = $ponto1 . "_" . $ponto2 . "_" . $ponto3 . "_" . $ponto4;
    $pontoFinalFind = $pontoFind1 . "_" . $pontoFind2;    
    $cadastro->atualizarTudo($tabela, $nome, $sobrenome, $funcao, $email, $cpf, $nascimento, $pontoFinal, $pontoFinalFind, $id);
    $cadastro->registroMovimentacao("Editar", $textoCompleto);
    setcookie("aviso", $id, time() + 3600);

    header("Location:painelAdministrativoEmpFunc.php");
} else {
    header("Location:modificarCadastro-painel.php");
}
