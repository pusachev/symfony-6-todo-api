<?php

declare(strict_types=1);

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;

/**
 * @see https://github.com/lexik/LexikJWTAuthenticationBundle/blob/2.x/Resources/doc/6-extending-jwt-authenticator.rst
 */
class CustomAuthenticator extends JWTAuthenticator
{
}
