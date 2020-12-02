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

require_once "crud_scaind.php";

class Prevent{

	private $permissoes,$contagem,$usuario,$nomeAnalise,$idAcao,$log,$registros,$dataHora,$sessaoAtual;
	private $arrayPermissoes = array();
	private $arrayDados = array();
	
	function __construct($perm){
		
		$this->permissoes = $perm;
		$this->conferePrevent();
		
		if (is_numeric($this->permissoes) == true){
					
			$this->montarPermissao();
		
		}
	}
	
	private function conferePrevent(){
	
		//Atualizar o estado atual das permissões.
		if($_SESSION['usuario'] != 'ROOT SCAIND'){

			$permisso = new AcoesBD('cadastros');
			$permisso->setInfo('nome_sistema LIKE','coluna');
			$permisso->setInfo($_SESSION['usuario'],'dado');
			$permisso->setInfo('especificado','selecionar');
			$this->arrayDados = array('permisso');
			$permisso->setInfo($this->arrayDados,'colunas');
			
			foreach($permisso->ler() as $this->registros){
				
				if($this->registros['permisso'] != $_SESSION['prevent']){
				
					unset($_SESSION['prevent']);
					$_SESSION['prevent'] = $this->registros['permisso'];
					
				}
			}
			
		}
		
		unset($this->arrayDados,$permisso,$this->registros);
	
		//verificar se a sessao atual esta ativa, caso nao seja a atual derruba.
		$sessionObj = new AcoesBD('sessao');
		$sessionObj->setInfo($_SESSION['usuario'],'dado');
		$sessionObj->setInfo('nome_sistema LIKE','coluna');
		$sessionObj->setInfo('especificado','selecionar');
		$this->arrayDados = array('sessao');
		$sessionObj->setInfo($this->arrayDados,'colunas');
		$this->sessaoAtual = session_id();
					
		if ($sessionObj->ler()){
							
			foreach($sessionObj->ler() as $this->registros){
								
				if ($this->registros['sessao'] != $this->sessaoAtual){
					
					$this->destroySession();
					
				}
			}
		}else{
			
			$this->destroySession();
		}
	
		return 0;
		
	}
	
	private function destroySession(){
		
		SESSION_DESTROY();
		$this->permissoes = '00';
		echo	"<div class='formRegistros'>";			
		echo	"	<fieldset class='fieldDetalheBase'>";
		echo	"		<legend class='legendaExibicao'>Mensagem</legend>";
		echo	"			Acesso negado, sessão aberta em outro navegador.";
		echo	"		</fieldset>";
		echo	"</div>";
	
	}
	
	public function permissoAtiva($id){
		
		//retorna as permissões ativas para o código de exibição
		return $this->arrayPermissoes[$id];
		
	}
		
	private function montarPermissao(){
		
		//identificador de permissões ativas ou não.
		$this->contagem = 1;
		for($m = 1;$m <= strlen($this->permissoes);$m++){
		
			$valorCod=substr($this->permissoes,($m-1),$m);
			for($n = 1;$n <= 3;$n++){
				
				if($valorCod != 0){
					
					if($valorCod != 9){
						
						if($valorCod != 8){
							
							if($valorCod != 6){
								
								if($valorCod != 4){
									
									
									if($valorCod == 1 AND $n == 1){
										
										$this->arrayPermissoes[$this->contagem] = true;
										
									}elseif($valorCod == 3 AND $n == 2){
										
										$this->arrayPermissoes[$this->contagem] = true;
										
									}elseif($valorCod == 5 AND $n == 3){
									
										$this->arrayPermissoes[$this->contagem] = true;
										
									}else{
										
										$this->arrayPermissoes[$this->contagem] = false;
								
									}
									$this->contagem++;
									
								}elseif($n != 3){
									
									$this->arrayPermissoes[$this->contagem] = true;
									$this->contagem++;
									
								}else{
									
									$this->arrayPermissoes[$this->contagem] = false;
									$this->contagem++;
								
								}
						
							}elseif($n != 2){
								
								$this->arrayPermissoes[$this->contagem] = true;
								$this->contagem++;
								
							}else{
								
								$this->arrayPermissoes[$this->contagem] = false;
								$this->contagem++;
							
							}
						
						}elseif($n != 1){
							
							$this->arrayPermissoes[$this->contagem] = true;
							$this->contagem++;
							
						}else{
							$this->arrayPermissoes[$this->contagem] = false;
							$this->contagem++;
						}
					}else{
						
						$this->arrayPermissoes[$this->contagem] = true;
						$this->contagem++;
						
					}
					
				}else{
					
					$this->arrayPermissoes[$this->contagem] = false;
					$this->contagem++;
					
				}
				
			}
			
		}
	
	}
	
