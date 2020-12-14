<?php
// Include config file
require_once "connect.php";
 
// Definir variáveis e inicializa-las com valores vazios inicialmente
$username = $password = $confirm_password = $email = "";
$username_err = $password_err = $email_err = $confirm_password_err =  "";

 
// Processar dados do form quando ele for submetido
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validatar username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id_usuario FROM usuarios WHERE email = ?";
        
        if($stmt = mysqli_prepare($conexao, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            echo  $email;

            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) > 1){
                    $email_err = "Esse email já foi utilizado.";
                } else{
                    $username = trim($_POST["username"]);
                   
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
     // Validar senha
     if(empty(trim($_POST["password"]))){
        $password_err = "Por favor insira a senha";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Senha tem que ter pelo menos 6 caractéres";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validar senha confirmada
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Por favor confirme a senha";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Senhas diferentes";
        }
    }
    
    // Checar erros de input antes da inserir no banco de dados.
    if(empty($username_err) && empty($password_err) && empty($email_err) && empty($confirm_password_err) ){
        
        // Preparar query sql
        $sql = "INSERT INTO usuarios (usuario, senha, email) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($conexao, $sql)){
            // Conectar variáveis para as querys sql
            mysqli_stmt_bind_param($stmt, "sss", $param_username, $param_password , $param_email);
            
            // Setar parâmetros
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_email = $email;

            
            // if para executar a query e ver se foi realizada com sucesso
            if(mysqli_stmt_execute($stmt)){
                // Redirecioar para página de login mas por enquando será para index na intenção de teste 
                header("location: index.php");
            } else{
                echo "Algo deu errado por favor tente novamente depois.";
            }

            // Fechar query sql
            mysqli_stmt_close($stmt);
        }
    }
    
    
   
    // Fechar conexão
    mysqli_close($conexao);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>

    <div class="wrapper">
        <h2>Registrar-se</h2>
        <p>Preencha os formulários para criar uma conta</p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>    

            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Nome</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    

            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Senha</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirmar Senha</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submeter">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>

            <p>Já tem uma conta? <a href="login.php">Logue por aqui</a>.</p>
        </form>
    </div>    
</body>
</html>