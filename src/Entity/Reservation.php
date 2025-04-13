<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation", indexes={@ORM\Index(name="fkhotel", columns={"idhotel"}), @ORM\Index(name="fkspace", columns={"idspace"}), @ORM\Index(name="fktransport", columns={"idtransport"}), @ORM\Index(name="fkuser", columns={"iduser"})})
 * @ORM\Entity
 */
class Reservation
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
     * @var int
     *
     * @ORM\Column(name="nbrnuit", type="integer", nullable=false)
     */
    private $nbrnuit;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrheure", type="integer", nullable=false)
     */
    private $nbrheure;

    /**
     * @var string|null
     *
     * @ORM\Column(name="typeservice", type="string", length=100, nullable=true)
     */
    private $typeservice;

    /**
     * @var string|null
     *
     * @ORM\Column(name="statut", type="string", length=100, nullable=true)
     */
    private $statut;

    /**
     * @var \Hotel
     *
     * @ORM\ManyToOne(targetEntity="Hotel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idhotel", referencedColumnName="id")
     * })
     */
    private $idhotel;

    /**
     * @var \CoworkingSpace
     *
     * @ORM\ManyToOne(targetEntity="CoworkingSpace")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idspace", referencedColumnName="id")
     * })
     */
    private $idspace;

    /**
     * @var \TransportMeans
     *
     * @ORM\ManyToOne(targetEntity="TransportMeans")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idtransport", referencedColumnName="id")
     * })
     */
    private $idtransport;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iduser", referencedColumnName="id")
     * })
     */
    private $iduser;


}
