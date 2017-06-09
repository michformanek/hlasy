<?php
/**
 * Created by PhpStorm.
 * User: mformanek
 * Date: 2.4.17
 * Time: 12:55
 */

namespace App\Service;


use App\Repository\VoteRepository;

class CronTaskService
{

    private $mailService;
    private $voteRepository;

    /**
     * CronTaskService constructor.
     * @param $mailService
     */
    public function __construct(MailService $mailService,VoteRepository $voteRepository)
    {
        $this->mailService = $mailService;
        $this->voteRepository = $voteRepository;
    }


    /**
     * @cronner-task E-mail sending
     * @cronner-period 1 day
     */
    public function sendEmails()
    {
        $votes = $this->votesRepository->findSoonEnding();
        $this->mailService->sendDigestEmails();
    }

}