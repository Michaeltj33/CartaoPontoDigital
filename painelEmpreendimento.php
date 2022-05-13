<!DOCTYPE html>
<html lang="pt-br">
<?php
include_once 'superServidor.php';

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

//setcookie("emp", null);
//setcookie("cpf", null);
//setcookie("agentes", null);

$listaTab = new classe();
$todosEmp =  $listaTab->listarTabela("tabelaEmpreendimento");

?>

<read>
    <title>Painel Administrativo-Funcionários</title>
    <link rel="Stylesheet" href="_css/estilo.css">

</read>

<body>
<!Formulário usado para desconectar o Administrador" –>
        <?php if (!empty($_COOKIE["adm"])) { ?>
            <form action="desconectado.php" method="POST" name="form2" id="ladoA">
                <div>
                    <input type="submit" name="DESLOGA_ADM" id="submit2" value="DESLOGAR">
                </div>
            </form>
        <?php } ?>
    <form action="painelAdministrativo.php" method="POST" class="form-style-6" name="form1" id="formAdm1">
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
        <hr size="10">
    </form>

    <form action="selecionarEmpreendimento.php" method="POST" class="form-style-6" name="form1" id="formAdm">
        <div id="overFlow">
            <ol>
                <h4>LISTA DOS EMPREENDIMENTOS</h4>
                <?php  for ($x = 0; $x < sizeof($todosEmp); $x++) {
                ?>
                    <li>
                        <div>
                            <input type="submit" name="<?php echo $todosEmp[$x]['nome'] ?>" id="submitUl" value="<?php echo strtoupper($todosEmp[$x]['nome']) ?>">
                        </div>
                    </li>

                <?php } ?>
            </ol>
        </div>
    </form>

</body>


</html>