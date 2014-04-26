<?php
/**
 * Phonebook_Form_EditNumber.php
 *
 * Creation date: 2014-04-26
 * Creation time: 21:47
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\Form;

use Phonebook\Entity\PhoneNumber;
use Phonebook\Form\Elements\Submit;
use Phonebook\Form\Elements\Hash;
use Phonebook\Form\Elements\PhoneNumberText;
/**
 * Class Phonebook_Form_EditPhoneNumber
 * @package Phonebook\Form
 */
class Phonebook_Form_EditPhoneNumber extends Phonebook_Form_Abstract{

    public function init()
    {
        $this->setMethod('POST');

        $this->setAttrib('id','editForm');

        $this
            ->addElement(new PhoneNumberText())
            ->addElement(new Submit('Save Changes'))
            ->addElement(new Hash());
        $this->setMainDecorators();
    }
    /**
     * Sets the phonenumber in the inputs
     *
     * @param Phonenumber $number
     */
    public function setPhoneNumber(PhoneNumber $number)
    {
        $phoneNumber = $number->getPhoneNumber();

        $this->getElement('phoneNumber')->setValue($phoneNumber);
    }

    /**
     * @inheritdoc
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
} 