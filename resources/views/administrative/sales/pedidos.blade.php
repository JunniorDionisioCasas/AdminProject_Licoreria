@extends('adminlte::page')

@section('title', 'Pedidos')

@section('content_header')
    <h1>Tabla Pedidos</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <button id="btnCrear" type="button" class="btn btn-secondary btn-crear">Registrar nuevo pedido</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla_pedidos" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Comprobante</th>
                            <th scope="col">Num Comprobante</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Cliente Apellidos</th>
                            <th scope="col">Total</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Estado code</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="lista_pedidos">
                        <!-- lista de pedidos mediante api -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalReceipt" class="modal" aria-labelledby="Comprobante de pedido" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Comprobante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Receipt goes here</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-print"></i>
                        Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="js/urlDomain.js"></script>
    <script>
        let dataTableProductos = $('#tabla_pedidos').DataTable({
            "ajax":{
                "url":urlDominio+'api/pedidos',
                "type": "GET",
                "dataSrc":""
            },
            "columns":[
                {
                    "data":"id_pedido",
                    visible: false
                },
                {
                    "data":"cmp_serie",
                    render: function (data, type, row, meta) {
                        return data + '-' + row['cmp_numero'];
                    },
                },
                {
                    "data":"cmp_numero",
                    visible: false
                },
                {
                    "data":"name",
                    render: function (data, type, row) {
                        let apellidos = row['usr_apellidos'];
                        if ( apellidos ) {
                            return data + ' ' + apellidos;
                        } else {
                            return data;
                        }
                    },
                },
                {
                    "data":"usr_apellidos",
                    visible: false
                },
                {
                    "data":"pdd_total",
                    render(v){
                        return 'S/ '+Number(v).toFixed(2)
                    }
                },
                {"data":"pdd_fecha_entrega"},
                {
                    "data":"pdd_estado",
                    render: function (data, type, row) {
                        switch(parseInt(data)) {
                            case 0:
                                return `<span class="badge badge-pill badge-danger">Por pagar</span>`;
                                break;
                            case 1:
                                return `<span class="badge badge-pill badge-success">Pagado</span>
                                        <span class="badge badge-pill badge-danger">Por enviar</span>`;
                                break;
                            case 2:
                                return `<span class="badge badge-pill badge-success">Pagado</span>
                                        <span class="badge badge-pill badge-warning">Enviado</span>`;
                                break;
                            case 3:
                                return `<span class="badge badge-pill badge-success">Pagado</span>
                                        <span class="badge badge-pill badge-success">Recibido</span>`;
                                break;
                            default:
                                return `<span class="badge badge-pill badge-secondary">Error de estado, consulte al administrador</span>`;
                        }
                    },
                },
                {
                    "data":"pdd_estado",
                    visible: false
                },
                {
                    render: function (data, type, row) {
                        let btnVer = `<button class="btn btn-xs btn-default text-primary mx-1 shadow btnVer" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </button>`;
                        
                        switch(row['pdd_estado']) {
                            case 0:
                                return btnVer+`<button class="btn btn-xs btn-default mx-1 shadow btnPagar" title="Ya pagó">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </button>`;
                                break;
                            case 1:
                                return btnVer;
                                break;
                            case 2:
                                return btnVer;
                                break;
                            case 3:
                                return btnVer;
                                break;
                            default:
                                return btnVer;
                        }
                    },
                    "orderable":false
                }
            ],
            autoWidth: false,
            language: {
                url: 'vendor/datatables-plugins/internationalisation/es-ES.json'
            },
            dom:"<'row'<'col-sm-12 col-md-7'lB><'col-sm-12 col-md-5'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: {
                buttons: [
                    {
                        extend: 'copy',
                        text:'<i class="fas fa-copy"></i>',
                        titleAttr:'Copiar',
                        className: 'copyButton',
                        exportOptions: {
                            columns: [1,3,5,6,7]
                        }
                    },
                    {
                        extend: 'excel',
                        text:'<i class="fas fa-file-excel"></i>',
                        titleAttr:'Formato Excel',
                        className: 'excelButton',
                        exportOptions: {
                            columns: [1,3,5,6,7]
                        }
                    },
                    {
                        extend: 'csv',
                        text:'<i class="fas fa-file-csv"></i>',
                        titleAttr:'Formato CSV',
                        className: 'csvButton',
                        exportOptions: {
                            columns: [1,3,5,6,7]
                        }
                    },
                    {
                        extend: 'print',
                        text:'<i class="fas fa-print"></i>',
                        titleAttr:'Imprimir',
                        className: 'printButton',
                        exportOptions: {
                            columns: [1,3,5,6,7]
                        }
                    }
                ]
            }
        });

        // CRUD logic
        let fila, id;

        document.getElementById("btnCrear").addEventListener("click", function() {
            window.location.assign("/registrar-nuevo-pedido");
        });

        $(document).on('click', ".btnVer", function (){
            $('#modalReceipt').modal('show');
        });

        $(document).on('click', ".btnPagar", function (){
            fila = $(this).closest('tr');
            id = parseInt(fila.find('td:eq(0)').text());
            Swal.fire({
                title: 'Confirma pedido pagado?',
                showCancelButton: true,
                showConfirmButton: true,
                type: 'warning',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar',
            }).then( (result) => {
                console.log(result);
                if (result.value == true) {
                    //api pedido_pagado/id put
                    let url = urlDominio+'api/pedido_pagado/'+id;

                    fetch(url, {
                        method: 'PUT',
                        headers: {
                            "Content-Type": "application/json",
                        }
                    })
                    .then(res => res.json())
                    .then(success => {
                        dataTableProductos.ajax.reload(null, false);
                        Swal.fire('Pedido pagado', '', 'success');
                    })
                    .catch(error => console.log(error));
                }else{
                    console.log('cancelado');
                }
            })
        });
    </script>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)