@extends('adminlte::page')

@section('title', 'Proveedores')

@section('content_header')
    <h1>Tabla Proveedores</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <button id="btnCrear" type="button" class="btn btn-secondary btn-crear">Nuevo proveedor</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla_proveedores" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Anotaciones</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="lista_proveedores">
                        <!-- lista de proveedores mediante api -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalCRUD" class="modal" aria-labelledby="Formulario de nuevo proveedor" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo proveedor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formProveedor">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input id="idProveedor" type="hidden">
                            <div class="form-group row">
                                <label for="nombreProveedor" class="col-sm-3 col-form-label">Proveedor</label>
                                <div class="col-sm-9">
                                    <input id="nombreProveedor" type="text" class="form-control" placeholder="Ingrese el nombre del proveedor" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="anotProveedor" class="col-sm-3 col-form-label">Anotaciones</label>
                                <div class="col-sm-9">
                                    <textarea id="anotProveedor" rows="2" class="form-control" placeholder="Ingrese notas sobre el proveedor (opcional)"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Estado</label>
                                <div class="col-sm-9 centerVertical">
                                    <div class="custom-control custom-switch">
                                        <input id="estadoProveedor" class="custom-control-input" type="checkbox" role="switch" checked>
                                        <label id="labelSttPrv" for="estadoProveedor" class="custom-control-label">
                                            <span class="badge badge-pill badge-success">Activo</span>
                                        </label>
                                    </div>
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
        let dataTableProveedores = $('#tabla_proveedores').DataTable({
            "ajax":{
                "url":urlDominio+'api/proveedores',
                "type": "GET",
                "dataSrc":""
            },
            "columns":[
                {"data":"id_proveedor"},
                {"data":"prv_nombre"},
                {
                    "data":"prv_anotaciones",
                    "defaultContent":"<i>Sin anotaciones</i>",
                    "orderable":false
                },
                {
                    "data":"prv_estado",
                    "render": function ( data, type, row, meta ) {
                        if (data == 1) {
                            return `<span class="badge badge-pill badge-success">Activo</span>`;
                        } else {
                            return `<span class="badge badge-pill badge-danger">Inactivo</span>`;
                        }
                    }
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
                            columns: [0,1,2,3]
                        }
                    },
                    {
                        extend: 'excel',
                        text:'<i class="fas fa-file-excel"></i>',
                        titleAttr:'Formato Excel',
                        className: 'excelButton',
                        exportOptions: {
                            columns: [0,1,2,3]
                        }
                    },
                    {
                        extend: 'csv',
                        text:'<i class="fas fa-file-csv"></i>',
                        titleAttr:'Formato .csv',
                        className: 'csvButton',
                        exportOptions: {
                            columns: [0,1,2,3]
                        }
                    },
                    {
                        extend: 'print',
                        text:'<i class="fas fa-print"></i>',
                        titleAttr:'Imprimir',
                        className: 'printButton',
                        exportOptions: {
                            columns: [0,1,2,3]
                        }
                    }
                ]
            }
        });

        // proveedores logic
        
        let switchEstado = document.getElementById("estadoProveedor");
        let labelSttPrv = document.getElementById("labelSttPrv");
        let opcion, fila, id, nombre, anotaciones, estadoText;
        let estado = switchEstado.checked;
        
        switchEstado.onclick = function(){
            estado = !estado;
            if (estado) {
                labelSttPrv.innerHTML = `<span class="badge badge-pill badge-success">Activo</span>`;
            } else {
                labelSttPrv.innerHTML = `<span class="badge badge-pill badge-danger">Inactivo</span>`;
            }
        };

        //Crear
        $('#btnCrear').click(function (){
            opcion = 'crear';
            $("#formProveedor").trigger("reset");
            $('.modal-header').css("background-color", "#6c757d");
            $('.modal-title').text("Nuevo proveedor");
            $('#modalCRUD').modal('show');
        })

        //Editar
        $(document).on('click', '.btnEditar', function (){
            opcion = 'editar';
            fila = $(this).closest('tr');

            id = fila.find('td:eq(0)').text();
            nombre = fila.find('td:eq(1)').text();
            anotaciones = fila.find('td:eq(2)').text();
            estadoText = fila.find('td:eq(3)').text();

            $("#idProveedor").val(id);
            $("#nombreProveedor").val(nombre);
            if ( anotaciones == "Sin anotaciones" ) {
                $("#anotProveedor").val();
            } else {
                $("#anotProveedor").val(anotaciones);
            }
            if ( estadoText == "Activo" ) {
                switchEstado.checked = true;
                labelSttPrv.innerHTML = `<span class="badge badge-pill badge-success">Activo</span>`;
            } else {
                switchEstado.checked = false;
                labelSttPrv.innerHTML = `<span class="badge badge-pill badge-danger">Inactivo</span>`;
            }
            estado = switchEstado.checked;

            $('.modal-header').css("background-color", "#007bff");
            $('.modal-title').text("Editar proveedor");
            $('#modalCRUD').modal('show');
        })

        //Borrar
        $(document).on('click', '.btnEliminar', function (){
            fila = $(this).closest('tr');
            id = parseInt(fila.find('td:eq(0)').text());

            Swal.fire({
                title: 'Confirma eliminar el proveedor?',
                showCancelButton: true,
                showConfirmButton: true,
                type: 'warning',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar',
            }).then( (result) => {
                if (result.value == true) {
                    //api proveedor/id, delete
                    let url = urlDominio+'api/proveedor/'+id;

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                        }
                    })
                    .then(res => res.json(), console.log('Cargando API delete proveedor/id'))
                    .then(success => {
                        dataTableProveedores.ajax.reload(null, false);
                        Swal.fire('Proveedor eliminado', '', 'success');
                        console.log(success);
                    })
                    .catch(error => console.log(error));
                }
            })
        })

        //submit form crear o editar
        $("#formProveedor").submit(function (e){
            e.preventDefault();
            id = $('#idProveedor').val();
            nombre = $('#nombreProveedor').val();
            anotaciones = $('#anotProveedor').val();

            if(opcion == 'crear'){
                //api proveedor, post
                let url = urlDominio + 'api/proveedor';
                fetch(url, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({prv_nombre: nombre, prv_anotaciones: anotaciones, prv_estado: estado})
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'El proveedor se registró exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            dataTableProveedores.ajax.reload(null, false);
                        });
                        $('#modalCRUD').modal('hide');
                    })
                    .catch(error => console.log(error));
            }
            if(opcion == 'editar'){
                //api proveedor (update)
                let url = urlDominio + 'api/proveedor/'+id;
                fetch(url, {
                    method: 'PUT',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({prv_nombre: nombre, prv_anotaciones: anotaciones, prv_estado: estado})
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'El proveedor se actualizó exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then( (result) => {
                            dataTableProveedores.ajax.reload(null, false);
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