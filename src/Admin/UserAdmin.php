<?php

declare(strict_types=1);

namespace App\Admin;

use DeepCopy\TypeFilter\TypeFilter;
use phpDocumentor\Reflection\Types\Array_;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sonata\Form\Type\CollectionType;

final class UserAdmin extends AbstractAdmin
{

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('email')
            ->add('name')
            ->add('roles')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('email')
            ->add('name')
            ->add('roles',null, ['label'=>'Роль'])
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name')
            ->add('email')
           // ->add('roles', CollectionType::class, ['allow_add' => true,'allow_delete' => true])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Пароль'],
                'second_options' => ['label' => 'Подтверждение пароля']
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('email')
            ->add('name')
           ->add('roles',null, ['label'=>'Роль'])
        ;
    }
}
