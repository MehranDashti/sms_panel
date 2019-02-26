<?php
namespace app\exceptions;
use Exception;
/**
 * Class SmsPanelException
 * @package app\exceptions
 * @author Mehran
 */
class SmsPanelException extends Exception
{
    public $statusCode;
    /**
     * Constructor.
     * @param string $message error message
     * @param int $code error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     * @author Mehran
     */
    public function __construct($message = null, $code = 404, \Exception $previous = null)
    {
        $this->statusCode = $code;
        parent::__construct($message, $code, $previous);
    }
}
?>
