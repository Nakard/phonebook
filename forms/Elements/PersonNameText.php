<?php
/**
 * PersonNameText.php
 *
 * Creation date: 2014-04-26
 * Creation time: 14:23
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\Form\Elements;

/**
 * Class PersonNameText
 * Person name text field using bootstrap
 * @package Phonebook\Form\Elements
 */
final class PersonNameText extends \Zend_Form_Element_Text{

    /**
     * @inheritdoc
     */
    public function __construct($spec, $label)
    {
        $options = array(
            'placeholder'   =>  'First name',
            'label'         =>  'First name',
            'class'         =>  'form-control',
            'required'      =>  true,
            'filters'       =>  array('StringTrim', 'StripTags'),
            'validators'    =>  array(
                array('validator' => 'StringLength', 'options' => array(1,50)),
                'notEmpty','Alpha'
            )
        );
        parent::__construct($spec, $options);
    }

} 