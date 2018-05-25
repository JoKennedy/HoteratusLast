<?php class my404 extends Front_Controller 
{
    public function __construct() 
    {
        parent::__construct(); 
    } 

    public function index() 
    { 
        $this->output->set_status_header('404'); 
       // $data['content'] = 'error_404'; // View name 
        $this->load->view('admin/404');//loading in my template 
    } 
}