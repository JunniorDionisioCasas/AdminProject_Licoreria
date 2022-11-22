@extends('adminlte::page')

@section('title', 'Marcas')

@section('content_header')
    <h1>Tabla Marcas</h1>
@stop

@section('content')
    <div class="container-fluid">
        <button id="btnCrear" type="button" class="btn btn-secondary btn-crear">Nueva marca</button>
    </div>

    <div class="table-responsive">
        <table id="tabla_marcas" class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody id="lista_marcas">
                <!-- lista de marcas mediante api -->
            </tbody>
        </table>
    </div>

    <div id="modalCRUD" class="modal" aria-labelledby="Formulario de nueva marca" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva marca</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formMarca">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input id="idMarca" type="hidden">
                            <div class="form-group row">
                                <label for="nombreMarca" class="col-sm-3 col-form-label">Nombre</label>
                                <div class="col-sm-9">
                                    <input id="nombreMarca" type="text" class="form-control" placeholder="Ingrese el nombre de la marca" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="descMarca" class="col-sm-3 col-form-label">Descripción</label>
                                <div class="col-sm-9">
                                    <textarea id="descMarca" rows="2" class="form-control" placeholder="Ingrese una descripcion (opcional)"></textarea>
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
        let tabla = document.getElementById("tabla_marcas");
        let opcion, fila, id, nombre, descripcion;

        let dataTableMarcas = $('#tabla_marcas').DataTable({
            "ajax":{
                "url":urlDominio+'api/marcas',
                "dataSrc":""
            },
            "columns":[
                {"data":"id_marca"},
                {"data":"mrc_nombre"},
                {"data":"mrc_descripcion"},
                {"defaultContent":`<button class="btn btn-outline-primary btn-xs btnEditar"><i class="fas fa-pen"> Editar</i></button>
                                   <button class="btn btn-outline-danger btn-xs btnEliminar"><i class="fas fa-trash-can"> Eliminar</i></button>`}
            ],
        });

        //Crear
        $('#btnCrear').click(function (){
            opcion = 'crear';
            $("#formMarca").trigger("reset");
            $('.modal-header').css("background-color", "#6c757d");
            $('.modal-title').text("Nueva marca");
            $('#modalCRUD').modal('show');
        })

        //Editar
        $(document).on('click', '.btnEditar', function (){
            opcion = 'editar';
            fila = $(this).closest('tr');

            id = fila.find('td:eq(0)').text();
            nombre = fila.find('td:eq(1)').text();
            descripcion = fila.find('td:eq(2)').text();

            $("#idMarca").val(id);
            $("#nombreMarca").val(nombre);
            $("#descMarca").val(descripcion);

            $('.modal-header').css("background-color", "#007bff");
            $('.modal-title').text("Editar marca");
            $('#modalCRUD').modal('show');
        })

        //Borrar
        $(document).on('click', '.btnEliminar', function (){
            fila = $(this).closest('tr');
            id = parseInt(fila.find('td:eq(0)').text());

            Swal.fire({
                title: 'Confirma eliminar la marca?',
                showCancelButton: true,
                showConfirmButton: true,
                type: 'warning',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar',
            }).then( (result) => {
                if (result.value == true) {
                    //api marca/id, delete
                    let url = urlDominio+'api/marca/'+id;

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                        }
                    })
                    .then(res => res.json(), console.log('Cargando API delete marca/id'))
                    .then(success => {
                        dataTableMarcas.ajax.reload(null, false);
                        Swal.fire('Marca eliminada', '', 'success');
                        console.log(success);
                    })
                    .catch(error => console.log(error));
                }
            })
        })

        //submit form crear o editar
        $("#formMarca").submit(function (e){
            e.preventDefault();
            id = $('#idMarca').val();
            nombre = $('#nombreMarca').val();
            descripcion = $('#descMarca').val();

            if(opcion == 'crear'){
                //api marca, post
                let url = urlDominio + 'api/marca';
                fetch(url, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({mrc_nombre: nombre, mrc_descripcion: descripcion})
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'La marca se registró exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            dataTableMarcas.ajax.reload(null, false);
                        });
                        $('#modalCRUD').modal('hide');
                    })
                    .catch(error => console.log(error));
            }
            if(opcion == 'editar'){
                //api marca (update)
                let url = urlDominio + 'api/marca/'+id;
                fetch(url, {
                    method: 'PUT',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({mrc_nombre: nombre, mrc_descripcion: descripcion})
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'La marca se actualizó exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then( (result) => {
                            dataTableMarcas.ajax.reload(null, false);
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
