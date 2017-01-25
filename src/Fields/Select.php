<?php


namespace Despark\Cms\Fields;


use Despark\Cms\Contracts\SourceModel;

class Select extends Field
{

    protected $sourceModel;


    public function getSelectOptions()
    {
        $defaultOption = [null => 'Select '.$this->getLabel()];
        $options = array_merge($defaultOption, $this->getSourceModel()->toOptionsArray());

        return $options;
    }

    /**
     * @return SourceModel
     * @throws \Exception
     */
    public function getSourceModel()
    {
        if (! isset($this->sourceModel) && ($sourceModel = $this->getOptions('sourceModel'))) {
            if (class_exists($sourceModel)) {
                $this->sourceModel = new $sourceModel;
                if (! $this->sourceModel instanceof SourceModel) {
                    throw new \Exception('Source model for field '.$this->getFieldName().' must implement '.SourceModel::class);
                }
            } else {
                throw new \Exception('Source model is missing for field '.$this->getFieldName());
            }
        }

        return $this->sourceModel;
    }

    public function getAttributes()
    {
        $attributes = isset($this->options['attributes']) ? $this->options['attributes'] : [];
        $a = array_merge_recursive(['class' => ['form-control']], $attributes);

        if (isset($a['class']) && is_array($a['class'])) {
            $a['class'] = implode(' ', $a['class']);
        }

        return $a;
    }
}