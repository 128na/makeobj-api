<?php

namespace App\Services\Makeobj;

use _128Na\Simutrans\Makeobj\MakeobjResponse;

class MakeobjFailedException extends \Exception
{
    private MakeobjResponse $response;

    public function __construct(MakeobjResponse $response)
    {
        $this->response = $response;
        parent::__construct($response->__toString());
    }

    public function getResponse(): MakeobjResponse
    {
        return $this->response;
    }
}
