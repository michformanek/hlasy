<?php

namespace App\Service;

use Nette;
use Latte;
use App\Model\Vote;
use App\Repository\VoteRepository;
use Nette\Mail\Message;
use Nette\Mail\IMailer;
use App\Repository\ProposalRepository;
use App\Repository\UserRepository;

class MailService
{
    /** @var ProposalRepository */
    private $proposalRepository;

    /**
     * @var \App\Repository\UserRepository
     */
    public $userRepository;

    /**
     * @var \Nette\Mail\IMailer
     */
    public $mailer;

    public function __construct(
        ProposalRepository $proposalRepository,
        VoteRepository $voteRepository,
        UserRepository $userRepository,
        Nette\Mail\IMailer $mailer)
    {
        $this->mailer = $mailer;
        $this->proposalRepository = $proposalRepository;
        $this->voteRepository = $voteRepository;
        $this->userRepository = $userRepository;
    }


    public function sendDigestEmails($votes)
    {
        $mailer = $this->mailer;
        foreach ($votes as $vote) {
            $message = $this->createDigestMessage($vote);
            $this->$mailer->send($message);
        }
    }

    public function createDigestMessage(Vote $vote)
    {
        $latte = new Latte\Engine;
        $params = [
            'vote' => $vote,
        ];

        $mail = new Message;
        $mail->setFrom('Hlasovací systém <hlasys@hkfree.org>')
            ->addTo($vote->getAuthor()->getEmail())
            ->setSubject('Pobídka k hlasování')
            ->setHtmlBody($latte->renderToString('templates/digest.latte', $params));
        return $mail;
    }

}