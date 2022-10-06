@extends('adminlte::page')

@section('title', 'Productos')

@section('content_header')
    <h1>Tabla Productos</h1>
@stop

@section('content')
    <div class="container-fluid">
        <button id="btnCrear" type="button" class="btn btn-secondary btn-crear">Crear</button>
    </div>

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
                    <th scope="col">Fecha venc.</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody id="lista_productos">
                <!-- lista de productos mediante api -->
            </tbody>
        </table>
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
                <form id="formCrear">
                    <div class="modal-body">
                        <div class="container-fluid">
                            <input id="idProd" type="hidden">
                            <div class="form-group row">
                                <label for="nombreProd" class="col-sm-2 col-form-label">Nombre</label>
                                <div class="col-sm-10">
                                    <input id="nombreProd" type="text" class="form-control" placeholder="Ingrese el nombre del producto" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="precioProd" class="col-sm-2 col-form-label">Precio</label>
                                <div class="input-group col-sm-10">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">S/</span>
                                    </div>
                                    <input id="precioProd" type="number" class="form-control" min="1" placeholder="Ingrese el precio del producto" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="stockProd" class="col-sm-2 col-form-label">Stock</label>
                                <div class="col-sm-10">
                                    <input id="stockProd" type="number" class="form-control" min="0" placeholder="Ingrese el stock inicial" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fechaVencProd" class="col-sm-5 col-form-label">Fecha de vencimiento</label>
                                <div class="col-sm-7">
                                    <input id="fechaVencProd" type="date" class="form-control" placeholder="Ingrese la fecha">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label for="categoriaProd" class="col-sm-2 col-form-label">Categoria</label>
                                    <select id="categoriaProd" class="form-control" required>

                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="marcaProd" class="col-sm-2 col-form-label">Marca</label>
                                    <select id="marcaProd" class="form-control" required>

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
                                <label for="imagenProd" class="col-sm-3 col-form-label">Imagen</label>
                                <div class="col-sm-9">
                                    <div class="custom-file">
                                        <input id="imagenProd" type="file" class="custom-file-input" accept="image/*">
                                        <label class="custom-file-label" for="imagenProd">Elegir una imagen</label>
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
    <script>
        const urlDominio = 'http://127.0.0.1:8080/api';
        let tabla = document.getElementById("tabla_productos");
        let fila, id, nombre, precio, stock, fechaVenc, categoria, marca, ;
        let dataTableProductos = $('#tabla_productos').DataTable({
            "ajax":{
                "url":urlDominio+'/productos',
                "dataSrc":""
            },
            "columns":[
                {"data":"id_producto"},
                {"data":"prd_nombre"},
                {"data":"prd_precio"},
                {"data":"prd_stock"},
                {"data":"id_categoria"},
                {"data":"id_marca"},
                {"data":"prd_fecha_vencimiento"},
                {"data":"prd_descripcion"},
                {"defaultContent":`<button class="btn btn-outline-primary btn-xs btnEditar"><i class="fas fa-pen"> Editar</i></button>
                                   <button class="btn btn-outline-danger btn-xs btnEliminar"><i class="fas fa-trash-can"> Eliminar</i></button>`}
            ],
            "columnDefs":[
                {
                    "targets":[2],
                    render(v){
                        return 'S/ '+Number(v).toFixed(2)
                    }
                }
            ]
        });

        //Crear
        $('#btnCrear').click(function (){
            $('.modal-header').css("background-color", "#6c757d");
            $('.modal-title').text("Nuevo Producto");
            $('#modalCRUD').modal('show');
        })

        //Editar
        $(document).on('click', 'btnEditar', function (){
            fila = $(this).closest('tr');
            id = parseInt(fila.find('td:eq(0)').text());
            precio = parseInt(fila.find('td:eq(0)').text());
            $('.modal-header').css("background-color", "#6c757d");
            $('.modal-title').text("Nuevo Producto");
            $('#modalCRUD').modal('show');
        })

        /*function listar_productos() {
            const url = urlDominio+'/productos';

            let lista_productos = document.getElementById('lista_productos');

            //llamado al api productos, index
            fetch(url, {
                method: 'GET',
                headers: {
                    "Content-Type": "application/json",
                }
            })
                .then(res => res.json(), console.log('Cargando API productos'))
                .then(res => {
                    console.log(res);
                    res.forEach(producto => {
                        let fila = tabla.insertRow();

                        let id = fila.insertCell(0);
                        id.innerHTML = producto.id_producto;
                        let nombre = fila.insertCell(1);
                        nombre.innerHTML = producto.prd_nombre;
                        let precio = fila.insertCell(2);
                        precio.innerHTML = "S/ " + producto.prd_precio;
                        let stock = fila.insertCell(3);
                        stock.innerHTML = producto.prd_stock;
                        let categoria = fila.insertCell(4);
                        categoria.innerHTML = producto.id_categoria;
                        let marca = fila.insertCell(5);
                        marca.innerHTML = producto.id_marca;
                        let fecha_vnc = fila.insertCell(6);
                        fecha_vnc.innerHTML = producto.prd_fecha_vencimiento;
                        let descripcion = fila.insertCell(7);
                        descripcion.innerHTML = producto.prd_descripcion;
                        let acciones = fila.insertCell(8);
                        acciones.innerHTML = `<a id="btnEditar" href="./editar_producto?` + producto.id_producto + `" class="btn btn-outline-primary btn-xs"><i class="fas fa-pen"> Editar</i></a>
                                            <button id="btnEliminar" onclick="eliminar_producto(` + producto.id_producto + `)" class="btn btn-outline-danger btn-xs"><i class="fas fa-trash-can"> Eliminar</i></button>`;
                    });
                })
                .catch(error => console.log(error));
        };

        listar_productos();*/

    </script>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
