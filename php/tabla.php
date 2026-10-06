<?php

//Realiazamos la conexion con la base de datos
$conexion = mysqli_connect("localhost", "root", "","cae") or die("Problemas con la conexion");

//Comprobamos si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    //Recogemos los datos enviados al formulario
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $dni = $_POST["dni"];
    $f_nac = $_POST["f_nac"];
    $tlf = $_POST["tlf"];
    $email = $_POST["email"];
    $profesion = $_POST["profesion"];
    $jornadaParcial = $_POST["jornadaParcial"];

    //Guardamos los idiomas seleccionados
    $idiomas = "";

    //Comprobamos si hemos seleccionado Euskera
    if (isset($_POST["euskera"])){
        $idiomas = "Euskera";
    }

    //Comprobamos si hemos seleccionado Ingles, en caso de tener Euskera ya seleccionado y seleccionamos Ingles nos muestra los dos idiomas
    if (isset($_POST["ingles"])){
        if($idiomas == ""){
            $idiomas = "Ingles";
        } else {
            $idiomas = $idiomas . ", Ingles";
        }
    }

    //Aqui comprobamos si no se ha seleccionado ningun idioma
    if ($idiomas == ""){
        $idiomas = "Ninguno";
    }

    //Insertamos los datos en la base de datos
    $sql = "INSERT INTO solicitud (nombre, apellidos, dni, f_nac, tlf, email, profesion, jornadaParcial, idiomas) 
            VALUES('$nombre', '$apellidos', '$dni', '$f_nac', '$tlf', '$email', '$profesion', '$jornadaParcial', '$idiomas')";
    
    mysqli_query($conexion, $sql);
}

?>

<html>
    <head>
        <title>Examen de Desarrollo web en entorno servidor</title>
        <link rel="icon" type="image/jpeg" sizes="32x32" href="../imagenes/favicon.jpeg">
        <link rel="stylesheet" type="text/css" href="../estilos/estilos.css">

    </head>
    <body>
        <h1>Centro de Ayuda al Empleo</h1>
        <h2>    
            <?php
                $tipo = "Soldadura";

                //He comprobado si existe mi variable $tipo
                if (isset($_REQUEST["tipo"])){
                    if ($_REQUEST['tipo'] == 'informatica'){
                        $tipo = 'Informática';
                    } elseif ($_REQUEST['tipo'] == 'socio'){
                        $tipo = "Asistencia Sociosanitaria";
                    }
                }
                echo "Solicitudes de $tipo";
            ?>
        </h2>

        
        <?php 
        // Aquí tenéis que crear la tabla de solicitantes de ese tipo

        //Comprobamos si hemos seleccionado una profesion
        if (isset($_REQUEST["tipo"])){

            //Guardamos la profesion que hemos seleccionado
            $tipoBusqueda = $_REQUEST["tipo"];

            //Buscamos las solicitudes de la profesion
            $sql = "SELECT * FROM solicitud WHERE profesion = '$tipoBusqueda'";

            //Ejecutamos la consulta
            $resultado = mysqli_query($conexion, $sql);

            //Creamos la tabla
            echo "<table border = '1'>";
            
            echo "<tr>";
            echo "<th>Nombre</th>";
            echo "<th>Apellidos</th>";
            echo "<th>Dni</th>";
            echo "<th>Fecha Nacimiento</th>";
            echo "<th>Telefono</th>";
            echo "<th>Email</th>";
            echo "<th>Jornada</th>";
            echo "<th>Idiomas</th>";
            echo "</tr>";

            //Recorremos los resultados de la consulta y mostramos los datos de cada solicitante
            while ($fila = mysqli_fetch_assoc($resultado)){
                echo "<tr>";

                echo "<td>" . $fila["nombre"] . "</td>";
                echo "<td>" . $fila["apellidos"] . "</td>";
                echo "<td>" . $fila["dni"] . "</td>";
                echo "<td>" . $fila["f_nac"] . "</td>";
                echo "<td>" . $fila["tlf"] . "</td>";
                echo "<td>" . $fila["email"] . "</td>";
                
                if ($fila["jornadaParcial"] == 1){
                    echo "<td>Parcial</td>";
                } else {
                    echo "<td>Completa</td>";
                }

                echo "<td>" . $fila["idiomas"] . "</td>";

                echo "</tr>";
            }

            echo "</table>";
        }

        mysqli_close($conexion);
        ?>
        <button onclick="location.href='../html/index.html'">Volver al formulario</button>
    </body>
</html>