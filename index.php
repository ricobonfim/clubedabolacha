<html>
<head>
    <title>Controle da caixinha</title>
    <link rel="shortcut icon" type="image/png" href="favicon.ico"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
</head>
<body>
    <br/>
    <div class="container-fluid">
    <?php 
        $ultimosGastos = array();

        $ultimosGastos[] = array(
            'data' => "04/12/2017",
            'valor' => 14.92
        );

        $saldos[] = array(
            'dataFechamento' => "01/12/2017",
            'caixaAtual' => 65.00
        );
    ?>
        <h4>Saldo caxinha: <?php echo "R$" . number_format($saldos[count($saldos)-1]['caixaAtual'], 2, ',', '.'); ?></h4>
        <table class='table table-hover'>
            <thead>
                <tr>
                    <th>Data Fechamento</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_reverse($saldos) as $saldo) : ?>
                    <tr>
                        <td><?php echo $saldo['dataFechamento']; ?></td>
                        <td><?php echo "R$" . number_format($saldo['caixaAtual'], 2, ',', '.'); ?></td>
                    </tr>            
                <?php endforeach; ?>
            </tbody>
        </table>

        <br>
        <br>
        <br>
        <br>

        <h4>Últimos gastos</h4>
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
                        <td><a href="comprovantes/<?php echo str_replace('/', '_', $gasto['data']) ?>.jpg">Comprovante</a></td>
                    </tr>            
                <?php endforeach; ?>
            </tbody>
        </table>
</div>
</body>
</html>
