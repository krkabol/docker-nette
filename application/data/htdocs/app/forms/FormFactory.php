<?php
namespace App\Forms;
use Nette\Application\UI\Form;
use Nette\SmartObject;

class FormFactory
{
    use SmartObject;
    /**
     * @return Form
     */
    public function create()
    {
        return new Form;
    }

}
