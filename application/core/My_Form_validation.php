<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_Form_Validation extends CI_Form_Validation {
  
  function valor_unico($value, $id, $model, $where=array()){
    $ci =& get_instance();
    $id_col = $ci->{$model}->id_col;
    $key = array_search($value, $ci->input->posts());
    if(strstr($ci->{$model}->fields[$key]['rules'], 'valor_unico')){
      $where[$key] = $value;
      $result = $ci->{$model}->get_where($where)->row();
      if($result){
        if($result->$id_col == $id)
          return true;
        
        $ci->form_validation->set_message('valor_unico', "O {$ci->$model->fields[$key]['label']} {$value} já existe");
        return false;
      }
    }else{
      return true;
    }
  }
  
}