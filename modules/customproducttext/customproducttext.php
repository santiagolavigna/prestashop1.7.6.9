<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class CustomProductText extends Module
{
    public function __construct($name = null, Context $context = null)
    {
        $this->name = 'customproducttext';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Santiago Lavigna Jara';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Custom Product Text');
        $this->description = $this->l('Allows you to set custom text for each product.');
    }

    public function install()
    {
        return parent::install()
            && $this->installDb()
            && $this->registerHook('displayAdminProductsExtra')
            && $this->registerHook('DisplayProductExtraContent')
            && $this->registerHook('actionProductUpdate');
    }

    public function uninstall()
    {
        return parent::uninstall()
            && $this->uninstallDb();
    }

    private function installDb()
    {
        $sql = "CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "custom_product_text (
            `id_product` INT(11) NOT NULL,
            `custom_text` TEXT,
            PRIMARY KEY (`id_product`)
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8";

        return Db::getInstance()->execute($sql);
    }

    private function uninstallDb()
    {
        $sql = "DROP TABLE IF EXISTS " . _DB_PREFIX_ . "custom_product_text";
        return Db::getInstance()->execute($sql);
    }

    public function hookDisplayAdminProductsExtra($params)
    {
        $id_product = (int) $params['id_product'];
        $custom_text = Db::getInstance()->getValue('SELECT custom_text FROM ' . _DB_PREFIX_ . 'custom_product_text WHERE id_product = ' . (int) $id_product);

        $this->context->smarty->assign([
            'id_product' => $id_product,
            'custom_text' => $custom_text,
        ]);

        return $this->display(__FILE__, 'views/templates/admin/product_tab.tpl');
    }

    public function hookDisplayProductExtraContent($params)
    {
        $id_product = (int) $params['product']->id;
        $custom_text = Db::getInstance()->getValue('SELECT custom_text FROM ' . _DB_PREFIX_ . 'custom_product_text WHERE id_product = ' . (int) $id_product);

        //MOSTRAR CONTENIDO EN FRONT
        //OPCION A) AGREGAR AL TPL EL HOOK           {hook h='displayProductExtraContent' product=$product}
//        if ($custom_text) {
//            $this->context->smarty->assign([
//                'custom_text' => $custom_text,
//            ]);
//
//            return $this->display(__FILE__, 'views/templates/front/product_extra_content.tpl');
//        }
//
//        return '';

         //OPCION B) INSTANCIAR UN TAB
        //esta mostrando contenido en el front (tab)
        if ($custom_text) {
            $array = array();
            $array[] = (new PrestaShop\PrestaShop\Core\Product\ProductExtraContent())
                ->setTitle($this->l('Custom text','customproducttext'))
                ->setContent($custom_text);
            return $array;
        }
        return '';
    }

    public function hookActionProductUpdate($params)
    {
        $id_product = (int) $params['id_product'];
        $custom_text = Tools::getValue('custom_text');

        Db::getInstance()->execute('REPLACE INTO ' . _DB_PREFIX_ . 'custom_product_text (id_product, custom_text) VALUES (' . (int) $id_product . ', \'' . pSQL($custom_text) . '\')');
    }


}
