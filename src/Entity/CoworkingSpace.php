<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CoworkingSpace
 *
 * @ORM\Table(name="coworking_space", indexes={@ORM\Index(name="hotel_id", columns={"hotel_id"})})
 * @ORM\Entity
 */
class CoworkingSpace
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
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=false)
     */
    private $adresse;

    /**
     * @var float
     *
     * @ORM\Column(name="prixParHeure", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixparheure;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var \Hotel
     *
     * @ORM\ManyToOne(targetEntity="Hotel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hotel_id", referencedColumnName="id")
     * })
     */
    private $hotel;


}
