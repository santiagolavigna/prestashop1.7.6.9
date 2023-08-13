<?php

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use GuzzleHttp\Exception\RequestException;
require_once _PS_MODULE_DIR_ . 'weather/classes/WeatherApi.php';

class Weather extends Module implements WidgetInterface
{
    public function __construct($name = null, Context $context = null)
    {
        $this->name = "weather";
        $this->tab = "front_office_features";
        $this->version = "1.0.0";
        $this->author = "Santiago Lavigna Jara";
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->ps_versions_compliancy = [
            "min" => "1.6",
            "max" => _PS_VERSION_
        ];

        parent::__construct();

        $this->displayName = $this->l('Weather');
        $this->description = $this->l('Module to display weather and geolocalization data through customer IP.');
    }


    public function install()
    {
        return parent::install()
            && Configuration::updateValue('WEATHER_API_KEY', '')
            && Configuration::updateValue('WEATHER_IP_DEFAULT', '5.203.211.175');
    }


    public function uninstall()
    {
        return parent::uninstall()
            && Configuration::deleteByName('WEATHER_API_KEY')
            && Configuration::deleteByName('WEATHER_IP_DEFAULT');
    }

    public function renderWidget($hookName, array $configuration)
    {
        if(isset($configuration['display_data_header']) &&  $configuration['display_data_header']=== 'true'){
            $ip = $this->validateAndGetIp(Tools::getRemoteAddr());
            $apiKey = Configuration::get('WEATHER_API_KEY');

            try {
                $weatherApi = new WeatherApi($apiKey);
                $data = $weatherApi->fetchData($ip);

                if ($data === null) {
                     throw new Exception("No response from API, check api key or logs and then try again");
                }
                $this->context->smarty->assign('weatherData', $data);

            } catch (RequestException $e) {
                $this->context->smarty->assign('ip', $ip);
                $this->context->smarty->assign('tooltipMessage', $e->getMessage());
                return $this->fetch('module:weather/views/templates/error.tpl');
            } catch (Exception $e){
                $this->context->smarty->assign('ip', $ip);
                $this->context->smarty->assign('tooltipMessage', $e->getMessage());
                return $this->fetch('module:weather/views/templates/error.tpl');
            }

            return $this->fetch('module:weather/views/templates/weather.tpl');
        }
        return '';
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        return '';
    }

    public function getContent()
    {
        $output = '';

        if (Tools::isSubmit('submitWeatherModule')) {
            $apiKey = Tools::getValue('WEATHER_API_KEY');
            Configuration::updateValue('WEATHER_API_KEY', $apiKey);
            $output .= $this->displayConfirmation($this->l('Settings updated.'));
        }

        $output .= $this->renderForm();

        return $output;
    }

    protected function renderForm()
    {
        $fieldsForm = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('OpenWeatherMap API Key'),
                        'name' => 'WEATHER_API_KEY',
                        'required' => true,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                    'name' => 'submitWeatherModule',
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->fields_value['WEATHER_API_KEY'] = Configuration::get('WEATHER_API_KEY');

        return $helper->generateForm(array($fieldsForm));
    }

    private function validateAndGetIp($ip) {

        $privateIpRanges = array(
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16',
        );

        foreach ($privateIpRanges as $range) {
            list($subnet, $mask) = explode('/', $range);
            $subnet = ip2long($subnet);
            $mask = ~((1 << (32 - $mask)) - 1);
            if ((ip2long($ip) & $mask) === $subnet) {
                return Configuration::get('WEATHER_IP_DEFAULT');
            }
        }

        return $ip;
    }

}
