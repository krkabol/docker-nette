<?php
namespace App\Presenters;

use App\Forms\PasswdFormFactory;
use App\Grids\UserGridFactory;
use App\Model\Authenticator;
use Nette\Neon\Exception;

class UserPresenter extends SecuredPresenter
{
    /** @var PasswdFormFactory @inject */
    public $passwdFormFactory;

    /** @var UserGridFactory @inject */
    public $userGrid;

    public function renderDefault(){
       $this->onlyAdmin();
    }

    public function renderPassword()
    {
        $this->template->title = $this->translator->translate('main.user.passwd.title');
    }

    public function passwdFormSucceeded($form)
    {
        $this->flash($this->translator->translate('main.user.passwd.changed'));
        $this->redirect('Homepage:');
    }

    protected function createComponentPasswdForm()
    {
        $form = $this->passwdFormFactory->create();
        $form->onSuccess[] = array($this, 'passwdFormSucceeded');

        return $form;
    }

    public function createComponentSimpleGrid($name = 'simpleGrid')
    {
        $grid = $this->userGrid->create($this, $name);
        return $grid;
    }

    public function handleToggleDeleted($id)
    {
        try {
            $user = $this->usersService->toggleDeleted($id);
            $this->flash('Uživatel ' . $user->getName() . " smazán!");
        } catch (\Exception $e) {
            $this->flash('Nebylo možné smazat uživatele.', null, 'error', $e);
        }

        if ($this->isAjax()) {
            $this->redrawControl('flashMessage');
            $this['simpleGrid']->reload();
        } else {
            $this->redirect('this');
        }
    }

    public function handleEdit($values, $id)
    {
        $values['id'] = $id;
        try {
            $user = $this->usersService->updateUser($values);
            $this->flash( $user->getFullname(), "success",
                $this->translator->translate('main.user.grid.editSuccess'));
        } catch (\Exception $e) {
            $this->flash(
                $this->translator->translate('main.user.grid.editError').$e->getMessage(), 'error');
        }
        if ($this->isAjax()) {
            $this->redrawControl('flashMessage');
            $this['simpleGrid']->reload();
        } else {
            $this->redirect('this');
        }
    }

    public function handleChangeRole($id, $status)
    {
        try {
            $user=$this->usersService->changeRole($id, $status);
            $this->flash($user->getFullname(), "success",
                $this->translator->translate('main.user.grid.changeRoleSuccess'));
        } catch (\Exception $e) {
            $this->flash(
                $this->translator->translate('main.user.grid.changeRoleError').$e->getMessage(), 'error');
        }
        if ($this->isAjax()) {
            $this->redrawControl('flashMessage');
            $this['simpleGrid']->reload();
        } else {
            $this->redirect('this');
        }
    }

    public function handleAdd($values)
    {
        try {
            $this->usersService->createUser($values);
            $this->flash("Vytvořen uživatel " . $values['name'] . ".");
        } catch (\Exception $e) {
            $this->flash('Nebylo možné přidat uživatele.', null, 'error', $e);
        }
        if ($this->isAjax()) {
            $this->redrawControl('flashMessage');
            $this['simpleGrid']->reload();
        } else {
            $this->redirect('this');
        }
    }

    public function handleResetPasswd($id)
    {
        try {
            $user = $this->usersService->resetPassword($id);
            $this->flash("Heslo pro uživatele %s bylo nastaveno na výchozí \"".Authenticator::DEFAULT_PASSWORD ."\".", $user->getName());
        } catch (\Exception $e) {
            $this->flash('Nebylo možné resetovat heslo.', null, 'error', $e);
        }
        if ($this->isAjax()) {
            $this->redrawControl('flashMessage');
            $this['simpleGrid']->reload();
        } else {
            $this->redirect('this');
        }
    }
}