<?php
    session_start();
    if ($_SESSION && $_SESSION["usuario"]) {
        header('Location:cadastrar.php');
    }
	if($_POST){
		if($_POST["parametros"]){
			$obj = json_decode($_POST["parametros"]);
			$usuario = $obj->usuario;
			$senha = $obj->senha;
			$arquivo = "9d07c616b4eccceaf52e3fbea1ebbf29.txt";
			$myfile = fopen($arquivo, "r");
			$ls = preg_split('/\R/', fread($myfile, filesize($arquivo)));
			if($usuario == $ls[0] && $senha == $ls[1]){
				session_start();
				$_SESSION["usuario"] = true;
				echo json_encode(array('login' => 1));die;
			}else{
				echo json_encode(array('login' => 0));die;
			}
		}
	}
?>
<head>
    <title>Login - Controle da caixinha</title>
    <link rel="shortcut icon" type="image/png" href="favicon.ico"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
</head>
<div class="container-fluid">
    <div class="form-group">
        <label for="usuario">Login</label>
        <input type="text" class="form-control" id="usuario" arplaceholder="Login">
    </div>
    <div class="form-group">
        <label for="senha">Senha</label>
        <input type="password" class="form-control" id="senha" arplaceholder="Senha">
    </div>
    <input type="button" class="btn btn-primary" onclick="logar()" value="Login" />
</div>
<div class="modal fade" id="modalErro" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ERRO</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Login ou senha incorretos!
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
<script>
    function logar () {
        var u = document.getElementById("usuario").value;
        var s = document.getElementById("senha").value;
        var xhr = new XMLHttpRequest();
        var url = "login.php";
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {//Call a function when the state changes.
            if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
                var retorno = JSON.parse(xhr.response);
				console.log(retorno);
                if(retorno.login){
                    window.location.replace('login.php');
                }else{
                    $('#modalErro').modal();
                }
            }
        }
        var data = "parametros=" + JSON.stringify({usuario: u, senha: s});
        xhr.send(data);
    }
</script>