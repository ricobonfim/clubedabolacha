<?php
    echo '<pre>';
    $nomeArquivo = 'dados.json';
    $contents = json_decode(file_get_contents($nomeArquivo), true);
    var_dump($contents);die;
    $myfile = fopen($nomeArquivo, "w+") or die("Unable to open file!");
    $obj = array("dataFechamento" => "08/12/2017", "totalContribuicoes" => 0, "gastosUltimoFechamento" => 0);
    array_push($contents["dados"], $obj);
    fwrite($myfile, json_encode($contents));
    fclose($myfile);