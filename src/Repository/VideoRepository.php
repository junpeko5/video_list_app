<?php

namespace App\Repository;

use App\Entity\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Video::class);
        $this->paginator = $paginator;
    }

    /**
     * @param array $value
     * @param int $page
     * @param string|null $sort_method
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function findByChildIds(array $value, int $page, ?string $sort_method)
    {
        $sort_method = $sort_method != 'rating' ? $sort_method : 'ASC';
        $dbquery = $this->createQueryBuilder('v')
            ->andWhere('v.category IN (:val)')
            ->setParameter('val', $value)
            ->orderBy('v.title', $sort_method)
            ->getQuery();
        $pagination = $this->paginator->paginate($dbquery, $page, 5);
        return $pagination;
    }

    public function findByTitle(string $query, int $page, ?stirng $sort_method)
    {
        $sort_method = $sort_method != 'rating' ? $sort_method : 'ASC';
        $querybuilder = $this->createQueryBuilder('v');
        $searchTerms = $this->prepareQuery($query);
        foreach ($searchTerms as $key => $term)
        {
            $querybuilder
                ->orWhere('v.title LIKE :t_'.$key)
                ->setParameter('t_'.$key, '%'.trim($term).'%');
        }
        $dbquery = $querybuilder
            ->orderBy('v.title', $sort_method)
            ->getQuery();
        return $this->paginator->paginate($dbquery, $page, 5);
    }

    private function prepareQuery(string $query): array
    {
        return explode(' ', $query);
    }
}
