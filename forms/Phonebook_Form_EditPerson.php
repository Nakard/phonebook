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
            'placeholder'   =>  'First name',
            'label'         =>  'First name',
            'class'         =>  'form-control',
            'required'      =>  true,
            'filters'       =>  array('StringTrim', 'StripTags'),
            'validators'    =>  array(
                array('validator' => 'StringLength', 'options' => array(1,50)),
                array('notEmpty'),
                'Alpha'
            )
        ));

        $lastName = new \Zend_Form_Element_Text('lastName', array(
            'placeholder'   =>  'Last name',
            'label'         =>  'Last name',
            'class'         =>  'form-control',
            'required'      =>  true,
            'filters'       =>  array('StringTrim', 'StripTags'),
            'validators'    =>  array(
                array('validator' => 'StringLength', 'options' => array(1,50)),
                array('validator' => 'notEmpty', 'messages' => array(
                    'isEmpty'
                )),
                'Alpha'
            )
        ));

        $saveChanges = new \Zend_Form_Element_Submit('submit', array(
            'ignore'    =>  true,
            'label'     =>  'Submit',
            'class'     =>  'btn btn-primary'
        ));


        $hash = new \Zend_Form_Element_Hash('csrf', array(
            'ignore'    =>  true,
        ));

        $this
            ->addElement($firstName)
            ->addElement($lastName)
            ->addElement($saveChanges)
            ->addElement($hash);
    }

} 