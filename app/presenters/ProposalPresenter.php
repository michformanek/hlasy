<?php
/**
 * Created by PhpStorm.
 * User: mformanek
 * Date: 5.2.17
 * Time: 20:43
 */

namespace App\Presenters;


use App\Components\IProposalDatagridFactory;
use App\Components\IProposalTableFactory;
use App\Forms\ICommentFormFactory;
use App\Forms\IProposalFormFactory;
use App\Model\Comment;
use App\Repository\ProposalRepository;
use App\Repository\CommentRepository;
use Nette\Application\UI\Form;
use Tracy\Debugger;

class ProposalPresenter extends SecuredPresenter
{

    /** @var ProposalRepository */
    private $proposals;

    /** @var CommentRepository */
    private $comments;

    /**
     * @var ProposalDatagridFactory
     */
    public $proposalDatagridFactory;

    /**
     * @var ProposalFormFactory
     */
    public $proposalFormFactory;

    /**
     * @var CommentFormFactory
     */
    private $commentFormFactory;

    public function __construct(
        ProposalRepository $proposals,
        CommentRepository $commentRepository,
        IProposalDatagridFactory $datagridFactory,
        IProposalFormFactory $proposalFormFactory,
        ICommentFormFactory $commentFormFactory)
    {
        $this->proposals = $proposals;
        $this->comments = $commentRepository;
        $this->proposalDatagridFactory = $datagridFactory;
        $this->proposalFormFactory = $proposalFormFactory;
        $this->commentFormFactory = $commentFormFactory;
    }

    public function renderDefault()
    {
        $this->template->proposals = $this->proposals->findAll();
    }


    public function renderAdd()
    {

    }

    public function renderEdit($id)
    {
        //$this->proposalFormCreate->create($id);
    }

    public function renderDetail($id)
    {
        $proposal = $this->proposals->find($id);;
        if (!$proposal) {
            $this->error('Návrh nebyl nalezen');
        }

        $this->template->proposal = $proposal;
    }

    public function renderLogs($id)
    {
        $proposal = $this->proposals->find($id);;
        if (!$proposal) {
            $this->error('Návrh nebyl nalezen');
        }

        $this->template->proposal = $proposal;
    }

    public function renderDelete($id = 0)
    {
        $this->template->album = $this->albums->findById($id);
        if (!$this->template->album) {
            $this->error('Record not found');
        }
    }

    public function createComponentProposalTable()
    {
        return $this->proposalDatagridFactory->create(
            function ($id) {
                $this->redirect('Proposal:detail', $id);
            });
    }

    public function createComponentProposalForm()
    {
        return $this->proposalFormFactory->create(function () {
            //$this->restoreRequest($this->backlink);
            $this->redirect('this'); //TODO
        });
    }

    protected function createComponentCommentForm()
    {
        return $this->commentFormFactory->create($this->getParameter('id'));
    }

}