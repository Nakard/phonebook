<?php
/**
 * Phonebook_Form_ExistingPhonenumber.php
 * 
 * Data utworzenia: 25.04.2014
 * Czas utworzenia: 13:03
 *
 * @author Arkadiusz Moskwa <arkadiusz.moskwa@novamedia.pl>
 */

namespace Phonebook\Form;

use Phonebook\Exceptions\UniquePersonPhoneNumberException;
use Phonebook\Repository\PhoneNumberRepository;

/**
 * Class Phonebook_Form_ExistingPhonenumber
 * @package Phonebook\Form
 */
class Phonebook_Form_ExistingPhonenumber extends \Zend_Form{

    /**
     * Populates select options after initialization
     *
     * @param array $persons
     * @param mixed $options
     */
    public function __construct(array $persons, $options = null)
    {
        parent::__construct($options);
        /**
         * @var \Zend_Form_Element_Select $select
         */
        $select = $this->getElement('person');
        $select->addMultiOptions($persons);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setMethod('POST');

        $this->setAttribs(array(
            'class' =>  'form-inline',
            'role'  =>  'form'
        ));

        $select = new \Zend_Form_Element_Select('person', array(
            'label'     =>  'Person',
            'required'  =>  true,
            'class'     =>  'form-control'
        ));
        $this->addElement($select);

        $this->addElement('text','phoneNumber',array(
            'placeholder'   =>  'Phone number',
            'label'         =>  'Phone number',
            'class'         =>  'form-control',
            'required'      =>  true,
            'filters'       =>  array('StringTrim', 'StripTags'),
            'validators'    =>  array(
                array('notEmpty'),
                'Digits'
            )
        ));
        $this->addElement('submit', 'submit', array(
            'ignore'    =>  true,
            'label'     =>  'Submit',
            'class'     =>  'btn btn-primary'
        ));
        $this->addElement('hash','csrf', array(
            'ignore'    =>  true,
        ));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'fieldset', 'class' => 'fieldset-bordered')),
            'Form',
        ));
        $this->setElementDecorators(array(
            'ViewHelper',
            'Errors',
            array('Label', array('class' => 'sr-only')),
            array(array('row' => 'HtmlTag'), array('tag' => 'div', 'class' => 'form-group')),
            array('ViewHelper', array('class' => 'form-control')),
        ));
    }

    /**
     * Translates form errors array with more human readable
     *
     * @param   array   $messages
     * @return  array
     */
    protected function errorKeysTranslate(array $messages)
    {
        $translatedMessages = array();
        $translation = array(
            'phoneNumber'   =>  'Phone number',
            'csrf'          =>  'CSRF Token'
        );
        foreach($messages as $formKey => $formErrors)
        {
            $translatedMessages[$translation[$formKey]] = $formErrors;
        }
        return $translatedMessages;
    }

    /**
     * @inheritdoc
     */
    public function getMessages($name = null, $suppressArrayNotation = false)
    {
        $messages = parent::getMessages($name, $suppressArrayNotation);
        return $this->errorKeysTranslate($messages);
    }
} 