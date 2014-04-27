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
            ->orderBy('p.lastName')
            ->addOrderBy('p.firstName');

        if(!is_null($filter))
        {
            $lowerFilter = strtolower($filter);
            if(2 === count($parts = explode(" ", $lowerFilter)))// if filter has a space look for matching full names
            {
                $qb->where('LOWER(p.firstName) LIKE :filter1')
                    ->andWhere('LOWER(p.lastName) LIKE :filter2')
                    ->setParameter('filter1','%'.$parts[0].'%')
                    ->setParameter('filter2','%'.$parts[1].'%');
            }
            else
            {
                $qb->where('n.phoneNumber LIKE :filter')
                    ->orwhere('LOWER(p.firstName) LIKE :filter')
                    ->orWhere('LOWER(p.lastName) LIKE :filter')
                    ->setParameter('filter','%'.$lowerFilter.'%');
            }
        }
        $query = $qb->getQuery();

        return $query->getArrayResult();
    }

    /**
     * Get numbers for csv export
     *
     * @return array
     */
    public function getExport()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb
            ->select('p.firstName, p.lastName, n.phoneNumber')
            ->from('Phonebook\Entity\PhoneNumber','n')
            ->join('Phonebook\Entity\Person','p','WITH','n.person = p.id')
            ->orderBy('p.lastName')
            ->addOrderBy('p.firstName');

        $query = $qb->getQuery();

        return $query->getArrayResult();
    }
} 