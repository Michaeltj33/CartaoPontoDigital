<?php
class classe
{
    private function pegarServ()
    {
        $servidor = "localhost:3306";
        $usuario = "root";
        $senha = "";
        $bancodedados = "pontodigital";

        $conn = new mysqli($servidor, $usuario, $senha, $bancodedados);

        if ($conn->connect_error) {
            die("Falha na conexão: " . $conn->connect_error);
        }
        return $conn;
    }


    //Usado para pegar o ID do funcionário cadastrado
    function pegarID($emp)
    {
        $pegaN = 0;
        $conn = $this->pegarServ();
        $sql = "SELECT * FROM " . $emp;
        $resultado = $conn->query($sql);
        foreach ($resultado as $res) {
            $pegaN = $res['id_agente'];
        }
        return $pegaN;
    }

    //Faz pesquisa dentro da tabela e retorna os dados que for igual ao cpf solicitado
    function verificarAgenteCriado($tabela, $cpf)
    {
        $agenteCriado = "";
        $conn = $this->pegarServ();
        $sql = "select * from " . $tabela . " where cpf='" . $cpf . "'";
        $resultado = $conn->query($sql);
        foreach ($resultado as $res) {
            $agenteCriado = $res;
        }
        return $agenteCriado;
    }

    //Faz uma pesquisa dentro da tabela no banco de dados e retorna caso o pcf existe
    function verificarCpfAgenteCriado($tabela, $cpf)
    {
        $tabela = strtolower(str_replace(" ", "_", $tabela));
        $agenteCriado = "";
        $conn = $this->pegarServ();
        $sql = "select * from " . $tabela . " where cpf='" . $cpf . "'";
        $resultado = $conn->query($sql);
        if ($resultado) {
            foreach ($resultado as $res) {
                $agenteCriado = $res;
            }
        }
        return $agenteCriado;
    }

    function removeE($dt)
    {
        $dt = trim($dt); //remove espaço no inicio e fim
        return $dt;
    }

    //Serve para verificar se existe tabela
    function listarTabela($agente)
    {
        $func = array();
        $conn = $this->pegarServ();
        $sql = "select * from " . $agente . " order by nome";
        $resultado = $conn->query($sql);
        if ($resultado) {
            foreach ($resultado as $res) {
                array_push($func, $res);
            }
            return $func;
        } else {
            return "";
        }
    }

    //Serve para atualizar os dados dos funcionários
    function atualizarTudo($tabela, $nome, $sobrenome, $func, $email, $cpf, $nascimento, $ponto, $pontoFind, $id)
    {
        $conn = $this->pegarServ();
        $sql = "update " . $tabela .  " set nome ='" . "$nome" . "', email ='" . $email . "', pontoFind ='" . $pontoFind . "', funcao = '" . $func . "', sobrenome = '" . $sobrenome . "',cpf = '" . $cpf . "', nascimento = '" . $nascimento . "', ponto = '" . $ponto .  "' where id_agente =" .  $id;
        var_dump($sql);
        $conn->query($sql);
    }

    //Retorna uma lista do funcionário pegando pelo id
    function verificarAgentes($agente, $id)
    {
        $conn = $this->pegarServ();
        $sql = "select * from " . $agente . " WHERE id_agente=" . $id;
        $resultado = $conn->query($sql);
        return $resultado;
    }

    //Serve para verififcar se existem o empreendimento
    function verificarEmpreendimentoExiste($tabela, $nome)
    {
        $verificador = "";
        $conn = $this->pegarServ();
        $sql = "select * from " . $tabela . " WHERE nome='" . $nome . "'";
        $resultado = $conn->query($sql);
        foreach ($resultado as $res) {
            $verificador = $res;
        }
        return $verificador;
    }

    function listarPontoAgente($agente)
    {
        $array = array();
        $conn = $this->pegarServ();
        $sql = "select * from " . $agente;
        $resultado = $conn->query($sql);
        foreach ($resultado as $res) {
            array_push($array, $res);
        }
        return $array;
    }

    function verificarConsultor($cpf, $tabela)
    {
        $idAg = "";
        $conn = $this->pegarServ();
        $sql = "SELECT * FROM " . $tabela;
        $resultado = $conn->query($sql);
        foreach ($resultado as $res) {
            if ($res['cpf'] == $cpf) {
                $idAg = $res['id_agente'];
            }
        }

        return $idAg;
    }

