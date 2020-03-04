<?php

if (!defined('_PS_VERSION_')) {
    exit;
}
//require_once '../classes/order/Orders.php';
// require_once dirname(__FILE__).'/../../models/ExportModel.php';



class SkeletonController extends AdminController{
	
	protected $config_module = array(
		'name' => 'Skeleton',
		'displayName' => 'Skeleton',
		'description' => 'Skeleton Module Prestashop 1.7',
		'parent_tab' => 'export',
		'version' => '1.0.0',
		'author' => 'NOUREDDINE ERRAMY',
		'bootstrap' => true,
		'versions_compliancy' => array('min' => '1.6', 'max' => _PS_VERSION_),
		'tabs' => array(
			array(
				'name' => 'Skeleton', // One name for all langs
				'class_name' => 'Skeleton',
				'active' => true,
				'parent_class_name' => 'SELL',
				'childs' => array(
					array(
						'name' => 'Skeleton Menu 1',
						'class_name' => 'Skeleton1',
						'active' => true,
						'childs' => array(
							array(
								'name' => 'Skeleton Menu 1-1',
								'class_name' => 'Skeleton11',
								'active' => true,
							),
						)
					),
					array(
						'name' => 'Skeleton Menu 2',
						'class_name' => 'Skeleton2',
						'active' => true,
					),
				)
			)
		)
	);


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
		echo "ok";
		exit;

		$smarty = $this->context->smarty;
		
		$smarty->assign('orders',array());//ExportModel::getExports());

		$this->content=$this->createTemplate('Skeleton.tpl')->fetch();
		parent::initContent();
		
	}
	
	// public function setMedia(){
		// parent::setMedia();
		//$this->addJS('../views/js/table2CSV.js'); 
	// }

}