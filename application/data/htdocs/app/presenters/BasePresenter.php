<?php

namespace App\Presenters;

use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{
    /** @persistent */
    public $locale;

    /** @var \Kdyby\Translation\Translator @inject */
    public $translator;

    public function beforeRender()
    {
        parent::beforeRender();
        $this->template->title = '';
        $this->template->locale = $this->locale;
    }

    /**
     * @param $text
     * @param string $type
     * @param null $format
     */

    public function flash($text, $type = "success", $format = null)
    {
        if ($text instanceof \Exception) {
            $msg = $this->translator->translate('main.app.error.flash').": [" . $text->getMessage() . "]";
            $this->flashMessage($msg, 'error');
            return;
        }

        if (null !== $format) {
            $text = sprintf($format, '"' . $text . '"');
        }

        $this->flashMessage($text, $type);
        return;
    }
}
