<?php   
if (!defined('BASEPATH'))exit('No direct script access allowed');

class Main extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->data['title'] = 'Admin Veridiana - Área Administrativa';
        $this->data['tituloMenu'] = 'inicio';
    }

    public function index() {
        $this->load->helper('html');
        $this->load->view('admin/header', $this->data);
        //$this->load->view('admin/menu', $this->data);
        $this->load->view('admin/main', $this->data);
        $this->load->view('admin/footer', $this->data);
    }

}