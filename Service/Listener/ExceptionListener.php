<?php

namespace Youshido\TokenAuthenticationBundle\Service\Listener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Date: 2/3/16
 *
 * @author Portey Vasil <portey@gmail.com>
 */
class ExceptionListener
{
    /** @var  string */
    private $tokenRequestHeader;

    public function __construct($tokenRequestHeader)
    {
        $this->tokenRequestHeader = $tokenRequestHeader;
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $response = new JsonResponse([
            'errors' => [
                [
                    'message' =>  $event->getThrowable()->getMessage(),
                    'code'    =>  $event->getThrowable()->getCode()
                ]
            ]
        ], 200, [
            'Access-Control-Allow-Origin'  => '*',
            'Access-Control-Allow-Headers' => $this->tokenRequestHeader
        ]);

        $event->setResponse($response);
    }

}