	public function registroAcesso($idUser,$user,$acao){
		
		//registrador de acesso a dados 
		$this->usuario = $idUser;
		$this->nomeAnalise = $user;	
		$idAcaoObj = new AcoesBD('acaolog');
		$idAcaoObj->setInfo($acao,'dado');
		$idAcaoObj->setInfo('nome_acao LIKE','coluna');
		$idAcaoObj->setInfo('todos','selecionar');
		foreach($idAcaoObj->ler() as $this->registros){
		
			$this->idAcao = $this->registros['id_acao'];
		}
		unset($this->registros,$idAcaoObj);
		
		switch($acao){
		
			case 'Visualização':
				
				$guardarRegistroObj = new AcoesBD('log');
				$this->arrayDados = array('id_usuario','horario','acao','nome_usuario','log');
				$guardarRegistroObj->setInfo($this->arrayDados,'colunas');
				unset($this->arrayDados);
				$this->log = "Detalhes do nome ".$this->nomeAnalise.".";
				$this->dataHora = date("Y-m-d H:i:s");
				$this->arrayDados = array($this->usuario,$this->dataHora,$this->idAcao,$_SESSION['usuario'],$this->log);
				$guardarRegistroObj->setInfo($this->arrayDados,'dados');
				
				if ($guardarRegistroObj->inserir()){
					
					unset($this->arrayDados,$guardarRegistroObj,$this->log,$this->idAcao,$this->usuario,$this->nomeAnalise);
					return 0;
				}
				
				unset($this->arrayDados,$guardarRegistroObj,$this->log,$this->idAcao,$this->usuario,$this->nomeAnalise);
				return 1;
		
			break;
			
			case 'Criação de Cadastro':
				
				unset($this->usuario);
				$buscarRegistroObj = new AcoesBD('cadastros');
				$buscarRegistroObj->setInfo($this->nomeAnalise,'dado');
				$buscarRegistroObj->setInfo('nome LIKE','coluna');
				$buscarRegistroObj->setInfo('especificado','selecionar');
				$buscarRegistroObj->setInfo(array('id_usuario'),'colunas');
				
				foreach($buscarRegistroObj->ler() as $this->registros){
					
					$this->usuario = $this->registros['id_usuario'];
					
				}
				unset($this->registros,$buscarRegistroObj);
				
				$guardarRegistroObj = new AcoesBD('log');
				$this->arrayDados = array('id_usuario','horario','acao','nome_usuario','log');
				$guardarRegistroObj->setInfo($this->arrayDados,'colunas');
				unset($this->arrayDados);
				$this->log = "Cadastro criado com o nome ".$this->nomeAnalise.".";
				$this->dataHora = date("Y-m-d H:i:s");
				$this->arrayDados = array($this->usuario,$this->dataHora,$this->idAcao,$_SESSION['usuario'],$this->log);
				$guardarRegistroObj->setInfo($this->arrayDados,'dados');
				
				if ($guardarRegistroObj->inserir()){
					
					unset($this->arrayDados,$guardarRegistroObj,$this->log,$this->idAcao,$this->usuario,$this->nomeAnalise);
					return 0;
				}
				
				unset($this->arrayDados,$guardarRegistroObj,$this->log,$this->idAcao,$this->usuario,$this->nomeAnalise);
				return 1;
		
			break;
			
			case 'Criação de Setor':
				
				unset($this->usuario);
				$buscarRegistroObj = new AcoesBD('setor');
				$buscarRegistroObj->setInfo($this->nomeAnalise,'dado');
				$buscarRegistroObj->setInfo('nome_setor LIKE','coluna');
				$buscarRegistroObj->setInfo('especificado','selecionar');
				$buscarRegistroObj->setInfo(array('id_setor'),'colunas');
				
				foreach($buscarRegistroObj->ler() as $this->registros){
					
					$this->usuario = $this->registros['id_setor'];
					
				}
				unset($this->registros,$buscarRegistroObj);
				
				$guardarRegistroObj = new AcoesBD('log');
				$this->arrayDados = array('id_usuario','horario','acao','nome_usuario','log');
				$guardarRegistroObj->setInfo($this->arrayDados,'colunas');
				unset($this->arrayDados);
				$this->log = "Setor criado com o nome ".$this->nomeAnalise.".";
				$this->dataHora = date("Y-m-d H:i:s");
				$this->arrayDados = array($this->usuario,$this->dataHora,$this->idAcao,$_SESSION['usuario'],$this->log);
				$guardarRegistroObj->setInfo($this->arrayDados,'dados');
				
				if ($guardarRegistroObj->inserir()){
					
					unset($this->arrayDados,$guardarRegistroObj,$this->log,$this->idAcao,$this->usuario,$this->nomeAnalise);
					return 0;
				}
				
				unset($this->arrayDados,$guardarRegistroObj,$this->log,$this->idAcao,$this->usuario,$this->nomeAnalise);
				return 1;
		
			break;
			
			case 'Edição de Cadastro':
				
				$guardarRegistroObj = new AcoesBD('log');
				$this->arrayDados = array('id_usuario','horario','acao','nome_usuario','log');
				$guardarRegistroObj->setInfo($this->arrayDados,'colunas');
				unset($this->arrayDados);
				$this->log = "Alteração registro: ".$this->nomeAnalise.".";
				$this->dataHora = date("Y-m-d H:i:s");
				$this->arrayDados = array($this->usuario,$this->dataHora,$this->idAcao,$_SESSION['usuario'],$this->log);
				$guardarRegistroObj->setInfo($this->arrayDados,'dados');
				
				if ($guardarRegistroObj->inserir()){
					
					unset($this->arrayDados,$guardarRegistroObj,$this->log,$this->idAcao,$this->usuario,$this->nomeAnalise);
					return 0;
				}
				
				unset($this->arrayDados,$guardarRegistroObj,$this->log,$this->idAcao,$this->usuario,$this->nomeAnalise);
				return 1;
		
			break;
			
			case 'Exclusão de Cadastro':
				
				unset($this->nomeAnalise);
				$buscarRegistroObj = new AcoesBD('cadastros');
				$buscarRegistroObj->setInfo($this->usuario,'dado');
				$buscarRegistroObj->setInfo('id_usuario LIKE','coluna');
				$buscarRegistroObj->setInfo('especificado','selecionar');
				$buscarRegistroObj->setInfo(array('nome','cpf','nome_sistema','nome_email','obs'),'colunas');
				
				foreach($buscarRegistroObj->ler() as $this->registros){
					
					$this->nomeAnalise = $this->registros['nome'].", CPF = ".$this->registros['cpf'].", Login = ".$this->registros['nome_sistema'].", Email = ".$this->registros['nome_email'].", Observações = ".$this->registros['obs'].".";
					
				}
				unset($this->registros,$buscarRegistroObj);
				
				$guardarRegistroObj = new AcoesBD('log');
				$this->arrayDados = array('id_usuario','horario','acao','nome_usuario','log');
				$guardarRegistroObj->setInfo($this->arrayDados,'colunas');
				unset($this->arrayDados);
				$this->log = "Solicitado a exclusão do cadastro com o Nome = ".$this->nomeAnalise.".";
				$this->dataHora = date("Y-m-d H:i:s");
				$this->arrayDados = array($this->usuario,$this->dataHora,$this->idAcao,$_SESSION['usuario'],$this->log);
				$guardarRegistroObj->setInfo($this->arrayDados,'dados');
				
				if ($guardarRegistroObj->inserir()){
					
					unset($this->arrayDados,$guardarRegistroObj,$this->log,$this->idAcao,$this->usuario,$this->nomeAnalise);
					return 0;
				}
				
				unset($this->arrayDados,$guardarRegistroObj,$this->log,$this->idAcao,$this->usuario,$this->nomeAnalise);
				return 1;
		
			break;
			
			case 'Edição de Permissões':
				
				$guardarRegistroObj = new AcoesBD('log');
				$this->arrayDados = array('id_usuario','horario','acao','nome_usuario','log');
				$guardarRegistroObj->setInfo($this->arrayDados,'colunas');
				unset($this->arrayDados);
				$this->log = "Alteração de permissão.";
				$this->dataHora = date("Y-m-d H:i:s");
				$this->arrayDados = array($this->usuario,$this->dataHora,$this->idAcao,$_SESSION['usuario'],$this->log);
				$guardarRegistroObj->setInfo($this->arrayDados,'dados');
				
				if ($guardarRegistroObj->inserir()){
					
					unset($this->arrayDados,$guardarRegistroObj,$this->log,$this->idAcao,$this->usuario,$this->nomeAnalise);
					return 0;
				}
				
				unset($this->arrayDados,$guardarRegistroObj,$this->log,$this->idAcao,$this->usuario,$this->nomeAnalise);
				return 1;
		
			break;
		}
	}
}
	

?>
