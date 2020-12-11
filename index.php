<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<div>
    <?php
        $errors = array();

        if (isset($_POST['nome'])) {
           if (empty($_POST['nome'])) {
                array_push($errors, '<spam style="color: red">Não foi inserido nenhum nome!</spam> <br/>');                
           }
           if (!ctype_alpha($_POST['nome'])) {
                array_push($errors, '<spam style="color: red">O nome possui caracteres inválidos!</spam> <br/>');
           }

           if (count($errors) == 0) {
                echo 'SALVA AS COISAS NO BANCO POIS OS DADOS ESTÃO VÁLIDOS';
           } else {
               foreach ($errors as $e) {
                   echo $e;
               }
           }
        }   
    ?>
</div>

<form action="verificar.php" method="POST">
        <?php
            include('connect.php');

            if(!$conexao) {
                echo 'nao conectou';
            } else{
                echo 'conectou';
            } echo '<br>';

            $query = 'select * from usuarios';
            $result = mysqli_query($conexao, $query); 

            $query2 = "select * from datas";
            $result2= mysqli_query($conexao, $query2); 

            $query3 = "select * from horas";
            $result3 = mysqli_query($conexao, $query3); 

            $query4 = 'select * from cabeleleiros';
            $result4 = mysqli_query($conexao, $query4);
        ?>

        <select name="usuario" id="">
            <?php while($row = mysqli_fetch_array($result)):;?>
                <option value="<?php echo $row[1];?>"><?php echo $row[1];?></option>
            <?php endwhile;?>
        </select>

        <select name="data" id="">
            <?php while($row2 = mysqli_fetch_array($result2)):;?>
                <option value="<?php echo $row2[1];?>"><?php echo $row2[1];?></option>
            <?php endwhile;?>
        </select>

        <select name="hora" id="">
        <?php while($row3 = mysqli_fetch_array($result3)):;?>
            <option value="<?php echo $row3[1];?>"><?php echo $row3[1];?></option>
        <?php endwhile;?>
        </select>

        <select name="cabeleleiro" id="">
        <?php while($row4 = mysqli_fetch_array($result4)):;?>
            <option value="<?php echo $row4[1];?>"><?php echo $row4[1];?></option>
        <?php endwhile;?>
        </select>

        <input type="submit">
    </form>
</body>
</html>