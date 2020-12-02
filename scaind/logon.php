<?php

/*
  * Projeto SCAIND - Sistema de Cadastro Independente - v2.0
  * Desenvolvido por Leandro Farias
  * Email: projectserverone@gmail.com
  * Perfil profissional: https://www.linkedin.com/in/leandro-farias-rj-19b09796/
*/

ini_set('session.save_path', '/sessao');

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

require_once "crud_scaind.php";

class Logon{
	
	private $usuario,$chave,$dadosRetorno,$sessao,$sessaoAtual;
	private $dadosArray = array();
	
	function __construct($use,$key){
		
		$this->usuario = $use;
		$this->chave = $key;
		
		if (isset($_SESSION['logon']) == null){
					
			$this->testeSessao();
		
		}
    }
    
	private function testeSessao(){
		
		if (isset($_SESSION['logon']) != null AND isset($_POST['keysis']) == null AND $_SESSION['logon'] == true ){
			header ("Location: index.php");
		}
	}
	
	public function sessaoUnica(){
	
		$sessionObj = new AcoesBD('sessao');
		$sessionObj->setInfo($_SESSION['usuario'],'dado');
		$sessionObj->setInfo('nome_sistema LIKE','coluna');
		$sessionObj->setInfo('especificado','selecionar');
		$this->dadosArray = array('sessao');
		$sessionObj->setInfo($this->dadosArray,'colunas');
		$this->sessaoAtual = session_id();
						
		if ($sessionObj->ler()){
						
			foreach($sessionObj->ler() as $this->sessao){
						
				if ($this->sessao['sessao'] != $this->sessaoAtual){
									
					$altereObj = new AcoesBD('sessao');
					$altereObj->setInfo($_SESSION['usuario'],'item');
					$altereObj->setInfo('nome_sistema','onde');
					$altereObj->setInfo($this->sessaoAtual,'dado');
					$altereObj->setInfo('sessao','coluna');
					if(!$altereObj->alterar()){
		
						unset($altereObj,$sessionObj,$this->dadosArray,$this->sessaoAtual);
						return 0;
					}
				}
			}
			unset($altereObj,$sessionObj,$this->sessaoAtual);
			return 1;
							
		}else{
						
			$cadastrarSessao = new AcoesBD('sessao');
			$this->dadosArray = array('nome_sistema','sessao');
			$cadastrarSessao->setInfo($this->dadosArray,'colunas');
							
			unset($this->dadosArray);
			$this->dadosArray = array($_SESSION['usuario'],$this->sessaoAtual);
			$cadastrarSessao->setInfo($this->dadosArray,'dados');
							
			if ($cadastrarSessao->inserir()){
							
				unset($cadastrarSessao,$this->dadosArray,$this->sessaoAtual);
				return 0;
							
			}else{
							
				unset($cadastrarSessao,$this->dadosArray,$this->sessaoAtual);
				return 1;
							
			}
		}
	}
	
	public function testeLogon(){
		
		//Acesso administrador usuário="root scaind" senha="#root@scaind"
			
		if ( $this->usuario == 'root scaind' and $this->chave == '#root@scaind'){
			
			$_SESSION['logon'] = true;
			$_SESSION['usuario'] = strtoupper($this->usuario);
			$_SESSION['prevent'] = '99';

			if($this->sessaoUnica()){

				header ("Location: index.php");
				return 0;
			}
			
		
	}else{
			
			$cadastroObj = new AcoesBD('cadastros');
			$cadastroObj->setInfo($this->usuario,'dado');
			$cadastroObj->setInfo('nome_sistema LIKE','coluna');
			$cadastroObj->setInfo('especificado','selecionar');
			$this->dadosArray = array('nome_sistema','permisso','acesso');
			$cadastroObj->setInfo($this->dadosArray,'colunas');
			unset($this->dadosArray);
			$this->dadosArray = array('AND (senha_sistema LIKE'=>$this->chave,' OR acesso LIKE'=>$this->chave.'\')');
			$cadastroObj->setInfo($this->dadosArray,'colunaDados');
			unset($this->dadosArray);
			
			if($cadastroObj->ler()){
								
				foreach($cadastroObj->ler() as $this->dadosRetorno){
					
					if($this->dadosRetorno['acesso'] != null AND $this->dadosRetorno['acesso'] != $this->chave){

						return 1;
												
					}elseif($this->dadosRetorno['acesso'] != null AND $this->dadosRetorno['acesso'] == $this->chave){

						$_SESSION['usuario'] = $this->dadosRetorno['nome_sistema'];
						$_SESSION['prevent'] = $this->dadosRetorno['permisso'];
						$_SESSION['logon'] = true;
						
					}else{
					
						$_SESSION['usuario'] = $this->dadosRetorno['nome_sistema'];
						$_SESSION['prevent'] = $this->dadosRetorno['permisso'];
						$_SESSION['logon'] = true;
					
					}
					
					if($this->sessaoUnica()){
						return 0;
					}
					
				}
	
			}else{
			
				return 1;
			
			}
		}
	}
	
