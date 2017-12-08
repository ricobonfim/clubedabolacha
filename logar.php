<?php
if(!$_POST || !$_POST["parametros"]){
    header('Location:login.php');
}else{
    $obj = json_decode($_POST["parametros"]);
    $usuario = $obj->usuario;
    $senha = $obj->senha;
    $arquivo = "9d07c616b4eccceaf52e3fbea1ebbf29.txt";
    $myfile = fopen($arquivo, "r") or header('Location:login.php');
    $ls = preg_split('/\R/', fread($myfile, filesize($arquivo)));
    if($usuario == $ls[0] && $senha == $ls[1]){
        session_start();
        $_SESSION["usuario"] = true;
        echo json_encode(array('login' => 1));
    }else{
        echo json_encode(array('login' => 0));
    }
        
}