<!DOCTYPE html>
<html>

  <head>
		<title>SCAIND - Sistema de Cadastro</title>
		<meta charset='utf-8'>
		<link rel="stylesheet" href="estilo/modelo.css">
		<link rel="shortcut icon" href="imagens/icone_aba.ico" type="image/x-icon">
  </head>

	<body background="#CFCFCF">

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

class FncCadastros{
	
	private $dados_busca;
	private $dadosArray = array();
	private $registro = null;
	private $identificacao;
	private $array_retorno = array();
	
	function __construct($filtro){
		
		$this->dados_busca = $filtro;
				
	}
	
	public function getInfo($var,$indice){
		
		return $this->$var[$indice]; 
	}
	
		
	public function buscarCadastros($tipoPesquisa){
				
		$buscaObj = new AcoesBD('cadastros');
				
		if($tipoPesquisa == 'todos'){
			//Selecionar todas as colunas previstas.
			$buscaObj->setInfo('nome LIKE','coluna');	
			$this->dadosArray = array('OR nome_sistema LIKE'=>'%'.$this->dados_busca.'%','OR nome_email LIKE'=>'%'.$this->dados_busca.'%','OR cpf LIKE'=>'%'.$this->dados_busca.'%','OR obs LIKE'=>'%'.$this->dados_busca.'%');
		}else{
			//Selecionar a coluna pesquisada.
			$buscaObj->setInfo($tipoPesquisa.' LIKE','coluna');		
		}

		//Palavra chave.
		$buscaObj->setInfo('%'.$this->dados_busca.'%','dado');
		//Habilitar o retorno.
		$buscaObj->setInfo('especificado','selecionar');
		//Multiplos filtro.
		$buscaObj->setInfo($this->dadosArray,'colunaDados');
		
		unset($this->dadosArray);
		$this->dadosArray = array('id_usuario','nome','nome_email','estado','nome_setor','permisso');
		//Selecionar colunas a serem exibidas.
		$buscaObj->setInfo($this->dadosArray,'colunas');
		 
		//Instrução de junção das tabelas
		
		$buscaObj->setInfo(' INNER JOIN setor ON setor.id_setor=cadastros.setor','instrucao');
		$buscaObj->setInfo('nome','ordem');
		
		$estadoPermissao = new Prevent($_SESSION['prevent']);
		
		if($estadoPermissao->permissoAtiva('1') == true && $buscaObj->ler()){
			
			unset($estadoPermissao);
			
			foreach($buscaObj->ler() as $this->registro){
				
				echo "<div class='formRegistros'>";			
				echo "	<div class='formRegistrosNome'>";
				echo "		<fieldset class='fieldExibicao'>";
				echo "			<legend class='legendaExibicao'>Nome Completo</legend>";
				echo 				ucwords(strtolower($this->registro['nome']));
				echo "		</fieldset>";
				echo "	</div>";
				
				echo "	<div class='formRegistrosP'>";
				echo "		<fieldset class='fieldExibicao'>";
				echo "			<legend class='legendaExibicao'>Setor</legend>";
				echo 				$this->registro['nome_setor'];
				echo "		</fieldset>";
				echo "	</div>";
				
				echo "	<div class='formRegistrosEmail'>";
				echo "		<fieldset class='fieldExibicao'>";
				echo "			<legend class='legendaExibicao'>Email</legend>";
				
				if (strlen($this->registro['nome_email']) > 0){
				
					echo 		strtolower($this->registro['nome_email']);
				
				}else{
					
					echo	"<center>-</center>";	
				}
				
				echo "		</fieldset>";
				echo "	</div>";
				
				echo "	<div class='formRegistrosSituacao'>";
				echo "		<fieldset class='fieldExibicao'>";
				echo "			<legend class='legendaExibicao'>Situação</legend>";
				
				if($this->registro['estado'] == 1){
					echo "<label class='labelAtivo'>Ativo</label>";
				}else{
					echo "<label class='labelInativo'>Inativo</label>";
				}
				
				echo "		</fieldset>";
				echo "	</div>";
				echo "	<div class='formRegistrosG'>";
				echo "		<fieldset class='fieldExibicao'>";
				echo "			<legend class='legendaExibicao'>Opções</legend>";
				
				$estadoPermissao = new Prevent($_SESSION['prevent']);
				if($estadoPermissao->permissoAtiva('2') == false AND $estadoPermissao->permissoAtiva('3') == false AND $estadoPermissao->permissoAtiva('5') == false AND $estadoPermissao->permissoAtiva('6') == false){

					echo "<center>-</center>";

				}
				
				if($estadoPermissao->permissoAtiva('2') == true){
				
					echo "			<form method='post' action='fnc_cadastros.php' target='frameresultados' id='Fncdetalhe-".$this->registro['id_usuario']."'>";
					echo "				<input type='hidden' name='funcao' value='ver'>";
					echo "				<input type='hidden' name='opc' value='".$this->registro['id_usuario']."'>";
					echo "				<div class='botaoOpc'>";
					echo "					<a href=\"javascript:document.getElementById('Fncdetalhe-".$this->registro['id_usuario']."').submit()\">Detalhe</a>";
					echo "				</div>";
					echo "			</form>";
				
				}
				
				if($estadoPermissao->permissoAtiva('2') == true){
			
					echo "			<form method='post' action='fnc_cadastros.php' target='frameresultados' id='Fnceditar-".$this->registro['id_usuario']."'>";
					echo "				<input type='hidden' name='funcao' value='editar'>";	
					echo "				<input type='hidden' name='opc' value='".$this->registro['id_usuario']."'>";
					echo "				<div class='botaoOpc'>";
					echo "					<a href=\"javascript:document.getElementById('Fnceditar-".$this->registro['id_usuario']."').submit()\" >Editar</a>";
					echo "				</div>";
					echo "			</form>";
			
				}
				
				if($estadoPermissao->permissoAtiva('3') == true){
					
					echo "			<form method='post' action='fnc_cadastros.php' target='frameresultados' id='Fncexcluir-".$this->registro['id_usuario']."'>";
					echo "				<input type='hidden' name='funcao' value='excluir'>";	
					echo "				<input type='hidden' name='nome' value='".$this->registro['nome']."'>";	
					echo "				<input type='hidden' name='opc' value='".$this->registro['id_usuario']."'>";
					echo "				<div class='botaoOpc'>";
					echo "					<a href=\"javascript:document.getElementById('Fncexcluir-".$this->registro['id_usuario']."').submit()\" >Excluir</a>";
					echo "				</div>";
					echo "			</form>";
				
				}
				
				if($estadoPermissao->permissoAtiva('5') == true){
					
					echo "			<form method='post' action='fnc_cadastros.php' target='frameresultados' id='Fncacesso-".$this->registro['id_usuario']."'>";
					echo "				<input type='hidden' name='funcao' value='permitir'>";
					echo "				<input type='hidden' name='nome' value='".$this->registro['nome']."'>";	
					echo "				<input type='hidden' name='permisso' value='".$this->registro['permisso']."'>";	
					echo "				<input type='hidden' name='opc' value='".$this->registro['id_usuario']."'>";
					echo "				<div class='botaoOpc'>";
					echo "					<a href=\"javascript:document.getElementById('Fncacesso-".$this->registro['id_usuario']."').submit()\" >Acessos</a>";
					echo "				</div>";
					echo "			</form>";
				
				}
				
				if($estadoPermissao->permissoAtiva('6') == true){
					
					echo "			<form method='post' action='fnc_cadastros.php' target='frameresultados' id='FncHacesso-".$this->registro['id_usuario']."'>";
					echo "				<input type='hidden' name='funcao' value='historico'>";	
					echo "				<input type='hidden' name='nome' value='".$this->registro['nome']."'>";	
					echo "				<input type='hidden' name='opc' value='".$this->registro['id_usuario']."'>";
					echo "				<div class='botaoOpc'>";
					echo "					<a href=\"javascript:document.getElementById('FncHacesso-".$this->registro['id_usuario']."').submit()\" >Histórico</a>";
					echo "				</div>";
					echo "			</form>";
				
				}
				
				unset($estadoPermissao);
				echo "		</fieldset>";
				echo "	</div>";
				echo "</div>";
			}

		}else{
			
			if($estadoPermissao->permissoAtiva('1') != true){
				
				echo	"<div class='formRegistros'>";			
				echo	"	<fieldset class='fieldDetalheBase'>";
				echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
				echo	"			Acesso negado, favor entrar em contato com o administrador.";
				echo	"		</fieldset>";
				echo	"</div>";
			}
			unset($buscaObj,$estadoPermissao);
			return 1;
		}
		
		unset($buscaObj,$this->registro,$this->dados_busca);
		return 0;
		
	}
	
