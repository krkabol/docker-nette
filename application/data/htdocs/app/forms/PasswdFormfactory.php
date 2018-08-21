<?php

namespace App\Forms;

use App\Model\Services\UsersService;
use Kdyby\Translation\Translator;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security as NS;


class PasswdFormFactory
{
    use Nette\SmartObject;
    /** @var FormFactory */
    private $factory;

    /** @var NS\User */
    private $user;

    /** @var UsersService */
    private $userService;

    /** @var  Translator */
    private $translator;

    public function __construct(FormFactory $factory, NS\User $user, UsersService $usersService, Translator $translator)
    {
        $this->factory = $factory;
        $this->user = $user;
        $this->userService = $usersService;
        $this->translator=$translator;
    }

    /**
     * @return Form
     */
    public function create()
    {
        $form = $this->factory->create();

        $form->addPassword('password_old', $this->translator->translate('main.user.passwd.currentLabel'))
            ->setRequired($this->translator->translate('main.user.passwd.current'))
            ->setAttribute('autocomplete', 'off');

        $form->addPassword('password_new', $this->translator->translate('main.user.passwd.newLabel'))
            ->setRequired($this->translator->translate('main.user.passwd.new'))
            ->setAttribute('autocomplete', 'off');

        $form->addPassword('password_confirm', $this->translator->translate('main.user.passwd.new2Label'))
            ->setRequired($this->translator->translate('main.user.passwd.new2'))
            ->addRule(Form::EQUAL, $this->translator->translate('main.user.passwd.notEqual'), $form['password_new'])
            ->setAttribute('autocomplete', 'off');

        $form->addSubmit('send', $this->translator->translate('main.user.passwd.send'));

        $form->onSuccess[] = array($this, 'formSucceeded');
        return $form;
    }

    public function formSucceeded(Form $form, $values)
    {
        try {
            $userEntity = $this->userService->find($this->user->getId());

            (NS\Passwords::verify($values->password_old, $userEntity->getPassword())) ? $passVerified = TRUE : $passVerified = FALSE;

            if (!$passVerified) {
                throw new Nette\Neon\Exception($this->translator->translate('main.user.passwd.notVerified'));
            }
            $userEntity->setPassword(NS\Passwords::hash($values->password_new));
            $this->userService->getRepo()->getEntityManager()->flush();
        } catch (\Exception  $e) {

            $form->addError($e->getMessage());
        }
    }
}