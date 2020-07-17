<?php

namespace Burst\Express\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class WeekDaysList implements ArrayInterface
{
    protected $_categoryFactory;
    protected $_categoryCollectionFactory;

    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
    )
    {
        $this->_categoryFactory = $categoryFactory;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
    }

    public function toOptionArray()
    {
        $arr = $this->_toArray();
        $ret = [];

        foreach ($arr as $key => $value)
        {
            $ret[] = [
                'value' => $key,
                'label' => $value
            ];
        }

        return $ret;
    }

    private function _toArray()
    {
        $week_days=[
            '1'=>'Monday',
            '2'=>'Tuesday',
            '3'=>'Wednesday',
            '4'=>'Thursday',
            '5'=>'Friday',
            '6'=>'Saturday',
            '7'=>'Sunday'
        ];
        return $week_days;
    }

}