<?php

namespace DidUngar\MailboxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
	/**
	 * @Route("/")
	 * @Template()
	 */
	public function indexAction() {
		$mbox = imap_open(
			'{'.$this->container->getParameter('mailbox_host').'/imap}',
			$this->container->getParameter('mailbox_user'),
			$this->container->getParameter('mailbox_password')
			);
		$mails = FALSE;

		if (FALSE === $mbox) {
			$err = 'La connexion a échoué. Vérifiez vos paramètres!';
		} else {
			$oInfo = imap_check($mbox);
			if (FALSE !== $oInfo) {
				$nbMessages = min(50, $oInfo->Nmsgs);
				$aMails = imap_fetch_overview($mbox, '1:'.$nbMessages);
			} else {
				$err = 'Impossible de lire le contenu de la boite mail';
			}
			imap_close($mbox);
		}


		return [
			'oInfo' => $oInfo,
			'sMails' => json_encode($aMails),
		];
	}
	/**
	 * @Route("/mail-{uid_mail}")
	 * @Template()
	 */
	public function mailAction($uid_mail) {
		$mbox = imap_open(
			'{'.$this->container->getParameter('mailbox_host').'/imap}',
			$this->container->getParameter('mailbox_user'),
			$this->container->getParameter('mailbox_password')
			);
		$mails = FALSE;

		if (FALSE === $mbox) {
			$err = 'La connexion a échoué. Vérifiez vos paramètres!';
		} else {
			$sMail = quoted_printable_decode(imap_fetchbody($mbox, $uid_mail, 2, FT_UID));
			imap_close($mbox);
		}


		return [
			'uid_mail' => $uid_mail,
			'sMail' => $sMail,
		];
	}
}
