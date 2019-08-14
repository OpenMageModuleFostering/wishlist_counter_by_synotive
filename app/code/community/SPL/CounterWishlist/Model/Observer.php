<?php
/**
 * SPL
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category    SPL
 * @package     SPL_CounterWishlist
 * @copyright   Copyright (c) 2015 SPL. 
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class SPL_CounterWishlist_Model_Observer
{

    const ENABLEWISHLIST = 'catalog/spl_saleswishlistcounter/enabledwishlist';
    

    public function catalogProductLoadAfter(Varien_Event_Observer $observer)
    {
        if (Mage::getStoreConfigFlag(self::ENABLEWISHLIST)) {
            $productId = $observer->getProduct()->getId();
            $wishlist = Mage::getModel('wishlist/item')->getCollection();
            $wishlist->getSelect()
                ->join(array('t2' => 'wishlist'),
                    'main_table.wishlist_id = t2.wishlist_id',
                    array('wishlist_id','customer_id'))
                ->where('main_table.product_id = '. $productId);
            $count = $wishlist->getSize();
            $observer->getProduct()->setWishlistCount($count);
        }        
    }

    public function catalogProductCollectionLoadAfter(Varien_Event_Observer $observer)
    {
        if (Mage::getStoreConfigFlag(self::ENABLEWISHLIST)) {
            $productCollection = $observer->getCollection();
            foreach ($productCollection as $product) {
                $productId = $product->getId();
                $wishlist = Mage::getModel('wishlist/item')->getCollection();
                $wishlist->getSelect()
                    ->join(array('t2' => 'wishlist'),
                        'main_table.wishlist_id = t2.wishlist_id',
                        array('wishlist_id','customer_id'))
                    ->where('main_table.product_id = '. $productId);
                $count = $wishlist->getSize();
                $product->setWishlistCount($count);
            }
        }
    }
}