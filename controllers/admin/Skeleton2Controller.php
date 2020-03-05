<?php

if (!defined('_PS_VERSION_')) {
    exit;
}
//require_once '../classes/order/Orders.php';
// require_once dirname(__FILE__).'/../../models/ExportModel.php';



class Skeleton2Controller extends AdminController{
	
	public function __construct(){
		$this->bootstrap = true;
		parent::__construct();
		
	}
	
 	public function getTemplatePath()
	{
		return dirname(__FILE__).'/../../views/templates/admin/';
        
	}

		
	 public function createTemplate($tpl_name) {
        if (file_exists($this->getTemplatePath() . $tpl_name) && $this->viewAccess())
            return $this->context->smarty->createTemplate($this->getTemplatePath() . $tpl_name, $this->context->smarty);
            return parent::createTemplate($tpl_name);

	} 
	
	
	public function initContent(){
		// $this->createTabs($this->config_module['tabs']);
		// $this->removeTabs($this->config_module['tabs']);
		// $tabId = (int) Tab::getIdFromClassName('SELL');
		
		$smarty = $this->context->smarty;
		
		$smarty->assign('orders',array());//ExportModel::getExports());

		$this->content=$this->createTemplate('Skeleton2.tpl')->fetch();
		parent::initContent();
		
	}
	
	// public function setMedia(){
		// parent::setMedia();
		//$this->addJS('../views/js/table2CSV.js'); 
	// }

}