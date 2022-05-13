<?php
include_once 'superServidor.php';
$excFunc = new classe();

if (isset($_POST['REMOVER_FUNC'])) {   
$pegaCookies = explode("_", $_COOKIE['FuncionarioAdm']);
$pdf2 = $excFunc->stringToPdf($pegaCookies[0]);
$empreendimento = $pegaCookies[1];
$excFunc->moverFuncExcluido($pdf2, $empreendimento, $_POST['data']);
header("Location:painelAdministrativoPontos.php");

//$cadastro->registroMovimentacao("Removido", );

}else if(isset($_POST['VOLTAR'])){
    header("Location:listaspontoFuncionario.php");
    exit(0);
}


?>