<!DOCTYPE html>
<html lang="pt-br">
<?php
include_once 'superServidor.php';
if (empty($_COOKIE['emp'])) {
    header("Location:painelEmpreendimento.php");
} else {
    $empreendimento = strtolower(str_replace(" ", "_", $_COOKIE['emp'])); //Captura o Empreendimento escolhido
}
$pClasse = new classe();
//Usado para verificar se o Site contem Cookies salvo
//e depois analisar se o Adm está logado.
if (empty($_COOKIE["adm"])) {
    header("Location:painelAdministrativo-senha.php");
    exit(0);
} else {
    $nomeAdm = $_COOKIE["adm"];
}

if (!empty($_COOKIE["aviso"])) {
    $arrayAgente = $pClasse->retornaAgenteCriado($_COOKIE["aviso"], $empreendimento);
    setcookie("aviso", null);
}

if(!empty($_COOKIE["emp2"])){
    setcookie("emp2", null);
}

if (!empty($_COOKIE["data"])) {
    setcookie("data", null); //serve para apagar o Mês e o Ano da pesquisa
}


//Utilizado para Adicionar uma nova coluna dentro do Empreendimento
$nomeColunaNova = "funcao";
$depoisColuna = "sobrenome";
//$pClasse->inserirSeNaoTemColuna($empreendimento."f_excluido", $nomeColunaNova, 50, $depoisColuna );


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

        <form action="painelSelecao.php" method="POST" class="form-style-6" name="form1" id="formAdm">
            <h1><b>SISTEMA DE PAINEL ADMINISTRATIVO</b></h1>
            <div>
                <img src="_imagens/TincDig.png" width="100px">
            </div>
            <div>
                <h2> Administrador </h2>
                <h3> <?php echo $nomeAdm ?> </h3>
            </div>
            <div>
                <input type="submit" name="VOLTAR_EMP" id="submit3" value="VOLTAR">
            </div>
            <hr size="5"><br>
            <h2>EMPREENDIMENTO: <?php echo strtoupper($_COOKIE['emp']) ?></h2>
            <br>
            <hr size="5">

            <ul>
                <li>
                    <div>
                        <input type="submit" name="ACESSO_FUNC2" id="submitUl" value="ACESSAR SISTEMA DE LOGIN DOS FUNCIONÁRIOS">
                    </div>
                </li>

                <li>
                    <div>
                        <input type="submit" name="CADASTRO_FUNC" id="submitUl" value="CADASTRAR FUNCIONÁRIOS">
                    </div>
                </li>

                <li>
                    <div>
                        <input type="submit" name="PAINEL_EMP" id="submitUl" value="ACESSAR PAINEL DOS FUNCIONÁRIOS">
                    </div>
                </li>
                <li>
                    <div>
                        <input type="submit" name="PAINEL_DEMINIT_FUNC" id="submitUl" value="ACESSAR PAINEL DE DEMISSÃO">
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
                        <input type="submit" name="MSG_RECEBIDA" id="submitUl" value="MENSAGENS RECEBIDAS">
                    </div>
                </li>
            </ul>
        </form>

</body>


</html>