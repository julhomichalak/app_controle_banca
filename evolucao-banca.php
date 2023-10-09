<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico de Barras</title>
    <!-- Inclua o Bootstrap CSS e o Chart.js -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <canvas id="graficoBarras"></canvas>
    </div>

    <script>
        // Seus arrays com os valores do campo "qnt_unidade"
        var seusArrays = [
            <?php
            $seusArrays = array(
                array("valor_banca" => 100),
                array("valor_banca" => 550),
                array("valor_banca" => 1200),
                // Outros arrays aqui
            );
            foreach ($seusArrays as $array) {
                echo $array["valor_banca"] . ",";
            }
            ?>
        ];

        // Configuração do gráfico
        var ctx = document.getElementById('graficoBarras').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Array 1", "Array 2", "Array 3"], // Rótulos para cada conjunto de dados
                datasets: [{
                    label: 'Valor da banca',
                    data: seusArrays,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>