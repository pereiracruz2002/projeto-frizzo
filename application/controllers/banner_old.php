<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once('BaseCrud.php');

class Banner extends My_Controller
{

  function __construct() {
    parent::__construct();
    $this->data['title'] = 'Admin Veridiana - Banner';
    $this->data['tituloMenu'] = 'Banner';
  }

  public function index(){
    $this->load->model('banner_model', 'banner');

    $this->data['collectionBanners'] = $this->banner->get_where(array())->result();

    $this->load->view('include/html_header', $this->data);
    $this->load->view('include/menu', $this->data);
    $this->load->view('v_lista_banner', $this->data);
    $this->load->view('include/html_footer', $this->data);
  }

  public function novo(){
    $this->load->model('banner_model', 'banner');
    $this->data['banner_form'] = $this->banner->form('nome', 'descricao', 'link', 'ativo');

    $this->load->view('include/html_header', $this->data);
    $this->load->view('include/menu', $this->data);
    $this->load->view('v_novo_banner', $this->data);
    $this->load->view('include/html_footer', $this->data);
  }


  public function editar($banner_id){
    $this->load->model('banner_model', 'banner');
    $this->load->model('localizacao_model', 'localizacao');

    $this->load->model('categoria_model', 'categoria');
    $this->load->model('produto_model', 'produto');

    if($this->input->posts()){
      $post = $this->input->posts();

      if($this->banner->validar()) {
        $dadosBanner['nome'] = $post['nome'];
        $dadosBanner['descricao'] = $post['descricao'];
        $dadosBanner['link'] = $post['link'];
        $dadosBanner['ativo'] = $post['ativo'];
        $dadosBanner['banner_id'] = $banner_id;

        if($_FILES['src']['name']){
          $imagem_atual =$this->banner->get_where(array('banner_id' => $banner_id))->row();
          if(file_exists($imagem_atual->src))
            unlink($imagem_atual->src);

          $dadosBanner['src']=BANNER_URL.$this->upload_check();
        }
          
          $this->banner->save($dadosBanner);

        $data = array('pagina_id' => $post['pagina']);
        $this->db->where('banner_id', $banner_id);
        $this->db->update('banner_pagina', $data); 

        $this->db->where('banner_id', $banner_id);
        $this->db->delete('banner_imagem'); 

        $localizacao_id = $post['localizacao_id'];
        if($post['pagina']<>0){
          $uri = $post['uri'];
          foreach($post['uri'] as $uri){
            $this->banner->atualizarPosicao($banner_id,$localizacao_id,$uri);
          }
        }else{
            $this->banner->atualizarPosicao($banner_id,$localizacao_id);
        }
          
      }
      redirect('banner');

    }

    $banner = $this->banner->get($banner_id)->row();

    $this->data['banner_id'] = $banner_id;
    preenche_form($this->banner->fields, $banner);

    $this->data['banner_form'] = $this->banner->form('nome', 'descricao', 'link', 'ativo');

     $this->db->select('pagina_id')
              ->join('banner', 'banner.banner_id=banner_pagina.banner_id')
              ->where('banner.banner_id ="'.$banner_id.'"');
     $this->data['collectionPagina'] = $this->db->get('banner_pagina')->row(); 

      $this->db->select('localizacao.*,banner_imagem.*')
                ->join('localizacao', 'localizacao.localizacao_id=banner_imagem.localizacao_id')
                ->where('banner_imagem.banner_id',$banner_id);
      $this->data['collectionLocal'] = $this->db->get('banner_imagem')->result();

    $this->db->select('categoria.*,banner_cat_prod.*')
                ->join('categoria', 'categoria.categoria_id=banner_cat_prod.categoria_id')
                ->where('banner_cat_prod.banner_imagem_id',$banner_id);
    $this->data['collectionCategoria'] = $this->db->get('banner_cat_prod')->result();

    $this->db->select('produto.*,banner_cat_prod.*')
                ->join('produto', 'produto.produto_id=banner_cat_prod.produto_id')
                ->where('banner_cat_prod.banner_imagem_id',$banner_id);
    $this->data['collectionProduto'] = $this->db->get('banner_cat_prod')->result();

    $bannersinfo = $this->banner->get_detalhesImagens($banner_id);

    $this->data['localizacao_id']= array();
    $this->data['uri']=array();
    foreach($bannersinfo as $info){
      $this->data['localizacao_id'][].= $info->localizacao_id;
    }
    if(!$this->input->is_ajax_request()){
      $this->load->view('include/html_header', $this->data);
      $this->load->view('include/menu', $this->data);
    }
    $this->load->view('v_editar_banner', $this->data);
    $this->load->view('include/html_footer', $this->data);
  }

