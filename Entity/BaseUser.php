<?php
namespace CCETC\DirectoryUserBundle\Entity;

use FOS\UserBundle\Entity\User;

abstract class BaseUser extends User
{
 	public function setEmail($email)
    {
        $this->setUsername($email);

        return parent::setEmail($email);
    }

    public function setEmailCanonical($emailCanonical)
    {
        $this->setUsernameCanonical($emailCanonical);

        return parent::setEmailCanonical($emailCanonical);
    }
}
