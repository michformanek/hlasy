<?php

namespace App\Forms;

use App\Model;
use App\Repository\GroupRepository;
use App\Repository\ProposalRepository;
use App\Repository\UserRepository;
use Nette\Security\User;
use Nette\Application\UI;

class ProposalForm extends UI\Control
{

    private $onSuccess;

    private $repository;
    private $userRepository;
    private $groupRepository;

    /** @var \Nette\Security\User */
    private $user;

    /**
     * LoginForm constructor.
     * @param callable $onSuccess
     * @param ProposalRepository repository
     */
    public function __construct(callable $onSuccess, ProposalRepository $repository, UserRepository $userRepository, GroupRepository $groupRepository, User $user)
    {
        parent::__construct();
        $this->onSuccess = $onSuccess;
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
        $this->repository = $repository;
        $this->user = $user;

    }

    /**
     * @return Form
     */
    protected function createComponentMyForm() //TODO
    {
        $groups = $this->groupRepository->findAll();
        $form = new UI\Form;
        $form->addText('title', 'Titulek:')
            ->setRequired('Prosím zadejte název žádosti');
        $form->addTextArea('description');
        $form->addSelect('group', 'Bude rozhodovat:', $this->convertGroupsToArray($groups));
        $form->addSubmit('send', 'Vytvořit návrh');
        $form->onSuccess[] = function ($form, $values) {
            $this->repository->saveOrUpdate($this->convertValuesToProposal($values));
            $this->onSuccess;
        };
        return $form;

    }


    public function render()
    {
        $this->template->render(__DIR__ . '/ProposalForm.latte');

    }

    private function convertValuesToProposal($values)
    {
        //TODO Upravit tak, aby se pri editaci neprepsaly puvodni hodnoty
        $proposal = new Model\Proposal();

       // if ($values['id']) {
       //     $proposal->setId($values['id']);
        //}

        $proposal->setTitle($values['title']);
        $proposal->setAuthor($this->userRepository->findOneBy(array('id' => $this->user->getId())));
        $dateStart = new \DateTime('now');
        $dateEnd = new \DateTime('now');
        $dateEnd->modify("+ 30 days");
        $proposal->setDateCreated($dateStart);
        $proposal->setDateEnd($dateEnd);
        $proposal->setDescription($values['description']);
      //  $proposal->setResponsibleGroup($this->groupRepository->findOneBy(array('id' => $values['group'])));
        $proposal->setTrash(false);
        return $proposal;
    }

    private function convertProposalToValues($proposal)
    {

    }

    private function convertGroupsToArray($groups)
    {
        $result = array();
        foreach ($groups as $group) {
            $result[$group->getId()] = $group->getName();
        }
        return $result;
    }

}

interface IProposalFormFactory
{
    /**
     * @param callable $onSuccess
     * @return ProposalForm
     */
    function create(callable $onSuccess);
}
