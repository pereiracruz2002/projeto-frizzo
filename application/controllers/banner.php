<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once('BaseCrud.php');
class Banner extends BaseCrud{
  
  var $modelname = 'banner'; //Nome da model sem o "_model"
  var $titulo = 'Banners';
  var $campos_busca = 'nome'; //Campos para filtragem
  var $base_url = 'banner';
  var $actions = 'CURD';// C: CREATE; R:READ; U:UPDATE; D:DELETE; P:PRINT
  var $delete_fields = '';
  var $tabela = 'nome,link'; //Campos que aparecerão na tabela de listagem
  var $upload = "";

  public function upload_check(){
    $config['upload_path'] = FCPATH.'static/uploads/';
    $config['allowed_types'] = 'gif|jpg|png';
    $config['max_size'] = '10000';
    $config['max_width']  = '600';
    $config['max_height']  = '855';

    if($_FILES['src']['name']){
      $this->load->library('upload', $config);
      if(!$this->upload->do_upload('src')){
        $this->form_validation->set_message('upload_check', $this->upload->display_errors());
        return false;
      }else{
        $data = $this->upload->data();
        $this->upload = $data['file_name'];
        //return $data['file_name'];
      }
    }
  }

  function _filter_pre_save(&$_data){
    if($this->upload)
      $_data['src'] = $this->upload;
  }
}