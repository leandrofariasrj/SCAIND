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
	<title>SCAIND - Sistema de Cadastro</title>
	<meta charset='utf-8'>
	<link rel="stylesheet" href="estilo/modelo.css">
	<link rel="shortcut icon" href="imagens/icone_aba.ico" type="image/x-icon">
  </head>

	<body class='sistema'>

<?php

class funcaoIndex{
	
	private $nomeSessao,$usuario,$chave,$registro;
	private $dadosArray;
	 
	public function boasVindas(){
		
		$this->nomeSessao=ucwords(strtolower(str_replace("."," ",$_SESSION['usuario'])));
		echo "Olá, ".$this->nomeSessao;
		
	}
	
	public function alterarSenha($user,$key){
		
		require_once "crud_scaind.php";
		$this->usuario = $user;
		$this->chave = $key;
		$novaSenhaObj = new AcoesBD('cadastros');
		$gravarSenhaObj = $novaSenhaObj;
		$novaSenhaObj->setInfo($this->usuario,'dado');
		$novaSenhaObj->setInfo('nome_sistema LIKE','coluna');
		$novaSenhaObj->setInfo('especificado','selecionar');
		$this->dadosArray = array('id_usuario','acesso');
		$novaSenhaObj->setInfo($this->dadosArray,'colunas');
		
		foreach($novaSenhaObj->ler() as $this->registro){
			
			if($this->registro['acesso'] != $this->chave){
				$gravarSenhaObj->setInfo($this->chave,'dado');
				$gravarSenhaObj->setInfo('acesso','coluna');
				$gravarSenhaObj->setInfo($this->registro['id_usuario'],'item');
				$gravarSenhaObj->setInfo('id_usuario','onde');
				if($gravarSenhaObj->alterar()){
					return 0;
				}
			}
		}
		return 1;
	}
}


if ($_SESSION['logon'] == true AND isset($_POST['keysis']) == null) {

		$fncIndex = new funcaoIndex();
		
		echo	"<div id=baseSite>";
		echo	"	<div id=menuTopo>";
		echo	'	<label id=tituloSistem>SCAIND <p id=subtituloSistem>Sistema de Cadastro Independente</p></label>';	
		echo	"		<div class=botaoTop>";
		echo	"			<a href='consulta.php' target='frameconteudo'>Administração de Cadastros</a>";
		echo	"		</div>";
		echo	"		<div class=botaoTop>";
		echo	"			<a href='creditos.html' target='frameconteudo'>Sobre</a>";
		echo	"		</div>";
		echo	"  		<div class='blocoUser'>";
		echo	"			<label>";
								$fncIndex->boasVindas();
		echo	"			</label>";
		echo	"    		<form method=post name=AltSenha action=index.php>";
		echo	"    			<input type='hidden' name='keysis' value='alterar_senha'>";
		echo	"        			<a href='javascript:AltSenha.submit()' title='Alterar Senha'> Aterar senha </a>";
		echo	"    		</form>";
		echo	"    		<form method=post name=opSair action=logon.php>";
		echo	"		 		<input type=hidden name=keysis value=sair>";
		echo	"      				<a href='javascript:opSair.submit()' title='Sair'> Encerrar</a>";
		echo	"    		</form>";
		echo	"  		</div>";
		echo	"	</div>";
		echo	"  	<div id='baseConteudo'>";
		echo	"		<iframe id=conteudoframe name=frameconteudo></iframe>";
		echo	"	</div>";
		echo	"</div>";

}elseif (isset($_POST['keysis']) != null AND  isset($_SESSION['usuario']) != null){
	
	if ($_POST['keysis'] == 'alterar_senha'){
			
		if (isset($_POST['senhaNova']) == null AND isset($_POST['senhaNovaC']) == null){
			
			echo	"<div id=baseSite>";
			echo	"	<div id=menuTopo>";	
			echo	"		<label id=tituloSistem>SCAIND <p id=subtituloSistem>Sistema de Cadastro Independente</p></label>";	
			echo	"	</div>";	
			echo	"		<div id=baseLogin>";
			echo  	"			<form method='post' action='index.php' name=acessoLogon>";
			echo	"		  	<fieldset id='flogin'>";
			echo	"				<center>";
			echo	"					<label for='user'><font  size=5 color=#000><b>Alterar Senha</b></font> </label><br>";
			echo	"		 			<input type=hidden name=keysis value='alterar_senha'>";
			echo	"					<input type='password' class='boxTextForm' name='senhaNova' placeholder='Nova senha'>";
			echo	"		   			<input type='password' class='boxTextForm' name='senhaNovaC' placeholder='Repetir nova senha'>";
			echo	"					<div class='botaoBusca'>";
			echo	"						<a href=\"javascript:acessoLogon.submit()\" >Acessar</a>";
			echo	"					</div>";
			echo	"				</center>";
			echo	"		  	</fieldset>";
			echo	"			</form>";	
			echo	"		</div>";
			echo	"</div>";	
			
		}else{
			
			if($_POST['senhaNova'] != '' AND $_POST['senhaNova'] == $_POST['senhaNovaC']){
			
				$alterarSenha = new funcaoIndex();
				if(!$alterarSenha->alterarSenha($_SESSION['usuario'],$_POST['senhaNova'])){
				
					
					echo	"		<div id=baseLogin>";
					echo  	"			<form method='post' action='index.php' name=retornoAcesso>";
					echo	"		  	<fieldset id='flogin'>";
					echo	"				<center>";
					echo	"					<label for='user'><font  size=5 color=#000><b>Senha Alterada!</b></font> </label><br>";
					echo	"					<div class='botaoBusca'>";
					echo	"						<a href=\"javascript:retornoAcesso.submit()\" >Voltar</a>";
					echo	"					</div>";
					echo	"				</center>";
					echo	"		  	</fieldset>";
					echo	"			</form>";	
					echo	"		</div>";
						
				}else{
					
					echo	"		<div id=baseLogin>";
					echo  	"			<form method='post' action='index.php' name=retornoAcesso>";
					echo	"		  	<fieldset id='flogin'>";
					echo	"				<center>";
					echo	"					<label for='user'><font  size=5 color=#000><b>A senha deve ser diferente, verifique e tente novamente!</b></font> </label><br>";
					echo	"					<div class='botaoBusca'>";
					echo	"						<a href=\"javascript:retornoAcesso.submit()\" >Voltar</a>";
					echo	"					</div>";
					echo	"				</center>";
					echo	"		  	</fieldset>";
					echo	"			</form>";	
					echo	"		</div>";
				}
			}else{
			
				echo	"		<div id=baseLogin>";
				echo  	"			<form method='post' action='index.php' name=retornoAcesso>";
				echo	"		  	<fieldset id='flogin'>";
				echo	"				<center>";
				echo	"					<label for='user'><font  size=5 color=#000><b>Senhas não conferem verifique e tente novamente!</b></font> </label><br>";
				echo	"					<div class='botaoBusca'>";
				echo	"						<a href=\"javascript:retornoAcesso.submit()\" >Voltar</a>";
				echo	"					</div>";
				echo	"				</center>";
				echo	"		  	</fieldset>";
				echo	"			</form>";	
				echo	"		</div>";
			}
		}
	}
}else{
	header ("Location: logon.php");
}

?>
  	</body>

 </html>
