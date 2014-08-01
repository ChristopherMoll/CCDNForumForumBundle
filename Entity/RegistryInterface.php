<?php

/*
 * This file is part of the CCDNForum ForumBundle
 *
 * (c) CCDN (c) CodeConsortium <http://www.codeconsortium.com/>
 *
 * Available on github <http://www.github.com/codeconsortium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CCDNForum\ForumBundle\Entity;

use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 *
 * @category CCDNForum
 * @package  ForumBundle
 *
 * @author   Reece Fowell <reece@codeconsortium.com>
 * @license  http://opensource.org/licenses/MIT MIT
 * @version  Release: 2.0
 * @link     https://github.com/codeconsortium/CCDNForumForumBundle
 *
 */
interface PostInterface
{
    public function getId();
    public function getBody();
    public function setBody($body);
    public function getCreatedDate();
    public function setCreatedDate($createdDate);
    public function getEditedDate();
    public function setEditedDate($editedDate);
    public function isDeleted();
    public function setDeleted($isDeleted);
    public function getDeletedDate();
    public function setDeletedDate($deletedDate);
    public function getUnlockedDate();
    public function setUnlockedDate(\Datetime $datetime);
    public function getUnlockedUntilDate();
    public function setUnlockedUntilDate(\Datetime $datetime);
    public function isLocked();

    public function getId()
    public function getCachedPostCount()
    public function setCachedPostCount($cachedPostCount)
    public function getCachedKarmaPositiveCount()
    public function setCachedKarmaPositiveCount($cachedKarmaPositiveCount)
    public function getCachedKarmaNegativeCount()
    public function setCachedKarmaNegativeCount($cachedKarmaNegativeCount)
}
