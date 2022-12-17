@extends('adminlte::page')

@section('title', 'Reporte de ventas')

@section('content_header')
    <h1>Tabla Reporte de ventas</h1>
@stop

@section('content')

    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body row">
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Filtro</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <label for="dateFrom">Desde:</label>
                                <input type="date" id="dateFrom" class="form-control" max="{{date('Y-m-d');}}" name="Desde">
                            
                                <label for="dateUntil">Hasta:</label>
                                <input type="date" id="dateUntil" class="form-control" max="{{date('Y-m-d');}}" name="Hasta">
                            </li>
                            <li class="list-group-item" style="display: none;">
                                <label for="selectProducto">Por producto:</label>
                                <select class="form-control" id="selectProducto">
                                    <option value="0">Todos</option>
                                    <!-- Se insertan la lista de productos mediante api -->
                                </select>
                            </li>
                            <li class="list-group-item">
                                <label for="selectTipoPedido">Por tipo de venta:</label>
                                <select class="form-control" id="selectTipoPedido">
                                    <option value="0">Todos</option>
                                    <!-- Se insertan la lista de tipos de pedidos mediante api -->
                                </select>
                            </li>
                            <li class="list-group-item">
                                <label for="selectCliente">Por cliente:</label>
                                <select class="form-control" id="selectCliente">
                                    <option value="0">Todos</option>
                                    <!-- Se insertan la lista de clientes mediante api -->
                                </select>
                            </li>
                        </ul>

                    </div>
                    <div class="card-footer">
                        <button id="btnActualizar" type="button" class="btn btn-secondary btn-actualizar">Actualizar</button>
                    </div>
                </div>
            </div>
            <div class="table-responsive col-sm-9">
                <table id="tabla_reporte_ventas" class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Comprobante</th>
                            <th scope="col">N° Comprobante</th>
                            <th scope="col">Tipo de Venta</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Clt apellido</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Total</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="lista_ventas">
                        <!-- lista de ventas mediante api -->
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7" class="tdFooterTotal">TOTAL</td>
                            <td id="tdFooterTotal">S/0.00</td>
                        </tr>
                    </tfoot>
                </table>
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
        let dateFValue = "{{Carbon\Carbon::now()->subYear()->format('Y-m-d')}}",
         dateUValue = "{{date('Y-m-d')}}",
         idPrdSelected = 0,
         idTipoPddSelected = 0,
         idCltSelected = 0;
        console.log("dateFValue: "+dateFValue);
        console.log("dateUValue: "+dateUValue);

        let dataTableReporteVentas = $('#tabla_reporte_ventas').DataTable({
            "ajax":{
                "url":urlDominio+'api/reporte_ventas/'+dateFValue+'/'+dateUValue+'/'+idPrdSelected+'/'+idTipoPddSelected+'/'+idCltSelected,
                "type": "GET",
                "dataSrc":""
            },
            "columns":[
                {
                    "data":"id_pedido",
                    visible: false
                },
                {
                    "data":"cmp_serie",
                    render: function (data, type, row, meta) {
                        return data + '-' + row['cmp_numero'];
                    },
                },
                {
                    "data":"cmp_numero",
                    visible: false
                },
                {"data":"tpe_nombre"},
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
                {"data":"pdd_fecha_entrega"},
                {
                    "data":"pdd_total",
                    render(v){
                        return 'S/ '+Number(v).toFixed(2)
                    }
                },
                {
                    defaultContent: `<button class="btn btn-xs btn-default text-primary mx-1 shadow btnVer" title="Ver">
                                        <i class="fas fa-eye"></i>
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
                            columns: [1,3,4,6,7]
                        },
                        footer: true
                    },
                    {
                        extend: 'excel',
                        text:'<i class="fas fa-file-excel"></i>',
                        titleAttr:'Formato Excel',
                        className: 'excelButton',
                        exportOptions: {
                            columns: [1,3,4,6,7]
                        },
                        footer: true
                    },
                    {
                        extend: 'csv',
                        text:'<i class="fas fa-file-csv"></i>',
                        titleAttr:'Formato CSV',
                        className: 'csvButton',
                        exportOptions: {
                            columns: [1,3,4,6,7]
                        },
                        footer: true
                    },
                    {
                        extend: 'print',
                        text:'<i class="fas fa-print"></i>',
                        titleAttr:'Imprimir',
                        className: 'printButton',
                        exportOptions: {
                            columns: [1,3,4,6,7]
                        },
                        footer: true
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fas fa-file-pdf"></i>',
                        titleAttr:'Formato PDF',
                        className: 'pdfButton',
                        exportOptions: {
                            columns: [1,3,4,6,7]
                        },
                        footer: true,
                        orientation: 'portrait',
                        pageSize: 'LEGAL'
                    }
                ]
            },
            footerCallback: function (row, data, start, end, display) {
                let api = this.api();
                
                // Remove the formatting to get integer data for summation
                let getFloatValue = function (i) {
                    return parseFloat(i.substring(3));
                    // return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                };
    
                // Total over all pages
                total = api
                    .column(7)
                    .data()
                    .reduce(function (a, b) {
                        return parseFloat(a) + parseFloat(b);
                    }, 0);
    
                // Total over this page
                /* pageTotal = api
                    .column(4, { page: 'current' })
                    .data()
                    .reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0); */
    
                // Update footer
                $(api.column(7).footer()).html('S/ ' + total.toFixed(2));
            },
        });

        // filter logic
        let dateFrom = document.getElementById('dateFrom');
        let dateUntil = document.getElementById('dateUntil');
        let selectProducto = document.getElementById('selectProducto');
        let selectTipoPedido = document.getElementById('selectTipoPedido');
        let selectCliente = document.getElementById('selectCliente');
        let btnActualizar = document.getElementById('btnActualizar');
        let tdFooterTotal = document.getElementById('tdFooterTotal');

        function listar_productos() {
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
                res.forEach(producto => {
                    let option_elem = document.createElement('option');
                    option_elem.value = producto.id_producto;
                    option_elem.textContent = producto.prd_nombre;
                    selectProducto.appendChild(option_elem);
                });
            })
            .catch(error => console.log(error));
        };

        function listar_tipo_pedidos() {
            const url = urlDominio+'api/tipo_pedidos';

            //llamado al api tipo_pedidos, index
            fetch(url, {
                method: 'GET',
                headers: {
                    "Content-Type": "application/json",
                }
            })
            .then(res => res.json())
            .then(res => {
                res.forEach(tpedido => {
                    let option_elem = document.createElement('option');
                    option_elem.value = tpedido.id_tipo_pedido;
                    option_elem.textContent = tpedido.tpe_nombre;
                    selectTipoPedido.appendChild(option_elem);
                });
            })
            .catch(error => console.log(error));
        };

        function listar_clientes() {
            const url = urlDominio+'api/clientes';

            //llamado al api clientes, index
            fetch(url, {
                method: 'GET',
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer {{Auth::user()->createToken('my-token')->plainTextToken}}"
                }
            })
            .then(res => res.json())
            .then(res => {
                res.forEach(cliente => {
                    let option_elem = document.createElement('option');
                    option_elem.value = cliente.id;
                    option_elem.textContent = cliente.name + ' ' + cliente.usr_apellidos;
                    selectCliente.appendChild(option_elem);
                });
            })
            .catch(error => console.log(error));
        };

        listar_productos();
        listar_tipo_pedidos();
        listar_clientes();

        function updateTableUrl(){
            dataTableReporteVentas.ajax.url( urlDominio+'api/reporte_ventas/'+dateFValue+'/'+dateUValue+'/'+idPrdSelected+'/'+idTipoPddSelected+'/'+idCltSelected ).load();
        };

        dateFrom.onchange = function(){
            dateFValue = dateFrom.value;
            dateUntil.min = dateFValue;
            updateTableUrl();
        };
        dateUntil.onchange = function(){
            dateUValue = dateUntil.value;
            dateFrom.max = dateUValue;
            updateTableUrl();
        };
        selectProducto.onchange = function(){
            idPrdSelected = selectProducto.value;
            updateTableUrl();
        };
        selectTipoPedido.onchange = function(){
            idTipoPddSelected = selectTipoPedido.value;
            updateTableUrl();
        };
        selectCliente.onchange = function(){
            idCltSelected = selectCliente.value;
            updateTableUrl();
        };

        btnActualizar.addEventListener("click", function(){updateTableUrl();});
    </script>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)
@section('plugins.Sweetalert2', true)