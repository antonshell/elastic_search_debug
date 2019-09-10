<?php

namespace src\index;

/**
 * Class ProductIndex
 * @package src\index
 */
class ProductIndex extends BaseIndex implements IndexInterface
{
    private $indexName = 'product';

    private $mapperName = 'type';

    /**
     * @return string
     */
    public function getIndexName(): string
    {
        return $this->indexName;
    }

    /**
     * @return string
     */
    public function getMappingName(): string
    {
        return $this->mapperName;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        $typeText = $this->getTextType(true);
        $integer = ['type'=> 'integer'];
        $double = ['type'=> 'double'];

        $properties = [
            'properties' => [
                'id' => $integer,
                'sku' => $typeText,
                'name' => $typeText,
                'vendor' => $typeText,
                'model' => $typeText,
                'year' => $typeText,
            ],
        ];

        return $properties;
    }
}