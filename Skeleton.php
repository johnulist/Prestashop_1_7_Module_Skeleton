<?php
/**
* 2007-2018 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2018 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
// require_once dirname(__FILE__).'/models/StatusModel.php';
class Skeleton extends Module
{

	protected $config_module = array();




    public function __construct()
    {
		$this->init();
        $this->name    = $this->config_module['name'];
        $this->tab     = $this->config_module['parent_tab'];
        $this->version = $this->config_module['version'];
        $this->author  = $this->config_module['author'];
        $this->need_instance = 0;
        $this->bootstrap = true;
		$this->displayName =  $this->config_module['displayName'];
        $this->description =  $this->config_module['description'];
        $this->ps_versions_compliancy = $this->config_module['versions_compliancy'];
        parent::__construct();
    }
	
	private function init(){
		$this->config_module = array(
			'name' => 'Skeleton',
			'displayName' => $this->l('Skeleton'),
			'description' => $this->l('Skeleton Module Prestashop 1.7'),
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
					'icon' => 'public',
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
			),
		);
	}

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */

	public function installTabs($src,$parent = 0){
		$result = true;
		foreach($src as $tab){
			$parent_tab = ($parent > 0) ? $parent : ((isset($tab['parent_class_name']) ) ? (int) Tab::getIdFromClassName($tab['parent_class_name']) : 0 );
			if(!$parent_tab){
				$parent_tab=null;
			}
			
			$tabObj = new Tab();
			$tabObj->active = (isset($tab['active'])) ? $tab['active'] : false;
			$tabObj->class_name = $tab['class_name'];
			$tabObj->name = array();
			$tabObj->icon = (isset($tab['icon']) ? $tab['icon'] : "");
			foreach (Language::getLanguages() as $lang) {
				$tabObj->name[$lang['id_lang']] = $tab['name'];
			}
			$tabObj->id_parent = $parent_tab;
			$tabObj->module = $this->name;

			// echo "Parent_id : ".$parent_tab." / Parent-class-name : ".$tab['parent_class_name']." / Class-name : ".$tab['class_name']."<br/>";
			//Create tab here.
			//
			// $id_created_parent_tab = rand(0,100);
			$result = $result && $tabObj->save();
			$id_created_parent_tab =  (int) Tab::getIdFromClassName($tab['class_name']);

			if(isset($tab['childs']) && count($tab['childs'])>0){
				$childResult = $this->installTabs($tab['childs'],$id_created_parent_tab);
				$result = $result && $childResult;
			}
		}
		return $result;
	}

	public function  uninstallTabs($src){
		$result = true;
		foreach($src as $tab){
			if(isset($tab['childs']) && count($tab['childs'])>0){
				$this->uninstallTabs($tab['childs']);
			}
			$tabId = (int) Tab::getIdFromClassName($tab['class_name']);
			if (!$tabId) {
				continue;
			}
			
			$tabObj = new Tab($tabId);
			$result = $result && $tabObj->delete();
			// $tab->delete();
			// echo "Class-name : ".$tab['class_name']."<br/>";
		}
		return $result;
	}

	
    public function install()
    {
        return parent::install() && $this->registerHook('displayBackOfficeHeader') && $this->installTabs($this->config_module['tabs']);
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallTabs($this->config_module['tabs']) && $this->unregisterHook('displayBackOfficeHeader');
    }


    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */

         
        if (((bool)Tools::isSubmit('submit'.$this->name)) == true) {
			echo "<p>Config form submit</p>";
			exit;
        }

        $this->context->smarty->assign('module_dir', $this->_path);
       

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();
		$helper->module = $this;        
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = $this->context->language->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
		$helper->title = $this->displayName;
		$helper->show_toolbar = true;
		$helper->toolbar_scroll = true;
		$helper->submit_action = 'submit'.$this->name;
		$helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );



        $helper->fields_value['SKELETON_CONFIGURATION'] = Configuration::get('SKELETON_CONFIGURATION');

		$fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),

            'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('SKELETON_CONFIGURATION'),
					'name' => 'SKELETON_CONFIGURATION',
					'size' => 220,
					'required' => true
				)
			) ,
            
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ),
            'buttons' => array(
                array(
                    'href' => "#",
                    'title' => $this->l('Button1'),
                    'id' => 'button1',
                    'icon' => 'process-icon-delete'
                ),
                array(
                    'href' => "#",
                    'title' => $this->l('Button2'),
                    'id' => 'button2',
                    'icon' => 'process-icon-refresh'
                ),
                array(
                    'href' => "#",
                    'title' => $this->l('Button3'),
                    'id' => 'button3',
                    'icon' => 'process-icon-refresh'
                )
            )
        );
		
        return $helper->generateForm($fields_form);

       
    }

 
 
    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        $this->context->controller->addCss($this->local_path.'views/css/back.css');
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        
    }

}
