<?php
/**
 * UniquePersonException.php
 * 
 * Data utworzenia: 25.04.2014
 * Czas utworzenia: 11:24
 *
 * @author Arkadiusz Moskwa <arkadiusz.moskwa@novamedia.pl>
 */

namespace Phonebook\Exceptions;

/**
 * Class UniquePersonException
 * @package Phonebook\Exceptions
 */
class UniquePersonException extends \PDOException{

    /**
     * @inheritdoc
     */
    public function __construct(
        $message = "Person with this credentials already exists",
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($message,$code,$previous);
    }
} 