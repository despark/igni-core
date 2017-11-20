<?php

namespace Despark\Cms\Fields;

use Illuminate\Database\Eloquent\Model;

class Seo extends Field
{
    protected $galleryItems;
    protected $model;
    protected $fieldName;
    protected $options;

    public function getSlug()
    {
        return $this->model->slug;
    }

    /**
     * Sets the value of model.
     *
     * @param mixed $model the model
     *
     * @return self
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Gets the value of model.
     *
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        // Prepare options
        return view($this->getTemplate(), [
            'field' => $this,
            'record' => $this->model,
            'fieldName' => $this->fieldName,
            'options' => $this->options,
        ])->render();
    }

    /**
     * @return Collection|\Illuminate\Database\Eloquent\Collection
     */
    public function getGalleryItems($type)
    {
        return $this->model->images()
            ->where('image_type', $type)
            ->first();
    }
}
