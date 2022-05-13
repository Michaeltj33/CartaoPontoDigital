<?php
include_once 'superServidor.php';
$empTotal = new classe();

$pegaPost = implode($_POST);
$empreendimento = strtolower(str_replace(" ","_",$pegaPost));

$verTabela = $empTotal->verificarTabelaExiste($empreendimento);

if($verTabela == ""){
    $empTotal->criarTabelaEmpreendimentoFunc($empreendimento);
}

setcookie("emp", $pegaPost);
header("Location:painelAdministrativoEmpFunc.php");

?>