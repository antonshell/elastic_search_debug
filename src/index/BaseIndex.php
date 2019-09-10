<?php

namespace src\index;

/**
 * Class BaseIndex
 * @package src\index
 */
abstract class BaseIndex
{
    /**
     * @return array
     */
    public function getSettings(): array
    {
        $settings = [
            'max_result_window' => 500000,
            'mapping.total_fields.limit' => 5000,
            'settings' => [
                'index' => [
                    'similarity' => [
                        'default' => [
                            'type' => 'BM25',
                      ]
                    ]
                ],
                'analysis' => [
                    'filter' => [
                        'my_synonym_filter' => [
                            'type' => 'synonym',
                            'synonyms' => [],
                        ],
                        'ru_stemmer' => [
                            'type'=> 'stemmer',
                            'language'=> 'russian'
                        ],

                        'en_stopwords' => [
                            'type' => 'stop',
                            'stopwords' => 'an,and,are,as,at,be,but,by,for,if,in,into,is,it,no,not,of,on,or,such,that,the,their,then,there,these,they,this,to,was,will,with'
                        ],
                        'ru_stopwords' => [
                            'type' => 'stop',
                            'stopwords' => '_russian_'
                        ],
                    ],

                    'analyzer' => [
                        'my_synonyms' => [
                            'tokenizer' => 'standard',
                            'filter' => [
                                'lowercase',
                                'my_synonym_filter',
                                'ru_stopwords',
                                'ru_stemmer',
                                /* needed for 5.6 */
                                //'russian_morphology',
                                //'english_morphology',
                                'en_stopwords'
                            ],
                        ],

                        'model_analyzer' => [
                            'tokenizer' => 'standard',
                            'filter' => [
                                'lowercase',
                            ],
                        ]
                    ],
                ],
            ]
        ];

        return $settings;
    }

    /**
     * @param bool $useKeyword
     * @return array
     */
    protected function getTextType($useKeyword = false){
        $typeText = [
            'type' => 'text',
            'index' => true,
            'search_analyzer'=> 'my_synonyms',
            'analyzer' => 'my_synonyms',
            'term_vector' => 'with_positions_offsets_payloads'
        ];

        if($useKeyword){
            $typeText['fields'] = [
                'keyword' => [
                    'type' => 'keyword',
                    'ignore_above' => 256
                ]
            ];
        }

        return $typeText;
    }

    /**
     * @return array
     */
    protected function getSuggestType(){
        $typeSuggest = [
            'type' => 'completion',
            'analyzer' => 'my_synonyms',
            'search_analyzer' => 'my_synonyms',
            'preserve_position_increments' => true
        ];

        return $typeSuggest;
    }

    /**
     * @return array
     */
    protected function getDatetimeType(){
        return [
            'type' => 'date',
            'format' => 'yyyy-MM-dd HH:mm:ss'
        ];
    }

    /**
     * @return array
     */
    protected function getIntegerType()
    {
        return ['type'=> 'integer'];
    }
}