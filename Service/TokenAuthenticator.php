<?php
/**
 * Date: 22.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\TokenAuthenticationBundle\Service;


use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Youshido\TokenAuthenticationBundle\Entity\AccessToken;
use Youshido\TokenAuthenticationBundle\Service\Exception\NotValidTokenException;

class TokenAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{

    use ContainerAwareTrait;

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof TokenUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of ApiUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $errorCode = $this->container->getParameter('token_authenticator.error_codes')['invalid_token'];
        $apiKey    = $token->getCredentials();
        $token     = $userProvider->findTokenByApiKey($apiKey);

        if (!$token) {
            throw new NotValidTokenException(sprintf('API Key "%s" does not exist.', $apiKey), $errorCode);
        }

        if ($token->getStatus() == AccessToken::STATUS_DENIED) {
            throw new NotValidTokenException('Access denied for this token.', $errorCode);
        }

        if ($this->container->get('api_token_helper')->checkExpires($token)) {
            $em = $this->container->get('doctrine')->getEntityManager();
            $em->remove($token);
            $em->flush();
            throw new NotValidTokenException('Token expired. Please login again.', $errorCode);
        }

        $user = $userProvider->loadUserByToken($token);

        if (!$user) {
            throw new NotValidTokenException('User of this token not exist', $errorCode);
        }

        return new PreAuthenticatedToken($user, $apiKey, $providerKey, $user->getRoles());
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $providerKey)
    {
        $apiKey = $request->headers->get('access_token');
        if (!$apiKey) {
            $apiKey = $request->headers->get('accesstoken');
        }

        if ($apiKey) {
            return new PreAuthenticatedToken('anon.', $apiKey, $providerKey);
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new Response(strtr($exception->getMessageKey(), $exception->getMessageData()), 403);
    }
}
