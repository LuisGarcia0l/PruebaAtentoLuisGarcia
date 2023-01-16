<?php
define("DB_SERVER", "localhost");
define("DB_USERNAME", "id20155629_root");
define("DB_PASSWORD", "3d4u3dktd<$_QT/V");
define("DB_NAME", "id20155629_atento");

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (!$link) {
  die("La conexion fallo: " . mysqli_connect_error());
}
