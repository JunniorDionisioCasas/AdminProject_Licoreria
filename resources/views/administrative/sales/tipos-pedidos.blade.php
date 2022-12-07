@extends('adminlte::page')

@section('title', 'Tipos de pedidos')

@section('content_header')
    <h1>Tabla Tipos de Pedidos</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <button id="btnCrear" type="button" class="btn btn-secondary btn-crear">Nuevo tipo de pedido</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla_tipo_pedidos" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="lista_tipo_pedidos">
                        <!-- lista de tipo de pedidos mediante api -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalCRUD" class="modal" aria-labelledby="Formulario de nuevo tipo de pedidos" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo tipo de pedido</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formTipoPedido">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input id="idTipoPedido" type="hidden">
                            <div class="form-group row">
                                <label for="nombreTipoPedido" class="col-sm-3 col-form-label">Tipo de Pedido*</label>
                                <div class="col-sm-9">
                                    <input id="nombreTipoPedido" type="text" class="form-control" placeholder="Ingrese el nombre del tipo de pedido" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="descTipoPedido" class="col-sm-3 col-form-label">Descripción</label>
                                <div class="col-sm-9">
                                    <textarea id="descTipoPedido" rows="2" class="form-control" placeholder="Ingrese una descripción del tipo de pedido (opcional)"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
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
        // datatables config
        let dataTableTipoPedidos = $('#tabla_tipo_pedidos').DataTable({
            "ajax":{
                "url":urlDominio+'api/tipo_pedidos',
                "type": "GET",
                "dataSrc":""
            },
            "columns":[
                {"data":"id_tipo_pedido"},
                {"data":"tpe_nombre"},
                {
                    "data":"tpe_descripcion",
                    "defaultContent":"<i>Sin descripción</i>",
                    "orderable":false
                },
                {
                    "defaultContent":`<button class="btn btn-xs btn-default text-primary mx-1 shadow btnEditar" title="Editar">
                                        <i class="fa fa-lg fa-fw fa-pen"></i>
                                    </button>
                                    <button class="btn btn-xs btn-default text-danger mx-1 shadow btnEliminar" title="Eliminar">
                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                    </button>`,
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
                            columns: [0,1,2]
                        }
                    },
                    {
                        extend: 'excel',
                        text:'<i class="fas fa-file-excel"></i>',
                        titleAttr:'Formato Excel',
                        className: 'excelButton',
                        exportOptions: {
                            columns: [0,1,2]
                        }
                    },
                    {
                        extend: 'csv',
                        text:'<i class="fas fa-file-csv"></i>',
                        titleAttr:'Formato .csv',
                        className: 'csvButton',
                        exportOptions: {
                            columns: [0,1,2]
                        }
                    },
                    {
                        extend: 'print',
                        text:'<i class="fas fa-print"></i>',
                        titleAttr:'Imprimir',
                        className: 'printButton',
                        exportOptions: {
                            columns: [0,1,2]
                        }
                    }
                ]
            }
        });

        // CRUD logic
        let opcion, fila, id, nombre, descripcion;

        //Crear
        $('#btnCrear').click(function (){
            opcion = 'crear';
            $("#formTipoPedido").trigger("reset");
            $('.modal-header').css("background-color", "#6c757d");
            $('.modal-title').text("Nuevo tipo de pedido");
            $('#modalCRUD').modal('show');
        })

        //Editar
        $(document).on('click', '.btnEditar', function (){
            opcion = 'editar';
            fila = $(this).closest('tr');

            id = fila.find('td:eq(0)').text();
            nombre = fila.find('td:eq(1)').text();
            descripcion = fila.find('td:eq(2)').text();

            $("#idTipoPedido").val(id);
            $("#nombreTipoPedido").val(nombre);
            $("#descTipoPedido").val(descripcion);

            $('.modal-header').css("background-color", "#007bff");
            $('.modal-title').text("Editar tipo de pedido");
            $('#modalCRUD').modal('show');
        })

        //Borrar
        $(document).on('click', '.btnEliminar', function (){
            fila = $(this).closest('tr');
            id = parseInt(fila.find('td:eq(0)').text());

            Swal.fire({
                title: 'Confirma eliminar el tipo de pedido?',
                showCancelButton: true,
                showConfirmButton: true,
                type: 'warning',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar',
            }).then( (result) => {
                if (result.value == true) {
                    //api tipo_pedido/id, delete
                    let url = urlDominio+'api/tipo_pedido/'+id;

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                        }
                    })
                    .then(res => res.json())
                    .then(success => {
                        dataTableTipoPedidos.ajax.reload(null, false);
                        Swal.fire('Tipo de pedido eliminado', '', 'success');
                        console.log(success);
                    })
                    .catch(error => console.log(error));
                }
            })
        })

        //submit form crear o editar
        $("#formTipoPedido").submit(function (e){
            e.preventDefault();
            id = $('#idTipoPedido').val();
            nombre = $('#nombreTipoPedido').val();
            descripcion = $('#descTipoPedido').val();

            if(opcion == 'crear'){
                //api tipo_pedido, post
                let url = urlDominio + 'api/tipo_pedido';
                fetch(url, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({tpe_nombre: nombre, tpe_descripcion: descripcion})
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'El tipo de pedido se registró exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            dataTableTipoPedidos.ajax.reload(null, false);
                        });
                        $('#modalCRUD').modal('hide');
                    })
                    .catch(error => console.log(error));
            }
            if(opcion == 'editar'){
                //api tipo_pedido (update)
                let url = urlDominio + 'api/tipo_pedido/'+id;
                fetch(url, {
                    method: 'PUT',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({tpe_nombre: nombre, tpe_descripcion: descripcion})
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'El tipo de pedido se actualizó exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then( (result) => {
                            dataTableTipoPedidos.ajax.reload(null, false);
                        });

                        $('#modalCRUD').modal('hide');
                    })
                    .catch(error => console.log(error));
            }
        });
    </script>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)