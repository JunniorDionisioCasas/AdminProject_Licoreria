@extends('adminlte::page')

@section('title', 'Categorias')

@section('content_header')
    <h1>Tabla Categorias</h1>
@stop

@section('content')
    <div class="container-fluid">
        <button id="btnCrear" type="button" class="btn btn-secondary btn-crear">Nueva categoria</button>
    </div>

    <div class="table-responsive">
        <table id="tabla_categorias" class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody id="lista_categorias">
                <!-- lista de categorias mediante api -->
            </tbody>
        </table>
    </div>

    <div id="modalCRUD" class="modal" aria-labelledby="Formulario de nueva categoria" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva categoria</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCategoria">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input id="idCatg" type="hidden">
                            <div class="form-group row">
                                <label for="nombreCatg" class="col-sm-3 col-form-label">Nombre</label>
                                <div class="col-sm-9">
                                    <input id="nombreCatg" type="text" class="form-control" placeholder="Ingrese el nombre de la categoria" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="descCatg" class="col-sm-3 col-form-label">Descripción</label>
                                <div class="col-sm-9">
                                    <textarea id="descCatg" rows="2" class="form-control" placeholder="Ingrese una descripcion (opcional)"></textarea>
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
    <script>
        //dominio del api
        const urlDominio = 'http://127.0.0.1:8080/';
        let tabla = document.getElementById("tabla_categorias");
        let opcion, fila, id, nombre, descripcion;

        let dataTableCategorias = $('#tabla_categorias').DataTable({
            "ajax":{
                "url":urlDominio+'api/categorias',
                "dataSrc":""
            },
            "columns":[
                {"data":"id_categoria"},
                {"data":"ctg_nombre"},
                {"data":"ctg_descripcion"},
                {"defaultContent":`<button class="btn btn-outline-primary btn-xs btnEditar"><i class="fas fa-pen"> Editar</i></button>
                                   <button class="btn btn-outline-danger btn-xs btnEliminar"><i class="fas fa-trash-can"> Eliminar</i></button>`}
            ],
        });

        //Crear
        $('#btnCrear').click(function (){
            opcion = 'crear';
            $("#formCategoria").trigger("reset");
            $('.modal-header').css("background-color", "#6c757d");
            $('.modal-title').text("Nueva categoria");
            $('#modalCRUD').modal('show');
        })

        //Editar
        $(document).on('click', '.btnEditar', function (){
            opcion = 'editar';
            fila = $(this).closest('tr');

            id = parseInt(fila.find('td:eq(0)').text());
            nombre = fila.find('td:eq(1)').text();
            descripcion = fila.find('td:eq(2)').text();

            $("#idCatg").val(id);
            $("#nombreCatg").val(nombre);
            $("#descCatg").val(descripcion);

            $('.modal-header').css("background-color", "#007bff");
            $('.modal-title').text("Editar categoria");
            $('#modalCRUD').modal('show');
        })

        //Borrar
        $(document).on('click', '.btnEliminar', function (){
            fila = $(this).closest('tr');
            id = parseInt(fila.find('td:eq(0)').text());

            Swal.fire({
                title: 'Confirma eliminar la categoria?',
                showCancelButton: true,
                showConfirmButton: true,
                type: 'warning',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar',
            }).then( (result) => {
                console.log(result);
                if (result.value == true) {
                    //api categoria/id, delete
                    let url = urlDominio+'api/categoria/'+id;

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                        }
                    })
                    .then(res => res.json())
                    .then(success => {
                        dataTableCategorias.ajax.reload(null, false);
                        Swal.fire('Categoria eliminada', '', 'success');
                    })
                    .catch(error => console.log(error));
                }else{
                    console.log('cancelado');
                }
            })
        })

        //submit form crear o editar
        $("#formCategoria").submit(function (e){
            e.preventDefault();
            id = $('#idCatg').val();
            nombre = $('#nombreCatg').val();
            descripcion = $('#descCatg').val();

            let formData = new FormData();

            formData.append('ctg_nombre', nombre);
            formData.append('ctg_descripcion', descripcion);

            //prueba de obtencio de datos del form
            for (const pair of formData) {
                console.log(`${pair[0]}: ${pair[1]}\n`);
            }

            if(opcion == 'crear'){
                //api categoria, post
                let url = urlDominio + 'api/categoria';
                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'La categoria se registró exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            dataTableCategorias.ajax.reload(null, false);
                        });
                        $('#modalCRUD').modal('hide');
                    })
                    .catch(error => console.log(error));
            }
            if(opcion == 'editar'){
                //api categoria/id (update)
                let url = urlDominio + 'api/categoria/'+id;
                fetch(url, {
                    method: 'PUT',
                    headers: {
                        "Content-Type": "application/json",//"multipart/form-data",
                    },
                    body: JSON.stringify({ctg_nombre: nombre, ctg_descripcion: descripcion})
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'La categoria se actualizó exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then( (result) => {
                            dataTableCategorias.ajax.reload(null, false);
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
