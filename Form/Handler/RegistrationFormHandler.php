<?php

namespace CCETC\DirectoryUserBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseHandler;

use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

class RegistrationFormHandler extends BaseHandler
{
    protected $container;

    public function __construct(FormInterface $form, Request $request, UserManagerInterface $userManager, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, $container)
    {
        parent::__construct($form, $request, $userManager, $mailer, $tokenGenerator);

        $this->container = $container;
    }

        
    /**
     * @param boolean $confirmation
     */
    public function process($confirmation = false)
    {
        $user = $this->createUser();
        $this->form->setData($user);

        if ('POST' === $this->request->getMethod()) {
            $this->form->bind($this->request);
            if(!$this->getListingByEmail($user->getEmail())) {
                $this->form->get('email')->addError(new FormError('There is no Listing that matches this E-mail address'));
                return false;                
            }


            if ($this->form->isValid()) {
                $this->onSuccess($user, $confirmation);

                return true;
            }
        }

        return false;
    }

    protected function getListingByEmail($email)
    {
        $listingTypeHelper = $this->container->get('ccetc.directory.helper.listingtypehelper');

        foreach($listingTypeHelper->getAll() as $listingType)
        {
            $listingRepository = $listingType->getRepository();
            $listing = $listingRepository->findOneByPrimaryEmail($email);
            if($listing && $listing->getId()) return $listing;
        }

        return false;
    }

    /**
     * @param boolean $confirmation
     */
    protected function onSuccess(UserInterface $user, $confirmation)
    {
        if ($confirmation) {
            $user->setEnabled(false);
            if (null === $user->getConfirmationToken()) {
                $user->setConfirmationToken($this->tokenGenerator->generateToken());
            }

            $this->mailer->sendConfirmationEmailMessage($user);
        } else {
            $user->setEnabled(true);
        }

        $listing = $this->getListingByEmail($user->getEmail());
        $listingTypeHelper = $this->container->get('ccetc.directory.helper.listingtypehelper');
        $listingType = $listingTypeHelper->findByListing($listing);
        $listingAdmin = $listingType->getAdminClass();
        $listing->setUser($user);
        $user->setListing($listing);

        $this->userManager->updateUser($user);
        $listingAdmin->update($listing);
    }
}
