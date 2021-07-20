<?php
include_once "config.php";
include_once "entidades/cliente.php";
include_once "entidades/producto.php";
include_once "entidades/venta.php";

$pg = "Edicion de venta";


$producto = new Producto();
$aProductos = $producto->obtenerTodos();

$cliente = new Cliente();
$aClientes = $cliente->obtenerTodos();

$venta = new Venta();
$venta->cargarFormulario($_REQUEST);

if ($_POST) {

    if (isset($_POST["btnGuardar"])) {
        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            //Actualizo un cliente existente
            $venta->actualizar();
        } else {
            //Es nuevo
            $venta->insertar();
        }
    } else if (isset($_POST["btnBorrar"])) {
        $venta->eliminar();
        header("Location: venta-formulario.php");
    }
}

if (isset($_GET["id"]) && $_GET["id"] > 0) {
    $venta->obtenerPorId();
}

if (isset($_GET["do"]) && $_GET["do"] == "buscarProducto"){
    $producto = new Producto();
    $producto->idproducto = $_GET["id"];
    $producto->obtenerPorId();

    $precio = $producto->precio;
    $array["precio"] = $precio;

    echo json_encode($array);
    exit;
}



include_once("header.php");
?>



<body>

    <div class="container-fluid">
        <h1 class="h3 text-gray-800 ms-3">Venta</h1>
        <div class="row">

            <div class="col-12 mt-3">

                <a href="ventas.php" class="btn btn-primary mr-2">Listado</a>
                <a href="venta-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
                <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
                <button type="submit" class="btn btn-danger mr-2" id="btnBorrar" name="btnBorrar">Borrar</button>

            </div>
        </div>


        <div class="row">

            <div class="col-12 form-group mt-3">
                <?php if (isset($msg) && $msg != "") : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $msg; ?>
                    </div>
                <?php endif; ?>
                <label for="txtFechaNac" class="d-block">Fecha y hora:</label>
                <select class="form-control d-inline" name="txtDia" id="txtDia" style="width: 80px">
                    <option selected="" disabled="">DD</option>
                    <?php for ($i = 1; $i <= 31; $i++) : ?>
                        <?php if ($venta->fecha != "" && $i == date_format(date_create($venta->fecha), "d")) : ?>
                            <option selected><?php echo $i; ?></option>
                        <?php else : ?>
                            <option><?php echo $i; ?></option>
                        <?php endif; ?>
                    <?php endfor; ?>
                </select>
                <select class="form-control d-inline" name="txtMes" id="txtMes" style="width: 80px">
                    <?php for ($i = 1; $i <= 12; $i++) : ?>
                        <?php if ($venta->fecha != "" && $i == date_format(date_create($venta->fecha), "m")) : ?>
                            <option selected><?php echo $i; ?></option>
                        <?php else : ?>
                            <option><?php echo $i; ?></option>
                        <?php endif; ?>
                    <?php endfor; ?>
                </select>
                <select class="form-control d-inline" name="txtAnio" id="txtAnio" style="width: 100px">
                    <option selected="" disabled="">YYYY</option>
                    <?php for ($i = 1900; $i <= date("Y"); $i++) : ?>
                        <?php if ($venta->fecha != "" && $i == date_format(date_create($venta->fecha), "Y")) : ?>
                            <option selected><?php echo $i; ?></option>
                        <?php else : ?>
                            <option><?php echo $i; ?></option>
                        <?php endif; ?>
                    <?php endfor; ?> ?>
                </select>
                <?php if ($venta->fecha == "") : ?>
                    <input type="time" required="" class="form-control d-inline" style="width: 120px" name="txtHora" id="txtHora" value="00:00">
                <?php else : ?>
                    <input type="time" required="" class="form-control d-inline" style="width: 120px" name="txtHora" id="txtHora" value="<?php echo date_format(date_create($venta->fecha), "H:i"); ?>">
                <?php endif; ?>
            </div>

            <div class="col-6 mt-1 form-group">
                <label for="lstCliente">Cliente:</label>
                <select name="lstCliente" id="lstCliente" class="form-control selectpicker" data-live-search="true">
                    <option value="" disabled selected>Seleccionar</option>
                    <?php foreach ($aClientes as $cliente) : ?>
                        <?php if ($cliente->idcliente == $venta->fk_idcliente) : ?>
                            <option selected value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre; ?></option>
                        <?php else : ?>
                            <option value="<?php echo $cliente->idcliente; ?>"><?php echo $cliente->nombre; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 mt-1 form-group">
                <label for="lstProducto" class="">Producto:</label>
                <select name="lstProducto" id="lstProducto" class="form-control selectpicker" data-live-search="true">
                    <option value="" disabled selected>Seleccionar</option>
                    <?php foreach ($aProductos as $producto) : ?>
                        <?php if ($producto->idproducto == $venta->fk_idproducto) : ?>
                            <option selected value="<?php echo $producto->idproducto; ?>"><?php echo $producto->nombre; ?></option>
                        <?php else : ?>
                            <option value="<?php echo $producto->idproducto; ?>"><?php echo $producto->nombre; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 form-group ">
                <label for="txtPrecioUni">Precio Unitario:</label>
                <input type="text" class="form-control" id="txtPrecioUniCurrency" value="$"disabled>
                <input type="hidden" class="form-control" name="txtPrecioUni" id="txtPrecioUni" value="<?php echo $venta->preciounitario; ?>">
            </div>
            <div class="col-6 form-group">
                <label for="txtCantidad">Cantidad:</label>
                <input type="text" class="form-control" name="txtCantidad" id="txtCantidad" value="<?php echo $venta->cantidad; ?>" >
                <span id="msgStock" class="text-danger" style="display:none;">No hay stock suficiente</span>
            </div>

            <div class="col-6 form-group">
                <label for="txtTotal">Total:</label>
                <input type="text" class="form-control" name="txtTotal" id="txtTotal" value="<?php echo $venta->total; ?>">
            </div>

                    
            
        </div>
    </div>
    <script>
        window.onload = function() {
            $("#txtCantidad").change(function() {

                let total = $("#txtCantidad").val() * $("#txtPrecioUni").val();
                $("#txtTotal").val(total);
            });

            $("#lstProducto").change(function() {
                idProducto = $("#lstProducto option:selected").val();
                $.ajax({
                    type: "GET",
                    url: "venta-formulario.php?do=buscarProducto",
                    data: {
                        id: idProducto
                    },
                    async: true,
                    dataType: "json",
                    success: function(respuesta) {
                        strResultado = Intl.NumberFormat("es-AR", {
                            style: 'currency',
                            currency: 'ARS'
                        }).format(respuesta.precio);
                        $("#txtPrecioUniCurrency").val(strResultado);
                        $("#txtPrecioUni").val(respuesta.precio);
                    }
                });
            });
            
        };
    </script>
    <?php include_once("footer.php"); ?>