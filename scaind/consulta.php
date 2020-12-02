<?php

/*
  * Projeto SCAIND - Sistema de Cadastro Independente - v2.0
  * Desenvolvido por Leandro Farias
  * Email: projectserverone@gmail.com
  * Perfil profissional: https://www.linkedin.com/in/leandro-farias-rj-19b09796/
*/

ini_set('session.save_path', '/sessao');
ini_set('default_charset','UTF-8');

if (!isset($_SESSION)){
	session_start();
}

?>	
<!DOCTYPE html>
<html>

  <head>
	<title>Consultar cadastro de usuário</title>
	<meta charset='utf-8'>	
	<link rel="stylesheet" href="estilo/modelo.css">
  </head>
	<body class='sistema'>
	
<?php

require_once "credencial.php";
	
	//exibir formulário de busca filtrada
	echo	"<div id='menuSecundario'>";
	echo	"	<fieldset class='fieldBusca'>";
	echo	"	  <legend class='legendaExibicao'>Pesquisar:</legend>";
	echo  	"		<form method='post' action='fnc_cadastros.php' target='frameresultados' name='buscar'>";
	echo	"				<input type='hidden' name='funcao' value='buscar'>";
	echo	"				<div class='boxRadioForm'>";	
	echo	"					<input type='radio' checked id='dataTodos' name='tipoBusca' value='todos'> <label for='dataTodos'>Todos</label> <br>";	
	echo	"				</div>";
	echo	"				<div class='boxRadioForm'>";	
	echo	"					<input type='radio' id='dataNome' name='tipoBusca' value='nome'> <label for='dataNome'>Nome do colaborado</label> <br>";
	echo	"				</div>";
	echo	"				<div class='boxRadioForm'>";	
	echo	"					<input type='radio' id='dataCpf' name='tipoBusca' value='cpf'> <label for='dataCpf'>CPF</label> <br>";
	echo	"				</div>";
	echo	"				<div class='boxRadioForm'>";	
	echo	"					<input type='radio' id='dataLogin' name='tipoBusca' value='nome_sistema'> <label for='dataLogin'>Login do colaborador</label> <br>";
	echo	"				</div>";
	echo	"				<div class='boxRadioForm'>";	
	echo	"					<input type='radio' id='dataEmail' name='tipoBusca' value='nome_email'> <label for='dataEmail'>Email do colaborador</label> <br>";
	echo	"				</div>";
	echo	"				<div class='boxRadioForm'>";	
	echo	"					<input type='radio' id='dataObs' name='tipoBusca' value='obs'> <label for='dataObs'>Observações</label> <br>";	
	echo	"				</div>";
	echo	"					<input type='text' class='boxInputForm' name='userdata'>";
	echo	"				<div class='botaoBusca'>";
	echo	"					<a href=\"javascript:buscar.submit()\" >Selecionar</a>";
	echo	"				</div>";
	echo	"		</form>";
	echo	"	</fieldset>";
	
	$verPermissaoObj = new Prevent($_SESSION['prevent']);
	if($verPermissaoObj->permissoAtiva('2') != false OR $verPermissaoObj->permissoAtiva('4') != false){
		
		// exibir botão de novo cadastro e ou setor
		echo	"		<fieldset class='fieldBusca'>";
		echo	"			<legend class='legendaExibicao'>Novo:</legend>";
		
		
		if($verPermissaoObj->permissoAtiva('2') == true){

			echo	"		<div class='botaoNovo'>";
			echo  	"			<form method='post' action='novo_cadastro.php' target='frameresultados' name='criarCadastro'>";
			echo	"				<input type='hidden' name='funcao' value='formularioCadastro'>";
			echo	"				<a href=\"javascript:criarCadastro.submit()\" >Cadastro</a>";
			echo	"			</form>";
			echo	"		</div>";

		}
		
		if($verPermissaoObj->permissoAtiva('4') == true){

			echo	"		<div class='botaoNovo'>";
			echo	"			<a href=novo_setor.php target='frameresultados'>Setor</a>";
			echo	"		</div>";
		
		}
				
	}
	
	
	echo	"		</fieldset>";
	
	
	if($verPermissaoObj->permissoAtiva('6') == true){

		//exibir botão de exclusões
		echo	"<fieldset class='fieldBusca'>";
		echo	"	<legend class='legendaExibicao'>Exclusões:</legend>";
		echo  	"	<form method='post' action='fnc_cadastros.php' target='frameresultados' name='regisEx'>";
		echo	"		<input type='hidden' name='funcao' value='historico'>";	
		echo	"		<input type='hidden' name='opc' value='x'>";	
		echo	"		<div class='botaoNovo'>";
		echo	"			<a href=\"javascript:regisEx.submit()\" >Exibir</a>";
		echo	"		</div>";
		echo	"</fieldset>";
		

	}
	
	unset($verPermissaoObj);
	echo	"</div>";
	echo	"	<iframe id='resultadosframe' name='frameresultados'></iframe>";
	
?>
	</body>
</html>
