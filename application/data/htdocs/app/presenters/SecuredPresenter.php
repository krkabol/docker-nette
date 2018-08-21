<?php
namespace App\Presenters;

abstract class SecuredPresenter extends BasePresenter
{
    /**
     * @inject
     * @var \App\Model\Services\UsersService
     */
    public $usersService;


    public function startup()
    {
        parent::startup();
        if (!$this->user->isLoggedIn()) {
            $this->flash($this->translator->translate('main.app.sign.missing'), "error");
            $this->redirect('Sign:in', array('backlink' => $this->storeRequest()));
        }
    }

    public function beforeRender()
    {
        parent::beforeRender();
        $this->template->userEntity = $this->usersService->find($this->user->getId());
    }

    public function afterRender()
    {
        parent::afterRender();
        if ($this->isAjax() && $this->hasFlashSession()) {
            $this->redrawControl('flashMessage');
        }
    }

    public function onlyAdmin(){
        if (!$this->user->isInRole('admin')){
            $this->flash($this->translator->translate('main.app.error.403'), "error");
            $this->redirect('Homepage:');
        }
    }

}