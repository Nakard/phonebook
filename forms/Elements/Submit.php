<?php
/**
 * Submit.php
 *
 * Creation date: 2014-04-26
 * Creation time: 13:50
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\Form\Elements;

/**
 * Class Submit
 * Submit button for using with bootstrap
 * @package Phonebook\Form\Elements
 */
final class Submit extends \Zend_Form_Element_Submit {

    /**
     * @param array|string|\Zend_Config $label Label for submit
     */
    public function __construct($label)
    {
        $options = array(
            'ignore'    =>  true,
            'label'     =>  $label,
            'class'     =>  'btn btn-primary'
        );
        parent::__construct('submit',$options);
    }

} 