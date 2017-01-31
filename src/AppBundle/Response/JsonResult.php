<?php

namespace AppBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class JsonResult extends JsonResponse
{
    const SUCCESS = 0;
    const INFO = 30;
    const WARNING = 35;
    const DANGER = 40;
    
    protected $message;
    protected $errorcode;
    protected $contents;
    
    public function __toString()
    {
        return $this->make();
    }
    
    public function __construct($message = '', $errorcode = self::SUCCESS, $contents = array())
    {
        $this->message = $message;
        $this->errorcode = $errorcode;
        $this->contents = $contents;
        return $this->make();
    }
    
    public static function create($message = '', $errorcode = self::SUCCESS, $contents = array())
    {
        return new static($message, $errorcode, $contents);
    }
    
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }
    
    public function setErrorcode($errorcode)
    {
        $this->errorcode = $errorcode;
        return $this;
    }
    
    public function setContents(array $contents)
    {
        $this->contents = $contents;
        return $this;
    }
    
    public function getMessage()
    {
        return $this->message;
    }
    
    public function getErrorcode()
    {
        return $this->errorcode;
    }
    
    public function getContents()
    {
        return $this->contents;
    }
    
    public function make()
    {
        if (empty($this->contents))
            return new JsonResponse(array('message' => $this->message, 'errorcode' => $this->errorcode));
        return new JsonResponse(array('message' => $this->message, 'errorcode' => $this->errorcode, key($this->contents) => $this->contents[key($this->contents)]));
    }
}