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
    <div class="container"> <br> <br>
        <div class="card margen-card"> 
            <div class="card-body"> 
                <table class="table"> 
                    <thead>
                        <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Presentación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "SELECT * FROM Medicamento";
                            $ret = pg_query($db1, $sql);
                            if(!$ret) {
                                echo pg_last_error($db1);
                                exit;
                            }
                            while($row = pg_fetch_row($ret)) {
                                echo '<tr>
                                <th scope="row">'.$row[1].'</th>
                                <td>'.$row[2].'</td>
                                <td>'.$row[3].'</td>
                                </tr>' ;
                            } 
                            $sql = "SELECT * FROM Medicamento";
                            $ret = pg_query($db2, $sql);
                            if(!$ret) {
                                echo pg_last_error($db2);
                                exit;
                            }
                            while($row = pg_fetch_row($ret)) {
                                echo '<tr>
                                <th scope="row">'.$row[1].'</th>
                                <td>'.$row[2].'</td>
                                <td>'.$row[3].'</td>
                                </tr>' ;
                            }
                            $sql = "SELECT * FROM Medicamento";
                            $ret = pg_query($db3, $sql);
                            if(!$ret) {
                                echo pg_last_error($db3);
                                exit;
                            }
                            while($row = pg_fetch_row($ret)) {
                                echo '<tr>
                                <th scope="row">'.$row[1].'</th>
                                <td>'.$row[2].'</td>
                                <td>'.$row[3].'</td>
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