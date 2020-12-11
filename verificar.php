<?php
    #PROBLEMAS = 0;

    include('connect.php');

    $usuario = $_POST['usuario'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $cabeleleiro = $_POST['cabeleleiro'];
    
    #mostrar informações. 
    #echo $data. ' '. $hora . ' '. $cabeleleiro . ' '. $usuario. '<br>'; 

    $query = "select id_usuario, id_cabeleleiro, id_data, id_hora from horas as h inner join datas as d inner join cabeleleiros as c inner join usuarios as u where h.hora = '$hora' and d.data = '$data' and c.cabeleleiro = '$cabeleleiro' and u.usuario='$usuario'";
    $result = mysqli_query($conexao, $query);

    while($row = mysqli_fetch_assoc($result)) {
        $id_hora = $row['id_hora'];
        $id_data = $row['id_data'];
        $id_cabeleleiro = $row['id_cabeleleiro'];
        $id_usuario = $row['id_usuario'];
    }

    #verificação principal.
    $query_verify = "select id_hora from reservas where id_cabeleleiro = $id_cabeleleiro and id_data = $id_data and id_hora = $id_hora";
    $result_verify = mysqli_query($conexao, $query_verify);
    $row_verify = mysqli_fetch_assoc($result_verify);
    
    #OBS: Está dando certo, porém existe um erro que diz: Trying to access array offset on value of type null in C:\xampp1\htdocs\salao\verificar.php on line 25
    #OBS: Samuel não pode estar em dois locais ao mesmo tempo, ou ele corta com um cabeleleiro ou com outro.
    #resolvendo o problema do Samuel. Pronto, esse foi RESOLVIDO., mas surgiu outro, no qual o Samuel não poderia marcar mais nem um outro horário em nem um dia do mês, esse também foi RESOLVIDO.

    $query_verify2 = "select id_cabeleleiro from reservas where id_usuario = $id_usuario and id_data = $id_data and id_hora = $id_hora";
    $result_verify2 = mysqli_query($conexao, $query_verify2);
    $row_verify2 = mysqli_fetch_assoc($result_verify2);
    $id_cabeleleiro_verify = $row_verify2['id_cabeleleiro'];

    if($row_verify['id_hora'] == '' and $id_cabeleleiro_verify == '') {
        #query de adição.
        echo 'o usuário foi adicionado com sucesso.';
        $query_add = "insert into reservas(id_usuario, id_cabeleleiro, id_data, id_hora) values($id_usuario, $id_cabeleleiro, $id_data, $id_hora)";
        $result_add = mysqli_query($conexao, $query_add);
    } else {
        echo '<br>Horário ocupado.';
    }

    #usado para voltar a tela inicial, não indicado descomentar durante testes:
    #header('location:index.php');
?>