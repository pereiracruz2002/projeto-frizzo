<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_model extends My_Model{
	var $id_col="admId";

	var $fields= array(
    "admLogin" => array("type" => "text",
									  "label" => "Login",
									  "class"=> "",
									  "rules" => ""
	                  ),
    "admSenha" => array("type" => "password",
									  "label" => "Senha",
									  "class"=> "",
									  "rules" => ""
	                  ),
     "admAtivo" => array('type' => 'select',
                         'label' => 'Ativo',
                         'class' => '',
                         'values' => array('Ativo' => 'Ativo', 
                                           'Inativo' => 'Inativo'
                                           ),
                        ),
   );
}