<?php

namespace Despark\Cms\Admin;

use Despark\Cms\Admin\Sidebar\SidebarItem;
use Despark\Cms\Resource\EntityManager;

/**
 * Class Sidebar.
 *
 * @todo Make the menu items separate file again. Keep the possibility entities to define menu items
 */
class Sidebar
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var SidebarItem[]
     */
    protected $sidebarItems = [];

    protected $template = 'ignicms::admin.layouts.sidebar';

    /**
     * Sidebar constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        // We need to build the sidebar items.
        foreach ($entityManager->all() as $entityId => $configs) {
            if (isset($configs['adminMenu'])) {
                foreach ($configs['adminMenu'] as $key => $config) {
                    $sidebarItem = new SidebarItem($this, $config);
                    $sidebarItem->setId($key);
                    $sidebarItem->setEntityId($entityId);
                    $this->add($sidebarItem);
                }
            }
        }
    }

    /**
     * @param SidebarItem $sidebarItem
     */
    public function add(SidebarItem $sidebarItem)
    {
        $this->sidebarItems[$sidebarItem->getId()] = $sidebarItem;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        $this->beforeToHtml();

        return view($this->template, ['sidebarItems' => $this->getSidebarItems()])->__toString();
    }

    /**
     * Before html rendering.
     */
    protected function beforeToHtml()
    {
        uasort($this->sidebarItems, function ($a, $b) {
            return $a->getWeight() - $b->getWeight();
        });
    }

    public function __toString()
    {
        return $this->toHtml();
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @param EntityManager $entityManager
     *
     * @return Sidebar
     */
    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * @return SidebarItem[]
     */
    public function getSidebarItems()
    {
        return $this->sidebarItems;
    }

    /**
     * @param SidebarItem[] $sidebarItems
     *
     * @return Sidebar
     */
    public function setSidebarItems($sidebarItems)
    {
        $this->sidebarItems = $sidebarItems;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     *
     * @return Sidebar
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }
}
