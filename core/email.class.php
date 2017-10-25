<?php

Load::_require('/vendor/autoload.php');

class Email{

	private $destinatario;
	private $assunto;
	private $mensagem;
	private $remetente;
    private $template;


    public function __construct(){
        $this->template = null;
    }

	public function getDestinatario(){
	    return $this->destinatario;
	}

	public function setDestinatario($destinatario){
		if(is_array($destinatario)){
	    	$this->destinatario = concatStr($destinatario, ',');
		} else {
			$this->destinatario = $destinatario;
		}
	}

	public function getAssunto(){
	    return $this->assunto;
	}

	public function setAssunto($assunto){
	    $this->assunto = MAIL_TITLE.' - '.$assunto;
	}

	public function getMensagem(){
	    return $this->mensagem;
	}

	public function setMensagem($mensagem){
	    $this->mensagem = $mensagem;
	}

	public function getRemetente(){
	    return $this->remetente;
	}

	public function setRemetente($remetente){
	    if(Validator::validateEmail($remetente)){
	    	$this->remetente = $remetente;
	    } else {
	    	$this->remetente = $GLOBALS['MAIL_SENDER'];
	    }
	}

	public function getTemplate(){
	    return $this->template;
	}

	public function setTemplate($template){
	    $this->template = $template;
	}

    public function enviar(Contato $contato){
        $transport = (new Swift_SmtpTransport(SMTP_SERVER, SMTP_PORT, SMTP_SECURITY))
            ->setUsername(SMTP_USERNAME)
            ->setPassword(SMTP_PASSWORD);

        $mailer = new Swift_Mailer($transport);

        $message = new Swift_Message($this->assunto);
        $message
            ->setFrom([$contato->getEmail() => $contato->getNome()])
            ->setTo([$this->destinatario => MAIL_TITLE])
            ->setBody($this->mensagem, 'text/html');

        if ($mailer->send($message)) {
            return true;
        } else {
            throw new Exception('Erro ao enviar o e-mail');
        }
    }
}
