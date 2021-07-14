<?php 

include_once "config.php";
include_once "entidades/venta.php";

$pg = "Listado de ventas";

$venta = new Venta();
$aVentas= $venta->cargarGrilla();


include_once("header.php"); 

?>




    <div class="container-fluid">

        <h1 class="h3 text-gray-800 ms-3">Listado de Ventas</h1>
        <?php include_once("menu.php"); ?>

        <div class="row">
            <div class="col-12">

                <a href="venta-formulario.php" class="btn btn-primary mr-2 mt-3">Nuevo</a>
            </div>
        </div>

        <table class="table table-hover border mt-4">
            <tr>
                <th>Fecha</th>
                <th>Cantidad</th>
                <th>Producto</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Acciones</th>

            </tr>
           
            <?php foreach ($aVentas as $venta): ?>
            
            <tr>
                <td><?php echo date_format(date_create($venta->fecha), "d/m/Y H:m") ; ?></td>
                <td><?php echo $venta->cantidad; ?></td>
                <td><?php echo $venta->nombre_producto;?></td>
                <td><?php echo $venta->nombre_cliente; ?></td>
                <td><?php echo "$".number_format($venta->total, 2, ",", "."); ?></td>
                <td style="width: 110px;">
                      <a href="venta-formulario.php?id=<?php echo $venta->idventa; ?>"><i class="fas fa-search"></i></a>   
                  </td>
                
                 </tr>
                 <?php endforeach;?>
        </table>

    </div>

 <?php include_once("footer.php"); ?>

