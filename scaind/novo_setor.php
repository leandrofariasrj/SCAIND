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
	<title>Scaind</title>
	  <meta charset='utf-8'>	
	<link rel="stylesheet" href="estilo/modelo.css">
  </head>
  
	<body>

<?php

require_once "credencial.php";	
require_once "crud_scaind.php";

class NovoSetor{
	
	private $setor;
	private $dadosArray;
	
	function __construct($nome){
		
		$this->setor = $nome;
		
	}
	
	public function gravarSetor(){
		
		//gravação do setor informado
		$verPermissaoObj = new Prevent($_SESSION['prevent']);
		
		if($verPermissaoObj->permissoAtiva('4') == true){
			
			$cadastrarObj = new AcoesBD('setor');
			$this->dadosArray = array('nome_setor');
			$cadastrarObj->setInfo($this->dadosArray,'colunas');
				
			unset($this->dadosArray);
			$this->dadosArray = array($this->setor);
			$cadastrarObj->setInfo($this->dadosArray,'dados');
			
			if ($cadastrarObj->inserir()){
			
				unset($cadastrarObj,$this->dadosArray,$this->setor);
				return 0;
			
			}else{
			
				unset($cadastrarObj,$this->dadosArray,$this->setor);
				return 1;
			
			}
		}else{
		
			echo	"<div class='formRegistros'>";			
			echo	"	<fieldset class='fieldDetalheBase'>";
			echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
			echo	"			Acesso negado, favor entrar em contato com o administrador.";
			echo	"	</fieldset>";
			echo	"</div>";
			unset($estadoPermissao);
			return 1;
		}	
			
	}
	
}

	$registrados = null;
	$reg = null;
	$valido = true;
	$setorObj = new AcoesBD('setor');
	$setorObj->setInfo('especifico','selecionar');
	$setorObj->setInfo('','dado');
	$arrayColuna = array('nome_setor');
	$setorObj->setInfo($arrayColuna,'colunas');
	
	$conferePermissaoObj = new Prevent($_SESSION['prevent']);	
			
	if($setorObj->ler() && $conferePermissaoObj->permissoAtiva('4') == true){
		
		//verificação de duplicidade		
		foreach($setorObj->ler() as $reg){
			
			if(isset($_POST['nomeS']) != null){
				
				if(strtolower($reg['nome_setor']) == strtolower($_POST['nomeS'])){
					
					$registrados .= "<label class='labelInativo'>".ucwords(strtolower($reg['nome_setor']))."</label><br>";
					$valido = false;
					
				}else{
					
					$registrados .= "<label class='labelAtivo'>".ucwords(strtolower($reg['nome_setor']))."</label><br>";
					
				}
				
			}else{
				
				$registrados .= "<label class='labelAtivo'>".ucwords(strtolower($reg['nome_setor']))."</label><br>";
				
			}
		}
	}else{
		
		$registrados .= "<label class='labelInativo'>".ucwords(strtolower('Nenhum encontrado.'))."</label><br>";
			
	}
	
	if(isset($_POST['nomeS']) != null AND strlen($_POST['nomeS']) != 0 AND $valido == true){
		
		//solicitação de gravação pós confirmação de dados
		$gravaObj = new NovoSetor($_POST['nomeS']);
		if (!$gravaObj->gravarSetor()){
			
			$registrarAcessoObj = new Prevent('N');
			if($registrarAcessoObj->registroAcesso('0',$_POST['nomeS'],'Criação de Setor')){
				echo "Falha ao salvar histrico.";
			}
			unset($registrarAcessoObj);	
			header('location:novo_setor.php#frameresultados');

		}
	}
	
	if($conferePermissaoObj->permissoAtiva('4') == true){
	
		//formulário para o envio do setor
		echo	"<div class='formRegistros'>";			
		echo	"		<fieldset class='fieldNovoBase'>";
		echo	"		<legend class='legendaExibicao'>Novo Setor:</legend>";
		echo	"		<fieldset class='fieldNovo'>";
		echo	"			<legend class='legendaExibicao'>Setores Registrados</legend>";
		echo				$registrados;		
		echo	"		</fieldset>";
		echo  	"	<form method='post' action='novo_setor.php' target='frameresultados' name='novoSetor'>";
		echo	"		<input type='hidden' name='gravar' value='save'>";	
		echo	"			<fieldset class='fieldNovo'>";
		echo	"				<legend class='legendaExibicao'>Nome do Setor</legend>";
		echo	"				<input type='text' class='boxFormNovo' name='nomeS' placeholder='Setor'>";	
		echo	"			</fieldset>";
		echo	"			<div class='botaoGravar'>";
		echo	"				<a href=\"javascript:novoSetor.submit()\" >Gravar</a>";
		echo	"			</div>";
		echo	"		</fieldset>";
		echo	"	</form>";
		echo	"</div>";
	
	}
	unset($conferePermissaoObj);
?>	

	</body>
</html>
