<!DOCTYPE html>
<html lang="pt-br">
<?php
include_once 'superServidor.php';
$verAgent = new classe();

//Usado para verificar se o Site contem Cookies salvo
//e depois analisar no servidor para buscar as informações do consultor
if (!empty($_COOKIE["agentes"])) {
    header("Location:agente.php");
}

//$verAgent->verFuncCadastrado("053.136.349-09");

?>
<read>
    <title>Sistema de Login</title>
    <link rel="Stylesheet" href="_css/estilo.css">

</read>

<body>

    <?php if (empty($_COOKIE['cpf'])) { ?>
        <form action=logar.php method="POST" class="form-style-6" name="form1" id="form1">
            <h1><b>SISTEMA DE LOGIN DOS FUNCIONÁRIOS</b></h1>
            <div>
                <img src="_imagens/TincDig.png" width="100px">
            </div>
            <hr size="5">
            <div id="centro">
                <b>
                    <p>Digite Seu CPF para se conectar no Sistema</p>
                </b>
                <div>
                    <input type="text" autofocus name="cpf" id="cpf" autocomplete="off" onchange="cpfCnpj()" onkeydown="cpfCnpj()" onkeyup="cpfCnpj()" minlength="14" maxlength="14" name="cpf" value="" placeholder="Número do cpf" required />
                </div>
            </div>
            <hr size="5">

            <div>
                <input type="submit" class="form-style-6" id="submit" value="LOGAR">
            </div>
        </form>

    <?php } else {
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
                        <td colspan="8"><?php echo strtoupper($pegaCookies[1]) ?></td>
                    </tr>
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
            <h3> Bem vindo, Digite uma senha para finalizar seu Registro. </h3><br>
            <div>
                <b>Digite a Senha:</b>
                <input type="password" size="35" autofocus name="senha1" id="senha1" required />
            </div>
            <div>
                <b>Digite a Novamente Senha:</b>
                <input type="password" size="20" name="senha2" id="senha2" required />
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
    <?php } ?>




    <?php if (!empty($_COOKIE["adm"])) {
        if (empty($_COOKIE["emp"])) { ?>
            <form action="painelAdministrativo.php" name="form3" class="form-style-6" id=formAlinhar>
            <?php } else { ?>
                <form action="painelAdministrativoEmpFunc.php" name="form3" class="form-style-6" id=formAlinhar>
                <?php } ?>
                <div>
                    <input type="submit" id="submitUl" value="VOLTAR PARA SETOR ADMINISTRATIVO">
                </div>
            <?php } ?>
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

                function justNumbers(text) {
                    var numbers = text.replace(/[^0-9]/g, '');
                    return (numbers);
                }

                function cpfCnpj() {
                    var pegaTecla = event.keyCode;
                    var tel = document.getElementById('cpf').value;
                    if (pegaTecla == 8) {
                        if (tel.length == 4) {
                            document.getElementById('cpf').value = tel[0] + tel[1] + tel[2];
                        } else if (tel.length == 8) {
                            document.getElementById('cpf').value = tel[0] + tel[1] + tel[2] + "." + tel[4] + tel[5] + tel[6];
                        } else if (tel.length == 12) {
                            document.getElementById('cpf').value = tel[0] + tel[1] + tel[2] + "." + tel[4] + tel[5] + tel[6] + "." + tel[8] + tel[9] + tel[10];
                        }
                    } else {
                        tel = justNumbers(tel);
                        document.getElementById('cpf').value = "";
                        for (var x = 0; x < tel.length; x++) {
                            if (x == 2 || x == 5) {
                                document.getElementById('cpf').value += tel[x] + ".";
                            } else if (x == 8) {
                                document.getElementById('cpf').value += tel[x] + "-";
                            } else {
                                document.getElementById('cpf').value += tel[x];
                            }
                        }


                    } // esse else faz parte no caso não for digitado backSpace

                }
            </script>


</body>


</html>