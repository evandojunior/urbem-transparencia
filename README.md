Portal da Transparência
----------------------------------------------
Ferramenta responsável pela consolidação dos dados obtidos exclusivamente do Urbem.

Requisitos mínimos
----------------------------------------------
1. Necessário NGINX ou APACHE
2. PHP 5.6
3. Extensões do PHP: php-intl, php-gd, php-bcmath
4. PostgreSQL 9.4
5. Composer
6. GIT

Configuração do Host no NGINX
----------------------------------------------
```
server {
    listen 80;

    root /var/www/transparencia/site;
    index index.php;

    server_name transparencia.prod;
    access_log /var/log/nginx/transparencia-access-api.log;
    error_log /var/log/nginx/transparencia-error-api.log;

    location ~* \.(js|jpg|png|css)$ {
        expires 30d;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
        if (-f $request_filename) {
            break;
        }

        rewrite ^/despesa/orgao/pdf$ /index.php?module=mpdf&action=listaBalanceteDespesaOrgao&categoria=despesa&secao=orgao&template=blank2 last;
        rewrite ^/despesa/funcao/pdf$ /index.php?module=mpdf&action=listaBalanceteDespesaFuncao&categoria=despesa&secao=funcao&template=blank2 last;
        rewrite ^/despesa/programa/pdf$ /index.php?module=mpdf&action=listaBalanceteDespesaPrograma&categoria=despesa&secao=programa&template=blank2 last;
        rewrite ^/despesa/projeto/pdf$ /index.php?module=mpdf&action=listaBalanceteDespesaProjeto&categoria=despesa&secao=projeto&template=blank2 last;
        rewrite ^/despesa/recurso/pdf$ /index.php?module=mpdf&action=listaBalanceteDespesaRecurso&categoria=despesa&secao=recurso&template=blank2 last;
        rewrite ^/despesa/credor/pdf$ /index.php?module=mpdf&action=listaBalanceteDespesaCredor&categoria=despesa&secao=credor&template=blank2 last;

        rewrite ^/despesa/categoria/([0-9]+)/natureza/([0-9]+)/elemento/([0-9]+)/empenho/pdf$ /index.php?module=mpdf&action=listaEmpenho&categoria=despesa&secao=categoria&cod_categoria=$1&cod_natureza=$2&cod_elemento=$3&natureza=natureza&elemento=elemento&empenho=empenho&template=blank2 last;
        rewrite ^/despesa/categoria/([0-9]+)/natureza/([0-9]+)/elemento/pdf$ /index.php?module=mpdf&action=listaBalanceteDespesaCategoriaElemento&categoria=despesa&secao=categoria&cod_categoria=$1&cod_natureza=$2&natureza=natureza&elemento=elemento&nivel=elemento&template=blank2 last;
        rewrite ^/despesa/categoria/([0-9]+)/natureza/pdf$ /index.php?module=mpdf&action=listaBalanceteDespesaCategoriaNatureza&categoria=despesa&secao=categoria&cod_categoria=$1&natureza=natureza&nivel=natureza&template=blank2 last;
        rewrite ^/despesa/categoria/pdf$ /index.php?module=mpdf&action=listaBalanceteDespesaCategoria&categoria=despesa&secao=categoria&nivel=categoria&template=blank2 last;
        rewrite ^/despesa/(.*)/empenho/(.*)/pdf$ /index.php?module=mpdf&action=listaEmpenho&categoria=despesa&secao=$1&empenho=empenho&id=$2&template=blank2 last;

        rewrite ^/empenho/([0-9]+)/pdf$ /index.php?module=mpdf&action=detalheEmpenho&categoria=despesa&secao=empenho&cod_empenho=$1&template=blank2 last;

        rewrite ^/compra_licitacao/(.*)/empenho/(.*),(.*),(.*),(.*)/pdf$ /index.php?module=mpdf&action=listaEmpenho&categoria=compra_licitacao&secao=$1&empenho=empenho&cod_entidade=$2&exercicio_entidade=$3&cod=$4&modalidade=$5&template=blank2 last;

        rewrite ^/receita/conta/pdf$ /index.php?module=mpdf&action=listaBalanceteReceitaConta&categoria=receita&secao=conta&template=blank2 last;
        rewrite ^/receita/mes/pdf$ /index.php?module=mpdf&action=listaBalanceteReceitaMes&categoria=receita&secao=mes&template=blank2 last;

        rewrite ^/compra_licitacao/compra/pdf$ /index.php?module=mpdf&action=listaCompra&categoria=compra_licitacao&secao=compra&template=blank2 last;
        rewrite ^/compra_licitacao/licitacao/pdf$ /index.php?module=mpdf&action=listaLicitacao&categoria=compra_licitacao&secao=licitacao&template=blank2 last;

        rewrite ^/quadro_funcional/cargo/pdf$ /index.php?module=mpdf&action=listaCargo&categoria=quadro_funcional&secao=cargo&template=blank2 last;
        rewrite ^/quadro_funcional/servidor/pdf$ /index.php?module=mpdf&action=listaServidor&categoria=quadro_funcional&secao=servidor&template=blank2 last;
        rewrite ^/quadro_funcional/estagiario/pdf$ /index.php?module=mpdf&action=listaEstagiario&categoria=quadro_funcional&secao=estagiario&template=blank2 last;
        rewrite ^/quadro_funcional/cedidoadido/pdf$ /index.php?module=mpdf&action=listaCedidoAdido&categoria=quadro_funcional&secao=cedidoadido&template=blank2 last;
        rewrite ^/quadro_funcional/remuneracao/pdf$ /index.php?module=mpdf&action=listaRemuneracao&categoria=quadro_funcional&secao=remuneracao&template=blank2 last;

        rewrite ^/despesa/orgao$ /index.php?module=site&action=listaBalanceteDespesaOrgao&categoria=despesa&secao=orgao last;
        rewrite ^/despesa/funcao$ /index.php?module=site&action=listaBalanceteDespesaFuncao&categoria=despesa&secao=funcao last;
        rewrite ^/despesa/programa$ /index.php?module=site&action=listaBalanceteDespesaPrograma&categoria=despesa&secao=programa last;
        rewrite ^/despesa/projeto$ /index.php?module=site&action=listaBalanceteDespesaProjeto&categoria=despesa&secao=projeto last;
        rewrite ^/despesa/recurso$ /index.php?module=site&action=listaBalanceteDespesaRecurso&categoria=despesa&secao=recurso last;
        rewrite ^/despesa/credor$ /index.php?module=site&action=listaBalanceteDespesaCredor&categoria=despesa&secao=credor last;

        rewrite ^/despesa/categoria/([0-9]+)/natureza/([0-9]+)/elemento/([0-9]+)/empenho$ /index.php?module=site&action=listaEmpenho&categoria=despesa&secao=categoria&cod_categoria=$1&cod_natureza=$2&cod_elemento=$3&natureza=natureza&elemento=elemento&empenho=empenho last;
        rewrite ^/despesa/categoria/([0-9]+)/natureza/([0-9]+)/elemento$ /index.php?module=site&action=listaBalanceteDespesaCategoriaElemento&categoria=despesa&secao=categoria&cod_categoria=$1&cod_natureza=$2&natureza=natureza&elemento=elemento&nivel=elemento last;
        rewrite ^/despesa/categoria/([0-9]+)/natureza$ /index.php?module=site&action=listaBalanceteDespesaCategoriaNatureza&categoria=despesa&secao=categoria&cod_categoria=$1&natureza=natureza&nivel=natureza last;
        rewrite ^/despesa/categoria$ /index.php?module=site&action=listaBalanceteDespesaCategoria&categoria=despesa&secao=categoria&nivel=categoria last;
        rewrite ^/despesa/empenho/([0-9]+)/item /index.php?module=site&action=listaItem&categoria=despesa&empenho=empenho&cod_empenho=$1 last;

        rewrite ^/despesa/empenho/([0-9]+)/historico$ /index.php?module=site&action=listaHistorico&categoria=despesa&secao=$1&empenho=empenho&cod_empenho=$1 last;
        rewrite ^/despesa/(.*)/empenho/(.*)$ /index.php?module=site&action=listaEmpenho&categoria=despesa&secao=$1&empenho=empenho&id=$2 last;
        rewrite ^/compra_licitacao/(.*)/empenho/(.*),(.*),(.*),(.*)$ /index.php?module=site&action=listaEmpenho&categoria=compra_licitacao&secao=$1&empenho=empenho&cod_entidade=$2&exercicio_entidade=$3&cod=$4&modalidade=$5 last;

        rewrite ^/receita/conta$ /index.php?module=site&action=listaBalanceteReceitaConta&categoria=receita&secao=conta last;
        rewrite ^/receita/mes$ /index.php?module=site&action=listaBalanceteReceitaMes&categoria=receita&secao=mes last;

        rewrite ^/compra_licitacao/compra$ /index.php?module=site&action=listaCompra&categoria=compra_licitacao&secao=compra last;
        rewrite ^/compra_licitacao/licitacao$ /index.php?module=site&action=listaLicitacao&categoria=compra_licitacao&secao=licitacao last;

        rewrite ^/quadro_funcional/cargo$ /index.php?module=site&action=listaCargo&categoria=quadro_funcional&secao=cargo last;
        rewrite ^/quadro_funcional/servidor$ /index.php?module=site&action=listaServidor&categoria=quadro_funcional&secao=servidor last;
        rewrite ^/quadro_funcional/estagiario$ /index.php?module=site&action=listaEstagiario&categoria=quadro_funcional&secao=estagiario last;
        rewrite ^/quadro_funcional/cedidoadido$ /index.php?module=site&action=listaCedidoAdido&categoria=quadro_funcional&secao=cedidoadido last;
        rewrite ^/quadro_funcional/remuneracao$ /index.php?module=site&action=listaRemuneracao&categoria=quadro_funcional&secao=remuneracao last;

        rewrite ^/publicacao/geral$ /index.php?module=site&action=listaPublicacaoGeral last;
        rewrite ^/publicacao/(.*)$ /index.php?module=site&action=listaPublicacao&categoria=$1 last;

        rewrite ^/como-funciona$ /index.php?module=site&action=comoFunciona last;
        rewrite ^/legislacao$ /index.php?module=site&action=legislacao last;
        rewrite ^/contato$ /index.php?module=site&action=contato last;
        rewrite ^/contato/create$ /index.php?module=site&action=createContato last;

        rewrite ^/$ /index.php last;
    }

    location ~ \.php$ {
        # if the file is not there show a error : mynonexistingpage.php -> 404
        try_files $uri =404;

        # pass to the php-fpm server
        fastcgi_pass unix:/run/php/php5.6-fpm.sock;

        # also for fastcgi try index.php
        fastcgi_index  index.php;
        fastcgi_read_timeout 3600;

        # some tweaking
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param SCRIPT_NAME $fastcgi_script_name;
        fastcgi_buffer_size 128k;
        fastcgi_buffers 256 16k;
        fastcgi_busy_buffers_size 256k;
        fastcgi_temp_file_write_size 256k;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}

```
Banco de dados
----------------------------------------------
* Importar aquivo de banco de dados
```sh
1. Descompactar urbem-zerado.sql.tar.gz localizado em https://github.com/tilongevo/banco-zerado-urbem3.0
2. pg_restore -h <hostname> -p <port> -u <user> -d <database> < transparencia-zerado.sql
```

* Instalar dependências do projeto

```$xslt
composer install
```

* Testar projeto
```sh
http://transparencia.prod
```