<?php

namespace App\Grids;

use App\Model\Services\UsersService;
use Kdyby\Translation\Translator;
use Nette;
use Ublaboo\DataGrid\DataGrid;


class UserGridFactory
{
    use Nette\SmartObject;
    /** @var GridFactory */
    private $factory;

    /** @var Nette\Security\User */
    private $user;

    /** @var UsersService */
    private $service;

    /** @var DataGrid */
    private $grid;

    /** @var Nette\Application\UI\Presenter */
    private $presenter;

    /** @var Translator */
    private $translator;

    public function __construct(GridFactory $factory, Nette\Security\User $user, UsersService $service, Translator $translator)
    {
        $this->factory = $factory;
        $this->service = $service;
        $this->user = $user;
        $this->translator = $translator;
    }

    /**
     * @return DataGrid
     */
    public function create(Nette\Application\UI\Presenter $presenter, $name)
    {
        $this->grid = $this->factory->create($presenter, $name, $this->translator);
        $this->presenter = $presenter;

        $this->grid->setPagination(FALSE);
        $datasource = $this->service->getRepo()->createQueryBuilder('usr');
        $this->grid->setDataSource($datasource);

        //cols
        $this->grid->addColumnText('name','main.user.grid.name')
            ->setSortable()
            ->setFilterText();
        $this->grid->addColumnText('surname','main.user.grid.surname')
            ->setSortable()
            ->setFilterText();
        $this->grid->addColumnText('email','main.user.grid.email')
            ->setSortable()
            ->setFilterText();

        $roles = $this->service->getRepo()->related('role')->findAll();
        $role_col = $this->grid->addColumnStatus('role_','main.user.grid.role', 'role.id');

        foreach ($roles as $role) {

            $role_col->addOption($role->getId(), $role->getName())
                //->setIcon('check')
                ->setClass('btn-' . $role->getCssClass())
                ->endOption();
        }

        $role_col->onChange[] = [$presenter, 'handleChangeRole'];

        $this->grid->addColumnText('deleted','main.user.grid.deleted')
            ->setSortable()
            ->setRenderer(function ($item) {
                $val = $item->isDeleted() ? 'TRUE' : 'FALSE';
                return $val;
            })
            ->setFilterSelect(['' => ' - - ', 1 => 'TRUE', 0 => 'FALSE']);

        $this->inlineAdd();
        $this->inlineEdit();
        $this->inlineActions();

        return $this->grid;
    }

    protected function inlineAdd()
    {
        $roles_array = $this->service->getRepo()->related('role')->findPairs('name');
        $this->grid->addInlineAdd()
            ->onControlAdd[] = function ($container) use ($roles_array) {
            $container->addText('name', '');
            $container->addText('surname', '');
            $container->addText('email', '');
            $container->addSelect('role', '', $roles_array);
        };
        $this->grid->getInlineAdd()->onSubmit[] = [$this->presenter, 'handleAdd'];
    }

    protected function inlineEdit()
    {
        $roles_array = $this->service->getRepo()->related('role')->findPairs('name');
        $deleted_options=[1 => 'TRUE', 0 => 'FALSE'];
        $presenter = $this->presenter;

        $this->grid->addInlineEdit()
            ->onControlAdd[] = function ($container) use ($roles_array,$deleted_options) {
            $container->addText('name', '');
            $container->addText('surname', '');
            $container->addText('email', '');
        };
        $this->grid->getInlineEdit()->onSetDefaults[] = function ($container, $item) {
            $container->setDefaults([
                'name' => $item->getName(),
                'surname' => $item->getSurname(),
                'email' => $item->getEmail()]);
        };
        $this->grid->getInlineEdit()->onSubmit[] = function ($id, $values) use ($presenter) {
            $presenter->handleEdit($values, $id);
        };

    }

    protected function inlineActions()
    {
        $user = $this->user;

        $this->grid->addAction('delete', '', 'delete!')
            ->setIcon('trash')
            ->setTitle('ublaboo_datagrid.delete')
            ->setClass('btn btn-xs btn-danger ajax')
            ->setConfirm('ublaboo_datagrid.deleteConfirm', 'name'); // Second parameter is optional

        $this->grid->allowRowsAction('delete', function ($item) use ($user) {
            return $item->id != $user->getId();
        });

        $this->grid->addAction('resetpasswd', '', 'resetpasswd!')
            ->setIcon('shield')
            ->setTitle('main.user.grid.resetPasswd')
            ->setClass('btn btn-xs btn-warning ajax')
            ->setConfirm('main.user.grid.resetPasswdConfirm');
        $this->grid->allowRowsAction('resetpasswd', function ($item) use ($user) {
            return $item->id != $user->getId();
        });

    }
}