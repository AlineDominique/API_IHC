<?php
function ListaAulasPorProfessor($id){
	include("conectar.php");
	
	$resposta = array();
	$id = mysqli_real_escape_string($conexao,$id);
	
	//Consulta Aula no banco
	if($id == 0){
		$query = mysqli_query($conexao,"SELECT a.idAula, a.Dia, a.HoraInicio, a.HoraFim, a.Semestre, b.Nome as 'Disciplina', c.Nome as 'Professor', d.Numero as 'Sala' FROM Aula as a INNER JOIN Disciplina as b on a.idDisciplina = b.idDisciplina INNER JOIN Professor as c on a.idProfessor = c.idProfessor INNER JOIN Sala as d on a.idSala = d.idSala ORDER BY a.Dia ASC") or die(mysqli_error($conexao));
	}else{
		$query = mysqli_query($conexao,"SELECT a.idAula, a.Dia, a.HoraInicio, a.HoraFim, a.Semestre, b.Nome as 'Disciplina', c.Nome as 'Professor', d.Numero as 'Sala' FROM Aula as a INNER JOIN Disciplina as b on a.idDisciplina = b.idDisciplina INNER JOIN Professor as c on a.idProfessor = c.idProfessor INNER JOIN Sala as d on a.idSala = d.idSala WHERE c.idProfessor = " .$id ." ORDER BY a.Dia ASC") or die(mysqli_error($conexao));
	}
	//faz um looping e cria um array com os campos da consulta
	while($dados = mysqli_fetch_array($query))
	{
		$resposta[] = array('idAula' => $dados['idAula'],
							'Dia' => $dados['Dia'],
							'HoraInicio' => $dados['HoraInicio'],
							'HoraFim' => $dados['HoraFim'],
							'Semestre' => $dados['Semestre'],
							'Disciplina' => $dados['Disciplina'],
							'Professor' => $dados['Professor'],
							'Sala' => $dados['Sala']); 
	}
	return $resposta;
}
function ListaAulasPorSala($NRO){
	include("conectar.php");
	
	$resposta = array();
	$NRO = mysqli_real_escape_string($conexao,$NRO);
	
	//Consulta Aula no banco
	if($NRO == 0){
		$query = mysqli_query($conexao,"SELECT a.idAula, a.Dia, a.HoraInicio, a.HoraFim, a.Semestre, b.Nome as 'Disciplina', c.Nome as 'Professor', d.Numero as 'Sala' FROM Aula as a INNER JOIN Disciplina as b on a.idDisciplina = b.idDisciplina INNER JOIN Professor as c on a.idProfessor = c.idProfessor INNER JOIN Sala as d on a.idSala = d.idSala ORDER BY a.Dia ASC") or die(mysqli_error($conexao));
	}else{
		$query = mysqli_query($conexao,"SELECT a.idAula, a.Dia, a.HoraInicio, a.HoraFim, a.Semestre, b.Nome as 'Disciplina', c.Nome as 'Professor', d.Numero as 'Sala' FROM Aula as a INNER JOIN Disciplina as b on a.idDisciplina = b.idDisciplina INNER JOIN Professor as c on a.idProfessor = c.idProfessor INNER JOIN Sala as d on a.idSala = d.idSala WHERE d.Numero = '" .$NRO . "' ORDER BY a.Dia ASC") or die(mysqli_error($conexao));
	}
	//faz um looping e cria um array com os campos da consulta
	while($dados = mysqli_fetch_array($query))
	{
		$resposta[] = array('idAula' => $dados['idAula'],
							'Dia' => $dados['Dia'],
							'HoraInicio' => $dados['HoraInicio'],
							'HoraFim' => $dados['HoraFim'],
							'Semestre' => $dados['Semestre'],
							'Disciplina' => $dados['Disciplina'],
							'Professor' => $dados['Professor'],
							'Sala' => $dados['Sala']);
	}
	return $resposta;
}
// Testar Código

