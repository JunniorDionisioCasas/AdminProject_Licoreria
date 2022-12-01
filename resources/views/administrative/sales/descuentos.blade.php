@extends('adminlte::page')

@section('title', 'Descuentos')

@section('content_header')
    <h1>Tabla Descuentos</h1>
@stop

@section('content')

    descuentos

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)