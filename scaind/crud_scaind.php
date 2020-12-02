<?php

/*
  * Projeto SCAIND - Sistema de Cadastro Independente - v2.0
  * Desenvolvido por Leandro Farias
  * Email: projectserverone@gmail.com
  * Perfil profissional: https://www.linkedin.com/in/leandro-farias-rj-19b09796/
*/

ini_set('session.save_path', '/sessao');

require_once "acesso.php";

class AcoesBD extends ConexaoScaind{

    private $colunas = array();
    private $dados = array();
	private $colunaDados = array();
    private $query;
    private $retorno;
    private $item;
    private $selecionar;
	private $tabela;
	private $instrucao = null;
	private $coluna = null;
	private $onde;
	private $dado = null;
    private $ordem = null;
    private $tipoOrdem = null;
    private $recebe;
    private $busca,$qntReg;

    function __construct($tab){
		$this->tabela = $tab;
		
	}
	
	function __destruct(){
		
	}
		
    public function setInfo($info,$nomeVar){
		
		$this->$nomeVar = $info;
		
    }

    public function getInfo($info){

        return $this->$info;
    }
		
    public function ler(){
				
        $this->query = 'select ';
        if($this->selecionar == 'todos'){
			$this->query .= '*';
		}else{
			$z=1;
			foreach($this->colunas as $this->item){
				if($z == 1){
					$this->query .= '`'.$this->item.'`';
					$z++;	
				}else{
					$this->query .= ',`'.$this->item.'`';
				}
			}
		}
        $this->query .= ' from ';
        $this->query .= $this->tabela;
        
        if(isset($this->instrucao) != null){
			
			$this->query .= $this->instrucao;
		
		}

        if($this->coluna != null && $this->dado != null){

			$this->query .= ' where '.$this->coluna.' \''.$this->dado.'\'';
			            
			foreach ($this->colunaDados as $andCol=>$andDado){
				
				if (substr($andDado,-1) != ')'){
					
					$this->query .= ' '.$andCol.' \''.$andDado.'\'';
				
				}else{
				
					$this->query .= ' '.$andCol.' \''.$andDado;
				
				}
 			}
		}

        if (isset($this->ordem) != null && isset($this->tipoOrdem) != null){
             $this->query .= ' ORDER BY '.$this->ordem.' DESC';
        }elseif (isset($this->ordem) != null && isset($this->tipoOrdem) == null){
		     $this->query .= ' ORDER BY '.$this->ordem.' ASC';
		}
		$this->query .= ';';

		
        parent::startConection();
        $this->busca = mysqli_query($this->link,"$this->query");
        $this->qntReg = mysqli_num_rows($this->busca);
       
        if ($this->qntReg > 0){

            for ($a=0;$a<$this->qntReg;$a++){

                $linha = mysqli_fetch_array($this->busca);
                $chave = array_keys($linha);

                for ($b=0;$b<count($chave);$b++){
        			if (!is_numeric($chave[$b])){
        				$this->retorno[$a][$chave[$b]] = $linha[$chave[$b]];
        			}
                }
            }

        }else{

            parent::stopConection();
            return false;
        }

        parent::stopConection();
        return $this->retorno;
    }

    public function inserir(){

      $this->query = "insert into ".$this->tabela."(";

      $linha1 = 1;
      foreach ($this->colunas as $key => $entidade){

          if ($linha1 == 1 ){
              $this->query .= $entidade;
              $linha1++;
          }else{
              $this->query .= ','.$entidade;
          }

      }

      $this->query .= ') values(';

      $linha1 = 1;
      foreach ($this->dados as $key => $atributo){

          if ($linha1 == 1 ){
              $this->query .= '\''.$atributo.'\'';
              $linha1++;
          }else{
              $this->query .= ',\''.$atributo.'\'';
          }

      }

      $this->query .=');';

      parent::startConection();

      $inserir=mysqli_query($this->link,"$this->query");

      if (!$inserir){
          parent::stopConection();
          return false;
      }

      parent::stopConection();
      return true;

    }

    public function alterar(){

        $this->query = 'update '.$this->tabela;
        $this->query .= ' set '.$this->coluna.'=';

        if ($this->dado == 'NULL'){

          $this->query .= $this->dado.' ';

        }else{

          $this->query .= '\''.$this->dado.'\' ';

        }
        $this->query .= 'where '.$this->onde.'=\'';
        $this->query .= $this->item.'\'';

        parent::startConection();
        $alterar=mysqli_query($this->link,"$this->query");
        if (!$alterar){
            parent::stopConection();
            return false;
        }else{
            parent::stopConection();
            return true;
        }
    }

    public function deletar(){

        $this->query = "delete from ".$this->tabela." ";
        $this->query .= "where ".$this->onde."='";
        $this->query .= $this->dado."'";
		
        parent::startConection();
		
        $excluir=mysqli_query($this->link,"$this->query");
        if (!$excluir){
            
            parent::stopConection();
            return false;
            
        }

        parent::stopConection();
        return true;

    }

}

?>
