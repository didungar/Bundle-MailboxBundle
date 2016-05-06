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
		$oMailboxService = new \DidUngar\MailboxBundle\Services\MailboxService($this->container);
		$mbox = $oMailboxService->open();
		$oInfo = $oMailboxService->check();
		$nbMessages = min(50, $oInfo->Nmsgs);
		$aMails = imap_fetch_overview($mbox, '1:'.$nbMessages);
		$oMailboxService->close();
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
		$oMailboxService = new \DidUngar\MailboxBundle\Services\MailboxService($this->container);
		$mbox = $oMailboxService->open();
		$mails = FALSE;
		$sMail = quoted_printable_decode(imap_fetchbody($mbox, $uid_mail, 2, FT_UID));
		$oMailboxService->close();
		return [
			'uid_mail' => $uid_mail,
			'sMail' => $sMail,
		];
	}
}
