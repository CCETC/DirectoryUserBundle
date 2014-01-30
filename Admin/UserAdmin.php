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
        $formMapper
            ->add('listing', null, array('required' => false))
        ;
    }


    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('email')
            ->add('listing')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'view' => array(),
                    'edit' => array(),
                    'promote' => array('template' => 'CCETCDirectoryUserBundle:Admin:_promote_action.html.twig'),
                    'demote' => array('template' => 'CCETCDirectoryUserBundle:Admin:_demote_action.html.twig'),
                )
            ))   
        ;
    }
    
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('email')   
            ->add('listing')
        ;
    }
}