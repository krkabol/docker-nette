<?php
namespace App\Presenters;

class HomepagePresenter extends SecuredPresenter
{
    public function renderDefault(){

        $this->template->title=$this->translator->translate("main.homepage.title");
    }

}
