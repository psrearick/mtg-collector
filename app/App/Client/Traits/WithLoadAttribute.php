<?php

namespace App\App\Client\Traits;

use App\Jobs\ImportCardImages;

trait WithLoadAttribute
{
    /**
     * @param iterable $collection
     * @param array $attributes
     * @return iterable
     */
    public function loadAttribute(iterable $collection, array $attributes) : iterable
    {
        foreach ($attributes as $attribute) {
            $key   = $attribute;
            $value = $attribute;

            if (is_array($attribute)) {
                $key   = $attribute['key'];
                $value = $attribute['value'];
            }

            foreach ($collection as $item) {
                $item->{$key} = $item->{$value};
//                $this->watch($item, $attribute);
            }
        }

        return $collection;
    }

//    private function watch($item, string $attribute) {
//        if ($attribute == 'image_url') {
//            ImportCardImages::dispatch($item);
//        }
//    }
}