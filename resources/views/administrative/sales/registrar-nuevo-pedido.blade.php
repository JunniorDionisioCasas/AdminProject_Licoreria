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
                                <td class="text-right" colspan="4">
                                    <b>TOTAL:</b>
                                </td>
                                <td id="txt_prc_total" class="text-right">
                                    <b>S/ 0.00</b>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="tdFooterTotal">
                <button id="btnRealizarVenta" type="button" class="btn btn-danger">Realizar venta</button>
            </div>
            @php
                $app_path = app_path();
                $database_path = database_path();
                $public_path = public_path();
                $base_path = base_path();
                $lang_path = lang_path();
                $resource_path = resource_path();
                $config_path = config_path();
                $storage_path = storage_path();
                $url = url('/invoices');
            @endphp
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="js/urlDomain.js"></script>
    <script>
        console.log("{{$app_path}}");
        console.log("{{$database_path}}");
        console.log("{{$public_path}}");
        console.log("{{$base_path}}");
        console.log("{{$lang_path}}");
        console.log("{{$resource_path}}");
        console.log("{{$config_path}}");
        console.log("{{$storage_path}}");
        console.log("{{$url}}");
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
                    "render": function ( data, type, row, meta ) {
                        return `<button id="btnAdd-${row['id_producto']}" class="btn btn-default mx-1 shadow btnAdd" title="Añadir">
                                    <i class="fas fa-cart-plus"></i>
                                </button>`;
                    },
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
            pageLength: 3,
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
            pageLength: 5,
            autoWidth: false,
            language: {
                url: 'vendor/datatables-plugins/internationalisation/es-ES.json'
            },
            dom:"<'row'<'col-lg-12 col-xl-6'l><'col-lg-12 col-xl-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-lg-12 col-xl-auto'i><'col-lg-12 col-xl-auto'p>>",
        });

        let cartTable = document.getElementById("tabla_cliente_carrito");
        let listCartTableBody = document.getElementById("lista_cliente_carrito");
        let btnRedirectPrd = document.getElementById("btnAdministrarPrd");
        let btnRedirectClt = document.getElementById("btnAdministrarClt");
        let btnRealizarVenta = document.getElementById("btnRealizarVenta");
        let idClt, nombreApllClt, emailClt, idPrd, nombrePrd, precioPrd, stockPrd, imgPathPrd;
        let disabledIfMaxStock = '';
        let carrito = [];
        let prc_total = 0;

        $('#divSelectedClient').hide();
        btnRedirectPrd.addEventListener("click", function(){ location.href = '/productos' });
        btnRedirectClt.addEventListener("click", function(){ location.href = '/clientes' });
        btnRealizarVenta.addEventListener("click", function(){ registro_venta() });

        // select client
        $(document).on('click', '.btnSelect', function (){
            let fila = $(this).closest('tr');

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
            let fila = $(this).closest('tr');

            idPrd = dataTableProductos.row(fila).data()['id_producto'];
            imgPathPrd = dataTableProductos.row(fila).data()['prd_imagen_path'];
            nombrePrd = fila.find('td:eq(1)').text();
            precioPrd = dataTableProductos.row(fila).data()['prd_precio'];
            stockPrd = dataTableProductos.row(fila).data()['prd_stock'];
            disabledIfMaxStock = (stockPrd == 1) ? disabled : '';

            console.log("id prod: "+idPrd);

            if(addProduct(idPrd, precioPrd, nombrePrd)) {
                listCartTableBody.insertAdjacentHTML('beforeend', 
                    `<tr>
                        <td>${idPrd}</td>
                        <td>
                            <img src="${imgPathPrd}" alt="${nombrePrd}" width="auto" height="50">
                        </td>
                        <td>${nombrePrd}</td>
                        <td class="text-center">S/ ${precioPrd}</td>
                        <td class="text-center">
                            <button id="btn_decrs_${idPrd}" class="btn btn-xs btn-default shadow btnDcrsAmnt" type="button" title="Menos" onclick="decr_cant(${idPrd}, ${parseFloat(precioPrd).toFixed(2)})" disabled>
                                <i class="fa fa-minus fa-sm"></i>
                            </button>
                            <span id="txt_cntd_${idPrd}">1</span>
                            <button id="btn_incrs_${idPrd}" class="btn btn-xs btn-default shadow btnIncrAmnt" type="button" title="Más" onclick="incr_cant(${idPrd}, ${parseFloat(precioPrd).toFixed(2)}, ${stockPrd})" ${disabledIfMaxStock}>
                                <i class="fa fa-plus fa-sm"></i>
                            </button>
                        </td>
                        <td class="text-center" id="multiplied_prc_${idPrd}">S/ ${parseFloat(precioPrd).toFixed(2)}</td>
                        <td>
                            <button type="button" class="btn btn-xs  btn-default btnDltPrd" onclick="quitarProd(event, ${idPrd})">
                                <i class="fa fa-times"></i>
                            </button>
                        </td>
                    </tr>`);

                $('#msg-empty-cart').hide();
            } else {
                const qnt = $('#txt_cntd_'+idPrd).text();
                const newCant = parseInt(qnt);

                if(newCant == stockPrd) {
                    $(this).prop('disabled', true);
                    $('#btn_incrs_'+idPrd).prop('disabled', true);
                }
            }
        });

        let calculation = () => {
            prc_total = 0;
            //obteniendo total
            carrito.forEach(p => {
                prc_total += p.cntd * p.precio;
            });

            prc_total = parseFloat(prc_total.toFixed(2));
            update_total_prices();
        };

        let addProduct = (id, prc, nmbr) => {
            let search = carrito.find( (x) => x.id === id );
            if(search === undefined){
                carrito.push({
                    id: id,
                    cntd: 1,
                    nmbr: nmbr,
                    precio: prc
                });

                calculation();
                console.log(carrito);
                return true;
            }else{
                document.getElementById("btn_decrs_"+id).disabled = false;
                document.getElementById("btn_incrs_"+id).click();
                
                // search.cntd++;
                console.log(carrito);

                return false;
            }
        };

        let increment = (id) => {
            let search = carrito.find( (x) => x.id === id );
            search.cntd += 1;
            console.log(carrito);
            calculation();
        };

        let decrement = (id) => {
            let search = carrito.find( (x) => x.id === id );
            if(search.cntd === 0)
                return;
            else
                search.cntd -= 1;
            
            console.log(carrito);
            calculation();
        };

        let remove = (id) => {
            console.log(id);
            carrito = carrito.filter( (x) => x.id !== id);

            calculation();
        };

        let update_total_prices = () => {
            document.getElementById('txt_prc_total').innerHTML = `<b>S/ ${prc_total.toFixed(2)}</b>`;
        };

        // increase decrease product amount
        let calcular_precio_x_prd = (id, prc, cntd) => {
            document.getElementById("multiplied_prc_"+id).innerHTML = "S/ " + (prc * cntd).toFixed(2);
        };

        let incr_cant = (id, prc, stock) => {
            let elmt_cntd = document.getElementById("txt_cntd_"+id);
            let cntd = parseInt(elmt_cntd.innerHTML);
            
            if(cntd == 1) document.getElementById("btn_decrs_"+id).disabled = false;

            cntd = cntd + 1;
            elmt_cntd.innerHTML = cntd;

            if(cntd == stock) document.getElementById("btn_incrs_"+id).disabled = true;

            calcular_precio_x_prd(id, prc, cntd);
            increment(id);
        }

        let decr_cant = (id, prc) => {
            let elmt_cntd = document.getElementById("txt_cntd_"+id);
            let cntd = parseInt(elmt_cntd.innerHTML);
            
            document.getElementById("btn_incrs_"+id).disabled = false;

            cntd = cntd - 1;
            elmt_cntd.innerHTML = cntd;

            if(cntd == 1) document.getElementById("btn_decrs_"+id).disabled = true;

            calcular_precio_x_prd(id, prc, cntd);
            decrement(id);
        }

        let quitarProd = (e, id) => {
            e = e || window.event;
            const target = e.target || e.srcElement;
            // let fila = $(this).closest('tr');
            const row_elmt = target.parentElement.parentElement;
            row_elmt.closest("tr").remove();
            
            document.getElementById("btnAdd-"+id).disabled = false;

            remove(id);

            if( cartTable.tBodies[0].rows.length == 1){
                $('#msg-empty-cart').show();
            }
        }

        let registro_venta = () => {
            if(carrito.length == 0){
                Swal.fire('Seleccione productos', 'No ha seleccionado al menos un producto que vender.', 'error');
            } else if(idClt == null || emailClt == null) {
                Swal.fire('Seleccione un cliente', 'No ha seleccionado un cliente al que vender.', 'error');
            } else {
                const pdd_descripcion = null;

                const pedido_data = {
                    id_tipo_pedido: 2,  //2 = Presencial
                    tipo_comp: "Boleto",
                    id_user: idClt,
                    total: prc_total,
                    pdd_fecha_entrega: "{{Carbon\Carbon::now('-05:00')}}",
                    pdd_descripcion: pdd_descripcion,
                    pdd_estado: 3, //3=pagado y entregado, al ser presencial
                    productos: carrito,
                    userMail: emailClt,
                    id_empleado: {{\Auth::user()->id}}
                };
                
                let loadingSwal = Swal.fire({
                    title: 'Registrando venta',
                    confirmButtonText: 'Look up',
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    })

                //api pedido, store
                const url = urlDominio+'api/pedido';

                fetch( url, {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(pedido_data)
                } )
                .then( response => response.json() )
                .then( success => {
                    console.log(success);
                    loadingSwal.close();
                    Swal.fire({
                        title: 'Venta realizada',
                        type: 'success',
                    }).then( (result) => {
                        console.log(result);
                        /* if (result.value == true) {
                            location.href = '/pedidos';
                        }else{
                            console.log('cancelado');
                        } */
                        location.href = '/pedidos';
                    })
                } )
                .catch( error => {
                    console.log(error);
                    Swal.fire('Error de servidor', 'Hubo un fallo al momento de registrar, por favor intente más tarde.', 'error');
                } );
            }
        };

    </script>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)
