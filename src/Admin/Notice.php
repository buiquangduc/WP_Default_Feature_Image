<?php
namespace WPDFI\Admin;
/**
 * This class handle all notice stuffs in admin dashboard of this plugin
 *
 * @author Duc Bui Quang <ducbuiquangxd@gmail.com>
 * @since 1.0.0
 */
use WPDFI\Traits\Singleton;

final class Notice 
{
    use Singleton;

    /**
     * @traitDoc
     */
    public function initializes() 
    {   
        //
    }

    /**
     * Type of notice, it can be 'success', 'error', 'warning', 'info'.
     */
    private $type;
        
    /**
     * Message of the notice
     */
    private $message;
    
    /**
     * Add new notice to the admin dashboard.
     *
     * @param string $noticeMessage
     * @param string $noticeType
     * @since 1.0.0
     * @return void
     */
    public function add($noticeMessage, $noticeType = 'info') 
    {
        $this->message = $noticeMessage;
        $this->type = $noticeType;
        
        \add_action( 'admin_notices', [$this, 'display']);
    }
    
    /**
     * Display notice template to the admin dashboard.
     * 
     * @since 1.0.0
     * @return \VA\Templater
     */
    public function display() 
    {
        if(!$this->message) return;
        
        echo \wpdfi()->templater->render('admin/notice', [
            'classes'   => $this->get_classes(),
            'message'   => __($this->message, 'wpdfi')
        ]);
    }
    
    /**
     * Get all the classes of the notice
     * 
     * @since 1.0.0
     * @return string
     */
    private function get_classes() 
    {
        return "notice notice-{$this->get_type()} is-dismissible";
    }
    
    /**
     * Get the type of the notice
     *
     * @since 1.0.0
     * @return string
     */ 
    private function get_type()
    {
        return $this->type ? $this->type : $this->get_default_type();
    }
    
    /**
     * Get default type of the notice
     *
     * @since 1.0.0
     * @return string
     */
    private function get_default_type()
    {
        return 'info';
    }
}