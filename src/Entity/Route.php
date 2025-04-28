<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Route
 *
 * @ORM\Table(name="route", indexes={@ORM\Index(name="transportId", columns={"transportId"})})
 * @ORM\Entity
 */
class Route
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
     * @ORM\Column(name="depart", type="string", length=255, nullable=false)
     */
    private $depart;

    /**
     * @var string
     *
     * @ORM\Column(name="arrivee", type="string", length=255, nullable=false)
     */
    private $arrivee;

    /**
     * @var float
     *
     * @ORM\Column(name="distance", type="float", precision=10, scale=0, nullable=false)
     */
    private $distance;

    /**
     * @var string
     *
     * @ORM\Column(name="duree", type="string", length=50, nullable=false)
     */
    private $duree;

    /**
     * @var \TransportMeans
     *
     * @ORM\ManyToOne(targetEntity="TransportMeans")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="transportId", referencedColumnName="id")
     * })
     */
    private $transportid;


}
