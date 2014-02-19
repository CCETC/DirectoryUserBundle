<?php
namespace CCETC\DirectoryUserBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Doctrine\ORM\Query\Expr\Join;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

use Sonata\AdminBundle\Route\RouteCollection;

class UserAdmin extends Admin
{
    protected function configureRoutes(RouteCollection $collection)
    {

        $collection->add('promote', 'promote/{id}');
        $collection->add('demote', 'demote/{id}');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
    }
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('email');

        if(!$this->getSubject()->getId()) {
            $formMapper->add('plainPassword', 'text');

            $this->formPreHook = array(
                'template' => 'CCETCDirectoryUserBundle:Admin:_form_pre_hook.html.twig'
            );
        }

        $formMapper->add('enabled');
    }


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('email')
            ->add('listing')
            ->add('enabled', null, array('editable' => true))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'promote' => array('template' => 'CCETCDirectoryUserBundle:Admin:_promote_action.html.twig'),
                    'demote' => array('template' => 'CCETCDirectoryUserBundle:Admin:_demote_action.html.twig'),
                )
            ))   
        ;
    }
    
    protected function configureShowFields(ShowMapper $showMapper)
    {

    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($user)
    {
        $this->getUserManager()->updateCanonicalFields($user);
        $this->getUserManager()->updatePassword($user);
    }

    /**
     * @param UserManagerInterface $userManager
     */
    public function setUserManager($userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->userManager;
    }
}