<!DOCTYPE html>
<html lang="pt-br">
<?php
include_once 'superServidor.php';
if(empty($_COOKIE['emp'])){
    header("Location:painelEmpreendimento.php");
}else{
    $empreendimento = strtolower(str_replace(" ", "_",$_COOKIE['emp']));//Captura o Empreendimento escolhido
}

$retornaTudo = new classe();
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
$separador = explode("_", $_COOKIE['pdf']);
$pdfCompleto = $retornaTudo->stringToPdf($separador[1]);
$pegaCadastro = $retornaTudo->verificarAgenteCriado($empreendimento, $pdfCompleto);
$todosPontos = explode("_",  $pegaCadastro['ponto']);

$pontosFind = explode("_", $pegaCadastro['pontoFind']);
if (empty($pontosFind[1])) {
    $pontosFind[1] = null;
}

$nomeCompleto = $pegaCadastro['nome'] . " " . $pegaCadastro['sobrenome'];
$dataAtual = $retornaTudo->pegarDataBanco();

if(empty($pegaCadastro['email'])){
    $pegaEmail = "Não Possui Email";
}else{
    $pegaEmail = $pegaCadastro['email'];
}

?>
<read>
    <title>Remover da Lista Principal</title>
    <link rel="Stylesheet" href="_css/estilo.css">

</read>

<body>
    <!Formulário usado para desconectar o Administrador" –>
        <?php if (!empty($_COOKIE["adm"])) { ?>
            <form action="desconectado.php" method="POST" class="lado" name="form2" id="form2">
                <div>
                    <input type="submit" name="DESLOGA_ADM" id="submit2" value="DESLOGAR">
                </div>
            </form>
        <?php } ?>

        <form action="moverFuncExcluido.php" method="POST" class="form-style-6" name="form1" id="formAdm">
            <h1><b>REMOVER DA LISTA PRINCIPAL</b></h1>
            <div>
                <img src="_imagens/TincDig.png" width="100px">
            </div>
            <div>
                <h2> Administrador </h2>
                <h3> <?php echo $nomeAdm ?> </h3>
            </div>
            <div>
                <input type="submit" id="submit3" name="VOLTAR" value="VOLTAR">
            </div>
        <hr size="5"><br>
        <h2>EMPREENDIMENTO: <?php echo strtoupper($_COOKIE['emp']) ?></h2>
        <br>
        <hr size="5">
        <div id="divTableFunc">
              <table>
                  <tr>
                      <td>Funcionário</td>
                      <td><?php echo $nomeCompleto; ?></td>
                  </tr>
                  <tr>
                  <td>Função</td>
                      <td><?php echo $pegaCadastro['funcao'] ?></td>
                  </tr>
                  <tr>
                  <td>Email</td>
                      <td><?php echo $pegaEmail; ?></td>
                  </tr>
                  <tr>
                  <td>Cpf</td>
                      <td><?php echo $pegaCadastro['cpf']; ?></td>
                  </tr>        
                  
              </table>         
        </div>
        <div id="divTable">
            <table>
                <tr>
                    <td colspan="5">Registro de Pontos</td>
                </tr>
                <tr>
                    <td>Semana</td>
                    <td>E1</td>
                    <td>S1</td>
                    <td>E2</td>
                    <td>S2</td>
                </tr>
                <tr>
                    <td>Seg~Sex</td>
                    <td><?php echo $todosPontos[0] ?></td>
                    <td><?php echo $todosPontos[1] ?></td>
                    <td><?php echo $todosPontos[2] ?></td>
                    <td><?php echo $todosPontos[3] ?></td>
                </tr>
                <tr>
                    <td>Sab~Dom</td>
                    <td><?php echo $pontosFind[0] ?></td>
                    <td><?php echo $pontosFind[1] ?></td>
                    <td></td>
                    <td></td>
                </tr>       
            </table>    
        </div>    
            <hr size=10>
            <h2>Informa a Data que esse Funcionário foi Demitido</h2>
            <div>
                <label>Data:</label>
                <input type="date" id="data" name="data" value="<?php echo $dataAtual ?>" required />
            </div>
            <div>

                <div>
                    <input type="submit" name="REMOVER_FUNC" id="submitUl" value="Remover Funcionário da lista atual">
                </div>

                <br>
                <h3>Os Funcionários não serão excluido, apenas movidos para a tabela "Funcionários Demitidos"</h3>
            </div>
        </form>
</body>


</html>