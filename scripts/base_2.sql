--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: samlink; Type: SCHEMA; Schema: -; Owner: urbem
--

CREATE SCHEMA samlink;


ALTER SCHEMA samlink OWNER TO urbem;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = samlink, pg_catalog;

--
-- Name: fn_getsamlinkconnstr(); Type: FUNCTION; Schema: samlink; Owner: urbem
--

CREATE FUNCTION fn_getsamlinkconnstr() RETURNS text
    LANGUAGE plpgsql
    AS $$
declare
   cResult text;
   rParam  record;
   cValue  text;
begin
   cResult := 'host=SAMLINK_HOST port=SAMLINK_PORT dbname=SAMLINK_DBNAME user=SAMLINK_USER password='||quote_literal('SAMLINK_PASSWORD');

   for rParam in
      select  valor,
            upper(parametro) as parametro
      from  administracao.configuracao
      where parametro like 'samlink%'
--    and      exercicio=(
--             select valor
--             from sw_configuracao
--             where parametro='ano_exercicio'
--             order by exercicio desc
--             limit 1
--             )
    loop
      cResult := replace(cResult, rParam.parametro, rParam.valor);
   end loop;

   return cResult;
end;
$$;


ALTER FUNCTION samlink.fn_getsamlinkconnstr() OWNER TO urbem;

--
-- Name: fn_mod11(text); Type: FUNCTION; Schema: samlink; Owner: urbem
--

CREATE FUNCTION fn_mod11(text) RETURNS integer
    LANGUAGE plpgsql
    AS $_$
declare
iBaseinteger;
sValortext;
iSomainteger;
iPesointeger;
iDigitointeger;
iTamanhointeger;
iValorinteger;
begin
iBase    := 9;
iSoma    := 0;
iPeso    := 2;
sValor   := trim(both ' ' from $1);
iTamanho := length(sValor);
while iTamanho > 0 loop
iValor := to_number(substr(sValor, iTamanho, 1), '9')::integer;
iSoma := iSoma + (iValor * iPeso);
if iPeso < iBase then
iPeso := iPeso + 1;
else
iPeso := 2;
end if;
iTamanho := iTamanho - 1;
end loop;
iDigito := 11 - (iSoma%11);
if iDigito > 9 then
iDigito := 0;
end if;
return iDigito;
end;
$_$;


ALTER FUNCTION samlink.fn_mod11(text) OWNER TO urbem;

--
-- Name: fn_samlinkcgm(); Type: FUNCTION; Schema: samlink; Owner: urbem
--

CREATE FUNCTION fn_samlinkcgm() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
    declare
       sConnStr text;
       --
    -- Cria Variaves da Estrutura do CGM do SIAMmax
    --
    -- Obs: Todos estao definidos como text para facilitar a montagem
    --      da clausula sql, seja INSERT ou UPDATE.
    --
    z01_cgccpf text;
    z01_numcgm text;
    z01_nome   text;
    z01_ender  text;
    z01_munic  text;
    z01_uf     text;
    z01_cep    text;
    z01_cadast text;
    z01_telef  text;
    z01_ident  text;
    z01_digito text;
    z01_login  text;
    z01_bairro text;
    z01_incest text;
    z01_telcel text;
    z01_email  text;
    z01_endcon text;
    z01_muncon text;
    z01_baicon text;
    z01_ufcon  text;
    z01_cepcon text;
    z01_telcon text;
    z01_celcon text;
    z01_emailc text;
    --
    sTabela    text;
    sOperac    text;

    tNumero      text;
    tLogradouro  text;
    tComplemento text;
    tEnder       text;
    tTelef       text;
    tTelcel      text;
    tTelcon      text;

    iLoop        Integer;
    iCount       Integer;
    aEnder       Varchar[];
    aAlter       Boolean[];
    bAlter       Boolean;
    vAux         VArchar(200);
    cCaracter    Char(1);

    iNumLinhas  INTEGER := 0;
    iTirar      INTEGER := 0;
 begin
    -- Tabela Alvo e Operacao da Trigger
    sTabela := upper(TG_RELNAME);
    sOperac := upper(TG_OP);

    -- Busca Parametro samlink
    select into sConnStr samlink.fn_getSamLinkConnStr();

    -- Valida String de Conexao
    if sConnStr is null then
       raise exception 'Parametro de conexao (samlink) nao definido em sw_configuracao';
       if sOperac='INSERT' or sOperac='UPDATE' then
          return new;
       else
          return old;
       end if;
    end if;

    /***
     *
      *  Sincronizacao SW_CGM -> CGM
      *
     */

    if sTabela='SW_CGM' then
       if sOperac='INSERT' or sOperac='UPDATE' then
          -- Define variaveis para INSERIR ou ALTERAR no SIAMmax
          z01_cgccpf := quote_literal(repeat('0', 14));
          z01_numcgm := to_char(new.numcgm, '99999999');
          z01_nome   := quote_literal(upper(samlink.fn_removeAcentos(substr(new.nom_cgm, 1, 40))));
          z01_munic  := quote_literal(upper(samlink.fn_removeAcentos(samlink.fn_nomeMunicipio(new.cod_municipio, new.cod_uf))));
          z01_uf     := quote_literal(samlink.fn_siglaUf(new.cod_uf));
          z01_cep    := quote_literal(new.cep);
          z01_cadast := quote_literal(to_char(new.dt_cadastro, 'yyyy-mm-dd'));
          z01_telef  := quote_literal(new.fone_residencial);
          z01_ident  := quote_literal(repeat('0', 15));
          z01_digito := quote_literal(samlink.fn_mod11(new.numcgm));
          z01_login  := quote_literal('siamweb');
          z01_bairro := quote_literal(upper(samlink.fn_removeAcentos(substr(new.bairro, 1, 20))));
          z01_incest := quote_literal(repeat('0', 15));
          z01_telcel := quote_literal(new.fone_celular);
          z01_email  := quote_literal(lower(new.e_mail));
          z01_endcon := quote_literal(upper(samlink.fn_removeAcentos(substr(new.logradouro_corresp, 1, 24) || ', N ' || new.numero_corresp || '/' || substr(new.complemento_corresp, 1, 6))));
          z01_muncon := quote_literal(upper(samlink.fn_removeAcentos(samlink.fn_nomeMunicipio(new.cod_municipio_corresp, new.cod_uf_corresp))));
          z01_baicon := quote_literal(upper(samlink.fn_removeAcentos(substr(new.bairro_corresp, 1, 20))));
          z01_ufcon  := quote_literal(samlink.fn_siglaUf(new.cod_uf_corresp));
          z01_cepcon := quote_literal(new.cep_corresp);
          z01_telcon := quote_literal(new.fone_comercial);
          z01_celcon := quote_literal('');
          z01_emailc := quote_literal(lower(new.e_mail_adcional));

          tLogradouro  := Upper(BTrim(new.logradouro));
          tNumero      := Upper(Btrim(new.numero));
          tComplemento := Upper(Btrim(new.complemento));
          tEnder       :='';
          tTelef       := Btrim(Replace(new.fone_residencial,'.', ''));
          tTelcel      := Btrim(Replace(new.fone_celular    ,'.', ''));
          tTelcon      := Btrim(Replace(new.fone_comercial  ,'.', ''));

          If tTelef != '' Then
             z01_telef  := quote_literal(Btrim(To_Char(To_number(Translate(tTelef  , ' ', ''), '999999999999'), '999999999999')));
          End If;
          If tTelcel != '' Then
             z01_telcel := quote_literal(Btrim(To_Char(To_number(Translate(tTelcel, ' ', ''), '999999999999'), '999999999999')));
          End If;
          If tTelcon != '' Then
             z01_telcon := quote_literal(Btrim(To_Char(To_number(Translate(tTelcon, ' ', ''), '999999999999'), '999999999999')));
          End If;

          -- Ira concatenar todo o endereço.
          If tLogradouro != '' Then
             tEnder  := tLogradouro;
          End If;
          If tNumero != '' Then
             tEnder  := tEnder || ',N:' || tNumero;
          End If;
          If tComplemento != '' Then
             tEnder  := tEnder || ' / ' || tComplemento;
          End If;

          -- Verifica o tamanho do Endereço.
          If Length( tEnder )  <= 40 Then
             z01_ender  := quote_literal(samlink.fn_removeAcentos(tEnder));
          Else
             --
             -- Ira truncar caracteres desnecessarios.
             --
             -- Abrevia logradouro.
             tEnder  := Replace(tEnder, 'RUA '     , 'R.'   );
             tEnder  := Replace(tEnder, 'AVENIDA ' , 'AV.'  );
             tEnder  := Replace(tEnder, 'LARGO '   , 'LRG.' );
             tEnder  := Replace(tEnder, 'TRAVESSA ', 'TRAV.');
             tEnder  := Replace(tEnder, 'PRACA '   , 'PRC.' );
             tEnder  := Replace(tEnder, 'BECO '    , 'BEC.' );
             tEnder  := Replace(tEnder, 'ACESSO '  , 'ACES.');
             tEnder  := Replace(tEnder, 'ESTRADA ' , 'ESTR.');
             tEnder  := Replace(tEnder, 'QUADRA '  , 'QDR.' );
             tEnder  := Replace(tEnder, 'ALAMEDA ' , 'ALAM.');
             tEnder  := Replace(tEnder, 'LADEIRA ' , 'LAD.' );
             tEnder  := Replace(tEnder, 'RODOVIA ' , 'RDV.' );
             tEnder  := Replace(tEnder, 'ACENTAMENTO' , ' ACENT.' );
             tEnder  := Replace(tEnder, 'ACESSO'      , ' ACES.' );

             -- Retirada de palavras comuns.
             tEnder  := Replace(tEnder, ' SALA'       , ' SL.'    );
             tEnder  := Replace(tEnder, 'CENTRO'      , ' CENT.'  );
             tEnder  := Replace(tEnder, 'APARTAMENTO' , ' AP.'    );
             tEnder  := Replace(tEnder, 'APTO'        , ' AP.'    );
             tEnder  := Replace(tEnder, ' APT'        , ' AP.'    );
             tEnder  := Replace(tEnder, 'BLOCO'       , ' BL.'    );
             tEnder  := Replace(tEnder, ' CASA'       , ' CAS.'   );
             tEnder  := Replace(tEnder, 'CIDADE'      , ' CID.'   );

             -- Cargos.
             tEnder  := Replace(tEnder, 'CORONEL'        , 'CEL.'   );
             tEnder  := Replace(tEnder, 'MARECHAL'       , 'MAL.'    );
             tEnder  := Replace(tEnder, 'GENERAL'        , 'GAL.'    );
             tEnder  := Replace(tEnder, 'VIGARIO'        , 'VIG.'    );
             tEnder  := Replace(tEnder, 'SANTO'          , 'SAN.'    );
             tEnder  := Replace(tEnder, 'PADRE'          , 'P.'      );
             tEnder  := Replace(tEnder, 'DESEMBARGADOR'  , 'DESEMB.' );

             If Length( tEnder )  <= 40 Then
                z01_ender  :=  quote_literal(samlink.fn_removeAcentos(tEnder));
             Else

                -- Ira Desmembrar o endereçõe para retirar espaços duplos.
                vAux   := '';
                aEnder := ARRAY[''];
                FOR iLoop IN 1..Length(tEnder) LOOP
                   cCaracter :=  Substr( tEnder, iLoop, 1 );

                   If cCaracter != ' ' Then
                      If cCaracter != ',' Then
                         vAux := vAux || Substr( tEnder, iLoop, 1 );
                      Else
                          aEnder := aEnder || ARRAY[vAux];
                          aEnder := aEnder || ARRAY[cCaracter::varchar];
                          vAux   := '';
                      End If;
                   Else
                      If Btrim(vAux) != '' Then
                         aEnder := aEnder || ARRAY[vAux];
                      End If;
                      vAux   := '';
                   End If;
                End Loop;

                aEnder     := aEnder || ARRAY[vAux];
                iNumLinhas := Coalesce(array_upper(aEnder, 1 ),0);
                tEnder     := '';

                FOR iLoop IN 2 ..iNumLinhas LOOP
                    tEnder := Btrim(tEnder || ' ' || aEnder[iLoop]);
                End Loop;

                -- Pontuacao.
                tEnder  := Replace(tEnder, '/ '       , '/' );
                tEnder  := Replace(tEnder, ' /'       , '/' );
                tEnder  := Replace(tEnder, '- '       , '-' );
                tEnder  := Replace(tEnder, ' -'       , '-' );
                tEnder  := Replace(tEnder, '. '       , '.' );
                tEnder  := Replace(tEnder, ' .'       , '.' );
                tEnder  := Replace(tEnder, ': '       , ':' );
                tEnder  := Replace(tEnder, ' :'       , ':' );
                tEnder  := Replace(tEnder, ', '       , ',' );
                tEnder  := Replace(tEnder, ' ,'       , ',' );


                 If Length( tEnder )  <= 40 Then
                    z01_ender  := quote_literal(tEnder);
                 Else
                    tEnder  := Replace(tEnder, 'N:' ,'');
                    tEnder  := Replace(tEnder, 'AP.','');

                    If Length( tEnder )  <= 40 Then
                       z01_ender  := quote_literal(tEnder);
                    Else

                       -- Ultima tentativa
                       -- Remonta o array de endereções e indentifica
                       -- quais os valores com numeros que não serão truncados.
                       iTirar := Length( tEnder ) - 40;
                       vAux   := '';
                       aEnder := ARRAY[''];
                       aAlter := ARRAY[true];
                       bAlter := True;
                       FOR iLoop IN 1..Length(tEnder) LOOP
                          cCaracter :=  Substr( tEnder, iLoop, 1 );

                          If cCaracter != ' ' Then
                             If cCaracter != ',' Then
                                vAux   := vAux || Substr( tEnder, iLoop, 1 );
                                If bAlter Then
                                   bAlter := ( Ascii(cCaracter) Not Between 48 And 57 );
                                End If;
                             Else
                                aAlter := aAlter || ARRAY[bAlter];

                                aEnder := aEnder || ARRAY[vAux];
                                aEnder := aEnder || ARRAY[cCaracter::varchar];
                                aAlter := aAlter || ARRAY[False];
                                bAlter := True;
                                vAux   := '';
                             End If;
                          Else
                             If Btrim(vAux) != '' Then
                                aEnder := aEnder || ARRAY[vAux];
                                aAlter := aAlter || ARRAY[bAlter];
                                bAlter := True;
                           End If;
                             vAux   := '';
                          End If;
                       End Loop;
                       aEnder     := aEnder || ARRAY[vAux];
                       aAlter     := aAlter || ARRAY[bAlter];
                       iNumLinhas := Coalesce(array_upper(aEnder, 1 ),0);
                       tEnder     := '';
                       iCount     := 0 ;

                       -- Trunca os nomes.
                       While  iTirar > 0 Loop
                           FOR iLoop IN 2 ..iNumLinhas LOOP
                              vAux := aEnder[iLoop];

                              If vAux = ',' Then
                                 Exit;
                              End If;

                              If aAlter[iLoop] And Length(vAux) > 3  Then
                                 vAux          := Substr(vAux, 01, Length( vAux ) -1 );
                                 iTirar        := iTirar - 1;
                                 aEnder[iLoop] := vAux;
                              End If;

                              If iTirar = 0  Then
                                 Exit;
                              End If;
                           End Loop;

                          -- Apenas para evitar Loop infinito.
                          iCount := iCount + 1;
                          If iCount > 10 Then
                             Exit;
                          End If;
                       End Loop;

                       FOR iLoop IN 2 ..iNumLinhas LOOP
                          tEnder := Btrim(tEnder || ' ' || aEnder[iLoop]);
                       End Loop;

                       tEnder  := Replace(tEnder, ', '       , ',' );
                       tEnder  := Replace(tEnder, ' ,'       , ',' );

                       z01_ender  := quote_literal(tEnder);
                    End If;
                 End If;

             End If;
          End If;
       End if;
       /***
        *
         * INSERT - Insere em CGM qdo insere em SW_CGM
         *
        */
       if sOperac='INSERT' then
          -- Executa INSERT remoto
          perform samlink.fn_remoteInsert(
             sConnStr,
             'cgm',
             ARRAY['z01_cgccpf', 'z01_numcgm',
                  'z01_nome',   'z01_ender',
                  'z01_munic',  'z01_uf',
                  'z01_cep',    'z01_cadast',
                  'z01_telef',  'z01_ident',
                  'z01_digito', 'z01_login',
                  'z01_bairro', 'z01_incest',
                  'z01_telcel', 'z01_email',
                  'z01_endcon', 'z01_muncon',
                  'z01_baicon', 'z01_ufcon',
                  'z01_cepcon', 'z01_telcon',
                  'z01_celcon', 'z01_emailc'],
             ARRAY[z01_cgccpf, z01_numcgm,
                  z01_nome,   z01_ender,
                  z01_munic,  z01_uf,
                  z01_cep,    z01_cadast,
                  z01_telef,  z01_ident,
                  z01_digito, z01_login,
                  z01_bairro, z01_incest,
                  z01_telcel, z01_email,
                  z01_endcon, z01_muncon,
                  z01_baicon, z01_ufcon,
                  z01_cepcon, z01_telcon,
                  z01_celcon, z01_emailc]
          );
       /***
        *
         * UPDATE - Altera em CGM qdo altera em SW_CGM
         *
        */
       elsif sOperac='UPDATE' then
          -- Executa UPDATE remoto


            perform samlink.fn_remoteUpdate(
               sConnStr,
               'cgm',
               ARRAY['z01_numcgm', 'z01_nome',   'z01_ender',
                    'z01_munic',  'z01_uf',      'z01_cep',
                    'z01_cadast', 'z01_telef',
                    'z01_digito', 'z01_login',  'z01_bairro',
                    'z01_telcel', 'z01_email',
                    'z01_endcon', 'z01_muncon', 'z01_baicon',
                    'z01_ufcon',  'z01_cepcon', 'z01_telcon',
                    'z01_celcon', 'z01_emailc'],
               ARRAY[z01_numcgm, z01_nome,   z01_ender,
                    z01_munic,  z01_uf,     z01_cep,
                    z01_cadast, z01_telef,
                    z01_digito, z01_login,  z01_bairro,
                    z01_telcel, z01_email,
                    z01_endcon, z01_muncon,
                    z01_baicon, z01_ufcon,
                    z01_cepcon, z01_telcon,
                    z01_celcon, z01_emailc],
               'z01_numcgm='||old.numcgm
            );


       /***
        *
         * DELETE - Apaga de CGM qdo apaga de SW_CGM
         *
        */
       elsif sOperac='DELETE' then
          -- Execute DELETE remoto
          perform samlink.fn_remoteDelete(
             sConnStr,
             'cgm',
             'z01_numcgm='||old.numcgm
          );
       end if;
     /***
      *
      *  Sincronizacao SW_CGM_PESSOA_FISICA -> CGM
      *
      */
    elsif sTabela='SW_CGM_PESSOA_FISICA' then
       /***
        *
         * INSERT - Altera em CGM qdo insere em SW_CGM_PESSOA_FISICA
         * UPDATE - Altera em CGM qdo altera em SW_CGM_PESSOA_FISICA
         *
        */
       if sOperac='INSERT' or sOperac='UPDATE' then
          -- Define variaveis para alteracao
          z01_cgccpf := quote_literal(new.cpf);
          z01_ident  := quote_literal(new.rg);

          -- Executa UPDATE remoto
          perform samlink.fn_remoteUpdate(
             sConnStr,
             'cgm',
             ARRAY['z01_cgccpf', 'z01_ident'],
             ARRAY[z01_cgccpf, z01_ident],
             'z01_numcgm='||new.numcgm
          );

       /***
        *
         * DELETE - Altera em CGM qdo exclui de SW_CGM_PESSOA_FISICA
         *
        */
       else
          -- Define variaveis para alteracao
          z01_cgccpf := quote_literal(repeat('0', 14));
          z01_ident  := quote_literal(repeat('0', 15));

          -- Executa UPDATE remoto
          perform samlink.fn_remoteUpdate(
             sConnStr,
             'cgm',
             ARRAY['z01_cgccpf', 'z01_ident'],
             ARRAY[z01_cgccpf, z01_ident],
             'z01_numcgm='||old.numcgm
          );

       end if;
    /***
     *
      *  Sincronizacao SW_CGM_PESSOA_JURIDICA -> CGM
      *
     */
    else
       /***
        *
         * INSERT - Altera em CGM qdo insere em SW_CGM_PESSOA_JURIDICA
         * UPDATE - Altera em CGM qdo altera em SW_CGM_PESSOA_JURIDICA
         *
        */
       if sOperac='INSERT' or sOperac='UPDATE' then
          -- Define variaveis para alteracao
          z01_cgccpf := quote_literal(new.cnpj);
          z01_incest := quote_literal(new.insc_estadual);

          -- Executa UPDATE remoto
          perform samlink.fn_remoteUpdate(
             sConnStr,
             'cgm',
             ARRAY['z01_cgccpf', 'z01_incest'],
             ARRAY[z01_cgccpf, z01_incest],
             'z01_numcgm='||new.numcgm
          );


       /***
        *
         * DELETE - Altera em CGM qdo exclui de SW_CGM_PESSOA_JURIDICA
         *
        */
       else
          -- Define variaveis para alteracao
          z01_cgccpf := quote_literal(repeat('0', 14));
          z01_incest := quote_literal(repeat('0', 15));

          -- Executa UPDATE remoto
          perform samlink.fn_remoteUpdate(
             sConnStr,
             'cgm',
             ARRAY['z01_cgccpf', 'z01_incest'],
             ARRAY[z01_cgccpf, z01_incest],
             'z01_numcgm='||old.numcgm
          );

       end if;
    end if;

    return new;
 end;
 $$;