  public function editar_posicao($banner_id){
     $this->load->model('banner_model', 'banner');
    $this->load->model('localizacao_model', 'localizacao');

    $this->load->model('categoria_model', 'categoria');
    $this->load->model('produto_model', 'produto');
     $this->db->select('pagina_id')
              ->join('banner', 'banner.banner_id=banner_pagina.banner_id')
              ->where('banner.banner_id ="'.$banner_id.'"');
     $this->data['collectionPagina'] = $this->db->get('banner_pagina')->row(); 


      $this->db->select('localizacao.*,banner_imagem.*')
                ->join('localizacao', 'localizacao.localizacao_id=banner_imagem.localizacao_id')
                ->where('banner_imagem.banner_id',$banner_id)
                ->group_by('banner_imagem.localizacao_id');
      $this->data['collectionLocal'] = $this->db->get('banner_imagem')->result();

    $this->db->select('categoria.*,banner_cat_prod.*')
                ->join('categoria', 'categoria.categoria_id=banner_cat_prod.categoria_id')
                ->where('banner_cat_prod.banner_imagem_id',$banner_id);
    $this->data['collectionCategoria'] = $this->db->get('banner_cat_prod')->result();

    $this->db->select('produto.*,banner_cat_prod.*')
                ->join('produto', 'produto.produto_id=banner_cat_prod.produto_id')
                ->where('banner_cat_prod.banner_imagem_id',$banner_id);
    $this->data['collectionProduto'] = $this->db->get('banner_cat_prod')->result();

     $bannersinfo = $this->banner->get_detalhesImagens($banner_id);

    $bannersinfo = $this->banner->get_detalhesImagens($banner_id);
    $this->load->view('v_editar_banner_imagem',$this->data);
  }

  public function salvar(){
    $this->load->model('banner_model', 'banner');

    foreach($this->banner->fields as $key => $val){
      $dadosBanner[$key] = $this->input->post($key);
    }

    
    $banner_id = $this->banner->save($dadosBanner);

    redirect('banner/editar/'.$banner_id);
  }




  public function associar_pagina($banner_id){
    $post = $this->input->posts();
    $nome = $post['nome'];

    $this->db->select('pagina_banner.*');
    $this->db->like('pagina_banner_nome', $nome); 

    $this->db->where("pagina_banner_id NOT IN (SELECT pagina_id FROM banner_pagina WHERE banner_id = {$banner_id})", "", false);


    $this->data['paginas'] = $this->db->get('pagina_banner')->result();
    $this->load->view('banner_paginas', $this->data);
  }

  public function associar_produto($banner_id){
    $this->load->model('produto_model','produto');
    $post = $this->input->posts();
    $nome = $post['nome'];
    $this->produto->like('nome', $nome); 
    $where['ativo'] = 1;
    $this->db->where("produto_id NOT IN (SELECT produto_id FROM banner_cat_prod WHERE banner_imagem_id = {$banner_id})", "", false);
    $this->data['produtos'] =$this->produto->get_where($where)->result();
    $this->load->view('banner_produtos', $this->data);
  }

  public function associar_categoria($banner_id){
    $this->load->model('categoria_model','categorias');
    $post = $this->input->posts();
    $nome = $post['nome'];
    $this->categorias->like('nome', $nome); 
    $where['ativo'] = 1;
    $this->db->where("categoria_id NOT IN (SELECT categoria_id FROM banner_cat_prod WHERE banner_imagem_id = {$banner_id})", "", false);
    $this->data['categorias'] =$this->categorias->get_where($where)->result();
    $this->load->view('banner_categoria', $this->data);
  }

