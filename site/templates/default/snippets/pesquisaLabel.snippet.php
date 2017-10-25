<?php
    if(Sessao::get('cod_entidade') != false){
        $entidade = EntidadeBO::getByEntidade(Sessao::get('cod_entidade'))->nome_entidade;
    } else {
        $entidade = 'Consolidado';
    }
    
    if(Sessao::get('exercicio') != false){
        $exercicio = Sessao::get('exercicio');
    } else {
        //Seta com último exercício disponível na tabela de importações
		$q = new Doctrine_Query();
		$q->select("DISTINCT i.exercicio")
		  ->from("Importacao i")
		  ->orderBy("i.exercicio DESC")
		  ->limit(1);

        $exercicio = $q->fetchOne(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
        //$exercicio = date('Y');
    }
?>
<div class="pesquisa_label">
    Entidade: <?php echo $entidade ?> <br/> Exercício: <?php echo $exercicio ?>
</div>