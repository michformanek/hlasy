<?php

namespace App\Presenters;

use Nette;
use App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    protected function beforeRender()
    {
        parent::beforeRender();
        if ($this->isAjax()) {
            $this->redrawControl('title');
            $this->redrawControl('content');
        }
    }
}
