<?php
include_once "superServidor.php";
$empreendimento = strtolower(str_replace(" ", "_",$_COOKIE['emp']));//Captura o Empreendimento escolhido
$registrador = new classe();


if (isset($_POST['ACESSO_FUNC2'])) {
    setcookie("cpf", null);
    header("Location:index.php");
} else if (isset($_POST['CADASTRO_FUNC'])) {
    header("Location:cadastrarAgente.php");
} else if (isset($_POST['VOLTAR_EMP'])) {
    header("Location:painelEmpreendimento.php");
} else if (isset($_POST['PAINEL_EMP'])) {
    header("Location:painelAdministrativoPontos.php");
}else if (isset($_POST['PAINEL_DEMINIT_FUNC'])){
    header("Location:painelFuncionarioDemitidos.php");
} else {
    header("Location:painelAdministrativoEmpFunc.php");
}
