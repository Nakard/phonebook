<?php
/**
 * Select.php
 *
 * Creation date: 2014-04-26
 * Creation time: 13:35
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\Form\Elements;

/**
 * Class PersonSelect
 * Select for persons to use the bootstrap layout
 * @package Phonebook\Form\Elements
 */
final class PersonSelect extends \Zend_Form_Element_Select{

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        $spec = 'person';
        $options = array(
            'label'     =>  'Person',
            'required'  =>  true,
            'class'     =>  'form-control'
        );
        parent::__construct($spec,$options);
    }

} 