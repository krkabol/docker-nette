<?php

namespace App\Forms;

use Kdyby\Translation\Translator;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;

class SignForm extends Control
{
    /** @var FormFactory */
    private $factory;

    /** @var User */
    private $user;

    /** @var Translator */
    private $translator;

    public $onFormFinished;

    public function __construct(FormFactory $factory, User $user, Translator $translator)
    {
        parent::__construct();
        $this->factory = $factory;
        $this->user = $user;
        $this->translator = $translator;

    }

    protected function createComponentSignForm()
    {
        $form = $this->factory->create();
        $form->addText('username')
            ->setRequired($this->translator->translate('form.sign.email.required'))
            ->addRule(Form::EMAIL);

        $form->addPassword('password')
            ->setRequired($this->translator->translate('form.sign.passwd.required'));
        //$form->addCheckbox('remember')->setDisabled(TRUE);
        $form->addSubmit('send', $this->translator->translate('form.sign.send'));

        $form->onSuccess[] = array($this, 'formSucceeded');
        return $form;
    }

    public function formSucceeded(Form $form, $values)
    {
        $this->user->setExpiration('20 minutes', TRUE);
        try {
            $this->user->login($values->username, $values->password);
        } catch (AuthenticationException $e) {

            $form->addError($e->getMessage());
        }
        $this->onFormFinished($this);
    }

    public function render()
    {
        $this->template->setFile(__DIR__ . '/templates/signForm.latte');
        $this->template->render();
    }
}

interface ISignFormFactory
{
    /** @return SignForm */
    function create();
}