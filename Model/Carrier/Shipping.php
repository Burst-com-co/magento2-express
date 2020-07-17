<?php
namespace Burst\Express\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;

class Shipping extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'express';

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $_rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $_rateMethodFactory;

    /**
     * Shipping constructor.
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface          $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory  $rateErrorFactory
     * @param \Psr\Log\LoggerInterface                                    $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory                  $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array                                                       $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        array $data = [],
        \Burst\Express\Helper\Config $config,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Directory\Model\RegionFactory $regionFactory
    ) {
        $this->_timezone = $context->getLocaleDate();
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->_config = $config;
        $this->_regionFactory = $regionFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * get allowed methods
     * @return array
     */
    public function getAllowedMethods()
    {
        return [$this->_code => $this->getConfigData('name')];
    }

    /**
     * @return float
     */
    private function getShippingPrice()
    {
        $configPrice = $this->getConfigData('price');

        $shippingPrice = $this->getFinalPriceWithHandlingFee($configPrice);

        return $shippingPrice;
    }

    /**
     * @param RateRequest $request
     * @return bool|Result
     */
    public function collectRates(RateRequest $request)
    {
        $regionId=$request->getDestRegionCode();
        $bcs=$this->dateAndTimeValidation();
        if (!$this->getConfigFlag('active') || 
            !$this->belongsToSelectedCategories($request->getAllItems()) ||
            !$this->dateAndTimeValidation() ||
            !$this->regionValidation($regionId)) {
            return false;
        }
        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();
        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();
        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));
        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));
        $amount = $this->getShippingPrice();
        $method->setPrice($amount);
        $method->setCost($amount);
        $result->append($method);
        return $result;
    }
    public function belongsToSelectedCategories($items)
    {
        if (\is_null($this->_config->getCategories())) {
            return true;
        } else {
            $belongs_to=true;
            foreach ($items as $item) {
                $productid = $item->getProductId();
                $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $product = $_objectManager->create('Magento\Catalog\Model\Product')->load($productid);
                $product_categories = json_decode(json_encode($product->getCategoryIds()));
                $enabled_categories=explode(',',$this->_config->getCategories());
                if (count(array_intersect($product_categories, $enabled_categories))==0) {
                    $belongs_to=false;
                }
            }
        }
        return $belongs_to;
    }
    public function dateAndTimeValidation()
    {
        $days=explode(',',$this->_config->getDays());
        $from_hour=explode(',',$this->_config->getFromHour());
        $to_hour=explode(',',$this->_config->getToHour());
        if (is_null($days)) {
            return false;
        }
        $today=$this->_timezone->date()->format('Y-m-d H:i:s');
        $today_day_of_week=date('D',\strtotime($today));
        $today_day_hour=date('H',\strtotime($today));
        if (in_array($today_day_of_week, $days)) {
            if ($today_day_hour>=$from_hour[0] && $today_day_hour<$to_hour[0]) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        return false;
    }
    public function regionValidation($quote_region_code)
    {
        $regions=explode(',',$this->_config->getRegions());
        if (\is_null($regions)) {
            return true;
        } else {
            $codes=\explode('-', $quote_region_code);
            $regionCode = $quote_region_code;
            $countryCode = $codes[0];
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $region = $objectManager->create('Magento\Directory\Model\Region');
            $regionId = $region->loadByCode($quote_region_code, $countryCode)->getId();
            if (in_array($regionId, $regions)) {
                return true;
            } else {
                return false;
            }
            return false;
        }
    }
}