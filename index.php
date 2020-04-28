<?php
  $host        = "host = localhost";
  $port        = "port = 5432";
  $credentials = "user = postgres password=1215";
  $dbname1     = "dbname = Farmacia1";
  $dbname2     = "dbname = Farmacia2";
  $dbname3     = "dbname = Farmacia3";

  $db1 = pg_connect( "$host $port $dbname1 $credentials"  );
  if(!$db1) {
    echo '
      <div class="alert alert-danger" role="alert">
          No se pudo conectar a la base de datos!
      </div>';
  }

  $db2 = pg_connect( "$host $port $dbname2 $credentials"  );
  if(!$db2) {
    echo '
      <div class="alert alert-danger" role="alert">
          No se pudo conectar a la base de datos!
      </div>';
  }

  $db3 = pg_connect( "$host $port $dbname3 $credentials"  );
  if(!$db3) {
    echo '
      <div class="alert alert-danger" role="alert">
          No se pudo conectar a la base de datos!
      </div>';
    
  } 

  $filtroPrecio        = false;
  $filtroRegion        = false;
  $filtroComuna        = false;
  $filtroPresentacion  = false;

  $precio              = '';
  $region              = '';
  $comuna              = '';
  $presentacion        = '';
   
  if(isset($_GET["precio"]) && $_GET["precio"]!=''){
    $filtroPrecio=true;
    $precio=$_GET["precio"];
  } 
  if(isset($_GET["region"]) && $_GET["region"]!=''){
    $filtroRegion=true;
    $region=$_GET["region"];
  } 
  if(isset($_GET["comuna"]) && $_GET["comuna"]!=''){
    $filtroComuna=true;
    $comuna=$_GET["comuna"];
  }
  if(isset($_GET["presentacion"]) && $_GET["presentacion"]!=''){
    $filtroPresentacion=true;
    $presentacion=$_GET["presentacion"];
  }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Inventario Medicamentos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="app.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/79cdef1336.js"></script>
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <span class="navbar-brand mb-0 h1"></i>Inventario de Medicamentos</span>
    </nav>

    <br>
    <form action="/index.php">
        <div class="container">
            <h5 class="card-title">Filtrar por: </h5>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Precio Medicamento</span>
                </div>
                <input value="<?php echo $precio;?>" type="text" name="precio" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Presentación Medicamento</span>
                </div>
                <input value="<?php echo $presentacion;?>" type="text" name="presentacion" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Región</span>
                </div>
                <input value="<?php echo $region;?>" type="text" name="region" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>

            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-default">Comuna</span>
                </div>
                <input value="<?php echo $comuna;?>" type="text" name="comuna" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
            </div>

        <button type="submit" class="btn btn-warning">Aplicar filtro</button>
        </div>
    </form> 

    <div class="container"> <br> <br>
        <div class="card margen-card"> 
            <div class="card-body"> 
                <table class="table"> 
                    <thead>
                        <tr>
                        <th scope="col">Nombre Medicamento</th>
                        <th scope="col">Precio Medicamento</th>
                        <th scope="col">Presentación Medicamento</th>
                        <th scope="col">Franquicia</th>
                        <th scope="col">Dirección</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Región</th>
                        <th scope="col">Cuidad</th>
                        <th scope="col">Comuna</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $textFiltro = "SELECT * FROM Sucursal,Medicamento,MedicamentoSucursal WHERE MedicamentoSucursal.RefMedicamento=Medicamento.ID and MedicamentoSucursal.RefSucursal=Sucursal.ID";
                            if($filtroPrecio){
                              $textFiltro .= " AND Medicamento.precio=$precio";
                            }
                            if($filtroPresentacion){
                              $textFiltro .= " AND Medicamento.presentacion ILIKE '$presentacion'";
                            }
                            if($filtroComuna){
                              $textFiltro .= " AND Sucursal.comuna ILIKE '$comuna'";
                            }
                            if($filtroRegion){
                              $textFiltro .= " AND Sucursal.region ILIKE '$region'";
                            }

                            $sql = "$textFiltro";

                            $ret = pg_query($db1, $sql);
                            if(!$ret) {
                                echo pg_last_error($db1);
                                exit;
                            }
                            while($row = pg_fetch_row($ret)) {
                                echo '<tr>
                                <th scope="row">'.$row[8].'</th>
                                <td>'.$row[9].'</td>
                                <td>'.$row[10].'</td>
                                <td>'.$row[1].'</td>
                                <td>'.$row[2].'</td>
                                <td>'.$row[3].'</td>
                                <td>'.$row[4].'</td>
                                <td>'.$row[5].'</td>
                                <td>'.$row[6].'</td>
                                </tr>' ;
                            }

                            $ret = pg_query($db2, $sql);
                            if(!$ret) {
                                echo pg_last_error($db2);
                                exit;
                            }
                            while($row = pg_fetch_row($ret)) {
                                echo '<tr>
                                <th scope="row">'.$row[8].'</th>
                                <td>'.$row[9].'</td>
                                <td>'.$row[10].'</td>
                                <td>'.$row[1].'</td>
                                <td>'.$row[2].'</td>
                                <td>'.$row[3].'</td>
                                <td>'.$row[4].'</td>
                                <td>'.$row[5].'</td>
                                <td>'.$row[6].'</td>
                                </tr>' ;
                            }

                            $ret = pg_query($db3, $sql);
                            if(!$ret) {
                                echo pg_last_error($db3);
                                exit;
                            }
                            while($row = pg_fetch_row($ret)) {
                                echo '<tr>
                                <th scope="row">'.$row[8].'</th>
                                <td>'.$row[9].'</td>
                                <td>'.$row[10].'</td>
                                <td>'.$row[1].'</td>
                                <td>'.$row[2].'</td>
                                <td>'.$row[3].'</td>
                                <td>'.$row[4].'</td>
                                <td>'.$row[5].'</td>
                                <td>'.$row[6].'</td>
                                </tr>' ;
                            } 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>