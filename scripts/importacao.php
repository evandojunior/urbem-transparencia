<?php

if (!isset($_SESSION)) {
    session_start();
};

ini_set('max_execution_time', '0');

# Carrega as variáveis de configuração
require_once __DIR__ . '/../settings.php';

# Carrega as classes do ../core do framework
require_once $GLOBALS['BASE_DIR'] . 'core/sessao.class.php';
require_once $GLOBALS['BASE_DIR'] . 'core/doctrine/lib/Doctrine.php';
require_once $GLOBALS['BASE_DIR'] . 'core/load.class.php';
require_once $GLOBALS['BASE_DIR'] . 'core/email.class.php';
require_once $GLOBALS['BASE_DIR'] . 'core/utils.php';
require_once $GLOBALS['BASE_DIR'] . 'core/pdo.class.php';
require_once $GLOBALS['BASE_DIR'] . 'core/conn.class.php';
require_once $GLOBALS['BASE_DIR'] . 'core/field.class.php';
require_once $GLOBALS['BASE_DIR'] . 'core/validator.class.php';
require_once $GLOBALS['BASE_DIR'] . 'core/model.class.php';
require_once $GLOBALS['BASE_DIR'] . 'core/controller.class.php';
require_once $GLOBALS['BASE_DIR'] . 'core/load.class.php';
require_once $GLOBALS['BASE_DIR'] . 'core/pagination.class.php';


# Registra classes de manipulação com a base de dados
spl_autoload_register(['Doctrine', 'autoload']);
spl_autoload_register(['Doctrine_Core', 'modelsAutoload']);

# Classes responsáveis pela configuração
require_once $GLOBALS['BASE_DIR'] . 'modules/configuracao/configuracao.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/configuracao/configuracao.class.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/configuracao/configuracao.bo.php';

# Classes responsáveis pela tabela de endereços
require_once $GLOBALS['BASE_DIR'] . 'modules/endereco/endereco.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/endereco/endereco.class.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/endereco/endereco.bo.php';

# Carrega classes responsáveis pela geração de log
require_once $GLOBALS['BASE_DIR'] . 'modules/log/log.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/log/log.class.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/log/log.bo.php';

# Carrega classes de importacao
require_once $GLOBALS['BASE_DIR'] . 'modules/importacao/importacao.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/importacao/importacao.class.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/importacao/importacao.bo.php';

# Carrega classes do módulo transparência
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/acao.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/acao.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/acao.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/balancetedespesa.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/balancetedespesa.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/balancetedespesa.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/balancetereceita.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/balancetereceita.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/balancetereceita.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/cargo.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/cargo.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/cargo.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/cedidoadido.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/cedidoadido.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/cedidoadido.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/compra.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/compra.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/compra.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/credor.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/credor.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/credor.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/empenho.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/empenho.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/empenho.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/entidade.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/entidade.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/entidade.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/estagiario.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/estagiario.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/estagiario.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/funcao.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/funcao.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/funcao.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/item.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/item.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/item.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/licitacao.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/licitacao.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/licitacao.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/liquidacao.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/liquidacao.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/liquidacao.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/orgao.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/orgao.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/orgao.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/pagamento.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/pagamento.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/pagamento.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/programa.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/programa.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/programa.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/publicacaoedital.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/publicacaoedital.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/publicacaoedital.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/recurso.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/recurso.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/recurso.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/remuneracao.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/remuneracao.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/remuneracao.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/rubrica.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/rubrica.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/rubrica.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/servidor.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/servidor.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/servidor.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/subfuncao.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/subfuncao.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/subfuncao.class.php';

require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/unidade.base.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/unidade.bo.php';
require_once $GLOBALS['BASE_DIR'] . 'modules/transparencia/unidade.class.php';

$pathRemessas = $GLOBALS['BASE_DIR'] . 'uploads/remessas/';
$pathImportados = $GLOBALS['BASE_DIR'] . 'uploads/importados/';
$pathErros = $GLOBALS['BASE_DIR'] . 'uploads/erros/';

