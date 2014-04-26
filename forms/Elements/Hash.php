<?php
/**
 * Hash.php
 *
 * Creation date: 2014-04-26
 * Creation time: 14:29
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\Form\Elements;

/**
 * Class Hash
 * CSRF hash field
 * @package Phonebook\Form\Elements
 */
final class Hash extends \Zend_Form_Element_Hash{

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $spec = 'csrf';
        $options = array(
            'ignore'    =>  true,
        );
        parent::__construct($spec,$options);
    }

} 