<?php
require '../files/functions.php';
$user = 'Laura';
//declaramos una varible para poder controlar cuando un cliente esta bajo una accion del ususario
$matches = false;
$bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
//comprobamos si existe usuario desde ela rchivo requerido anterior se maneja esta posibilidad y se guarda en una variable
if (isset($user)) {
    //rezlimaos la consulta para sacar por pantalla los nombres de todos los usuarios de la veterinaria
    $sql = "select dni,nombre,apellido,telefono,direccion FROM personas";
    $clients = selectValues($bd, $sql);
}
//filtramos el formulario que nos viene por post
if (filter_input_array(INPUT_POST)) {
    if (isset($_POST['update'])) {
        $update = htmlspecialchars($_POST['update']);
    }
    if (isset($_POST['delete'])) {
        $delete = htmlspecialchars($_POST['delete']);
    }
    if (isset($_POST['commit'])) {
        $commit = htmlspecialchars($_POST['commit']);
        //contruimos una sentencia para modificar los datos del registro de donde se haga refernecia en la variable commmit, para ellos llamamos a una metodo que nos filtra los
        //resultado y nos constuye una sentencia 
        $sql = makeStatement('personas', $_POST);
    }
}
//Cerramos la conexión con la base de datos 
$bd = null;
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" href="../css/styles.css">
        <title>Clinica Veterinaria Talavera</title>
    </head>
    <body>
        <header class= "info">
            <h1 class="title">Aplicación creada por:</h1>
            <div class="personal_data">
                <p>Nombre: Juan Ferrón Paterna</p>
                <p>Nombre: Javier Rocha </p>
                <p>Curso: 2º DAW</p>
                <p>Módulo: Desarrollo web en entorno servidor (DWES)</p>
            </div>
        </header>
        <div>
            <main class="main">
                <?php
                ?>
                <h2>Estas son tus mascotas <?= $user ?></h2>
                <table border="1">
                    <thead>
                        <tr>
                            <th class="td">Nombre</th>
                            <th class="td">Apellidos</th>
                            <th class="td">Teléfono</th>
                            <th class="td">Dirección</th>
                            <th class="td">Mascotas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //recorremos al arrya de los registros que de la consulta y creamos un tr para cada mascota
                        foreach ($clients as $client) {
                            //Creamos un formulario para guardar los inputs qeu generamos para cada cliente
                            ?>
                        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                            <tr>
                                <?php
                                //recorremos el array para cada registro en este caso para cada mascota

                                foreach ($client as $key => $value) {
                                    //controlamos con este  if que el dni del cliente no se muestre por pantalla
                                    if($key != 'dni'){
                                        
                                    
                                    //en cada pasada del bucle controlamos que la varibale de coinicidencia $matches este a false cada vez que se itera un cliente
                                    $matches = false;
                                    //creamos un td para imprimir cada uno de los valores de las columnas, dentro metremos un input para hacer el campo editable
                                    //Controlamos si existe la consulta si a devulto algo para poder controlar los errores,
                                    if (isset($update)) {
                                        //HAY QUE ARREGLAR ESTO PORQUE TENEMOS QUE HACER EN UN MISMO IF SI EXISTE EL UPDATE Y SI COINCIDE EL NOMBRE DE ESTE PARA NO GENERAR 
                                        //LOS INPUT DISABLES DOS VECES, QUIZAS CON UNA TERNARIO
                                        if ($update === $client['nombre']) {
                                            //le damos a la varibale matches el valor true para mas abajo generar un input confirmas cambios en lugar de modificar
                                            $matches = true;
                                            ?>

                                            <td class="td">
                                                <input name="<?= $key ?>" value="<?= $value ?>">
                                            </td>

                                            <?php
                                        } else {
                                            //este condicional nos va a controlar que los input se generen disabled para no poder modificarlo
                                            ?>

                                            <td class="td">
                                                <input name="<?= $key ?>" disabled value="<?= $value ?>">
                                            </td>

                                            <?php
                                        }
                                    } else {
//                                                  echo 'algo va mal porque existe el update pero no coincide el nombre del cliente';
                                        //este condicional nos va a controlar que los input se generen disabled para no poder modificarlo
                                        ?>

                                        <td class="td">
                                            <input name="name_cliente" disabled value="<?= $value ?>">
                                        </td>

                                        <?php
                                    }
                                }
                                }
                                //Realizamos una conexión a la base de datos para obtener el objeto con el que realizaremos la consulta
                                $bd = connectionBBDD('mysql:dbname=exposicion;host=127.0.0.1', 'root', '');
                                //generamos una consulta para las masscotas de cada cliente
                                //guardo en una variable el nombre del cliente sobre el que se genera la consulta
                                $client_name = $client['nombre'];
                                $mascotas = selectValues($bd, "SELECT nombre FROM mascotas WHERE dni_propietario in (SELECT dni FROM personas WHERE nombre = '$client_name')");
                                //Cerramos la conexión con la base de datos
                                $bd = null;
                                if (isset($mascotas)) {
                                    //recoremos el array con los registros de las mascotas de cada cliente
                                    //creamos un select para guardad todas los nombres de las mascotas en options
                                    ?>
                                    <td class="td">
                                        <select class="select" name="puppies">
                                            <?php
                                            foreach ($mascotas as $mascota) {
                                                //para cada creamos una etiqueta option y la rellenamos con el nombre de la mascota del cliente
                                                ?>
                                                <option class="option" name="puppy" value="<?= $mascota['nombre'] ?>"><?= $mascota['nombre'] ?></option>
                                                <?php
                                            }
                                            //cerramos la etiqueta select
                                            ?>
                                        </select>
                                    </td>
                                    <?php
                                    //Creamos dos etiquetas <td> para guardar lso botones de editar o borrar y dentro meteremos un formulario
                                    ?>
                                    <td>

                                        <?php
                                        //condicinal para controlar que input se va a generar dependiendo de si se ha encontrado el cliente a modificar
                                        if ($matches) {
                                            ?>
                                        <input name="dni" hidden value="<?= $client['dni'] ?>">
                                            <button type="submit" name="commit" value="<?= $client['nombre'] ?>">Confirmar</button>

                                            <?php
                                        } else {
                                            ?>
                                            <button type="submit" name="update" value="<?= $client['nombre'] ?>">Modificar</button>
                                            <?php
                                        }
                                        ?>
                                        <button type="submit" name="delete" value="<?= $client['nombre'] ?>">Eliminar</button>
                            </form>
                            </td>
                            <?php
                        }
                        ?>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <!--final de la tabla de informacion de las cosnultas--> 
                <?php ?>

            </main>
            <footer class="footer">

            </footer>
        </div>                       
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </body>
</html>