try {
    # Percorre os diretórios do diretório uploads
    $dirs = scandir($GLOBALS['BASE_DIR'] . 'uploads');

    foreach ($dirs as $dir) {
        if (($dir != '.') && ($dir != '..')) {

            # Percorre os arquivos do diretório remessas ../uploads/#1/remessas/
            $listaRemessas = scandir($GLOBALS['BASE_DIR'] . 'uploads/remessas/');

            # Retira o {. , .., .gitignore}
            if (count($listaRemessas) > 3) {

                # Pega último arquivo da remessa (o mais atualizado)
                $remessa = array_pop($listaRemessas);

                # Efetua verificação para só abrir arquivo .zip
                if (strpos($pathRemessas . $remessa, '.zip') !== false) {
                    $zip = new ZipArchive();
                    if ($zip->open($pathRemessas . $remessa) === true) {
                        $zip->extractTo($pathRemessas);
                        $zip->close();
                    } else {
                        throw new Exception('Não foi possível abrir o arquivo ' . $remessa . '<br/>');
                    }
                }

                # Carrega arquivo XML em objeto $configXML
                $configXML = simplexml_load_file($pathRemessas . 'config.xml');

                # Abre conexão com o banco do cliente
                Conn::openConnection(DB);

                # Abre transação com o banco de dados
                $conn = Doctrine_Manager::connection();
                $conn->beginTransaction();

                # Inicia a importação
                $importacao = new Importacao();
                $importacao->setExercicio($configXML->exercicio);
                $importacao->setTimestampGeracao($configXML->timestamp_geracao);
                $importacao->setDataLimiteDado($configXML->data_limite_dado);
                $importacao->setUsuario($configXML->usuario);
                $importacao = ImportacaoBO::create($importacao, $conn);

                AcaoBO::deleteByExercicio($configXML->exercicio, $conn);
                BalanceteDespesaBO::deleteByExercicio($configXML->exercicio, $conn);
                BalanceteReceitaBO::deleteByExercicio($configXML->exercicio, $conn);
                CargoBO::deleteByExercicio($configXML->exercicio, $conn);
                CedidoAdidoBO::deleteByExercicio($configXML->exercicio, $conn);
                CompraBO::deleteByExercicio($configXML->exercicio, $conn);
                CredorBO::deleteByExercicio($configXML->exercicio, $conn);
                EmpenhoBO::deleteByExercicio($configXML->exercicio, $conn);
                EntidadeBO::deleteByExercicio($configXML->exercicio, $conn);
                EstagiarioBO::deleteByExercicio($configXML->exercicio, $conn);
                FuncaoBO::deleteByExercicio($configXML->exercicio, $conn);
                ItemBO::deleteByExercicio($configXML->exercicio, $conn);
                LicitacaoBO::deleteByExercicio($configXML->exercicio, $conn);
                LiquidacaoBO::deleteByExercicio($configXML->exercicio, $conn);
                OrgaoBO::deleteByExercicio($configXML->exercicio, $conn);
                PagamentoBO::deleteByExercicio($configXML->exercicio, $conn);
                ProgramaBO::deleteByExercicio($configXML->exercicio, $conn);
                PublicacaoEditalBO::deleteByExercicio($configXML->exercicio, $conn);
                RecursoBO::deleteByExercicio($configXML->exercicio, $conn);
                RemuneracaoBO::deleteByExercicio($configXML->exercicio, $conn);
                RubricaBO::deleteByExercicio($configXML->exercicio, $conn);
                ServidorBO::deleteByExercicio($configXML->exercicio, $conn);
                SubfuncaoBO::deleteByExercicio($configXML->exercicio, $conn);
                UnidadeBO::deleteByExercicio($configXML->exercicio, $conn);

                $arquivoAtual = '';
                foreach ($configXML->arquivos->arquivo as $arquivo) {
                    $arquivoAtual = $arquivo;
                    # Verifica se o arquivo informado no XML está no arquivo compactado .zip
                    if (file_exists($pathRemessas . $arquivo)) {

                        if (filesize($pathRemessas . $arquivo) > 0) {
                            if ($arquivo == 'ACOES.TXT') {
                                AcaoBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'BALANCETEDESPESA.TXT') {
                                BalanceteDespesaBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'BALANCETERECEITA.TXT') {
                                BalanceteReceitaBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'CARGOS.TXT') {
                                CargoBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'CEDIDOSADIDOS.TXT') {
                                CedidoAdidoBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'COMPRAS.TXT') {
                                CompraBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'CREDOR.TXT') {
                                CredorBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'EMPENHO.TXT') {
                                EmpenhoBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'ENTIDADES.TXT') {
                                EntidadeBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'ESTAGIARIOS.TXT') {
                                EstagiarioBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'FUNCOES.TXT') {
                                FuncaoBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'ITEM.TXT') {
                                ItemBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'LICITACAO.TXT') {
                                LicitacaoBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'LIQUIDACAO.TXT') {
                                LiquidacaoBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'ORGAO.TXT') {
                                OrgaoBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'PAGAMENTO.TXT') {
                                PagamentoBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'PROGRAMA.TXT') {
                                ProgramaBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'PUBLICACAOEDITAL.TXT') {
                                PublicacaoEditalBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'RECURSO.TXT') {
                                RecursoBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'REMUNERACAO.TXT') {
                                RemuneracaoBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'RUBRICA.TXT') {
                                RubricaBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'SERVIDORES.TXT') {
                                ServidorBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'SUBFUNCOES.TXT') {
                                SubfuncaoBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }

                            if ($arquivo == 'UNIDADES.TXT') {
                                UnidadeBO::import($pathRemessas . $arquivo, $importacao->getId(), $conn);
                            }
                        }
                    } else {
                        throw new Exception('Arquivo <b>' . $pathRemessas . $arquivo . '</b> NÃO encontrado.<br/>');
                    }
                } # Foreach que percorre arquivos da remessa

                $conn->commit();

                # Efetua cópia de segurança do arquivo para o diretório importados
                copy($pathRemessas . $remessa, $pathImportados . $remessa);
                limpaDiretorio($pathRemessas);

            } # If que verifica se há remessa
        } # If que verifica {.,..}
    } # Foreach que lista diretórios

} catch (Doctrine_Exception $e) {
    $conn->rollback();

    $logFilePath = $pathErros . 'erro.log';
    $previouslyLogContent = '';
    if (file_exists($logFilePath)) {
        $previouslyLogContent = file_get_contents($logFilePath);
    }

    $logContent = sprintf(
        'Arquivo %s: %s %s%s',
        $arquivoAtual,
        date('Y-m-d H:i:s'),
        $e->getMessage() . PHP_EOL,
        $previouslyLogContent
    );

    $logFile = fopen($logFilePath, 'w+');

    fwrite($logFile, $logContent);
    fclose($logFile);

    # Efetua cópia de segurança do arquivo para o diretório de erros
    copy($pathRemessas . $remessa, $pathErros . $remessa);

    $mensagem = "<h2>Erro ao importar remessas no Portal da Transparência.</h2>";
    $mensagem .= "<p>Um erro ocorreu durante a importação da remessa {$remessa} no portal da transparência.</p>";
    $mensagem .= "<p><b>Erro:</b> {$e->getMessage()}</p>";

    $transport = (new Swift_SmtpTransport(SMTP_SERVER, SMTP_PORT, SMTP_SECURITY))
        ->setUsername(SMTP_USERNAME)
        ->setPassword(SMTP_PASSWORD);

    $mailer = new Swift_Mailer($transport);

    $message = new Swift_Message('ERRO - Portal da Transparência');
    $message
        ->setFrom([MAIL_ADMINISTRATOR => $GLOBALS['municipio_nome']])
        ->setTo([MAIL_ADMINISTRATOR => MAIL_TITLE])
        ->setBody($mensagem, 'text/html');

    limpaDiretorio($pathRemessas);

    return false;
} catch (Exception $e) {
    $logFilePath = $pathErros . 'erro.log';
    $previouslyLogContent = '';
    if (file_exists($logFilePath)) {
        $previouslyLogContent = file_get_contents($logFilePath);
    }

    $logContent = sprintf(
        'Arquivo %s: %s %s%s',
        $arquivoAtual,
        date('Y-m-d H:i:s'),
        $e->getMessage() . PHP_EOL,
        $previouslyLogContent
    );

    $logFile = fopen($logFilePath, 'w+');

    fwrite($logFile, $logContent);
    fclose($logFile);

    $mensagem = "<h2>Erro ao importar remessas no Portal da Transparência.</h2>";
    $mensagem .= "<p>Um erro ocorreu durante a importação da remessa {$remessa} no portal da transparência.</p>";
    $mensagem .= "<p><b>Erro:</b> {$e->getMessage()}</p>";

    $transport = (new Swift_SmtpTransport(SMTP_SERVER, SMTP_PORT, SMTP_SECURITY))
        ->setUsername(SMTP_USERNAME)
        ->setPassword(SMTP_PASSWORD);

    $mailer = new Swift_Mailer($transport);

    $message = new Swift_Message('ERRO - Portal da Transparência');
    $message
        ->setFrom([MAIL_ADMINISTRATOR => $GLOBALS['municipio_nome']])
        ->setTo([MAIL_ADMINISTRATOR => MAIL_TITLE])
        ->setBody($mensagem, 'text/html');

    limpaDiretorio($pathRemessas);

    return false;
}

return true;
