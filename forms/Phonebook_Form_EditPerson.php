<?php
/**
 * Phonebook_Form_EditPerson.php
 *
 * Creation date: 2014-04-26
 * Creation time: 12:54
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\Form;


class Phonebook_Form_EditPerson extends \Zend_Form {

    public function init()
    {
        $this->setMethod('POST');

        $firstName = new \Zend_Form_Element_Text('firstName', array(

        ));
    }

} 