<?php

namespace Site\SiteBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    public function getTopCategories()
    {
        return $this->getCategories(0, 'top');
    }

    public function getCategories($parent_id = null, $location = null)
    {
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->addOrderBy('c.sort', 'ASC')
                ->addOrderBy('c.title', 'ASC');

        if (!is_null($parent_id)) {
            $qb->andWhere('c.parent = :parent_id')
                ->setParameter('parent_id', $parent_id);
        }

        if (!is_null($location)) {
            $qb->andWhere('c.location = :location')
                ->setParameter('location', $location);
        }

        return $qb->getQuery()->getResult();
    }

    public function findBySlug($slug = '')
    {
        $qb = $this->createQueryBuilder('c')
                ->select('c')
                ->andWhere('c.slug = :slug')
                ->setParameter('slug', $slug);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getCategoriesTree($parent_id = null, $location = null)
    {
        $categoiries = $this->getCategories($parent_id = null, $location = null);
    }

    protected function getSubCategoires($categories)
    {
        $sub = array();
        foreach ($categories as $category) {
            # code...
        }
    }
}
