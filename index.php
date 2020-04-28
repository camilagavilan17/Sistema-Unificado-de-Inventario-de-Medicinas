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
                <table id="myTable2" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%"> 
                    <thead>
                        <tr>
                        <th class="th-sm" onclick="sortTable2()">Precio Medicamento</th>
                        <th class="th-sm" onclick="sortTable(1)">Nombre Medicamento</th>
                        <th class="th-sm" onclick="sortTable(2)">Presentación Medicamento</th>
                        <th class="th-sm" onclick="sortTable(3)">Franquicia</th>
                        <th class="th-sm" onclick="sortTable(4)">Dirección</th>
                        <th class="th-sm">Teléfono</th>
                        <th class="th-sm" onclick="sortTable(6)">Región</th>
                        <th class="th-sm" onclick="sortTable(7)">Ciudad</th>
                        <th class="th-sm" onclick="sortTable(8)">Comuna</th>
                        <th class="th-sm"></th>
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
                                <td>'.$row[9].'</td>
                                <td>'.$row[8].'</td>
                                <td>'.$row[10].'</td>
                                <td>'.$row[1].'</td>
                                <td>'.$row[2].'</td>
                                <td>'.$row[3].'</td>
                                <td>'.$row[4].'</td>
                                <td>'.$row[5].'</td>
                                <td>'.$row[6].'</td>
                                <td>
                                <button onclick="AgregarLista()">Agregar a la lista</button>
                                </td>
                                </tr>' ;
                            }

                            $ret = pg_query($db2, $sql);
                            if(!$ret) {
                                echo pg_last_error($db2);
                                exit;
                            }
                            while($row = pg_fetch_row($ret)) {
                                echo '<tr>
                                <td>'.$row[9].'</td>
                                <td>'.$row[8].'</td>
                                <td>'.$row[10].'</td>
                                <td>'.$row[1].'</td>
                                <td>'.$row[2].'</td>
                                <td>'.$row[3].'</td>
                                <td>'.$row[4].'</td>
                                <td>'.$row[5].'</td>
                                <td>'.$row[6].'</td>
                                <td>
                                <button onclick="AgregarLista()">Agregar a la lista</button>
                                </td>
                                </tr>' ;
                            }

                            $ret = pg_query($db3, $sql);
                            if(!$ret) {
                                echo pg_last_error($db3);
                                exit;
                            }
                            while($row = pg_fetch_row($ret)) {
                                echo '<tr>
                                <td>'.$row[9].'</td>
                                <td>'.$row[8].'</td>
                                <td>'.$row[10].'</td>
                                <td>'.$row[1].'</td>
                                <td>'.$row[2].'</td>
                                <td>'.$row[3].'</td>
                                <td>'.$row[4].'</td>
                                <td>'.$row[5].'</td>
                                <td>'.$row[6].'</td>
                                <td>
                                <button onclick="AgregarLista()">Agregar a la lista</button>
                                </td>
                                </tr>' ;
                            } 
                        ?>
                    </tbody>
                </table>
              <script>
                  function sortTable(n) {
                    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
                    table = document.getElementById("myTable2");
                    switching = true;
                    // Set the sorting direction to ascending:
                    dir = "asc";
                    /* Make a loop that will continue until
                    no switching has been done: */
                    while (switching) {
                      // Start by saying: no switching is done:
                      switching = false;
                      rows = table.rows;
                      /* Loop through all table rows (except the
                      first, which contains table headers): */
                      for (i = 1; i < (rows.length - 1); i++) {
                        // Start by saying there should be no switching:
                        shouldSwitch = false;
                        /* Get the two elements you want to compare,
                        one from current row and one from the next: */
                        x = rows[i].getElementsByTagName("TD")[n];
                        y = rows[i + 1].getElementsByTagName("TD")[n];
                        /* Check if the two rows should switch place,
                        based on the direction, asc or desc: */
                        if (dir == "asc") {
                          if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                          }
                        } else if (dir == "desc") {
                          if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            // If so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                          }
                        }
                      }
                      if (shouldSwitch) {
                        /* If a switch has been marked, make the switch
                        and mark that a switch has been done: */
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                        // Each time a switch is done, increase this count by 1:
                        switchcount ++;
                      } else {
                        /* If no switching has been done AND the direction is "asc",
                        set the direction to "desc" and run the while loop again. */
                        if (switchcount == 0 && dir == "asc") {
                          dir = "desc";
                          switching = true;
                        }
                      }
                    }
                  }
                </script>
                <script>
                function sortTable2() {
                  var table, rows, switching, i, x, y, shouldSwitch;
                  table = document.getElementById("myTable2");
                  switching = true;
                  /*Make a loop that will continue until
                  no switching has been done:*/
                  while (switching) {
                    //start by saying: no switching is done:
                    switching = false;
                    rows = table.rows;
                    /*Loop through all table rows (except the
                    first, which contains table headers):*/
                    for (i = 1; i < (rows.length - 1); i++) {
                      //start by saying there should be no switching:
                      shouldSwitch = false;
                      /*Get the two elements you want to compare,
                      one from current row and one from the next:*/
                      x = rows[i].getElementsByTagName("TD")[0];
                      y = rows[i + 1].getElementsByTagName("TD")[0];
                      //check if the two rows should switch place:
                      if (Number(x.innerHTML) > Number(y.innerHTML)) {
                        //if so, mark as a switch and break the loop:
                        shouldSwitch = true;
                        break;
                      }
                    }
                    if (shouldSwitch) {
                      /*If a switch has been marked, make the switch
                      and mark that a switch has been done:*/
                      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                      switching = true;
                    }
                  }
                }
              </script>
              <script>
              function AgregarLista() {
                document.write(5 + 6);
              }
              </script> 
            </div>
        </div>
    </div>
</body>
</html>