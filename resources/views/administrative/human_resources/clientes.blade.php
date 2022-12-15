@extends('adminlte::page')

@section('title', 'Clientes')

@section('content_header')
    <h1>Tabla Clientes</h1>
@stop

@section('content')

<div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <button id="btnCrear" type="button" class="btn btn-secondary btn-crear">Nuevo cliente</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla_clientes" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellidos</th>
                            <th scope="col">Email</th>
                            <th scope="col">Dirección</th>
                            <th scope="col">Profile photo path</th>
                            <th scope="col">Fecha nacimiento</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="lista_clientes">
                        <!-- lista de clientes mediante api -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalCRUD" class="modal" aria-labelledby="Formulario de nuevo cliente" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo clientes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCliente">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input id="idCliente" type="hidden">
                            <div class="form-group row">
                                <label for="nombreCliente" class="col-sm-5 col-form-label">Nombre*</label>
                                <div class="col-sm-7">
                                    <input id="nombreCliente" type="text" class="form-control" placeholder="Ingrese el nombre del cliente" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="apellidoCliente" class="col-sm-5 col-form-label">Apellido*</label>
                                <div class="col-sm-7">
                                    <input id="apellidoCliente" type="text" class="form-control" placeholder="Ingrese el apellido del cliente" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="emailCliente" class="col-sm-5 col-form-label">Correo*</label>
                                <div class="col-sm-7">
                                    <input id="emailCliente" type="email" class="form-control" placeholder="Ingrese el correo del cliente" required>
                                </div>
                            </div>
                            <div class="form-group row is-invalid" id="divPassword">
                                <label id="labelPasswClt" for="passwordCliente" class="col-sm-5 col-form-label">Contraseña*</label>
                                <div class="col-sm-7">
                                    <input id="passwordCliente" type="password" class="form-control" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(.{8,})" placeholder="Ingrese una contraseña">
                                    <small class="form-text text-muted">Mínimo de 8 caracteres, que contengan números, símbolos y letras mayúsculas y minúsculas.</small>
                                </div>
                            </div>
                            <div class="form-group row" id="divConfPassword">
                                <label id="labelConfPasswClt" for="passwordConfCliente" class="col-sm-5 col-form-label">Confirmar contraseña*</label>
                                <div class="col-sm-7">
                                    <input id="passwordConfCliente" type="password" class="form-control" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9])(.{8,})" placeholder="Confirme la contraseña">
                                <small class="form-text text-muted">Vuelva a ingresar la contraseña.</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fechaNacCliente" class="col-sm-5 col-form-label">Fecha de nacimiento (opcional)</label>
                                <div class="col-sm-7">
                                    <input id="fechaNacCliente" type="date" class="form-control" max="{{date('Y-m-d');}}" placeholder="Mes/Día/Año">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="direccionCliente" class="col-sm-5 col-form-label">Dirección (opcional)</label>
                                <div class="col-sm-7">
                                    <input id="direccionCliente" type="text" class="form-control" placeholder="Ingrese la dirección del cliente">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Foto de perfil (opcional)</label>
                                <div class="input-group col-sm-7">
                                    <div class="custom-file">
                                        <input id="imagenCliente" name="imagenCliente" type="file" class="custom-file-input" accept="image/*">
                                        <label class="custom-file-label" for="imagenCliente" data-browse="Elegir">Seleccionar imagen</label>
                                    </div>
                                </div>
                            </div>
                            <div class="div-img-center">
                                <img id="imgPreview" class="rounded-circle avatar-lg img-thumbnail img-preview" alt="cliente-image">
                                <small class="form-text text-muted">400x400 px preferentemente</small>
                            </div>
                            <small class="form-text text-muted">*: Campo obligatorio a rellenar.</small>
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
        let dataTableClientes = $('#tabla_clientes').DataTable({
            "ajax":{
                "url":urlDominio+'api/clientes',
                "type": "GET",
                "headers": {
                    "Authorization": "Bearer {{Auth::user()->createToken('my-token')->plainTextToken}}"
                },
                "dataSrc":""
            },
            rowId: 'id',
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
        let inputPasswClt = document.getElementById("passwordCliente");
        let inputConfPasswClt = document.getElementById("passwordConfCliente");
        let opcion, fila, id, nombre, apellido, cargo, correo, direccion, imagen, fechaNac;

        function removeValidatedStyle(elementId) {
            document.getElementById(elementId).classList.remove("is-invalid");
        }

        function invalidInputAlert(msg, title) {
            toastr.error(msg, title);
        }

        inputConfPasswClt.addEventListener("input", function(){removeValidatedStyle('passwordConfCliente')});

        inputPasswClt.oninvalid = (event) => {
            invalidInputAlert("La contraseña debe ser mínimo de 8 caracteres, que contengan números, símbolos y letras mayúsculas y minúsculas.", 'Contraseña insegura');
        };

        //Crear
        $('#btnCrear').click(function (){
            opcion = 'crear';
            $("#formCliente").trigger("reset");
            $('.modal-header').css("background-color", "#6c757d");
            $('.modal-title').text("Nuevo cliente");
            $('#labelConfPasswClt').text("Confirmar contraseña");
            $("#imgPreview").attr("src", "images/placeholder.png");
            $('#passwordCliente').attr('required', 'required');
            $('#passwordConfCliente').attr('required', 'required');
            $('#passwordCliente').attr("placeholder", "Ingrese una contraseña");
            $('#passwordConfCliente').attr("placeholder", "Confirme la contraseña");
            $('#modalCRUD').modal('show');
        })

        //Editar
        $(document).on('click', '.btnEditar', function (){
            opcion = 'editar';
            fila = $(this).closest('tr');

            id = fila.find('td:eq(0)').text();
            nombre = dataTableClientes.row(fila).data()['name'];
            apellido = dataTableClientes.row(fila).data()['usr_apellidos'];
            correo = fila.find('td:eq(2)').text();
            direccion = fila.find('td:eq(3)').text();
            imagen = dataTableClientes.row(fila).data()['profile_photo_path'];
            fechaNac = dataTableClientes.row(fila).data()['usr_fecha_nacimiento'];

            console.log(dataTableClientes.row(fila).data()['name']);
            console.log(dataTableClientes.row(fila).id());
            console.log("nombre: "+nombre);
            console.log("apellido: "+apellido);
            console.log("imagen: "+imagen);
            console.log("fechaNac: "+fechaNac);

            $("#idCliente").val(id);
            $("#nombreCliente").val(nombre);
            $("#apellidoCliente").val(apellido);
            $("#emailCliente").val(correo);
            $("#fechaNacCliente").val(fechaNac);

            if ( direccion == nullCellText ) {
                $("#direccionCliente").val();
            } else {
                $("#direccionCliente").val(direccion);
            }

            if ( imagen ) {
                $("#imgPreview").attr("src", imagen);
            } else {
                $("#imgPreview").attr("src", "images/placeholder.png");
            }

            $('.modal-header').css("background-color", "#007bff");
            $('.modal-title').text("Editar cliente");
            $('#passwordCliente').removeAttr('required');
            $('#passwordConfCliente').removeAttr('required');
            $('#divPassword').hide()
            $('#divConfPassword').hide()
            $('#modalCRUD').modal('show');
        })

        //Borrar
        $(document).on('click', '.btnEliminar', function (){
            fila = $(this).closest('tr');
            id = parseInt(fila.find('td:eq(0)').text());

            Swal.fire({
                title: 'Confirma eliminar cliente?',
                showCancelButton: true,
                showConfirmButton: true,
                type: 'warning',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar',
            }).then( (result) => {
                if (result.value == true) {
                    //api cliente/id, delete
                    let url = urlDominio+'api/cliente/'+id;

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                            "Authorization": "Bearer {{Auth::user()->createToken('my-token')->plainTextToken}}"
                        }
                    })
                    .then(res => res.json())
                    .then(success => {
                        dataTableClientes.ajax.reload(null, false);
                        Swal.fire('Cliente eliminado', '', 'success');
                        console.log(success);
                    })
                    .catch(error => console.log(error));
                }
            })
        })

        //submit form crear o editar
        $("#formCliente").submit(function (e){
            e.preventDefault();

            let password, passwordConfirm;

            id = $('#idCliente').val();
            nombre = $('#nombreCliente').val();
            apellido = $('#apellidoCliente').val();
            correo = $('#emailCliente').val();
            password = $('#passwordCliente').val();
            passwordConfirm = $('#passwordConfCliente').val();
            direccion = $('#direccionCliente').val();
            fechaNac = $('#fechaNacCliente').val();

            // validate input
            if ( password !== passwordConfirm ) {
                toastr.warning('La contraseña y la confirmación no coinciden');
                inputConfPasswClt.classList.add("is-invalid");
                inputConfPasswClt.focus();
                return false;
            }

            let formData = new FormData();

            formData.append('name', nombre);
            formData.append('usr_apellidos', apellido);
            formData.append('email', correo);
            formData.append('drc_direccion', direccion);
            formData.append('usr_fecha_nacimiento', fechaNac);
            if( imagen ) {
                if(imagen.files.length !== 0){
                    formData.append('profile_photo_path', imagen.files[0]);
                }
            }
            
            //prueba de obtencio de datos del form
            for (const pair of formData) {
                console.log(`${pair[0]}: ${pair[1]}\n`);
            }

            if(opcion == 'crear'){
                formData.append('password', password);
                formData.append('password_confirmation', passwordConfirm);

                //api empleado, post
                let url = urlDominio + 'api/register';
                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'El cliente se registró exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            dataTableClientes.ajax.reload(null, false);
                        });
                        $('#modalCRUD').modal('hide');
                    })
                    .catch(error => console.log(error));
            }
            if(opcion == 'editar'){
                //api cliente (update)
                let url = urlDominio + 'api/cliente/'+id;
                fetch(url, {
                    method: 'POST', //cant use PUT with formData
                    body: formData, //formData neccesary for file upload
                    headers: {
                        "Authorization": "Bearer {{Auth::user()->createToken('my-token')->plainTextToken}}",
                    }
                })
                    .then(res => {
                        console.log(res);
                        if ( res.ok ) {
                            return res.json();
                        } else {
                            return res.text().then(
                                text => { throw text }
                            );
                        }
                    })
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'El cliente se actualizó exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then( (result) => {
                            dataTableClientes.ajax.reload(null, false);
                        });

                        $('#modalCRUD').modal('hide');
                    })
                    .catch(error => {
                        // console.log(JSON.parse(error));
                        let response = JSON.parse(error);
                        let errores = response.validator_errors;
                        console.log(errores);
                        for (let msg in errores ) {
                            toastr.error(errores[msg], 'Error en '+msg);
                        }
                    });
            }
        });

        $('#imagenCliente').on('change',function(e){
            let reader = new FileReader();
            let fileName = imagen.files[0].name;
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
            reader.onload = function(e) {
                document.getElementById("imgPreview").src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        });
    </script>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Toastr', true)