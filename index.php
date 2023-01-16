<?php
session_start();

if (!isset($_SESSION["logeado "]) || $_SESSION["logeado "] !== TRUE) {
  echo "<script>" . "window.location.href='./login.php';" . "</script>";
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Prueba práctica</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link rel="stylesheet" href="./css/main.css">
</head>

<body>
  <div class="container">
    <div class="alert  my-5">
      Esta es una pagina con un simple login; Cuenta con conexion a base de datos con MySQL ,PHP y boostrap, asi como validaciones para usuarios ya registrados y Contraseña de 6 caracteres y correo electrónico    </div>
    <div class="row justify-content-center">
      <div class="col-lg-5 text-center">
        <h4 class="my-4">Hola, <?= htmlspecialchars($_SESSION["username"]); ?></h4>
        <a href="./logout.php" class="btn btn-primary">Salir</a>
      </div>
    </div>
  </div>
</body>

</html>