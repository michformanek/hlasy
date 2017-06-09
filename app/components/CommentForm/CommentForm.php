<?php

namespace App\Forms;

use App\Model\Comment;
use App\Repository\CommentRepository;
use App\Repository\ProposalRepository;
use Tracy\Debugger;
use Nette\Application\UI;

class CommentForm extends UI\Control
{

    private $id;
    /**
     * @var CommentRepository
     */
    private $commentRepository;
    /**
     * @var ProposalRepository
     */
    private $proposalRepository;

    /**
     * LoginForm constructor.
     * @param int $id
     * @param CommentRepository $commentRepository
     * @param ProposalRepository $proposalRepository
     */
    public function __construct(int $id = 1,CommentRepository $commentRepository,ProposalRepository $proposalRepository)
    {
        parent::__construct();
        $this->id=$id;
        $this->commentRepository = $commentRepository;
        $this->proposalRepository = $proposalRepository;
    }

    /**
     * @return Form
     */
    protected function createComponentCommentForm()
    {
        $form = new UI\Form;
        $form->addTextArea('text')
            ->setRequired('Prosím zadejte text komentáře');
        $form->addSubmit('send', 'Přidat komentář');
        $form->addHidden('id',$this->id);
        $form->onSuccess[] = [$this, 'commentFormSucceeded'];
        return $form;

    }

    public function commentFormSucceeded($form)
    {
        $values = $form->getValues();
        $comment = new Comment();
        $comment->setProposal($this->proposalRepository->find($values->id));
        $comment->setText($values->text);
        $this->commentRepository->saveOrUpdate($comment);

        $this->flashMessage('Děkuji za komentář', 'success');
        $this->redirect('Proposal:detail',$values->id);
    }


    public function render()
    {
        $this->template->render(__DIR__ . '/CommentForm.latte');
    }

}

interface ICommentFormFactory
{
    /**
     * @param int $id
     * @return CommentForm
     */
    function create(int $id);
}
