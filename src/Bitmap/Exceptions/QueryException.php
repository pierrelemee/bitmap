<?php

namespace Bitmap\Exceptions;

use Exception;
use PDO;

class QueryException extends Exception
{
    protected $message;
    protected $code;
    protected $sql;

    public function __construct($sql, PDO $connection)
    {
        $this->message = $connection->errorInfo()[2];
        $this->code = $connection->errorInfo()[1];
        $this->sql = $sql;

        parent::__construct(sprintf("Error '%s' on query \n%s", $this->message, $this->sql), $this->code, null);
    }

    public function getLocalMessage()
    {
        return $this->message;
    }

    public function getSQL()
    {
        return $this->sql;
    }
}