	public function exibirCadastro(){
	
		$estadoPermissao = new Prevent($_SESSION['prevent']);
		if($estadoPermissao->permissoAtiva('2') != false){

			$buscaObj = new AcoesBD('cadastros');		
			//Selecionar as colunas previstas na busca.
			$buscaObj->setInfo('nome LIKE','coluna');	
			//Selecionar a coluna pesquisada.
			$buscaObj->setInfo('id_usuario LIKE','coluna');		
			//Palavra chave.
			$buscaObj->setInfo($this->dados_busca,'dado');
			//Habilitar o retorno.
			$buscaObj->setInfo('especificado','selecionar');
			//Selecionar colunas a serem exibidas.
			$this->dadosArray = array('id_usuario','nome','cpf','nome_sistema','senha_sistema','nome_email','senha_email','obs','estado','setor','nome_setor');
			$buscaObj->setInfo($this->dadosArray,'colunas');
			//Instrução de junção das tabelas
			$buscaObj->setInfo(' INNER JOIN setor ON setor.id_setor=cadastros.setor','instrucao');
			
			if($buscaObj->ler()){
				
				foreach($buscaObj->ler() as $this->registro){	
					
				}
				
				$registrarAcessoObj = new Prevent('N');
				if($registrarAcessoObj->registroAcesso($this->getInfo('registro','id_usuario'),$this->getInfo('registro','nome'),'Visualização')){
					echo "Falha ao salvar histrico.";
				}
				unset($registrarAcessoObj);	
				return 0;
				
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
	
	public function excluirCadastro(){
	
		//exclusão de cadastro selecionado
		$estadoPermissao = new Prevent($_SESSION['prevent']);
		if($estadoPermissao->permissoAtiva('3') != false){
			
			$excluirObj = new AcoesBD('cadastros');	
			$excluirObj->setInfo($this->dados_busca,'dado');
			$excluirObj->setInfo('id_usuario','onde');
			
			$registrarAcessoObj = new Prevent('N');
			if($registrarAcessoObj->registroAcesso($this->dados_busca,'','Exclusão de Cadastro')){
				echo "Falha ao salvar histrico.";
			}
			unset($registrarAcessoObj);	
			
			if($excluirObj->deletar()){
			
				unset($excluirObj,$this->dados_busca);
				return 0;
			
			}else{
			
				unset($excluirObj,$this->dados_busca);
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
	
	public function editarCadastro($nomeC,$cpf,$nomeS,$senhaS,$email,$senhaE,$setor,$estado,$obs){
		
		//edição de cadastro selecionado
		$estadoPermissao = new Prevent($_SESSION['prevent']);
		if($estadoPermissao->permissoAtiva('2') != false){
				
			$this->dadosArray = array($nomeC,$cpf,$nomeS,$senhaS,$email,$senhaE,$setor,$estado,$obs);
			$this->array_retorno = array('nome','cpf','nome_sistema','senha_sistema','nome_email','senha_email','setor','estado','obs');
			$indiceLog = array('campo nome','campo cpf','campo nome usuário','campo senha usuário','campo nome de email','campo senha de email','campo setor','campo estado','campo de observações');
			
			$confereObj = new AcoesBD('cadastros');
			$confereObj->setInfo($this->dados_busca,'dado');	
			$confereObj->setInfo('id_usuario LIKE','coluna');
			$confereObj->setInfo('todos','selecionar');
					
			foreach($confereObj->ler() as $this->registro){
				
				foreach($this->array_retorno as $key=>$this->identificacao){
					
					$altereObj = new AcoesBD('cadastros');
					//Confere o item que foi alterado e solicita a alteração.
					if($this->dadosArray[$key] != $this->registro[$this->identificacao]){
						
						if($this->duplicidadeTeste($this->dadosArray[$key],$this->identificacao != true)){
									
							
							$altereObj->setInfo($this->dados_busca,'item');
							$altereObj->setInfo('id_usuario','onde');
							$altereObj->setInfo($this->dadosArray[$key],'dado');
							$altereObj->setInfo($this->identificacao,'coluna');
							if(!$altereObj->alterar()){
							
								$this->confere = true;
								
							}else{
								
								$registrarAcessoEdObj = new Prevent('N');
								if($registrarAcessoEdObj->registroAcesso($this->dados_busca,$indiceLog[$key]." registrado ".$this->dadosArray[$key].", removido ".$this->registro[$this->identificacao],'Edição de Cadastro')){
								
									echo "Falha ao salvar histrico.";
								
								}
								unset($registrarAcessoEdObj);	
							}
							
						}else{
						
							$this->confere = true;
						
						}
					}
					unset($altereObj);
				}
			}
			
			if(isset($this->confere) != true){
			
				return 0;
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
	
	public function duplicidadeTeste($inf,$buscaPor){
		
		//conferencia de duplicidade nos pilares: nome, cpf, nome de sistema e email
		$doubleObj = new AcoesBD('cadastros');
		
		switch($buscaPor){
			
			case 'nome':
				
				$doubleObj->setInfo('especifica','selecionar');
				$doubleObj->setInfo($inf,'dado');
				$doubleObj->setInfo('nome LIKE','coluna');
				$doubleObj->setInfo(array('nome'),'colunas');
				if($doubleObj->ler()){
					return true;
				}
				return false;
				
			break;
			
			case 'cpf':
				
				$doubleObj->setInfo('especifica','selecionar');
				$doubleObj->setInfo($inf,'dado');
				$doubleObj->setInfo('cpf LIKE','coluna');
				$doubleObj->setInfo(array('cpf'),'colunas');
				if($doubleObj->ler()){
					unset($doubleObj);
					return true;
				}
				unset($doubleObj);
				return false;
				
			break;

			case 'nome_sistema':

				$doubleObj->setInfo('especifica','selecionar');
				$doubleObj->setInfo($inf,'dado');
				$doubleObj->setInfo('nome_sistema LIKE','coluna');
				$doubleObj->setInfo(array('nome_sistema'),'colunas');
				if($doubleObj->ler()){
					unset($doubleObj);
					return true;
				}
				unset($doubleObj);
				return false;
				
			break;

			case 'nome_email':

				$doubleObj->setInfo('especifica','selecionar');
				$doubleObj->setInfo($inf,'dado');
				$doubleObj->setInfo('nome_email LIKE','coluna');
				$doubleObj->setInfo(array('nome_email'),'colunas');
				if($doubleObj->ler()){
					unset($doubleObj);
					return true;
				}
				unset($doubleObj);
				return false;
				
			break;
			
			default:
			
				return true;
			
			break;

		}
		
	}	
	
	public function editarPermissao($userPerm){
		
		$estadoPermissao = new Prevent($_SESSION['prevent']);
		if($estadoPermissao->permissoAtiva('5') != false){

			//subentende a existncia de 6 permissõs.
			$this->identificacao = 0;
			$this->retorno = '';
			for($p=0;$p<=5;$p++){
				
				if ((($p+1)%3) == 0){
					
					$this->identificacao = $this->identificacao + $this->dados_busca[$p]; 
					$this->retorno .= $this->identificacao;
					$this->identificacao = 0;

				}else{
					
					$this->identificacao = $this->identificacao + $this->dados_busca[$p];
			
				}	
				
			}
				
			$gravarPemisso = new AcoesBD('cadastros');
			$conferePermisso = $gravarPemisso;
			$conferePermisso->setInfo('especifica','selecionar');
			$conferePermisso->setInfo($userPerm,'dado');
			$conferePermisso->setInfo('id_usuario LIKE','coluna');
			$conferePermisso->setInfo(array('permisso'),'colunas');
			
			foreach ($conferePermisso->ler() as $this->registro){
					
				if($this->registro['permisso'] != $this->retorno){
					$gravarPemisso->setInfo($userPerm,'item');
					$gravarPemisso->setInfo('id_usuario','onde');
					$gravarPemisso->setInfo($this->retorno,'dado');
					$gravarPemisso->setInfo('permisso','coluna');
					if($gravarPemisso->alterar()){
						
						$registrarAcessoObj = new Prevent('N');
						if($registrarAcessoObj->registroAcesso($userPerm,$this->retorno,'Edição de Permissões')){
							echo "Falha ao salvar histrico.";
						}
						unset($registrarAcessoObj);	
						
						unset($gravarPemisso,$conferePemisso);			
						return 0;
										
					}
				}else{
					unset($gravarPemisso,$conferePemisso);
					return 1;
				}
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

	public function buscarHistorico(){
		
		$estadoPermissao = new Prevent($_SESSION['prevent']);
		if($estadoPermissao->permissoAtiva('5') != false){

			$buscaObj = new AcoesBD('log');	
			
			if(is_numeric($this->dados_busca) == true){
				
				$buscaObj->setInfo($this->dados_busca,'dado');
				$buscaObj->setInfo('id_usuario LIKE','coluna');
				
			}else{
				
				unset($this->dados_busca);
				$buscarRegistroObj = new AcoesBD('acaolog');
				$buscarRegistroObj->setInfo('Exclusão de Cadastro','dado');
				$buscarRegistroObj->setInfo('nome_acao LIKE','coluna');
				$buscarRegistroObj->setInfo('todos','selecionar');
				
				foreach($buscarRegistroObj->ler() as $this->registro){
					
					$this->dados_busca = $this->registro['id_acao'];
				}
				
				$buscaObj->setInfo($this->dados_busca,'dado');
				$buscaObj->setInfo('acao LIKE','coluna');
				unset($this->registro,$buscarRegistroObj);
			}
			
							
			$buscaObj->setInfo('especificado','selecionar');
			$this->dadosArray = array('horario','nome_usuario','log','nome_acao');
			$buscaObj->setInfo($this->dadosArray,'colunas');
			$buscaObj->setInfo('log.horario','ordem');
			$buscaObj->setInfo('inverte','tipoOrdem');
			$buscaObj->setInfo(' INNER JOIN acaolog ON acaolog.id_acao=log.acao','instrucao');
			
			if($buscaObj->ler()){
				
				foreach($buscaObj->ler() as $this->registro){
					
					echo	"<div class='formRegistros'>";	
					echo	"	<div class='formHistoricoP'>";
					echo	"		<fieldset class='fieldExibicao'>";
					echo	"			<legend class='legendaExibicao'>Registro:</legend>";
					echo 					$this->registro['horario'];
					echo 	"		</fieldset>";
					echo	"	</div>";
					echo 	"	<div class='formHistoricoP'>";
					echo 	"		<fieldset class='fieldExibicao'>";
					echo 	"			<legend class='legendaExibicao'>Realizado:</legend>";
					echo 					$this->registro['nome_acao'];
					echo 	"		</fieldset>";
					echo	"	</div>";
					echo 	"	<div class='formHistoricoP'>";
					echo 	"		<fieldset class='fieldExibicao'>";
					echo 	"			<legend class='legendaExibicao'>Responsável:</legend>";
					echo 					$this->registro['nome_usuario'];
					echo 	"		</fieldset>";
					echo	"	</div>";
					echo 	"	<div class='formHistoricoG'>";
					echo 	"		<fieldset class='fieldExibicao'>";
					echo 	"			<legend class='legendaExibicao'>Detalhe:</legend>";
					echo 					strtolower($this->registro['log']);
					echo 	"		</fieldset>";
					echo	"	</div>";
					echo	"</div>";
				
				}
				
				return 0;
				
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
}

$fncCadastro = null;

if (isset($_POST['funcao']) != null){
	
	$fncCadastro = $_POST['funcao'];
}

switch($fncCadastro){

case 'buscar':
		
	$estadoPermissao = new Prevent($_SESSION['prevent']);
	
	if (isset($_POST['userdata']) != null && $estadoPermissao->permissoAtiva('1') == true){
		
		$fnBusca = new FncCadastros($_POST['userdata']);
		if($fnBusca->buscarCadastros($_POST['tipoBusca'])){

			echo	"<div class='formRegistros'>";			
			echo	"	<fieldset class='fieldDetalheBase'>";
			echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
			echo	"			Nenhum registro encontrado!";
			echo	"		</fieldset>";
			echo	"</div>";
		}
			
	}

break;

case 'ver':

	$estadoPermissao = new Prevent($_SESSION['prevent']);
	if($estadoPermissao->permissoAtiva('2') == true){
		
		$cpfC = null;
		$fnDetalhe = new FncCadastros($_POST['opc']);
		if(!$fnDetalhe->exibirCadastro()){
				
			echo	"<div class='formRegistros'>";			
			echo	"		<fieldset class='fieldDetalheBase'>";
			echo	"		<legend class='legendaExibicao'>Detalhes do Cadastro:</legend>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"				<legend class='legendaExibicao'>Nome Completo do Colaborador</legend>";
			echo 	"				<div class='textoDetalhes'>";
			echo 						ucwords(strtolower($fnDetalhe->getInfo('registro','nome')));
			echo	"				</div>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"			<legend class='legendaExibicao'>CPF do Colaborador</legend>";
			echo 	"				<div class='textoDetalhes'>";
				
					if (strlen($fnDetalhe->getInfo('registro','cpf')) == 11){
									
						$cpfCPonto=explode(".",wordwrap($fnDetalhe->getInfo('registro','cpf'), 3, ".", true));
						$cpfForm = $cpfCPonto[0].".".$cpfCPonto[1].".".$cpfCPonto[2]."-".$cpfCPonto[3];
								
					}else{
									
						$cpfForm = $fnDetalhe->getInfo('registro','cpf');
								
					}
			
			echo 			$cpfForm;
			echo	"				</div>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"			<legend class='legendaExibicao'>Nome do Usuário de Sistema</legend>";
			echo 	"				<div class='textoDetalhes'>";
			echo						$fnDetalhe->getInfo('registro','nome_sistema');
			echo	"				</div>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"			<legend class='legendaExibicao'>Senha de Usuário</legend>";
			echo 	"				<div class='textoDetalhes'>";
			echo						$fnDetalhe->getInfo('registro','senha_sistema');
			echo	"				</div>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"			<legend class='legendaExibicao'>Email do Colaborador</legend>";
			echo 	"				<div class='textoDetalhes'>";
			echo						strtolower($fnDetalhe->getInfo('registro','nome_email'));
			echo	"				</div>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"			<legend class='legendaExibicao'>Senha do Email</legend>";
			echo 	"				<div class='textoDetalhes'>";
			echo						$fnDetalhe->getInfo('registro','senha_email');
			echo	"				</div>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"			<legend class='legendaExibicao'>Setor Destinado</legend>";
			echo 	"				<div class='textoDetalhes'>";
			echo						$fnDetalhe->getInfo('registro','nome_setor');
			echo	"				</div>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"			<legend class='legendaExibicao'>Contratação</legend>";
			echo 	"				<div class='textoDetalhes'>";
			if($fnDetalhe->getInfo('registro','estado') == 1){
				echo "					<label class='labelAtivo'>Ativo</label>";
			}else{
				echo "					<label class='labelInativo'>Inativo</label>";
			}
			echo	"				</div>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalheObs'>";
			echo	"			<legend class='legendaExibicao'>Observações</legend>";
			echo						$fnDetalhe->getInfo('registro','obs');
			echo	"			</fieldset>";
			echo	"		</fieldset>";
			echo	"</div>";
			unset($fnDetalhe);
		}
	}else{
		
		echo	"<div class='formRegistros'>";			
		echo	"	<fieldset class='fieldDetalheBase'>";
		echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
		echo	"			Acesso negado, favor entrar em contato com o administrador.";
		echo	"		</fieldset>";
		echo	"</div>";

	}

	unset($estadoPermissao);
	
break;

case 'editar':
	
	$estadoPermissao = new Prevent($_SESSION['prevent']);
	if($estadoPermissao->permissoAtiva('2') == true){

		
		if(isset($_POST['gravar']) != null){
			
			$fnEditar = new FncCadastros($_POST['opc']);
			if(!$fnEditar->editarCadastro($_POST['nomeC'],$_POST['cpf'],$_POST['nomeS'],$_POST['senhaS'],$_POST['email'],$_POST['senhaE'],$_POST['setor'],$_POST['estado'],$_POST['obs'])){
				
				echo	"<div class='formRegistros'>";			
				echo	"	<fieldset class='fieldDetalheBase'>";
				echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
				echo	"			Registro alterado!";
				echo	"		</fieldset>";
				echo	"</div>";	
				
			}else{
				
				echo	"<div class='formRegistros'>";			
				echo	"	<fieldset class='fieldDetalheBase'>";
				echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
				echo	"			Alteração negada, verificar os itens que não foram alterados!";
				echo	"		</fieldset>";
				echo	"</div>";	
			}
			
			return 0;
		}
		
		$fnDetalhe = new FncCadastros($_POST['opc']);
		if(!$fnDetalhe->exibirCadastro()){
			
			$registrarAcessoObj = new Prevent('N');
			if($registrarAcessoObj->registroAcesso($_POST['opc'],$fnDetalhe->getInfo('registro','nome').", aberto para edição",'Visualização')){
				echo "Falha ao salvar histrico.";
			}
			unset($registrarAcessoObj);	
			echo	"<div class='formRegistros'>";			
			echo  	"	<form method='post' action='fnc_cadastros.php' target='frameresultados' name='editUser'>";
			echo	"		<input type='hidden' name='gravar' value='save'>";	
			echo	"		<input type='hidden' name='opc' value='".$_POST['opc']."'>";	
			echo	"		<input type='hidden' name='funcao' value='editar'>";	
			echo	"		<fieldset class='fieldDetalheBase'>";
			echo	"		<legend class='legendaExibicao'>Editando o Cadastro:</legend>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"				<legend class='legendaExibicao'>Nome Completo do Colaborador</legend>";
			echo 	"				<div class='textoDetalhes'>";
			echo	"					<input type='text' class='boxFormNovo' name='nomeC' placeholder='Nome completo' value='".ucwords(strtolower($fnDetalhe->getInfo('registro','nome')))."'>";
			echo	"				</div>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"			<legend class='legendaExibicao'>CPF do Colaborador</legend>";
			echo 	"				<div class='textoDetalhes'>";
			echo	"					<input type='text' class='boxFormNovo' name='cpf' placeholder='CPF' value='".$fnDetalhe->getInfo('registro','cpf')."'>";
			echo	"				</div>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"			<legend class='legendaExibicao'>Nome do Usuário de Sistema</legend>";
			echo 	"				<div class='textoDetalhes'>";
			echo	"					<input type='text' class='boxFormNovo' name='nomeS' placeholder='Usuário de Sistema' value='".$fnDetalhe->getInfo('registro','nome_sistema')."'>";
			echo	"				</div>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"			<legend class='legendaExibicao'>Senha de Usuário</legend>";
			echo 	"				<div class='textoDetalhes'>";
			echo	"				<input type='text' class='boxFormNovo' name='senhaS' placeholder='Senha de Usuário' value='".$fnDetalhe->getInfo('registro','senha_sistema')."'>";
			echo	"				</div>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"			<legend class='legendaExibicao'>Email do Colaborador</legend>";
			echo 	"				<div class='textoDetalhes'>";
			echo	"					<input type='email' class='boxFormNovo' name='email' placeholder='Conta de Email' value='".strtolower($fnDetalhe->getInfo('registro','nome_email'))."'>";
			echo	"				</div>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"			<legend class='legendaExibicao'>Senha do Email</legend>";
			echo 	"				<div class='textoDetalhes'>";
			echo	"				<input type='text' class='boxFormNovo' name='senhaE' placeholder='Senha do Email' value='".$fnDetalhe->getInfo('registro','senha_email')."'>";	
			echo	"				</div>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"			<legend class='legendaExibicao'>Setor Destinado</legend>";
			echo	"		    	<select class='boxFormNovo' name='setor'>";
			echo 	"			<option value='".$fnDetalhe->getInfo('registro','setor')."'>".$fnDetalhe->getInfo('registro','nome_setor')."</option>";	
			$setorObj = new AcoesBD('setor');
			$setorObj->setInfo('todos','selecionar');
			$setorObj->setInfo('','dado');
			
			if($setorObj->ler()){
						
				foreach($setorObj->ler() as $reg){
					
					if ($fnDetalhe->getInfo('registro','nome_setor') != $reg['nome_setor']){
						echo 	"	<option value=".$reg['id_setor'].">".$reg['nome_setor']."</option>";
					}
				}
			}
			
			unset($setorObj);
			echo	"				</select>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalhe'>";
			echo	"			<legend class='legendaExibicao'>Contratação</legend>";
			echo	"		    	<select class='boxFormNovo' name='estado'>";
			if($fnDetalhe->getInfo('registro','estado') == 1){
				echo 	"			<option value='1'>Ativo</option>";
				echo 	"			<option value='0'>Inativo</option>";
			}else{
				echo 	"			<option value='0'>Inativo</option>";
				echo 	"			<option value='1'>Ativo</option>";
			}
			echo	"				</select>";
			echo	"			</fieldset>";
			echo	"			<fieldset class='fieldDetalheObs'>";
			echo	"			<legend class='legendaExibicao'>Observações</legend>";
			echo	"				<textarea name='obs' rows='2' cols='60' class='boxFormNovo' placeholder='Informações Complementares'>".$fnDetalhe->getInfo('registro','obs')."</textarea>";	
			echo	"			</fieldset>";		
			echo	"			<div class='botaoGravar'>";
			echo	"				<a href=\"javascript:editUser.submit()\" >Gravar</a>";
			echo	"			</div>";
			echo	"		</fieldset>";
			echo	"	</form>";
			echo	"</div>";	
		}
	}else{
		
		echo	"<div class='formRegistros'>";			
		echo	"	<fieldset class='fieldDetalheBase'>";
		echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
		echo	"			Acesso negado, favor entrar em contato com o administrador.";
		echo	"		</fieldset>";
		echo	"</div>";
		
	}
	
	unset($estadoPermissao);

break;

case 'excluir':
	
	$estadoPermissao = new Prevent($_SESSION['prevent']);
	if($estadoPermissao->permissoAtiva('3') == true){
		if(isset($_POST['confirma']) != null){
			$fnExcluir = new FncCadastros($_POST['opc']);
			if(!$fnExcluir->excluirCadastro()){
				
				echo	"<div class='formRegistros'>";			
				echo	"	<fieldset class='fieldDetalheBase'>";
				echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
				echo	"			Registro excluido!";
				echo	"		</fieldset>";
				echo	"</div>";	
								
			}else{
				
				echo	"<div class='formRegistros'>";			
				echo	"	<fieldset class='fieldDetalheBase'>";
				echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
				echo	"			Falha na exclusão!";
				echo	"		</fieldset>";
				echo	"</div>";	
			
			}
		}else{

			echo	"<div class='formRegistros'>";			
			echo	"	<fieldset class='fieldDetalheBase'>";
			echo	"		<legend class='legendaExibicao'>Confirmar Exclusão</legend>";
			echo 	"		<form method='post' action='fnc_cadastros.php' target='frameresultados' id='confirmaExcluir'>";
			echo 	"			<input type='hidden' name='funcao' value='excluir'>";	
			echo 	"			<input type='hidden' name='opc' value='".$_POST['opc']."'>";
			echo 	"			<input type='hidden' name='confirma' value='ok'>";
			echo	"			Cadastro: ".$_POST['nome'].".<br>";
			echo	"			Tem certeza que deseja excluir o registro?";
			echo 	"			<div class='botaoGravar'>";
			echo 	"				<a href=\"javascript:document.getElementById('confirmaExcluir').submit()\" >Sim</a>";
			echo 	"			</div>";
			echo 	"		</form>";
			echo	"	</fieldset>";
			echo	"</div>";	
				
		}
	}else{
		
		echo	"<div class='formRegistros'>";			
		echo	"	<fieldset class='fieldDetalheBase'>";
		echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
		echo	"			Acesso negado, favor entrar em contato com o administrador.";
		echo	"		</fieldset>";
		echo	"</div>";
		
	}
	
	unset($estadoPermissao);
	
break;

case 'permitir':
		
	$verPermissaoObj = new Prevent($_SESSION['prevent']);
	if($verPermissaoObj->permissoAtiva('5') == true){
		
		if(isset($_POST['gravar']) != false){
			//Zerando as permissões vazias
			for($z=1;$z<=6;$z++){
			
				if(isset($_POST['pr'.$z]) == null){
					$_POST['pr'.$z] = 0;
				}
			
			}
			
			$acessoObj = new FncCadastros(array($_POST['pr1'],$_POST['pr2'],$_POST['pr3'],$_POST['pr4'],$_POST['pr5'],$_POST['pr6']));
			if(!$acessoObj->editarPermissao($_POST['opc'])){
		
				echo	"<div class='formRegistros'>";			
				echo	"	<fieldset class='fieldDetalheBase'>";
				echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
				echo	"			Permissões de acesso modificadas!";
				echo	"		</fieldset>";
				echo	"</div>";	
				return 0 ;
			}
			
		}
		
		$estadoPermissao = new Prevent($_POST['permisso']);	
		
		echo	"<div class='formRegistros'>";			
		echo  	"	<form method='post' action='fnc_cadastros.php' target='frameresultados' name='permUser'>";
		echo	"		<fieldset class='fieldDetalheBase'>";
		echo	"			Cadastro: ".$_POST['nome'].".<br>";
		echo	"			<legend class='legendaExibicao'>Permissões do Sistema</legend><br>";
		echo	"				<input type='hidden' name='gravar' value='save'>";	
		echo	"				<input type='hidden' name='opc' value='".$_POST['opc']."'>";	
		echo	"				<input type='hidden' name='funcao' value='permitir'>";	
		echo	"				<div class='cBox'>";
		echo	"				<input type='checkbox'";
		if($estadoPermissao->permissoAtiva('1') == true){
			echo "checked";
		}
		echo	" name='pr1' id='pes' value='1'>";
		echo	"					<label for='pes' >Pesquisar</label>";
		echo	"				</div>";	
		echo	"				<div class='cBox'>";
		echo	"				<input type='checkbox' ";
		if($estadoPermissao->permissoAtiva('2') == true){
			echo "checked";
		}
		echo	" name='pr2' id='cad' value='3'>";
		echo	"					<label for='cad'>Gerenciar Cadastro</label>";	
		echo	"				</div>";	
		echo	"				<div class='cBox'>";
		echo	"				<input type='checkbox' ";
		if($estadoPermissao->permissoAtiva('3') == true){
			echo "checked";
		}
		echo	" name='pr3' id='exc' value='5'>";
		echo	"					<label for='exc'>Excluir Cadastro</label>";
		echo	"				</div>";	
		echo	"				<div class='cBox'>";
		echo	"				<input type='checkbox' ";
		if($estadoPermissao->permissoAtiva('4') == true){
			echo "checked";
		}
		echo	" name='pr4' id='set' value='1'>";
		echo	"					<label for='set'>Gerenciar setores</label>";	
		echo	"				</div>";	
		echo	"				<div class='cBox'>";
		echo	"				<input type='checkbox' ";
		if($estadoPermissao->permissoAtiva('5') == true){
			echo "checked";
		}
		echo	" name='pr5' id='per' value='3'>";
		echo	"					<label for='per'>Alterar Permissões</label>";	
		echo	"				</div>";	
		echo	"				<div class='cBox'>";
		echo	"				<input type='checkbox' ";
		if($estadoPermissao->permissoAtiva('6') == true){
			echo "checked";
		}
		echo	" name='pr6' id='his' value='5'>";
		echo	"					<label for='his' >Visualizar Histórico</label>";	
		echo	"				</div>";	
		echo	"				<div class='botaoGravar'>";
		echo	"					<a href=\"javascript:permUser.submit()\" >Gravar Permissões</a>";
		echo	"				</div>";
		echo	"		</fieldset>";
		echo	"	</form>";
		echo	"</div>";		
		unset($estadoPermissao);
	
	}else{
		
		echo	"<div class='formRegistros'>";			
		echo	"	<fieldset class='fieldDetalheBase'>";
		echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
		echo	"			Acesso negado, favor entrar em contato com o administrador.";
		echo	"		</fieldset>";
		echo	"</div>";
		
	}
	
	unset($verPermissaoObj);

break;

case 'historico':
	
	$estadoPermissao = new Prevent($_SESSION['prevent']);
	if($estadoPermissao->permissoAtiva('6') == true){
		
		if (!isset($_POST['nome'])){
		
			echo	"		<legend class='legendaExibicao'>Históricos de exclusões:</legend><br>";
		
		}else{
			
			echo	"		<legend class='legendaExibicao'>Históricos de acesso ao cadastro \"".$_POST['nome']."\":</legend><br>";
		
		}
		$historicoObj = new FncCadastros($_POST['opc']);
		if($historicoObj->buscarHistorico()){
			
			echo	"<div class='formRegistros'>";	
			echo	"	<fieldset class='fieldDetalheBase'>";
			echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
			echo	"			Nenhum registro encontrado.";
			echo	"		</fieldset>";
			echo	"</div>";
		
		}	


	}else{
		
		echo	"<div class='formRegistros'>";	
		echo	"	<fieldset class='fieldDetalheBase'>";
		echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
		echo	"			Acesso negado, favor entrar em contato com o administrador.";
		echo	"		</fieldset>";
		echo	"</div>";
	
	}
		
default:

	return 0;	


break;

}

?>
	</body>

</html>
