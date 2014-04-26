<?php
/**
 * ResponseHelper.php
 *
 * Creation date: 2014-04-26
 * Creation time: 23:41
 *
 * @author Arkadiusz Moskwa <a.moskwa@gmail.com>
 */

namespace Phonebook\Resolver;

use Doctrine\ORM\Tools\Export\ExportException;
use \Phonebook\Entity\PhoneNumber;
use \Phonebook\Entity\Person;
/**
 * Class EntityResolver
 * @package Phonebook\Resolver
 */
class EntityResolver {

    private $translation = array(
        'Person'        =>  array(
            'title'     =>  'Edit person credentials',
            'message'   =>  'Credentials changed successfully',
            'validate'  =>  'checkPersonUniqueness'
        ),
        'PhoneNumber'   =>  array(
            'title'     =>  'Edit phone number',
            'message'   =>  'Phone number changed successfully',
            'validate'  =>  null
        ),
    );

    /**
     * Gets messages for entity, used in controllers for json response
     *
     * @param   string  $entityName
     * @param   string  $type
     * @return  string
     */
    public function getEntityMessage($entityName, $type)
    {
        return $this->translation[$entityName][$type];
    }

    /**
     * Transforms edit route parameter to match class name
     *
     * @param string $type
     */
    public function typeTranslate(&$type)
    {
        if('number' === $type)
            $type = 'phoneNumber';

        return $type;
    }

    /**
     * Calls adequate methods depending on class, uses values from POST
     *
     * @param Person|Phonenumber    $entity
     * @param array                 $values
     * @param callable              $validate
     */
    public function callEntitySetMethods(&$entity, array &$values, callable $validate = null)
    {
        switch(get_class($entity))
        {
            case "Phonebook\\Entity\\Person":
                $entity->setCredentials($values['firstName'],$values['lastName']);
                call_user_func($validate, $values['firstName'], $values['lastName']);
                break;
            case "Phonebook\\Entity\\PhoneNumber":
                $entity->setPhoneNumber($values['phoneNumber']);
                break;
        }
        return $entity;
    }

} 