  public function associar_posicao($banner_id){
    $this->load->model('localizacao_model', 'localizacao');

    $result= $this->db->select('banner_pagina.*')
                      ->where('banner_id',$banner_id)
                      ->get('banner_pagina')
                      ->row();

    $post = $this->input->posts();
    $nome = $post['nome'];
    $this->localizacao->like('nome', $nome); 

    $where['ativo'] = 1;
    $this->db->where("localizacao_id NOT IN (SELECT localizacao_id FROM banner_imagem WHERE banner_id = {$banner_id})", "", false);

    $this->data['posicao'] = $this->localizacao->get_where(array('ativo' => '1', 'pagina'=> $result->pagina_id))->result();
    $this->load->view('banner_posicao', $this->data);
  }

  public function relacionarPagina($banner_id, $pagina_id) 
  {
    $this->load->model('banner_model', 'banner');


    $cadastrado= $this->db->select('banner_pagina.*')
                      ->where('banner_id',$banner_id)
                      ->get('banner_pagina')
                      ->row();



    if($cadastrado){
       $this->db->where('banner_id', $banner_id);
       $this->db->delete('banner_pagina'); 

       $this->db->where('banner_id', $banner_id);
       $this->db->delete('banner_imagem'); 

       //if($cadastrado->pagina_id<1){
          $this->db->where('banner_cat_prod.banner_imagem_id', $banner_id);
          $this->db->delete('banner_cat_prod'); 
       //}
    }

    $pagina_id=$this->banner->adicionarPaginas($banner_id, $pagina_id);
    if($banner_id){

      $this->db->select('pagina_banner.*,banner_pagina.*')
                ->join('banner_pagina', 'banner_pagina.pagina_id=pagina_banner.pagina_banner_id')
                ->where('banner_pagina.banner_id',$banner_id);
      $relacionado = $this->db->get('pagina_banner')->row();


      $html = '<tr>
        <td>'.$relacionado->pagina_banner_nome.'</td>
        <td width="110" class="text-center"></td>
      </tr>';
    }
    $this->output->set_output($html);
  }

  public function relacionarPosicao($banner_id,$localizacao)
  {
    $this->load->model('banner_model', 'banner');

    $catsprods= $this->db->select('banner_cat_prod.*')
                      ->where('banner_imagem_id',$banner_id)
                      ->get('banner_cat_prod')
                      ->result();

    if($catsprods)
      $this->banner->adicionarPosicao($banner_id,$localizacao,$catsprods);
    else
       $this->banner->adicionarPosicao($banner_id,$localizacao);

     $this->db->select('localizacao.nome,banner_imagem.banner_imagem_id,banner_imagem.localizacao_id')
                ->join('localizacao', 'localizacao.localizacao_id=banner_imagem.localizacao_id')
                ->where('banner_imagem.banner_id',$banner_id)
                ->group_by('banner_imagem.localizacao_id')
                ->order_by("banner_imagem.banner_imagem_id", "desc");
      $relacionado = $this->db->get('banner_imagem')->row();

      //echo $this->db->last_query();
      //exit();

     $html = '<tr>
        <td>'.$relacionado->nome.'</td>
        <td width="110" class="text-center"><a href="'.site_url('banner/removerRelacaoPosicao/'.$banner_id.'/'.$relacionado->localizacao_id).'" class="btn btn-mini btn-danger remover">Remover Relação</a></td>
      </tr>';

    $this->output->set_output($html);
  }

  public function relacionarCategoria($banner_id,$url)
  {
    $this->load->model('categoria_model','categorias');
     $arrayDados = array(
        'banner_imagem_id' => $banner_id,
        'categoria_id' => $url
      );
   $id = $this->db->insert('banner_cat_prod', $arrayDados);

     $this->db->select('categoria.*,banner_cat_prod.*')
                ->join('banner_cat_prod', 'banner_cat_prod.categoria_id=categoria.categoria_id')
                ->where('banner_cat_prod.banner_imagem_id',$banner_id)
                ->order_by("banner_cat_prod.banner_cat_prod_id", "desc"); 
    $relacionado = $this->db->get('categoria')->row();


   
     $html = '<tr>
        <td>'.$relacionado->nome.'</td>
        <td width="110" class="text-center"><a href="'.site_url('banner/removerRelacaoCategoria/'.$relacionado->categoria_id."/".$banner_id).'" class="btn btn-mini btn-danger remover">Remover Relação</a></td>
      </tr>';


    $this->output->set_output($html);
  }

