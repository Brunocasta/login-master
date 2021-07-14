<?php


include_once "config.php";
include_once "entidades/tipoproducto.php";
 
$pg = "Edición de producto";

$tipoProducto = new TipoProducto();
$tipoProducto->cargarFormulario($_REQUEST);


if ($_POST) {

    if (isset($_POST["btnGuardar"])) {
        if (isset($_GET["id"]) && $_GET["id"] > 0) {
            //Actualizo un cliente existente
            $tipoProducto->actualizar();
        } else {
            //Es nuevo
            $tipoProducto->insertar();
        }
        
    } else if (isset($_POST["btnBorrar"])) {
        $tipoProducto->eliminar();
        header("Location: tipoproducto-formulario.php");
    }
}
if (isset($_GET["id"]) && $_GET["id"] > 0){
    $tipoProducto->obtenerPorId();
}

include_once("header.php"); 
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Productos</title>


    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body>

    <div class="container-fluid">
        <h1 class="h3 text-gray-800 ms-3">Tipo de Productos</h1>
        <div class="row">

            <div class="col-12 mt-3">

                <a href="tipoproductos.php" class="btn btn-primary mr-2">Listado</a>
                <a href="tipoproducto-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
                <button type="submit" class="btn btn-success mr-2" id="btnGuardar" name="btnGuardar">Guardar</button>
                <button type="submit" class="btn btn-danger mr-2" id="btnBorrar" name="btnBorrar">Borrar</button>

            </div>
        </div>

        <div class="row">
            <div class="col-12 mt-3 form-group">
                <label for="txtNombre">Nombre:</label>
                <input type="text" required class="form-control" name="txtNombre" id="txtNombre" value="<?php echo $tipoProducto->nombre; ?>">
            </div>
           

        </div>
    </div>
    <footer>
        <?php include_once("footer.php"); ?>
    </footer>

</body>

</html>