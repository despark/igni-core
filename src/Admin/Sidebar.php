<?php


namespace Despark\Cms\Admin;


use Despark\Cms\Admin\Sidebar\SidebarItem;
use Despark\Cms\Resource\ResourceManager;

/**
 * Class Sidebar.
 */
class Sidebar
{

    /**
     * @var ResourceManager
     */
    protected $resourceManager;

    /**
     * @var SidebarItem[]
     */
    protected $sidebarItems = [];

    protected $template = 'ignicms::admin.layouts.sidebar';

    /**
     * Sidebar constructor.
     * @param ResourceManager $resourceManager
     */
    public function __construct(ResourceManager $resourceManager)
    {
        $this->resourceManager = $resourceManager;
        // We need to build the sidebar items.
        foreach ($resourceManager->all() as $configs) {
            if (isset($configs['adminMenu'])) {

                foreach ($configs['adminMenu'] as $key => $config) {
                    $sidebarItem = new SidebarItem($this, $config);
                    $sidebarItem->setId($key);
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

    /**
     *
     */
    public function __toString()
    {
        return $this->toHtml();
    }

    /**
     * @return ResourceManager
     */
    public function getResourceManager()
    {
        return $this->resourceManager;
    }

    /**
     * @param ResourceManager $resourceManager
     * @return Sidebar
     */
    public function setResourceManager($resourceManager)
    {
        $this->resourceManager = $resourceManager;

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
     * @return Sidebar
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }


}