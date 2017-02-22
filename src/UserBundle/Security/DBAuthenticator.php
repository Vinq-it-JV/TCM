<?php
namespace UserBundle\Security;

use Symfony\Component\Security\Http\Authentication\SimpleFormAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;

class DBAuthenticator implements SimpleFormAuthenticatorInterface
{
    private $encoder;
    private $logins;

    public function __construct(UserPasswordEncoderInterface $encoder, $logins)
    {
        $this->encoder = $encoder;
        $this->logins = $logins;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        try
        {
            $user = $userProvider->loadUserByUsername($token->getUsername());
        }
        catch (UsernameNotFoundException $e)
        {
            throw new AuthenticationException('Invalid username or password');
        }

        $logins = $user->getLogins();
        if (!$logins)
            throw new AuthenticationException('User is locked out');

        $password = $token->getCredentials();
        if(trim($password) == '' || is_null($password))
            throw new AuthenticationException('Invalid username or password');

        $passwordValid = $this->encoder->isPasswordValid($user, $password);

        if ($passwordValid)
        {
            $user->setLogins($this->logins)->save();

            if (!$user->getRoles())
                throw new AuthenticationException('User has no role(s)');

            if (!$user->getIsEnabled())
                throw new AuthenticationException('User is not activated');

            return new UsernamePasswordToken(
                $user,
                $user->getPassword(),
                $providerKey,
                $user->getRolesArray()
            );
        }

        if ($logins)
        {   $logins--;
            $user->setLogins($logins)->save();
        }

        throw new AuthenticationException('Invalid username or password');
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof UsernamePasswordToken
        && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $username, $password, $providerKey)
    {
        return new UsernamePasswordToken($username, $password, $providerKey);
    }
}

?>