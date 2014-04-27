<?php
/**
 * Phonebook_Form_FileUpload.php
 *
 * Creation date: 2014-04-27
 * Creation time: 13:14
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\Form;

use Phonebook\Form\Elements\Hash;
use Phonebook\Form\Elements\Submit;

/**
 * Class Phonebook_Form_FileUpload
 * @package Phonebook\Form
 */
class Phonebook_Form_FileUpload extends Phonebook_Form_Abstract{

    public function init()
    {
        $this->setMethod('POST');
        $this->setAttrib('id','fileUpload');
        $this->addElement('file', 'upload_file', array(
            'disableLoadDefaultDecorators' => true,
            'decorators' => array('File'),
            'label' => 'Upload',
            'required' => false,
            'filters' => array(),
            'validators' => array(
                array('Count', false, 1),
                array('Extension', false, 'csv' ),
            ),
        ));
        $this->addElement(new Submit('Upload'));
        $this->addElement(new Hash());
    }
    /**
     * @inheritdoc
     */
    protected function errorKeysTranslate(array $messages)
    {
        $translatedMessages = array();
        $translation = array(
            'csrf'          =>  'CSRF Token'
        );
        foreach($messages as $formKey => $formErrors)
        {
            $translatedMessages[$translation[$formKey]] = $formErrors;
        }
        return $translatedMessages;
    }

} 