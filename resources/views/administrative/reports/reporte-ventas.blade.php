@extends('adminlte::page')

@section('title', 'Reporte de ventas')

@section('content_header')
    <h1>Tabla Reporte de ventas</h1>
@stop

@section('content')

    reporte de ventas

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)