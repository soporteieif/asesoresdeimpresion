<?php
class AdminProductsController extends AdminProductsControllerCore
{
    protected function _displaySpecificPriceModificationForm($defaultCurrency, $shops, $currencies, $countries, $groups)
	{
	   $result = parent::_displaySpecificPriceModificationForm($defaultCurrency, $shops, $currencies, $countries, $groups);
       
       $params = array(
            'id_product'=>(int)$this->id,
            'defaultCurrency' => $defaultCurrency,
            'shops' => $shops,
            'countries' => $countries,
            'groups' => $groups);
       
       if($display = Hook::exec('displayAdminProductPriceFormFooter', $params))
            return $result . '
               <div class="panel">
               <h3>'.$this->l('Extra settings').'</h3>
               '.$display.'
               <div class="panel-footer">
        				<a href="'.$this->context->link->getAdminLink('AdminProducts').'" class="btn btn-default"><i class="process-icon-cancel"></i> '.$this->l('Cancel').'</a>
        				<button id="product_form_submit_btn"  type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> '.$this->l('Save') .'</button>
        				<button id="product_form_submit_btn"  type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> '.$this->l('Save and stay') .'</button>
        			</div>
               </div>';
       return $result;
    }
}
?>