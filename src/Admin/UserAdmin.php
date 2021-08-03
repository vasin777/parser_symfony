<?php

declare(strict_types=1);

namespace App\Admin;
use App\Entity\User;
use SebastianBergmann\CodeCoverage\Report\Text;
use Sonata\Doctrine;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

final class UserAdmin extends AbstractAdmin
{
    protected $passwordEncoder;

    public function setPasswordEncoder(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function preUpdate($object)
    {
        $this->setPassword($object);
    }

    public function prePersist($object)
    {
        $this->setPassword($object);
    }

    protected function setPassword(User $object)
    {
        if ($object->getPassword()) {
            $object->setPassword(
                $this->passwordEncoder->encodePassword($object, $object->getPassword())
            );
            $object->setRoles('ROLE_ADMIN');
        }
    }

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
            ->add('name', TextType::class, ['label'=>'Имя'])
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
            ->add('name', TextType::class, ['label'=>'Имя'])
            ->add('email')
            ->add('password', PasswordType::class, ['label'=>'Пароль'])
        /*    ->add('roles', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
            ]) */
        ;
    }


    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('email')
            ->add('name',TextType::class, ['label'=>'Имя'])
           ->add('roles',null, ['label'=>'Роль'])
        ;
    }
}
