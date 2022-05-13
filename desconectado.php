<?php
include_once "superServidor.php";
$empreendimento = strtolower($_COOKIE['emp']);//Captura o Empreendimento escolhido

if(isset($_POST['DESLOGA_ADM'])){
  setcookie("adm",null);
  header("Location:painelAdministrativo-senha.php");
  exit(0);
}else if(isset($_POST['SAIR_FUNC'])){
 setcookie("cpf", null);
 setcookie("agentes", null);
 setcookie("nome", null);
 setcookie("aviso", null);
 header("Location:index.php");
}

$Logador = new classe();
if (!empty($_COOKIE["nome"])) { 
$nome = $_COOKIE["nome"];
}
if (!empty($_POST["senha0"])) { 
$senha0 = $_POST['senha0'];
}
if (!empty($_POST["senha1"])) { 
$senha1 = $_POST['senha1'];
}
if (!empty($_POST["senha2"])) { 
$senha2 = $_POST['senha2'];
}
$tempo = time() + (3600 * 24 * 30 * 3);
$tabela = $empreendimento;

if (!empty($_COOKIE["nome"])) { 
  $verId = $Logador->logarConsultor($nome, $senha0, $tabela);
  if ($verId == "") {
    setcookie("aviso", "A-Senha-atual-esta-diferente.", $tempo);
    setcookie("senha", $senha0, $tempo);
    header("Location:logar.php");
  } else if ($senha1 == $senha2) {
    $Logador->mudarSenha($tabela, $senha1, $verId);
    $verificar = $Logador->logarConsultor($nome, $senha1, $tabela);
    setcookie($tabela, $verificar, $tempo);
    header("Location:agente.php");
  }else {
    setcookie("aviso", "As-Senhas-estao-diferentes.", $tempo);
    setcookie("senha", $senha0, $tempo);
    header("Location:logar.php");
  }
  exit(0);
}
