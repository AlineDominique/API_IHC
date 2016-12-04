<?php
function ListaProfessores($id){
	include("conectar.php");
	
	$resposta = array();
	$id = mysqli_real_escape_string($conexao,$id);
	
	//Consulta Professores no banco
	if($id == 0){
		$query = mysqli_query($conexao,"SELECT idProfessor, Nome FROM Professor ORDER BY Nome ASC") or die(mysqli_error($conexao));
	}else{
		$query = mysqli_query($conexao,"SELECT idProfessor, Nome FROM Professor WHERE idProfessor = " .$id . " ORDER BY Nome ASC") or die(mysqli_error($conexao));
	}
	//faz um looping e cria um array com os campos da consulta
	while($dados = mysqli_fetch_array($query))
	{
		$resposta[] = array('idProfessor' => $dados['idProfessor'],
							'Nome' => $dados['Nome']); 
	}
	return $resposta;
}

function InsereProfessor(){
	
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
					
			//Recupera idProfessor para incrementar 1
			$idProfessor = 0;
			$query = mysqli_query($conexao, "SELECT idProfessor FROM Professor ORDER BY idProfessor DESC LIMIT 1") or die(mysqli_error($conexao));
			while($dados = mysqli_fetch_array($query)){
				$idProfessor = $dados["idProfessor"];
			}
			$idProfessor++;
			
			//Insere Professor
			$query = mysqli_query($conexao,"INSERT INTO Professor VALUES(" .$idProfessor .",'" .$Nome ."')") or die(mysqli_error($conexao));
			$resposta = mensagens(4);
		}
	}
	return $resposta;
}
function AtualizaProfessor($id){
	
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
						
				$update = "UPDATE Professor SET Nome = '" .$Nome ."' WHERE idProfessor = ".$id;
								
				
				//Atualiza Professor no banco
				$query = mysqli_query($conexao, $update) or die(mysqli_error($conexao));
				$resposta = mensagens(6);
			}
		}
	}
	return $resposta;
}
function ExcluiProfessor($id){
	
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
		$query = mysqli_query($conexao, "DELETE FROM Professor WHERE idProfessor=" .$id) or die(mysqli_error($conexao));
		
		$resposta = mensagens(7);
	}
	return $resposta;
}
?>
