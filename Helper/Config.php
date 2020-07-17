<?php
namespace Burst\Express\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper{
    public function __construct( 
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager)
	{
        $this->scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
    }
    /**
     * Get store ID
     *
     * @return string
     */
    public function getStoreID(){
        return $this->_storeManager->getStore()->getId();
        
    }
    /**
     * Get store name
     *
     * @return string
     */
    public function getStorename(){
        return $this->scopeConfig->getValue(
            'trans_email/ident_sales/name',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );   
    }
    /**
     * Get default store email
     *
     * @return string
     */
    public function getStoreEmail(){
        return $this->scopeConfig->getValue(
            'trans_email/ident_sales/email',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get activate status from modue 
     * 
     * @return bool
     */
    public function getActive(): bool
    {
        return $this->scopeConfig->getValue('carriers/express/active', ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get module title
     * 
     * @return string
     */
    public function getTitle()
    {
        return $this->scopeConfig->getValue('carriers/express/title', ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get name from module
     * 
     * @return string
     */
    public function getName()
    {
        return $this->scopeConfig->getValue('carriers/express/name', ScopeInterface::SCOPE_STORE);
    }
    /**
     * Get handling_type from module
     * 
     * @return string
     */
    public function getHandlingType()
    {
        return $this->scopeConfig->getValue('carriers/express/handling_type', ScopeInterface::SCOPE_STORE);
    }
    /**
     * Get price from module
     * 
     * @return string
     */
    public function getPrice()
    {
        return $this->scopeConfig->getValue('carriers/express/price', ScopeInterface::SCOPE_STORE);
    }
    /**
     * Get regions from module
     * 
     * @return string
     */
    public function getRegions()
    {
        return $this->scopeConfig->getValue('carriers/express/regions', ScopeInterface::SCOPE_STORE);
    }
    /**
     * Get specificerrmsg from module
     * 
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->scopeConfig->getValue('carriers/express/specificerrmsg', ScopeInterface::SCOPE_STORE);
    }
    /**
     * Get categories from module
     * 
     * @return string
     */
    public function getCategories()
    {
        return $this->scopeConfig->getValue('carriers/express/categories', ScopeInterface::SCOPE_STORE);
    }
    /**
     * Get days from module
     * 
     * @return string
     */
    public function getDays()
    {
        return $this->scopeConfig->getValue('carriers/express/days', ScopeInterface::SCOPE_STORE);
    }
    /**
     * Get from_hour from module
     * 
     * @return string
     */
    public function getFromHour()
    {
        return $this->scopeConfig->getValue('carriers/express/from_hour', ScopeInterface::SCOPE_STORE);
    }
    /**
     * Get to_hour from module
     * 
     * @return string
     */
    public function getToHour()
    {
        return $this->scopeConfig->getValue('carriers/express/to_hour', ScopeInterface::SCOPE_STORE);
    }
    /**
     * Get copy_to from module
     * 
     * @return string
     */
    public function getCopyTo()
    {
        return $this->scopeConfig->getValue('carriers/express/copy_to', ScopeInterface::SCOPE_STORE);
    }
    /**
     * Get subject from module
     * 
     * @return string
     */
    public function getSubject()
    {
        return $this->scopeConfig->getValue('carriers/express/subject', ScopeInterface::SCOPE_STORE);
    }
}