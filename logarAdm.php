<?php
include_once 'superServidor.php';

//Usado para Logar na conta ja criada
$logar = new classe();
$senhaPr = "HDDyna15@@";

if (!empty($_POST["senha"])) {
    $senha = $_POST["senha"];
} else {
    $senha = "";
}
if (!empty($_POST["nomeCompleto"])) {
    $nome = $_POST["nomeCompleto"];
} else {
    $nome = "";
}
$tempo = time() + (3600 * 24 * 30 * 3);


if (!empty($_POST['cpf'])) {
    $cpf = $_POST['cpf'];
} else {
    $cpf = $_COOKIE["aviso"];
}

$verLogar = $logar->verCpfAdm($cpf);

if (!empty($verLogar)) {    
    $nome = $verLogar['nomeCompleto'];  
    setcookie("aviso", null);
    setcookie("adm", $nome, $tempo);
    header("location:painelAdministrativo.php");
    exit(0);
} else {   
    if ($senha == "" && $nome == "") {      
        setcookie("aviso", $cpf);
        header("Location:painelAdministrativo-senha.php");
    } else {       
        if ($senha == $senhaPr) {           
            $logar->cadastrarAdm($nome, $cpf);
            setcookie("aviso", null);
            setcookie("adm", $nome, $tempo);
            header("location:painelAdministrativo.php");
            exit(0);
        } else {         
            header("Location:painelAdministrativo-senha.php");
        }
    }
}



?>


</html>