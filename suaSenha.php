<!DOCTYPE html>
<html lang="pt-br">
<read>
    <title>Digite sua Senha</title>
    <link rel="Stylesheet" href="_css/estilo.css">

</read>
<?php
include_once 'superServidor.php';
$verAgent = new classe();
$pegaCookies = explode("_", $_COOKIE['cpf']);
$dadosCompleto = $verAgent->verificarCpfAgenteCriado($pegaCookies[1], $pegaCookies[0]);       
$ponto = explode("_", $dadosCompleto['ponto']);
$nasc = explode("-", $dadosCompleto['nascimento']);
$nasc1 = $nasc[2] . "-" . $nasc[1] . "-" . $nasc[0];
?>
 <form action=logar.php method="POST" class="form-style-6" name="form1" id="form1">
     <h1><b>SISTEMA DE LOGIN DOS FUNCIONÁRIOS</b></h1>
     <div>
         <img src="_imagens/TincDig.png" width="100px">
     </div>
     <hr size="5">
     <br>
     <div id="centro">
         <table id="tableAgentes">
             <tr>
                 <td colspan="4">Informações Pessoais</td>
                 <td colspan="4">Registro de Pontos</td>
             </tr>
             <tr>
                 <td>Nome</td>
                 <td>Sobrenome</td>
                 <td>Cpf</td>
                 <td>Nascimento</td>
                 <td>E1</td>
                 <td>S1</td>
                 <td>E2</td>
                 <td>S2</td>
             </tr>
             <tr>
                 <td><?php echo $dadosCompleto['nome'] ?> </td>
                 <td><?php echo $dadosCompleto['sobrenome'] ?> </td>
                 <td><?php echo $dadosCompleto['cpf'] ?> </td>
                 <td><?php echo $nasc1 ?> </td>
                 <td><?php echo $ponto[0] ?> </td>
                 <td><?php echo $ponto[1] ?> </td>
                 <td><?php echo $ponto[2] ?> </td>
                 <td><?php echo $ponto[3] ?> </td>
             </tr>
         </table>
     </div>
     <br><br><br><hr size="5">
     <div>
         <b>Digite sua Senha:</b>
         <input type="password" size="35" autofocus name="senhaM" id="senhaM" required />
     </div>
     <?php if(!empty($_COOKIE['aviso'])){ ?>           
     <div>
     <b style="color: #eb2113;"><?php echo $_COOKIE['aviso'] ?></b>
     </div>
     <br>
     <?php } ?>
     <hr size="5">
     <div>
         <input type="submit" class="form-style-6" id="submit" value="LOGAR">
     </div>           
    
 </form>

 <form action="index.php" class="form-style-6" name="form2" id="form2">
    <div>
        <input type="submit" id="submit2" value="VOLTAR">
    </div>

</form>


</html>


?>