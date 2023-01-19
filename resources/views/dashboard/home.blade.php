@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Bienvenido {{\Auth::user()->name}}</p>
    @php
        echo(Carbon\Carbon::now('-05:00')->isoFormat('d'));
        $weekDays = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $today = Carbon\Carbon::now('-05:00')->isoFormat('d');
        $barChartsLabels = [];
        for($i=0; $i<7; $i++){
            $weekIndex = $today + $i + 1;
            if($weekIndex>6) $weekIndex = $weekIndex - 7;
            $barChartsLabels[$i] = $weekDays[$weekIndex];
        }
        print_r($barChartsLabels);
    @endphp
    <div>
        <canvas id="myChart" aria-label="Gráfico de ventas" role="img"></canvas>
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
        })
        .catch(error => console.log(error));

        const ctx = document.getElementById('myChart');
        /* const data = [
            { year: 2010, count: 10 },
            { year: 2011, count: 20 },
            { year: 2012, count: 15 },
            { year: 2013, count: 25 },
            { year: 2014, count: 22 },
            { year: 2015, count: 30 },
            { year: 2016, count: 28 },
        ];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(row => row.year),
                datasets: [{
                    label: 'Acquisitions by year',
                    data: data.map(row => row.count),
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
        }); */
    </script>
@stop