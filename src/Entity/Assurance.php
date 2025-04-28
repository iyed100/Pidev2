<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Assurance
 *
 * @ORM\Table(name="assurance", indexes={@ORM\Index(name="id_reservation", columns={"id_reservation"})})
 * @ORM\Entity
 */
class Assurance
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $montant;

    /**
     * @var string|null
     *
     * @ORM\Column(name="conditions", type="text", length=65535, nullable=true)
     */
    private $conditions;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_souscription", type="date", nullable=false)
     */
    private $dateSouscription;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_expiration", type="date", nullable=false)
     */
    private $dateExpiration;

    /**
     * @var string|null
     *
     * @ORM\Column(name="statut", type="string", length=0, nullable=true, options={"default"="Actif"})
     */
    private $statut = 'Actif';

    /**
     * @var \Reservation
     *
     * @ORM\ManyToOne(targetEntity="Reservation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_reservation", referencedColumnName="id")
     * })
     */
    private $idReservation;


}
