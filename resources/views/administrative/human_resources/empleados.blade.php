@extends('adminlte::page')

@section('title', 'Empleados')

@section('content_header')
    <h1>Tabla Empleados(as)</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <button id="btnCrear" type="button" class="btn btn-secondary btn-crear">Nuevo empleado(a)</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla_empleados" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Cargo</th>
                            <th scope="col">Email</th>
                            <th scope="col">Dirección</th>
                            <th scope="col">Profile photo path</th>
                            <th scope="col">Fecha nacimiento</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="lista_empleados">
                        <!-- lista de empleados mediante api -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalCRUD" class="modal" aria-labelledby="Formulario de nuevo empleado" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo empleado(a)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEmpleado">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input id="idEmpleado" type="hidden">
                            <div class="form-group row">
                                <label for="nombreEmpleado" class="col-sm-5 col-form-label">Nombre*</label>
                                <div class="col-sm-7">
                                    <input id="nombreEmpleado" type="text" class="form-control" placeholder="Ingrese el nombre del empleado" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="apellidoEmpleado" class="col-sm-5 col-form-label">Apellido*</label>
                                <div class="col-sm-7">
                                    <input id="apellidoEmpleado" type="text" class="form-control" placeholder="Ingrese el apellido del empleado" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <label for="cargoEmpleado" class="col-sm-5 col-form-label">Cargo*</label>
                                <div class="form-group col-sm-7">
                                    <select id="cargoEmpleado" class="form-control" required>
                                        <!-- Se insertan la lista de cargos mediante api -->
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="emailEmpleado" class="col-sm-5 col-form-label">Correo*</label>
                                <div class="col-sm-7">
                                    <input id="emailEmpleado" type="email" class="form-control" placeholder="Ingrese el correo del empleado" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="passwordEmpleado" class="col-sm-5 col-form-label">Contraseña*</label>
                                <div class="col-sm-7">
                                    <input id="passwordEmpleado" type="password" class="form-control" pattern=".{8,}" placeholder="Ingrese la contraseña de la nueva cuenta">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="passwordConfEmpleado" class="col-sm-5 col-form-label">Confirmar contraseña*</label>
                                <div class="col-sm-7">
                                    <input id="passwordConfEmpleado" type="password" class="form-control" pattern=".{8,}" placeholder="Confirme la contraseña">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fechaNacEmpleado" class="col-sm-5 col-form-label">Fecha de nacimiento (opcional)</label>
                                <div class="col-sm-7">
                                    <input id="fechaNacEmpleado" type="date" class="form-control" max="{{date('Y-m-d');}}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="direccionEmpleado" class="col-sm-5 col-form-label">Dirección (opcional)</label>
                                <div class="col-sm-7">
                                    <input id="direccionEmpleado" type="text" class="form-control" placeholder="Ingrese la dirección del empleado">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Foto de perfil (opcional)</label>
                                <div class="input-group col-sm-7">
                                    <div class="custom-file">
                                        <input id="imagenEmpleado" name="imagenEmpleado" type="file" class="custom-file-input" accept="image/*">
                                        <label class="custom-file-label" for="imagenEmpleado" data-browse="Elegir">Seleccionar imagen</label>
                                    </div>
                                </div>
                            </div>
                            <div class="div-img-center">
                                <img id="imgPreview" class="rounded-circle avatar-lg img-thumbnail img-preview" alt="empleado-image">
                                <small class="form-text text-muted">800x800 px preferentemente</small>
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
        let nullCellText = "No registrado";
        // datatables config
        let dataTableEmpleados = $('#tabla_empleados').DataTable({
            "ajax":{
                "url":urlDominio+'api/empleados',
                "type": "GET",
                "dataSrc":""
            },
            "columns":[
                {"data":"id"},
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
                    "data":"crg_nombre",
                    "render": function ( data, type, row, meta ) {
                        if (data == "Administrador") {
                            return `<span class="badge badge-pill badge-primary">${data}</span>`;
                        } else {
                            return `<span class="badge badge-pill badge-info">${data}</span>`;
                        }
                    }
                },
                {"data":"email"},
                {
                    "data":"drc_direccion",
                    "defaultContent":`<i>${nullCellText}</i>`
                },
                {
                    "data":"profile_photo_path",
                    visible: false
                },
                {
                    "data":"usr_fecha_nacimiento",
                    visible: false
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
                            columns: [0,1,3,4,5]
                        }
                    },
                    {
                        extend: 'excel',
                        text:'<i class="fas fa-file-excel"></i>',
                        titleAttr:'Formato Excel',
                        className: 'excelButton',
                        exportOptions: {
                            columns: [0,1,3,4,5]
                        }
                    },
                    {
                        extend: 'csv',
                        text:'<i class="fas fa-file-csv"></i>',
                        titleAttr:'Formato .csv',
                        className: 'csvButton',
                        exportOptions: {
                            columns: [0,1,3,4,5]
                        }
                    },
                    {
                        extend: 'print',
                        text:'<i class="fas fa-print"></i>',
                        titleAttr:'Imprimir',
                        className: 'printButton',
                        exportOptions: {
                            columns: [0,1,3,4,5]
                        }
                    }
                ]
            }
        });

        // CRUD logic
        let inputPasswEmp = document.getElementById("passwordEmpleado");
        let inputConfPasswEmp = document.getElementById("passwordConfEmpleado");
        let selectCargoEmpleado = document.getElementById("cargoEmpleado");
        let opcion, fila, id, nombre, apellido, cargo, correo, direccion, imagen, fechaNac;

        function listar_cargos() {
            const url = urlDominio+'api/cargos';

            //llamado al api cargos, index
            fetch(url, {
                method: 'GET',
                headers: {
                    "Content-Type": "application/json",
                }
            })
            .then(res => res.json())
            .then(res => {
                res.forEach(cargo => {
                    if ( cargo.id_cargo != 1 ) {    //1=cliente
                        let option_elem = document.createElement('option');
                        option_elem.value = cargo.id_cargo;
                        option_elem.textContent = cargo.crg_nombre;
                        selectCargoEmpleado.appendChild(option_elem);
                    }
                });
            })
            .catch(error => console.log(error));
        };

        listar_cargos();

        //Crear
        $('#btnCrear').click(function (){
            opcion = 'crear';
            $("#formCargo").trigger("reset");
            $('.modal-header').css("background-color", "#6c757d");
            $('.modal-title').text("Nuevo empleado(a)");
            $('#passwordEmpleado').attr('required', 'required');
            $('#passwordConfEmpleado').attr('required', 'required');
            $('#passwordEmpleado').attr("placeholder", "Ingrese una contraseña");
            $('#passwordConfEmpleado').attr("placeholder", "Confirme la contraseña");
            $('#modalCRUD').modal('show');
        })

        //Editar
        $(document).on('click', '.btnEditar', function (){
            opcion = 'editar';
            fila = $(this).closest('tr');
            // Display an info toast with no title
            toastr.info('Are you the 6 fingered man?')

            id = fila.find('td:eq(0)').text();
            nombre = fila.find('td:eq(1)').text();
            apellido = fila.find('td:eq(2)').text();
            cargo = fila.find('td:eq(3)').text();
            correo = fila.find('td:eq(4)').text();
            direccion = fila.find('td:eq(5)').text();
            imagen = fila.find('td:eq(6)').text();
            fechaNac = fila.find('td:eq(7)').text();

            $("#idEmpleado").val(id);
            $("#nombreEmpleado").val(nombre);
            $("#apellidoEmpleado").val(apellido);
            $("#emailEmpleado").val(correo);
            $("#fechaNacEmpleado").val(fechaNac);

            $("#cargoEmpleado option").filter(':selected').attr('selected', false);
            $("#cargoEmpleado option").filter(function() {
                return $(this).text() == cargo;
            }).attr('selected', true);

            if ( direccion == nullCellText ) {
                $("#direccionEmpleado").val();
            } else {
                $("#direccionEmpleado").val(direccion);
            }

            if ( imagen ) {
                $("#imgPreview").attr("src", imagen);
            } else {
                $("#imgPreview").attr("src", "images/placeholder.png");
            }

            $('.modal-header').css("background-color", "#007bff");
            $('.modal-title').text("Editar empleado(a)");
            $('#passwordEmpleado').removeAttr('required');
            $('#passwordConfEmpleado').removeAttr('required');
            $('#passwordEmpleado').attr("placeholder", "Ingrese una nueva contraseña si desea actualizar");
            $('#passwordConfEmpleado').attr("placeholder", "Confirme la contraseña a actualizar");
            $('#modalCRUD').modal('show');
        })

        //Borrar
        $(document).on('click', '.btnEliminar', function (){
            fila = $(this).closest('tr');
            id = parseInt(fila.find('td:eq(0)').text());

            Swal.fire({
                title: 'Confirma eliminar emplead(a)?',
                showCancelButton: true,
                showConfirmButton: true,
                type: 'warning',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar',
            }).then( (result) => {
                if (result.value == true) {
                    //api empleado/id, delete
                    let url = urlDominio+'api/empleado/'+id;

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                        }
                    })
                    .then(res => res.json())
                    .then(success => {
                        dataTableEmpleados.ajax.reload(null, false);
                        Swal.fire('Empleado(a) eliminado', '', 'success');
                        console.log(success);
                    })
                    .catch(error => console.log(error));
                }
            })
        })

        //submit form crear o editar
        $("#formEmpleado").submit(function (e){
            e.preventDefault();

            let password, passwordConfirm;

            id = $('#idEmpleado').val();
            nombre = $('#nombreEmpleado').val();
            apellido = $('#apellidoEmpleado').val();
            cargo = $('#cargoEmpleado').val();
            correo = $('#emailEmpleado').val();
            password = $('#passwordEmpleado').val();
            passwordConfirm = $('#passwordConfEmpleado').val();
            direccion = $('#direccionEmpleado').val();
            fechaNac = $('#fechaNacEmpleado').val();

            // validate input
            if ( password !== passwordConfirm ) {
                Alert;
            }

            let formData = new FormData();

            formData.append('name', nombre);
            formData.append('usr_apellidos', apellido);
            formData.append('id_cargo', cargo);
            formData.append('email', correo);
            formData.append('password', password);
            formData.append('password_confirmation', passwordConfirm);
            formData.append('drc_direccion', direccion);
            formData.append('usr_fecha_nacimiento', fechaNac);
            if(imagen.files.length !== 0){
                formData.append('profile_photo_path', imagen.files[0]);
            }

            if(opcion == 'crear'){
                //api empleado, post
                let url = urlDominio + 'api/empleado';
                fetch(url, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: formData
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'El empleado(a) se registró exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            dataTableEmpleados.ajax.reload(null, false);
                        });
                        $('#modalCRUD').modal('hide');
                    })
                    .catch(error => console.log(error));
            }
            if(opcion == 'editar'){
                // validate input
                if ( password ) {
                    if ( password === passwordConfirm ) {
                        ;
                    }
                    console.log("hay contraseñas");
                } else {
                    console.log("no hay contraseñas");
                }
                
                //api empleado (update)
                let url = urlDominio + 'api/empleado/'+id;
                fetch(url, {
                    method: 'PUT',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: formData
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'El empleado(a) se actualizó exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then( (result) => {
                            dataTableEmpleados.ajax.reload(null, false);
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
@section('plugins.Toastr', true)