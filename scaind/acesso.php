<?php

/*
  * Projeto SCAIND - Sistema de Cadastro Independente - v2.0
  * Desenvolvido por Leandro Farias
  * Email: projectserverone@gmail.com
  * Perfil profissional: https://www.linkedin.com/in/leandro-farias-rj-19b09796/
*/

class ConexaoScaind {
	
	private $servidor;
    private $bd;
    private $usuario;
    private $senha;
    protected $link;

    function __construct(){
	
      
        $this->link = null;
	
    }
	
	function __destruct(){
		
		 $this->link = null;
		 
	}
		
	protected function startConection(){
		
		//Parâmetros de acesso ao banco de dados
		$this->servidor = 'localhost';
        $this->bd = 'SCAIND';
        $this->usuario = 'admin';
        $this->senha = 'senha123';
		
        $this->link = mysqli_connect($this->servidor,$this->usuario,$this->senha,$this->bd);

        if(!$this->link){
            echo "Falha na conexão com o banco de dados.";
            exit();
        }else{
            return true;
        }
    }

    protected function stopConection(){
        mysqli_close($this->link);
        unset($this->link);
    }

}

?>
