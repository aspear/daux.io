<?php namespace Todaymade\Daux\Tree;

abstract class Entry
{
    /** @var string */
    protected $title;

    /** @var string */
    protected $name;

    /** @var string */
    protected $uri;

    /** @var Directory */
    protected $parent;

    /** @var string */
    protected $path;

    /** @var integer */
    protected $last_modified;

    /**
     * @param Directory $parent
     * @param string $uri
     * @param string $path
     * @param integer $last_modified
     */
    public function __construct(Directory $parent, $uri, $path = null, $last_modified = null)
    {
        $this->setUri($uri);
        $this->setParent($parent);

        if ($path !== null) {
            $this->path = $path;
        }

        if ($last_modified !== null) {
            $this->last_modified = $last_modified;
        }
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
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri)
    {
        if ($this->parent) {
            $this->parent->removeChild($this);
        }

        $this->uri = $uri;

        if ($this->parent) {
            $this->parent->addChild($this);
        }
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return Directory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Return all parents starting with the root
     *
     * @return Directory[]
     */
    public function getParents()
    {
        $parents = [];
        if ($this->parent && !$this->parent instanceof Root) {
            $parents = $this->parent->getParents();
            $parents[] = $this->parent;
        }

        return $parents;
    }

    /**
     * @param Directory $parent
     */
    protected function setParent(Directory $parent)
    {
        if ($this->parent) {
            $this->parent->removeChild($this);
        }

        $parent->addChild($this);
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $url = '';

        if ($this->getParent() && !$this->getParent() instanceof Root) {
            $url = $this->getParent()->getUrl() . '/' . $url;
        }

        $url .= $this->getUri();
        return $url;
    }

    public function dump()
    {
        return [
            'title' => $this->getTitle(),
            'type' => get_class($this),
            'name' => $this->getName(),
            'uri' => $this->getUri(),
            'url' => $this->getUrl(),
            'path' => $this->path
        ];
    }
}