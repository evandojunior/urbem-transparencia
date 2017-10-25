<?php 

class FormPesquisa extends Form{

	public function __construct($data=null, $files=null, $args=null){
		
		/********** Consulta **********/
		$query = new Doctrine_Query();
        $query->select("e.*")
              ->from("Entidade e")
			  ->innerJoin("e.ConfiguracaoEntidade e2");
		$lista = $query->execute();
		/**********/
		
		$cod_entidade = new Select();
		$cod_entidade->setName('cod_entidade');
		$cod_entidade->setQueryOptions('cod_entidade', 'nome_entidade', $lista, 'Consolidado');
		$cod_entidade->setStyle(array('width' => '350px'));
		$this->fields['cod_entidade'] = $cod_entidade;
		
		/********** Consulta **********/
		$query = new Doctrine_Query();
        $query->select("DISTINCT i.exercicio")
              ->from("Importacao i")
			  ->orderBy("i.exercicio DESC");
		$lista = $query->execute();
		/**********/
		
		$exercicio = new Select();
		$exercicio->setName('exercicio');
        $exercicio->setQueryOptions('exercicio', 'exercicio', $lista, 'Todos');
        //Seta com último exercício disponível na tabela de importações
		$exercicio->setStyle(array('width'=>'108px'));
		$this->fields['exercicio'] = $exercicio;
        
        parent::__construct($data, $files);
	}
}

class FormPesquisaMes extends FormPesquisa{
	public function __construct($data=null, $files=null){
		$mes = new Select();
		$mes->setName('mes');
		$mes->setOptions(array('janeiro'   => 'Janeiro',
							   'fevereiro' => 'Fevereiro',
							   'marco'     => 'Março',
							   'abril'     => 'Abril',
							   'maio'      => 'Maio',
							   'junho'     => 'Junho',
							   'julho'     => 'Julho',
							   'agosto'    => 'Agosto',
							   'setembro'  => 'Setembro',
							   'outubro'   => 'Outubro',
							   'novembro'  => 'Novembro',
							   'dezembro'  => 'Dezembro'));
		
		$mes->setStyle(array('width'=>'108px'));
		if(isset($_REQUEST['mes'])) {
			$mes->setValue($_REQUEST['mes']);
		} else {
			$ultimoMesImportacao = getMesExtenso(getMesData(ImportacaoBO::getUltimo()->data_limite_dado, 'US'));
			$mes->setValue($ultimoMesImportacao);
		}
		
		$this->fields['mes'] = $mes;
        
        parent::__construct($data, $files);
	}
}

class FormPesquisaNome extends Form{

    public function __construct($data=null, $files=null, $args=null){
        $nome = new Text();
        $nome->setName('nome');
        $nome->setStyle(array('width' => '350px', 'placeholder' => 'Digite o nome que deseja pesquisar'));
        $this->fields['nome'] = $nome;
    
        /********** Consulta **********/
         $query = new Doctrine_Query();
         $query->select("DISTINCT i.exercicio")
               ->from("Importacao i")
               ->orderBy("i.exercicio DESC");
         $lista = $query->execute();
         /**********/
         
        $exercicio = new Select();
        $exercicio->setName('exercicio');
        $exercicio->setQueryOptions('exercicio', 'exercicio', $lista);
        $exercicio->setStyle(array('width'=>'108px'));
        $this->fields['exercicio'] = $exercicio;
        
        parent::__construct($data, $files);
    }
}

class FormPesquisaServidor extends Form{

    public function __construct($data=null, $files=null, $args=null){
        $nome = new Text();
        $nome->setName('nome');
        $nome->setStyle(array('width' => '200px', 'placeholder' => 'Digite o nome que deseja pesquisar'));
        $this->fields['nome'] = $nome;
		
        /********** Consulta **********/
         $query = new Doctrine_Query();
         $query->select("DISTINCT i.exercicio")
               ->from("Importacao i")
               ->orderBy("i.exercicio DESC");
         $lista = $query->execute();
         /**********/		
		
        $exercicio = new Select();
        $exercicio->setName('exercicio');
        $exercicio->setQueryOptions('exercicio', 'exercicio', $lista);
        $exercicio->setStyle(array('width'=>'108px'));
        $this->fields['exercicio'] = $exercicio;		
		
		/********** Consulta **********/
		$query = new Doctrine_Query();
        $query->select("DISTINCT r.mes_ano, SUBSTR(r.mes_ano, 4, 7) as ano, SUBSTR(r.mes_ano, 1, 2) as mes")
              ->from("Remuneracao r")
			  ->orderBy("SUBSTR(r.mes_ano, 4, 7), SUBSTR(r.mes_ano, 1, 2)");
		$lista = $query->execute();
		/**********/
		
		$competencia = new Select();
		$competencia->setName('competencia');
		$competencia->setQueryOptions('mes_ano', 'mes_ano', $lista, 'Todas as competências');
		$competencia->setStyle(array('width' => '180px'));
		$this->fields['competencia'] = $competencia;
		
		$situacao = new Select();
		$situacao->setName('situacao');
		$situacao->setOptions(array('' => 'Situação',
									'Ativo' => 'Ativo',
									'Ativo/Afastado (Motivo Licença)' => 'Ativo/Afastado (Motivo Licença)',
									'Aposentado' => 'Aposentado',
									'Pensionista' => 'Pensionista',
									'Pensionista Encerrado' => 'Pensionista Encerrado',
									'Rescindido' => 'Rescindido'));
		$situacao->setStyle(array('width' => '150px'));
		$this->fields['situacao'] = $situacao;		
        
        parent::__construct($data, $files);
    }
}


class FormPesquisaCompetencia extends Form{

	public function __construct($data=null, $files=null, $args=null){
		
		/********** Consulta **********/
		$query = new Doctrine_Query();
        $query->select("DISTINCT r.mes_ano, SUBSTR(r.mes_ano, 4, 7) as ano, SUBSTR(r.mes_ano, 1, 2) as mes")
              ->from("Remuneracao r")
			  ->orderBy("SUBSTR(r.mes_ano, 4, 7), SUBSTR(r.mes_ano, 1, 2)");
		$lista = $query->execute();
		/**********/
		
		$competencia = new Select();
		$competencia->setName('competencia');
		$competencia->setQueryOptions('mes_ano', 'mes_ano', $lista, 'Todas as competências');
		$competencia->setStyle(array('width' => '350px'));
		$this->fields['competencia'] = $competencia;
		
        parent::__construct($data, $files);
	}
}

class FormPesquisaPublicacao extends Form{

	public function __construct($data=null, $files=null, $args=null){
		
		/********** Consulta **********/
		$query = new Doctrine_Query();
        $query->select("s.*")
              ->from("Secao s");
		$lista = $query->execute();
		/**********/
		
		$secao = new Select();
		$secao->setName('secao');
		$secao->setQueryOptions('id', 'secao', $lista, 'Todas as seções');
		$secao->setStyle(array('width' => '350px'));
		$this->fields['secao'] = $secao;
		
		/********** Consulta **********/
		$query = new Doctrine_Query();
        $query->select("DISTINCT i.exercicio")
              ->from("Importacao i")
			  ->orderBy("i.exercicio DESC");
		$lista = $query->execute();
		/**********/
		
		$exercicio = new Select();
		$exercicio->setName('exercicio');
		$exercicio->setQueryOptions('exercicio', 'exercicio', $lista);
		$exercicio->setStyle(array('width'=>'108px'));
		$this->fields['exercicio'] = $exercicio;
        
        parent::__construct($data, $files);
	}
}


