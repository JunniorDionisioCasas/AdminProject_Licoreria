@extends('adminlte::page')

@section('title', 'Empleados')

@section('content_header')
    <h1>Tabla Empleados</h1>
@stop

@section('content')

    empleados

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)