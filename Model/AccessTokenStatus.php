<?php

namespace Youshido\TokenAuthenticationBundle\Model;


class AccessTokenStatus
{
    const STATUS_VALID   = 0;
    const STATUS_EXPIRED = 1;
    const STATUS_DENIED  = 2;
}