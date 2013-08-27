<?php

namespace Msi\CmsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Msi\CmsBundle\Model\SiteTranslation as BaseSiteTranslation;

/**
 * @ORM\Entity
 */
class SiteTranslation extends BaseSiteTranslation
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
}
