<html>
<head>
    <title>Controle da caixinha</title>
    <link rel="shortcut icon" type="image/png" href="favicon.ico"/>
    <link rel="stylesheet" href="content/css/bootstrap.min.css">

    <style type="text/css">
        #bob-gif {
            width: 300px;
        }
    </style>
</head>
<body>
    <br/>
    <div class="container-fluid">
    <?php 
        
        $nomeArquivo = 'dados.json';
        $contents    = json_decode(file_get_contents($nomeArquivo), true);
        $ultimosGastos = $contents["gastos"];
        $saldos = $contents["saldos"];

        function getSaldoAtual(array $saldos) : float {
            $total = 0;
            foreach ($saldos as $saldo) {
                $total += $saldo['totalContribuicoes']-$saldo['gastosUltimoFechamento'];
            }
            return $total;
        }

        function getSaldoParcial(array $saldos, int $index) : float {
            $saldosParcial = array_slice($saldos, 0, count($saldos)-$index);
            $total = 0;
            foreach ($saldosParcial as $saldo) {
                $total += $saldo['totalContribuicoes']-$saldo['gastosUltimoFechamento'];
            }
            return $total;
        }
    ?>
        <h4>Saldo caxinha: <?php echo "R$" . number_format(getSaldoAtual($saldos), 2, ',', '.'); ?></h4>
        <table class='table table-hover'>
            <thead>
                <tr>
                    <th>Data Fechamento</th>
                    <th>Custos ultimo fechamento</th>
                    <th>Total Contribuições</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_reverse($saldos) as $index => $saldo) : ?>
                    <tr>
                        <td><?php echo $saldo['dataFechamento']; ?></td>
                        <td><?php echo "R$" . number_format($saldo['gastosUltimoFechamento'], 2, ',', '.'); ?></td>
                        <td><?php echo "R$" . number_format($saldo['totalContribuicoes'], 2, ',', '.'); ?></td>
                        <td><?php echo "R$" . number_format(getSaldoParcial($saldos, $index), 2, ',', '.'); ?></td>
                    </tr>            
                <?php endforeach; ?>
            </tbody>
        </table>

        <br>
        <br>
        <br>
        <br>

        <h4>Últimos gastos</h4>
        <div class="row">
            <div class="col-md-12">
                <table class='table table-hover'>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Gasto</th>
                            <th>Comprovante</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_reverse($ultimosGastos) as $gasto) : ?>
                            <tr>
                                <td><?php echo $gasto['data']; ?></td>
                                <td><?php echo "R$" . number_format($gasto['valor'], 2, ',', '.'); ?></td>
                                <td><a target="_blank" href="comprovantes/<?php echo str_replace('/', '_', $gasto['data']) ?>.jpg">Comprovante</a></td>
                            </tr>            
                        <?php endforeach; ?>
                    </tbody>
                </table>                
            </div>
        </div>
    </div>
</body>
</html>
