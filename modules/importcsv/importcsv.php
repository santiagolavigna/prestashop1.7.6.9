<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class ImportCsv extends Module
{
    public function __construct()
    {
        $this->name = 'importcsv';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Santiago Lavigna Jara';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->ps_versions_compliancy = [
          "min" => "1.6",
          "max" => _PS_VERSION_
        ];
        parent::__construct();

        $this->displayName = $this->l('Special Import');
        $this->description = $this->l('Module to import products from webimpacto CSV.');
    }

    public function install()
    {
        return parent::install() && $this->registerHook('moduleRoutes') && $this->installTab();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallTab();
    }

    public function installTab()
    {
        $parentTab = new Tab();
        $parentTab->active = 1;
        $parentTab->class_name = 'AdminCustomModules';
        $parentTab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $parentTab->name[$lang['id_lang']] = 'Custom Modules';
        }
        $parentTab->id_parent = 0;
        $parentTab->module = $this->name;

        $parentTab->add();

        $childTab = new Tab();
        $childTab->active = 1;
        $childTab->class_name = 'AdminImportCsv'; // Controller AdminImportCsvController.php
        $childTab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $childTab->name[$lang['id_lang']] = 'Special Import';
        }
        $childTab->id_parent = (int)$parentTab->id;
        $childTab->module = $this->name;
        $childTab->add();

        return true;
    }

    public function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminImportCsv');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }
        return true;
    }
}
