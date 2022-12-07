@extends('adminlte::page')

@section('title', 'Registro de nuevo pedido')

@section('content_header')
    <h1>Registrar nuevo pedido</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <h5>Selecciones los productos</h5>
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

    <div class="card">
        <div class="card-header">
            <div class="container-fluid">
                <h5>Datos del cliente</h5>
            </div>
        </div>
        <div class="card-body">
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
                "url":urlDominio+'api/productos',
                "type": "GET",
                "dataSrc":""
            },
            "columns":[
                {"data":"id_producto"},
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
                {"data":"prd_imagen_path"},
                {
                    "defaultContent":`<button class="btn btn-default mx-1 shadow btnAdd" title="Añadir">
                                        <i class="fas fa-cart-plus"></i>
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
        });

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
