<?php
/**
 * MissingCsvHeadersException.php
 *
 * Creation date: 2014-04-27
 * Creation time: 16:37
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\Exceptions;

/**
 * Class MissingCsvHeadersException
 * @package Phonebook\Exceptions
 */
class MissingCsvHeadersException extends \Exception{

    /**
     * @inheritdoc
     */
    public function __construct(
        $message = "Csv File is missing required headers",
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($message,$code,$previous);
    }
} 