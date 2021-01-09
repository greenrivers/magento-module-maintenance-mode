<?php
/**
 * @author Greenrivers Team
 * @copyright Copyright (c) 2021 Greenrivers
 * @package Greenrivers_MaintenanceMode
 */

namespace Greenrivers\MaintenanceMode\pub\errors;

use Greenrivers\MaintenanceMode\Helper\Config;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Escaper;
use Magento\Framework\Serialize\Serializer\Json;
use SimpleXMLElement;
use stdClass;

class Processor
{
    const MAGE_ERRORS_LOCAL_XML = 'local.xml';
    const MAGE_ERRORS_DESIGN_XML = 'design.xml';
    const DEFAULT_SKIN = 'default';

    /** @var string */
    public $pageTitle;

    /** @var string */
    public $baseUrl;

    /** @var string */
    protected $_scriptName;

    /** @var bool */
    protected $_root;

    /** @var stdClass */
    protected $_config;

    /** @var Http */
    protected $_response;

    /** @var Json */
    private $serializer;

    /** @var Escaper */
    private $escaper;

    /** @var Config */
    private $config;

    /**
     * Processor constructor.
     * @param Http $response
     * @param Json|null $serializer
     * @param Escaper|null $escaper
     */
    public function __construct(Http $response, Json $serializer = null, Escaper $escaper = null)
    {
        $this->_response = $response;
        $this->_errorDir = __DIR__ . '/';
        $this->serializer = $serializer ?: ObjectManager::getInstance()->get(Json::class);
        $this->escaper = $escaper ?: ObjectManager::getInstance()->get(Escaper::class);

        if (!empty($_SERVER['SCRIPT_NAME'])) {
            if (in_array(basename($_SERVER['SCRIPT_NAME'], '.php'), ['404', '503', 'report'])) {
                $this->_scriptName = dirname($_SERVER['SCRIPT_NAME']);
            } else {
                $this->_scriptName = $_SERVER['SCRIPT_NAME'];
            }
        }

        $this->_indexDir = $this->_getIndexDir();
        $this->_root = is_dir($this->_indexDir . 'app');

        $this->_prepareConfig();
        if (isset($_GET['skin'])) {
            $this->_setSkin($_GET['skin']);
        }

        $this->config = ObjectManager::getInstance()->get(Config::class);
    }

    /**
     * @return Http
     */
    public function process503(): Http
    {
        $this->pageTitle = 'Error 503: Service Unavailable';
        $this->_response->setHttpResponseCode(503);
        $this->_response->setBody($this->_renderPage('503.phtml'));
        return $this->_response;
    }

    /**
     * @return string
     */
    public function getViewFileUrl(): string
    {
        //The url needs to be updated base on Document root path.
        return $this->getBaseUrl() .
            str_replace(
                str_replace('\\', '/', $this->_indexDir),
                '',
                str_replace('\\', '/', $this->_errorDir)
            ) . $this->_config->skin . '/';
    }

