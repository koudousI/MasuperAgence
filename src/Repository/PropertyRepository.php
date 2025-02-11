<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\QueryBuilder;
use App\Entity\PropertySearch;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;




/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }


    /**
     * recup un tab de property non vendus
     *
     * @return property[]
     */
    public function findAllVisible(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.sold = false')
            ->getQuery()
            ->getResult()
        ;
    }



    /**
     * recup un tab de property non vendus
     *
     * @return property[]
     */
    public function findLatest(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.sold = false')
            ->setMaxResults(4)
            ->getQuery()
            ->getResult()
        ;
    }



    /**
     * renvoie un QueryBuilder
     *
     * @return Query
     */
    private function findVisibleQuery(PropertySearch $search) 
    {
        $query = $this->createQueryBuilder('p')
        ->andWhere('p.sold = false')
        ;

        if ($search->getMaxPrice()) {
            $query = $query
                ->andWhere('p.price <= :maxprice')
                ->setParameter('maxprice', $search->getMaxPrice());
        }

        if ($search->getMinSurface()) {
            $query = $query
                ->andWhere('p.surface >= :minsurface')
                ->setParameter('minsurface', $search->getMinSurface());
        }

        if ($search->getOptions()->count() > 0) {
            $k = 0;
            foreach($search->getOptions() as $option) {
                $k++;
                $query = $query
                    ->andWhere(":option$k MEMBER OF p.options")
                    ->setParameter("option$k", $option);
            }
        }

        return $query->getQuery();
    } 





    

    /**
     * @return Query
     */
    public function findAllVisibleQuery(): Query
    {
        return $this->createQueryBuilder('p')
                        ->where('p.sold = false')
                        ->getQuery();
    }



    // /**
    //  * @return Property[] Returns an array of Property objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Property
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
