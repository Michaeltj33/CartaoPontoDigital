<!DOCTYPE html>
<html lang="pt-br">
<?php
include_once 'superServidor.php';
if (!empty($_COOKIE["adm"])) {
    header("Location:painelAdministrativo.php");
}
$verAgent = new classe();


?>
<read>
    <title>Sistema de Login Administrativo</title>
    <link rel="Stylesheet" href="_css/estilo.css">

</read>

<body>

    <?php if(empty($_COOKIE['aviso'])){ ?>
    <form action=logarAdm.php method="POST" class="form-style-6" name="form1" id="form1">
        <h1><b>SISTEMA DE LOGIN ADMINISTRATIVO</b></h1>
        <div>
            <img src="_imagens/TincDig.png" width="100px">
        </div>
        <hr size="5">
        <div id="centro">       
        <b><p>Digite Seu CPF para se conectar no Sistema</p></b>
        <div>           
            <input type="text" autofocus name="cpf" id="cpf" autocomplete="off"  onchange="cpfCnpj()" onkeydown="cpfCnpj()" onkeyup="cpfCnpj()" minlength="14" maxlength="14" name="cpf" value="" placeholder="Número do cpf" required />
        </div>
        </div>
        <hr size="5"> 
       
        <div>
            <input type="submit" class="form-style-6" id="submit" value="LOGAR">
        </div>
    </form>

    <?php }else{ ?>
        <form action=logarAdm.php method="POST" class="form-style-6" name="form1" id="form1">
        <h1><b>SISTEMA DE LOGIN ADMINISTRATIVO</b></h1>
        <div>
            <img src="_imagens/TincDig.png" width="100px">
        </div>
        <hr size="5">
        <div id="centro">       
        <b><p>CPF: <?php echo $_COOKIE['aviso'] ?> </p></b>
        <div>
            <b>Digite seu nome Completo</b>           
            <input type="text" size="50" autofocus name="nomeCompleto" id="nomeCompleto" autocomplete="off"  onchange="soNome()" onkeydown="soNome()" onkeyup="soNome()" maxlength="60" value="" placeholder="Digite seu nome Completo" required />
        </div>
        <br><br>
        <div>
        <b>Digite a senha:</b>            
            <input type="password" size="50" name="senha" id="senha" autocomplete="off" maxlength="30" value="" placeholder="Digite a senha para entrar no Sistema" required />
        </div>
        </div>
        <hr size="5"> 
       
        <div>
            <input type="submit" class="form-style-6" id="submit" value="LOGAR">
        </div>
    </form>
    <?php } ?>

    <script>

function somenteLetra(text) {
                return text.replace(/[^a-z àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇA-Z]/g, '');
            }

            function soNome() {
                var n = document.getElementById('nomeCompleto').value;
                n = somenteLetra(n);
                n = n.replace("  ", " ");
                document.getElementById('nomeCompleto').value = n;
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