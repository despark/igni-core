<?php


namespace Despark\Cms\Fields;


use Despark\Cms\Models\AdminModel;

/**
 * Class Custom.
 */
class Custom extends Field
{

    /**
     * @var
     */
    protected $template;

    /**
     * @var
     */
    protected $handler;

    /**
     * Custom constructor.
     * @param AdminModel $model
     * @param            $fieldName
     * @param array      $options
     * @param null       $elementName
     * @throws \Exception
     */
    public function __construct(
        AdminModel $model,
        $fieldName,
        array $options,
        $elementName = null
    ) {
        parent::__construct($model, $fieldName, $options, $elementName);

        // Check for handler and use it
        if (isset($options['handler'])) {
            if (class_exists($options['handler'])) {
                $this->handler = new $options['handler']($this);
            }
        }

        if (! isset($options['template'])) {
            throw new \Exception('Template is required for field '.$fieldName);
        }

        if (! \View::exists($options['template'])) {
            throw new \Exception('Template '.$options['template'].' doesn\'t exists.');
        }

        $this->template = $options['template'];

    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function toHtml()
    {
        return view($this->getTemplate(), ['field' => $this->getHandler()])->__toString();
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHandler()
    {
        if (! $this->handler) {
            return $this;
        }

        return $this->handler;
    }

    /**
     * @param mixed $handler
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
    }

}