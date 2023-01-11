@extends('adminlte::page')

@section('title', 'Productos')

@section('content_header')
    <h1>Tabla Productos</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <button id="btnCrear" type="button" class="btn btn-secondary btn-crear">Nuevo producto</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla_productos" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Proveedor</th>
                            <th scope="col">Fecha venc.</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Img path</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="lista_productos">
                        <!-- lista de productos mediante api -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modalCRUD" class="modal" aria-labelledby="Formulario de nuevo producto" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formProducto">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input id="idProd" type="hidden">
                            <div class="form-group row">
                                <label for="nombreProd" class="col-sm-2 col-form-label">Nombre*</label>
                                <div class="col-sm-10">
                                    <input id="nombreProd" type="text" class="form-control" placeholder="Ingrese el nombre del producto" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="precioProd" class="col-sm-2 col-form-label">Precio*</label>
                                <div class="input-group col-sm-10">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">S/</span>
                                    </div>
                                    <input id="precioProd" type="number" class="form-control" min="1" step="0.01" placeholder="Ingrese el precio del producto" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="stockProd" class="col-sm-2 col-form-label">Stock*</label>
                                <div class="col-sm-10">
                                    <input id="stockProd" type="number" class="form-control" min="0" placeholder="Ingrese el stock inicial" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fechaVencProd" class="col-sm-7 col-form-label">Fecha de vencimiento (opcional)</label>
                                <div class="col-sm-5">
                                    <input id="fechaVencProd" type="date" class="form-control" placeholder="Ingrese la fecha">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-4">
                                    <label for="categoriaProd" class="col-sm-2 col-form-label">Categoria*</label>
                                    <select id="categoriaProd" class="form-control" required>
                                        <!-- Se insertan la lista de categorias mediante api -->
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="marcaProd" class="col-sm-2 col-form-label">Marca*</label>
                                    <select id="marcaProd" class="form-control" required>
                                        <!-- Se insertan la lista de marcas mediante api -->
                                    </select>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="proveedorProd" class="col-sm-2 col-form-label">Proveedor*</label>
                                    <select id="proveedorProd" class="form-control" required>
                                        <!-- Se insertan la lista de proveedores mediante api -->
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="descProd" class="col-sm-3 col-form-label">Descripción</label>
                                <div class="col-sm-9">
                                    <textarea id="descProd" rows="2" class="form-control" placeholder="Ingrese una descripcion (opcional)"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Imagen*</label>
                                <div class="input-group col-sm-9">
                                    <div class="custom-file">
                                        <input id="imagenProd" name="imagenProd" type="file" class="custom-file-input" accept="image/*">
                                        <label class="custom-file-label" for="imagenProd" data-browse="Elegir">Seleccionar imagen</label>
                                    </div>
                                </div>
                            </div>
                            <div class="div-img-center">
                                <img id="imgPreview" class="rounded-circle avatar-lg img-thumbnail img-preview" alt="product-image">
                                <small class="form-text text-muted">800x960 px preferentemente</small>
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
        let dataTableProductos = $('#tabla_productos').DataTable({
            "ajax":{
                "url":urlDominio+'api/all_products',
                "type": "GET",
                "dataSrc":""
            },
            "columns":[
                {
                    "data":"id_producto",
                    visible: false
                },
                {"data":"prd_nombre"},
                {"data":"prd_precio"},
                {"data":"prd_stock"},
                {"data":"ctg_nombre"},
                {"data":"mrc_nombre"},
                {"data":"prv_nombre"},
                {
                    "data":"prd_fecha_vencimiento",
                    "defaultContent":"<i>No aplicable</i>"
                },
                {
                    "data":"prd_descripcion",
                    "defaultContent":"<i>Sin descripción</i>",
                    "orderable":false
                },
                {
                    "data":"prd_imagen_path",
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
            "columnDefs":[
                {
                    "targets":[2],
                    render(v){
                        return 'S/ '+Number(v).toFixed(2)
                    }
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
                        extend: 'excelHtml5',
                        text:'<i class="fas fa-file-excel"></i>',
                        titleAttr:'Formato Excel',
                        className: 'excelButton',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6]
                        },
                        title: document.title,
                        messageTop: 'Fecha de consulta: ' + "{{Carbon\Carbon::now('-05:00')->locale('es_PE')->isoFormat('LLLL')}}",
                        excelStyles: [
                            {
                                template: 'blue_medium',
                            }
                        ],
                        /* insertCells: [
                            {
                                "cells": "A",
                                "content": "This",
                                "pushCol": true
                            }
                        ] */
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i>',
                        titleAttr:'Formato PDF',
                        className: 'pdfButton',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6]
                        },
                        orientation: 'portrait',
                        pageSize: 'LEGAL',
                        title: document.title,
                        messageTop: 'Fecha de consulta: ' + "{{Carbon\Carbon::now('-05:00')->locale('es_PE')->isoFormat('LLLL')}}",
                        footer: true,
                        customize: function ( doc ) {
                            console.log( doc.content );
                            /* doc['header'] = (function (page, pages) {
                                return {
                                    table: {
                                        widths: ['20%', 'auto'],
                                        headerRows: 0,
                                        body: [
                                            [
                                                {
                                                    width: 50,
                                                    alignment: 'center',
                                                    image: 'data:image/jpeg;base64,...'
                                                },
                                                {
                                                    text: 'San Sebastián - Productos',
                                                    alignment: 'center',
                                                    fontSize: 20,
                                                    bold: true,
                                                    style: 'title',
                                                    width: 'auto'
                                                }
                                            ]
                                        ]
                                    },
                                    layout: 'noBorders',
                                    margin: 10,
                                }
                            });
                            doc.content[0] = (function (doc) {
                                return {
                                    text: '',
                                    margin: 20
                                }
                            }); */
                            doc.defaultStyle.fontSize = 13;
                            doc.content[2].table.widths = ['*','auto','*','*','*','*','*'];//"*";//Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                            doc.defaultStyle.alignment = 'center';
                            doc.styles.tableHeader.alignment = 'center';
                            doc.content.splice( 0, 0, {
                                alignment: 'center',
                                width: 75,
                                margin: [0, 0, 0, 12],
                                image: 'data:image/jpeg;base64,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'
                            } );
                            doc['footer'] = (function (page, pages) {
                                let rcout = doc.content[doc.content.length - 1].table.body.length - 1;
                                return {
                                    columns: [
                                        /* {
                                            alignment: 'left',
                                            text: ['Creado el: ', { text: "{{Carbon\Carbon::now()->locale('es_PE')->isoFormat('LLLL')}}" }]
                                        }, */
                                        {
                                            alignment: 'left',
                                            text: 'Total ' + rcout.toString() + ' filas'
                                        },
                                        {
                                            alignment: 'right',
                                            text: ['página ', { text: page.toString() }, ' de ', { text: pages.toString() }]
                                        }
                                    ],
                                    margin: 10
                                }
                            });
                        }
                    },
                    {
                        extend: 'print',
                        text:'<i class="fas fa-print"></i>',
                        titleAttr:'Imprimir',
                        className: 'printButton',
                        exportOptions: {
                            columns: [0,1,2,3,4,5,6]
                        },
                        messageTop: 'Fecha de consulta: ' + "{{Carbon\Carbon::now('-05:00')->locale('es_PE')->isoFormat('LLLL')}}",
                    }
                ]
            }
        });

        let tabla = document.getElementById("tabla_productos");
        let imagen = document.getElementById("imagenProd");
        let opcion, fila, id, nombre, precio, stock, fechaVenc, categoria, marca, proveedor, descripcion, imgPath;

        function listar_categorias() {
            const url = urlDominio+'api/categorias';

            let select_categorias = document.getElementById('categoriaProd');

            //llamado al api categorias, index
            fetch(url, {
                method: 'GET',
                headers: {
                    "Content-Type": "application/json",
                }
            })
                .then(res => res.json(), console.log('Cargando API categorias'))
                .then(res => {
                    console.log(res);
                    res.forEach(categoria => {
                        // select_categorias.append($("<option />").val(categoria.id_categoria).text(categoria.ctg_nombre));

                        let option_elem = document.createElement('option');
                        option_elem.value = categoria.id_categoria;
                        option_elem.textContent = categoria.ctg_nombre;
                        select_categorias.appendChild(option_elem);
                    });
                })
                .catch(error => console.log(error));
        };

        function listar_marcas() {
            const url = urlDominio+'api/marcas';

            let select_marcas = document.getElementById('marcaProd');

            //llamado al api marcas, index
            fetch(url, {
                method: 'GET',
                headers: {
                    "Content-Type": "application/json",
                }
            })
            .then(res => res.json(), console.log('Cargando API marcas'))
            .then(res => {
                console.log(res);
                res.forEach(marca => {
                    let option_elem = document.createElement('option');
                    option_elem.value = marca.id_marca;
                    option_elem.textContent = marca.mrc_nombre;
                    select_marcas.appendChild(option_elem);
                });
            })
            .catch(error => console.log(error));
        };

        function listar_proveedores() {
            const url = urlDominio+'api/proveedores';

            let select_proveedores = document.getElementById('proveedorProd');

            //llamado al api proveedores, index
            fetch(url, {
                method: 'GET',
                headers: {
                    "Content-Type": "application/json",
                }
            })
            .then(res => res.json(), console.log('Cargando API proveedores'))
            .then(res => {
                console.log(res);
                res.forEach(proveedor => {
                    let option_elem = document.createElement('option');
                    option_elem.value = proveedor.id_proveedor;
                    option_elem.textContent = proveedor.prv_nombre;
                    select_proveedores.appendChild(option_elem);
                });
            })
            .catch(error => console.log(error));
        };

        listar_categorias();
        listar_marcas();
        listar_proveedores();

        //Crear
        $('#btnCrear').click(function (){
            opcion = 'crear';
            $("#formProducto").trigger("reset");
            $("#imagenProd").prop("required", true);
            $("#imgPreview").attr("src", "images/placeholder.png");
            $('.modal-header').css("background-color", "#6c757d");
            $('.modal-title').text("Nuevo producto");
            $('#modalCRUD').modal('show');
        })

        //Editar
        $(document).on('click', '.btnEditar', function (){
            opcion = 'editar';
            $("#imagenProd").prop("required", false);
            fila = $(this).closest('tr');

            id = parseInt(fila.find('td:eq(0)').text());
            nombre = fila.find('td:eq(1)').text();
            precio = parseFloat(fila.find('td:eq(2)').text().substring(2));
            stock = parseInt(fila.find('td:eq(3)').text());
            categoria = fila.find('td:eq(4)').text();
            marca = fila.find('td:eq(5)').text();
            proveedor = fila.find('td:eq(6)').text();
            fechaVenc = fila.find('td:eq(7)').text();
            descripcion = fila.find('td:eq(8)').text();
            imgPath = fila.find('td:eq(9)').text();

            $("#idProd").val(id);
            $("#nombreProd").val(nombre);
            $("#precioProd").val(precio);
            $("#stockProd").val(stock);
            $("#fechaVencProd").val(fechaVenc);
            $("#descProd").val(descripcion);

            $("#categoriaProd option").filter(':selected').attr('selected', false);
            $("#categoriaProd option").filter(function() {
                return $(this).text() == categoria;
            }).attr('selected', true);

            $("#marcaProd option").filter(':selected').attr('selected', false);
            $("#marcaProd option").filter(function() {
                return $(this).text() == marca;
            }).attr('selected', true);

            $("#proveedorProd option").filter(':selected').attr('selected', false);
            $("#proveedorProd option").filter(function() {
                return $(this).text() == proveedor;
            }).attr('selected', true);

            $("#imgPreview").attr("src", imgPath); //urlDominio+"images/productos/"+imgPath

            $('.modal-header').css("background-color", "#007bff");
            $('.modal-title').text("Editar producto");
            $('#modalCRUD').modal('show');
        })

        //Borrar
        $(document).on('click', '.btnEliminar', function (){
            fila = $(this).closest('tr');
            id = parseInt(fila.find('td:eq(0)').text());

            Swal.fire({
                title: 'Confirma eliminar el producto?',
                showCancelButton: true,
                showConfirmButton: true,
                type: 'warning',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Confirmar',
            }).then( (result) => {
                console.log(result);
                if (result.value == true) {
                    //api producto/id, delete
                    let url = urlDominio+'api/producto/'+id;

                    fetch(url, {
                        method: 'DELETE',
                        headers: {
                            "Content-Type": "application/json",
                        }
                    })
                    .then(res => res.json(), console.log('Cargando API delete producto/id'))
                    .then(success => {
                        // dataTableProductos.row(fila.parents('tr')).remove().draw();
                        dataTableProductos.ajax.reload(null, false);
                        Swal.fire('Producto eliminado', '', 'success');
                        console.log(success);
                    })
                    .catch(error => console.log(error));
                }else{
                    console.log('cancelado');
                }
            })
        })

        //submit form crear o editar
        $("#formProducto").submit(function (e){
            e.preventDefault();
            id = $('#idProd').val();
            nombre = $('#nombreProd').val();
            precio = $.trim($('#precioProd').val());
            stock = $.trim($('#stockProd').val());
            categoria = $('#categoriaProd').val();
            marca = $('#marcaProd').val();
            proveedor = $('#proveedorProd').val();
            fechaVenc = $.trim($('#fechaVencProd').val());
            descripcion = $.trim($('#descProd').val());

            let formData = new FormData();

            formData.append('prd_nombre', nombre);
            formData.append('prd_precio', precio);
            formData.append('prd_stock', stock);
            formData.append('id_categoria', categoria);
            formData.append('id_marca', marca);
            formData.append('id_proveedor', proveedor);
            formData.append('prd_fecha_vencimiento', fechaVenc);
            formData.append('prd_descripcion', descripcion);
            if(imagen.files.length !== 0){
                formData.append('prd_imagen', imagen.files[0]);
            }

            //prueba de obtencio de datos del form
            for (const pair of formData) {
                console.log(`${pair[0]}: ${pair[1]}\n`);
            }

            if(opcion == 'crear'){
                //api producto, post
                let url = urlDominio + 'api/producto';
                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'El producto se registró exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            dataTableProductos.ajax.reload(null, false);
                            // location.reload();
                        });
                        $('#modalCRUD').modal('hide');
                    })
                    .catch(error => console.log(error));
            }
            if(opcion == 'editar'){
                //api producto (update)
                let url = urlDominio + 'api/producto/'+id;
                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(success => {
                        console.log(success);

                        Swal.fire({
                            title: 'Exito!',
                            text: 'El producto se actualizó exitosamente',
                            type: 'success',
                            confirmButtonText: 'Ok'
                        }).then( (result) => {
                            dataTableProductos.ajax.reload(null, false);
                            // location.reload();
                        });

                        $('#modalCRUD').modal('hide');
                    })
                    .catch(error => console.log(error));
            }
        });

        $('#imagenProd').on('change',function(e){
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
