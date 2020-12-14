<?php
// inicializa a session
session_start();
 
// Checa se o user já está logado,se sim ele será redirecionado
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Include connect file
require_once "connect.php";
 
// define variáveis inicialmente como vazias
$username = $password = "";
$username_err = $password_err = "";
 
// Roda quando o form for submetido
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Checa se o nome está vazio.
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor insira um nome.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Checa se a senha está vazia;
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor insira a senha";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validar credentials
    if(empty($username_err) && empty($password_err)){
        // prepara um select statement
        $sql = "SELECT id_usuario,usuario,senha FROM usuarios WHERE usuario = ?";

        if($stmt = mysqli_prepare($conexao, $sql)){
            // Insere variável no statement sql
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parametros
            $param_username = $username;
            
            // Tentativa para executar o statement
            if(mysqli_stmt_execute($stmt)){
                // armazena resultado
                mysqli_stmt_store_result($stmt);
                echo strval(mysqli_stmt_num_rows($stmt));

                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Conecta variáveis com os resultados da query
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    echo strval($hashed_password);

                    if(mysqli_stmt_fetch($stmt)){


                        if(password_verify($password, $hashed_password)){
                            // Senha é correta
                            
                            session_start();
                            
                            // Armzenamento dos dados em sessão
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirecia user para index
                            header("location: index.php");
                        } else{
                            // Mostra mensagem de erro se o usuário n for achado se a senha n for válida
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Mostra mensagem de erro se o usuário n for achado
                    $username_err = "Nenhuma conta achada pelo nome";
                }
            } else{
                echo "Oops!  Algo deu errado.Tente novamente.";
            }
            
            // fechar statement
            mysqli_stmt_close($stmt);
            }
    }
    
    // fechar conexão
    mysqli_close($conexao);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Não tem conta? <a href="signup.php">Registrar-se agora</a>.</p>
        </form>
    </div>    
</body>
</html>