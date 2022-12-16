@extends('adminlte::page')

@section('title', 'Descuentos')

@section('content_header')
    <h1>Tabla Descuentos</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <button id="btnCrear" type="button" class="btn btn-secondary btn-crear">Nuevo descuento</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla_descuentos" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Detalle</th>
                            <th scope="col">Código</th>
                            <th scope="col">Producto</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="lista_descuentos">
                        <!-- lista de descuentos mediante api -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalCRUD" class="modal" aria-labelledby="Formulario de nuevo descuento" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo descuento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formDescuento">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input id="idDescuento" type="hidden">
                            <div class="form-group row">
                                <label for="nombreDescuento" class="col-sm-3 col-form-label">Descuento*</label>
                                <div class="col-sm-9">
                                    <input id="nombreDescuento" type="text" class="form-control" placeholder="Ingrese el nombre del descuento" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cantidadDescuento" class="col-sm-3 col-form-label">Cantidad*</label>
                                <div class="input-group col-sm-9">
                                    <input id="cantidadDescuento" type="number" class="form-control" min="1" max="90" step="1" placeholder="Ingrese la cantidad del descuento" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-4">
                                    <label for="tipoDescuento" class="col-form-label">Tipo de descuento*</label>
                                    <select id="tipoDescuento" class="form-control" required>
                                        <!-- Se insertan la lista de tipos de descuentos mediante api -->
                                    </select>
                                </div>
                                <div id="divTextGeneral" class="col-sm-8 centerVertical">
                                    <span>Aplicable al monto total de un pedido</span>
                                </div>
                                <div id="divListProducts" class="form-group col-sm-8">
                                    <label for="listProductos" class="col-form-label">Al producto*</label>
                                    <select id="listProductos" class="form-control">
                                        <!-- Se insertan la lista de productos mediante api -->
                                    </select>
                                </div>
                                <div id="divCodeDiscount" class="form-group col-sm-8">
                                    <label for="codigoDescuento" class="col-form-label">Código del descuento*</label>
                                    <input id="codigoDescuento" type="text" class="form-control" maxlength="6" placeholder="Ingrese el código del descuento">
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label for="codigoDescuento" class="col-sm-3 col-form-label">Código del descuento*</label>
                                <div class="col-sm-9">
                                    <input id="codigoDescuento" type="text" class="form-control" maxlength="6" placeholder="Ingrese el código del descuento">
                                </div>
                            </div> -->
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Estado</label>
                                <div class="col-sm-9 centerVertical">
                                    <div class="custom-control custom-switch">
                                        <input id="estadoDescuento" class="custom-control-input" type="checkbox" role="switch" checked>
                                        <label id="labelSttDsc" for="estadoDescuento" class="custom-control-label">
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
        let dataTableDescuentos = $('#tabla_descuentos').DataTable({
            "ajax":{
                "url":urlDominio+'api/all_descuentos',
                "type": "GET",
                "dataSrc":""
            },
            "columns":[
                {"data":"id_descuento"},
                {"data":"dsc_nombre"},
                {
                    "data":"dsc_cantidad",
                    render(data){
                        return data+'%';
                    }
                },
                {"data":"tds_nombre"}, //tipo descuento
                {
                    "defaultContent":"<i>No aplicable</i>",
                    "render": function ( data, type, row, meta ) {
                        if (row["tds_nombre"] == 'Cupón') {
                            return "Código: " + row["dsc_codigo"];
                        } else if (row["tds_nombre"] == 'Individual') {
                            return "Producto: " + row["prd_nombre"];
                        } else {
                            return "<i>No aplicable</i>";
                        }
                    }
                },
                {
                    "data":"dsc_codigo",
                    visible: false
                },
                {
                    "data":"prd_nombre",
                    visible: false
                },
                {
                    "data":"dsc_estado",
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
                            columns: [0,1,2,3,4,7]
                        }
                    },
                    {
                        extend: 'excel',
                        text:'<i class="fas fa-file-excel"></i>',
                        titleAttr:'Formato Excel',
                        className: 'excelButton',
                        exportOptions: {
                            columns: [0,1,2,3,4,7]
                        }
                    },
                    {
                        extend: 'csv',
                        text:'<i class="fas fa-file-csv"></i>',
                        titleAttr:'Formato .csv',
                        className: 'csvButton',
                        exportOptions: {
                            columns: [0,1,2,3,4,7]
                        }
                    },
                    {
                        extend: 'print',
                        text:'<i class="fas fa-print"></i>',
                        titleAttr:'Imprimir',
                        className: 'printButton',
                        exportOptions: {
                            columns: [0,1,2,3,4,7]
                        }
                    }
                ]
            }
        });

        // CRUD logic
        let opcion, fila, id, nombre, cantidad, tipo, detalle, codigo, producto, estadoText;
        let switchEstado = document.getElementById("estadoDescuento");
        let labelSttDsc = document.getElementById("labelSttDsc");
        let select_tipos_descuentos = document.getElementById('tipoDescuento');
        let estado = switchEstado.checked;

        function selectElement(id, valueToSelect) {    
            let element = document.getElementById(id);
            element.value = valueToSelect;
        };

        switchEstado.onclick = function(){
            estado = !estado;
            if (estado) {
                labelSttDsc.innerHTML = `<span class="badge badge-pill badge-success">Activo</span>`;
            } else {
                labelSttDsc.innerHTML = `<span class="badge badge-pill badge-danger">Inactivo</span>`;
            }
        };

        function showAccordingElement(id) {
            switch(id) {
                case '1':
                    console.log("1");
                    $("#divTextGeneral").hide();
                    $("#divCodeDiscount").hide();
                    $("#divListProducts").show();
                    $("#listProductos").attr('required', 'required');
                    $("#codigoDescuento").removeAttr('required');
                    break;
                case '2':
                    console.log("2");
                    $("#divListProducts").hide();
                    $("#divCodeDiscount").hide();
                    $("#divTextGeneral").show();
                    $("#listProductos").removeAttr('required');
                    $("#codigoDescuento").removeAttr('required');
                    break;
                case '3':
                    console.log("3");
                    $("#divTextGeneral").hide();
                    $("#divListProducts").hide();
                    $("#divCodeDiscount").show();
                    $("#listProductos").removeAttr('required');
                    $("#codigoDescuento").attr('required', 'required');
                    break;
                default:
                    console.log("none");;
            };
        };

        select_tipos_descuentos.onchange = function(){
            console.log("select tipo descuento changed");
            console.log(select_tipos_descuentos.value);
            showAccordingElement(select_tipos_descuentos.value);
        };

        function listar_tipos_descuentos() {
            const url = urlDominio+'api/tipo_descuentos';

            //llamado al api tipo_descuentos, index
            fetch(url, {
                method: 'GET',
                headers: {
                    "Content-Type": "application/json",
                }
            })
            .then(res => res.json())
            .then(res => {
                console.log(res);
                res.forEach(tipo_descuento => {
                    let option_elem = document.createElement('option');
                    option_elem.value = tipo_descuento.id_tipo_descuento;
                    option_elem.textContent = tipo_descuento.tds_nombre;
                    select_tipos_descuentos.appendChild(option_elem);
                });
            })
            .catch(error => console.log(error));
        };

        function listar_productos() {
            let select_products = document.getElementById('listProductos');
            const url = urlDominio+'api/productos';

            //llamado al api productos, index
            fetch(url, {
                method: 'GET',
                headers: {
                    "Content-Type": "application/json",
                }
            })
            .then(res => res.json())
            .then(res => {
                console.log(res);
                res.forEach(producto => {
                    let option_elem = document.createElement('option');
                    option_elem.value = producto.id_producto;
                    option_elem.textContent = producto.prd_nombre;
                    select_products.appendChild(option_elem);
                });
            })
            .catch(error => console.log(error));
        };

        listar_tipos_descuentos();
        listar_productos();

        //Crear
        $('#btnCrear').click(function (){
            opcion = 'crear';
            $("#formDescuento").trigger("reset");
            $('.modal-header').css("background-color", "#6c757d");
            $('.modal-title').text("Nuevo descuento");
            $('#modalCRUD').modal('show');

            selectElement('tipoDescuento', '2');
            $("#divListProducts").hide();
            $("#divCodeDiscount").hide();
        })

        //Editar
        $(document).on('click', '.btnEditar', function (){
            opcion = 'editar';
            fila = $(this).closest('tr');

            id = fila.find('td:eq(0)').text();
            nombre = fila.find('td:eq(1)').text();
            cantidad = fila.find('td:eq(2)').text();
            cantidad = parseInt(cantidad.substring(0,cantidad.length-1));
            tipo = fila.find('td:eq(3)').text();
            detalle = fila.find('td:eq(4)').text();
            detalle = detalle.split(": ")[1];
            estadoText = fila.find('td:eq(5)').text();

            $("#idDescuento").val(id);
            $("#nombreDescuento").val(nombre);
            $("#cantidadDescuento").val(cantidad);
            $("#codigoDescuento").val(detalle);
            $("#estadoDescuento").val(estado);

            let idTipoDesc = $("#tipoDescuento option").filter(function() {
                                return $(this).text() == tipo;
                            }).val();
            console.log("idTipoDesc: "+idTipoDesc);
            $("#tipoDescuento").val(idTipoDesc);
            showAccordingElement(idTipoDesc);

            let idProducto = $("#listProductos option").filter(function() {
                                return $(this).text() == detalle;
                            }).val();
            console.log("idProducto: "+idProducto);
            $("#listProductos").val(idProducto);

            if ( estadoText == "Activo" ) {
                switchEstado.checked = true;
                labelSttDsc.innerHTML = `<span class="badge badge-pill badge-success">Activo</span>`;
            } else {
                switchEstado.checked = false;
                labelSttDsc.innerHTML = `<span class="badge badge-pill badge-danger">Inactivo</span>`;
            }
            estado = switchEstado.checked;

            $('.modal-header').css("background-color", "#007bff");
            $('.modal-title').text("Editar descuento");
            $('#modalCRUD').modal('show');
        })

        //Borrar
        $(document).on('click', '.btnEliminar', function (){
            fila = $(this).closest('tr');
            id = parseInt(fila.find('td:eq(0)').text());

            Swal.fire({
                title: 'Confirma eliminar el descuento?',
                showCancelButton: true,
                showConfirmButton: true,
                type: 'warning',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar',
            }).then( (result) => {
                if (result.value == true) {
                    //api descuento/id, delete
                    let url = urlDominio+'api/descuento/'+id;

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                        }
                    })
                    .then(res => res.json())
                    .then(success => {
                        dataTableDescuentos.ajax.reload(null, false);
                        Swal.fire('Descuento eliminado', '', 'success');
                        console.log(success);
                    })
                    .catch(error => console.log(error));
                }
            })
        })

        //submit form crear o editar
        $("#formDescuento").submit(function (e){
            e.preventDefault();

            id = $('#idDescuento').val();
            nombre = $('#nombreDescuento').val();
            cantidad = $("#cantidadDescuento").val();
            // estado = $("#estadoDescuento").val();
            tipo = $("#tipoDescuento").val();
            if(tipo == '1'){
                $("#codigoDescuento").val("");
            } else if (tipo == '2') {
                $("#codigoDescuento").val("");
                $("#listProductos").val("");
            } else {
                $("#listProductos").val("");
            }
            codigo = $("#codigoDescuento").val();
            producto = $("#listProductos").val();
            console.log("producto: "+producto);

            if(opcion == 'crear'){
                //api descuento, post
                let url = urlDominio + 'api/descuento';
                fetch(url, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                                            dsc_nombre: nombre,
                                            dsc_cantidad: cantidad,
                                            id_tipo_descuento: tipo,
                                            dsc_codigo: codigo,
                                            id_producto: producto,
                                            dsc_estado: estado
                                        })
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'El descuento se registró exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            dataTableDescuentos.ajax.reload(null, false);
                        });
                        $('#modalCRUD').modal('hide');
                    })
                    .catch(error => console.log(error));
            }
            if(opcion == 'editar'){
                //api descuento (update)
                let url = urlDominio + 'api/descuento/'+id;
                fetch(url, {
                    method: 'PUT',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                                            dsc_nombre: nombre,
                                            dsc_cantidad: cantidad,
                                            id_tipo_descuento: tipo,
                                            dsc_codigo: codigo,
                                            id_producto: producto,
                                            dsc_estado: estado
                                        })
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'El descuento se actualizó exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then( (result) => {
                            dataTableDescuentos.ajax.reload(null, false);
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