ALTER FUNCTION samlink.fn_samlinkcgm() OWNER TO urbem;

SET search_path = public, pg_catalog;

--
-- Name: acao_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE acao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.acao_id_seq OWNER TO urbem;

SET default_tablespace = '';

SET default_with_oids = true;

--
-- Name: acao; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE acao (
    id integer DEFAULT nextval('acao_id_seq'::regclass) NOT NULL,
    alias character varying(30) NOT NULL,
    descricao text
);


ALTER TABLE public.acao OWNER TO urbem;

--
-- Name: categoria_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE categoria_id_seq
    START WITH 3
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.categoria_id_seq OWNER TO urbem;

--
-- Name: categoria; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE categoria (
    id integer DEFAULT nextval('categoria_id_seq'::regclass) NOT NULL,
    type character varying(30) NOT NULL,
    alias character varying(30) NOT NULL,
    categoria character varying(100) NOT NULL,
    parent_id integer,
    created timestamp without time zone NOT NULL,
    updated timestamp without time zone DEFAULT now()
);


ALTER TABLE public.categoria OWNER TO urbem;

--
-- Name: cep_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE cep_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.cep_id_seq OWNER TO urbem;

--
-- Name: cep; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE cep (
    id integer DEFAULT nextval('cep_id_seq'::regclass) NOT NULL,
    cidade_id integer NOT NULL,
    bairro character varying(60) NOT NULL,
    logradouro character varying(100) NOT NULL,
    cep character varying(10)
);


ALTER TABLE public.cep OWNER TO urbem;

--
-- Name: configuracao_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE configuracao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.configuracao_id_seq OWNER TO urbem;

--
-- Name: configuracao; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE configuracao (
    id integer DEFAULT nextval('configuracao_id_seq'::regclass) NOT NULL,
    modulo_id integer NOT NULL,
    alias character varying(20) NOT NULL,
    parametro character varying(100) NOT NULL,
    valor character varying(100),
    descricao text,
    created timestamp without time zone NOT NULL,
    updated timestamp without time zone DEFAULT now(),
    municipio_id integer
);


ALTER TABLE public.configuracao OWNER TO urbem;

--
-- Name: contato_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE contato_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contato_id_seq OWNER TO urbem;

SET default_with_oids = false;

--
-- Name: contato; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE contato (
    id integer DEFAULT nextval('contato_id_seq'::regclass) NOT NULL,
    configuracao_id integer NOT NULL,
    assunto character varying(100) NOT NULL,
    nome character varying(100) NOT NULL,
    email character(100),
    ddd character varying(2),
    telefone character varying(9),
    mensagem text NOT NULL,
    status character varying(2) NOT NULL,
    created timestamp without time zone NOT NULL,
    updated timestamp without time zone
);


ALTER TABLE public.contato OWNER TO urbem;

--
-- Name: grupo_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE grupo_id_seq
    START WITH 3
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.grupo_id_seq OWNER TO urbem;

SET default_with_oids = true;

--
-- Name: grupo; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE grupo (
    id integer DEFAULT nextval('grupo_id_seq'::regclass) NOT NULL,
    grupo character varying(20) NOT NULL,
    alias character varying(20) NOT NULL,
    created timestamp without time zone NOT NULL,
    updated timestamp without time zone DEFAULT now()
);


ALTER TABLE public.grupo OWNER TO urbem;

--
-- Name: grupo_acao_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE grupo_acao_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.grupo_acao_id_seq OWNER TO urbem;

--
-- Name: grupo_acao; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE grupo_acao (
    id integer DEFAULT nextval('grupo_acao_id_seq'::regclass) NOT NULL,
    grupo_id integer NOT NULL,
    acao_id integer NOT NULL
);


ALTER TABLE public.grupo_acao OWNER TO urbem;

--
-- Name: historico_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE historico_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.historico_id_seq OWNER TO urbem;

--
-- Name: historico; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE historico (
    id integer DEFAULT nextval('historico_id_seq'::regclass) NOT NULL,
    pessoa_id integer NOT NULL,
    modulo_id integer NOT NULL,
    entidade_id integer NOT NULL,
    descricao text NOT NULL,
    created timestamp without time zone NOT NULL,
    updated timestamp without time zone DEFAULT now()
);


ALTER TABLE public.historico OWNER TO urbem;

--
-- Name: log_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.log_id_seq OWNER TO urbem;

