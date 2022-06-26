<?php

namespace App\Repository;

use App\Entity\Session;
use App\Entity\FormModule;
use Doctrine\ORM\ORMException;
use App\Repository\FormModuleRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Session $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Session $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findNonInscrits($session_id)
    {
        $em = $this->getEntityManager();
        $sub = $em->createQueryBuilder();

        $qb = $sub;
        $qb->select('s')
            ->from('App\Entity\Stagiaire', 's')
            ->leftJoin('s.sessions', 'se')
            ->where('se.id = :id');
        
        $sub = $em->createQueryBuilder();
        $sub->select('st')
            ->from('App\Entity\Stagiaire', 'st')
            ->where($sub->expr()->notIn('st.id', $qb->getDQL()))
            ->setParameter('id', $session_id)
            ->orderBy('st.nom');
        
        $query = $sub->getQuery();
        return $query->getResult();
    }

    public function findNonProgrammes($session_id) {

        // $em = $this->getEntityManager();
        // $sub = $em->createQueryBuilder();

        // $qb = $sub;
        // $qb->select('p')
        //     ->from('App\Entity\Programme', 'p')
        //     ->leftJoin('p.session', 'se')
        //     ->where('se.id = :id');
        
        // $sub = $em->createQueryBuilder();
        // $sub->select('pr')
        //     ->from('App\Entity\Programme', 'pr')
        //     ->where($sub->expr()->notIn('pr.id', $qb->getDQL()))
        //     ->setParameter('id', $session_id)
        //     ->orderBy('pr.session');
        
        // $query = $sub->getQuery();

        // $tab = [];
        // foreach($query->getResult() as $p) {
        //     $tab[] = $p->getFormModule();
        // }

        // return $tab;

        $session = $this->find($session_id);
        $allModules = $this->getEntityManager()->getRepository(FormModule::class)->findAll();
        $tabProg = [];
        $tab = [];

        foreach($session->getProgrammes() as $programme) {
            $tabProg[] = $programme->getFormModule();
        }

        $result = array_diff($allModules, $tabProg);

        return $result;
    }

    public function findPastSessions() {

        $now = new \DateTime();
        return $this->createQueryBuilder('s')
            ->andWhere('s.dateFin < :val')
            ->setParameter('val', $now)
            ->orderBy('s.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findProgressSessions() {

        $now = new \DateTime();
        return $this->createQueryBuilder('s')
            ->andWhere('s.dateDebut < :val')
            ->andWhere('s.dateFin > :val')
            ->setParameter('val', $now)
            ->orderBy('s.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findFutureSessions() {

        $now = new \DateTime();
        return $this->createQueryBuilder('s')
            ->andWhere('s.dateDebut > :val')
            ->setParameter('val', $now)
            ->orderBy('s.dateDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Session[] Returns an array of Session objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Session
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