    //Usado para criar tabela no banco de dados dos Funcionários
    function criarTabelaBancoDados($tabela)
    {
        $conn = $this->pegarServ();
        $sql = "create table IF NOT EXISTS " . $tabela . " (
            id_agente int auto_increment,
            nome varchar(30),
            data varchar(30),
            hora varchar (30),
            semana varchar (30),
            Justificativa varchar (100),
            primary key(id_agente)
            );";
        $conn->query($sql);
    }

    function criarTabelaEmpreendimentoFunc($tabela)
    {
        $conn = $this->pegarServ();
        $sql = "create table IF NOT EXISTS " . $tabela . " (
            id_agente int auto_increment,
            nome varchar(30),
            sobrenome varchar(100),
            senha varchar(30),
            cpf varchar(20),
            nascimento varchar(20),
            registro varchar(300),
            ponto varchar(35),
            administrador varchar(50),
            tabela varchar(30),
            primary key(id_agente)
            );";
        $conn->query($sql);
    }

    //Usado para criar tabela no banco de dados, Tabela que armazenas os Empreendimentos
    function criarTabelaEmpreendimento($tabela)
    {
        $conn = $this->pegarServ();
        $sql = "create table IF NOT EXISTS " . $tabela . " (
            id_geral int auto_increment,
            nome varchar(60),
            registro varchar(100),
            primary key(id_geral)
            );";
        $conn->query($sql);
    }

    function criarTabelaExcluir($tabela)
    {
        $conn = $this->pegarServ();
        $tabelaExcluir = $tabela . "_f_excluido";
        $sql = "create table IF NOT EXISTS " . $tabelaExcluir . "(
            id_geral int auto_increment,
            id_agente int,
            nome varchar(30),
            sobrenome varchar(100),
            funcao varchar(50),
            email varchar(50),
            cpf varchar(30),
            nascimento varchar(20),
            ponto varchar(25),
            pontoFind varchar(25),
            dataDemissao varchar(25),
            tabela varchar(30),
            primary key(id_geral)
            );";
        $conn->query($sql);
    }

    function cadastrarEmpreendimento($tabela, $empreendimento, $registro)
    {
        if ($this->verificarTabelaExiste($tabela) == "") {
            $this->criarTabelaEmpreendimento($tabela);
        }
        
        $conn = $this->pegarServ();
        $sql = "insert into " . $tabela . " (nome, registro) values ('$empreendimento', '$registro')";
        $conn->query($sql);
    }

    function mudarSenha($tabela, $senha, $id)
    {
        $tabela = str_replace(" ", "_", $tabela);
        $tabela = strtolower($tabela);
        $conn = $this->pegarServ();
        $sql = "update " . $tabela . " set senha = '" . $senha . "' where id_agente = " . $id;
        $conn->query($sql);
    }

    function retornaCookies($tabela, $idCookies)
    {
        $tabela = str_replace(" ", "_", $tabela);
        $tabela = strtolower($tabela);
        $conn = $this->pegarServ();
        $sql = "SELECT * FROM " . $tabela;
        $resultado = $conn->query($sql);
        foreach ($resultado as $res) {
            if ($res['id_agente'] == $idCookies) {
                $nome = $res;
            }
        }

        return $nome;
    }

    function pegarSomaDataTabela($tabelaFunc, $data)
    {
        $total = 0;
        $conn = $this->pegarServ();
        $sql = "select * from " . $tabelaFunc . " where data='" . $data . "'";
        $resultado = $conn->query($sql);
        if ($resultado) {
            foreach ($resultado as $res) {
                $total++;
            }
        }
        return $total;
    }

    function verificarSeTemColuna($tabela, $coluna)
    {
        $colunavazia = "vazio";
        $array = array();
        $conn = $this->pegarServ();
        $sql = "show columns from " . $tabela;
        $resultado = $conn->query($sql);
        foreach ($resultado as $res) {
            array_push($array, $res);
        }
        foreach ($array as $arr) {
            if ($arr["Field"] == $coluna) {
                $colunavazia = $arr["Field"];
            }
        }
        return $colunavazia;
    }

    //Usado para iserir uma coluna dentro da tabela do banco de dados, caso não exista
    function inserirSeNaoTemColuna($tabela, $coluna, $var, $depois_coluna)
    {
        $tabela = str_replace(" ", "_", $tabela);
        $tabela = strtolower($tabela);
        $coluna1 = $this->verificarSeTemColuna($tabela, $coluna);
        if ($coluna1 == "vazio") {
            $var = " varchar(" . $var . ")";
            $conn = $this->pegarServ();
            $sql = "alter table " . $tabela . " add column " . $coluna . $var . "after " . $depois_coluna;
            $conn->query($sql);
        }
    }

    function registrarPontoAgente($nome)
    {
        $data = $this->pegarData();
        $hora = $this->pegarHora();
        $semana = $this->pegarSemana();
        $conn = $this->pegarServ();
        $quebraNome = explode("_", $nome);
        $sql = "INSERT into " . $nome . "  (nome, data, hora, semana) values ('$quebraNome[1]', '$data', '$hora', '$semana')";
        $conn->query($sql);
    }

    function admRegistrarPontoAgente($nome, $data, $hora, $justificativa)
    {
        $pegarSemana = $this->pegarSemanaEditada($data);
        $pegaHora = $hora . ":00";
        $pegaData = explode("-", $data);
        $pegaData2 = $pegaData[2] . "-" . $pegaData[1] . "-" . $pegaData[0];
        $conn = $this->pegarServ();
        $quebraNome = explode("_", $nome);
        $sql = "INSERT into " . $nome . "  (nome, data, hora, semana, justificativa) values ('$quebraNome[1]', '$pegaData2', '$pegaHora', '$pegarSemana', '$justificativa')";
        $conn->query($sql);
    }

    //Usado para saber quantas horas o funcionário deverá trabalhar
    function somarhoraTrabalho($ponto)
    {
        $pontoSepara = explode("_", $ponto);
        $pontoSepara[0] .= ":00";
        $pontoSepara[1] .= ":00";
        $pontoSepara[2] .= ":00";
        $pontoSepara[3] .= ":00";

        $somaPonto = ($this->transformarHoraSegundos($pontoSepara[1]) - $this->transformarHoraSegundos($pontoSepara[0])) +
            ($this->transformarHoraSegundos($pontoSepara[3]) - $this->transformarHoraSegundos($pontoSepara[2]));

        return $somaPonto;
    }

    function transformarHoraSegundos($hora1)
    {
        if (strlen($hora1) == 5) {
            $hora1 .= ":00";
        }

        list($horas, $minutos, $segundos) = explode(":", $hora1);
        return $horas * 3600 + $minutos * 60 + $segundos;
    }

    function segundoHora($segundos)
    {
        $horas1 = floor($segundos / 3600);
        $minutos1 = floor($segundos % 3600 / 60);
        $segundos1 = $segundos % 60;
        if ($horas1 < 10) {
            $horas1 = "0" . $horas1;
        }
        if ($minutos1 < 10) {
            $minutos1 = "0" . $minutos1;
        }
        if ($segundos1 < 10) {
            $segundos1 = "0" . $segundos1;
        }

        return $horas1 . ":" . $minutos1 . ":" .  $segundos1;
    }

    function SomarHoraExtra($horaBatida, $horaPonto)
    {
        if ($this->transformarHoraSegundos($horaBatida) < ($this->transformarHoraSegundos($horaPonto) - 600)) {
            return $this->transformarHoraSegundos($horaPonto) - $this->transformarHoraSegundos($horaBatida);
        } else {
            return 0;
        }
    }

    function limitePonto10Min($horaBatida, $horaPonto)
    {
        if (($this->transformarHoraSegundos($horaBatida) > ($this->transformarHoraSegundos($horaPonto)) && $this->transformarHoraSegundos($horaBatida) <= ($this->transformarHoraSegundos($horaPonto) + 600))) {
            return $horaPonto . ":00";
        } else if (($this->transformarHoraSegundos($horaBatida) <  ($this->transformarHoraSegundos($horaPonto)) && $this->transformarHoraSegundos($horaBatida) >= ($this->transformarHoraSegundos($horaPonto) - 600))) {
            return $horaPonto . ":00";
        } else {
            return $horaBatida;
        }
    }

    function atrasoHora($horaBatida, $horaPonto)
    {
        if ($this->transformarHoraSegundos($horaBatida) > ($this->transformarHoraSegundos($horaPonto) + 600)) {
            return $this->transformarHoraSegundos($horaBatida) - $this->transformarHoraSegundos($horaPonto);
        } else {
            return 0;
        }
    }
    function resetarSenha($tabela, $id)
    {
        $conn = $this->pegarServ();
        $sql = "update " . $tabela . " set senha = 'Mudar@123' where id_agente = " . $id;
        $conn->query($sql);
    }

    function mandaMsg($nome, $sobrenome, $cpf, $empreendimento, $data, $hora, $administrador, $mensagem)
    {
        $conn = $this->pegarServ();
        $lido = 0;
        $sql = "insert into tabelaMsg (nome, sobrenome, cpf, empreendimento, data, hora, administrador, mensagem, lido) values('$nome', '$sobrenome', '$cpf', '$empreendimento', '$data', '$hora', '$administrador', '$mensagem', '$lido')";
        $conn->query($sql);
    }

    function lerMsgEnviada($cpf, $empreendimento)
    {
        $lista = array();
        $conn = $this->pegarServ();
        $sql = "select * from tabelaMsg where cpf='" . $cpf . "' and empreendimento='" . $empreendimento . "' and lido=0";
        $resultado = $conn->query($sql);
        if ($resultado) {
            foreach ($resultado as $res) {
                array_push($lista, $res);
            }
        }
        return $lista;
    }

    function confirmarMsgLida($id_tabela)
    {
        $conn = $this->pegarServ();
        $sql = "update tabelaMsg set lido = '1' where id_tabela ='" . $id_tabela . "'";
        $conn->query($sql);
    }

    function lerMsgLidaAdm($tabela, $adm)
    {
        $lido = array();
        $conn = $this->pegarServ();
        $sql = "select * from " . $tabela . " where administrador = '" . $adm . "' and lido = '1';";
        $resultado = $conn->query($sql);
        if($resultado){
           foreach($resultado as $res){
                array_push($lido, $res);
           }
        }
        return $lido;
    }



    function somaHoraTotal($pontoBatido, $horaPonto)
    {
        //$pontoBatido é um array
        $dividirPonto = explode("_", $horaPonto);

        $TOTAL1 = array();
        $horaPontoE1 = $dividirPonto[0] . ":00";
        $horaPontoS1 = $dividirPonto[1] . ":00";
        if (isset($dividirPonto[2])) {
            $horaPontoE2 = $dividirPonto[2] . ":00";
        } else {
            $horaPontoE2 = "00:00:00";
        }
        if (isset($dividirPonto[3])) {
            $horaPontoS2 = $dividirPonto[3] . ":00";
        } else {
            $horaPontoS2 = "00:00:00";
        }






        if (sizeof($pontoBatido) == 1) {
            $horaE1 = $pontoBatido[0];
            $horaPositivaE1 = $this->SomarHoraExtra($horaE1, $horaPontoE1);
            $horaNegativaE1 = $this->atrasoHora($horaE1, $horaPontoE1);

            $TOTAL1[0] = 0; //total de horas
            $TOTAL1[1] = 0; // total de horas extras depois das 8 horas
            $TOTAL1[2] = $horaPositivaE1; // total de horas de com minutos antes do ponto(Extra50%)
            $TOTAL1[3] = $horaNegativaE1; // total de horas em atraso depois do ponto(Hora Falta)
        } else if (sizeof($pontoBatido) == 2) {
            $horaE1 = $pontoBatido[0];
            $horaS1 = $pontoBatido[1];

            $horaPositivaE1 = $this->SomarHoraExtra($horaE1, $horaPontoE1);
            $horaNegativaE1 = $this->atrasoHora($horaE1, $horaPontoE1);
            $horaNegativaS1 = $this->SomarHoraExtra($horaS1, $horaPontoS1);
            $horaPositivaS1 = $this->atrasoHora($horaS1, $horaPontoS1);

            //echo $this->segundoHora($horaPositivaE1);


            $TOTAL_HORAS_MANHA = $this->transformarHoraSegundos($horaS1) - $this->transformarHoraSegundos($horaE1);

            $TOTAL1[0] = $TOTAL_HORAS_MANHA;
            $TOTAL1[1] = 0;
            $TOTAL1[2] = $horaPositivaE1 + $horaPositivaS1;
            $TOTAL1[3] = $horaNegativaE1 + $horaNegativaS1;
        } else if (sizeof($pontoBatido) == 3) {
            $horaE1 = $pontoBatido[0];
            $horaS1 = $pontoBatido[1];
            $horaE2 = $pontoBatido[2];

            $horaPositivaE1 = $this->SomarHoraExtra($horaE1, $horaPontoE1);
            $horaNegativaE1 = $this->atrasoHora($horaE1, $horaPontoE1);
            $horaNegativaS1 = $this->SomarHoraExtra($horaS1, $horaPontoS1);
            $horaPositivaS1 = $this->atrasoHora($horaS1, $horaPontoS1);
            $horaPositivaE2 = $this->SomarHoraExtra($horaE2, $horaPontoE2);
            $horaNegativaE2 = $this->atrasoHora($horaE2, $horaPontoE2);

            $TOTAL1[0] = 0;
            $TOTAL1[1] = 0;
            $TOTAL1[2] = $horaPositivaE1 + $horaPositivaS1 + $horaPositivaE2;
            $TOTAL1[3] = $horaNegativaE1 + $horaNegativaS1 + $horaNegativaE2;
        } else if (sizeof($pontoBatido) == 4) {
            $horaE1 = $pontoBatido[0];
            $horaS1 = $pontoBatido[1];
            $horaE2 = $pontoBatido[2];
            $horaS2 = $pontoBatido[3];

            $horaPositivaE1 = $this->SomarHoraExtra($horaE1, $horaPontoE1);
            $horaNegativaE1 = $this->atrasoHora($horaE1, $horaPontoE1);
            $horaNegativaS1 = $this->SomarHoraExtra($horaS1, $horaPontoS1);
            $horaPositivaS1 = $this->atrasoHora($horaS1, $horaPontoS1);
            $horaPositivaE2 = $this->SomarHoraExtra($horaE2, $horaPontoE2);
            $horaNegativaE2 = $this->atrasoHora($horaE2, $horaPontoE2);
            $horaNegativaS2 = $this->SomarHoraExtra($horaS2, $horaPontoS2);
            $horaPositivaS2 = $this->atrasoHora($horaS2, $horaPontoS2);


            if (strtotime($pontoBatido[3]) > strtotime($horaPontoS2)) {
                $horaS2 = $horaPontoS2;
                $horaEx1 = $this->transformarHoraSegundos($pontoBatido[3]) - $this->transformarHoraSegundos($horaS2);
            } else {
                $horaS2 = $pontoBatido[3];
            }

            $TOTAL_HORAS_TARDE = $this->transformarHoraSegundos($horaS2) - $this->transformarHoraSegundos($horaE2);
            $TOTAL_HORAS_MANHA = $this->transformarHoraSegundos($horaS1) - $this->transformarHoraSegundos($horaE1);
            $TOTAL1[0] = $TOTAL_HORAS_MANHA + $TOTAL_HORAS_TARDE;
            if (!empty($horaEx1)) {
                $TOTAL1[1] = $horaEx1;
            } else {
                $TOTAL1[1] = 0;
            }
            $TOTAL1[2] = $horaPositivaE1 + $horaPositivaS1 + $horaPositivaE2 + $horaPositivaS2;
            $TOTAL1[3] = $horaNegativaE1 + $horaNegativaS1 + $horaNegativaE2 + $horaNegativaS2;
        }
        return $TOTAL1;
    }


    function logarConsultor($agente, $senha, $tabela)
    {
        $idAg = "";
        $conn = $this->pegarServ();
        $sql = "SELECT * FROM " . $tabela;
        $resultado = $conn->query($sql);
        if (!empty($resultado)) {
            foreach ($resultado as $res) {
                if ($res['nome'] == $agente && $res['senha'] == $senha) {
                    $idAg = $res['id_agente'];
                }
            }
        }

        return $idAg;
    }

    function verCpfAdm($cpf)
    {
        $verCpf = "";
        $conn = $this->pegarServ();
        $sql = "SELECT * FROM tabelaAdm where cpf='" . $cpf . "'";
        $resultado = $conn->query($sql);
        foreach ($resultado as $res) {
            $verCpf = $res;
        }
        return $verCpf;
    }

    function moverFuncExcluido($pegarCpf, $empreendimento, $dataD)
    {
        $tabelaExcluir = $empreendimento . "_f_excluido";
        $conn = $this->pegarServ();
        $cadastroCompleto = $this->retornaAgenteCpf($pegarCpf, $empreendimento);
        $id_agente = $cadastroCompleto['id_agente'];
        $nome = $cadastroCompleto['nome'];
        $sobreNome = $cadastroCompleto['sobrenome'];
        $funcao = $cadastroCompleto['funcao'];
        $email = $cadastroCompleto['email'];
        $cpf = $cadastroCompleto['cpf'];
        $nascimento = $cadastroCompleto['nascimento'];
        $ponto = $cadastroCompleto['ponto'];
        $pontoFind = $cadastroCompleto['pontoFind'];
        $dataDemissao = $dataD;
        $tabela = $cadastroCompleto['tabela'];
        $sql = "insert into " . $tabelaExcluir . " (id_agente, nome, sobrenome, funcao, email, cpf, nascimento, ponto, pontoFind, dataDemissao, tabela) values ('$id_agente', '$nome', '$sobreNome', '$funcao', '$email', '$cpf', '$nascimento', '$ponto', '$pontoFind', '$dataDemissao', '$tabela')";
        $conn->query($sql);
        $this->deleterColunaTabela($empreendimento, $id_agente);
    }

    function verFuncCadastrado($cpf)
    {
        $tabelas = $this->listarTabela("tabelaEmpreendimento");
        foreach ($tabelas as $table) {
            $tb = strtolower(str_replace(" ", "_", $table['nome']));
            $ver = $this->verificarConsultor($cpf, $tb);
            if ($ver != "") {
                return $table['nome'];
            }
        }
    }

    function cadastrarAdm($nomeCompleto, $cpf)
    {
        $data = $this->pegarData();
        $hora = $this->pegarHora();
        $registro = "Cadastrador dia:" . $data . " - Horário: " . $hora;
        $conn = $this->pegarServ();
        $sql = "insert into tabelaAdm (nomeCompleto, cpf, registro) values ('$nomeCompleto', '$cpf', '$registro');";
        $resultado = $conn->query($sql);
        return $resultado;
    }

    function pegarPontoAgente($agente)
    {
        $ultimoPonto = array();
        $conn = $this->pegarServ();
        $sql = "SELECT * FROM " . $agente;
        $resultado =  $conn->query($sql);
        foreach ($resultado as $res) {
            array_push($ultimoPonto, $res);
        }
        return $ultimoPonto;
    }

    function getAnoBissexto($bi)
    {
        if ($bi % 400 == 0 || $bi % 4 == 0 && $bi % 100 != 0) {
            return 29;
        } else {
            return 28;
        }
    }

    function getTodosMes($ano)
    {
        $mes = array();
        array_push($mes, ["Janeiro", "31"]);
        array_push($mes, ["Fevereiro", $this->getAnoBissexto($ano)]);
        array_push($mes, ["Março", "31"]);
        array_push($mes, ["Abril", "30"]);
        array_push($mes, ["Maio", "31"]);
        array_push($mes, ["Junho", "30"]);
        array_push($mes, ["Julho", "31"]);
        array_push($mes, ["Agosto", "31"]);
        array_push($mes, ["Setembro", "30"]);
        array_push($mes, ["Outubro", "31"]);
        array_push($mes, ["Novembro", "30"]);
        array_push($mes, ["Dezembro", "31"]);

        return $mes;
    }

    function deleterColunaTabela($tabela, $id_geral)
    {
        $conn = $this->pegarServ();
        $sql = "delete from " . $tabela . " where id_agente='" . $id_geral . "'";
        $conn->query($sql);
    }

    function retornaAgenteCriado($id, $tabela)
    {
        $conn = $this->pegarServ();
        $sql = "SELECT * FROM " . $tabela . " where id_agente=" . $id;
        $resultado =  $conn->query($sql);
        foreach ($resultado as $res) {
            $idAgente = $res;
        }
        return $idAgente;
    }

    function listarConteudo($conteudo)
    {
        echo "<pre><th>";
        print_r($conteudo);
        echo "</pre><th>";
    }

    function verificarTabelaExiste($nomeTabela)
    {
        $verTabela = "";
        $conn = $this->pegarServ();
        $sql = "SELECT TABLE_NAME FROM information_schema.tables WHERE table_NAME = '" . $nomeTabela . "'";
        $resultado = $conn->query($sql);
        foreach ($resultado as $res) {
            $verTabela = $res;
        }
        return $verTabela;
    }

    function retornaAgenteCpf($cpf, $tabela)
    {
        $idAgente = "";
        $conn = $this->pegarServ();
        $sql = "SELECT * FROM " . $tabela . " where cpf='" . $cpf . "'";
        $resultado =  $conn->query($sql);
        if ($resultado) {
            foreach ($resultado as $res) {
                $idAgente = $res;
            }
        }
        return $idAgente;
    }

    //Usado para retornar todos os componentes da Tabela referente ao que estiver na coluna.
    function retornaListaTabela($tabelaMov, $coluna, $linha)
    {
        $array = array();
        $conn = $this->pegarServ();
        $sql = "select * from " . $tabelaMov . " where " . $coluna . "='" . $linha . "'";
        $resultado =  $conn->query($sql);
        foreach ($resultado as $res) {
            array_push($array, $res);
        }
        return $array;
    }

    function stringToPdf($string)
    {
        $pdf = "";

        $pdf .= $string[0];
        $pdf .= $string[1];
        $pdf .= $string[2];
        $pdf .= ".";
        $pdf .= $string[3];
        $pdf .= $string[4];
        $pdf .= $string[5];
        $pdf .= ".";
        $pdf .= $string[6];
        $pdf .= $string[7];
        $pdf .= $string[8];
        $pdf .= "-";
        $pdf .= $string[9];
        $pdf .= $string[10];

        return $pdf;
    }

    function pegarMes($sMes)
    {
        $mes = array('Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
        return $mes[$sMes - 1];
    }


    function pegarSemana()
    {
        // Array com os dias da semana
        $diasemana = array('Domingo', 'Segunda-Feira', 'Terca-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sabado');
        $data = date('Y-m-d');
        $diasemana_numero = date('w', strtotime($data));
        return $diasemana[$diasemana_numero];
    }
    function pegarSemanaEditada($data)
    {
        // Array com os dias da semana
        $diasemana = array('Domingo', 'Segunda-Feira', 'Terca-Feira', 'Quarta-Feira', 'Quinta-Feira', 'Sexta-Feira', 'Sabado');
        $diasemana_numero = date('w', strtotime($data));
        return $diasemana[$diasemana_numero];
    }

    function registroMovimentacao($processo, $registroFunc)
    {
        $empreendimento = $_COOKIE['emp'];
        $administrador = $_COOKIE['adm'];
        $hora = $this->pegarHora();
        $data = $this->pegarData();
        $conn = $this->pegarServ();
        $sql = "insert into tabelaMovimentacao (administrador,empreendimento , processo, data, hora, registro) values ('$administrador', '$empreendimento', '$processo', '$data', '$hora', '$registroFunc')";
        $conn->query($sql);
    }


    //Usado para pegar a Data atual do Computador
    function pegarData()
    {
        date_default_timezone_set('America/Sao_Paulo');
        return date('d-m-Y');
    }

    function pegarDataBanco()
    {
        date_default_timezone_set('America/Sao_Paulo');
        return date('Y-m-d');
    }

    //Usado para pegar a Hora atual do computador
    function pegarHora()
    {
        date_default_timezone_set('America/Sao_Paulo');
        return date('H:i:s');
    }
    function pegarMinutoHora()
    {
        date_default_timezone_set('America/Sao_Paulo');
        return date('H:i');
    }

    function cadastrarConsultor($nome, $sobrenome, $func, $senha, $email, $cpf, $nascimento, $ponto, $pontoFind, $administrador, $tabela)
    {
        $conn = $this->pegarServ();
        $registro = "Cadastrado Dia:" . $this->pegarData() . " - Hora:" . $this->pegarHora();
        $sql = "INSERT into " . $tabela .  "(nome, sobrenome,funcao, senha, email, cpf,nascimento, ponto, pontoFind, registro, administrador) values ('$nome','$sobrenome','$func', '$senha','$email', '$cpf', '$nascimento', '$ponto','$pontoFind', '$registro', '$administrador')";
        $conn->query($sql);

        $cpf1 = str_replace(".", "", $cpf);
        $cpf1 = str_replace("-", "", $cpf1);
        $pegaId = $this->retornaAgenteCpf($cpf, $tabela);

        $this->criarTabelaBancoDados("t_" . $pegaId['id_agente'] . "_" . $cpf1);
        $pegaTabela = "t_" . $pegaId['id_agente'] . "_" . $cpf1;
        $sql = "update " . $tabela .  " set tabela = '" . $pegaTabela . "' where id_agente =" . $pegaId['id_agente'];
        $conn->query($sql);
    }
}//fim da classe