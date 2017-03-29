<?php

namespace Despark\Cms\Fields;

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
     *
     * @param string $fieldName
     * @param array  $options
     * @param null   $value
     *
     * @throws \Exception
     *
     * @internal param AdminModel $model
     * @internal param null $elementName
     */
    public function __construct($fieldName, array $options, $value = null)
    {
        parent::__construct($fieldName, $options, $value);

        // Check for handler and use it
        if (isset($options['handler'])) {
            if (class_exists($options['handler'])) {
                // Todo make this to resolve through IOC
                $this->handler = new $options['handler']($fieldName, $options, $value);
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
     *
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
     *
     * @return $this
     */
    public function setTemplate(string $template)
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
