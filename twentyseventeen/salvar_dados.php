<?php
include 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $confirmarEmail = $_POST['confirmarEmail'];
    $telefone = $_POST['telefone'];
    $novasenha = $_POST['novasenha'];
    $nomeCurso = $_POST['nomeCurso'];
    $resumoCurso = $_POST['resumoCurso'];
    $modalidade = $_POST['modalidade'];
    $unidade = $_POST['unidade'];
    $cargaHoraria = $_POST['cargaHoraria'];
    $diasHorarios = $_POST['diasHorarios'];
    $inicio = $_POST['inicio'];
    $precoCheio = $_POST['precoCheio'];
    $precoDesconto = $_POST['precoDesconto'];

    $sql = "INSERT INTO cadastro (nome, email, confirmarEmail, telefone, novasenha, nomeCurso, resumoCurso, modalidade, unidade, cargaHoraria, diasHorarios, inicio, precoCheio, precoDesconto)
            VALUES ('$nome', '$email', '$confirmarEmail', '$telefone', '$novasenha', '$nomeCurso', '$resumoCurso', '$modalidade', '$unidade', '$cargaHoraria', '$diasHorarios', '$inicio', '$precoCheio', '$precoDesconto')";

    if ($conn->query($sql) === TRUE) {
        echo "Dados salvos com sucesso";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>