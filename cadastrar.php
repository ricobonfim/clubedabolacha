<?php
session_start();
if(!$_SESSION["usuario"])
    header('Location:login.php');
    if($_POST && $_POST["parametros"]){
    $data = json_decode($_POST["parametros"], true);
    $nomeArquivo = 'dados.json';
    $contents = json_decode(file_get_contents($nomeArquivo), true);
    $myfile = fopen($nomeArquivo, "w+") or die("Unable to open file!");
    if($data['tipo'] == 'saldo'){
        $obj = array("dataFechamento" => $data["dataFechamento"], "totalContribuicoes" => floatval(str_replace(',', '.', $data["totalContribuicoes"])), "gastosUltimoFechamento" => floatval(str_replace(',', '.', $data["gastosUltimoFechamento"])));
        array_push($contents["saldos"], $obj);
    }else{
        $obj = array("data" => $data["data"], "valor" => floatval(str_replace(',', '.', $data["valor"])));
        array_push($contents["gastos"], $obj);
        $imagem = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data["imagem"]));
        file_put_contents('comprovantes/'.str_replace("/", "_", $data["data"]).'.jpg', $imagem);
    }
    fwrite($myfile, json_encode($contents));
    fclose($myfile);
    echo json_encode(array('sucesso' => 1));die;
}
?>
<head>
    <title>Cadastro - Controle da caixinha</title>
    <link rel="shortcut icon" type="image/png" href="favicon.ico"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
</head>
<div class="container-fluid">
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="selecao" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Selecione uma opção
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" onclick="formGastos()">Gastos</a>
            <a class="dropdown-item" onclick="formSaldos()">Saldos</a>
        </div>
    </div>
    <div id="form"></div>
</div>
<div class="modal fade" id="modalSucesso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Cadastrado com sucesso!</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>
<script src="//code.jquery.com/jquery-3.1.0.min.js" ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
<script>

    function formSaldos () {
        var fm = '<div class="form-group">';
        fm += '<label for="data">Data Fechamento</label>';
        fm += '<input class="form-control" id="data" type="date"></input>';
        fm += '</div>';
        fm += '<div class="form-group">';
        fm += '<label for="data">Custos último fechamento</label>';
        fm += '<input class="form-control" id="custos" type="number"></input>';
        fm += '</div>';
        fm += '<div class="form-group">';
        fm += '<label for="data">Total Contribuições</label>';
        fm += '<input class="form-control" id="total" type="number"></input>';
        fm += '<input type="button" class="btn btn-primary" onclick="cadastrarSaldo()" value="Cadastrar" />';
        fm += '</div>';
        $('#form').html(fm);
        document.getElementById('data').value = new Date().toDateInputValue();
    }

    function formGastos () {
        var fm = '<form id="formulario" method="post" enctype="multipart/form-data"><div class="form-group">';
        fm += '<label for="data">Data</label>';
        fm += '<input class="form-control" id="data" type="date"></input>';
        fm += '</div>';
        fm += '<div class="form-group">';
        fm += '<label for="data">Valor</label>';
        fm += '<input class="form-control" id="valor" type="number"></input>';
        fm += '</div>';
        fm += '<div class="form-group">';
        fm += '<label for="data">Comprovante</label>';
        fm += '<input class="form-control" id="comprovante" type="file"></input>';
        fm += '<input type="button" class="btn btn-primary" id="cadastrar" value="Cadastrar" />';
        fm += '</div></form>';
        $('#form').html(fm);
        document.getElementById('data').value = new Date().toDateInputValue();
    }

    function cadastrarSaldo () {
        var data = formatarData($('#data').val());
        var custos = $('#custos').val();
        var total = $('#total').val();
        var obj = JSON.stringify({tipo: 'saldo', dataFechamento: data, gastosUltimoFechamento: custos, totalContribuicoes: total});
        
        var xhr = new XMLHttpRequest();
        var url = "cadastrar.php";
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {//Call a function when the state changes.
            if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
                var retorno = JSON.parse(xhr.response);
                if(retorno.sucesso){
                    $('#modalSucesso').modal();
                    $('#form').html('');
                }
            }
        }
        var data = "parametros=" + obj;
        xhr.send(data);
    }

    function formatarData (data){
        var newData = data.split('-');
        newData = newData[2] + "/" + newData[1] + "/" + newData[0];
        return newData;
    }

    function cadastrou() {
        $('#modalSucesso').modal();
    }

    Date.prototype.toDateInputValue = (function() {
        var local = new Date(this);
        local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
        return local.toJSON().slice(0,10);
    });

$(document).on('click', '#cadastrar', function() {
    var files = document.getElementById('comprovante').files;
    if (files.length > 0) {
        var reader = new FileReader();
        reader.readAsDataURL(files[0]);
        reader.onload = function () {
            var imagem = reader.result;
            var obj = JSON.stringify({tipo: 'gasto', imagem: imagem, data: formatarData($('#data').val()), valor: $("#valor").val()});
            $.ajax({
                url: "cadastrar.php",
                type: "POST",
                data: {parametros: obj},
                dataType: "json",
                success: function(data){
                    $('#modalSucesso').modal();
                    $('#form').html('');
                }
            });
        };
        reader.onerror = function (error) {
            alert("Erro ao fazer upload do arquivo!");
        };
    }
});
</script>
