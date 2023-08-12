<?php

class AdminCustomProductTextController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->bootstrap = true;
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitCustomText')) {
            $id_product = (int) Tools::getValue('id_product');
            $custom_text = Tools::getValue('custom_text');

            Db::getInstance()->execute('REPLACE INTO ' . _DB_PREFIX_ . 'custom_product_text (id_product, custom_text) VALUES (' . (int) $id_product . ', \'' . pSQL($custom_text) . '\')');

            $this->confirmations[] = $this->l('Custom text saved successfully.');
        }
    }
}
