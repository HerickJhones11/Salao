<?php
    $host = 'localhost'; #Aqui deve ser colocado seu localhost.
    $usuario = 'root'; #Aqui deve ser colocada o seu usuário mysql
    $senha = ''; #Aqui deve ser colocada a senha do seu servidor mysql.
    $db = 'leila'; #Aqui deve ser colocado o nome do seu database.
    $conexao = mysqli_connect($host, $usuario, $senha, $db);#cria a conexão entre php e mysql.
?>