<?php
namespace App\Grids;

use Nette;
use Ublaboo\DataGrid\DataGrid;
use Ublaboo\DataGrid\Localization\SimpleTranslator;

class GridFactory
{
use Nette\SmartObject;
    /**
     * @return DataGrid
     */
    public function create(Nette\Application\UI\Presenter $presenter, $name, $translator)
    {
        $grid = new DataGrid($presenter, $name);
        $grid->setTranslator($translator);
        return $grid;
    }

}