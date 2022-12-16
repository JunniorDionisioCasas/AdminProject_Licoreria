@extends('adminlte::page')

@section('title', 'Cargos')

@section('content_header')
    <h1>Tabla Cargos</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <button id="btnCrear" type="button" class="btn btn-secondary btn-crear">Nuevo cargo</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla_cargos" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Privilegios</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="lista_cargos">
                        <!-- lista de cargos mediante api -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalCRUD" class="modal" aria-labelledby="Formulario de nuevo cargo" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo cargo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCargo">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input id="idCargo" type="hidden">
                            <div class="form-group row">
                                <label for="nombreCargo" class="col-sm-4 col-form-label">Cargo*</label>
                                <div class="col-sm-8">
                                    <input id="nombreCargo" type="text" class="form-control" placeholder="Ingrese el nombre del cargo" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Acceso a sistema*</label>
                                <div class="col-sm-8 centerVertical">
                                    <div class="custom-control custom-switch">
                                        <input id="accesoSistemaCargo" class="custom-control-input" type="checkbox" role="switch" checked>
                                        <label id="labelAccsSist" for="accesoSistemaCargo" class="custom-control-label">
                                            <span class="badge badge-pill badge-primary">Con acceso</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="descCargo" class="col-sm-4 col-form-label">Descripción</label>
                                <div class="col-sm-8">
                                    <textarea id="descCargo" rows="2" class="form-control" placeholder="Ingrese una descripción del cargo (opcional)"></textarea>
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
        let badgeElmntSwitchAccs = `<span class="badge badge-pill badge-primary">Con acceso</span>`;
        let badgeElmntSwitchNoAccs = `<span class="badge badge-pill badge-secondary">Sin acceso</span>`;
        // datatables config
        let dataTableCargos = $('#tabla_cargos').DataTable({
            "ajax":{
                "url":urlDominio+'api/cargos',
                "type": "GET",
                "dataSrc":""
            },
            "columns":[
                {"data":"id_cargo"},
                {"data":"crg_nombre"},
                {
                    "data":"crg_acceso_admin",
                    "render": function ( data, type, row, meta ) {
                        if (data == 1) {
                            return badgeElmntSwitchAccs;
                        } else {
                            return badgeElmntSwitchNoAccs;
                        }
                    }
                },
                {
                    "data":"crg_descripcion",
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

        // CRUD logic
        let opcion, fila, id, nombre, descripcion, estadoText;
        let switchAccsSist = document.getElementById("accesoSistemaCargo");
        let labelAccsSist = document.getElementById("labelAccsSist");
        let estado = switchAccsSist.checked;
        
        switchAccsSist.onclick = function(){
            estado = !estado;
            if (estado) {
                labelAccsSist.innerHTML = badgeElmntSwitchAccs;
            } else {
                labelAccsSist.innerHTML = badgeElmntSwitchNoAccs;
            }
        };

        //Crear
        $('#btnCrear').click(function (){
            opcion = 'crear';
            $("#formCargo").trigger("reset");
            $('.modal-header').css("background-color", "#6c757d");
            $('.modal-title').text("Nuevo cargo");
            labelAccsSist.innerHTML = badgeElmntSwitchAccs;
            $('#modalCRUD').modal('show');
        })

        //Editar
        $(document).on('click', '.btnEditar', function (){
            opcion = 'editar';
            fila = $(this).closest('tr');

            id = fila.find('td:eq(0)').text();
            nombre = fila.find('td:eq(1)').text();
            estadoText = fila.find('td:eq(2)').text();
            descripcion = fila.find('td:eq(3)').text();

            $("#idCargo").val(id);
            $("#nombreCargo").val(nombre);
            if ( descripcion == "Sin descripción" ) {
                $("#descCargo").val();
            } else {
                $("#descCargo").val(descripcion);
            }
            if ( estadoText == "Con acceso" ) {
                switchAccsSist.checked = true;
                labelAccsSist.innerHTML = badgeElmntSwitchAccs;
            } else {
                switchAccsSist.checked = false;
                labelAccsSist.innerHTML = badgeElmntSwitchNoAccs;
            }
            estado = switchAccsSist.checked;
            
            // disable for default users, client and admin
            if ( id == 1 || id == 2 ) { //1=client, 2=admin
                switchAccsSist.disabled = true;
                labelAccsSist.innerHTML += ` <small class="text-muted">Predeterminado</small>`;
            }

            $('.modal-header').css("background-color", "#007bff");
            $('.modal-title').text("Editar cargo");
            $('#modalCRUD').modal('show');
        })

        //Borrar
        $(document).on('click', '.btnEliminar', function (){
            fila = $(this).closest('tr');
            id = parseInt(fila.find('td:eq(0)').text());

            Swal.fire({
                title: 'Confirma eliminar el cargo?',
                showCancelButton: true,
                showConfirmButton: true,
                type: 'warning',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar',
            }).then( (result) => {
                if (result.value == true) {
                    //api cargo/id, delete
                    let url = urlDominio+'api/cargo/'+id;

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                        }
                    })
                    .then(res => res.json())
                    .then(success => {
                        dataTableCargos.ajax.reload(null, false);
                        Swal.fire('Cargo eliminado', '', 'success');
                        console.log(success);
                    })
                    .catch(error => console.log(error));
                }
            })
        })

        //submit form crear o editar
        $("#formCargo").submit(function (e){
            e.preventDefault();
            id = $('#idCargo').val();
            nombre = $('#nombreCargo').val();
            descripcion = $('#descCargo').val();

            if(opcion == 'crear'){
                //api cargo, post
                let url = urlDominio + 'api/cargo';
                fetch(url, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({crg_nombre: nombre, crg_acceso_admin: estado, crg_descripcion: descripcion})
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'El cargo se registró exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            dataTableCargos.ajax.reload(null, false);
                        });
                        $('#modalCRUD').modal('hide');
                    })
                    .catch(error => console.log(error));
            }
            if(opcion == 'editar'){
                //api cargo (update)
                let url = urlDominio + 'api/cargo/'+id;
                fetch(url, {
                    method: 'PUT',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({crg_nombre: nombre, crg_acceso_admin: estado, crg_descripcion: descripcion})
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'El cargo se actualizó exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then( (result) => {
                            dataTableCargos.ajax.reload(null, false);
                        });

                        $('#modalCRUD').modal('hide');
                    })
                    .catch(error => console.log(error));
            }
        });

        $('#modalCRUD').on('hidden.bs.modal', function (event) {
            switchAccsSist.disabled = false;
        });
    </script>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)