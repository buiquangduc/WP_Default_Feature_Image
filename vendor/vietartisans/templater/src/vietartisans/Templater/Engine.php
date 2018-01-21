<?php
/**
 * Class templater engine
 * This class will use twig as default and do frequently works for us.
 * Usage:
 *  $template = new VA\Templater\Engine($path);
 */
namespace VA\Templater;

class Engine {
    
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
     * Initialize function
     *  - Load twig as default engine
     * 
     * @var     $dir_path       string            Path to template folder.
     * @return  void
     */
    public function __construct($dir_path) {
        $this->dir_path = $dir_path;
        $this->loadTwig($dir_path);
    }
    
    /**
     * Load Twig as default adapter
     * 
     * @var     $dir_path       string            Path to template folder.
     * @return  Twig_Environment
     */
    public function loadTwig($dir_path) {
        if(!$this->adapter) {
            $loader             = new \Twig_Loader_Filesystem($dir_path);
            $this->adapter      = new \Twig_Environment($loader, array(
                
            ));
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
    public function render($template, $data) {
        return $this->adapter->render($template, $data);
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
        $template_folders = [
			get_template_directory() . '/templates/'. $app_name,
			$this->dir_path
		];
		$loader = new \Twig_Loader_Filesystem($template_folders);
		$this->adapter->setLoader($loader);
		return $this;
    }
    
}
