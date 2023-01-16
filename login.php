<?php
session_start();

if (isset($_SESSION["logeado "]) && $_SESSION["logeado "] == TRUE) {
  echo "<script>" . "window.location.href='./'" . "</script>";
  exit;
}

require_once "./config.php";

$user_login_err = $user_password_err = $login_err = "";
$user_login = $user_password = "";

# Procesando los datos del formulario 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty(trim($_POST["user_login"]))) {
    $user_login_err = "Ingresa tu usuario o Correo electrónico";
  } else {
    $user_login = trim($_POST["user_login"]);
  }

  if (empty(trim($_POST["user_password"]))) {
    $user_password_err = "Ingresa tu contraseña";
  } else {
    $user_password = trim($_POST["user_password"]);
  }

  # Validar Acceso
  if (empty($user_login_err) && empty($user_password_err)) {
 
    $sql = "SELECT id, username, password FROM users WHERE username = ? OR email = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      mysqli_stmt_bind_param($stmt, "ss", $param_user_login, $param_user_login);
      $param_user_login = $user_login;

      if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
          mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);

          if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($user_password, $hashed_password)) {

              $_SESSION["id"] = $id;
              $_SESSION["username"] = $username;
              $_SESSION["logeado "] = TRUE;

              echo "<script>" . "window.location.href='./'" . "</script>";
              exit;
            } else {
              $login_err = "El correo electrónico o la contraseña son incorrectos";
            }
          }
        } else {
          $login_err = "Contraseña o usuario incorrecto ";
        }
      } else {
        echo "<script>" . "alert('Algo salió mal. Por favor, inténtelo de nuevo más tarde');" . "</script>";
        echo "<script>" . "window.location.href='./login.php'" . "</script>";
        exit;
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

<body class="bg-secondary ">
  <div class="container ">
    <div class="row min-vh-100 justify-content-center align-items-center">
      <div class="col-lg-5">
        <?php
        if (!empty($login_err)) {
          echo "<div class='alert alert-danger'>" . $login_err . "</div>";
        }
        ?>
        <div class="form-wrap border rounded p-4 bg-light">
          <h1>Ingresar</h1>
          <p>Completa tu informacion para ingresar</p>
          <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
            <div class="mb-3">
              <label for="user_login" class="form-label">Correo o Usuario</label>
              <input type="text" class="form-control" name="user_login" id="user_login" value="<?= $user_login; ?>">
              <small class="text-danger"><?= $user_login_err; ?></small>
            </div>
            <div class="mb-2">
              <label for="password" class="form-label">Contraseña</label>
              <input type="password" class="form-control" name="user_password" id="password">
              <small class="text-danger"><?= $user_password_err; ?></small>
            </div>
            <div class="mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="togglePassword">
              <label for="togglePassword" class="form-check-label">Mostrar contraseña</label>
            </div>
            <div class="mb-3">
              <input type="submit" class="btn btn-primary form-control" name="submit" value="Ingresar">
            </div>
            <p class="mb-0">No estas registrado? <a href="./register.php">Registrarse</a></p>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>