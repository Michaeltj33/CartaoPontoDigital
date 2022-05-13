<?php
include_once "superServidor.php";
$empreendimento = strtolower(str_replace(" ", "_",$_COOKIE['emp']));//Captura o Empreendimento escolhido
$registrador = new classe();

if (isset($_POST['drone']) == "txtInfo") {
   $msg = $_POST['msg'];
   $data = $registrador->pegarData();
   $hora = $registrador->pegarHora();
   $adm = $_COOKIE['adm'];
   $pdf = explode("_", $_COOKIE['pdf']);
   $cpf = $registrador->stringToPdf($pdf[1]);
   $cadastroC = $registrador->verificarCpfAgenteCriado($empreendimento, $cpf);
   $registrador->mandaMsg($cadastroC['nome'], $cadastroC['sobrenome'], $cadastroC['cpf'], $empreendimento, $data, $hora, $adm, $msg);
   $registrador->registroMovimentacao("Mandar_Msg", "Mensagem Enviada para: Nome:" . $nome . " " . $sobrenome . "-Mensagem:" . $msg);
   header("Location:mandarMsgFuncionario.php");
}else if(isset($_POST['LER_MSG'])){
    $lerMsg = $_COOKIE['lerMsg'];
    setcookie("lerMsg", null);
    $registrador->confirmarMsgLida($lerMsg);
    header("Location:agente.php");
} else {
    header("Location:painelAdministrativoEmpFunc.php");
}
