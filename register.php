<?php

require_once "./config.php";

$username_err = $email_err = $password_err = "";
$username = $email = $password = "";

# Procesamiento de datos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
  # Validate username
  if (empty(trim($_POST["username"]))) {
    $username_err = "Please enter a username.";
  } else {
    $username = trim($_POST["username"]);
    if (!ctype_alnum(str_replace(array("@", "-", "_"), "", $username))) {
      $username_err = "El usuario solo puede contener letras, números y símbolos como '@', '_', or '-'.";
    } else {
      $sql = "SELECT id FROM users WHERE username = ?";

      if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        $param_username = $username;
        if (mysqli_stmt_execute($stmt)) {
          mysqli_stmt_store_result($stmt);

          # Comprobar si el nombre de usuario ya está registrado
          if (mysqli_stmt_num_rows($stmt) == 1) {
            $username_err = "Usuario ya registrado.";
          }
        } else {
          echo "<script>" . "alert('Algo salió mal. Por favor, inténtelo de nuevo más tarde.')" . "</script>";
        }

        mysqli_stmt_close($stmt);
      }
    }
  }

  # Validacion de correo electrónico 
  if (empty(trim($_POST["email"]))) {
    $email_err = "Porfavor ingresa un correo electrónico";
  } else {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $email_err = "Ingresa un correo valido";
    } else {
    
      $sql = "SELECT id FROM users WHERE email = ?";

      if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $param_email);

        $param_email = $email;

        if (mysqli_stmt_execute($stmt)) {
          mysqli_stmt_store_result($stmt);

          # Comprobar si el correo electrónico ya está registrado
          if (mysqli_stmt_num_rows($stmt) == 1) {
            $email_err = "Correo electrónico ya registrado";
          }
        } else {
          echo "<script>" . "alert('Oops! Something went wrong. Please try again later.');" . "</script>";
        }

     
        mysqli_stmt_close($stmt);
      }
    }
  }

  # Validar contraseña
  if (empty(trim($_POST["password"]))) {
    $password_err = "Ingresa una contraseña";
  } else {
    $password = trim($_POST["password"]);
    if (strlen($password) < 6) {
      $password_err = "La contraseña debe tener almenos 6 caracteres";
    }
  }

  if (empty($username_err) && empty($email_err) && empty($password_err)) {
    $sql = "INSERT INTO users(username, email, password) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_email, $param_password);

      $param_username = $username;
      $param_email = $email;
      $param_password = password_hash($password, PASSWORD_DEFAULT);

      if (mysqli_stmt_execute($stmt)) {
        echo "<script>" . "alert('Registro completado con éxito. Inicie sesión para continuar.');" . "</script>";
        echo "<script>" . "window.location.href='./login.php';" . "</script>";
        exit;
      } else {
        echo "<script>" . "alert('Algo salió mal. Por favor, inténtelo de nuevo más tarde.');" . "</script>";
      }

      mysqli_stmt_close($stmt);
    }
  }

  mysqli_close($link);
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
  <script defer src="./js/script.js"></script>
</head>

<body  class="bg-secondary ">
  <div class="container">
    <div class="row min-vh-100 justify-content-center align-items-center">
      <div class="col-lg-5">
        <div class="form-wrap border rounded p-4 bg-light">
          <h1>Registrarse</h1>
          <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
            <div class="mb-3">
              <label for="username" class="form-label">Usuario</label>
              <input type="text" class="form-control" name="username" id="username" value="<?= $username; ?>">
              <small class="text-danger"><?= $username_err; ?></small>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Correo electrónico</label>
              <input type="email" class="form-control" name="email" id="email" value="<?= $email; ?>">
              <small class="text-danger"><?= $email_err; ?></small>
            </div>
            <div class="mb-2">
              <label for="password" class="form-label">Contraseña</label>
              <input type="password" class="form-control" name="password" id="password" value="<?= $password; ?>">
              <small class="text-danger"><?= $password_err; ?></small>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="togglePassword">
              <label for="togglePassword" class="form-check-label">Mostrar contraseña</label>
            </div>
            <div class="mb-3">
              <input type="submit" class="btn btn-primary form-control" name="submit" value="Registrar">
            </div>
            <p class="mb-0">Te encuentras registrado ? <a href="./login.php">Ingresar</a></p>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>