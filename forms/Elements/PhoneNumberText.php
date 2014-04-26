<?php
/**
 * PhoneNumberText.php
 *
 * Creation date: 2014-04-26
 * Creation time: 13:47
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\Form\Elements;

/**
 * Class PhoneNumberText
 * Text input for phone using boostrap
 * @package Phonebook\Form\Elements
 */
final class PhoneNumberText extends \Zend_Form_Element_Text{

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $spec = 'phoneNumber';
        $options = array(
            'placeholder'   =>  'Phone number',
            'label'         =>  'Phone number',
            'class'         =>  'form-control',
            'required'      =>  true,
            'filters'       =>  array('StringTrim', 'StripTags'),
            'validators'    =>  array(
                array('validator' => 'StringLength', 'options' => array(9,9)),
                'notEmpty','Digits'
            )
        );
        parent::__construct($spec,$options);
    }

} 