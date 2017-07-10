<?php

namespace Despark\Cms\Admin\Sidebar;

/*
 * Class SidebarItem.
 */
use Despark\Cms\Admin\Sidebar;

/**
 * Class SidebarItem.
 */
class SidebarItem
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string;
     */
    protected $name;

    /**
     * @var bool
     */
    public $active = false;

    /**
     * @var string
     */
    protected $iconClass = '';

    /**
     * @var Sidebar
     */
    protected $sidebar;

    /**
     * @var string;
     */
    protected $entityId;

    /**
     * @var bool
     */
    protected $hasChildren;

    /**
     * @var SidebarItem[]
     */
    protected $children;

    protected $childTemplate = 'ignicms::admin.layouts.sidebar.child';

    /**
     * SidebarItem constructor.
     *
     * @param array $config
     */
    public function __construct(Sidebar $sidebar, array $config)
    {
        $this->sidebar = $sidebar;
        foreach ($config as $property => $value) {
            $this->{$property} = $value;
        }
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return SidebarItem
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function isActive()
    {
        if ($this->active) {
            return true;
        }

        // TODO Version Dependant
        $resourceNameArray = explode('.', \Route::currentRouteName());
        $resourceName = reset($resourceNameArray);

        if (strcasecmp($resourceName, $this->getEntityId()) === 0) {
            $this->active = true;

            return true;
        }

        // Check if the current menu item is active.
        if (isset($this->link)) {
            return $this->link === \Route::currentRouteName();
        }

        // Check if we have childs and if one of them is active
        if ($this->hasChildren()) {
            foreach ($this->getChildren() as $child) {
                if ($child->isActive()) {
                    $this->active = true;

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param bool $active
     *
     * @return SidebarItem
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return isset($this->link) ? route($this->link) : '#';
    }

    /**
     * @return string
     */
    public function getIconClass()
    {
        return isset($this->iconClass) ? $this->iconClass : '';
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return isset($this->parent) && $this->parent;
    }

    public function getWeight()
    {
        return isset($this->weight) ? $this->weight : 0;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        if (! isset($this->hasChildren)) {
            foreach ($this->sidebar->getSidebarItems() as $item) {
                if ($item->hasParent() && $item->parent == $this->getId()) {
                    $this->hasChildren = true;
                    break;
                } else {
                    $this->hasChildren = false;
                }
            }
        }

        return $this->hasChildren;
    }

    public function getChildren()
    {
        if (! isset($this->children)) {
            foreach ($this->sidebar->getSidebarItems() as $item) {
                if ($item->hasParent() && $item->parent == $this->getId()) {
                    $this->children[$item->getId()] = $item;
                }
            }
        }

        return $this->children;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return SidebarItem
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return view('ignicms::admin.layouts.sidebar.item', ['sidebarItem' => $this])->__toString();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toHtml();
    }

    /**
     * @return string
     */
    public function getChildTemplate()
    {
        return $this->childTemplate;
    }

    /**
     * Gets the value of entityId.
     *
     * @return string
     */
    public function getEntityId(): string
    {
        return $this->entityId;
    }

    /**
     * Sets the value of entityId.
     *
     * @param mixed $entityId the entity id
     *
     * @return self
     */
    public function setEntityId(string $entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }
}
