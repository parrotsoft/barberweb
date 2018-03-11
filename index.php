<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <title>Barberia v 1.0</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">


    <style>
    table {
        border-collapse: collapse;
    }
    </style>
</head>
<body>
<div class="container">

<center><h3>Consulta Ventas</h3></center>
    <div class="row">
        <div class="col-xs-12">
            <form method="POST" action="#">
                <input class="form-control datepicker" name="fecha1" type="text" placeholder="Fecha Inicial">
                <input style="margin-top:0.2em" class="form-control datepicker" name="fecha2" type="text" placeholder="Fecha Final">
                <div class="row" style="margin-top:0.2em">
                    <div class="col-xs-12">
                        <center><button class="btn btn-primary"  type="submit">Consultar</button></center>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        $( function() {
            $( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
        });
    </script>
  

<?php 

        if($_POST){
            $mysqli = new mysqli("localhost", "root", "Mla1043605421", "barberia");
    if ($mysqli->connect_errno) {
        echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    
    $result = $mysqli->query("select 
    v.id,
    b.nombres_apellidos as barbero,
    c.nombres_apellidos as cliente,
    v.total,
    v.porcentaje,
    V.fecha 
    from ventas v 
    inner join barberos b on v.barbero_id = b.id
    inner join clientes c on v.cliente_id = c.id
    where date(v.fecha) BETWEEN ('".$_POST['fecha1']."') AND ('".$_POST['fecha2']."')
    ORDER by v.fecha DEsC");

    echo "<center><h4> <span class='label label-success'>Inicial: ".$_POST['fecha1']." - Final: ".$_POST['fecha2']."</span></h4></center>";

    echo "<table class='table table-bordered'><tr><td>Barbero</td><td>Servicio</td><td>Cliente</td><td>%</td><td>Total</td></tr>";

    $total = 0;
    $utilidadTotal = 0;
    while($row=mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>".$row['barbero']."</td>";
        echo "<td>".getServicio($row['id'],$mysqli)."</td>";
        echo "<td>".$row['cliente']."</td>";
        echo "<td>".$row['porcentaje']."%</td>";
        echo "<td>".number_format($row['total'],2)."</td>";
        echo "</tr>";

        $total += $row['total'];
        $utilidadTotal += $row['total'] *  ($row['porcentaje']/100);
    }
    echo "</table>";

    echo "<h4><span class='label label-default'>Vental Total</span> : <span class='label label-primary'>$".number_format($total,2)."</span></h4>";
    echo "<h4><span class='label label-default'>Utilidad Total</span> : <span class='label label-warning'>$".number_format($utilidadTotal,2)."</span></h4>";

        }


        function getServicio($venta_id, $conexion) {
            $result = $conexion->query("select s.servicio from detalles_ventas  d inner JOIN servicios s on d.servicio_id = s.id where d.venta_id = ".$venta_id."");
            $row=mysqli_fetch_assoc($result);
            return $row['servicio'];
        }
    
    
?>

</div>
    
    </body>
</html>