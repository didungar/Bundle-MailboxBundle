<?php
namespace DidUngar\MailboxBundle\Services;

class MailboxService {
	protected $container;
	protected $rMailbox = null;
	protected $oCheck = null;
	public function __construct($service_container) {
		$this->container = $service_container;
	}

	public function open(array $aArgs = []) {
		$this->rMailbox = imap_open(
			'{'.$this->container->getParameter('mailbox_host').'/imap}',
			$this->container->getParameter('mailbox_user'),
			$this->container->getParameter('mailbox_password')
			);
		if (FALSE === $this->rMailbox) {
			throw new \Exception('La connexion a échoué. Vérifiez vos paramètres!');
		}
		return $this->rMailbox;
	}

	/**
	 * Driver - protocole utilisé pour accéder à la boîte aux lettres: POP3, IMAP, NNTP.
	 * Mailbox - nom de la boîte aux lettres
	 * Nmsgs - nombre de messages de la boîte aux lettres
	 * Recent - nombre de messages récents de la boîte aux lettres 
	**/
	public function check(array $aArgs = []) {
		if ( ! $this->oCheck = imap_check($this->rMailbox) ) {
			throw new \Exception('Impossible de lire le contenu de la boite mail');
		}
		return $this->oCheck;
	}

	public function getMails(array $aArgs = []) {
		// TODO
	}

	public function close(array $aArgs = []) {
		return imap_close($this->rMailbox);
	}
}



