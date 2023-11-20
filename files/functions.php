<?php

function connectionBBDD($cadena, $user = 0, $password = 0) {
try {
$bd = new PDO($cadena, $user, $password);
} catch (Exception $ex) {
'La conexión no se pudo realizar correctamente, intentlo más tarde';
}
return $bd;
}

function selectValues($bd, $sql) {
//     and dni_propietario in (SELECT dni from personas where nombre = "Juan")
$values = $bd->query($sql);
//realizamos ese código apra solo coger el array asociativo de la consulta
$values->setFetchMode(PDO::FETCH_ASSOC);
return $values;
}

function makeStatement($table, $values) {
//guardamos el dni para luego usarlo como condicion del where
$dni = htmlspecialchars($values['dni']);
//guardamos en un array nuevo solo los valores que vamos a necesitar
$newValues = array_slice($values, 0, 4);
//inicializamos la varible sql para ir generando la consulta acumulando los string
$sql = "UPDATE $table SET ";
foreach ($newValues as $key => $value) {
$value = htmlspecialchars($value);
//Condicinal para saber si el valor es numerico o no
if (is_numeric($value)) {
$sql .= "$key = $value, ";
} else {
$sql .= "$key = '$value', ";
}
}
//SE TIENE QUE GENERAR UNA ESPECIA DE ALGORITMO PARA NO GENERAR LA ULTIMA COMA. SE PUEDE SABER CUAL ES EL ULTIMO VALOR EN ESTE CASO LA DIRECCION, PERO PARA HACER LA FUNCION
//PARA LLAMARLO DESDE CUALQUIR SITIO TENEMOS QUE RECORTAR EL ARRYA Y QUEDARNOS SOLO CON LOS VALORES QUE VAYAMOS A USAR
$sql .= "WHERE  dni = '$dni'";
echo $sql;
}

