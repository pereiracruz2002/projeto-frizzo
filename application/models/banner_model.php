<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banner_model extends MY_Model
{
  var $id_col = 'banner_id';

  var $fields = array(
    'nome' => array(
      'type' => 'text',
      'label' => 'Produto',
      'class' => '',
      'rules' => 'required',
      'extra' => array('required' => 'required')
    ),

    'link' => array(
      'type' => 'text',
      'label' => 'Preço',
      'class' => '',
      'rules' => 'required',
      'extra' => array('required' => 'required')
    ),

    'src' => array(
      'type' => 'file',
      'label' => 'Imagem',
      'class' => 'imagem',
      'rules' => 'callback_upload_check',
      'extra' => array('required' => 'required'),
    ),

    'ativo' => array(
      'type' => 'select',
      'label' => 'Status',
      'class' => '',
      'values' => array(1 => 'Ativo', 0 => 'Inativo')
    ),

  );

  public function get_paginas($banner_id){

    return $this->db->get_where('banner_pagina', array('banner_id' => $banner_id));

  }

  public function get_imagens($banner_id){

    $collectionImagens = $this->db->get_where('banner_imagem', array('banner_id' => $banner_id))->result();

    $arrayImagens = array();
    foreach($collectionImagens as $imagem){
      $arrayImagens[$imagem->localizacao_id] = array(
          'nome_banner' => $imagem->nome,
          'src' => $imagem->src,
          'link' => $imagem->link
        );
    }

    return $arrayImagens;
  }

  public function adicionarPaginas($banner_id, $pagina_id){
    $arrayDados = array(
        'banner_id' => $banner_id,
        'pagina_id' => $pagina_id
      );

    $this->db->insert('banner_pagina', $arrayDados);
  }

  public function deletarPaginas($banner_id){
    $arrayDados = array(
        'banner_id' => $banner_id
      );

    $this->db->delete('banner_pagina', $arrayDados);
  }

  public function adicionarImagem($dados){
    $this->db->insert('banner_imagem', $dados); 
  }

  public function deletarImagens($banner_id){
    $arrayDados = array(
        'banner_id' => $banner_id
      );

    $this->db->delete('banner_imagem', $arrayDados); 
  }

  public function adicionarPosicao($banner_id, $localizacao,$catsprods=null){
    if(is_array($catsprods)){
      foreach($catsprods as $catprod){
         $arrayDados = array(
          'banner_id' => $banner_id,
          'banner_cat_prod_id' => $catprod->banner_cat_prod_id,
          'localizacao_id'=>$localizacao
        );
           $this->db->insert('banner_imagem', $arrayDados);
      }
    }else{
        $arrayDados = array(
          'banner_id' => $banner_id,
          'localizacao_id'=>$localizacao
        );
           $this->db->insert('banner_imagem', $arrayDados);
    }

  }


  public function atualizarPosicao($banner_id, $localizacao_id,$uri=null){
    foreach($localizacao_id as $localizacao){
       $arrayDados = array(
        'banner_id' => $banner_id,
        'uri' => $uri,
        'localizacao_id'=>$localizacao
      );
         $this->db->insert('banner_imagem', $arrayDados);
    }

  }

  public function get_detalhesImagens($banner_id){
   $this->db->select('banner_imagem.*,banner_pagina.*')
             ->join('banner_pagina', 'banner_pagina.banner_id=banner.banner_id')
             ->join('banner_imagem', 'banner_imagem.banner_id=banner.banner_id')
             ->where('banner.banner_id ="'.$banner_id.'"');
    return $this->db->get('banner')->result(); 
  }

}