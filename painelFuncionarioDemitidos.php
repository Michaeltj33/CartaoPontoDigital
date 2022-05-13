<!DOCTYPE html>
<html lang="pt-br">
<?php
include_once 'superServidor.php';
if(empty($_COOKIE['emp'])){
    header("Location:painelEmpreendimento.php");
}else{
    $empreendimento = strtolower(str_replace(" ", "_",$_COOKIE['emp']));//Captura o Empreendimento escolhido
}

setcookie("emp2", $empreendimento . "_f_excluido");
//Usado para verificar se o Site contem Cookies salvo
//e depois analisar se o Adm está logado.
if (empty($_COOKIE["adm"])) {
    header("Location:painelAdministrativo-senha.php");
    exit(0);
} else {
    $nomeAdm = $_COOKIE["adm"];
}
if(!empty($_COOKIE['pdf'])){
    setcookie("pdf", null);
}


$listaFunc = new classe();
$todosFunc =  $listaFunc->listarTabela(strtolower($empreendimento. "_f_excluido"));

?>

<read>
    <title>Lista de Funcionários Demitidos</title>
    <link rel="Stylesheet" href="_css/estilo.css">

</read>

<body>
<!Formulário usado para desconectar o Administrador" –>
        <?php if (!empty($_COOKIE["adm"])) { ?>
            <form action="desconectado.php" method="POST" class="lado" name="form2" id="ladoA">
                <div>
                    <input type="submit" name="DESLOGA_ADM" id="submit2" value="DESLOGAR">
                </div>
            </form>
        <?php } ?>
    <form action="painelAdministrativoEmpFunc.php" method="POST" class="form-style-6" name="form1" id="formAdm">
        <h1><b>SISTEMA DE PAINEL ADMINISTRATIVO</b></h1>
        <div>
            <img src="_imagens/TincDig.png" width="100px">
        </div>
        <div>
            <h2> Administrador </h2>
            <h3> <?php echo $nomeAdm ?> </h3>
        </div>
        <div>
            <input type="submit" id="submit3" value="VOLTAR">
        </div>       
        <hr size="5"><br>
        <h2>EMPREENDIMENTO: <?php echo strtoupper($_COOKIE['emp']) ?></h2>
        <br>
        <hr size="5">
    </form>

    <form action="listaspontoFuncionario.php" method="POST" class="form-style-6" name="form1" id="formAdm">
        <div id="overFlow">        
            <ol>
                <h4>LISTA DE FUNCIONÁRIOS DEMITIDOS</h4>
                <?php for ($x = 0; $x < sizeof($todosFunc); $x++) {
                ?>
                    <li>
                        <div>
                            <input type="submit" name="<?php echo $todosFunc[$x]['nome'] ?>" id="submitUl" value="<?php echo $todosFunc[$x]['nome'] . " " . $todosFunc[$x]['sobrenome'] . "  CPF:" . $todosFunc[$x]['cpf']  ?>">
                        </div>
                    </li>

                <?php } ?>
            </ol>
        </div>
    </form>

</body>


</html>