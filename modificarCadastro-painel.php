<!DOCTYPE html>
<html lang="pt-br">
<?php
include_once 'superServidor.php';
if(empty($_COOKIE['emp'])){
    header("Location:painelEmpreendimento.php");
}else{
    $empreendimento = strtolower(str_replace(" ", "_",$_COOKIE['emp']));//Captura o Empreendimento escolhido
}

if (isset($_POST['REGISTRAR_PONTO_USUARIO'])) {
    header("Location:modificarPontoFuncionario.php");
    exit(0);
} else if (isset($_POST['EXCLUIR_FUNC_LISTA'])) {
    header("Location:excluirFuncionario.php");
    exit(0);
} else if (isset($_POST['MSG_FUNCIONARIO'])) {
    header("Location:mandarMsgFuncionario.php");
    exit(0);
} else if (isset($_POST['RESETA_SENHA'])) {
    header("Location:resetarSenha.php");
    exit(0);
}



$retornaTudo = new classe();
//Usado para verificar se o Site contem Cookies salvo
//e depois analisar no servidor para buscar as informações do consultor
if (empty($_COOKIE["adm"])) {
    header("Location:painelAdministrativo-senha.php");
    exit(0);
} else {
    $nomeAdm = $_COOKIE["adm"];
}



$separador = explode("_", $_COOKIE['pdf']);
$pdfCompleto = $retornaTudo->stringToPdf($separador[1]);
$pegaCadastro = $retornaTudo->verificarAgenteCriado($empreendimento, $pdfCompleto);

$pontos = explode("_", $pegaCadastro['ponto']);

$pontosFind = explode("_", $pegaCadastro['pontoFind']);

if(empty($pontosFind[1])){
    $pontosFind[1]=null;
}

$todosPontos = $pontos;
$nomeCompleto = $pegaCadastro['nome'] . " " . $pegaCadastro['sobrenome'];

if (!empty($pegaCadastro)) {
    setcookie("id", $pegaCadastro['id_agente']);
}
if(empty($pegaTudo['email'])){
    $pegaEmail = "Não Possui Email";
}else{
    $pegaEmail = $pegaTudo['email'];
}
?>
<read>
    <title>Modificar Cadastro do Funcionário</title>
    <link rel="Stylesheet" href="_css/estilo.css">
</read>

<body>
    <form action=modificarCadastro.php method="POST" class="form-style-6" name="form1" id="form1">
        <h1><b>ALTERAÇÃO DO CADASTRO DOS FUNCIONÁRIOS</b></h1>
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
        <hr width="101.6%">
        <div>
            <div>
                <label>Nome: *</label>
                <input type="text" id="nome" MAXLENGTH="60" name="nome" value="<?php echo $pegaCadastro['nome']; ?>" onkeydown="soNome()" onkeyup="soNome()" placeholder="Digite seu primeiro Nome" required />
            </div>
            <div>
                <label>Sobrenome: *</label>
                <input type="text" name="sobrenome" MAXLENGTH="60" id="sobrenome" value="<?php echo $pegaCadastro['sobrenome']; ?>" onkeydown="soSobrenome()" onkeyup="soSobrenome()" placeholder="Digite seu Sobrenome" required />
            </div>
            <div>
                <label>Cpf:</label>
                <input type="text" id="cpf" value="<?php echo $pegaCadastro['cpf']; ?>" onchange="cpfCnpj()" onkeydown="cpfCnpj()" onkeyup="cpfCnpj()" minlength="14" maxlength="14" name="cpf" value="" placeholder="Número do cpf" />
            </div>
            <hr width="117%">
            <div>
                <label>Data Nascimento:</label>
                <input type="date" id="nascimento" value="<?php echo $pegaCadastro['nascimento']; ?>" name="nascimento" required />
            </div>
            <div>
                <label>Função: *</label><br>
                <input type="text" id="funcao" size="30" placeholder="Digite a Função" value="<?php echo $pegaCadastro['funcao']; ?>" name="funcao" style="height: 30px;" required/>
            </div>
            <div>
                <label>E-Mail:(Opcional)</label><br>
                <input type="email" id="email" placeholder="Digite seu Email" value="<?php echo $pegaCadastro['email']; ?>" size="30" value="" name="email" style="height: 30px;"/>
            </div>
            
            
            <hr width="117%">
            <div>
                <label>HORÁRIO DO FUNCIONÁRIO DE SEG~SEX:</label><br>
                <div>
                    <div>
                        <label>Ponto E1:</label>
                        <input type="time" value="<?php echo $pontos[0] ?>" id="ponto1" name="ponto1" required />
                    </div>
                    <div>
                        <label>Ponto S1:</label>
                        <input type="time" value="<?php echo $pontos[1] ?>" id="ponto2" name="ponto2" required />
                    </div>
                    <div>
                        <label>Ponto E2:</label>
                        <input type="time" value="<?php echo $pontos[2] ?>" id="ponto3" name="ponto3" required />
                    </div>
                    <div>
                        <label>Ponto S2:</label>
                        <input type="time" value="<?php echo $pontos[3] ?>" id="ponto4" name="ponto4" required />
                    </div>
                </div>
            </div>
            <hr width="117%">
            <div>
                <label>HORÁRIO DO FUNCIONÁRIO DE SAB~DOM:(Opcional)</label><br>
                <div>
                    <div>
                        <label>Ponto E1:</label>
                        <input type="time" value="<?php echo $pontosFind[0] ?>" id="pontoFind1" name="pontoFind1" />
                    </div>
                    <div>
                        <label>Ponto S1:</label>
                        <input type="time" value="<?php echo $pontosFind[1] ?>" id="pontoFind2" name="pontoFind2" />
                    </div>
                    
                </div>
            </div>
            <hr width="117%">           
            <div>
                <input type="submit" name="editarRegistroPonto" class="form-style-6" id="submit3" value="ATUALIZAR DADOS">
            </div>
            <hr size=30>
        </div>
    </form>


    <form action="listaspontoFuncionario.php" name="form3" class="form-style-6" id=formAlinhar>
        <div>
            <input type="submit" id="submit3" value="VOLTAR AO INICIO">
        </div>
        <hr size=10>
        <div>
            <h3><b><?php
                    if (!empty($_COOKIE["aviso"])) {
                        echo "CRIADO COM SUCESSO <br><br>Nome:" . $arrayAgente["nome"] . "<br>" . "Sobrenome:" . $arrayAgente["sobrenome"]  . "<br>" . "Senha padrão:" . $arrayAgente["senha"] . "<br>" . "CPF:" . $arrayAgente["cpf"] . "<br>" . "Data Nascimento:" . $arrayAgente["nascimento"];
                    }
                    ?></b></h3>
        </div>
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