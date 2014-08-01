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
interface TopicInterface
{
    public function getId();
    public function getTitle();
    public function setTitle($title);
    public function getCachedViewCount();
    public function setCachedViewCount($cachedViewCount);
    public function getCachedReplyCount();
    public function setCachedReplyCount($cachedReplyCount);
    public function isClosed();
    public function setClosed($isClosed);
    public function getClosedDate();
    public function setClosedDate($closedDate);
    public function isDeleted();
    public function setDeleted($isDeleted);
    public function getDeletedDate();
    public function setDeletedDate($deletedDate);
    public function isSticky();
    public function setSticky($isSticky);
    public function getStickiedDate();
    public function setStickiedDate($stickiedDate);

}
