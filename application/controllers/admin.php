<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once('BaseCrud.php');
class Admin extends BaseCrud{
  
  var $modelname = 'admin'; //Nome da model sem o "_model"
  var $titulo = 'Administrador';
  var $campos_busca = 'admLogin'; //Campos para filtragem
  var $base_url = 'admin';
  var $actions = 'CURD';// C: CREATE; R:READ; U:UPDATE; D:DELETE; P:PRINT
  var $delete_fields = '';
  var $tabela = 'admLogin,dataAdmin'; //Campos que aparecerão na tabela de listagem

   public function _filter_pre_save(&$data) {
       // $this->load->library('encrypt');
        //$data['admSenha'] = $this->encrypt->encode($data['admSenha']);
        $data['admSenha'] = $data['admSenha'];
    }

    public function _filter_pre_form(&$data) {
        if (isset($data[0]['values']['admSenha'])) {
            $this->load->library('encrypt');
            $data[0]['values']['admSenha'] = $this->encrypt->decode($data[0]['values']['admSenha']);
        }
    }

   public function _filter_pre_read($data) {
    $this->model->fields['dataAdmin'] = array(
      'label' => 'Data',
      'type' => 'text',
      'class' => 'date',
    );

      foreach ($data as $item) {
        $dados = explode(' ', $item->dataAdmin);
        $item->dataAdmin = formata_data($dados[0]);
        $item->hora = $dados[1];
      }
   }

  
}