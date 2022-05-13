<!DOCTYPE html>
<html lang="pt-br">
<?php
include_once 'superServidor.php';
if (!empty($_COOKIE["emp"])) {
    $empreendimento = strtolower(str_replace(" ", "_", $_COOKIE['emp'])); //Captura o Empreendimento escolhido
}
$pClasse = new classe();

//Usado para verificar se o Site contem Cookies salvo
//e depois analisar se o Adm está logado.
if (empty($_COOKIE["adm"])) {
    header("Location:painelAdministrativo-senha.php");
} else {
    $nomeAdm = $_COOKIE["adm"];
}

if (!empty($_COOKIE["aviso"])) {
    $arrayAgente = $pClasse->retornaAgenteCriado($_COOKIE["aviso"], $empreendimento);
    setcookie("aviso", null);
}

//serve para apagar o empreendimento do Cookies
if (!empty($_COOKIE["emp"])) {
    setcookie("emp", null);
}

//serve para apagar o Mês e o Ano da pesquisa
if (!empty($_COOKIE["data"])) {
    setcookie("data", null); 
}


?>
<read>
    <title>Painel Administrativo</title>
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

        <form action="registrarPonto.php" method="POST" class="form-style-6" name="form1" id="ladoB">
            <h1><b>SISTEMA DE PAINEL ADMINISTRATIVO</b></h1>
            <div>
                <img src="_imagens/TincDig.png" width="100px">
            </div>
            <div>
                <h2> Administrador </h2>
                <h3> <?php echo $nomeAdm ?> </h3>
            </div>
            <hr size="10">
            <ul>
                <li>
                    <div>
                        <input type="submit" name="ACESSO_FUNC" id="submitUl" value="ACESSAR SISTEMA DE LOGIN DOS FUNCIONÁRIOS">
                    </div>
                </li>
                <li>
                    <div>
                        <input type="submit" name="PAINEL_EMP" id="submitUl" value="ACESSAR PAINEL DOS EMPREENDIMENTOS">
                    </div>
                </li>
            </ul>
        </form>

        <form action="meusRegistros.php" method="POST" class="form-style-6" name="form2" id="formAdm2">
            <ul>
                <li>
                    <div>
                        <input type="submit" name="REGISTRO_FUNC" id="submitUl" value="MEUS REGISTROS DE MOVIMENTAÇÃO">
                    </div>
                </li>
                <li>
                    <div>
                        <input type="submit" name="CRIAR_EMP" id="submitUl" value="CRIAR EMPREENDIMENTO">
                    </div>
                </li>
            </ul>
        </form>


        <?php if (!empty($_COOKIE["aviso"])) { ?>
            <form class="form-style-6" name="form3" id="formAdm3">
                <div>
                    <h3><b><?php
                            if (!empty($_COOKIE["aviso"])) {
                                echo "EDITADO COM SUCESSO <br><br>Nome:" . $arrayAgente["nome"] . "<br>" . "Sobrenome:" . $arrayAgente["sobrenome"]  . "<br>" . "CPF:" . $arrayAgente["cpf"] . "<br>" . "Data Nascimento:" . $arrayAgente["nascimento"];
                            }
                            ?></b></h3>
                </div>
            </form>
        <?php } ?>
</body>


</html>