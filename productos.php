<?php

include_once "config.php";
include_once "entidades/producto.php";
$pg = "Listado de Productos";

$producto = new Producto();
$aProductos= $producto->obtenerTodos();

$producto = new Producto();
$producto->cargarFormulario($_REQUEST);


 


include_once("header.php"); 
?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <h1 class="h3 mb-4 text-gray-800">Listado de Productos</h1>
          <div class="row">
                <div class="col-12 mb-3">
                    <a href="producto-formulario.php" class="btn btn-primary mr-2">Nuevo</a>
                </div>
            </div>
            <table class="table table-hover border">
            <tr>
                <th>Foto</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($aProductos as $produc): ?>
              <tr>
                  <td><img src="files/<?php echo $produc->imagen; ?>"class="img-thumbnail"></td>
                  <td><?php echo $produc->nombre; ?></td>
                  <td><?php echo $produc->cantidad; ?></td>
                  <td>$<?php echo number_format($produc->precio, 2,",","."); ?></td>
                  <td style="width: 110px;">
                      <a href="producto-formulario.php?id=<?php echo $produc->idproducto; ?>"><i class="fas fa-search"></i></a>   
                  </td>
            
              </tr>
            <?php endforeach; ?>
          </table>
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->
<?php include_once("footer.php"); ?>