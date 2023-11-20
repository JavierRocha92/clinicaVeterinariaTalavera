
<?php
//iniciamos sesion y guardamos el nombre de susuario qeu tenemos en la sesion en una variable una vz filtrado
session_start();
//varible booleana para guardar si hay errores a la hora de acceder a la pagina
$error = false;
$rol = 1;
if (isset($_SESSION['user']) && isset($_SESSION['rol'])) {
    $user = htmlspecialchars($_POST['user']);
    $rol = htmlspecialchars($_POST['rol']);
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel="stylesheet" href="../assets/css/style.css">
        <title>CRUD</title>
    </head>
    <body>
       <?php
       if($rol === 0){
           require '../pages/crudRol0.php';
       }else{
           if($rol === 1){
               require '../pages/crudRol1.php';
           }
       }
       
       ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </body>
</html>