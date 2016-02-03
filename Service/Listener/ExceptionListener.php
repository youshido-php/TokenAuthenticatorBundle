<?php

namespace Youshido\TokenAuthenticationBundle\Service\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Date: 2/3/16
 *
 * @author Portey Vasil <portey@gmail.com>
 */
class ExceptionListener
{

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $response = new JsonResponse([
            'errors' => [
                [
                    'message' => $event->getException()->getMessage(),
                    'code'    => $event->getException()->getCode()
                ]
            ]
        ], 200, [
            'Access-Control-Allow-Origin'  => '*',
            'Access-Control-Allow-Headers' => 'apikey'
        ]);

        $event->setResponse($response);
    }

}
