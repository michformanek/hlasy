<?php

namespace App\Presenters;

use Nette;
use App\Forms;
use Kdyby;


class LoginPresenter extends BasePresenter
{
	/** @var Forms\ILoginFormFactory @inject */
	public $loginFormFactory;

    /** @var Kdyby\Doctrine\EntityManager @inject */
	public $em;

    /** @persistent */
    public $backlink = '';


	/**
	 * Login-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentLoginForm()
	{
		return $this->loginFormFactory->create(function () {
            //$this->restoreRequest($this->backlink);
            $this->redirect('Homepage:default'); //TODO
        });
	}

	public function actionOut()
	{
		$this->getUser()->logout();
        $this->redirect('Homepage:default');
    }

}
