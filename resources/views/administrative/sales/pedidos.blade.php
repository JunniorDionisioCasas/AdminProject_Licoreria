@extends('adminlte::page')

@section('title', 'Pedidos')

@section('content_header')
    <h1>Tabla Pedidos</h1>
@stop

@section('content')

    pedidos

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)