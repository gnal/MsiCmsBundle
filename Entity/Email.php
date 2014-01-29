<?php

namespace Msi\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class Email
{
    use \Msi\AdminBundle\Doctrine\Extension\Model\Translatable;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $eventName;

    /**
     * @ORM\Column(type="string")
     */
    protected $fromWho;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $toWho;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $cc;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $bcc;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $availableVars;

    public function getCc()
    {
        return $this->cc;
    }

    public function setCc($cc)
    {
        $this->cc = $cc;

        return $this;
    }

    public function getBcc()
    {
        return $this->bcc;
    }

    public function setBcc($bcc)
    {
        $this->bcc = $bcc;

        return $this;
    }

    public function getAvailableVars()
    {
        return $this->availableVars;
    }

    public function setAvailableVars($availableVars)
    {
        $this->availableVars = $availableVars;

        return $this;
    }

    public function getEventName()
    {
        return $this->eventName;
    }

    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getFromWho()
    {
        return $this->fromWho;
    }

    public function setFromWho($fromWho)
    {
        $this->fromWho = $fromWho;

        return $this;
    }

    public function getToWho()
    {
        return $this->toWho;
    }

    public function setToWho($toWho)
    {
        $this->toWho = $toWho;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function __toString()
    {
        return (string) $this->getTranslation()->getSubject();
    }
}
