<?php
/**
 * Date: 22.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\TokenAuthenticationBundle\Service\Exception;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class NotValidTokenException extends \Exception implements HttpExceptionInterface
{
    /**
     * Returns the status code.
     *
     * @return int An HTTP response status code
     */
    public function getStatusCode()
    {
        return 200;
    }

    /**
     * Returns response headers.
     *
     * @return array Response headers
     */
    public function getHeaders()
    {
        return [];
    }
}