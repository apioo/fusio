<?php
namespace App\Action;

use PSX\Framework\Exception\ConverterInterface;
use PSX\Model\Error;
use PSX\Record\Record;
use Throwable;

class ExceptionConverter implements ConverterInterface
{
    const CONTEXT_SIZE = 4;

    protected $isDebug;
    protected $contextSize;

    public function __construct($isDebug, $contextSize = self::CONTEXT_SIZE)
    {
        $this->isDebug     = $isDebug;
        $this->contextSize = $contextSize;
    }

    public function convert(Throwable $e): Error {
        $data = [
            'error' => [
                'message' => $e->getMessage(),
                'type' => get_class($e),
                'code' => $e->getCode()
            ]
        ];

        if($this->isDebug === true) {
            $data['error']['trace'] = $e->getTraceAsString();
        }

        $err = new Error();

        $err->setMessage($e->getMessage());

        return $err;
    }
}