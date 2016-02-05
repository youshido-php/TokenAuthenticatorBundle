<?php

namespace Youshido\TokenAuthenticationBundle\Service\Listener;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Date: 2/3/16
 *
 * @author Portey Vasil <portey@gmail.com>
 */
class ExceptionListener
{

    use ContainerAwareTrait;

    public function onKernelException(GetResponseForExceptionEvent $event)
    {

        $field = $this->container->getParameter('token_authentication.token_field');

        $response = new JsonResponse([
            'errors' => [
                [
                    'message' => $event->getException()->getMessage(),
                    'code'    => $event->getException()->getCode()
                ]
            ]
        ], 200, [
            'Access-Control-Allow-Origin'  => '*',
            'Access-Control-Allow-Headers' => $field
        ]);

        $event->setResponse($response);
    }

}
