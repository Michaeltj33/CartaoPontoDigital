<!DOCTYPE html>
<html lang="pt-br">
<?php
include_once 'superServidor.php';
$retornaTudo = new classe();
if(empty($_COOKIE['emp'])){
    header("Location:painelEmpreendimento.php");
}else{
    $empreendimento = strtolower(str_replace(" ", "_",$_COOKIE['emp']));//Captura o Empreendimento escolhido
}

//Usado para verificar se o Site contem Cookies salvo
//e depois analisar no servidor para buscar as informações do consultor
if (empty($_COOKIE["adm"])) {
    header("Location:painelAdministrativo-senha.php");
    exit(0);
} else {
    $nomeAdm = $_COOKIE["adm"];
}

    //setcookie("aviso",6);

if (!empty($_COOKIE["aviso"])) {
    $arrayAgente = $retornaTudo->retornaAgenteCriado($_COOKIE["aviso"], $empreendimento);
    setcookie("aviso", null);
}

if(empty($pegaTudo['email'])){
    $pegaEmail = "Não Possui Email";
}else{
    $pegaEmail = $pegaTudo['email'];
}

?>
<read>
    <title>Cadastrar Funcionário</title>
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

        <form action=cadastrar.php method="POST" class="form-style-6" name="form1" id="form1">
            <h1><b>CADASTRO DE FUNCIONÁRIOS</b></h1>
            <div>
                <img src="_imagens/TincDig.png" width="100px">
            </div>
            <div>
                <h2> Administrador </h2>
                <h3> <?php echo $nomeAdm ?> </h3>
            </div>
            <hr size="5"><br>
            <h2>EMPREENDIMENTO: <?php echo strtoupper($_COOKIE['emp']) ?></h2>
            <br>
            <hr width="101.6%">
        <div>
            <div>
                <label>Nome: *</label>
                <input type="text" id="nome" autofocus MAXLENGTH="60" name="nome" onkeydown="soNome()" onkeyup="soNome()" placeholder="Digite seu primeiro Nome" required />
            </div>
            <div>
                <label>Sobrenome: *</label>
                <input type="text" name="sobrenome" MAXLENGTH="60" id="sobrenome" onkeydown="soSobrenome()" onkeyup="soSobrenome()" placeholder="Digite seu Sobrenome" required />
            </div>
            <div>
                <label>Cpf: *</label>
                <input type="text" id="cpf" name="cpf" onchange="cpfCnpj()" onkeydown="cpfCnpj()" onkeyup="cpfCnpj()" minlength="14" maxlength="14"  value="" placeholder="Número do cpf" />
            </div>
            <hr width="115.5%">
            <div>
                <label>Data Nascimento: *</label>
                <input type="date" id="nascimento" name="nascimento" required />
            </div>
            <div>
                <label>Função: *</label><br>
                <input type="text" id="funcao" size="30" placeholder="Digite a Função" name="funcao" style="height: 30px;" required/>
            </div>
            <div>
                <label>E-Mail:(Opcional)</label><br>
                <input type="email" id="email" size="30" value="" placeholder="Digite Seu Email" name="email" style="height: 30px;"/>
            </div>
            <hr width="115.5%">
            <div>
                <label>HORÁRIO DO FUNCIONÁRIO DE SEG~SEX:</label><br>
                <div>
                    <div>
                        <label>Ponto E1: *</label>
                        <input type="time" id="ponto1" name="ponto1" required />
                    </div>
                    <div>
                        <label>Ponto S1: *</label>
                        <input type="time" id="ponto2" name="ponto2" required />
                    </div>
                    <div>
                        <label>Ponto E2: *</label>
                        <input type="time" id="ponto3" name="ponto3" required />
                    </div>
                    <div>
                        <label>Ponto S2: *</label>
                        <input type="time" id="ponto4" name="ponto4" required />
                    </div>
                </div>
            </div>
            <hr width="115.5%">
            <div>
                <label>HORÁRIO DO FUNCIONÁRIO DE SAB~DOM:(Opcional) </label><br>
                <div>
                    <div>
                        <label>Ponto E1:</label>
                        <input type="time" id="pontoFind1" name="pontoFind1"/>
                    </div>
                    <div>
                        <label>Ponto S1:</label>
                        <input type="time" id="pontoFind2" name="pontoFind2" />
                    </div>
                    
                </div>
            </div>
            <hr width="115.5%">

                <div>
                    <input type="submit" class="form-style-6" id="submit3" value="CADASTRAR">
                </div>
        </form>
        <hr size=30, width="115%">

        <form action="painelAdministrativoEmpFunc.php" name="form3" class="form-style-6" id=formAlinhar>
            <div>
                <input type="submit" id="submit3" value="VOLTAR AO INICIO">
            </div>
            <?php if (!empty($_COOKIE["aviso"])) { ?>
                <hr size=20>
                <div>
                    <h3><b><?php
                            if (!empty($_COOKIE["aviso"])) {
                                $dataNasc = explode("-", $arrayAgente["nascimento"]);
                                $pontoTotal = explode("_", $arrayAgente['ponto']);
                                echo "CRIADO COM SUCESSO <br><br>Nome:" . $arrayAgente["nome"] . "<br>" . "Sobrenome:" . $arrayAgente["sobrenome"] . "<br>" . "Função:" . $arrayAgente["funcao"]  . "<br>" . "Senha padrão:" . $arrayAgente["senha"] . "<br>" . "CPF:" . $arrayAgente["cpf"] . "<br>" . "Data Nascimento:" . $dataNasc[2] . "-" . $dataNasc[1] . "-" . $dataNasc[0] . "<br>";
                                echo "Ponto E1: ". $pontoTotal[0] . "<br>" . "Ponto S1: ". $pontoTotal[1] . "<br>" . "Ponto E2: ". $pontoTotal[2] . "<br>" . "Ponto S2: ". $pontoTotal[3];
                                if(!empty($arrayAgente["pontoFind"])){
                                   $dataFind = explode("_", $arrayAgente["pontoFind"]);
                                   echo "<br> PontoFind1: " . $dataFind[0] . "<br>" . "PontoFind2: " . $dataFind[1];
                                }
                                if(!empty($arrayAgente["email"])){
                                    echo "<br> Email: ". $arrayAgente["email"];
                                }
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

            function soSobrenome() {
                var n = document.getElementById('sobrenome').value;
                n = somenteLetra(n);
                n = n.replace("  ", " ");
                document.getElementById('sobrenome').value = n;
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