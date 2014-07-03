<?php
/**
 * PersonRepository.php
 * 
 * Data utworzenia: 25.04.2014
 * Czas utworzenia: 10:15
 *
 * @author Arkadiusz Moskwa <arkadiusz.moskwa@novamedia.pl>
 */

namespace Phonebook\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Phonebook\Entity\Person;
use Phonebook\Entity\PhoneNumber;
use Phonebook\Exceptions\UniquePersonException;

/**
 * Class PersonRepository
 * @package Phonebook\Repository
 */
class PersonRepository extends EntityRepository{

    private $successfulInsertions = 0;
    /**
     * Inserts new person with phonenumber
     *
     * @param string        $firstName
     * @param string        $lastName
     * @param PhoneNumber   $phoneNumber
     */
    public function insertNewPerson($firstName, $lastName, PhoneNumber $phoneNumber)
    {
        $em = $this->getEntityManager();
        $person = new Person();
        $person->setFirstName($firstName);
        $person->setLastName($lastName);
        $person->addPhoneNumber($phoneNumber);
        $phoneNumber->setPerson($person);
        $em->persist($person);
        $em->flush();
    }

    /**
     * Checks if there isn't already a person with set first name and last name
     *
     * @param   string  $firstName
     * @param   string  $lastName
     */
    public function checkPersonUniqueness($firstName, $lastName)
    {
        $result = $this->findOneBy(array(
            'firstName' =>  $firstName,
            'lastName'  =>  $lastName,
        ));
        if($result instanceof Person)
            throw new UniquePersonException();
    }

    /**
     * Finds all persons to populate select
     *
     * @return array
     */
    public function findAllForSelect()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select(
                'p.id as key',
                $qb->expr()->concat(
                    $qb->expr()->concat(
                        'p.lastName', $qb->expr()->literal(' ')
                    ),'p.firstName'
                ).' as value'
            )
            ->from('Phonebook\Entity\Person','p')
            ->orderBy('p.lastName');
        return $qb->getQuery()->getArrayResult();
    }

    /**
     * Finds all person IDs
     *
     * @return array
     */
    public function findAllIds()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('p.id')
            ->from('\Phonebook\Entity\Person','p');
        return $qb->getQuery()->getArrayResult();
    }

    /**
     * Adds phonenumber to person
     *
     * @param PhoneNumber   $number
     * @param int           $personId
     */
    public function addNumberToPerson(PhoneNumber $number, $personId)
    {
        /**
         * @var \Phonebook\Entity\Person $person
         */
        $person = $this->find($personId);
        $this->addPersistableNumber($number,$person);
        $this->getEntityManager()->persist($person);
        $this->getEntityManager()->flush();
    }

    /**
     * Persists number to the person
     *
     * @param PhoneNumber   $number
     * @param Person        $person
     */
    private function addPersistableNumber(PhoneNumber &$number, Person &$person)
    {
        $person->addPhoneNumber($number);
        $number->setPerson($person);
    }

    /**
     * Merges data from import with existing records
     *
     * @param array $parsedImportArray
     */
    public function mergeImport(array &$parsedImportArray)
    {
        $this->successfulInsertions = 0;
        foreach($parsedImportArray as $personFullname => &$numbers)
        {

            try
            {
                $nameParts = explode(" ",$personFullname);
                $personFirstName = $nameParts[0];
                $personLastName = $nameParts[1];
                $person = $this->findOneBy(array(
                    'firstName' =>  $personFirstName,
                    'lastName'  =>  $personLastName
                ));
                if($person instanceof Person)
                {
                    $this->mergeNumbersForExistingPerson($person, $numbers);
                }
                else
                {
                    $this->addNewPersonNumbers($personFirstName, $personLastName, $numbers);
                }
            }
            catch(\Exception $e)
            {
                continue;
            }
        }
        $this->getEntityManager()->flush();
        return $this->successfulInsertions;
    }

    /**
     * Merges number with person existing in the database
     *
     * @param   Person    $person
     * @param   array     $numbers
     */
    private function mergeNumbersForExistingPerson(Person &$person, array &$numbers)
    {
        $this->addPersistableNumbers($person, $numbers);
        $this->getEntityManager()->persist($person);
    }

    /**
     * Adds new person with her/his numbers
     *
     * @param   string    $firstName
     * @param   string    $lastName
     * @param   array     $numbers
     */
    private function addNewPersonNumbers(&$firstName, &$lastName, array &$numbers)
    {
        $person = new Person();
        $person->setFirstName($firstName);
        $person->setLastName($lastName);
        $this->addPersistableNumbers($person, $numbers);
        $this->getEntityManager()->persist($person);
    }

    /**
     * Adds number collection to a person
     *
     * @param Person    $person
     * @param array     $numbers
     */
    private function addPersistableNumbers(Person &$person, array $numbers)
    {
        foreach($numbers as $number)
        {
            $phoneNumber = new PhoneNumber();
            $phoneNumber->setPhoneNumber($number);
            $this->addPersistableNumber($phoneNumber,$person);
            $this->successfulInsertions++;
        }
    }
}