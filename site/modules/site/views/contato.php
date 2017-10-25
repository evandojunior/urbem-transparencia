<?php Plugin::load('jmask'); ?>

<script type="application/javascript">
    $(document).ready(function(){
//        $("input:text").setMask();
    });
</script>

<div class="header_municipio_selecionado" style="margin-top: -20px">
        <div class="header_municipio_titulo">Município</div>
        <div class="header_municipio_nome"><?php echo Sessao::get('municipio_nome') ?> / <?php echo Sessao::get('uf_sigla') ?></div>
</div>


<?php Load::snippet('header_principal', $snippet); ?>

<section id="fale-conosco" class="row">
    <div class="container">
        <div class="row">

            <div class="col-xs-11 col-lg-3 hide-on-small" style="border-right: 1px solid #ccc;">
                <div class="col-lg-12" style="text-align: right;">
                    <h2>Fale Conosco</h2>
                    <p>Este espaço foi criado especialmente para você entrar e contato conosco.</p>
                    <p>Para dar a sua sugestão, fazer uma crítica ou solicitar informações adicionais basta preencher corretamente os campos abaixo.</p>
                </div>
            </div>

            <div class="col-xs-11 col-lg-8">


                <form name="fale-conosco" method="post" action="<?php echo url('contato/create'); ?>" class="form">
                    <div class="form-element"><label class="form-label"><?php echo $form->getFields('configuracao_id')->getLabel(); ?></label>
                        <div class="form-error"><?php echo $form->getFields('configuracao_id')->getError(); ?></div>
                        <div class="form-input-text"><?php echo $form->getFields('configuracao_id')->render(); ?></div>
                    </div>

                    <div class="form-element"><label class="form-label"><?php echo $form->getFields('assunto')->getLabel(); ?></label>
                        <div class="form-error"><?php echo $form->getFields('assunto')->getError(); ?></div>
                        <div class="form-input-text"><?php echo $form->getFields('assunto')->render(); ?></div>
                    </div>

                    <div class="form-element"><label class="form-label"><?php echo $form->getFields('nome')->getLabel(); ?></label>
                        <div class="form-error"><?php echo $form->getFields('nome')->getError(); ?></div>
                        <div class="form-input-text"><?php echo $form->getFields('nome')->render(); ?></div>
                    </div>

                    <div class="form-multi-element">
                        <div class="form-multi-element-1"><label class="form-label"><?php echo $form->getFields('ddd')->getLabel(); ?></label>
                            <div class="form-error"><?php echo $form->getFields('ddd')->getError(); ?></div>
                            <div class="form-input-text"><?php echo $form->getFields('ddd')->render(); ?></div>
                        </div>

                        <div class="form-multi-element-2"><label class="form-label"><?php echo $form->getFields('telefone')->getLabel(); ?></label>
                            <div class="form-error"><?php echo $form->getFields('telefone')->getError(); ?></div>
                            <div class="form-input-text"><?php echo $form->getFields('telefone')->render(); ?></div>
                        </div>
                    </div>

                    <div class="form-element"><label class="form-label"><?php echo $form->getFields('email')->getLabel(); ?></label>
                        <div class="form-error"><?php echo $form->getFields('email')->getError(); ?></div>
                        <div class="form-input-text"><?php echo $form->getFields('email')->render(); ?></div>
                    </div>

                    <div class="form-element"><label class="form-label"><?php echo $form->getFields('mensagem')->getLabel(); ?></label>
                        <div class="form-error"><?php echo $form->getFields('mensagem')->getError(); ?></div>
                        <div class="form-input-text"><?php echo $form->getFields('mensagem')->render(); ?></div>
                    </div>

                    <div class="form-element">
                        <div class="form-error"><?php echo $form->getFields('captcha')->getError(); ?></div>
                        <div class="form-input-text"><?php echo $form->getFields('captcha')->render(); ?></div>
                    </div>

                    <div class="row">
                        <div class="form-group col-xs-12  col-lg-6 send-btn">
                            <button type="submit" class="btn btn-success btn-lg"><i class="icon-send icon-btn" aria-hidden="true"></i>Enviar formulário</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>

