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

namespace CCDNForum\ForumBundle\Form\Handler\Admin\Category;

use CCDNForum\ForumBundle\Entity\CategoryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface ;

use Doctrine\Common\Collections\ArrayCollection;

use CCDNForum\ForumBundle\Component\Dispatcher\ForumEvents;
use CCDNForum\ForumBundle\Component\Dispatcher\Event\AdminCategoryEvent;
use CCDNForum\ForumBundle\Form\Handler\BaseFormHandler;
use CCDNForum\ForumBundle\Model\FrontModel\ModelInterface;
use CCDNForum\ForumBundle\Entity\Category;

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
class CategoryDeleteFormHandler extends BaseFormHandler
{
    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Form\Type\Admin\Category\CategoryDeleteFormType $categoryDeleteFormType
     */
    protected $categoryDeleteFormType;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Model\FrontModel\CategoryModel $categoryModel
     */
    protected $categoryModel;

    /**
     *
     * @access protected
     * @var \CCDNForum\ForumBundle\Entity\Category $category
     */
    protected $category;

    /**
     *
     * @access public
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @param \Symfony\Component\Form\FormFactory $factory
     * @param $categoryDeleteFormType
     * @param \CCDNForum\ForumBundle\Model\FrontModel\ModelInterface $categoryModel
     */
    public function __construct(EventDispatcherInterface $dispatcher, FormFactory $factory, $categoryDeleteFormType, ModelInterface $categoryModel)
    {
        $this->dispatcher = $dispatcher;
        $this->factory = $factory;
        $this->categoryDeleteFormType = $categoryDeleteFormType;
        $this->categoryModel = $categoryModel;
    }

    /**
     *
     * @access public
     * @param \CCDNForum\ForumBundle\Entity\CategoryInterface $category
     * @return \CCDNForum\ForumBundle\Form\Handler\Admin\Category\CategoryDeleteFormHandler
     */
    public function setCategory(CategoryInterface $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     *
     * @access public
     * @throws \Exception
     * @return \Symfony\Component\Form\Form
     */
    public function getForm()
    {
        if (null == $this->form) {
            if (!is_object($this->category) && !$this->category instanceof CategoryInterface) {
                throw new \Exception('Category object must be specified to delete.');
            }

            $this->dispatcher->dispatch(ForumEvents::ADMIN_CATEGORY_DELETE_INITIALISE, new AdminCategoryEvent($this->request, $this->category));

            $this->form = $this->factory->create($this->categoryDeleteFormType, $this->category);
        }

        return $this->form;
    }

    /**
     *
     * @access protected
     * @param \CCDNForum\ForumBundle\Entity\CategoryInterface $category
     */
    protected function onSuccess(CategoryInterface $category)
    {
        $this->dispatcher->dispatch(ForumEvents::ADMIN_CATEGORY_DELETE_SUCCESS, new AdminCategoryEvent($this->request, $category));

        if (! $this->form->get('confirm_subordinates')->getData()) {
            $boards = new ArrayCollection($category->getBoards()->toArray());

            $this->categoryModel->reassignBoardsToCategory($boards, null)->flush();
        }

        $this->categoryModel->deleteCategory($category);

        $this->dispatcher->dispatch(ForumEvents::ADMIN_CATEGORY_DELETE_COMPLETE, new AdminCategoryEvent($this->request, $category));
    }
}