	public function fecharSessao(){
		
		$fecharSessaoObj = new AcoesBD('sessao');
		$fecharSessaoObj->setInfo('nome_sistema','onde');
		$fecharSessaoObj->setInfo($this->usuario,'dado');
	
		if($fecharSessaoObj->deletar()){
			unset($fecharSessaoObj);
			return 0;
		}
		return 1;
	}
}

if(isset($_POST['keysis']) != null AND isset($_POST['usersis']) == null AND $_POST['keysis'] == 'sair'){

	$closeSessaoObj = new Logon($_SESSION['usuario'],null);
	
	if($closeSessaoObj->fecharSessao()){
		unset($closeSessaoObj);
		SESSION_DESTROY();
	}

}

	echo	'<div id=baseSite>';
	echo	'	<div id=menuTopo>';	
	echo	'		<label id=tituloSistem>SCAIND <p id=subtituloSistem>Sistema de Cadastro Independente</p></label>';	
	echo	'	</div>';		

if ((isset($_POST['usersis']) == null && isset($_POST['keysis']) == null) OR $_POST['usersis'] == ''){

	echo	"		<div id=baseLogin>";
	echo  	"			<form method='post' action='logon.php' name=acessoLogon>";
	echo	"		  	<fieldset id='flogin'>";
	echo	"				<center>";
	echo	"					<label for='user'><font  size=5 color=#000><b>Login</b></font> </label><br>";
	echo	"					<input type='text' class='boxTextForm' name='usersis' placeholder='Usuário'>";
	echo	"		   			<input type='password' class='boxTextForm' name='keysis' placeholder='Senha'>";
	echo	"					<div class='botaoBusca'>";
	echo	"						<a href=\"javascript:acessoLogon.submit()\" >Acessar</a>";
	echo	"					</div>";
	echo	"				</center>";
	echo	"		  	</fieldset>";
	echo	"			</form>";	
	echo	"		</div>";	 
	
		 
}elseif (isset($_POST['usersis']) != null && isset($_POST['keysis']) != null){

	
	$loginObj = new Logon($_POST['usersis'],$_POST['keysis']);
	
	if($loginObj->testeLogon()){
			
		echo	"		<div id=baseLogin>";
		echo  	"			<form method='post' action='logon.php' name=acessoLogon>";
		echo	"		  	<fieldset id='flogin'>";
		echo	"				<center>";
		echo	"					<label for='user'><font  size=5 color=#cd3333><b>Login Incorreto</b></font> </label><br><br>";
		echo	"					<input type='text' class='boxTextForm' name='usersis' placeholder='Usuário' value='".$_POST['usersis']."'>";
		echo	"		   			<input type='password' class='boxTextForm' name='keysis' placeholder='Senha'>";
		echo	"					<div class='botaoBusca'>";
		echo	"						<a href=\"javascript:acessoLogon.submit()\" >Acessar</a>";
		echo	"					</div>";
		echo	"				</center>";
		echo	"		  	</fieldset>";
		echo	"			</form>";	
		echo	"		</div>";	 
		
	}else{
		header ("Location: index.php");
					
	}
	
}
	echo	"</div>";
?>
 	</body>
  
 </html>
