<!DOCTYPE html>
<html lang="pt-br">
<?php
include_once 'superServidor.php';
$retornaTudo = new classe();

//Usado para verificar se o Site contem Cookies salvo
//e depois analisar no servidor para buscar as informações do consultor
if (empty($_COOKIE["adm"])) {
    header("Location:painelAdministrativo-senha.php");
    exit(0);
} else {
    $nomeAdm = $_COOKIE["adm"];
}

if (!empty($_COOKIE["aviso"])) {
    if(strpos($_COOKIE["aviso"], "_")){  
    $arrayAgente = explode("_", $_COOKIE['aviso']);
    $aviso = "CRIADO COM SUCESSO <br><br>Nome:" . $arrayAgente[0] . "<br>" . "Data:" . $arrayAgente[1]  . "<br>" . "Hora:" . $arrayAgente[2] ;
    }else {
        $aviso = $_COOKIE["aviso"];
    }
    setcookie("aviso", null);
}

$dataAtual = $retornaTudo->pegarDataBanco();
$pegaHora = $retornaTudo->pegarMinutoHora();

?>
<read>
    <title>Cadastrar Novo Empreendimento</title>
    <link rel="Stylesheet" href="_css/estilo.css">

</read>

<body>
    <form action=registrarPonto.php method="POST" class="form-style-6" name="form1" id="form1">
        <h1><b>PAINEL DE CADASTRO DE NOVO EMPREENDIMENTO</b></h1>
        <div>
            <img src="_imagens/TincDig.png" width="100px">
        </div>
        <div>
            <h2> Administrador </h2>
            <h3> <?php echo $nomeAdm ?> </h3>
        </div>
        <hr size=10>
        <h4>Digite o nome do Empreendimento</h4>
        <div>
            <label>Nome do Empreendimento:</label>            
            <input type="text" size="25" id="nome" MAXLENGTH="60" name="nome" onkeydown="soNome()" onkeyup="soNome()" placeholder="Digite o Empreendimento" required />
        </div>

        <div>
            <input type="submit" class="form-style-6" id="submitUl2" name="CADASTRAR_EMPREENDIMENTO" value="CADASTRAR EMPREENDIMENTO">
        </div>
    </form>

    <form action="painelAdministrativo.php" name="form3" class="form-style-6" id=formAlinhar>
        <div>
            <input type="submit" id="submit3" value="VOLTAR AO INICIO">
        </div>
        <?php if (!empty($_COOKIE["aviso"])) { ?>
        <hr size=20>
        <div>
            <h3><b><?php
                    if (!empty($_COOKIE["aviso"])) {
                        echo  $aviso;
                    }
                    ?></b></h3>
        </div>
        <?php } ?>
    </form>

    <script>
            function somenteLetra(text) {
                return text.replace(/[^a-z àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇA-Z]/g, '');
            }

            function soNome() {
                var n = document.getElementById('nome').value;
                n = somenteLetra(n);
                n = n.replace("  ", " ");
                document.getElementById('nome').value = n;
            }
    </script>        
</body>


</html>