--
-- Name: log; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE log (
    id integer DEFAULT nextval('log_id_seq'::regclass) NOT NULL,
    municipio_id integer NOT NULL,
    remessa character varying(100) NOT NULL,
    arquivo character varying(100) NOT NULL,
    mensagem text NOT NULL,
    excecao text,
    created timestamp without time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.log OWNER TO urbem;

--
-- Name: menu_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE menu_id_seq
    START WITH 25
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.menu_id_seq OWNER TO urbem;

--
-- Name: menu; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE menu (
    id integer DEFAULT nextval('menu_id_seq'::regclass) NOT NULL,
    acao_id integer,
    parent_id integer,
    url character varying(100) NOT NULL,
    label character varying(30) NOT NULL,
    target character varying(10) NOT NULL,
    posicao integer NOT NULL
);


ALTER TABLE public.menu OWNER TO urbem;

--
-- Name: modulo_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE modulo_id_seq
    START WITH 6
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.modulo_id_seq OWNER TO urbem;

--
-- Name: modulo; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE modulo (
    id integer DEFAULT nextval('modulo_id_seq'::regclass) NOT NULL,
    alias character varying(20) NOT NULL,
    modulo character varying(20) NOT NULL
);


ALTER TABLE public.modulo OWNER TO urbem;

--
-- Name: municipio_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE municipio_id_seq
    START WITH 3
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.municipio_id_seq OWNER TO urbem;

--
-- Name: municipio; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE municipio (
    id integer DEFAULT nextval('municipio_id_seq'::regclass) NOT NULL,
    uf_id integer NOT NULL,
    nome character varying(60) NOT NULL,
    disponivel integer DEFAULT 0 NOT NULL,
    hash character varying(100),
    db character varying(100),
    alias character varying(30)
);


ALTER TABLE public.municipio OWNER TO urbem;

--
-- Name: pessoa_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE pessoa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.pessoa_id_seq OWNER TO urbem;

--
-- Name: pessoa; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE pessoa (
    id integer DEFAULT nextval('pessoa_id_seq'::regclass) NOT NULL,
    categoria_id integer,
    nome character varying(100) NOT NULL,
    email character varying(100),
    observacao text,
    ddd_comercial character varying(2),
    telefone_comercial character varying(10),
    ddd_celular character varying(2),
    telefone_celular character varying(10),
    facebook character varying(100),
    twitter character varying(100),
    created timestamp without time zone NOT NULL,
    updated timestamp without time zone DEFAULT now()
);


ALTER TABLE public.pessoa OWNER TO urbem;

--
-- Name: pessoa_fisica_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE pessoa_fisica_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.pessoa_fisica_id_seq OWNER TO urbem;

--
-- Name: pessoa_fisica; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE pessoa_fisica (
    id integer DEFAULT nextval('pessoa_fisica_id_seq'::regclass) NOT NULL,
    pessoa_id integer NOT NULL,
    cpf character varying(20),
    rg character varying(20),
    orgao_emissor character varying(20),
    data_nascimento date,
    ddd_residencial character varying(2),
    telefone_residencial character varying(10)
);


ALTER TABLE public.pessoa_fisica OWNER TO urbem;

--
-- Name: pessoa_juridica_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE pessoa_juridica_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.pessoa_juridica_id_seq OWNER TO urbem;

--
-- Name: pessoa_juridica; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE pessoa_juridica (
    id integer DEFAULT nextval('pessoa_juridica_id_seq'::regclass) NOT NULL,
    pessoa_id integer NOT NULL,
    cnpj character varying(20),
    inscricao_estadual character varying(20),
    site character varying(100)
);


ALTER TABLE public.pessoa_juridica OWNER TO urbem;

--
-- Name: uf_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE uf_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.uf_id_seq OWNER TO urbem;

--
-- Name: uf; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE uf (
    id integer DEFAULT nextval('uf_id_seq'::regclass) NOT NULL,
    nome character varying(20) NOT NULL,
    disponivel integer NOT NULL,
    sigla character varying(2)
);


ALTER TABLE public.uf OWNER TO urbem;

--
-- Name: usuario_id_seq; Type: SEQUENCE; Schema: public; Owner: urbem
--

CREATE SEQUENCE usuario_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuario_id_seq OWNER TO urbem;

--
-- Name: usuario; Type: TABLE; Schema: public; Owner: urbem; Tablespace: 
--

CREATE TABLE usuario (
    id integer DEFAULT nextval('usuario_id_seq'::regclass) NOT NULL,
    pessoa_id integer NOT NULL,
    grupo_id integer NOT NULL,
    status integer NOT NULL,
    senha character varying(150) NOT NULL,
    created timestamp without time zone NOT NULL,
    updated timestamp without time zone DEFAULT now(),
    municipio_id integer
);


ALTER TABLE public.usuario OWNER TO urbem;

--
-- Name: 147385773; Type: BLOB; Schema: -; Owner: urbem
--

SELECT pg_catalog.lo_create('147385773');


ALTER LARGE OBJECT 147385773 OWNER TO urbem;

--
-- Name: 5997947; Type: BLOB; Schema: -; Owner: urbem
--

SELECT pg_catalog.lo_create('5997947');


ALTER LARGE OBJECT 5997947 OWNER TO urbem;

--
-- Data for Name: acao; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY acao (id, alias, descricao) FROM stdin;
1	home	Home
2	usuario.index	Visualizar lista de usuários
3	usuario.show	Visualizar detalhes do usuário
4	usuario.create	Criar usuários
5	usuario.update	Editar usuários
6	usuario.delete	Deletar usuários
7	usuario.setLogin	Login de usuários
8	usuario.login	Login de usuários
9	usuario.logout	Sair do sistema
10	usuario.setConta	Altera os dados pessoais do usuário logado
11	usuario.updateConta	Altera os dados pessoais do usuário logado
12	configuracao.configuracao	Configurações do sistema
13	usuario.editStatus	Alterar entre ativo/inativo
17	historico.index	
18	historico.show	
19	historico.create	
20	historico.update	
21	historico.delete	
37	configuracao.index	
38	configuracao.show	
39	configuracao.create	
40	configuracao.update	
41	configuracao.delete	
42	grupo.index	
43	grupo.show	
44	grupo.create	
45	grupo.update	
46	grupo.delete	
61	publicacao.index	\N
62	publicacao.create	\N
63	publicacao.update	\N
64	publicacao.delete	\N
65	publicacao.show	\N
66	configuracaoEntidade.index	\N
\.


--
-- Name: acao_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('acao_id_seq', 67, true);


--
-- Data for Name: categoria; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY categoria (id, type, alias, categoria, parent_id, created, updated) FROM stdin;
1	pessoa	usuario	Usuário	\N	2012-03-20 16:28:43	\N
2	pessoa	fornecedor	Fornecedor	\N	2012-03-20 16:34:09	\N
4	contato	cnm	Confederação Nacional de Municípios	\N	2012-04-22 16:34:09	\N
3	contato	municipio	Município Selecionado	\N	2012-04-22 16:34:09	\N
\.


--
-- Name: categoria_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('categoria_id_seq', 3, false);


--
-- Data for Name: cep; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY cep (id, cidade_id, bairro, logradouro, cep) FROM stdin;
\.


--
-- Name: cep_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('cep_id_seq', 1, false);


--
-- Data for Name: configuracao; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY configuracao (id, modulo_id, alias, parametro, valor, descricao, created, updated, municipio_id) FROM stdin;
1	3	contato-cnm	contato	transparencia@cnm.org.br	Confederação Nacional de Municípios	2013-04-23 17:19:22	2013-04-23 18:00:42	\N
3	3	fundo	contato	fmapsp@marianapimentel.gov-rs.com.br	FMAPSP - Fundo municipal de aposentadoria e pensão	2013-04-23 17:18:46	2013-04-24 10:17:54	1
2	3	prefeitura	contato	prefeitura@marianapimentel.gov-rs.com.br	Prefeitura Municipal de Mariana Pimentel	2013-04-23 17:17:49	2013-04-24 10:17:59	1
4	3	camara	contato	camara@marianapimentel.gov-rs.com.br	Câmara Municipal de Vereadores de Mariana Pimentel	2013-04-23 17:16:15	2013-04-25 11:05:42	1
\.


--
-- Name: configuracao_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('configuracao_id_seq', 4, true);


--
-- Data for Name: contato; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY contato (id, configuracao_id, assunto, nome, email, ddd, telefone, mensagem, status, created, updated) FROM stdin;
1	1	Assunto	Nome	teste@teste.com                                                                                     	11	2222-2222	mensagem	nl	2013-04-24 11:28:46	\N
2	2	Assunto	Nome	suporte@cnm.org.br                                                                                  	11	2222-2222	teste	nl	2013-06-06 09:15:59	\N
3	2	Assunto	Nome	suporte@cnm.org.br                                                                                  	11	2222-2222	teste	nl	2013-06-06 09:16:09	\N
4	2	Assunto	Nome	suporte@cnm.org.br                                                                                  	11	2222-2222	teste	nl	2013-06-06 09:16:44	\N
5	2	Assunto	Nome	suporte@cnm.org.br                                                                                  	11	2222-2222	teste	nl	2013-06-06 09:17:32	\N
7	2	Assunto222	Nome333	teste@teste.com.br                                                                                  	11	3333-3333	mensagem mensagem!	nl	2013-06-06 09:19:51	\N
8	2	Assunto222	XVI Marcha - A Brasília em Defesa dos Municípios	suporte@cnm.org.br                                                                                  	11	3333-3333	mamama	nl	2013-06-06 09:20:56	\N
9	2	Assunto222	XVI Marcha - A Brasília em Defesa dos Municípios	suporte@cnm.org.br                                                                                  	11	3333-3333	mamama	nl	2013-06-06 09:21:50	\N
10	2	Assunto222	XVI Marcha - A Brasília em Defesa dos Municípios	suporte@cnm.org.br                                                                                  	11	3333-3333	mamama	nl	2013-06-06 09:24:52	\N
11	2	Assunto222	Nome333	suporte@cnm.org.br                                                                                  	11	3333-3333	mensagem	nl	2013-06-06 09:26:46	\N
12	2	Assunto222	Nome333	suporte@cnm.org.br                                                                                  	11	3333-3333	mensagem	nl	2013-06-06 09:27:57	\N
13	4	Assunto222	Nome333	suporte@cnm.org.br                                                                                  	11	3333-3333	teste	nl	2013-06-06 09:29:11	\N
14	4	Assunto222	Nome333	suporte@cnm.org.br                                                                                  	11	3333-3333	teste	nl	2013-06-06 09:29:40	\N
15	1	Concurso	Tiago	tiagodsj@yahoo.com.br                                                                               			Como está a situação do concurso que estava suspenso em Mariana Pimentel?	nl	2014-07-09 12:30:52	\N
16	2	LICITAÇÃO	PATRICIA ALVES	adm@inovesempre.com.br                                                                              	43	9985-9955	Gostaria de acessar os editais das licitações 2015, onde posso conseguir?\r\nObrigada.\r\nPatricia.	nl	2015-06-23 09:30:55	\N
\.


--
-- Name: contato_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('contato_id_seq', 16, true);


--
-- Data for Name: grupo; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY grupo (id, grupo, alias, created, updated) FROM stdin;
1	Administradores	admin	2012-02-05 13:19:42	2013-06-28 11:58:34
2	Usuários	usuarios	2013-01-22 13:20:03	2013-06-28 12:02:28
\.


--
-- Data for Name: grupo_acao; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY grupo_acao (id, grupo_id, acao_id) FROM stdin;
610	2	1
611	2	8
612	2	9
613	2	10
614	2	11
615	2	61
616	2	62
617	2	63
618	2	64
619	2	65
620	2	66
571	1	1
572	1	2
573	1	3
574	1	4
575	1	5
576	1	6
577	1	7
578	1	8
579	1	9
580	1	10
581	1	11
582	1	12
583	1	13
584	1	17
585	1	18
586	1	19
587	1	20
588	1	21
589	1	37
590	1	38
591	1	39
592	1	40
593	1	41
594	1	42
595	1	43
596	1	44
597	1	45
598	1	46
\.


--
-- Name: grupo_acao_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('grupo_acao_id_seq', 622, true);


--
-- Name: grupo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('grupo_id_seq', 3, false);


--
-- Data for Name: historico; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY historico (id, pessoa_id, modulo_id, entidade_id, descricao, created, updated) FROM stdin;
\.


--
-- Name: historico_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('historico_id_seq', 1, false);


--
-- Data for Name: log; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY log (id, municipio_id, remessa, arquivo, mensagem, excecao, created) FROM stdin;
4	1	a	a	a	a	2013-01-23 14:53:46.219755
7	1	../uploads/1/remessas/201303271642.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "      "	\N	2013-04-04 10:55:24
8	1	../uploads/1/remessas/201303271642.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "      "	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "      "' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(83): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(240): BalanceteDespesaBO::import('../uploads/1/re...', 72, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-04 11:50:35
9	1	../uploads/1/remessas/201303271642.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "      "	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "      "' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(86): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(240): BalanceteDespesaBO::import('../uploads/1/re...', 74, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-04 14:49:18
10	1	../uploads/1/remessas/201303271642.zip	BALANCETEDESPESA.TXT	SQLSTATE[23502]: Not null violation: 7 ERROR:  null value in column "id" violates not-null constraint	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[23502]: Not null violation: 7 ERROR:  null value in column "id" violates not-null constraint' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(86): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(240): BalanceteDespesaBO::import('../uploads/1/re...', 76, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-04 15:12:06
11	1	../uploads/1/remessas/201303271642.zip	BALANCETEDESPESA.TXT	SQLSTATE[23502]: Not null violation: 7 ERROR:  null value in column "id" violates not-null constraint	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[23502]: Not null violation: 7 ERROR:  null value in column "id" violates not-null constraint' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(86): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(240): BalanceteDespesaBO::import('../uploads/1/re...', 78, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-04 15:13:31
12	1	../uploads/1/remessas/201303271642.zip	BALANCETEDESPESA.TXT	Commit failed. There is no active transaction.	exception 'Doctrine_Transaction_Exception' with message 'Commit failed. There is no active transaction.' in /var/www/transparencia/core/doctrine/lib/Doctrine/Transaction.php:239\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1395): Doctrine_Transaction->commit(NULL)\n#1 /var/www/transparencia/scripts/importacao.script.php(335): Doctrine_Connection->commit()\n#2 {main}	2013-04-04 17:23:50
13	1	../uploads/1/remessas/201303271642.zip	BALANCETEDESPESA.TXT	Commit failed. There is no active transaction.	exception 'Doctrine_Transaction_Exception' with message 'Commit failed. There is no active transaction.' in /var/www/transparencia/core/doctrine/lib/Doctrine/Transaction.php:239\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1395): Doctrine_Transaction->commit(NULL)\n#1 /var/www/transparencia/scripts/importacao.script.php(335): Doctrine_Connection->commit()\n#2 {main}	2013-04-04 17:24:42
14	1	../uploads/1/remessas/201303271642.zip	BALANCETERECEITA.TXT	Unknown record property / related component "espeficicacao_conta" on "BalanceteReceita"	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown record property / related component "espeficicacao_conta" on "BalanceteReceita"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record/Filter/Standard.php:44\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1504): Doctrine_Record_Filter_Standard->filterSet(Object(BalanceteReceita), 'espeficicacao_c...', 'RECEITAS CORREN...')\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1456): Doctrine_Record->_set('espeficicacao_c...', 'RECEITAS CORREN...', true)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Access.php(60): Doctrine_Record->set('espeficicacao_c...', 'RECEITAS CORREN...')\n#3 /var/www/transparencia/modules/transparencia/balancetereceita.class.php(155): Doctrine_Access->__set('espeficicacao_c...', 'RECEITAS CORREN...')\n#4 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(78): BalanceteReceita->setEspecificacaoConta('RECEITAS CORREN...')\n#5 /var/www/transparencia/scripts/importacao.script.php(244): BalanceteReceitaBO::import('../uploads/1/re...', 100, Object(Doctrine_Connection_Pgsql))\n#6 {main}	2013-04-04 17:49:58
55	1	../uploads/1/remessas/config.xml	aa	SQLSTATE[42P01]: Undefined table: 7 ERROR:  relation "transparencia.importacao_id_seq" does not exist. Failing Query: "SELECT CURRVAL('transparencia.importacao_id_seq')"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[42P01]: Undefined table: 7 ERROR:  relation "transparencia.importacao_id_seq" does not exist. Failing Query: "SELECT CURRVAL('transparencia.importacao_id_seq')"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1025): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Pgsql), 'SELECT CURRVAL(...')\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(813): Doctrine_Connection->execute('SELECT CURRVAL(...', Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Sequence/Pgsql.php(82): Doctrine_Connection->fetchOne('SELECT CURRVAL(...')\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(947): Doctrine_Sequence_Pgsql->lastInsertId('transparencia.i...')\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(648): Doctrine_Connection_UnitOfWork->_assignIdentifier(Object(Importacao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Importacao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Importacao))\n#7 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Importacao))\n#8 /var/www/transparencia/modules/importacao/importacao.bo.php(8): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(200): ImportacaoBO::create(Object(Importacao), Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 09:23:05
56	1	../uploads/1/remessas/remessas.zip	aa	SQLSTATE[42P01]: Undefined table: 7 ERROR:  relation "transparencia.importacao_id_seq" does not exist. Failing Query: "SELECT CURRVAL('transparencia.importacao_id_seq')"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[42P01]: Undefined table: 7 ERROR:  relation "transparencia.importacao_id_seq" does not exist. Failing Query: "SELECT CURRVAL('transparencia.importacao_id_seq')"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1025): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Pgsql), 'SELECT CURRVAL(...')\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(813): Doctrine_Connection->execute('SELECT CURRVAL(...', Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Sequence/Pgsql.php(82): Doctrine_Connection->fetchOne('SELECT CURRVAL(...')\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(947): Doctrine_Sequence_Pgsql->lastInsertId('transparencia.i...')\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(648): Doctrine_Connection_UnitOfWork->_assignIdentifier(Object(Importacao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Importacao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Importacao))\n#7 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Importacao))\n#8 /var/www/transparencia/modules/importacao/importacao.bo.php(8): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(200): ImportacaoBO::create(Object(Importacao), Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 09:38:05
59	1	../uploads/1/remessas/config.xml	aa	SQLSTATE[42P01]: Undefined table: 7 ERROR:  relation "transparencia.importacao_id_seq" does not exist. Failing Query: "SELECT CURRVAL('transparencia.importacao_id_seq')"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[42P01]: Undefined table: 7 ERROR:  relation "transparencia.importacao_id_seq" does not exist. Failing Query: "SELECT CURRVAL('transparencia.importacao_id_seq')"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1025): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Pgsql), 'SELECT CURRVAL(...')\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(813): Doctrine_Connection->execute('SELECT CURRVAL(...', Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Sequence/Pgsql.php(82): Doctrine_Connection->fetchOne('SELECT CURRVAL(...')\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(947): Doctrine_Sequence_Pgsql->lastInsertId('transparencia.i...')\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(648): Doctrine_Connection_UnitOfWork->_assignIdentifier(Object(Importacao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Importacao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Importacao))\n#7 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Importacao))\n#8 /var/www/transparencia/modules/importacao/importacao.bo.php(8): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(195): ImportacaoBO::create(Object(Importacao), Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 09:48:24
60	1	../uploads/1/remessas/teste.zip	aa	SQLSTATE[23503]: Foreign key violation: 7 ERROR:  insert or update on table "acao" violates foreign key constraint "fk_acao_1"\nDETAIL:  Key (importacao_id)=(12) is not present in table "importacao".	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[23503]: Foreign key violation: 7 ERROR:  insert or update on table "acao" violates foreign key constraint "fk_acao_1"\nDETAIL:  Key (importacao_id)=(12) is not present in table "importacao".' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Acao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Acao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Acao))\n#7 /var/www/transparencia/modules/transparencia/acao.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/acao.bo.php(63): AcaoBO::create(Object(Acao), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(223): AcaoBO::import('../uploads/1/re...', 12, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 09:56:29
61	1	../uploads/1/remessas/remessas.zip	aa	SQLSTATE[23503]: Foreign key violation: 7 ERROR:  insert or update on table "acao" violates foreign key constraint "fk_acao_1"\nDETAIL:  Key (importacao_id)=(13) is not present in table "importacao".	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[23503]: Foreign key violation: 7 ERROR:  insert or update on table "acao" violates foreign key constraint "fk_acao_1"\nDETAIL:  Key (importacao_id)=(13) is not present in table "importacao".' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Acao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Acao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Acao))\n#7 /var/www/transparencia/modules/transparencia/acao.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/acao.bo.php(63): AcaoBO::create(Object(Acao), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(223): AcaoBO::import('../uploads/1/re...', 13, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 10:04:52
145	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/config.xml~	11	É obrigatório o preenchimento do campo timestamp_remessa no arquivo config.xml	exception 'Exception' with message 'É obrigatório o preenchimento do campo timestamp_remessa no arquivo config.xml' in /var/www/transparencia/modules/importacao/importacao.bo.php:72\nStack trace:\n#0 /var/www/transparencia/modules/importacao/importacao.bo.php(7): ImportacaoBO::validate(Object(Importacao))\n#1 /var/www/transparencia/scripts/importacao.script.php(203): ImportacaoBO::create(Object(Importacao), Object(Doctrine_Connection_Pgsql))\n#2 {main}	2013-05-20 11:10:16
62	1	../uploads/1/remessas/remessas.zip	aa	SQLSTATE[42P01]: Undefined table: 7 ERROR:  relation "transparencia.acao_id_seq" does not exist. Failing Query: "SELECT CURRVAL('transparencia.acao_id_seq')"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[42P01]: Undefined table: 7 ERROR:  relation "transparencia.acao_id_seq" does not exist. Failing Query: "SELECT CURRVAL('transparencia.acao_id_seq')"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1025): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Pgsql), 'SELECT CURRVAL(...')\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(813): Doctrine_Connection->execute('SELECT CURRVAL(...', Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Sequence/Pgsql.php(82): Doctrine_Connection->fetchOne('SELECT CURRVAL(...')\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(947): Doctrine_Sequence_Pgsql->lastInsertId('transparencia.a...')\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(648): Doctrine_Connection_UnitOfWork->_assignIdentifier(Object(Acao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Acao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Acao))\n#7 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Acao))\n#8 /var/www/transparencia/modules/transparencia/acao.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/modules/transparencia/acao.bo.php(63): AcaoBO::create(Object(Acao), Object(Doctrine_Connection_Pgsql))\n#10 /var/www/transparencia/scripts/importacao.script.php(207): AcaoBO::import('../uploads/1/re...', 15, Object(Doctrine_Connection_Pgsql))\n#11 {main}	2013-04-05 10:07:24
63	1	../uploads/1/remessas/remessas.zip	aa	SQLSTATE[42P01]: Undefined table: 7 ERROR:  relation "transparencia.acao_id_seq" does not exist. Failing Query: "SELECT CURRVAL('transparencia.acao_id_seq')"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[42P01]: Undefined table: 7 ERROR:  relation "transparencia.acao_id_seq" does not exist. Failing Query: "SELECT CURRVAL('transparencia.acao_id_seq')"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1025): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Pgsql), 'SELECT CURRVAL(...')\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(813): Doctrine_Connection->execute('SELECT CURRVAL(...', Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Sequence/Pgsql.php(82): Doctrine_Connection->fetchOne('SELECT CURRVAL(...')\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(947): Doctrine_Sequence_Pgsql->lastInsertId('transparencia.a...')\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(648): Doctrine_Connection_UnitOfWork->_assignIdentifier(Object(Acao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Acao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Acao))\n#7 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Acao))\n#8 /var/www/transparencia/modules/transparencia/acao.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/modules/transparencia/acao.bo.php(63): AcaoBO::create(Object(Acao), Object(Doctrine_Connection_Pgsql))\n#10 /var/www/transparencia/scripts/importacao.script.php(207): AcaoBO::import('../uploads/1/re...', 16, Object(Doctrine_Connection_Pgsql))\n#11 {main}	2013-04-05 10:08:28
64	1	../uploads/1/remessas/remessas.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(83): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(211): BalanceteDespesaBO::import('BALANCETEDESPES...', 19, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 10:15:40
65	1	../uploads/1/remessas/remessas.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(83): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(211): BalanceteDespesaBO::import('BALANCETEDESPES...', 20, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 10:42:06
66	1	../uploads/1/remessas/remessas.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(83): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(211): BalanceteDespesaBO::import('BALANCETEDESPES...', 21, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 11:10:37
67	1	../uploads/1/remessas/remessas.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(83): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(211): BalanceteDespesaBO::import('BALANCETEDESPES...', 22, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 11:30:22
68	1	../uploads/1/remessas/remessas.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetedespesa.bo.php(83): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(211): BalanceteDespesaBO::import('BALANCETEDESPES...', 24, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 11:33:55
69	1	../uploads/1/remessas/remessas.zip	BALANCETEDESPESA.TXT	Commit failed. There is no active transaction.	exception 'Doctrine_Transaction_Exception' with message 'Commit failed. There is no active transaction.' in /var/www/transparencia/core/doctrine/lib/Doctrine/Transaction.php:239\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1395): Doctrine_Transaction->commit(NULL)\n#1 /var/www/transparencia/scripts/importacao.script.php(300): Doctrine_Connection->commit()\n#2 {main}	2013-04-05 11:53:58
70	1	../uploads/1/remessas/remessas.zip	BALANCETERECEITA.TXT	Unknown record property / related component "espeficicacao_conta" on "BalanceteReceita"	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown record property / related component "espeficicacao_conta" on "BalanceteReceita"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record/Filter/Standard.php:44\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1504): Doctrine_Record_Filter_Standard->filterSet(Object(BalanceteReceita), 'espeficicacao_c...', 'RECEITAS CORREN...')\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1456): Doctrine_Record->_set('espeficicacao_c...', 'RECEITAS CORREN...', true)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Access.php(60): Doctrine_Record->set('espeficicacao_c...', 'RECEITAS CORREN...')\n#3 /var/www/transparencia/modules/transparencia/balancetereceita.class.php(155): Doctrine_Access->__set('espeficicacao_c...', 'RECEITAS CORREN...')\n#4 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(78): BalanceteReceita->setEspecificacaoConta('RECEITAS CORREN...')\n#5 /var/www/transparencia/scripts/importacao.script.php(215): BalanceteReceitaBO::import('../uploads/1/re...', 29, Object(Doctrine_Connection_Pgsql))\n#6 {main}	2013-04-05 14:08:10
71	1	../uploads/1/remessas/remessas.zip	BALANCETERECEITA.TXT	Unknown method BalanceteReceita::setCodRecurso	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown method BalanceteReceita::setCodRecurso' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php:2658\nStack trace:\n#0 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(81): Doctrine_Record->__call('setCodRecurso', Array)\n#1 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(81): BalanceteReceita->setCodRecurso('00')\n#2 /var/www/transparencia/scripts/importacao.script.php(215): BalanceteReceitaBO::import('../uploads/1/re...', 30, Object(Doctrine_Connection_Pgsql))\n#3 {main}	2013-04-05 14:09:19
95	1	../uploads/1/remessas/remessas.zip	LIQUIDACAO.TXT	Unknown record property / related component "cod_liquidacao" on "Liquidacao"	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown record property / related component "cod_liquidacao" on "Liquidacao"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record/Filter/Standard.php:44\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1504): Doctrine_Record_Filter_Standard->filterSet(Object(Liquidacao), 'cod_liquidacao', '000000000000000...')\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1456): Doctrine_Record->_set('cod_liquidacao', '000000000000000...', true)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Access.php(60): Doctrine_Record->set('cod_liquidacao', '000000000000000...')\n#3 /var/www/transparencia/modules/transparencia/liquidacao.class.php(42): Doctrine_Access->__set('cod_liquidacao', '000000000000000...')\n#4 /var/www/transparencia/modules/transparencia/liquidacao.bo.php(65): Liquidacao->setCodLiquidacao('000000000000000...')\n#5 /var/www/transparencia/scripts/importacao.script.php(255): LiquidacaoBO::import('../uploads/1/re...', 67, Object(Doctrine_Connection_Pgsql))\n#6 {main}	2013-04-08 11:58:21
72	1	../uploads/1/remessas/remessas.zip	BALANCETERECEITA.TXT	SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "00000010000000000000" is out of range for type integer	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "00000010000000000000" is out of range for type integer' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteReceita))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteReceita))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteReceita))\n#7 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(83): BalanceteReceitaBO::create(Object(BalanceteReceita), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(215): BalanceteReceitaBO::import('../uploads/1/re...', 31, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 14:11:06
73	1	../uploads/1/remessas/remessas.zip	BALANCETERECEITA.TXT	SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "00000010000000000000" is out of range for type integer	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "00000010000000000000" is out of range for type integer' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteReceita))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteReceita))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteReceita))\n#7 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(83): BalanceteReceitaBO::create(Object(BalanceteReceita), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(215): BalanceteReceitaBO::import('../uploads/1/re...', 32, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 14:47:03
74	1	../uploads/1/remessas/remessas.zip	BALANCETERECEITA.TXT	SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  numeric field overflow\nDETAIL:  The absolute value is greater than or equal to 10^12 for field with precision 14, scale 2.	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  numeric field overflow\nDETAIL:  The absolute value is greater than or equal to 10^12 for field with precision 14, scale 2.' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteReceita))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteReceita))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteReceita))\n#7 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(83): BalanceteReceitaBO::create(Object(BalanceteReceita), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(215): BalanceteReceitaBO::import('../uploads/1/re...', 33, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 14:49:16
75	1	../uploads/1/remessas/remessas.zip	BALANCETERECEITA.TXT	SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  numeric field overflow\nDETAIL:  The absolute value is greater than or equal to 10^12 for field with precision 14, scale 2.	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  numeric field overflow\nDETAIL:  The absolute value is greater than or equal to 10^12 for field with precision 14, scale 2.' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteReceita))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteReceita))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteReceita))\n#7 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(83): BalanceteReceitaBO::create(Object(BalanceteReceita), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(215): BalanceteReceitaBO::import('../uploads/1/re...', 34, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 14:51:51
76	1	../uploads/1/remessas/remessas.zip	BALANCETERECEITA.TXT	SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "00000010000000000000" is out of range for type integer	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "00000010000000000000" is out of range for type integer' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteReceita))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteReceita))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteReceita))\n#7 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(83): BalanceteReceitaBO::create(Object(BalanceteReceita), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(215): BalanceteReceitaBO::import('../uploads/1/re...', 37, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 15:16:26
77	1	../uploads/1/remessas/remessas.zip	BALANCETERECEITA.TXT	SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "00000010000000000000" is out of range for type integer	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "00000010000000000000" is out of range for type integer' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteReceita))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteReceita))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteReceita))\n#7 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(86): BalanceteReceitaBO::create(Object(BalanceteReceita), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(215): BalanceteReceitaBO::import('../uploads/1/re...', 41, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 15:49:00
78	1	../uploads/1/remessas/remessas.zip	BALANCETERECEITA.TXT	SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "00000010000000000000" is out of range for type integer	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "00000010000000000000" is out of range for type integer' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteReceita))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteReceita))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteReceita))\n#7 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(86): BalanceteReceitaBO::create(Object(BalanceteReceita), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(215): BalanceteReceitaBO::import('../uploads/1/re...', 42, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 15:58:40
79	1	../uploads/1/remessas/remessas.zip	BALANCETERECEITA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000-92871099"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000-92871099"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteReceita))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteReceita))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteReceita))\n#7 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/balancetereceita.bo.php(83): BalanceteReceitaBO::create(Object(BalanceteReceita), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(215): BalanceteReceitaBO::import('../uploads/1/re...', 44, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 16:49:17
80	1	../uploads/1/remessas/remessas.zip	COMPRAS.TXT	Unknown method Compra::setExercicioEntidade	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown method Compra::setExercicioEntidade' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php:2658\nStack trace:\n#0 /var/www/transparencia/modules/transparencia/compra.bo.php(62): Doctrine_Record->__call('setExercicioEnt...', Array)\n#1 /var/www/transparencia/modules/transparencia/compra.bo.php(62): Compra->setExercicioEntidade('2013')\n#2 /var/www/transparencia/scripts/importacao.script.php(227): CompraBO::import('../uploads/1/re...', 47, Object(Doctrine_Connection_Pgsql))\n#3 {main}	2013-04-05 17:36:22
81	1	../uploads/1/remessas/remessas.zip	COMPRAS.TXT	Unknown method Compra::setDescricaoLicitacao	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown method Compra::setDescricaoLicitacao' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php:2658\nStack trace:\n#0 /var/www/transparencia/modules/transparencia/compra.bo.php(70): Doctrine_Record->__call('setDescricaoLic...', Array)\n#1 /var/www/transparencia/modules/transparencia/compra.bo.php(70): Compra->setDescricaoLicitacao('AQUISI??O DE MA...')\n#2 /var/www/transparencia/scripts/importacao.script.php(227): CompraBO::import('../uploads/1/re...', 48, Object(Doctrine_Connection_Pgsql))\n#3 {main}	2013-04-05 17:49:29
148	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/config.xml	11	Unknown method Importacao::setTimestampRemessa	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown method Importacao::setTimestampRemessa' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php:2658\nStack trace:\n#0 /var/www/transparencia/scripts/importacao.script.php(200): Doctrine_Record->__call('setTimestampRem...', Array)\n#1 /var/www/transparencia/scripts/importacao.script.php(200): Importacao->setTimestampRemessa(Object(SimpleXMLElement))\n#2 {main}	2013-05-20 11:24:12
82	1	../uploads/1/remessas/remessas.zip	CREDOR.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "MA"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "MA"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Credor))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Credor))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Credor))\n#7 /var/www/transparencia/modules/transparencia/credor.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/credor.bo.php(66): CredorBO::create(Object(Credor), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(231): CredorBO::import('../uploads/1/re...', 50, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-05 17:56:07
83	1	../uploads/1/remessas/remessas.zip	CREDOR.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "MA"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "MA"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Credor))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Credor))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Credor))\n#7 /var/www/transparencia/modules/transparencia/credor.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/credor.bo.php(66): CredorBO::create(Object(Credor), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(231): CredorBO::import('../uploads/1/re...', 51, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 09:14:14
84	1	../uploads/1/remessas/remessas.zip	CREDOR.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "MA"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "MA"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Credor))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Credor))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Credor))\n#7 /var/www/transparencia/modules/transparencia/credor.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/credor.bo.php(66): CredorBO::create(Object(Credor), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(231): CredorBO::import('../uploads/1/re...', 52, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 10:06:26
85	1	../uploads/1/remessas/remessas.zip	CREDOR.TXT	SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "95938842034   " is out of range for type integer	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "95938842034   " is out of range for type integer' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Credor))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Credor))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Credor))\n#7 /var/www/transparencia/modules/transparencia/credor.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/credor.bo.php(66): CredorBO::create(Object(Credor), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(231): CredorBO::import('../uploads/1/re...', 53, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 10:11:29
160	1	../uploads/4786a3baa11537c52442da283829e9b2/remessas/20130723075348.zip	nofile	Não foi possível abrir o arquivo 20130723075348.zip<br/>	exception 'Exception' with message 'Não foi possível abrir o arquivo 20130723075348.zip<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:175\nStack trace:\n#0 {main}	2013-07-23 08:01:02
86	1	../uploads/1/remessas/remessas.zip	CREDOR.TXT	SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "95938842034" is out of range for type integer	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "95938842034" is out of range for type integer' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Credor))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Credor))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Credor))\n#7 /var/www/transparencia/modules/transparencia/credor.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/credor.bo.php(66): CredorBO::create(Object(Credor), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(231): CredorBO::import('../uploads/1/re...', 54, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 10:13:15
87	1	../uploads/1/remessas/remessas.zip	CREDOR.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Credor))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Credor))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Credor))\n#7 /var/www/transparencia/modules/transparencia/credor.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/credor.bo.php(66): CredorBO::create(Object(Credor), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(231): CredorBO::import('../uploads/1/re...', 55, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 10:22:25
88	1	../uploads/1/remessas/remessas.zip	CREDOR.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Credor))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Credor))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Credor))\n#7 /var/www/transparencia/modules/transparencia/credor.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/credor.bo.php(66): CredorBO::create(Object(Credor), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(231): CredorBO::import('../uploads/1/re...', 56, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 10:23:16
89	1	../uploads/1/remessas/remessas.zip	EMPENHO.TXT	SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "031901174000000" is out of range for type integer	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "031901174000000" is out of range for type integer' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Empenho))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Empenho))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Empenho))\n#7 /var/www/transparencia/modules/transparencia/empenho.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/empenho.bo.php(83): EmpenhoBO::create(Object(Empenho), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(235): EmpenhoBO::import('../uploads/1/re...', 58, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 10:30:01
161	1	../uploads/4786a3baa11537c52442da283829e9b2/remessas/20130723092223.zip	nofile	Não foi possível abrir o arquivo 20130723092223.zip<br/>	exception 'Exception' with message 'Não foi possível abrir o arquivo 20130723092223.zip<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:175\nStack trace:\n#0 {main}	2013-07-23 09:26:01
90	1	../uploads/1/remessas/remessas.zip	EMPENHO.TXT	SQLSTATE[22008]: Datetime field overflow: 7 ERROR:  date/time field value out of range: "18122012"\nHINT:  Perhaps you need a different "datestyle" setting.	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22008]: Datetime field overflow: 7 ERROR:  date/time field value out of range: "18122012"\nHINT:  Perhaps you need a different "datestyle" setting.' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Empenho))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Empenho))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Empenho))\n#7 /var/www/transparencia/modules/transparencia/empenho.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/empenho.bo.php(83): EmpenhoBO::create(Object(Empenho), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(235): EmpenhoBO::import('../uploads/1/re...', 59, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 10:47:47
91	1	../uploads/1/remessas/remessas.zip	ENTIDADES.TXT	SQLSTATE[22001]: String data, right truncated: 7 ERROR:  value too long for type character varying(160)	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22001]: String data, right truncated: 7 ERROR:  value too long for type character varying(160)' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Entidade))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Entidade))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Entidade))\n#7 /var/www/transparencia/modules/transparencia/entidade.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/entidade.bo.php(65): EntidadeBO::create(Object(Entidade), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(239): EntidadeBO::import('../uploads/1/re...', 61, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 11:08:22
92	1	../uploads/1/remessas/remessas.zip	FUNCOES.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "0501LEG"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "0501LEG"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Funcao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Funcao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Funcao))\n#7 /var/www/transparencia/modules/transparencia/funcao.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/funcao.bo.php(66): FuncaoBO::create(Object(Funcao), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(247): FuncaoBO::import('../uploads/1/re...', 63, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 11:25:07
93	1	../uploads/1/remessas/remessas.zip	LIQUIDACAO.TXT	Unknown method Liquidacao::setCodEmpenho	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown method Liquidacao::setCodEmpenho' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php:2658\nStack trace:\n#0 /var/www/transparencia/modules/transparencia/liquidacao.bo.php(63): Doctrine_Record->__call('setCodEmpenho', Array)\n#1 /var/www/transparencia/modules/transparencia/liquidacao.bo.php(63): Liquidacao->setCodEmpenho('0000000000001')\n#2 /var/www/transparencia/scripts/importacao.script.php(255): LiquidacaoBO::import('../uploads/1/re...', 65, Object(Doctrine_Connection_Pgsql))\n#3 {main}	2013-04-08 11:44:45
94	1	../uploads/1/remessas/remessas.zip	LIQUIDACAO.TXT	Unknown method Liquidacao::setCodLiquidacao	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown method Liquidacao::setCodLiquidacao' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php:2658\nStack trace:\n#0 /var/www/transparencia/modules/transparencia/liquidacao.bo.php(65): Doctrine_Record->__call('setCodLiquidaca...', Array)\n#1 /var/www/transparencia/modules/transparencia/liquidacao.bo.php(65): Liquidacao->setCodLiquidacao('000000000000000...')\n#2 /var/www/transparencia/scripts/importacao.script.php(255): LiquidacaoBO::import('../uploads/1/re...', 66, Object(Doctrine_Connection_Pgsql))\n#3 {main}	2013-04-08 11:53:07
113	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	CARGOS.TXT	Unknown method Cargo::setDescricaoCargo	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown method Cargo::setDescricaoCargo' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php:2658\nStack trace:\n#0 /var/www/transparencia/modules/transparencia/cargo.bo.php(65): Doctrine_Record->__call('setDescricaoCar...', Array)\n#1 /var/www/transparencia/modules/transparencia/cargo.bo.php(65): Cargo->setDescricaoCargo('Agente Administ...')\n#2 /var/www/transparencia/scripts/importacao.script.php(219): CargoBO::import('../uploads/e604...', 105, Object(Doctrine_Connection_Pgsql))\n#3 {main}	2013-04-09 10:33:46
96	1	../uploads/1/remessas/remessas.zip	LIQUIDACAO.TXT	Unknown record property / related component "cod_liquidacao" on "Liquidacao"	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown record property / related component "cod_liquidacao" on "Liquidacao"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record/Filter/Standard.php:44\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1504): Doctrine_Record_Filter_Standard->filterSet(Object(Liquidacao), 'cod_liquidacao', '000000000000000...')\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1456): Doctrine_Record->_set('cod_liquidacao', '000000000000000...', true)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Access.php(60): Doctrine_Record->set('cod_liquidacao', '000000000000000...')\n#3 /var/www/transparencia/modules/transparencia/liquidacao.class.php(42): Doctrine_Access->__set('cod_liquidacao', '000000000000000...')\n#4 /var/www/transparencia/modules/transparencia/liquidacao.bo.php(65): Liquidacao->setCodLiquidacao('000000000000000...')\n#5 /var/www/transparencia/scripts/importacao.script.php(255): LiquidacaoBO::import('../uploads/1/re...', 68, Object(Doctrine_Connection_Pgsql))\n#6 {main}	2013-04-08 12:00:50
97	1	../uploads/1/remessas/remessas.zip	LIQUIDACAO.TXT	Unknown record property / related component "cod_liquidacao" on "Liquidacao"	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown record property / related component "cod_liquidacao" on "Liquidacao"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record/Filter/Standard.php:44\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1504): Doctrine_Record_Filter_Standard->filterSet(Object(Liquidacao), 'cod_liquidacao', '000000000000000...')\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1456): Doctrine_Record->_set('cod_liquidacao', '000000000000000...', true)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Access.php(60): Doctrine_Record->set('cod_liquidacao', '000000000000000...')\n#3 /var/www/transparencia/modules/transparencia/liquidacao.class.php(42): Doctrine_Access->__set('cod_liquidacao', '000000000000000...')\n#4 /var/www/transparencia/modules/transparencia/liquidacao.bo.php(65): Liquidacao->setCodLiquidacao('000000000000000...')\n#5 /var/www/transparencia/scripts/importacao.script.php(255): LiquidacaoBO::import('../uploads/1/re...', 69, Object(Doctrine_Connection_Pgsql))\n#6 {main}	2013-04-08 12:03:53
98	1	../uploads/1/remessas/remessas.zip	LIQUIDACAO.TXT	Unknown record property / related component "cod_liquidacao" on "Liquidacao"	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown record property / related component "cod_liquidacao" on "Liquidacao"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record/Filter/Standard.php:44\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1504): Doctrine_Record_Filter_Standard->filterSet(Object(Liquidacao), 'cod_liquidacao', '000000000000000...')\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1456): Doctrine_Record->_set('cod_liquidacao', '000000000000000...', true)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Access.php(60): Doctrine_Record->set('cod_liquidacao', '000000000000000...')\n#3 /var/www/transparencia/modules/transparencia/liquidacao.class.php(42): Doctrine_Access->__set('cod_liquidacao', '000000000000000...')\n#4 /var/www/transparencia/modules/transparencia/liquidacao.bo.php(65): Liquidacao->setCodLiquidacao('000000000000000...')\n#5 /var/www/transparencia/scripts/importacao.script.php(255): LiquidacaoBO::import('../uploads/1/re...', 70, Object(Doctrine_Connection_Pgsql))\n#6 {main}	2013-04-08 12:10:09
99	1	../uploads/1/remessas/remessas.zip	LIQUIDACAO.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000000006220+L"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000000006220+L"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Liquidacao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Liquidacao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Liquidacao))\n#7 /var/www/transparencia/modules/transparencia/liquidacao.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/liquidacao.bo.php(73): LiquidacaoBO::create(Object(Liquidacao), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(255): LiquidacaoBO::import('../uploads/1/re...', 75, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 14:22:00
100	1	../uploads/1/remessas/remessas.zip	ORGAO.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Orgao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Orgao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Orgao))\n#7 /var/www/transparencia/modules/transparencia/orgao.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/orgao.bo.php(64): OrgaoBO::create(Object(Orgao), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(259): OrgaoBO::import('ORGAO.TXT', 77, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 14:33:03
162	1	../uploads/4786a3baa11537c52442da283829e9b2/remessas/config.xml	ACOES.TXT	Arquivo <b>../uploads/4786a3baa11537c52442da283829e9b2/remessas/ACOES.TXT</b> NÃO encontrado.<br/>	exception 'Exception' with message 'Arquivo <b>../uploads/4786a3baa11537c52442da283829e9b2/remessas/ACOES.TXT</b> NÃO encontrado.<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:340\nStack trace:\n#0 {main}	2013-07-23 10:03:03
101	1	../uploads/1/remessas/remessas.zip	PAGAMENTO.TXT	SQLSTATE[22008]: Datetime field overflow: 7 ERROR:  date/time field value out of range: "03012013"\nHINT:  Perhaps you need a different "datestyle" setting.	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22008]: Datetime field overflow: 7 ERROR:  date/time field value out of range: "03012013"\nHINT:  Perhaps you need a different "datestyle" setting.' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Pagamento))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Pagamento))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Pagamento))\n#7 /var/www/transparencia/modules/transparencia/pagamento.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/pagamento.bo.php(68): PagamentoBO::create(Object(Pagamento), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(263): PagamentoBO::import('../uploads/1/re...', 78, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 14:35:23
102	1	../uploads/1/remessas/remessas.zip	PUBLICACAOEDITAL.TXT	Unknown method PublicacaoEdital::setNumEdital	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown method PublicacaoEdital::setNumEdital' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php:2658\nStack trace:\n#0 /var/www/transparencia/modules/transparencia/publicacaoedital.bo.php(61): Doctrine_Record->__call('setNumEdital', Array)\n#1 /var/www/transparencia/modules/transparencia/publicacaoedital.bo.php(61): PublicacaoEdital->setNumEdital('00000007')\n#2 /var/www/transparencia/scripts/importacao.script.php(271): PublicacaoEditalBO::import('../uploads/1/re...', 83, Object(Doctrine_Connection_Pgsql))\n#3 {main}	2013-04-08 15:39:36
103	1	../uploads/1/remessas/remessas.zip	PUBLICACAOEDITAL.TXT	Unknown method PublicacaoEdital::setExercicioLicitacao	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown method PublicacaoEdital::setExercicioLicitacao' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php:2658\nStack trace:\n#0 /var/www/transparencia/modules/transparencia/publicacaoedital.bo.php(62): Doctrine_Record->__call('setExercicioLic...', Array)\n#1 /var/www/transparencia/modules/transparencia/publicacaoedital.bo.php(62): PublicacaoEdital->setExercicioLicitacao('2012')\n#2 /var/www/transparencia/scripts/importacao.script.php(271): PublicacaoEditalBO::import('../uploads/1/re...', 84, Object(Doctrine_Connection_Pgsql))\n#3 {main}	2013-04-08 15:42:51
104	1	../uploads/1/remessas/remessas.zip	PUBLICACAOEDITAL.TXT	SQLSTATE[22008]: Datetime field overflow: 7 ERROR:  date/time field value out of range: "16012012"\nHINT:  Perhaps you need a different "datestyle" setting.	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22008]: Datetime field overflow: 7 ERROR:  date/time field value out of range: "16012012"\nHINT:  Perhaps you need a different "datestyle" setting.' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(PublicacaoEdital))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(PublicacaoEdital))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(PublicacaoEdital))\n#7 /var/www/transparencia/modules/transparencia/publicacaoedital.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/publicacaoedital.bo.php(70): PublicacaoEditalBO::create(Object(PublicacaoEdital), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(271): PublicacaoEditalBO::import('../uploads/1/re...', 85, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 15:57:34
105	1	../uploads/1/remessas/remessas.zip	RUBRICA.TXT	SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "031900500020000" is out of range for type integer	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "031900500020000" is out of range for type integer' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Rubrica))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Rubrica))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Rubrica))\n#7 /var/www/transparencia/modules/transparencia/rubrica.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/rubrica.bo.php(65): RubricaBO::create(Object(Rubrica), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(283): RubricaBO::import('../uploads/1/re...', 87, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 16:10:34
106	1	../uploads/1/remessas/remessas.zip	SERVIDORES.TXT	Unknown method Servidor::setDescricaoRegimeSubdivisaoFuncao	exception 'Doctrine_Record_UnknownPropertyException' with message 'Unknown method Servidor::setDescricaoRegimeSubdivisaoFuncao' in /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php:2658\nStack trace:\n#0 /var/www/transparencia/modules/transparencia/servidor.bo.php(69): Doctrine_Record->__call('setDescricaoReg...', Array)\n#1 /var/www/transparencia/modules/transparencia/servidor.bo.php(69): Servidor->setDescricaoRegimeSubdivisaoFuncao('Contrato emerge...')\n#2 /var/www/transparencia/scripts/importacao.script.php(287): ServidorBO::import('../uploads/1/re...', 91, Object(Doctrine_Connection_Pgsql))\n#3 {main}	2013-04-08 16:42:46
107	1	../uploads/1/remessas/remessas.zip	SERVIDORES.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "01/2013"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "01/2013"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Servidor))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Servidor))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Servidor))\n#7 /var/www/transparencia/modules/transparencia/servidor.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/servidor.bo.php(78): ServidorBO::create(Object(Servidor), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(287): ServidorBO::import('../uploads/1/re...', 92, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 16:50:02
108	1	../uploads/1/remessas/remessas.zip	SERVIDORES.TXT	SQLSTATE[22008]: Datetime field overflow: 7 ERROR:  date/time field value out of range: "03112011"\nHINT:  Perhaps you need a different "datestyle" setting.	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22008]: Datetime field overflow: 7 ERROR:  date/time field value out of range: "03112011"\nHINT:  Perhaps you need a different "datestyle" setting.' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Servidor))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Servidor))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Servidor))\n#7 /var/www/transparencia/modules/transparencia/servidor.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/servidor.bo.php(78): ServidorBO::create(Object(Servidor), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(287): ServidorBO::import('../uploads/1/re...', 93, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 16:52:22
109	1	../uploads/1/remessas/remessas.zip	SERVIDORES.TXT	SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type date: "    -  -  "	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type date: "    -  -  "' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Servidor))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Servidor))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Servidor))\n#7 /var/www/transparencia/modules/transparencia/servidor.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/servidor.bo.php(80): ServidorBO::create(Object(Servidor), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(287): ServidorBO::import('../uploads/1/re...', 94, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 17:00:02
110	1	../uploads/1/remessas/remessas.zip	SERVIDORES.TXT	SQLSTATE[23502]: Not null violation: 7 ERROR:  null value in column "dt_rescisao" violates not-null constraint	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[23502]: Not null violation: 7 ERROR:  null value in column "dt_rescisao" violates not-null constraint' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Servidor))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Servidor))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Servidor))\n#7 /var/www/transparencia/modules/transparencia/servidor.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/servidor.bo.php(84): ServidorBO::create(Object(Servidor), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(287): ServidorBO::import('../uploads/1/re...', 95, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-08 17:02:58
114	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	CARGOS.TXT	SQLSTATE[22008]: Datetime field overflow: 7 ERROR:  date/time field value out of range: "16072008"\nHINT:  Perhaps you need a different "datestyle" setting.	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22008]: Datetime field overflow: 7 ERROR:  date/time field value out of range: "16072008"\nHINT:  Perhaps you need a different "datestyle" setting.' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Cargo))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Cargo))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Cargo))\n#7 /var/www/transparencia/modules/transparencia/cargo.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/cargo.bo.php(78): CargoBO::create(Object(Cargo), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(219): CargoBO::import('../uploads/e604...', 106, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-09 10:35:30
115	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	CEDIDOSADIDOS.TXT	SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type date: "    -  -  "	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type date: "    -  -  "' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(CedidoAdido))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(CedidoAdido))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(CedidoAdido))\n#7 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(80): CedidoAdidoBO::create(Object(CedidoAdido), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(223): CedidoAdidoBO::import('../uploads/e604...', 107, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-09 10:36:47
116	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	CEDIDOSADIDOS.TXT	SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type date: "    -  -  "	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type date: "    -  -  "' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(CedidoAdido))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(CedidoAdido))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(CedidoAdido))\n#7 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(80): CedidoAdidoBO::create(Object(CedidoAdido), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(223): CedidoAdidoBO::import('../uploads/e604...', 108, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-09 10:54:14
117	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	CEDIDOSADIDOS.TXT	SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type date: "    -  -  "	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type date: "    -  -  "' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(CedidoAdido))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(CedidoAdido))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(CedidoAdido))\n#7 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(82): CedidoAdidoBO::create(Object(CedidoAdido), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(223): CedidoAdidoBO::import('../uploads/e604...', 110, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-09 11:21:32
118	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	CEDIDOSADIDOS.TXT	SQLSTATE[23502]: Not null violation: 7 ERROR:  null value in column "dt_final" violates not-null constraint	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[23502]: Not null violation: 7 ERROR:  null value in column "dt_final" violates not-null constraint' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(CedidoAdido))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(CedidoAdido))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(CedidoAdido))\n#7 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(82): CedidoAdidoBO::create(Object(CedidoAdido), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(223): CedidoAdidoBO::import('../uploads/e604...', 114, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-09 11:30:48
119	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	ESTAGIARIOS.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Estagiario))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Estagiario))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Estagiario))\n#7 /var/www/transparencia/modules/transparencia/estagiario.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/estagiario.bo.php(82): EstagiarioBO::create(Object(Estagiario), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(243): EstagiarioBO::import('ESTAGIARIOS.TXT', 116, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-09 11:50:34
120	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	ESTAGIARIOS.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Estagiario))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Estagiario))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Estagiario))\n#7 /var/www/transparencia/modules/transparencia/estagiario.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/estagiario.bo.php(82): EstagiarioBO::create(Object(Estagiario), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(243): EstagiarioBO::import('../uploads/e604...', 117, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-09 11:52:53
121	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	ESTAGIARIOS.TXT	SQLSTATE[23502]: Not null violation: 7 ERROR:  null value in column "data_fim" violates not-null constraint	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[23502]: Not null violation: 7 ERROR:  null value in column "data_fim" violates not-null constraint' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Estagiario))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Estagiario))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Estagiario))\n#7 /var/www/transparencia/modules/transparencia/estagiario.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/estagiario.bo.php(82): EstagiarioBO::create(Object(Estagiario), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(243): EstagiarioBO::import('../uploads/e604...', 118, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-09 11:57:56
122	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	LICITACAO.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "Convite                                           "	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "Convite                                           "' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Licitacao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Licitacao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Licitacao))\n#7 /var/www/transparencia/modules/transparencia/licitacao.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/licitacao.bo.php(71): LicitacaoBO::create(Object(Licitacao), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(278): LicitacaoBO::import('../uploads/e604...', 129, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-17 09:09:12
123	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	ACOES.TXT	Arquivo <b>../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/ACOES.TXT</b> NÃO encontrado.<br/>	exception 'Exception' with message 'Arquivo <b>../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/ACOES.TXT</b> NÃO encontrado.<br/>' in /var/www/transparencia/scripts/importacao.script.php:230\nStack trace:\n#0 {main}	2013-04-17 09:09:13
124	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	LICITACAO.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "Convite                                           "	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "Convite                                           "' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Licitacao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Licitacao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Licitacao))\n#7 /var/www/transparencia/modules/transparencia/licitacao.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/licitacao.bo.php(71): LicitacaoBO::create(Object(Licitacao), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(278): LicitacaoBO::import('../uploads/e604...', 131, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-17 09:14:15
125	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	PUBLICACAOEDITAL.TXT	SQLSTATE[22001]: String data, right truncated: 7 ERROR:  value too long for type character varying(50)	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22001]: String data, right truncated: 7 ERROR:  value too long for type character varying(50)' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(PublicacaoEdital))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(PublicacaoEdital))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(PublicacaoEdital))\n#7 /var/www/transparencia/modules/transparencia/publicacaoedital.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/publicacaoedital.bo.php(71): PublicacaoEditalBO::create(Object(PublicacaoEdital), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(298): PublicacaoEditalBO::import('../uploads/e604...', 134, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-17 09:22:02
140	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	ITEM.TXT	SQLSTATE[42P01]: Undefined table: 7 ERROR:  relation "transparencia.item_id_seq" does not exist. Failing Query: "SELECT CURRVAL('transparencia.item_id_seq')"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[42P01]: Undefined table: 7 ERROR:  relation "transparencia.item_id_seq" does not exist. Failing Query: "SELECT CURRVAL('transparencia.item_id_seq')"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1025): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Pgsql), 'SELECT CURRVAL(...')\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(813): Doctrine_Connection->execute('SELECT CURRVAL(...', Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Sequence/Pgsql.php(82): Doctrine_Connection->fetchOne('SELECT CURRVAL(...')\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(947): Doctrine_Sequence_Pgsql->lastInsertId('transparencia.i...')\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(648): Doctrine_Connection_UnitOfWork->_assignIdentifier(Object(Item))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Item))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Item))\n#7 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Item))\n#8 /var/www/transparencia/modules/transparencia/item.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/modules/transparencia/item.bo.php(107): ItemBO::create(Object(Item), Object(Doctrine_Connection_Pgsql))\n#10 /var/www/transparencia/scripts/importacao.script.php(282): ItemBO::import('../uploads/e604...', 174, Object(Doctrine_Connection_Pgsql))\n#11 {main}	2013-05-17 16:54:31
126	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	PUBLICACAOEDITAL.TXT	SQLSTATE[22001]: String data, right truncated: 7 ERROR:  value too long for type character varying(50)	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22001]: String data, right truncated: 7 ERROR:  value too long for type character varying(50)' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(PublicacaoEdital))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(PublicacaoEdital))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(PublicacaoEdital))\n#7 /var/www/transparencia/modules/transparencia/publicacaoedital.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/publicacaoedital.bo.php(71): PublicacaoEditalBO::create(Object(PublicacaoEdital), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(298): PublicacaoEditalBO::import('../uploads/e604...', 136, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-17 09:31:01
127	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	PUBLICACAOEDITAL.TXT	SQLSTATE[22001]: String data, right truncated: 7 ERROR:  value too long for type character varying(50)	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22001]: String data, right truncated: 7 ERROR:  value too long for type character varying(50)' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(PublicacaoEdital))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(PublicacaoEdital))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(PublicacaoEdital))\n#7 /var/www/transparencia/modules/transparencia/publicacaoedital.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/publicacaoedital.bo.php(71): PublicacaoEditalBO::create(Object(PublicacaoEdital), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(298): PublicacaoEditalBO::import('../uploads/e604...', 137, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-04-17 09:32:43
128	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/ExportacaoArquivosPortalTransparencia.zip	CEDIDOSADIDOS.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(CedidoAdido))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(CedidoAdido))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(CedidoAdido))\n#7 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(70): CedidoAdidoBO::create(Object(CedidoAdido), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(251): CedidoAdidoBO::import('../uploads/e604...', 148, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-05-07 14:28:51
129	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/config.xml	CEDIDOSADIDOS.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(CedidoAdido))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(CedidoAdido))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(CedidoAdido))\n#7 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(70): CedidoAdidoBO::create(Object(CedidoAdido), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(251): CedidoAdidoBO::import('../uploads/e604...', 151, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-05-07 14:40:16
130	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/ExportacaoArquivosPortalTransparencia.zip	CEDIDOSADIDOS.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: ""' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(CedidoAdido))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(CedidoAdido))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(CedidoAdido))\n#7 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(70): CedidoAdidoBO::create(Object(CedidoAdido), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(253): CedidoAdidoBO::import('../uploads/e604...', 152, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-05-07 14:44:28
131	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/ExportacaoArquivosPortalTransparencia.zip	REMUNERACAO.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000000-410.49"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000000-410.49"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Remuneracao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Remuneracao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Remuneracao))\n#7 /var/www/transparencia/modules/transparencia/remuneracao.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/remuneracao.bo.php(66): RemuneracaoBO::create(Object(Remuneracao), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(306): RemuneracaoBO::import('../uploads/e604...', 153, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-05-07 14:47:54
132	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/config.xml	REMUNERACAO.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000000-410.49"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000000-410.49"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Remuneracao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Remuneracao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Remuneracao))\n#7 /var/www/transparencia/modules/transparencia/remuneracao.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/remuneracao.bo.php(66): RemuneracaoBO::create(Object(Remuneracao), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(306): RemuneracaoBO::import('../uploads/e604...', 155, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-05-07 15:46:08
133	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/ExportacaoArquivosPortalTransparencia.zip	LIQUIDACAO.TXT	SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "2013020000001" is out of range for type integer	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "2013020000001" is out of range for type integer' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Liquidacao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Liquidacao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Liquidacao))\n#7 /var/www/transparencia/modules/transparencia/liquidacao.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/liquidacao.bo.php(73): LiquidacaoBO::create(Object(Liquidacao), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(282): LiquidacaoBO::import('../uploads/e604...', 157, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-05-07 16:09:28
134	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/ExportacaoArquivosPortalTransparencia.zip	CEDIDOSADIDOS.TXT	SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type date: "--"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type date: "--"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(CedidoAdido))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(CedidoAdido))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(CedidoAdido))\n#7 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(70): CedidoAdidoBO::create(Object(CedidoAdido), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(250): CedidoAdidoBO::import('../uploads/e604...', 160, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-05-09 08:51:40
135	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/ExportacaoArquivosPortalTransparencia.zip	CEDIDOSADIDOS.TXT	SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type date: "--"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type date: "--"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(CedidoAdido))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(CedidoAdido))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(CedidoAdido))\n#7 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/cedidoadido.bo.php(70): CedidoAdidoBO::create(Object(CedidoAdido), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(250): CedidoAdidoBO::import('../uploads/e604...', 161, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-05-09 08:53:45
138	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	ITEM.TXT	SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "2013020001238" is out of range for type integer	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22003]: Numeric value out of range: 7 ERROR:  value "2013020001238" is out of range for type integer' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Item))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Item))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Item))\n#7 /var/www/transparencia/modules/transparencia/item.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/item.bo.php(102): ItemBO::create(Object(Item), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(282): ItemBO::import('../uploads/e604...', 172, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-05-17 16:15:46
139	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/remessas.zip	ITEM.TXT	SQLSTATE[22008]: Datetime field overflow: 7 ERROR:  date/time field value out of range: "06052013"\nHINT:  Perhaps you need a different "datestyle" setting.	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22008]: Datetime field overflow: 7 ERROR:  date/time field value out of range: "06052013"\nHINT:  Perhaps you need a different "datestyle" setting.' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Item))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Item))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Item))\n#7 /var/www/transparencia/modules/transparencia/item.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/modules/transparencia/item.bo.php(102): ItemBO::create(Object(Item), Object(Doctrine_Connection_Pgsql))\n#9 /var/www/transparencia/scripts/importacao.script.php(282): ItemBO::import('../uploads/e604...', 173, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-05-17 16:48:47
150	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/config.xml	11	SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type timestamp: "20130520112039"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type timestamp: "20130520112039"' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Importacao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Importacao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Importacao))\n#7 /var/www/transparencia/modules/importacao/importacao.bo.php(8): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/scripts/importacao.script.php(202): ImportacaoBO::create(Object(Importacao), Object(Doctrine_Connection_Pgsql))\n#9 {main}	2013-05-20 11:25:44
151	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/config.xml~	11	SQLSTATE[22008]: Datetime field overflow: 7 ERROR:  date/time field value out of range: "30/04/2013"\nHINT:  Perhaps you need a different "datestyle" setting.	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22008]: Datetime field overflow: 7 ERROR:  date/time field value out of range: "30/04/2013"\nHINT:  Perhaps you need a different "datestyle" setting.' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Importacao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Importacao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Importacao))\n#7 /var/www/transparencia/modules/importacao/importacao.bo.php(8): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/scripts/importacao.script.php(202): ImportacaoBO::create(Object(Importacao), Object(Doctrine_Connection_Pgsql))\n#9 {main}	2013-05-20 11:29:07
152	1	../uploads/e60408e9a55027070e3caf0550d2b4df/remessas/20130520112039.zip	11	SQLSTATE[23502]: Not null violation: 7 ERROR:  null value in column "timestamp_remessa" violates not-null constraint	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[23502]: Not null violation: 7 ERROR:  null value in column "timestamp_remessa" violates not-null constraint' in /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Importacao))\n#5 /var/www/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Importacao))\n#6 /var/www/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Importacao))\n#7 /var/www/transparencia/modules/importacao/importacao.bo.php(8): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /var/www/transparencia/scripts/importacao.script.php(202): ImportacaoBO::create(Object(Importacao), Object(Doctrine_Connection_Pgsql))\n#9 {main}	2013-05-20 11:31:21
153	2	../uploads/4786a3baa11537c52442da283829e9b2/remessas/20130521.zip	11	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000000-112.49"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000000-112.49"' in /home/diogo/Project/Transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /home/diogo/Project/Transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /home/diogo/Project/Transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /home/diogo/Project/Transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /home/diogo/Project/Transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /home/diogo/Project/Transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteReceita))\n#5 /home/diogo/Project/Transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteReceita))\n#6 /home/diogo/Project/Transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteReceita))\n#7 /home/diogo/Project/Transparencia/modules/transparencia/balancetereceita.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /home/diogo/Project/Transparencia/modules/transparencia/balancetereceita.bo.php(179): BalanceteReceitaBO::create(Object(BalanceteReceita), Object(Doctrine_Connection_Pgsql))\n#9 /home/diogo/Project/Transparencia/scripts/importacao.script.php(245): BalanceteReceitaBO::import('../uploads/4786...', 196, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-05-24 09:51:52
156	2	../uploads/4786a3baa11537c52442da283829e9b2/remessas/20130722091001.zip	nofile	Não foi possível abrir o arquivo 20130722091001.zip<br/>	exception 'Exception' with message 'Não foi possível abrir o arquivo 20130722091001.zip<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:175\nStack trace:\n#0 {main}	2013-07-22 14:54:33
157	1	../uploads/4786a3baa11537c52442da283829e9b2/remessas/20130722091001.zip	nofile	Não foi possível abrir o arquivo 20130722091001.zip<br/>	exception 'Exception' with message 'Não foi possível abrir o arquivo 20130722091001.zip<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:175\nStack trace:\n#0 {main}	2013-07-22 15:14:27
158	1	../uploads/4786a3baa11537c52442da283829e9b2/remessas/transparencia_20130523.sql	nofile	Não foi possível encontrar o município  através do hash informado.<br/>	exception 'Exception' with message 'Não foi possível encontrar o município  através do hash informado.<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:188\nStack trace:\n#0 {main}	2013-07-22 15:15:51
159	1	../uploads/4786a3baa11537c52442da283829e9b2/remessas/20130722091001.zip	nofile	Não foi possível abrir o arquivo 20130722091001.zip<br/>	exception 'Exception' with message 'Não foi possível abrir o arquivo 20130722091001.zip<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:175\nStack trace:\n#0 {main}	2013-07-22 15:16:51
163	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20130726121502.zip	REMUNERACAO.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "000000000-6.37"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "000000000-6.37"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Remuneracao))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Remuneracao))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Remuneracao))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/remuneracao.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/remuneracao.bo.php(71): RemuneracaoBO::create(Object(Remuneracao), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(318): RemuneracaoBO::import('../uploads/d3c4...', 210, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2013-07-26 13:28:18
164	1	../uploads/4786a3baa11537c52442da283829e9b2/remessas/config.xml	ACOES.TXT	Arquivo <b>../uploads/4786a3baa11537c52442da283829e9b2/remessas/ACOES.TXT</b> NÃO encontrado.<br/>	exception 'Exception' with message 'Arquivo <b>../uploads/4786a3baa11537c52442da283829e9b2/remessas/ACOES.TXT</b> NÃO encontrado.<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:340\nStack trace:\n#0 {main}	2013-07-26 14:57:35
165	1	../uploads/testedevcnm/remessas/20130729112439.zip	nofile	Não foi possível encontrar o município  através do hash informado.<br/>	exception 'Exception' with message 'Não foi possível encontrar o município  através do hash informado.<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:188\nStack trace:\n#0 {main}	2013-07-29 11:26:03
166	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20130812180613.zip	UNIDADES.TXT	Não foi possível abrir o arquivo 20130812180613.zip<br/>	exception 'Exception' with message 'Não foi possível abrir o arquivo 20130812180613.zip<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:175\nStack trace:\n#0 {main}	2013-08-13 08:28:14
167	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20130823095420.zip	nofile	Não foi possível abrir o arquivo 20130823095420.zip<br/>	exception 'Exception' with message 'Não foi possível abrir o arquivo 20130823095420.zip<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:175\nStack trace:\n#0 {main}	2013-08-23 10:01:02
168	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20130823095420.zip	nofile	Não foi possível abrir o arquivo 20130823095420.zip<br/>	exception 'Exception' with message 'Não foi possível abrir o arquivo 20130823095420.zip<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:175\nStack trace:\n#0 {main}	2013-08-23 13:30:40
169	1	../uploads/17ad97f7e86dc1d138816d2dee73d27b/remessas/20130826093927.zip	nofile	Não foi possível abrir o arquivo 20130826093927.zip<br/>	exception 'Exception' with message 'Não foi possível abrir o arquivo 20130826093927.zip<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:175\nStack trace:\n#0 {main}	2013-08-26 09:51:03
170	1	../uploads/17ad97f7e86dc1d138816d2dee73d27b/remessas/20130826102905.zip	nofile	Não foi possível abrir o arquivo 20130826102905.zip<br/>	exception 'Exception' with message 'Não foi possível abrir o arquivo 20130826102905.zip<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:175\nStack trace:\n#0 {main}	2013-08-26 10:51:01
171	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20130912121513.zip	nofile	Não foi possível abrir o arquivo 20130912121513.zip<br/>	exception 'Exception' with message 'Não foi possível abrir o arquivo 20130912121513.zip<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:175\nStack trace:\n#0 {main}	2013-09-12 13:26:01
172	1	../uploads/4786a3baa11537c52442da283829e9b2/remessas/20130920082741.zip	nofile	Não foi possível abrir o arquivo 20130920082741.zip<br/>	exception 'Exception' with message 'Não foi possível abrir o arquivo 20130920082741.zip<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:175\nStack trace:\n#0 {main}	2013-09-20 08:26:03
173	1	../uploads/4786a3baa11537c52442da283829e9b2/remessas/20131016085035.zip	nofile	Não foi possível encontrar o município  através do hash informado.<br/>	exception 'Exception' with message 'Não foi possível encontrar o município  através do hash informado.<br/>' in /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php:188\nStack trace:\n#0 {main}	2013-10-16 08:51:03
174	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20140307114058.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000040000,.00"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000040000,.00"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(493): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(246): BalanceteDespesaBO::import('../uploads/d3c4...', 438, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2014-03-07 12:01:07
175	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20140310112107.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000040000,.00"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000040000,.00"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(493): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(246): BalanceteDespesaBO::import('../uploads/d3c4...', 439, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2014-03-10 12:01:05
176	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20140311164236.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000040000,.00"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000040000,.00"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(493): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(246): BalanceteDespesaBO::import('../uploads/d3c4...', 440, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2014-03-11 18:01:06
177	2	../uploads/4786a3baa11537c52442da283829e9b2/remessas/20140312075249.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000005000,.00"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000005000,.00"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(493): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(246): BalanceteDespesaBO::import('../uploads/4786...', 263, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2014-03-12 08:01:06
178	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20140312113717.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000040000,.00"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000040000,.00"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(493): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(246): BalanceteDespesaBO::import('../uploads/d3c4...', 441, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2014-03-12 12:01:05
179	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20140313090851.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000040000,.00"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000040000,.00"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(493): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(246): BalanceteDespesaBO::import('../uploads/d3c4...', 442, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2014-03-13 10:01:05
180	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20140317144106.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000040000,.00"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000040000,.00"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(493): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(246): BalanceteDespesaBO::import('../uploads/d3c4...', 443, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2014-03-17 16:01:06
181	2	../uploads/4786a3baa11537c52442da283829e9b2/remessas/20140312075249.zip	BALANCETEDESPESA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000005000,.00"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000005000,.00"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteDespesa))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteDespesa))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteDespesa))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetedespesa.bo.php(493): BalanceteDespesaBO::create(Object(BalanceteDespesa), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(246): BalanceteDespesaBO::import('../uploads/4786...', 264, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2014-04-10 10:01:06
182	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/config.xml	BALANCETERECEITA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "S5"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "S5"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteReceita))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteReceita))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteReceita))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetereceita.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetereceita.bo.php(207): BalanceteReceitaBO::create(Object(BalanceteReceita), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(250): BalanceteReceitaBO::import('../uploads/d3c4...', 501, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2014-07-15 16:01:13
183	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20140716172359.zip	BALANCETERECEITA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "S5"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "S5"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteReceita))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteReceita))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteReceita))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetereceita.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetereceita.bo.php(207): BalanceteReceitaBO::create(Object(BalanceteReceita), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(250): BalanceteReceitaBO::import('../uploads/d3c4...', 502, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2014-07-16 20:01:13
184	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20140717082447.zip	BALANCETERECEITA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "S5"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "S5"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteReceita))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteReceita))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteReceita))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetereceita.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetereceita.bo.php(207): BalanceteReceitaBO::create(Object(BalanceteReceita), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(250): BalanceteReceitaBO::import('../uploads/d3c4...', 503, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2014-07-17 12:01:14
185	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20140718143732.zip	BALANCETERECEITA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "S5"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "S5"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteReceita))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteReceita))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteReceita))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetereceita.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetereceita.bo.php(207): BalanceteReceitaBO::create(Object(BalanceteReceita), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(250): BalanceteReceitaBO::import('../uploads/d3c4...', 504, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2014-07-18 16:01:14
186	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20140721085228.zip	BALANCETERECEITA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "S5"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "S5"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteReceita))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteReceita))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteReceita))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetereceita.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetereceita.bo.php(207): BalanceteReceitaBO::create(Object(BalanceteReceita), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(250): BalanceteReceitaBO::import('../uploads/d3c4...', 505, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2014-07-21 12:01:14
187	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20140722082212.zip	BALANCETERECEITA.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "S5"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for integer: "S5"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(BalanceteReceita))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(BalanceteReceita))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(BalanceteReceita))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetereceita.bo.php(7): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/balancetereceita.bo.php(207): BalanceteReceitaBO::create(Object(BalanceteReceita), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(250): BalanceteReceitaBO::import('../uploads/d3c4...', 506, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2014-07-22 12:01:14
326	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/config.xml	UNIDADES.TXT	SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type date: "--"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22007]: Invalid datetime format: 7 ERROR:  invalid input syntax for type date: "--"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Importacao))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Importacao))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Importacao))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/importacao/importacao.bo.php(8): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(210): ImportacaoBO::create(Object(Importacao), Object(Doctrine_Connection_Pgsql))\n#9 {main}	2015-06-17 12:01:08
327	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20150819101451.zip	REMUNERACAO.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "00000000-19.28"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "00000000-19.28"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Remuneracao))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Remuneracao))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Remuneracao))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/remuneracao.bo.php(15): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/remuneracao.bo.php(81): RemuneracaoBO::create(Object(Remuneracao), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(321): RemuneracaoBO::import('../uploads/d3c4...', 678, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2015-08-19 10:19:13
328	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20150819145839.zip	REMUNERACAO.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000000-183.56"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "0000000-183.56"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Remuneracao))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Remuneracao))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Remuneracao))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/remuneracao.bo.php(15): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/remuneracao.bo.php(81): RemuneracaoBO::create(Object(Remuneracao), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(321): RemuneracaoBO::import('../uploads/d3c4...', 679, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2015-08-19 16:02:49
329	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20150819173129.zip	REMUNERACAO.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "00000000-19.28"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "00000000-19.28"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Remuneracao))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Remuneracao))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Remuneracao))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/remuneracao.bo.php(15): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/remuneracao.bo.php(81): RemuneracaoBO::create(Object(Remuneracao), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(321): RemuneracaoBO::import('../uploads/d3c4...', 680, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2015-08-19 17:42:05
330	1	../uploads/d3c410b135250a83f0616d106027c104/remessas/20150819173129.zip	REMUNERACAO.TXT	SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "00000000-19.28"	exception 'Doctrine_Connection_Pgsql_Exception' with message 'SQLSTATE[22P02]: Invalid text representation: 7 ERROR:  invalid input syntax for type numeric: "00000000-19.28"' in /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php:1082\nStack trace:\n#0 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Statement.php(269): Doctrine_Connection->rethrowException(Object(PDOException), Object(Doctrine_Connection_Statement))\n#1 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection.php(1042): Doctrine_Connection_Statement->execute(Array)\n#2 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/Pgsql.php(244): Doctrine_Connection->exec('INSERT INTO tra...', Array)\n#3 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(647): Doctrine_Connection_Pgsql->insert(Object(Doctrine_Table), Array)\n#4 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(571): Doctrine_Connection_UnitOfWork->processSingleInsert(Object(Remuneracao))\n#5 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Connection/UnitOfWork.php(81): Doctrine_Connection_UnitOfWork->insert(Object(Remuneracao))\n#6 /dados/websites/producao/hotsites/urbem/transparencia/core/doctrine/lib/Doctrine/Record.php(1718): Doctrine_Connection_UnitOfWork->saveGraph(Object(Remuneracao))\n#7 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/remuneracao.bo.php(15): Doctrine_Record->save(Object(Doctrine_Connection_Pgsql))\n#8 /dados/websites/producao/hotsites/urbem/transparencia/modules/transparencia/remuneracao.bo.php(81): RemuneracaoBO::create(Object(Remuneracao), Object(Doctrine_Connection_Pgsql))\n#9 /dados/websites/producao/hotsites/urbem/transparencia/scripts/importacao.php(321): RemuneracaoBO::import('../uploads/d3c4...', 681, Object(Doctrine_Connection_Pgsql))\n#10 {main}	2015-08-19 18:08:52
\.


--
-- Name: log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('log_id_seq', 330, true);


--
-- Data for Name: menu; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY menu (id, acao_id, parent_id, url, label, target, posicao) FROM stdin;
1	1	\N	admin/home	Home	_self	1
2	2	\N	admin/usuario	Usuários	_self	3
3	3	2	admin/usuario/new	Cadastrar usuário	_self	3
16	42	\N	admin/grupo	Grupos	_self	4
17	44	16	admin/grupo/new	Adicionar grupo	_self	4
18	61	\N	admin/publicacao	Publicações	_self	2
19	62	18	admin/publicacao/new	Adicionar publicação	_self	2
20	37	\N	admin/configuracao	Configuração	_self	5
21	39	20	admin/configuracao/new	Adicionar configuração	_self	5
4	9	\N	admin/logout	Sair	_self	7
5	10	\N	admin/setConta	Meus dados	_self	6
22	66	\N	admin/configuracaoEntidade	Entidades	_self	5
\.


--
-- Name: menu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('menu_id_seq', 25, false);


--
-- Data for Name: modulo; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY modulo (id, alias, modulo) FROM stdin;
1	usuario	Usuário
2	historico	Histórico
3	contato	Contato
4	conteudo	Conteúdo
5	banner	Banner
\.


--
-- Name: modulo_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('modulo_id_seq', 6, false);


--
-- Data for Name: municipio; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY municipio (id, uf_id, nome, disponivel, hash, db, alias) FROM stdin;
1	1	Mariana Pimentel	1	d3c410b135250a83f0616d106027c104	transparencia_rs_mariana_pimentel	mariana-pimentel
2	20	Itaú	1	4786a3baa11537c52442da283829e9b2	transparencia_rn_itau	itau
3	1	CIDEJA	1	17ad97f7e86dc1d138816d2dee73d27b	transparencia_rs_candiota	cideja
4	11	Bom Despacho	1	aee284ae75ec495d9108bde52a567f29	transparencia_mg_bom_despacho	bom-despacho
\.


--
-- Name: municipio_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('municipio_id_seq', 3, true);


--
-- Data for Name: pessoa; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY pessoa (id, categoria_id, nome, email, observacao, ddd_comercial, telefone_comercial, ddd_celular, telefone_celular, facebook, twitter, created, updated) FROM stdin;
21	\N	Luciano Fontoura	b4dh4@hotmail.com	\N	\N	\N	\N	\N	\N	\N	2013-04-12 17:33:15	2013-04-12 17:33:26.190016
25	\N	urbem Goes	urbem.goes@cnm.org.br	\N	\N	\N	\N	\N	\N	\N	2013-05-22 17:00:35	2013-05-23 11:46:36
26	\N	Gedson Bruno Araújo de Brito	gedsonbruno@yahoo.com.br	\N	\N	\N	\N	\N	\N	\N	2013-05-23 10:22:02	2013-05-24 14:34:37
28	\N	Gelsinho	gelsinho@cnm.com.br	\N	\N	\N	\N	\N	\N	\N	2013-05-24 14:51:22	2013-05-24 14:51:31
1	\N	Suporte	suporte@cnm.org.br	\N	\N	\N	\N	\N	\N	\N	2013-01-22 13:33:21	2013-06-06 14:30:05
27	\N	Teste	teste@cnm.org.br	\N	\N	\N	\N	\N	\N	\N	2013-05-24 14:49:33	2013-06-28 11:59:27
29	\N	Rozane Fatima Andrades	cideja@hotmail.com	\N	\N	\N	\N	\N	\N	\N	2013-08-22 17:42:31	2013-08-22 17:42:31.596715
24	\N	Maurício Brzezinski	informatica@marianapimentel.rs.gov.br	\N	\N	\N	\N	\N	\N	\N	2013-05-22 15:03:49	2014-02-07 09:40:11
30	\N	Wallace Campos Rodrigues	wallace.campos@pmbd.mg.gov.br	\N	\N	\N	\N	\N	\N	\N	2014-10-17 14:27:44	2015-05-18 16:31:10
31	\N	Gelson Wolowski Gonçalves	gelson.goncalves@cnm.org.br	\N	\N	\N	\N	\N	\N	\N	2014-11-03 10:31:33	2015-05-18 16:34:16
\.


--
-- Data for Name: pessoa_fisica; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY pessoa_fisica (id, pessoa_id, cpf, rg, orgao_emissor, data_nascimento, ddd_residencial, telefone_residencial) FROM stdin;
\.


--
-- Name: pessoa_fisica_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('pessoa_fisica_id_seq', 1, false);


--
-- Name: pessoa_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('pessoa_id_seq', 31, true);


--
-- Data for Name: pessoa_juridica; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY pessoa_juridica (id, pessoa_id, cnpj, inscricao_estadual, site) FROM stdin;
\.


--
-- Name: pessoa_juridica_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('pessoa_juridica_id_seq', 1, false);


--
-- Data for Name: uf; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY uf (id, nome, disponivel, sigla) FROM stdin;
1	Rio Grande do Sul	1	RS
2	Alagoas	1	AL
3	Amazonas	1	AM
4	Amapá	1	AP
5	Bahia	1	BA
6	Ceará	1	CE
7	Distrito Federal	1	DF
8	Espírito Santo	1	ES
9	Goiás	1	GO
10	Maranhão	1	MA
11	Minas Gerais	1	MG
12	Mato Grosso do Sul	1	MS
13	Mato Grosso	1	MT
14	Pará	1	PA
15	Paraíba	1	PB
16	Pernambuco	1	PE
17	Piauí	1	PI
18	Paraná	1	PR
19	Rio de Janeiro	1	RJ
20	Rio Grande do Norte	1	RN
21	Rondônia	1	RO
22	Roraima	1	RR
23	Santa Catarina	1	SC
24	Sergipe	1	SE
25	São Paulo	1	SP
26	Tocantins	1	TO
\.


--
-- Name: uf_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('uf_id_seq', 26, true);


--
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: urbem
--

COPY usuario (id, pessoa_id, grupo_id, status, senha, created, updated, municipio_id) FROM stdin;
13	25	2	1	04554a4fdffcfd9c9c0f637da71584fc	2013-05-22 17:00:35	2013-05-23 11:46:36	1
14	26	2	1	0d11ef740fb5b86affdd86c674c070fd	2013-05-23 10:22:02	2013-05-24 14:34:37	2
16	28	2	0	f6fd1939bdf31481d27ac4344a2aab58	2013-05-24 14:51:22	2013-05-24 14:51:42	2
15	27	2	1	f6fd1939bdf31481d27ac4344a2aab58	2013-05-24 14:49:33	2013-06-28 11:59:27	1
1	1	1	1	2a9f1fff3d4b665255a5a98a970ffa54	2013-01-22 13:33:21	2013-07-05 17:23:06	\N
17	29	2	1	82e7ef806f2ddd082ea86f590497277d	2013-08-22 17:42:31	2013-08-22 17:42:31.596715	3
12	24	2	1	dab5b6a071d098d00f4f4b5c95cbf2f6	2013-05-22 15:03:49	2014-02-07 09:40:11	1
19	31	1	1	719d5ac7466571fa238f5e79ef4cbc1f	2014-11-03 10:31:33	2015-05-18 16:34:16	1
18	30	2	1	96535f2c4202b22fa47c38819d4ad1d1	2014-10-17 14:27:44	2015-05-18 16:40:44	4
\.


--
-- Name: usuario_id_seq; Type: SEQUENCE SET; Schema: public; Owner: urbem
--

SELECT pg_catalog.setval('usuario_id_seq', 19, true);


--
-- Data for Name: BLOBS; Type: BLOBS; Schema: -; Owner: 
--

SET search_path = pg_catalog;

BEGIN;

SELECT pg_catalog.lo_open('147385773', 131072);
SELECT pg_catalog.lo_close(0);

SELECT pg_catalog.lo_open('5997947', 131072);
SELECT pg_catalog.lowrite(0, '\x5c3337375c3333305c3337375c3334305c3030305c3032304a4649465c3030305c3030315c3030315c3030315c303030485c303030485c3030305c3030305c3337375c3334315c3030305c303236457869665c3030305c3030304d4d5c3030302a5c3030305c3030305c3030305c3031305c3030305c3030305c3030305c3030305c3030305c3030305c3337375c3337365c3030305c303237437265617465642077697468205468652047494d505c3337375c3333335c303030435c3030305c3030355c3030335c3030345c3030345c3030345c3030335c3030355c3030345c3030345c3030345c3030355c3030355c3030355c3030365c3030375c3031345c3031305c3030375c3030375c3030375c3030375c3031375c3031335c3031335c3031315c3031345c3032315c3031375c3032325c3032325c3032315c3031375c3032315c3032315c3032335c3032365c3033345c3032375c3032335c3032345c3033325c3032355c3032315c3032315c303330215c3033305c3033325c3033355c3033355c3033375c3033375c3033375c3032335c3032372224225c303336245c3033345c3033365c3033375c3033365c3337375c3333335c303030435c3030315c3030355c3030355c3030355c3030375c3030365c3030375c3031365c3031305c3031305c3031365c3033365c3032345c3032315c3032345c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3033365c3337375c3330305c3030305c3032315c3031305c3030312c5c3030315c3234345c3030335c303031225c3030305c3030325c3032315c3030315c3030335c3032315c3030315c3337375c3330345c3030305c3033345c3030305c3030315c3030305c3030325c3030335c3030315c3030315c3030315c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030365c3030375c3030345c3030355c3031305c3030315c3030335c3030325c3337375c3330345c3030303f5c3032305c3030305c3030315c3030335c3030345c3030325c3030305c3030345c3030345c3030335c3030335c3031325c3030355c3030355c3030305c3030305c3030305c3030305c3030315c3030325c3030335c3030345c3030355c3030365c3032315c3030375c3032325c3032335c30323421315c3031305c30323522415c30323632512324715c303330333738426173765c3230315c3236345c3032374752565c323034775c3232315c3234355c3330335c3332335c3337375c3330345c3030305c3032345c3030315c3030315c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3337375c3330345c3030305c3032345c3032315c3030315c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3337375c3333325c3030305c3031345c3030335c3030315c3030305c3030325c3032315c3030335c3032315c3030303f5c3030305c3335345c3236305c3030305c3030305c3030305c3030305c3030305c30303061525d5c323535555c3232374a5c3335335d255c3331365c3231325c3234325c3237365c3333375c3334315c3337315c333332585c3234376b5c3234355c3234365c3336315c3033335c3333323f5c3032315c3231305c32373367665c3234325c3235326d5c303233695c3335325c323036685c3030305c3030305c3030305c3030305c3030305c3030305c30303061525d5c323535555c3232374a5c3335335d255c3331365c3231325c3234325c3237365c3333375c3334315c3337315c333332585c3234376b5c3234355c3234365c3336315c3033335c3333323f5c3032315c3231305c32373367665c3234325c3235326d5c303233695c3335325c323036685c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030325c3233315c3337305c3333325c3337365c32353459775c3337365c3032375c3337335c3333305c3031335c3233305c3235375c33373622305c3235335c32353722705c3335355c3336375c3031365c3236325451535c3332375c3333343c5c3237375c32303525635c3333345c3333305c3233335c3334315c333234452a5c333636565c3236355c3331335c333731585c3237325c3332322f5c3235365c3237375c3231305c3032355c32343728705e215c3230305c333430575c3233345c3332375c323134645c323732625c3032375c3335335c3032355c3033345c3232375c303330665c3234355c3237315c3331372473242d575c323732395c3033312b5c3333345c3231366b5c3233325c3332354d7e5c3235325c3233335c3333326d5c3032373f365c3334377a5c3335325c303333765c3032376c5c323632375c3032375c3234325c333130324b5c3031345c3032375c3235315c323532722b5c323132515c3333335c3335305c3234315c3232315c3231315c3235355c3235365c3332315c333137735c3233355c3333315c3032315c3235355d5c3337352a5c3237365c3237376f5c323636435c323033735c323637215b5c3033375c3231355c333437794e5c303235635c3330375c3235325c3232355c3033335e5c3333346e5c3233365c3234355c33363535316f6b5c303337695c3337355c3033305c3231335c3235352a5c3234375c3337325c3234325c3234365c333231735c3237317b5c3230345c3333335c3232305d2c595c303136275c303136355c3336332b2d5c3237312d715c3333335c3336325c3033336a565c33323054525c323635765c333036395c3235325c3231325c333436395c3231335c3237353d5c3237365c323732554034585c3235373b5c3333365c3335326d595c3332355c3235365c3335325c3237306d5c333037235c333037715c3237325c3231335c3335355c303235663d5e5c3236355c3232365c3333325c323636465c3330377d2e5c3337325c3237335c3236355a5c3337365c3231305c32353557225c3235323b7e5c3233365c3231325c3237365c333330395c3137375c32323422775c3033365f5c3336325c323734635c3033335c3234365c33303573295c3335305c3335355c3336312d245c3336325c3235356455355c3032315c3335355c3232322a39555c3231315c3033335c3333345c3231325c3334365c3236375c333532546e5c3236365c3335355c3233375c5c7b5c32313073645c3236315c3334355c3336305d2d5c32333457635c3235305c32373463755c3236365c32313258715c3231332a5c33323335655c3233355c3235305c3231357c5c3236335c3237315c3237362744545c33363562225c3234325c3335377a556a5c3032325c3031335c3337375c3030305c3032375c3333375c3335335c3337305c3335335c323131715c3331306b2d5c3231355c3235335c3330332e5c3336366a5c3333335c3231335c333337235c3332323959475c3031325c323632545c323131515c323333735c323235575c3335315c3335345c323135454f754034795c303237265c3332355c3334325c3233313f385c5c68715c3031334555462f5c3030355c323336485c3334345c3234345c323436585c3335325c3235335c333236783f35545c3231305c3235325c323632362d5c3335353d5c3032335c3235335c3032315c3331315c3337355c3334365c3031365c3033315c3331365c3333315c3032347866415c323331655c3236335c333430775c323133255c3235365c3333355c333436775c323134574a5c3236335c333035505c3335315c3033305c3331305c3235315c3334365c3231326f565c333637575c3235365c3233365c3233365c3231315c3332357d5c3331315c323033705c3033345c3333365c3333355c3233355c3336325c323136555c3231375e6c5c3236343579475c3331325d687c5c3336315c323736745c32313169225c3335312b67675444475c3234365c3333325c3231325c33323739515c3033355c323737454d5c3032305c333333775c303031645c303331355c3332332d5c32373272455e2d432d5c3337325c3331305c3333334b69716a59235c3230315c3235325c3333315c323331336a5f5c333432222b5c323434475c3330365c3331377d5c3335353d375c3235355c3030315c3235315c33373651595d5c323235285c3235375c333731356f5c3032345c3332365c333330675c32333626545c3333336c595c3030375c323137755c3234335c323136472277565c333636564a5c3235345c3333365c3332355c3033305c323337655c3336364d5c323532755c3031315c3331345c32323170772236265c333332565c3330375c3330313e515c3235305c3232315c3337345c3333375c3336305c3232323a5c323731535c3333335c3237325c3330345c3235355c3336307b7d5c3336355c3335355c3236335c3234365c3330305c3334315c3331375c3230365c3337345c32373138735c3231355c3234305c3237375c3332355c3235335c3234345c3236375c333435765c3033325c3333325c333132285c3333355c323635475c333335685c3335325c3334365c3231315c3236305c3234327d5c323734485c3333355c3032375c3336315c333530595c3337375c3030305c3031305c3033305c33333556255c3331315c3337345c323437635c3237305c3331345c3337315c3235365c3032315c333031615c3233365c323736475c3237336a5c3335325c3235315c323531655c323332755c3333375c3337305c3232323c5c323333713f5c3031345b5c3235355c5c3b5c3230375c3334315c3333315c3336355c3031355c3236365c3336315f5c323135574b5f4f25345c3332325c3337304c5c3233356a255c3232355c3231366a5c3335315c3231325c333434465c3331305c3233336b5c3232335c3235325c3235327a5c3234325c3335315c3032345c3331325c3234365c3334333b5c3237335c3336336e5d5c323732545c5c5c3335315c3235315c3235305c3236335c3231325c3031322a4a5c303331295c3333345c33343754525c323534546f5c3230315c3335377a2b515c303231515c33313747375c3235335c3232377a5c3336355c3332305c3032305c3235334738655c3332345c3033345c3231315c3231375c3333315c3236325c3330375c3336315c333135455c3030355c3336365c3334325c33313373692c5c3032375c3234355c3235325c3237305b5c3234365c323233695c3033375c3231365c3332355d393b695c3235325c3235356a222a5c3337335c333733225c3335362e5c5c5c3230355c333133377e615c3331355c3337305c3337375c3030305c3030355c3236315c33343272325c3330305c3232345c30323245705c3237333e76475c303233665c323437495c3033345c3333315235573d5c333536725c3337353d515c3235305c3231305c3330376f6b5c3234325c3031375c3231355c3337343a665c33363452612b545c333136365c3234345c333734315c3137375c3234315c32353475455c3235365c33333724555c3232355c3332345c3332305c3237315c5c5c3336374d3a5c3236315c5c5c333531574d5c3332335c3032334c5555555d5c3234334b5c3234375c3030375c3330325c3235365c3236363e625c3334345c5c5c3330365c3235365c3234325c3231315c3336345c3033313f5c3331333c5c323234713d5c3331332c7e5a5c3233355c333231495c333432225c3236355c303231365c3334354d695d5c3335315c3335375c3235376034785c33373655565c3237345c3235335c3331345c3236345c3032365c333734775c30333765655c3230325c3232325c3333332d3d43234a695c333536323e5c3231355c333632355c323635535c3235325c3235322b5a5c3235305c3231356b5c3232355c3032335c323433557d5c3331355c3031375c3033317336475f5c3331325c3332365c3337345c3033332c5c3235335c3334335c3337335c3234325c333336295c3334375c3232365c3230365c3234375c3032335c3237323a5c323531295c3334345c3230355c3237355c3333355c3032345c3335305c333435554556235c3232355c303237485c3231325c3235355d6f5c333237595c3033315c3235375c3031345f5c3236324a5c33373663545c3237345c333231505c3330315c323334416a655c323732485c3333345c333637495c3032335c323531225c3332335c333232645c3335325c3231305c3231357b5c3232315c3032335c333531577d2a5c3237335c3337355c303135375c303337705c3230365f685c3334355c30333427335c32373253715c3333355c3235363b5c303332565c333037594b5c3231365b5c3333374b5c3334325c3236364a655c323132375c33363656765c3233315c3337355c3233345c3235325c3335365c3335325c3332346a275c3332335c323635725c3230316c735c3236375c333634215c3233365c3137375c3232365c3335363f5c3335355c3234345c3033345c3032335c3337355c303130605c3137375c3334355c323733775c333733685c3331355c32333725592a5c3236325e3a5c333131715c3331325c303331215c3231365c3235365c333533685c3235325c3234325c3230315c3336332a5c3234346d7c5c3236305c3237315c323135572a225c323532376e4d5c3335315c303235755c333636525c3236335c3334335b275c3330343e35435c323135635c3232355c33323271645c3233305c3336355c3235322a5a295c3333375c3031325c3332375c3235355b5c32353162465c3236315c3331325c33323554462c5c3237355c3033325c3237325c333332237b7d5c3232315c3030305c3332365a5c333731375c3233315c3336336b5d76635c333037585c323136292e255c3030345c333633325c3231322b5c3233354c5c3331315f72644e565c3237315c333631745c3337325c3033315c3236356b5c3232315c3032315c333337745c3332375c3235315c3335355c3334335c3233345c3336323b5c3235347c593f5c3033375c3334335c3332365c3337325c3335375c333037305c3333343b5c333233575c3331305c3334365c3237325c3233366a76353f5c3233346a5c323432235c3033305c3336357a5c3237357a5c3235325c3237315c3235345c333732515c3032354f2d7c655c3331355c303330555c3235365c3237335c3031365c3334335c3235345c3237335c3032345c3231335c3032325c323336795c323337452d5c3331365c323332655c3235375c3236365c323632572b5c3233345c3331305c3237327d5c3031375c3332325c3237315c3331325c3231325c3335375c3237325c3337355c3231305c333236755c32303157615c3237315c3237375c303031605c333330555c3334363a4b5c3230355c3236365c3033335c3334375c3232355c3235355c3235325c3230335c323733245c323331295c3333312b5c33373446225c3335375c3234345c3231365c3335365c3231325c3231305c323733447a5c333531765c3230304a5c3234335c3334365c3335345c3231375c3031337e5d695c3334355c3235335c3033355c3235325c3031335c3330355c3230365c3332335c3033355c3334325c3232355c333636495e5c3236345c3332355c3336345c3335375c323235216a375c3330355c3333335c3233325c3237362b5c3233305c3331355c3235375c3335325c3235335c3234345c3332375c323534635c333731456576545c3234325c3237375c3334345c3332355c323734535b615c323336785c323331536d5c323631645c3033363d5c3332365c323136395c3033345c3231315c3333355b5c333331592a5c3236337b54627d5c3232375c333331365c323531275c3231335c323034723c5c3332324c5c3237325c3335355c3331335c3232375c323733545c3336377b5c3336355c3234363b3d3476385c3333365c3232345c3336345c3032345c3335345c323235266a5c3236375c3330355c3337325c3233345c3335375c3032355c3235347e5c3232375c3336345f555c3333375c3234347a2e5c3031365c333434465c3330345c3333334a5c3333305c333730275c333132355c3032323f5c3233335c3337365c30323247572a7b77585c3232355c3237365c3031376f5c3237365c3237355c3236365c303036455c3332335c323335335c3331325c323536565c3237365c3334325c3232362a6c5c3031325c3333365c3333334d5c3330355c333234305b6f5c3336355c3332335c333233575c3333345c3332355c3237365c3331365c3230315c3337375c3030305c333135223f5c3332335c3235376f5c3332353d5c3331365c323233395c3337375c3030305c323237785c3234375c3232345c3337315c3032365c3236365c3234365c333035745c323734605c3333375c323036655c323730785c3336305c5c5c3237365d2a5e2920497b5c323636285c3332375c3336326d5c3235315c3234365c3336364545726f7e5c333532745c3030305c303334395c3336305c3333375c323237275c303136715c3236345c3032375c3337325c323635745c3232365c3337345c3235365c3330335b59455c3033335c3236365c3235305c3337335c3235355c3033355c5c5c333231365c3032344f5c3236375c3231315c3033335c3234325c3337363d5c3031333f5c3334315c3030335c3033335c3235325c3330345c3237313f5c3232345c333534775c3033315c323337355c333032382c335c3332375c3331305c3336376d5d55352c5c3236334e5c3237335c3337375c3030305c303232475c3232336e275c3334315c323133755c3235335c323037705c3337343b3e5c3234315c3236365c3333362b5c3336315c3235325c3335316b5c3335315c3334345c3234365c3233325f5c3031315c3232335c323535445c3236325c3236315c3331355d315c5c5c3231305c3333315c3032336d7275554f545d225c323331545c33333467777e6d5c3331335c3236374a5c3231335c32333535355c3032367141454943253b5c3233345c3335325c323132555c3231325c3231355c3336303d5c333537456a222a395c3335305c3334365c333635725c3335375e5c3237325c3030325c303235685c3334375c3031345c3237325c3230335c323231315c33373336585c333736395c3235305c3234305c3237365c333334596e6d255c3230325c3336345c323635575c303133745c3332326d235c3336315c3333325c3235335c323437276d35555c32353544455f5c313737645d5c3333325c3336324f275c333434595c3235364a5c3331345c3030335c3032365c3330375c333533715c3231345a5c3237355c3336365c3337325c333437575c3332345c3331315c303335657d44685c32313334745c3337357e5c3230365c323731375c3234345c3335365c3233325d5c3234325c3335375c333237495c3030345c3330365c3337365c303335337a29305c3232355c323532675c303333527e5c3033305c3237375c3332305c3332363a5c3234325c3332376f5c3232322a5c3331325c333532685c5c5c3235367b5c3234365c323335585c323536745c3235335c3234365c3335315c3231315c3234362a5c3235325c3235325c3235365c3332315c3234375c333233305c3237305c3332345c3336315c323236775c3233355c3332335c3334335c3233345c3236315c3230345c3333315c3235355c3032374a5c3332375c5c5c3235365c3336365c3337335c3234323d5c3332374a5c3033325c3233315c3234326b5c3334347d24685c3235305c3232325c3237315c333535565c323731375c323634455c3332326b5c333231765c3032332c635c3232355c3236334c5c3230335c3334315c3331375c3033365c3331375c3335315c3333315c3230355b5c333537574a5c3235315c3234325c3235315c3233325c333537585c3337322b6d2c6c5c32333276235c333235555c3331367b5c3232375c3336364c4e5c3235305c3335355c3235325c323731575c3332315c303233465c3033362b5c3336315c30303722715c333036797b5c3331315c3335315c323534555c3236374c355c3332305c323636675c3333302b7c7a5c3031325c3335375c3033375c3332325c3030355c3231324d5c323731515c3032355c3337375c3030304a5c3337325c3235325c3236375f5c3235365c3333325c3232305c3233342b5c323037725c3233345c3233375c3334315c3334335c3231315c3234375c3236375c3236325c3330305c3335335c3230355c3231315c3332355c3236355c3235336a5c3331305c3335315c3334345c3232325c3230365c3235362a5c3235315e5c3336362c5c323135622a5c3235325c3236355c3235325c3332356a6b5c3337334b5c3335325c3230345c3230325c3231335c3230325c3334345c323633615c3337345c3237305c3331345c3331365c3335315c3231365b2c5c323731255c3237365c3231325c323431255c3236325c3332312c5c3032305b5c333335495c3033335c3334345c3232315c3337365d5c3033325c3231305c3231346c5c3233325444735c3233345c3336346a5c3235335c3236345c3334355036373e4c5c3334365c3335345e5c3235375c30313166655c323133625c3032345c3336345c3337314645436e5c333631685c3234345c3233355c3335365c3234345c323132672a3e295c3033325c333437275c3335355c3236355c3234356b5c3233325c323536675c3332305c333434545f43637b5c3334344e5b5c323731725c3235367b5c32303460785c3337362d545c3333346d5c3236345c3032325c33303355737c5c3331344e5c32363353785c3231365c3231355c3331305c3330377d72395c3331335c3234366b5c3234335a5c32313577655d5c323431545d2f5c3337316e635c323334715e37745c3334345c303334332d5c3235315c3234315c3331315c3335305c3335335c3234335c3230335c3033305c333537325c3237365c3031327d5c3237324a5c3233325c3235315c3032375c3332315c3232322353485c3330365c323432275c3332345c3336355c333733215c333231583e5c303235755c3236315c3336335c303237225c33343635755c3032344f5c3234305c3331315c333736595c3334345c3234335c3231315c33353659635c3336325c3332345c3335365c3231324f5c3032315c3032355c3235305c3231315c3236372a6b4a5c3335374f7d7b5c3030315c3033335c3234355c3334356b5c333636435c3330305c3233305c3337375c30303020635c323230625c3332365c3331325c3333335c3234345c3233365c303335435c3336325c3033335c3231325c333233505c3332315c3336345959235c3332355c3331315c333635393c485c3236345c3231354f5d3b5c31373765335c3237365c3033365c333731425c3237375c3232305c303233225c3236345c3333365c3332325c333031255c3334335c3033375c3235315c323132295c3335325c323534557e625c3230325c32353239585c3235365c323136485c3233375c3236355f5c3335345c3237315c3032355c303235574a5c3233365c3237325d5c323432572e5c3337307c5c3331345c3234325c3334326e395c3236305c333037575c3231305c3333342f5c3033305c3230354d745c3236335c3332305c3333355c303331345c3336365c3235325c3332365c3332345c3331325c3336375c3234376446235c3233345c323534454d225c3236353d557d5c313737595c3333375c3330335c3337375c3030305c3032365f385c3336372e5c3331356e5c33363759315c3330345c3234375c3331305c3032325c3333365c3335327a7b2d2a5c3332325c3330335c3030335c3234315c323136445c3232355c3235315c303137546b5c3033335c3333324f5c323435515c5c5c323536445c3335345c33353539545c3031335c3230345c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c333233665c3333314d5c3231335c3031335c3330362a5c3336326c5c3233325c3237335c3331305a685c333732795c3231325c3231375c3031315c333632745c3335365c3336365c3236315c3237374b5c3032315c5c5c323733735c3233325c3233365c3231305c3237365c3337375c3030305c3234315c323731295c3233375c3231355c3235375c3335325c3330355c3232375c3137375c3334315c3137375c3237355c3230305c3031355c333136255c3331375c3333343f5c3232345c3333355c3234315c32363559737a292b677232285c323532205c3233325c3233335c333034725c333733355c3235332b5c3033325c3231325c3235335c3335345c3231305c3231335c323635265c3232377b5c3337375c3030305c3331335c3236327b5c3033355c3231335c3334345c3232375c3235325c3237375c3233335c3337315c3231375c3333375c323531697b5c33323251784c475c3337365c333631265c3337375c303030675c3333377d595c3335305c3237355c3233345c3231325c3233365c3230345c3033335c3334325c3234325c3332335c3231365c5c382b2c5c3235315c3237375c3332335239685c3335355c3236325c333137473c5c3235354e5c333631545c3236355c3237375c3236315c3335305c33353774555c313737565c3337327b5c3335375f722a5c3333335c3336364b5c3337305c3336375c3334315c3332365c3232365c3235365c333531705c3230356e5c3336366b5c3230345c3232375c323132665c3331365c3336365c3236325c323532565c333333627a785c323534455c3332335c3332355c323537572a76455c3332325c3337325c3234305c3032375c333430395c3332375c3230355c3235345c3233314e6b5c3233325c3334365c3332375c3231335c32373723655c3232305a5c3236315c333736405c3235365c3231325c3230365c333331495c5c5c3235355c323136445c323132563b5c3330325c3232355c5c5c32313657535c3336355c3335305c3332345c3230353a5c3236355c3032335c3237375c33373545596d5c333137335c3033345c3336365c3232365c3236332a5c3235355c3233335c3233365c3334305c3235345c3233365c3234366f5c323237455c3231305c3333325a5c3335334d335c3033325c3336356b5c3033305c3334345c3335345c3231333a5c3234365c3236345c3335355c333531775c3236345c3337336c5c3031365c3330345c3237335c333435362b4e4f635c3330366e5c3032355c3333365c3031355c3333325c3337355c3334363e5b4f5c3334313d5c3333363f5c3230305c3330347c5c32373752225c3236355c3237355a5c3235305c323737525c3234365c3337365c3333335372715c3336355c3333365c3332355c3232335c33363226755c3330305c3336355c3237315c3233355c333333305c333035725c3031335c3236355c3237365c333631495c5c5c333132395c32333541514a5c333732585c3333365c323736344c73375c3032345c323233225c323437755c3332365c3233345c3331365c3231305c3231305c3231305c3231304e5c3232365c3332335c323232673c5c3237337b5c333433645c3334347c5c3236325c333033615c3330322d565c3335305b255c3237365c323635235c3237305c333335255c3233325c3033365c33313351345c333735765c3334354d695c3333365c323332572e5c3337355c303235545c3031365c3230375c303037225c333437797e715c323137705c333637325c3334325c30323366573b5c323035765c3033335b6a6d5c3237325c3337375c3030305c3033345c3331335c303135635c3234325c3235315c323332272c6e5c3232315c3231325c3231325c3235376a6d5c3235365d5c3335357b2a2f5c3234375c32343165665c3236345c303232715c3236375c3033335c3333366e794f295c3334365c333235374c5c3230325c3234325c3231365c3233335c3330365c323436464d24555c3033355c3332355c3331365c3230365c3333374f5c3332355c3033335c3032325c3331305c3332357b7d5c333237485c3231355d5c3335355c3237365c323431785c3030335c3233327e5c3033315c3336325c3335335c3335375c333734635c3237345c3334305c333235355c5c5c323037355c3233355c333636245c323733525c3330375c323334405c3231345c323730445c3336365c3331365c3333305c3233355c333235765c3237355c323432775c313737455c3336345c3336356a5c323432275c3234325c3235325c3333303f5c3032335c3237315c3032355c3337325c3330375c3230345c3333315c3335355c3337305c3334355c3331356d355c3333315c3033364147635c3337315c323233536e5c323432645c3335325c3335365c3332325c3234375c3335305c3235305c3231355c3332365c3337365c3333335c3336345c3332325c333531405c3232375c333434595c3235355c3235325c3330375c3233355c3334325c323730755d3d6b5c3335335c3336325c313737395c3334345c3234345c3231315c323135585c3234335c3336325c333231245c323632785c3231325c323536454d5c323635535a477a5c3337335c3335335c3333345c3232335c3033345c333133595c3231305c3333345c3336305c3333375c3231315c3335365c3033375c32363655665c3336375c3331345c3234325c3232315c3335345c3237345c3331335c3030375c333136676c5c333635503f5c3331316a4f5c33333222225c3235335c3033355c323436756a5c3337332b5d5c3335325c3237332b5c3331336e795c3233305c3334375c3236345c3236355c323331556c5c3333345c3336375c303035645c333635337c5c3237322c465c3332325c3332375a695c3233305c3332375c323533585c3330372764595c333235355c3234376f4b5c3237355c3234375c33333360765c3335314c5c3337375c3030302a3e5c3031315c3337375c3030305c3237365c3137375c3337305c3233325c3333375c3337375c3030305c303232635c3330315c3232375c3235345c323337205c3334327b5c3030355c333237335c3236365c3332355b725c30313160732b5c3234305c3235315c323436753c5c3233355c333331235c3233315c3333355c3332315c3237315c3032315b5c3333355c3033325c3231375c3332365c3232313e5c3235374f4d5c3032305c3335375c3231325c3137375c333731555c3337375c3030305c323531365c3137375c3337365c333430267c655c3331325827257c5c3330335c333630555c3336375c3334365c3237372e5c3336305c3237345c3333375c3335365c3232335c3330335c3334315c3337305c3233355c3337325c3137373a5c3330365c3335377d5c3033375c3335355c3237356b5c3332375c3335346e5c3235365c3336375c3337375c3030305c323237645c3336363b5c3032375c3331312f555c313737375c3336335c3033375c323737525c3332325c3336375c3234345c3234325c3336305c3233305c3231375c3337355c3334324d5c3337365c3331375c3237365c3337325c3236335c3332317b395c3032353d5c3031325c3233375c3334325c3031364c5c3234325c3236375c323331385c3234335c3032375c333037325c3331335c3233363b5c3033355c3334356f5c3031345c3235345c3232325c32323255447c6c5c3234375c3231355c3331325c3235325c3331375c3331325c3334375c3234337b5c33363457225c333634725c3234335c3232335c3333305c3336325c3336354f755c333033395c3231375c3230345c3336305c3337325c5c5c323637265c3237315c3332304f5c3336335c3335373b255c3331325c3334325c333531655c3235365c3332353a4a5c3331375c303335535c323533645c3335305c3334372f4d5c3234375c3332325c3231305c3233325c333735405c323633305c3031345c3332365c3332355c3233327e205c3337315d3d6c3f215c3237355c3332345c3333312a5c3237345c3331335c3033325c3333365c3336335c3330315c3332375c3237335c3233315c3332355c3331335c3236362f645c3332325c3235365c3232375c333337685c3230365c3334365c3235365c3332336a5c3235345c323732505c3333352a5c3335355c323234555c3032355c3336365c3337375c3030305c3032335c333131554b5c3030335d2d375c3231305c3333365c323632786f545c3333333b355c3032315c3032374a5c3233334f45397b5c3231345c3236307b5c3335365f575c333133535b5c3337312a5c3337375c3030305c3231325c333035435c3233355c3333355c3233374b5c3031355c323532765c3330325c333337315c3333315c3235325c3236325c3332342e5c323733495c3033365c3232315c3231305c3231345c333332275c3332325c3335375f53675c3230305c3336325c303336515c3232365f7e5c3033362e5c3232375c3031335c3233355c5c5c3031337b5c3234375c323737325c3335335c303034525c323732386b5f4f5c303137463e485c3333325c3235305c3332372f667744545c3332322b5c3232375a5c3030335c3234335c3235375c3332374a5c3031335c3033355c3231365c3237365c333637745c3233375c3331335c3332305b5c3335315c3234345c3235325c3235325c3232375c3234335c3233355c3334315c3330355c303333555c333137765c3233325c3231325c3235335c3234365c3234325c3235365c3232315c3032355c31373741625c323732505f2c745c3032375c3237335c5c5c333736625c3230325c3334314d5c303335552c5c3237355c3033345c3333375c303232295c3033325c323136635c3236345c333434454d5c32363551745c3235305c3231335c3337325c3233345c3334355c3233365c3332345c3333345c3336325c3033325c3335375c3231315c3235335c3033354e4578655c3237365c333331655c3236374f455c3032345c3032355c323536465c3332335c32353368645c323332485c3333305c3231335c3236346b25747d646a22776a5c3237315c3032375c333337665c3236365c3231325c3233365c3337375c3030305c3330375f5c3031365c333330654e355c323334645c3231375c323730674f5c3236315b5c3033333d5c3333325c3236312a695c3335346d5c3233365c303237395c3331365c323435625c3236355c30323226222f5d2a5c3235365c3232315c3033315c3335335c3236365c3234325c3230315c3332355c3230305c3334377b5c3334355c323533295c3334315c3331345c3333335c3030355c3235335c3234345c3334345c3233345c323433295c3236375c333434575c3333306c5c3236375c303333755c3337325c3235312a557c645d4d5c3031325c3335315c30323634625c3234365c3332355c3032335c33363544555c3332315c3032305c3330345c3234365c3334342c775c3232342c5c3235345c3334356c5c3232375c3232315c333535374b5c3232355c3336355c3232345c3336345c33323530555c333033515c323137565c3235335c3234345c333333695c3232325c3032344f5c3333315c3337305c323134456a2f5c3237326f7a4d2e5c3230335c3235355c3330305c3030305c303135255c3334375c3031375c3330345c32353757285c323536775c3233345a5c333037725c3235365c3231335e5c3033354d5d5c3237362965665c3237355c3236345c33343735553f5c33363737605c3030322222695c303233485c3230375c3334364632485c3333355c3033345c3231346b5c3333305c333434565c3237315c3235364d5c3234325c3234325c3337335c3234325c3234315c3337325c303030696c5c3033305c323236295c3231375c3332354d55615c3330366c5c3236365c3233325c3231315c333233534b45415c3032345c3031375c323231375c3237375c323531585c333234555c3337375c30303053745c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c303032335c33313258555c3235335c323231304b5c3231365c3033357b5c3235305c3235355c3234375c3234305c323730785e2c5c3232346f6b656f5c3230372b254e5c3235325c3334365c3237313f335c3032337b455c3336345c3333375c333631245c3330305c303132765c3232335c3334315c3334375c3032345c3232365c3237365c3233325c323437285c333132335c3231345c3332322a595c303232585c323531322b5c3333335c3235325c3235315c3332315c3335315c3335345c3235325c3330346b515c3137375c3230325c3335355c3032375c3335365c3231324e5c3336325c303334265c3332337b5c3331365c3236315c5c5c3330325c3235327a5c3331305c3335335c3336315c323137375c3334345c3234325c3231315c333535485f5c33343662485c3233375c333432225c32363555745c3332344d75565c3335317d5c3336375c333534495c3330305c3032315c3233345c3030335c3031325c323635615c3137375c3231303e57515b375c3331375c32353775375c3237325c323537325c3336365c3237335c3234345c3336335c3336355c3335365c333236756a695c3231315c333235345c3231335c3236355c3336375c3333325c3235315c3031335c323736702631597b5c3235355c32373259326c5c3332375c3032315c3336335c3336333a7a5c3331325c5c7e5c3336325c3335325a795c333434775c333436725c3330375c3332355c3331305c3231325c3237377d695c303133685c303031575c3333375c3237303f5c3032335c323732625c3333305c3337355c3231365c3033335c3234364b6b5c3232375c3033365c323236795c3235355c3332374a5c3033335c3233335c3233315f5c3032335c333436725c323732655c3336315c3233345c323136555c3335365c3235325c3235325c3237365c3233375c3333345c3233324f435c3336335c3232345c3336307e377e5c3233325c333235715c5c5c3230372e5c3236355c3333375c3235355c3332365c3337305c3335355c3335365c3237365b6e5c3235335c3030357d644c6a22785c33363275545c3232315c3331335c3235355c3235335c3236345c3231325c3235325c3237375c3234365c3232312d205c3030355b275c3030345c3334305c3335335c333035377e3a5c3230355c33363758686f335c3236325c3234365c3334335e5c323235285c3337325c3335325c3233315b2b24475c323736475c3236355c3331305c3235325c3235335c303332275c3334355c3332365c323235745c3231305c3235335c323632515c333132385c3032355c3231375c323231715c323436585c3335375c32363257405c3331306a5c3234335c3235345c3234355c3235325c3234325c3233375c3330325c3235305c3234365c3233355c3233335c333533246e5c3332325c323432395c3032315c3331364f545f72545c3030305c323536305c3031365c3033355c333037705c3333345c333331735c3033323b5c33303647735c32373449695b5d445c3336374a5c3337375c303030325c3335325c3230362c5c3331312f5c3231305c333637395c3237355c3232364f5c3234355c3235354d2a355c3033325c3332344e5c3237335c333635245c3333345c3231315c3230365c333330735c3333344e5c3235335c3033315c3331305c3335315c3233373d5c30313547576e375c3336345c323232275c323635765c3332375c3236315c3333375c333331722f5c3333375c3337355c303237685c3235325c3230345c3230345c303031545c3334325c3233345c3032315c32313458333b5c303136605c3237315c30303659775c3237355c3333313c5c333033615c3235325c3237325c3333345c3232325c3234355c33323332585c3232362f5c3031355c3335325c3334367e46225c3237315a5c3231345c3335335c323435725c333537675c3331365c3337315c3330305c3233305c333035655c3335365c3236365c333531645c3331315c3236335c5c475c3331375c3331345c3335315c33353329715c3337335c3331335c323531695c3334375c3232315c3333375c3233315c3331335c30333757222a5c3337355c3336355c3234342d5c3234305c3030365c3233335c3031315c3330365c3235355c3333305c32303631495c3231375a5d545c3337324a5e5c3335325c3332375c3332354e5c3335315c3234357b5c3233365c333637485c333637395c3335365c3336355557395c3331335c3337365c3237365c3233365c3230365c3032365c3137375c3230355a5c3236334f5c3330335c3337375c303030345c3235305c3235355c3230375c333434375c3237326b5c3333352f5c3232367b5b5c333336783b746b5c33373335765c3330355c3335345c323733445c3332325c33373369505c3232335c30303023395c3032365c3032356a5c32373667785c3235366357515a5c3331325c333734635c33313679285c3334327b52293c5c333134495c3032345c323336222b55574d445c3332365c3232355c3237365c3237365c3337335c3336365c3033315c3032365c3032356a5c32373667785c3235366357515a5c3331325c333734635c33313679285c3334327b52293c5c333134495c3032345c323336222b55574d445c3332365c3232355c3237365c3237365c3337335c333636245c3330305c303132625c3334375c3336305c3334335c3230365c3332365c3332355c3333376a5c3234325c3331305c3236332b745c3236375c3337335c323335455c3330325c3335315c3334346e5c3235315c3030336a5c3232326776745c3031376b595c3332355c3332305c3234325c3336365c3335325c3231325c3231325c3334345c3335365c3335375c323531775c333531225c333134386b5c3031365c3331305c3236315c5c5c3137375c3033375c3231316e76285c3336315c333037225c3333322a5c3335355c3032354b5c3030354d265c3233335c3332357a5c3331305c3235305c3335375c3331355c3234345c3333325c3235322a5c3235325c3234365c3336375c3236325c3330355c30303057384f5c303135625c3033305c3234335c3236325c3330345c3234345c3232325c3335335d5c303236574d5c3031353d5c3332363a5c3337325c3235375c3033335c333034485c3334327c6e776d235c333733495c3334323d5c3331375739765c3334377a754f435f645c3334307c4a5c3230335c3031345c323733615c3236375c3033335c333036515c323230586e5c3031345c3230315c3232305c3332315c3333355c3235365e332d5c3331315c3031325c323731595c333435745c3332365c3235345f5c323331375c3235355c3335375c323533535c333333685c3236365c3236305c3030325c3235345c3330325c333730335c3032375c333037325c3233322c5c3232365c3235365c3337315c323235645c3336375c303333735c5c5c333333735c3235375c333637455c3235336d5c3032365c3332334b5c333431375c323532227a7a7a5c3335375f6f5f535c3033325c3330375c3330305c3033305c3231355c323633255c3236355c333336265c323737665c303237582c5c3336355c3031355c3235315c3236355c3333325c333536575c3230375c33323451515c3331325c3333375c3331305c3335305c3333305c3235315c323634565c3337355c3236365c3334355c3336365c3337335c3232365c3334305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c');
SELECT pg_catalog.lowrite(0, '\x3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030305c3030335c3337375c333331');
SELECT pg_catalog.lo_close(0);

COMMIT;

SET search_path = public, pg_catalog;

--
-- Name: pk_acao_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY acao
    ADD CONSTRAINT pk_acao_id PRIMARY KEY (id);


--
-- Name: pk_categoria_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY categoria
    ADD CONSTRAINT pk_categoria_id PRIMARY KEY (id);


--
-- Name: pk_cep_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY cep
    ADD CONSTRAINT pk_cep_id PRIMARY KEY (id);


--
-- Name: pk_cidade_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY municipio
    ADD CONSTRAINT pk_cidade_id PRIMARY KEY (id);


--
-- Name: pk_configuracao_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY configuracao
    ADD CONSTRAINT pk_configuracao_id PRIMARY KEY (id);


--
-- Name: pk_contato_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY contato
    ADD CONSTRAINT pk_contato_id PRIMARY KEY (id);


--
-- Name: pk_grupo_acao_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY grupo_acao
    ADD CONSTRAINT pk_grupo_acao_id PRIMARY KEY (id);


--
-- Name: pk_grupo_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY grupo
    ADD CONSTRAINT pk_grupo_id PRIMARY KEY (id);


--
-- Name: pk_historico_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY historico
    ADD CONSTRAINT pk_historico_id PRIMARY KEY (id);


--
-- Name: pk_log_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY log
    ADD CONSTRAINT pk_log_id PRIMARY KEY (id);


--
-- Name: pk_menu_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT pk_menu_id PRIMARY KEY (id);


--
-- Name: pk_modulo_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY modulo
    ADD CONSTRAINT pk_modulo_id PRIMARY KEY (id);


--
-- Name: pk_pessoa_fisica_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY pessoa_fisica
    ADD CONSTRAINT pk_pessoa_fisica_id PRIMARY KEY (id);


--
-- Name: pk_pessoa_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY pessoa
    ADD CONSTRAINT pk_pessoa_id PRIMARY KEY (id);


--
-- Name: pk_pessoa_juridica_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY pessoa_juridica
    ADD CONSTRAINT pk_pessoa_juridica_id PRIMARY KEY (id);


--
-- Name: pk_uf_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY uf
    ADD CONSTRAINT pk_uf_id PRIMARY KEY (id);


--
-- Name: pk_usuario_id; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT pk_usuario_id PRIMARY KEY (id);


--
-- Name: uk_acao_alias; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY acao
    ADD CONSTRAINT uk_acao_alias UNIQUE (alias);


--
-- Name: uk_grupo_alias; Type: CONSTRAINT; Schema: public; Owner: urbem; Tablespace: 
--

ALTER TABLE ONLY grupo
    ADD CONSTRAINT uk_grupo_alias UNIQUE (alias);


--
-- Name: fki_configuracao_municipio_id; Type: INDEX; Schema: public; Owner: urbem; Tablespace: 
--

CREATE INDEX fki_configuracao_municipio_id ON configuracao USING btree (municipio_id);


--
-- Name: fki_contato_configuracao_id; Type: INDEX; Schema: public; Owner: urbem; Tablespace: 
--

CREATE INDEX fki_contato_configuracao_id ON contato USING btree (configuracao_id);


--
-- Name: fki_log_municipio_id_fk; Type: INDEX; Schema: public; Owner: urbem; Tablespace: 
--

CREATE INDEX fki_log_municipio_id_fk ON log USING btree (municipio_id);


--
-- Name: fki_usuario_municipio_id; Type: INDEX; Schema: public; Owner: urbem; Tablespace: 
--

CREATE INDEX fki_usuario_municipio_id ON usuario USING btree (municipio_id);


--
-- Name: municipio_hash_index; Type: INDEX; Schema: public; Owner: urbem; Tablespace: 
--

CREATE UNIQUE INDEX municipio_hash_index ON municipio USING btree (hash);


--
-- Name: fk_categoria_parent; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY categoria
    ADD CONSTRAINT fk_categoria_parent FOREIGN KEY (parent_id) REFERENCES categoria(id);


--
-- Name: fk_cep_cidade; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY cep
    ADD CONSTRAINT fk_cep_cidade FOREIGN KEY (cidade_id) REFERENCES categoria(id);


--
-- Name: fk_cidade_uf; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY municipio
    ADD CONSTRAINT fk_cidade_uf FOREIGN KEY (uf_id) REFERENCES uf(id);


--
-- Name: fk_configuracao_modulo; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY configuracao
    ADD CONSTRAINT fk_configuracao_modulo FOREIGN KEY (modulo_id) REFERENCES modulo(id);


--
-- Name: fk_configuracao_municipio_id; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY configuracao
    ADD CONSTRAINT fk_configuracao_municipio_id FOREIGN KEY (municipio_id) REFERENCES municipio(id);


--
-- Name: fk_contato_configuracao_id; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY contato
    ADD CONSTRAINT fk_contato_configuracao_id FOREIGN KEY (configuracao_id) REFERENCES configuracao(id);


--
-- Name: fk_grupo_acao_acao; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY grupo_acao
    ADD CONSTRAINT fk_grupo_acao_acao FOREIGN KEY (acao_id) REFERENCES acao(id);


--
-- Name: fk_grupo_acao_grupo; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY grupo_acao
    ADD CONSTRAINT fk_grupo_acao_grupo FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- Name: fk_historico_modulo; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY historico
    ADD CONSTRAINT fk_historico_modulo FOREIGN KEY (modulo_id) REFERENCES modulo(id);


--
-- Name: fk_historico_pessoa; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY historico
    ADD CONSTRAINT fk_historico_pessoa FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- Name: fk_menu_acao; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT fk_menu_acao FOREIGN KEY (acao_id) REFERENCES acao(id);


--
-- Name: fk_menu_parent; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY menu
    ADD CONSTRAINT fk_menu_parent FOREIGN KEY (parent_id) REFERENCES menu(id);


--
-- Name: fk_pessoa_categoria; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY pessoa
    ADD CONSTRAINT fk_pessoa_categoria FOREIGN KEY (categoria_id) REFERENCES categoria(id);


--
-- Name: fk_pessoa_fisica_pessoa; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY pessoa_fisica
    ADD CONSTRAINT fk_pessoa_fisica_pessoa FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- Name: fk_pessoa_juridica_pessoa; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY pessoa_juridica
    ADD CONSTRAINT fk_pessoa_juridica_pessoa FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- Name: fk_usuario_grupo_id; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT fk_usuario_grupo_id FOREIGN KEY (grupo_id) REFERENCES grupo(id);


--
-- Name: fk_usuario_municipio_id; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT fk_usuario_municipio_id FOREIGN KEY (municipio_id) REFERENCES municipio(id);


--
-- Name: fk_usuario_pessoa_id; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT fk_usuario_pessoa_id FOREIGN KEY (pessoa_id) REFERENCES pessoa(id);


--
-- Name: log_municipio_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: urbem
--

ALTER TABLE ONLY log
    ADD CONSTRAINT log_municipio_id_fk FOREIGN KEY (municipio_id) REFERENCES municipio(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- Name: samlink; Type: ACL; Schema: -; Owner: urbem
--

REVOKE ALL ON SCHEMA samlink FROM PUBLIC;
REVOKE ALL ON SCHEMA samlink FROM urbem;
GRANT ALL ON SCHEMA samlink TO urbem;


--
-- PostgreSQL database dump complete
--

