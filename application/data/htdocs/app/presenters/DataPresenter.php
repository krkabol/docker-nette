<?php

namespace App\Presenters;

class DataPresenter extends SecuredPresenter
{

    public function renderDefault()
    {
        $this->template->title = $this->translator->translate('main.data.title');
    }

}