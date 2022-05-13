<?php
include_once "superServidor.php";
$pegaCookies = explode("_", $_COOKIE["agentes"]);
$empreendimento = strtolower(str_replace(" ", "_", $pegaCookies[1])); //Captura o Empreendimento escolhido
$registrador = new classe();
if (isset($_POST['REGISTRAR'])) {
    $nome = $registrador->retornaCookies($empreendimento, $pegaCookies[0]);
    $pegaCpf = str_replace(".", "", $nome['cpf']);
    $pegaCpf = str_replace("-", "", $pegaCpf);
    $registrador->registrarPontoAgente("t_" . $nome["id_agente"] . "_" . $pegaCpf);
    header("Location:agente.php");
} else if (isset($_POST['CANCELAR'])) {
    header("Location:agente.php");
} else if (isset($_POST['ACESSO_FUNC'])) {
    setcookie("nome", null);
    setcookie("agentes", null);
    setcookie("aviso", null);
    setcookie("cpf", null);
    header("Location:index.php");
} else if (isset($_POST['CADASTRO_FUNC'])) {
    header("Location:cadastrarAgente.php");
} else if (isset($_POST['PAINEL_EMP'])) {
    header("Location:painelEmpreendimento.php");
}else if (isset($_POST['DELETAR_SENHA'])) {
    $separador = explode("_", $_COOKIE['pdf']);
    $pdfCompleto = $registrador->stringToPdf($separador[1]);
    $pegaCadastro = $registrador->verificarAgenteCriado($empreendimento, $pdfCompleto);
    $registrador->resetarSenha($empreendimento, $pegaCadastro["id_agente"]);   
    header("Location:listaspontoFuncionario.php");
} else if (isset($_POST['REGISTRAR_PONTO'])) {
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $justificativa = $_POST['justificativa'];
    $separador = explode("_", $_COOKIE['pdf']);
    $pdfCompleto = $registrador->stringToPdf($separador[1]);
    $id = $_COOKIE['id'];
    $empreendimento = strtolower(str_replace(" ", "_", $_COOKIE['emp']));
    $pegaCadastro = $registrador->verificarAgenteCriado($empreendimento, $pdfCompleto);
    $pegarData = $registrador->pegarSomaDataTabela($pegaCadastro['tabela'], $data);
    if ($pegarData == 4) {
        header("Location:painelAdministrativo.php");
    } else {
        $registrador->admRegistrarPontoAgente("t_" . $pegaCadastro["id_agente"] . "_" . $separador[1], $data, $hora, $justificativa);
        $registrador->registroMovimentacao("Registrar_ponto", "Ponto Registrado Manualmente do Funcionário: " . $pegaCadastro['nome'] . " " . $pegaCadastro['sobrenome'] . " - Data:" . $data . " - Hora:" . $hora);
        header("Location:listaspontoFuncionario.php");
    }
} else if (isset($_POST['PESQUISA_DATA'])) {
    $mes = $_POST['mes'];
    $ano = $_POST['ano'];
    setcookie("data", $mes . "_" . $ano);
    header("Location:outrosRelatorios.php");
} else if (isset($_POST['PESQUISA_DATA_ADM'])) {
    $mes = $_POST['mes'];
    $ano = $_POST['ano'];
    setcookie("data", $mes . "_" . $ano);
    header("Location:meusRegistros.php");
} else if (isset($_POST['VOLTAR_MENU'])) {
    header("Location:listaspontoFuncionario.php");
} else if (isset($_POST['VOLTAR_MENU_EMP_REG'])) {
    if (empty($_COOKIE['emp'])) {
        header("Location:painelAdministrativo.php");
    } else {
        header("Location:painelAdministrativoEmpFunc.php");
    }
} else if (isset($_POST['IMPRIMIR_PDF'])) {
    header("Location:imprimirPdf.php");
} else if (isset($_POST['DEMITIR_FUNC'])) {
    //header("Location:painelAdministrativo.php");
} else if (isset($_POST['CADASTRAR_EMPREENDIMENTO'])) {
    $hora = $registrador->pegarHora();
    $data = $registrador->pegarData();
    $nome = strtolower($_POST['nome']);    
    $registro = "Cadastrado no dia: " . $data . " - Hora: " . $hora;
    if ($registrador->verificarEmpreendimentoExiste("tabelaEmpreendimento", $nome) == "") {
        $registroCookies = $nome . "_" . $data . "_" . $hora;
        $registrador->cadastrarEmpreendimento("tabelaEmpreendimento", $nome, $registro);
        $registrador->criarTabelaExcluir($nome);//cria tabela para funcionário que forem Demitidos
        setcookie("aviso", $registroCookies, time() + 3600);
        $registrador->registroMovimentacao("Cadastro_Emp", $registro);
    } else {
        setcookie("aviso", "ATENÇÃO O Empreendimento '" . $nome . "' Já foi criado", time() + 3600);
    }
    header("Location:criarEmpreendimento.php");
} else {   
    header("Location:painelAdministrativo.php");
}
