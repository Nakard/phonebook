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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\Query;
use Phonebook\Entity\Person;
use Phonebook\Entity\PhoneNumber;

/**
 * Class PersonRepository
 * @package Phonebook\Repository
 */
class PersonRepository extends EntityRepository{

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
     * @return  bool
     */
    public function checkPersonUniqueness($firstName, $lastName)
    {
        $result = $this->findOneBy(array(
            'firstName' =>  $firstName,
            'lastName'  =>  $lastName,
        ));

        return !$result instanceof Person ? true : false;
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
                        'p.firstName', $qb->expr()->literal(' ')
                    ),'p.lastName'
                ).' as value'
            )
            ->from('Phonebook\Entity\Person','p');
        $query = $qb->getQuery();
        return $query->getResult(Query::HYDRATE_ARRAY);
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
        $person->addPhoneNumber($number);
        $number->setPerson($person);
        $em = $this->getEntityManager();
        $em->persist($person);
        $em->flush();
    }


}