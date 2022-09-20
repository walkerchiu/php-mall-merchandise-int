<?php

namespace WalkerChiu\MallMerchandise\Models\Entities;

use WalkerChiu\Core\Models\Entities\Lang;

class VariantLang extends Lang
{
    /**
     * Create a new instance.
     *
     * @param Array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->table = config('wk-core.table.mall-merchandise.variants_lang');

        parent::__construct($attributes);
    }
}
