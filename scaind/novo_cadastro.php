<!DOCTYPE html>
<html>

  <head>
	<title>Scaind</title>
	<meta charset='utf-8'>
	<link rel="stylesheet" href="estilo/modelo.css">
  </head>

	<body>
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

require_once "credencial.php";
require_once "crud_scaind.php";
require_once "fnc_cadastros.php";

class NovoCadastro{
	
	private $s_nome,$s_cpf,$s_nomeSis,$s_senhaSis,$s_email,$s_senhaEmail,$s_setor,$s_obs,$s_permisso,$s_estado;
	private $dadoArray = array();
	
	function __construct($nome,$cpf,$nomeSis,$senhaSis,$email,$senhaEmail,$setor,$obs,$permisso){
		
		$this->s_nome = $nome;
		$this->s_cpf = str_replace("-","",str_replace(".","",$cpf));
		$this->s_nomeSis = $nomeSis;
		$this->s_senhaSis = $senhaSis;
		$this->s_email = $email;
		$this->s_senhaEmail = $senhaEmail;
		$this->s_setor = $setor;
		$this->s_obs = $obs;
		$this->s_permisso = $permisso;
		$this->s_estado = '1';
		
	}
	
	function __destruct(){
		
		$this->s_nome = null;
		$this->s_cpf = null;
		$this->s_nomeSis = null;
		$this->s_senhaSis = null;
		$this->s_email = null;
		$this->s_senhaEmail = null;
		$this->s_setor = null;
		$this->s_obs = null;
		$this->s_permisso = null;
		$this->s_estado = null;
		
	}
	
