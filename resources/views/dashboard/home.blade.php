@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Bienvenido(a) {{\Auth::user()->name}}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col">
            <canvas id="barChart" aria-label="Gráfico de barras de ventas en la semana" role="img"></canvas>
        </div>
        <div class="col">
            <canvas id="pieChart" aria-label="Gráfico pie de productos más vendidos" role="img"></canvas>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/urlDomain.js"></script>
    <script>
        const url = urlDominio+'api/admin_home_info';
        const options = { style: 'currency', currency: 'PEN' };
        const numberFormat = new Intl.NumberFormat('es-PE', options);

        //llamado al api provincias, index
        fetch(url, {
            method: 'GET',
            headers: {
                "Content-Type": "application/json",
            }
        })
        .then(res => res.json())
        .then(res => {
            console.log(res);
            loadCharts(res);
        })
        .catch(error => console.log(error));

        let loadCharts = (responseObject) => {
            const barChartData = responseObject.barChartData;
            const pieData = responseObject.pieData;

            //bar chart
            new Chart(document.getElementById('barChart'), {
                type: 'bar',
                data: {
                    labels: barChartData.map(row => (row.nombre_dia+'\n'+row.pdd_fecha)),
                    datasets: [{
                        label: 'Monto en ventas por día',
                        data: barChartData.map(row => row.total_dia),
                        borderWidth: 1
                    }]
                },
                options: {
                    aspectRatio: 1,
                    scales: {
                        y: {
                            ticks: {
                                callback: value => numberFormat.format(value)
                            }
                        }
                    }
                },
            });

            //pie chart
            new Chart(document.getElementById('pieChart'), {
                type: 'pie',
                data: {
                    labels: pieData.map(row => row.prd_nombre),
                    datasets: [{
                        label: 'Cantidad vendida',
                        data: pieData.map(row => row.cant_vendidos),
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Productos más vendidos'
                        }
                    }
                },
            });
        }
    </script>
@stop