function InsereAula(){
	
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
		if(!isset($dados["Dia"]) || !isset($dados["HoraInicio"]) || !isset($dados["HoraFim"]) ||
			!isset($dados["Semestre"]) || !isset($dados["idDisciplina"]) || !isset($dados["idProfessor"]) ||
			!isset($dados["idSala"]))
		{
			$resposta = mensagens(3);
		}
		else{
			include("conectar.php");
			
			//Evita SQL injection
			$Dia = mysqli_real_escape_string($conexao,$dados["Dia"]);
			$HoraInicio = mysqli_real_escape_string($conexao,$dados["HoraInicio"]);
			$HoraFim = mysqli_real_escape_string($conexao,$dados["HoraFim"]);
			$Semestre = mysqli_real_escape_string($conexao,$dados["Semestre"]);
			$idDisciplina = mysqli_real_escape_string($conexao,$dados["idDisciplina"]);
			$idProfessor = mysqli_real_escape_string($conexao,$dados["idProfessor"]);
			$idSala = mysqli_real_escape_string($conexao,$dados["idSala"]);
					
			//Recupera idAula para incrementar 1
			$idAula = 0;
			$query = mysqli_query($conexao, "SELECT idAula FROM Aula ORDER BY idAula DESC LIMIT 1") or die(mysqli_error($conexao));
			while($dados = mysqli_fetch_array($query)){
				$idAula = $dados["idAula"];
			}
			$idAula++;
			
			//Insere Aula
			$query = mysqli_query($conexao,"INSERT INTO Aula VALUES(" .$idAula .",'" .$Dia ."','" .$HoraInicio ."','" .$HoraFim ."'," .$Semestre ."," .$idDisciplina ."," .$idProfessor ."," .$idSala .")") or die(mysqli_error($conexao)); 
			$resposta = mensagens(4);
		}
	}
	return $resposta;
}
function AtualizaAula($id){
	
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
			
			//Verifica se as informações esperadas foram recebidas
			if(!isset($dados["Dia"]) || !isset($dados["HoraInicio"]) || !isset($dados["HoraFim"]) ||
				!isset($dados["Semestre"]) || !isset($dados["idDisciplina"]) || !isset($dados["idProfessor"]) ||
				!isset($dados["idSala"]))
			{
				$resposta = mensagens(3);
			}
			else
			{
				include("conectar.php");
				
				//Evita SQL injection
				$Dia = mysqli_real_escape_string($conexao,$dados["Dia"]);
				$HoraInicio = mysqli_real_escape_string($conexao,$dados["HoraInicio"]);
				$HoraFim = mysqli_real_escape_string($conexao,$dados["HoraFim"]);
				$Semestre = mysqli_real_escape_string($conexao,$dados["Semestre"]);
				$idDisciplina = mysqli_real_escape_string($conexao,$dados["idDisciplina"]);
				$idProfessor = mysqli_real_escape_string($conexao,$dados["idProfessor"]);
				$idSala = mysqli_real_escape_string($conexao,$dados["idSala"]);
							
				$update = "UPDATE Aula SET Dia = '" .$Dia ."', HoraInicio = '" .$HoraInicio ."', HoraFim = '" .$HoraFim ."', Semestre = " .$Semestre .", idDisciplina = " .$idDisciplina .", idProfessor = " .$idProfessor .", idSala = " .$idSala ." WHERE idAula = ".$id;
							
				//Atualiza Aula no banco
				$query = mysqli_query($conexao, $update) or die(mysqli_error($conexao));
				$resposta = mensagens(6);
			}
		}
	}
	return $resposta;
}
function ExcluiAula($id){
	
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
		$query = mysqli_query($conexao, "DELETE FROM Aula WHERE idAula=" .$id) or die(mysqli_error($conexao));
		
		$resposta = mensagens(7);
	}
	return $resposta;
}
?>