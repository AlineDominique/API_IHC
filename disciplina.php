<?php
function ListaDisciplinas($id){
	include("conectar.php");
	
	$resposta = array();
	$id = mysqli_real_escape_string($conexao,$id);
	
	//Consulta Disciplina no banco
	if($id == 0){
		$query = mysqli_query($conexao,"SELECT idDisciplina, Nome FROM Disciplina") or die(mysqli_error($conexao));
	}else{
		$query = mysqli_query($conexao,"SELECT idDisciplina, Nome FROM Disciplina WHERE idProfessor = " .$id) or die(mysqli_error($conexao));
	}
	//faz um looping e cria um array com os campos da consulta
	while($dados = mysqli_fetch_array($query))
	{
		$resposta[] = array('idDisciplina' => $dados['idDisciplina'],
							'Nome' => utf8_encode($dados['Nome'])); //tira encode quando for publicar no host
	}
	return $resposta;
}

function InsereDisciplina(){
	
	//Recupera conteudo recebido na request
	$conteudo = file_get_contents("php://input");
	$resposta = array();
	//Verifica se o conteudo foi recebido
	if(empty($conteudo)){
		$resposta = mensagens(2);
	}
	else{
		//Converte o json recebido pra array
		$dados = json_decode($conteudo,true);
		
		//Verifica se as infromações esperadas foram recebidas
		if(!isset($dados["Nome"]))
		{
			$resposta = mensagens(3);
		}
		else{
			include("conectar.php");
			
			//Evita SQL injection
			$Nome = mysqli_real_escape_string($conexao,$dados["Nome"]);
					
			//Recupera idDisciplina para incrementar 1
			$idDisciplina = 0;
			$query = mysqli_query($conexao, "SELECT idDisciplina FROM Disciplina ORDER BY idDisciplina DESC LIMIT 1") or die(mysqli_error($conexao));
			while($dados = mysqli_fetch_array($query)){
				$idDisciplina = $dados["idDisciplina"];
			}
			$idDisciplina++;
			
			//Insere Disciplina
			$query = mysqli_query($conexao,"INSERT INTO Disciplina VALUES(" .$idDisciplina .",'" .utf8_decode($Nome) ."')") or die(mysqli_error($conexao)); // retirar decode quando publicar no host
			$resposta = mensagens(4);
		}
	}
	return $resposta;
}
function AtualizaDisciplina($id){
	
	//Recupera conteudo recebido na request
	$conteudo = file_get_contents("php://input");
	$resposta = array();
	//Verifica se o id foi recebido
	if($id == 0){
		$resposta = mensagens(5);
	}
	else{
		//Verifica se o conteudo foi recebido
		if(empty($conteudo)){
			$resposta = mensagens(2);
		}
		else{
			//Converte o json recebido pra array
			$dados = json_decode($conteudo,true);
			
			//Verifica se as infromações esperadas foram recebidas
			if(!isset($dados["Nome"]))
			{
				$resposta = mensagens(3);
			}
			else
			{
				include("conectar.php");
				
				//Evita SQL injection
				$Nome = mysqli_real_escape_string($conexao,$dados["Nome"]);
						
				$update = "UPDATE Disciplina SET Nome = '" .utf8_decode($Nome) ."' WHERE idDisciplina = ".$id;
								
				
				//Atualiza Professor no banco
				$query = mysqli_query($conexao, $update) or die(mysqli_error($conexao));
				$resposta = mensagens(6);
			}
		}
	}
	return $resposta;
}
function ExcluiDisciplina($id){
	
	//Recupera conteudo recebido na request
	$resposta = array();
	//Verifica se o id foi recebido
	if($id == 0){
		$resposta = mensagens(5);
	}
	else{
		include("conectar.php");
		
		//Evita SQL injection		
		$id = mysqli_real_escape_string($conexao,$id);
		
		//Exclui Professor
		$query = mysqli_query($conexao, "DELETE FROM Disciplina WHERE idDisciplina=" .$id) or die(mysqli_error($conexao));
		
		$resposta = mensagens(7);
	}
	return $resposta;
}
?>