<?php

namespace WebPay\ErrorResponse;

use WebPay\Data\ErrorData;
use WebPay\ApiException  as RootException;

class CardException extends RootException
{
    /** var integer */
    private $status;
    /** var ErrorData */
    private $data;

    public function __construct($status, array $data)
    {
        $data = new ErrorData($data);
        parent::__construct(sprintf('%s: %s', 'CardException', $data->error->message));
        $this->status = $status;
        $this->data = $data;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getData()
    {
        return $this->data;
    }
}
