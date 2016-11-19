<?php
function ListaSalas($id){
	include("conectar.php");
	
	$resposta = array();
	$id = mysqli_real_escape_string($conexao,$id);
	
	//Consulta Sala no banco
	if($id == 0){
		$query = mysqli_query($conexao,"SELECT idSala, Numero FROM Sala ORDER BY idSala ASC") or die(mysqli_error($conexao));
	}else{
		$query = mysqli_query($conexao,"SELECT idSala, Numero FROM Sala  WHERE idSala = " .$id . " ORDER BY idSala ASC") or die(mysqli_error($conexao));
	}
	//faz um looping e cria um array com os campos da consulta
	while($dados = mysqli_fetch_array($query))
	{
		$resposta[] = array('idSala' => $dados['idSala'],
							'Numero' => $dados['Numero']); 
	}
	return $resposta;
}

function InsereSala(){
	
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
		if(!isset($dados["Numero"]))
		{
			$resposta = mensagens(3);
		}
		else{
			include("conectar.php");
			
			//Evita SQL injection
			$Numero = mysqli_real_escape_string($conexao,$dados["Numero"]);
					
			//Recupera idSala para incrementar 1
			$idSala = 0;
			$query = mysqli_query($conexao, "SELECT idSala FROM Sala ORDER BY idSala DESC LIMIT 1") or die(mysqli_error($conexao));
			while($dados = mysqli_fetch_array($query)){
				$idSala = $dados["idSala"];
			}
			$idSala++;
			
			//Insere Sala
			$query = mysqli_query($conexao,"INSERT INTO Sala VALUES(" .$idSala ."," .$Numero .")") or die(mysqli_error($conexao)); 
			$resposta = mensagens(4);
		}
	}
	return $resposta;
}
function AtualizaSala($id){
	
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
			if(!isset($dados["Numero"]))
			{
				$resposta = mensagens(3);
			}
			else
			{
				include("conectar.php");
				
				//Evita SQL injection
				$Numero = mysqli_real_escape_string($conexao,$dados["Numero"]);
						
				$update = "UPDATE Sala SET Numero = " .$Numero ." WHERE idSala = ".$id;
								
				
				//Atualiza Professor no banco
				$query = mysqli_query($conexao, $update) or die(mysqli_error($conexao));
				$resposta = mensagens(6);
			}
		}
	}
	return $resposta;
}
function ExcluiSala($id){
	
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
		
		//Exclui Sala
		$query = mysqli_query($conexao, "DELETE FROM Sala WHERE idSala=" .$id) or die(mysqli_error($conexao));
		
		$resposta = mensagens(7);
	}
	return $resposta;
}
?>