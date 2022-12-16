@extends('adminlte::page')

@section('title', 'Registro de nuevo pedido')

@section('content_header')
    <h1>Registrar nuevo pedido</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="container-fluid display-in-flex">
                <h5>Selecciones los productos</h5>
                <button id="btnAdministrarPrd" type="button" class="btn btn-secondary btn-redirect-crud">
                    <i class="fa fa-arrow-alt-circle-left"></i>
                    Administrar productos
                </button>
            </div>
        </div>
        <div class="card-body text-white bg-gradient-info mb-3">
            <div class="table-responsive">
                <table id="tabla_productos" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Imagen</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Proveedor</th>
                            <th scope="col">Fecha venc.</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Agregar</th>
                        </tr>
                    </thead>
                    <tbody id="lista_productos">
                        <!-- lista de productos mediante api -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="container-fluid display-in-flex">
                        <h5>Datos del cliente</h5>
                        <button id="btnAdministrarClt" type="button" class="btn btn-secondary btn-redirect-crud">
                            <i class="fa fa-arrow-alt-circle-left"></i>
                            Administrar clientes
                        </button>
                    </div>
                </div>
                <div class="card-body text-white bg-gradient-primary mb-3">
                    <!-- clients table -->
                    <div id="divClientsTable" class="table-responsive">
                        <table id="tabla_clientes" class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellidos</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Dirección</th>
                                    <th scope="col">Selec.</th>
                                </tr>
                            </thead>
                            <tbody id="lista_clientes">
                                <!-- lista de clientes mediante api -->
                            </tbody>
                        </table>
                    </div>
                    <!-- selected client info -->
                    <div id="divSelectedClient">
                        <div class="row vertical-margin">
                            <div class="col-md-5 text-right">
                                <span>Cliente:</span>
                            </div>
                            <div class="col-md-7">
                                <b id="spanCltName"></b>
                            </div>
                        </div>
                        <div class="row vertical-margin">
                            <div class="col-md-5 text-right">
                                <span>Correo:</span>
                            </div>
                            <div class="col-md-7">
                                <b id="spanCltEmail"></b>
                            </div>
                        </div>
                        <button id="btnChangeClt" type="button" class="btn btn-secondary btn-redirect-crud">
                            <i class="fa fa-user-times"></i>
                            Cambiar cliente
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="container-fluid">
                        <h5>Carrito del cliente</h5>
                    </div>
                </div>
                <div class="card-body text-white bg-gradient-success mb-3">
                    <table id="tabla_cliente_carrito" class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Imagen</th>
                                <th scope="col">Nombre</th>
                                <th scope="col" class="text-center">Precio Unit.</th>
                                <th scope="col" class="text-center">Cantidad</th>
                                <th scope="col" class="text-center">Precio Total</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody id="lista_cliente_carrito">
                            <tr id="msg-empty-cart">
                                <td></td>
                                <td class="text-center" colspan="6">Carrito vacio</td>
                            </tr>
                            <!-- lista de productos escogidos para el carrito -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td class="text-right" colspan="5" id="txt_prc_total">
                                    <b>S/ 0.00</b>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
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
        /* Products table */
        let dataTableProductos = $('#tabla_productos').DataTable({
            "ajax":{
                "url":urlDominio+'api/productos',
                "type": "GET",
                "dataSrc":""
            },
            "columns":[
                {
                    "data":"id_producto",
                    visible: false
                },
                {
                    "data":"prd_imagen_path",
                    "defaultContent":"<i>Sin imagen</i>",
                    "orderable":false,
                    "render": function ( data, type, row, meta ) {
                        return `<img src="${data}" alt="${row['prd_nombre']}" width="100" height="100">`;
                    }
                },
                {"data":"prd_nombre"},
                {"data":"prd_precio"},
                {
                    "data":"prd_stock",
                },
                {"data":"ctg_nombre"},
                {"data":"mrc_nombre"},
                {
                    "data":"prv_nombre",
                    "orderable":false
                },
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
                    "defaultContent":`<button class="btn btn-default mx-1 shadow btnAdd" title="Añadir">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>`,
                    "orderable":false
                }
            ],
            "columnDefs":[
                {
                    "targets":[3],
                    render(v){
                        return 'S/ '+Number(v).toFixed(2)
                    }
                }
            ],
            autoWidth: false,
            language: {
                url: 'vendor/datatables-plugins/internationalisation/es-ES.json'
            },
            select: true
        });

        /* Clientes table */
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
                {
                    "data":"id",
                    visible: false
                },
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
                    "defaultContent":`<i>${nullCellText}</i>`,
                    visible: false
                },
                {
                    "defaultContent":`<button class="btn btn-default mx-1 shadow btnSelect" title="Seleccionar">
                                        <i class="fa fa-mouse-pointer"></i>
                                    </button>`,
                    "orderable":false
                }
            ],
            autoWidth: false,
            language: {
                url: 'vendor/datatables-plugins/internationalisation/es-ES.json'
            },
            dom:"<'row'<'col-lg-12 col-xl-6'l><'col-lg-12 col-xl-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-lg-12 col-xl-auto'i><'col-lg-12 col-xl-auto'p>>",
        });

        let listCartTable = document.getElementById("lista_cliente_carrito");
        let btnRedirectPrd = document.getElementById("btnAdministrarPrd");
        let btnRedirectClt = document.getElementById("btnAdministrarClt");
        let fila, idClt, nombreApllClt, emailClt, idPrd, nombrePrd, precioPrd, stockPrd, imgPathPrd;
        let disabledIfMaxStock = '';
        let carrito = [];

        $('#divSelectedClient').hide();
        btnRedirectPrd.addEventListener("click", function(){ location.href = '/productos' });
        btnRedirectClt.addEventListener("click", function(){ location.href = '/clientes' });

        // select client
        $(document).on('click', '.btnSelect', function (){
            fila = $(this).closest('tr');

            idClt = dataTableClientes.row(fila).data()['id'];
            nombreApllClt = fila.find('td:eq(0)').text();
            emailClt = fila.find('td:eq(1)').text();

            $('#spanCltName').text(nombreApllClt);
            $('#spanCltEmail').text(emailClt);

            $('#divClientsTable').hide();
            $('#divSelectedClient').show();
        });

        // change client
        $(document).on('click', '#btnChangeClt', function (){
            $('#divClientsTable').show();
            $('#divSelectedClient').hide();
        });

        // add product to cart
        $(document).on('click', '.btnAdd', function (){
            fila = $(this).closest('tr');

            idPrd = dataTableProductos.row(fila).data()['id_producto'];
            imgPathPrd = dataTableProductos.row(fila).data()['prd_imagen_path'];
            nombrePrd = fila.find('td:eq(1)').text();
            precioPrd = dataTableProductos.row(fila).data()['prd_precio'];

            console.log("id prod: "+idPrd);

            if(addProduct(idPrd)) {
                listCartTable.insertAdjacentHTML('beforeend', 
                    `<tr>
                        <td>${idPrd}</td>
                        <td>
                            <img src="${imgPathPrd}" alt="${nombrePrd}" width="50" height="50">
                        </td>
                        <td>${nombrePrd}</td>
                        <td class="text-center">S/ ${precioPrd}</td>
                        <td class="text-center">
                            <button id="btn_decrs_${idPrd}" class="btn btn-xs btn-default shadow btnIncrAmnt" type="button" title="Menos" onclick="decr_cant(event, ${idPrd}, ${parseFloat(precioPrd).toFixed(2)})">
                                <i class="fa fa-minus fa-sm"></i>
                            </button>
                            <span id="txt_cntd_${idPrd}">1</span>
                            <button id="btn_incrs_${idPrd}" class="btn btn-xs btn-default shadow btnDcrsAmnt" type="button" title="Más" onclick="incr_cant(event, ${idPrd}, ${parseFloat(precioPrd).toFixed(2)})" ${disabledIfMaxStock}>
                                <i class="fa fa-plus fa-sm"></i>
                            </button>
                        </td>
                        <td class="text-center" id="multiplied_prc_${idPrd}">S/ ${parseFloat(precioPrd).toFixed(2)}</td>
                        <td>
                            <button type="button" class="btn btn-xs  btn-default btnDltPrd" onclick="quitarProd(${idPrd})">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>`);

                $('#msg-empty-cart').hide();
            } else {
                let qnt = $('#txt_cntd_'+idPrd).text();
                $('#txt_cntd_'+idPrd).text(parseInt(qnt)+1);
            }
        });

        let addProduct = (id) => {
            let search = carrito.find( (x) => x.id === id );
            if(search === undefined){
                carrito.push({
                    id: id,
                    cntd: 1
                });

                console.log(carrito);
                return true;
            }else{
                search.cntd++;

                console.log(carrito);
                return false;
            }
            // calculation();
        };

        let increment = (id) => {
            let search = carrito.find( (x) => x.id === id );
            search.cntd += 1;
            console.log(carrito);
            // calculation();
        };

        let decrement = (id) => {
            let search = carrito.find( (x) => x.id === id );
            if(search.cntd === 0)
                return;
            else
                search.cntd -= 1;
            
            console.log(carrito);
            // calculation();
        };

        let remove = (id) => {
            console.log(id);
            carrito = carrito.filter( (x) => x.id !== id);

            // calculation();
        };

        let update_total_prices = () => {
            document.getElementById('txt_prc_total').innerHTML = `<b>S/ ${prc_total.toFixed(2)}</b>`;
        };

        // increase decrease product amount
        let calcular_precio_x_prd = (id, prc, cntd) => {
            document.getElementById("multiplied_prc_"+id).innerHTML = "S/ " + (prc * cntd).toFixed(2);
        };

        let incr_cant = (e, id, prc) => {
            e = e || window.event;
            let target = e.target || e.srcElement;
            let elmt_cntd = document.getElementById("txt_cntd_"+id);
            let cntd = parseInt(elmt_cntd.innerHTML);
            let fila = $(e.target).get(0);
            let stock = dataTableProductos.row(fila).data()['id_producto'];
            
            if(cntd == 1){
                document.getElementById("btn_decrs_"+id).disabled = false;
            }

            cntd = cntd + 1;
            elmt_cntd.innerHTML = cntd;

            calcular_precio_x_prd(id, prc, cntd);
            increment(id);
            update_total_prices();
        }

        let decr_cant = (e, id, prc) => {
            e = e || window.event;
            let target = e.target || e.srcElement;
            let elmt_cntd = document.getElementById("txt_cntd_"+id);
            let cntd = parseInt(elmt_cntd.innerHTML);
            
            if(cntd >= 2){
                cntd = cntd - 1;
                elmt_cntd.innerHTML = cntd;

                if(cntd == 1){
                    document.getElementById("btn_decrs_"+id).disabled = true;
                }
            }

            calcular_precio_x_prd(id, prc, cntd);
            decrement(id);
            update_total_prices();
        }

    </script>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)