    /**
     * @return string
     */
    public function getHostUrl(): string
    {
        /**
         * Define server http host
         */
        $host = $this->resolveHostName();

        $isSecure = (!empty($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] !== 'off')
            || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && ($_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
        $url = ($isSecure ? 'https://' : 'http://') . $host;

        $port = explode(':', $host);
        if (isset($port[1]) && !in_array($port[1], [80, 443])
            && !preg_match('/.*?\:[0-9]+$/', $url)
        ) {
            $url .= ':' . $port[1];
        }
        return $url;
    }

    /**
     * @return string
     */
    private function resolveHostName(): string
    {
        if (!empty($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
        } elseif (!empty($_SERVER['SERVER_NAME'])) {
            $host = $_SERVER['SERVER_NAME'];
        } else {
            $host = 'localhost';
        }
        return $host;
    }

    /**
     * @param bool $param
     * @return string
     */
    public function getBaseUrl($param = false): string
    {
        $path = $this->_scriptName;

        if ($param && !$this->_root) {
            $path = dirname($path);
        }

        $basePath = str_replace('\\', '/', dirname($path));
        return $this->getHostUrl() . ('/' == $basePath ? '' : $basePath) . '/';
    }

    /**
     * @return string
     */
    protected function _getClientIp(): string
    {
        return (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : 'undefined';
    }

    /**
     * @return string
     */
    protected function _getIndexDir(): string
    {
        $documentRoot = '';
        if (!empty($_SERVER['DOCUMENT_ROOT'])) {
            $documentRoot = rtrim(realpath($_SERVER['DOCUMENT_ROOT']), '/');
        }
        return dirname($documentRoot . $this->_scriptName) . '/';
    }

    /**
     * @return void
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _prepareConfig()
    {
        $local = $this->_loadXml(self::MAGE_ERRORS_LOCAL_XML);
        $design = $this->_loadXml(self::MAGE_ERRORS_DESIGN_XML);

        //initial settings
        $config = new stdClass();
        $config->action = '';
        $config->subject = 'Store Debug Information';
        $config->email_address = '';
        $config->trash = 'leave';
        $config->skin = self::DEFAULT_SKIN;

        //combine xml data to one object
        if ($design !== null && (string)$design->skin) {
            $this->_setSkin((string)$design->skin, $config);
        }
        if ($local !== null) {
            if ((string)$local->report->action) {
                $config->action = $local->report->action;
            }
            if ((string)$local->report->subject) {
                $config->subject = $local->report->subject;
            }
            if ((string)$local->report->email_address) {
                $config->email_address = $local->report->email_address;
            }
            if ((string)$local->report->trash) {
                $config->trash = $local->report->trash;
            }
            if ($local->report->dir_nesting_level) {
                $config->dir_nesting_level = (int)$local->report->dir_nesting_level;
            }
            if ((string)$local->skin) {
                $this->_setSkin((string)$local->skin, $config);
            }
        }
        if ((string)$config->email_address == '' && (string)$config->action == 'email') {
            $config->action = '';
        }

        $this->_config = $config;
    }

    /**
     * @param string $xmlFile
     * @return SimpleXMLElement|null
     */
    protected function _loadXml(string $xmlFile): ?SimpleXMLElement
    {
        $configPath = $this->_getFilePath($xmlFile);
        return ($configPath) ? simplexml_load_file($configPath) : null;
    }

    /**
     * @param string $template
     * @return string
     */
    protected function _renderPage(string $template): string
    {
        $baseTemplate = $this->_getTemplatePath('page.phtml');
        $contentTemplate = $this->_getTemplatePath($template);

        $html = '';
        if ($baseTemplate && $contentTemplate) {
            ob_start();
            require_once $baseTemplate;
            $html = ob_get_clean();
        }
        return $html;
    }

    /**
     * @param string $file
     * @param array|null $directories
     * @return string
     */
    protected function _getFilePath(string $file, array $directories = null): string
    {
        if ($directories === null) {
            $directories[] = $this->_errorDir;
        }

        foreach ($directories as $directory) {
            if (file_exists($directory . $file)) {
                return $directory . $file;
            }
        }

        return '';
    }

    /**
     * @param string $template
     * @return string
     */
    protected function _getTemplatePath(string $template): string
    {
        $directories[] = $this->_errorDir . $this->_config->skin . '/';

        if ($this->_config->skin != self::DEFAULT_SKIN) {
            $directories[] = $this->_errorDir . self::DEFAULT_SKIN . '/';
        }

        return $this->_getFilePath($template, $directories);
    }

    /**
     * @param string $value
     * @param stdClass|null $config
     * @return void
     */
    protected function _setSkin(string $value, stdClass $config = null)
    {
        if (preg_match('/^[a-z0-9_]+$/i', $value) && is_dir($this->_errorDir . $value)) {
            if (!$config) {
                if ($this->_config) {
                    $config = $this->_config;
                }
            }
            if ($config) {
                $config->skin = $value;
            }
        }
    }

    /**
     * @return bool
     */
    public function overrideTemplate(): bool
    {
        return $this->config->getEnabledConfig() && $this->config->getCustomPageEnabledConfig();
    }

    /**
     * @return string
     */
    public function getPageContent(): string
    {
        return $this->config->getCustomPageContentConfig();
    }

    /**
     * @return string
     */
    public function getPageStyles(): string
    {
        return $this->config->getCustomPageStylesConfig();
    }
}
