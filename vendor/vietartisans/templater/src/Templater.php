<?php
/**
 * Class templater engine
 * This class will use twig as default and do frequently works for us.
 * 
 * Text Domain: va-templater
 * 
 * Usage:
 *  $template = new VA\Templater($path);
 */
namespace VA;

use \Twig_Loader_Filesystem;
use \Twig_Environment;
use Philo\Blade\Blade;

class Templater {
    
    /**
     * Template adapter, it would be twig or any php template lib.
     * 
     * @var     $adapter    object               Template Engine object
     */
    private $adapter;
    
    /**
     * Template adapter, it would be twig or any php template lib.
     * 
     * @var     $adapter    object               Template Engine object
     */
    private $dir_path;
    
    /**
     * Template adapter name, it would be twig or any php template lib.
     * 
     * @var     $adapter    object               Template Engine object
     */
    private $adapter_name;
    
    /**
     * Initialize function
     *  - Load twig as default engine
     * 
     * @var     $dir_path       string            Path to template folder.
     * @return  void
     */
    public function __construct($dir_path, $adapter_name = 'twig') {
        $this->adapter_name = $adapter_name;
        $this->dir_path = $dir_path;
        $this->loadAdapter($adapter_name);
    }
    
    /**
     * Load Twig as default adapter
     * 
     * @return  Twig_Environment
     */
    public function loadTwig() {
        if(!$this->adapter) {
            $loader             = new Twig_Loader_Filesystem($this->dir_path);
            $this->adapter      = new Twig_Environment($loader, array(
                
            ));
        }
        return $this->adapter;
    }
    
    /**
     * Load Blade
     * 
     * @return Blade
     */
    public function loadBlade() {
        $this->adapter = new Blade($this->dir_path,'/tmp');//second parameter is where cache view is located.
        return $this->adapter;
    }
    
    /**
     * Load adapter, currently we are using 2 adapters: twig and blade
     * 
     * @var     $adapter_name       string            Path to template folder.
     * @return  Twig_Environment || Blade
     */
    public function loadAdapter($adapter_name) {
        if(!$this->adapter) { //
            $method_name = 'load'.ucfirst($adapter_name);
            if(method_exists($this, $method_name)) {
                $this->$method_name();
            }else{
                exit;
                //throw new \Exception(__("Could not load adapter $adapter_name", "va-templater"));
            }
        }
        return $this->adapter;
    }
    
    /**
     * Alias for engine render function
     * 
     * @var     $template       string              Template file name (e.g. test.php, folder/test.phtml...)
     * @var     $data           array               Data array. E.g. ['list'    =>  $list]
     * 
     * @return                  string              Rendered HTML/text
     */
    public function render($template, $data = []) {
        if($this->adapter_name == 'twig')
        {
            return $this->adapter->render($template, $data);
        }
        elseif($this->adapter_name == 'blade')
        {
            return $this->adapter->view()->make($template,$data)->render();
        }
    }
    
    /**
     * Setup Wordpress themes support. 
     * The template engine will find template file in theme folder before find it in plugin/application folder
     * 1. /wp-content/themes/{theme-name}/templates/{application-name}/
     * 2. /path/to/application/
     * 
     * @var     $app_name       string              Your application name, should be lowercase, letters only.
     * 
     * @return                  object              \VA\Templater\Engine
     */
    public function setWordPressThemeSupport($app_name) {
        // Path to templates folder in wordpress theme
        $theme_template_path = get_template_directory() . '/templates/'. $app_name;
        
        // Create folder path if it does not exists.
        if(!file_exists($theme_template_path)) {
            wp_mkdir_p($theme_template_path);
        }
        
        // Preapare template folders
        $template_folders = [
			$theme_template_path,   // Load this path first
			$this->dir_path         // Load this path second
		];
		$loader = new \Twig_Loader_Filesystem($template_folders);
		$this->adapter->setLoader($loader);
		return $this;
    }
    
}