<?php

namespace Vend\PheatBundle\Entity;

class FormFeature
{
    public $name = null;
    public $status = null;
    public $ratio = null;
    public $vary = null;
    public $provider = null;
    public $variants = [];

    public function addVariant($variant)
    {
        $this->variants[] = $variant;
    }
}
