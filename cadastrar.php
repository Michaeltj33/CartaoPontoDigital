<?php
include_once 'superServidor.php';
$empreendimento = strtolower(str_replace(" ", "_", $_COOKIE['emp'])); //Captura o Empreendimento escolhido
if (empty($_COOKIE["adm"])) {
    header("Location:painelAdministrativo-senha.php");
} else {
    $nomeAdm = $_COOKIE["adm"];
}
$cadastro = new classe();
$nome = $cadastro->removeE($_POST["nome"]);
$sobrenome = $cadastro->removeE($_POST['sobrenome']);
$senha = "Mudar@123";
$cpf = $cadastro->removeE($_POST['cpf']);
$nascimento = $_POST['nascimento'];
$tabela = $empreendimento;
$func = $_POST['funcao'];
$ponto = $_POST['ponto1'] . "_" . $_POST['ponto2'] . "_" . $_POST['ponto3'] . "_" . $_POST['ponto4'];

if(empty($_POST['pontoFind1']) || empty($_POST['pontoFind2'])){
    $pontoFind = "";
}else{
    $pontoFind = $_POST['pontoFind1'] . "_" . $_POST['pontoFind2'];
}

if (empty($_POST['email'])) {
    $email = "";
} else {
    $email = $_POST['email'];
}

$retorno = $cadastro->verificarConsultor($cpf, $tabela);

if ($retorno == "") {

    $cadastro->cadastrarConsultor($nome, $sobrenome,$func, $senha, $email, $cpf, $nascimento, $ponto, $pontoFind, $nomeAdm, $tabela);
    $id = $cadastro->logarConsultor($nome, $senha, $tabela);
    $cadastro->registroMovimentacao("Cadastrar", "Funcionário Cadastrado:" . $nome . " " . $sobrenome . "-Cpf: " . $cpf . "-Nascimento:" . $nascimento);
    setcookie("aviso", $id, time() + 3600);
    header("Location:cadastrarAgente.php");
} else {
    echo "Consultor ja foi cadastrado";
}
