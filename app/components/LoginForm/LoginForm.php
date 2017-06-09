<?php

namespace App\Forms;

use Nette\Security\User;
use Nette,
    Nette\Application\UI;

class LoginForm extends UI\Control
{

    private $onSuccess;

    /** @var \Nette\Security\User */
    private $user;

    /**
     * LoginForm constructor.
     * @param callable $onSuccess
     * @param User $user
     */
    public function __construct(callable $onSuccess,User $user)
    {
        parent::__construct();
        $this->onSuccess = $onSuccess;
        $this->user = $user;
    }

    /**
     * @return Form
     */
    protected function createComponentMyForm()
    {
        $form = new UI\Form;
        $form->addText('username', 'Username:')
            ->setRequired('Prosím zadejte uživatelské jméno');
        $form->addPassword('password', 'Password:')
            ->setRequired('Prosím zadejte heslo');
        $form->addCheckbox('remember', 'Zapamatuj si mě');
        $form->addSubmit('send', 'Přihlásit se');
        $form->onSuccess[] = function ($form, $values) {
            try {
                $this->user->setExpiration($values->remember ? '14 days' : '20 minutes');
                $this->user->login($values->username, $values->password);
            } catch (Nette\Security\AuthenticationException $e) {
                $form->addError('The username or password you entered is incorrect.');
                return;
            }
            $this->onSuccess;
        };
        return $form;

    }


    public function render()
    {
        $this->template->render(__DIR__ . '/LoginForm.latte');

    }
}

interface ILoginFormFactory
{
    /**
     * @param callable $onSuccess
     * @return LoginForm
     */
    function create(callable $onSuccess);
}