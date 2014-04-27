<?php
/**
 * PhoneNumberRepository.php
 * 
 * Data utworzenia: 25.04.2014
 * Czas utworzenia: 14:31
 *
 * @author Arkadiusz Moskwa <arkadiusz.moskwa@novamedia.pl>
 */

namespace Phonebook\Repository;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Parameter;
use Phonebook\Entity\PhoneNumber;
use Phonebook\Exceptions\UniquePersonPhoneNumberException;

/**
 * Class PhoneNumberRepository
 * @package Phonebook\Repository
 */
class PhoneNumberRepository extends EntityRepository{

    /**
     * Fetches numbers with pagination
     *
     * @param   int     $page
     * @param   int     $limit
     * @param   string  $filter
     * @return  array
     */
    public function getNumbersHydrated($filter)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('p.id as pid, p.firstName, p.lastName, n.id as nid, n.phoneNumber')
            ->from('Phonebook\Entity\PhoneNumber','n')
            ->join('Phonebook\Entity\Person','p','WITH','n.person = p.id')
            ->groupBy('n.id')
            ->orderBy('p.lastName');

        if(!is_null($filter))
        {
            $lowerFilter = strtolower($filter);
            $qb
                ->where('n.phoneNumber LIKE :filter')
                //->where('LOWER(p.firstName) LIKE :filter')
                //->andWhere('LOWER(p.lastName) LIKE :filter')
                ->setParameter('filter',$lowerFilter);
        }

        $query = $qb->getQuery();

        return $query->getArrayResult();
    }
} 