<?php
/**
 * UniquePersonPhoneNumberException.php
 * 
 * Data utworzenia: 25.04.2014
 * Czas utworzenia: 14:37
 *
 * @author Arkadiusz Moskwa <arkadiusz.moskwa@novamedia.pl>
 */

namespace Phonebook\Exceptions;

/**
 * Class UniquePersonPhoneNumberException
 * @package Phonebook\Exceptions
 */
class UniquePersonPhoneNumberException extends \PDOException {

    /**
     * @inheritdoc
     */
    public function __construct(
        $message = "This person already has this number !",
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($message,$code,$previous);
    }
} 