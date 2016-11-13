<?php
$ServerBD = $_SERVER['SERVER_NAME'];
if(!isset($PortaBD)){
    $conexao = mysqli_connect($ServerBD, "root", "");
}else{
    $conexao = mysqli_connect($ServerBD .":" .$PortaBD, "root", "");   
}
//$conect = mysqli_connect('localhost', "root", "");
// Caso a conexão seja reprovada, exibe na tela uma mensagem de erro
if (!$conexao) die ('<div id="erro2">Falha na coneco com o Banco de Dados!</div>');
// Caso a conexão seja aprovada, então conecta o Banco de Dados.	
$db = mysqli_select_db($conexao,"BDHorarioAula");
?>