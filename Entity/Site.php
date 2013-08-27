<?php

namespace Msi\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Msi\CmsBundle\Model\Site as BaseSite;

/**
 * @ORM\Entity
 */
class Site extends BaseSite
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
