<?php

class AdminImportCsvController extends ModuleAdminController
{
    private $id_lang;
    private $id_country;
    private $error_message;

    public function __construct()
    {
        $this->id_lang = $this->context->language->id ?? Configuration::get('PS_LANG_DEFAULT');
        $this->id_country = $this->context->country->id ?? Configuration::get('PS_COUNTRY_DEFAULT');
        parent::__construct();
    }

    public function initContent()
    {
        parent::initContent();

        $css_path = $this->module->getLocalPath() . 'views/css/style.css';
        $this->context->controller->addCSS($css_path, 'all');

        $js_path = $this->module->getLocalPath() . 'views/js/';
        $this->context->controller->addJS($js_path . 'importcsv.js');

        $form_action = $this->context->link->getAdminLink('AdminImportCsv');
        $this->context->smarty->assign(array(
            'form_action' => $form_action,
        ));

        $this->setTemplate('import_form.tpl');
    }

    public function postProcess()
    {
        $this->context->smarty->assign('error_message', "");
        $this->context->smarty->assign('success_message', "");

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            try {
                $csvFile = $_FILES['fileInput']['tmp_name'];

                if(!$csvFile){
                    throw new Exception("You must to select a file.");
                }

                if (($handle = fopen($csvFile, 'r')) !== false) {

                    fgetcsv($handle);
                    while (($data = fgetcsv($handle, 1000, ',')) !== false) {

                        [$productName, $reference, $ean13, $costPrice, $salePrice, $taxPercentage, $quantity, $categories, $brand] = $data;
                        $costPrice = (float) $costPrice;
                        $salePrice = (float) $salePrice;
                        $taxPercentage = (int) $taxPercentage;
                        $quantity = (int) $quantity;
                        $categories = explode(';', $categories);
                        $categoriesIds = array();

                        foreach ($categories as $categoryName) {
                            $category = Category::searchByName($this->id_lang, $categoryName);
                            if (!$category || (strcmp($categoryName, $category[0]['name']) !== 0)) {
                                $category = new Category();
                                $category->name = array($this->id_lang => $categoryName);
                                $category->link_rewrite = array($this->id_lang => Tools::link_rewrite($categoryName));
                                $category->active = true;
                                $category->add();
                                $categoriesIds[] = $category->id;
                            }else{
                                $categoriesIds[] = $category[0]['id_category'];
                            }
                        }

                        $manufacturerName = $brand;
                        $manufacturerId = Manufacturer::getIdByName($manufacturerName);
                        if (!$manufacturerId) {
                            $manufacturer = new Manufacturer();
                            $manufacturer->name = $manufacturerName;
                            $manufacturer->active = true;
                            $manufacturer->add();
                            $manufacturerId = $manufacturer->id;
                        }

                        $taxesArr = TaxRulesGroup::getAssociatedTaxRatesByIdCountry($this->id_country);
                        $taxId = $this->getIdTaxRoulesGroupByRate($taxesArr,$taxPercentage);

                        $taxConverted = (float) $taxPercentage / 100;
                        $price = (float) ($salePrice / (1 + ($taxConverted)));

                        $product = new Product();
                        $product->name = array((int) Configuration::get('PS_LANG_DEFAULT') => $productName);
                        $product->reference = $reference;
                        $product->ean13 = $ean13;
                        $product->price = number_format($price,6);
                        $product->wholesale_price = $costPrice;
                        $product->id_tax_rules_group = $taxId;
                        $product->quantity = 0;
                        $product->id_manufacturer = $manufacturerId;

                        if($product->add()){
                            $product->updateCategories($categoriesIds);
                            StockAvailable::setQuantity($product->id,null,$quantity);
                        }
                    }

                    fclose($handle);
                }

            } catch (Exception $e) {
                $this->error_message = 'Error: ' . $e->getMessage();
            }

            if (!empty($this->error_message)) {
                $this->context->smarty->assign('error_message', $this->error_message);
            }else{
                $this->context->smarty->assign('success_message', "All products has been added successfully");
            }
        }
        //Tools::redirectAdmin($this->context->link->getAdminLink('AdminImportCsv'));
    }

    private function getIdTaxRoulesGroupByRate($taxesArray,$rate){
        foreach ($taxesArray as $key => $value) {
            if ($value == $rate) {
                return $key;
                break;
            }
        }
        throw new Exception("Cannot find tax rate: " . $rate);
    }
}
