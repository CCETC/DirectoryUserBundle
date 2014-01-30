<?php

namespace CCETC\DirectoryUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CCETCDirectoryUserBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}
