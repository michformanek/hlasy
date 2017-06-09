<?php

namespace App\Components;

use Nette,
    Nette\Application\UI;

/**
 * Created by PhpStorm.
 * User: mformanek
 * Date: 12.2.17
 * Time: 13:24
 */
class ProposalTable extends UI\Control
{

    private $proposals;


    /**
     * ProposalTable constructor.
     */
    public function __construct(array $proposals)
    {
        $this->proposals = $proposals;
    }

    public function render()
    {
        $this->template->render(__DIR__ . '/ProposalTable.latte');
        $this->template->proposals = $this->proposals;
    }
}

interface IProposalTableFactory
{
    /**
     * @param array $proposals
     * @return ProposalTable
     */
    function create(array $proposals);
}