	public function gravarCadastro(){
		
		//gravar cadastro informado
		$verPermissaoObj = new Prevent($_SESSION['prevent']);
		
		if($verPermissaoObj->permissoAtiva('2') == true){
			
			$buscarObj = new AcoesBD('cadastros');
			$cadastrarObj = $buscarObj;		
				
			if($this->validarDados($this->s_nome,'nome') != true OR strlen($this->s_nome) == 0){
				return 1;
			}
			
			if($this->validarDados($this->s_cpf,'cpf') != true AND strlen($this->s_cpf) != 0){
				return 1;
			}

			if($this->validarDados($this->s_nomeSis,'nome_sistema') != true AND strlen($this->s_nomeSis) != 0){
				return 1;
			}

			if($this->validarDados($this->s_email,'nome_email') != true AND strlen($this->s_email) != 0){
				return 1;
			}

			$buscarObj->setInfo($this->s_nome,'dado');
			$buscarObj->setInfo('nome LIKE','coluna');
			$buscarObj->setInfo('todos','selecionar');
			
			
			if(!$buscarObj->ler() AND strlen($this->s_nome) != 0){
				
				unset($this->dadoArray);
				$this->dadoArray = array('nome','cpf','nome_sistema','senha_sistema','nome_email','senha_email','setor','obs','permisso','estado');
				$cadastrarObj->setInfo($this->dadoArray,'colunas');
				
				unset($this->dadoArray);
				$this->dadoArray = array($this->s_nome,$this->s_cpf,$this->s_nomeSis,$this->s_senhaSis,$this->s_email,$this->s_senhaEmail,$this->s_setor,$this->s_obs,$this->s_permisso,$this->s_estado);
				$cadastrarObj->setInfo($this->dadoArray,'dados');
				
				if ($cadastrarObj->inserir()){
					unset($cadastrarObj,$buscarObj);
					return 0;
				}else{
					unset($cadastrarObj,$buscarObj);
					return 1;
				}
			}else{
				
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
	public function validarDados($dado_valido,$indice){
		
		$testeDoubleObj = new FncCadastros(null);
		if($testeDoubleObj->duplicidadeTeste($dado_valido,$indice) == true){
		
			unset($testeDoubleObj);
			return false;
		
		}
		
		unset($testeDoubleObj);
		return true;
		
	}
}

$conferePermissaoObj = new Prevent($_SESSION['prevent']);	
		
if (isset($_POST["gravar"]) == null && $conferePermissaoObj->permissoAtiva('2') == true){
	
	//exibir formulário para cadastro
	echo	"<div class='formRegistros'>";			
	echo  	"	<form method='post' action='novo_cadastro.php' target='frameresultados' name='novoUser'>";
	echo	"		<input type='hidden' name='gravar' value='save'>";	
	echo	"		<fieldset class='fieldNovoBase'>";
	echo	"		<legend class='legendaExibicao'>Novo Cadastro:</legend>";
	echo	"			<fieldset class='fieldNovo'>";
	echo	"				<legend class='legendaExibicao'>Nome Completo do Colaborador</legend>";
	echo	"				<input type='text' class='boxFormNovo' name='nomeC' placeholder='Nome completo'>";	
	echo	"			</fieldset>";
	echo	"			<fieldset class='fieldNovo'>";
	echo	"			<legend class='legendaExibicao'>CPF do Colaborador</legend>";
	echo	"				<input type='text' class='boxFormNovo' name='cpf' placeholder='CPF'>";	
	echo	"			</fieldset>";
	echo	"			<fieldset class='fieldNovo'>";
	echo	"			<legend class='legendaExibicao'>Nome do Usuário de Sistema</legend>";
	echo	"				<input type='text' class='boxFormNovo' name='nomeS' placeholder='Usuário de Sistema'>";
	echo	"			</fieldset>";
	echo	"			<fieldset class='fieldNovo'>";
	echo	"			<legend class='legendaExibicao'>Senha de Usuário</legend>";
	echo	"				<input type='text' class='boxFormNovo' name='senhaS' placeholder='Senha de Usuário'>";
	echo	"			</fieldset>";
	echo	"			<fieldset class='fieldNovo'>";
	echo	"			<legend class='legendaExibicao'>Email do Colaborador</legend>";
	echo	"				<input type='email' class='boxFormNovo' name='email' placeholder='Conta de Email'>";
	echo	"			</fieldset>";
	echo	"			<fieldset class='fieldNovo'>";
	echo	"			<legend class='legendaExibicao'>Senha do Email</legend>";
	echo	"				<input type='text' class='boxFormNovo' name='senhaE' placeholder='Senha do Email'>";	
	echo	"			</fieldset>";
	echo	"			<fieldset class='fieldNovo'>";
	echo	"			<legend class='legendaExibicao'>Setor Destinado</legend>";
	echo	"		    	<select class='boxFormNovo' name='setor'>";
		
	$setorObj = new AcoesBD('setor');
	$setorObj->setInfo('todos','selecionar');
	$setorObj->setInfo('','dado');
	
	if($setorObj->ler()){
				
		foreach($setorObj->ler() as $reg){
	
			echo 	"			<option value=".$reg['id_setor'].">".$reg['nome_setor']."</option>";
			
		}
	}
	
	unset($setorObj);
	echo	"				</select>";
	echo	"			</fieldset>";
	echo	"			<fieldset class='fieldNovo'>";
	echo	"			<legend class='legendaExibicao'>Observações</legend>";
	echo	"				<textarea name='obs' rows='2' cols='60' class='boxFormNovo' placeholder='Informações Complementares'></textarea>";	
	echo	"			</fieldset>";
	echo	"			<div class='botaoGravar'>";
	echo	"				<a href=\"javascript:novoUser.submit()\" >Gravar</a>";
	echo	"			</div>";
	echo	"		</fieldset>";
	echo	"	</form>";
	echo	"</div>";
	
}else{
	
	//Solicitar a gravação dos dados.
	$permissao ='0000000000';
	
	if (isset($_POST['setor'])){
		$gravarObj = new NovoCadastro($_POST['nomeC'],$_POST['cpf'],$_POST['nomeS'],$_POST['senhaS'],$_POST['email'],$_POST['senhaE'],$_POST['setor'],$_POST['obs'],$permissao);
	}else{
		
		echo	"<div class='formRegistros'>";			
		echo	"	<fieldset class='fieldDetalheBase'>";
		echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
		echo	"			Necessário criar o setor antes de inserir um novo cadastro.";
		echo	"	</fieldset>";
		echo	"</div>";
		return 0;
			
	}
	
	if(!$gravarObj->gravarCadastro()){

		// Confirmando a gravação retorna para o formulário vazio, se não vai ao formulário preenchido.
		echo	"<div class='formRegistros'>";			
		echo	"	<fieldset class='fieldDetalheBase'>";
		echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
		echo	"			Registro criado!";
		echo	"		</fieldset>";
		echo	"</div>";	
		
		$registrarAcessoObj = new Prevent('N');
		if($registrarAcessoObj->registroAcesso('0',$_POST['nomeC'],'Criação de Cadastro')){
			echo "Falha ao salvar histrico.";
		}
		unset($registrarAcessoObj,$gravarObj);	
		return 0;

	}
	
	if($conferePermissaoObj->permissoAtiva('2') == true){
		
		// exibir formulário em caso de duplicidade
		$notificaItemObj = new NovoCadastro(null,null,null,null,null,null,null,null,null);
		
		echo	"<div class='formRegistros'>";			
		echo  	"	<form method='post' action='novo_cadastro.php' target='frameresultados' name='novoUser'>";
		echo	"		<input type='hidden' name='gravar' value='save'>";	
		echo	"		<fieldset class='fieldNovoBase'>";
		echo	"		<legend class='legendaExibicao'>Novo Cadastro:</legend>";
		echo	"			<fieldset class='fieldNovo'>";
		echo	"				<legend class='legendaExibicao'>Nome Completo do Colaborador</legend>";
		
		if($notificaItemObj->validarDados($_POST['nomeC'],'nome') == true AND strlen($_POST['nomeC']) != 0){
			
			echo	"				<input type='text' class='boxFormNovo' name='nomeC' placeholder='Nome completo' value='".$_POST['nomeC']."'>";	
		
		}else{
		
			echo	"				<input type='text' class='boxFormNovo' style='background-color:#f3f1c4;color:#e64000' name='nomeC' placeholder='Nome não pode ser vazio.' value='".$_POST['nomeC']."'>";	
		
		}
		
		echo	"			</fieldset>";
		echo	"			<fieldset class='fieldNovo'>";
		echo	"			<legend class='legendaExibicao'>CPF do Colaborador</legend>";
		
		if($notificaItemObj->validarDados($_POST['cpf'],'cpf') == true OR strlen($_POST['cpf']) == 0){
		
			echo	"				<input type='text' class='boxFormNovo' name='cpf' placeholder='CPF' value='".$_POST['cpf']."'>";		
		
		}else{
		
			echo	"				<input type='text' class='boxFormNovo' style='background-color:#f3f1c4;color:#e64000' name='cpf' placeholder='CPF' value='".$_POST['cpf']."'>";		
		
		}
		
		echo	"			</fieldset>";
		echo	"			<fieldset class='fieldNovo'>";
		echo	"			<legend class='legendaExibicao'>Nome do Usuário de Sistema</legend>";
		
		if($notificaItemObj->validarDados($_POST['nomeS'],'nome_sistema') == true OR strlen($_POST['nomeS']) == 0){
		
			echo	"				<input type='text' class='boxFormNovo' name='nomeS' placeholder='Usuário de Sistema' value='".$_POST['nomeS']."'>";
		
		}else{
		
			echo	"				<input type='text' class='boxFormNovo' style='background-color:#f3f1c4;color:#e64000' name='nomeS' placeholder='Usuário de Sistema' value='".$_POST['nomeS']."'>";
		
		}
		
		echo	"			</fieldset>";
		echo	"			<fieldset class='fieldNovo'>";
		echo	"			<legend class='legendaExibicao'>Senha de Usuário</legend>";
		echo	"				<input type='text' class='boxFormNovo' name='senhaS' placeholder='Senha de Usuário' value='".$_POST['senhaS']."'>";
		echo	"			</fieldset>";
		echo	"			<fieldset class='fieldNovo'>";
		echo	"			<legend class='legendaExibicao'>Email do Colaborador</legend>";
		
		if($notificaItemObj->validarDados($_POST['email'],'nome_email') == true OR strlen($_POST['email']) == 0){
		
			echo	"				<input type='email' class='boxFormNovo' name='email' placeholder='Conta de Email' value='".$_POST['email']."'>";
		
		}else{
		
			echo	"				<input type='email' class='boxFormNovo' style='background-color:#f3f1c4;color:#e64000' name='email' placeholder='Conta de Email' value='".$_POST['email']."'>";
		
		}
		
		unset($notificaItemObj);
		
		echo	"			</fieldset>";
		echo	"			<fieldset class='fieldNovo'>";
		echo	"			<legend class='legendaExibicao'>Senha do Email</legend>";
		echo	"				<input type='text' class='boxFormNovo' name='senhaE' placeholder='Senha do Email' value='".$_POST['senhaE']."'>";	
		echo	"			</fieldset>";
		echo	"			<fieldset class='fieldNovo'>";
		echo	"			<legend class='legendaExibicao'>Setor Destinado</legend>";
		echo	"		    	<select class='boxFormNovo' name='setor'>";
			
		$setorObj = new AcoesBD('setor');
		$setorObj->setInfo('todos','selecionar');
		$setorObj->setInfo('','dado');
		
		if($setorObj->ler()){
					
			foreach($setorObj->ler() as $reg){
				
				if($_POST['setor'] == $reg['id_setor']){
					echo 	"			<option value=".$reg['id_setor'].">".$reg['nome_setor']."</option>";
				}
			}
			unset($reg);
			foreach($setorObj->ler() as $reg){
				
				if($_POST['setor'] != $reg['id_setor']){
					echo 	"			<option value=".$reg['id_setor'].">".$reg['nome_setor']."</option>";
				}
			}
		}
		
		unset($setorObj);
		echo	"				</select>";
		echo	"			</fieldset>";
		echo	"			<fieldset class='fieldNovo'>";
		echo	"			<legend class='legendaExibicao'>Observações</legend>";
		echo	"				<textarea name='obs' rows='2' cols='60' class='boxFormNovo' placeholder='Informações Complementares' value='".$_POST['obs']."'></textarea>";	
		echo	"			</fieldset>";
		echo	"			<div class='botaoGravar'>";
		echo	"				<a href=\"javascript:novoUser.submit()\" >Gravar</a>";
		echo	"			</div>";
		echo	"		</fieldset>";
		echo	"	</form>";
		echo	"</div>";
	
	}
}

unset($conferePermissaoObj);
?>
