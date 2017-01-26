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
                // Todo make this to resolve through IOC
                $this->handler = new $options['handler']($this);
            }
        }

        if (! isset($options['template'])) {
            throw new \Exception('Template is required for field '.$fieldName);
        }

        if (isset($options['template']) && \View::exists($options['template'])) {
            $this->template = $options['template'];
        }


    }

    /**
     * @return string
     * @throws \Exception
     */
    public function toHtml()
    {
        $template = $this->getTemplate();
        if (! \View::exists($template)) {
            throw new \Exception('Template '.$template.' doesn\'t exists.');
        }

        return view($template, ['field' => $this->getHandler()])->__toString();
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