  public function relacionarProduto($banner_id,$url)
  {
    $this->load->model('produto_model','produto');
     $arrayDados = array(
        'banner_imagem_id' => $banner_id,
        'produto_id' => $url
      );
   $id = $this->db->insert('banner_cat_prod', $arrayDados);

     $this->db->select('produto.*,banner_cat_prod.*')
                ->join('banner_cat_prod', 'banner_cat_prod.produto_id=produto.produto_id')
                ->where('banner_cat_prod.banner_imagem_id',$banner_id)
                ->order_by("banner_cat_prod.banner_cat_prod_id", "desc"); 
    $relacionado = $this->db->get('produto')->row();


   
     $html = '<tr>
        <td>'.$relacionado->nome.'</td>
        <td width="110" class="text-center"><a href="'.site_url('banner/removerRelacaoProduto/'.$relacionado->produto_id."/".$banner_id).'" class="btn btn-mini btn-danger remover">Remover Relação</a></td>
      </tr>';


    $this->output->set_output($html);
  }

  public function deletar($banner_id){
    $this->load->model('banner_model', 'banner');

    if($banner_id != 1){
      $this->banner->delete($banner_id);
      
      echo "ok";  
    }
  }

  public function removerImagem($banner_id) 
  {
    $this->load->model('banner_model','banner');
    $imagens = $this->banner->get($banner_id)->row();


    if(file_exists(FCPATH.'../imagem_banner/'.$imagens->src))
      unlink(FCPATH.'../imagem_banner/'.$imagens->src);

    $data = array('src' => '');
        $this->db->where('banner_id', $banner_id);
        $this->db->update('banner', $data); 
        $this->output->set_output('ok');
  }

  public function removerRelacaoPosicao($banner_id,$localizacao_id){
    $this->db->delete('banner_imagem', array('banner_id' => $banner_id,'localizacao_id'=>$localizacao_id));
    $this->output->set_output('ok');
  }

  public function removerRelacaoCategoria($categoria_id,$banner_id){
    $this->db->delete('banner_cat_prod', array('banner_imagem_id' => $banner_id,'categoria_id'=>$categoria_id));
    $this->output->set_output('ok');
  }

  public function removerRelacaoProduto($produto_id,$banner_id){
     $this->db->delete('banner_cat_prod', array('banner_imagem_id' => $banner_id,'produto_id'=>$produto_id));
    $this->output->set_output('ok');
  }

  public function imagem($banner_id)
  {
    $this->load->model('banner_model','banner');
    $this->data['imagens'] =$this->banner->get_where(array('banner_id' => $banner_id))->result();
    $this->load->view('v_banner_imagem', $this->data);
  }

  public function uploadImagem($banner_id) 
  {
    $this->load->model('banner_model','banner');
    $save['src'] = $this->upload_check();
    if($save['src']){
      $data = array('src' => $save['src']);
      $this->db->where('banner_id', $banner_id);
      $this->db->update('banner', $data); 
      $this->data['banner_id'] = $banner_id;
      $this->data['src'] = $save['src'];
    }
    $this->load->view('upload_banner', $this->data);
  }


  public function upload_check(){
    $config['upload_path'] = FCPATH.'../imagem_banner/';
    $config['allowed_types'] = 'gif|jpg|png';
    $config['max_size'] = '10000';
    $config['max_width']  = '1920';
    $config['max_height']  = '1080';

    if($_FILES['arquivo']['name']){
      $this->load->library('upload', $config);
      if(!$this->upload->do_upload('arquivo')){
        $this->form_validation->set_message('upload_check', $this->upload->display_errors());
        return false;
      }else{
        $data = $this->upload->data();
        //$this->upload = $data['file_name'];
        return $data['file_name'];
      }
    }
  }


}
