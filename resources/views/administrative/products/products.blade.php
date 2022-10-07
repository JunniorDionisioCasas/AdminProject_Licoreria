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
                    <th scope="col">Img path</th>
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
                <form id="formProducto">
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
                                    <input id="precioProd" type="number" class="form-control" min="1" step="0.01" placeholder="Ingrese el precio del producto" required>
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
                                        <!-- Se insertan la lista de categorias mediante api -->
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="marcaProd" class="col-sm-2 col-form-label">Marca</label>
                                    <select id="marcaProd" class="form-control" required>
                                        <!-- Se insertan la lista de marcas mediante api -->
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
                                <label class="col-sm-3 col-form-label">Imagen</label>
                                <div class="input-group col-sm-9">
                                    <div class="custom-file">
                                        <input id="imagenProd" name="imagenProd" type="file" class="custom-file-input" accept="image/*">
                                        <label class="custom-file-label" for="imagenProd" data-browse="Elegir">Seleccionar imagen</label>
                                    </div>
                                </div>
                            </div>
                            <div class="div-img-center">
                                <img id="imgPreview" class="rounded-circle avatar-lg img-thumbnail img-preview" alt="product-image">
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
        let tabla = document.getElementById("tabla_productos");
        let imagen = document.getElementById("imagenProd");
        let opcion, fila, id, nombre, precio, stock, fechaVenc, categoria, marca, descripcion, imgPath;

        let dataTableProductos = $('#tabla_productos').DataTable({
            "ajax":{
                "url":urlDominio+'api/productos',
                "dataSrc":""
            },
            "columns":[
                {"data":"id_producto"},
                {"data":"prd_nombre"},
                {"data":"prd_precio"},
                {"data":"prd_stock"},
                {"data":"ctg_nombre"},
                {"data":"mrc_nombre"},
                {"data":"prd_fecha_vencimiento"},
                {"data":"prd_descripcion"},
                {"data":"prd_imagen_path"},
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

        listar_categorias();
        listar_marcas();

        //Crear
        $('#btnCrear').click(function (){
            opcion = 'crear';
            $("#formProducto").trigger("reset");
            $("#imagenProd").prop("required", true);
            $("#imgPreview").attr("src", "");
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
            fechaVenc = fila.find('td:eq(6)').text();
            descripcion = fila.find('td:eq(7)').text();
            imgPath = fila.find('td:eq(8)').text();

            $("#idProd").val(id);
            $("#nombreProd").val(nombre);
            $("#precioProd").val(precio);
            $("#stockProd").val(stock);
            $("#fechaVencProd").val(fechaVenc);
            $("#descProd").val(descripcion);

            $("#categoriaProd option").filter(function() {
                return $(this).text() == categoria;
            }).attr('selected', true);

            $("#marcaProd option").filter(function() {
                return $(this).text() == marca;
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
            fechaVenc = $.trim($('#fechaVencProd').val());
            descripcion = $.trim($('#descProd').val());

            let formData = new FormData();

            formData.append('prd_nombre', nombre);
            formData.append('prd_precio', precio);
            formData.append('prd_stock', stock);
            formData.append('id_categoria', categoria);
            formData.append('id_marca', marca);
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
@section('plugins.Sweetalert2', true)
