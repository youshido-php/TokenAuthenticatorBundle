<?php
/**
 * Date: 22.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\TokenAuthenticationBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Youshido\TokenAuthenticationBundle\Entity\AccessToken;
use Youshido\TokenAuthenticationBundle\Service\Exception\NotValidTokenException;
use Youshido\TokenAuthenticationBundle\Service\Helper\AccessTokenHelper;

class TokenAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{

    /** @var AccessTokenHelper */
    private $tokenHelper;

    /** @var  string */
    private $tokenRequestHeader;

    /** @var array */
    private $errorCodes = [];

    public function __construct(AccessTokenHelper $tokenHelper, $tokenRequestHeader, array $errorCodes = [])
    {
        $this->tokenHelper        = $tokenHelper;
        $this->tokenRequestHeader = $tokenRequestHeader;
        $this->errorCodes         = $errorCodes;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        if (!$userProvider instanceof TokenUserProvider) {
            throw new \InvalidArgumentException(
                sprintf(
                    'The user provider must be an instance of TokenUserProvider (%s was given).',
                    get_class($userProvider)
                )
            );
        }

        $tokenString = $token->getCredentials();
        $user        = $this->validateTokenAndGetUser($userProvider, $tokenString);

        return new PreAuthenticatedToken($user, $tokenString, $providerKey, $user->getRoles());
    }

    public function validateTokenAndGetUser(TokenUserProvider $userProvider, $tokenString)
    {
        $token = $userProvider->findTokenByApiKey($tokenString);

        $errorCode = array_key_exists('invalid_token', $this->errorCodes) ? $this->errorCodes['invalid_token'] : 401;

        if (!$token) {
            throw new NotValidTokenException(sprintf('API Key "%s" does not exist.', $tokenString), $errorCode);
        }

        if ($token->getStatus() != AccessToken::STATUS_VALID) {
            throw new NotValidTokenException('Access denied for this token.', $errorCode);
        }

        if (!$this->tokenHelper->checkExpires($token)) {
            $this->tokenHelper->expireToken($token);

            throw new NotValidTokenException('Token expired. Please login again.', $errorCode);
        }

        $user = $userProvider->loadUserByToken($token);

        if (!$user) {
            throw new NotValidTokenException('User of this token not exist', $errorCode);
        }

        return $user;
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function createToken(Request $request, $providerKey)
    {
        $tokenString = $request->headers->get($this->tokenRequestHeader);

        if ($tokenString) {
            return new PreAuthenticatedToken('anon.', $tokenString, $providerKey);
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
