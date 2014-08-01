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
interface RegistryInterface
{
    public function getId();
    public function getCachedPostCount();
    public function setCachedPostCount($cachedPostCount);
    public function getCachedKarmaPositiveCount();
    public function setCachedKarmaPositiveCount($cachedKarmaPositiveCount);
    public function getCachedKarmaNegativeCount();
    public function setCachedKarmaNegativeCount($cachedKarmaNegativeCount);
}
