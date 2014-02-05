<?php
namespace CCETC\DirectoryUserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Sonata\AdminBundle\Datagrid\ORM\ProxyQuery;

class UserAdminController extends Controller
{

  public function promoteAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();

    $userManager = $this->container->get('fos_user.user_manager');
    $user = $userManager->findUserBy(array("id" => $id));
    $user->addRole('ROLE_ADMIN');

    $em->flush();
    $em->clear();

    $this->getRequest()->getSession()->setFlash('sonata_flash_success', $user->getEmail() . ' has been given admin access');

    return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
  }

  public function demoteAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();

    $userManager = $this->container->get('fos_user.user_manager');
    $user = $userManager->findUserBy(array("id" => $id));
    $user->removeRole('ROLE_ADMIN');

    $em->flush();
    $em->clear();

    $this->getRequest()->getSession()->setFlash('sonata_flash_success', $user->getEmail() . "'s admin access has been removed");

    return new RedirectResponse($this->admin->generateUrl('list', $this->admin->getFilterParameters()));
  }

  public function createAction()
  {
    return $this->render('CCETCDirectoryUserBundle:Admin:create_message.html.twig', array(
        'action' => 'create',
        'admin' => $this->admin,
        'registrationSetting' => $this->container->getParameter('ccetc_directory.registration_setting')
    ));
  }

}