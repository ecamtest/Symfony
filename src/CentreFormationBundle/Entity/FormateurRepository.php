<?php

namespace CentreFormationBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * FormateurRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FormateurRepository extends EntityRepository
{
	public function getRemunerationList($prix)
	{
		$query = $this->_em->createQuery('SELECT formateur.nom, formateur.prenom, sum(formation.duree)*:prix as remuneration FROM CentreFormationBundle:Formation formation INNER JOIN CentreFormationBundle:Formateur formateur WITH formation.formateur = formateur.id GROUP BY formation.formateur')
							->setParameter('prix', $prix);

		return $query->getResult();
	}
}
