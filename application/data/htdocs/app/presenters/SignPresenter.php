<?php
namespace App\Presenters;

use App\Forms\ISignFormFactory;
use Nette;


class SignPresenter extends BasePresenter
{
    /** @var ISignFormFactory @inject */
    public $formFactory;

    /** @persistent */
    public $backlink = '';

    protected function createComponentSignInForm()
    {
        $form = $this->formFactory->create();
        $form->onFormFinished[] = array($this, 'signInFormSucceeded');
        return $form;
    }

    public function signInFormSucceeded($form)
    {
        $this->restoreRequest($this->backlink);
        $this->redirect('Homepage:');
    }


    public function renderIn()
    {
        $this->getUser()->logout();
        $this->template->title = $this->translator->translate("main.app.sign.in");
    }


    public function renderOut()
    {
        $this->getUser()->logout();
        $this->redirect(':in');

    }

}
