<?php
/**
 * Class BaseCrud
 * Versão 1.0
**/
class BaseCrud extends My_Controller {
  var $modelname = ''; /* Nome do model sem o "_model" */
  var $base_url = '';
  var $actions = 'CRUD';
  var $acoes_extras = array(); //array("url" => "methodo do controller", "title" => "texto que aparece", "class" => "classe do link")
  var $acoes_controller = array(); //array("url" => "methodo do controller", "title" => "texto que aparece", "class" => "classe do link")
  var $titulo;
  var $tabela = '';
  var $campos_busca = '';
  var $verificar_login = true;
  var $delete_fields = '';
  var $selects = '*';
  var $joins = false;
	var $group = false;
  var $base_ativo = false; // var $base_ativo = 'ativo';
  var $data = array(); 
  var $models_visualizar = array(); // array("nome da model (sem _model)" => array("titulo" => "Tiulo da visualização", "join" => "tabela.id_tabela=tabela2.id_tabela2"));
  var $fields_groups = false;

  function __construct() {
    parent::__construct();
    $this->_modelname();
  }

  function _modelname() {
    return $this->modelname.'_model';
  }

  function _model() {
    $model = $this->_modelname();
    $this->load->model($model);
    return $this->$model;
  }

  function _call_pre_form (&$model, &$data=array()) {
    if (method_exists($this, '_pre_form'))
      $this->_pre_form($model, $data);
  }

  function _call_pos_form () {
    if (method_exists($this, '_pos_form'))
      $this->_pos_form();
  }

  function _call_in_form () {
    if (method_exists($this, '_in_form'))
      $this->_in_form();
  }  

  function _call_filter_pre_form (&$data) {
    if (method_exists($this, '_filter_pre_form'))
      $this->_filter_pre_form($data);
  }

  function _crud() {
    $crud = $this->actions;
    $crud = str_split($crud, 1);
    return $crud;
  }

  function _call_filter_pre_save (&$data) {
    if (method_exists($this, '_filter_pre_save'))
      $this->_filter_pre_save($data);
  }

  function _call_filter_pos_save (&$data, $id=null) {
    if (method_exists($this, '_filter_pos_save'))
      $this->_filter_pos_save($data, $id);
  }

  function _call_filter_pre_delete ($id) {
    if(strstr($this->actions, 'D')){
      if(method_exists($this, '_filter_pre_delete'))
        return $this->_filter_pre_delete($id);
      else
        return true;
    }else{
      return false;
    }
  }
  
  function _call_filter_pos_delete ($id) {
    if (method_exists($this, '_filter_pos_delete'))
      $this->_filter_pos_delete($id);
  }

  function _call_filter_pre_busca(&$data) {
    if (method_exists($this, '_filter_pre_busca'))
      $this->_filter_pre_busca($data);
  }

  function _call_filter_pos_busca(&$data) {
    if (method_exists($this, '_filter_pos_busca'))
      $this->_filter_pos_busca($data);
  }

  function _call_filter_pre_read (&$data) {
    if (method_exists($this, '_filter_pre_read'))
      $this->_filter_pre_read($data);
  }
  
  function _call_filter_pre_listar (&$where) {
    if (method_exists($this, '_filter_pre_listar'))
      $this->_filter_pre_listar($where);
  }

  function _setVal(&$data, $key, $value) {
    $data[0]['values'][$key] = $value;
  }

  function _call_pre_validate($method) {
    if(!$this->session->userdata('logado'))
      return false;

    if (method_exists($this, '_pre_validate'))
      $this->_pre_validate($method);
  }
  
  function _call_pre_table(){
     if (method_exists($this, '_pre_table'))
       $this->_pre_table();
  }

  function _call_pre_view(&$data){
     if (method_exists($this, '_pre_view'))
       $this->_pre_view($data);
  }

  function _call_filter_pre_visualizar(&$item, &$models){
    if(method_exists($this, '_filter_pre_visualizar'))
      $this->_filter_pre_visualizar($item, $models);
  }
  
  function index(){
    $this->listar();
  }

  function listar() {
    
		$where = array();
		
    if(!$this->session->userdata('logado')){
      redirect('auth');
      exit();
    }

    $this->load->model($this->modelname."_model", 'model');
	
    $config['base_url'] = site_url($this->base_url."/listar/");
    $config['uri_segment'] = 3;
    $config['per_page'] = 10000;
    if( $this->base_ativo ) {
      $where_ativo = array("{$this->model->table}.{$this->base_ativo}" => 'ativo');
    } else {
      $where_ativo = array();
    }
    $busca = $this->input->posts();
    if(!is_numeric($this->uri->segment(3)) and $this->uri->segment(3) != ""){
      $query = base64_decode($this->uri->segment(3));
      $termos = explode("&", $query);
      foreach ($termos as $item) {
        $termo = explode("=", $item);
        if(isset($termo[1]))
          $busca[$termo[0]] = $termo[1];
      }
    }

    if(count($busca) > 0){
      $url = "";
      foreach ($busca as $key => $value) {
        if($value){
          $where[$key] = $value; 
          $this->model->fields[$key]['value'] = $value;
          $url .= $key."=".$value."&";
        }
      }
      $params = url_title(base64_encode($url));
      $config['base_url'] = site_url($this->base_url."/listar/{$params}");
      $config['uri_segment'] = 4;
    }
    $this->_call_filter_pre_listar($where_ativo);
    $order = array($this->model->table.".".$this->model->id_col => "desc");
    
    $results = $this->model->search($where, $this->uri->segment($config['uri_segment']), $config['per_page'], $this->selects, $this->joins,$where_ativo, $order,$this->group);

    $config['total_rows'] = $results['total_rows'];
    $data['total'] = $results['total_rows'];

    $this->_call_filter_pre_read($results['resultados']);
    $data['itens'] = $results['resultados'];

    $this->load->library('pagination');
    $this->pagination->initialize($config);
    $data['paginacao'] = $this->pagination->create_links();
    
    $data['form']="";
    foreach (explode(',', $this->campos_busca) as $item){
      $this->model->fields[trim($item)]['class'] = "";
      $data['form'] .= $this->model->form(trim($item));
    }
    $data['titulo'] = $this->titulo;
    $data['url'] = site_url($this->base_url);
    $data['crud'] = $this->_crud();
    $data['acoes_extras'] = $this->acoes_extras;
    $data['acoes_controller'] = $this->acoes_controller;
    
    $data['campos'] = explode (',', $this->tabela);
    $data['model'] = $this->model;
    $data['c'] =& $this;

    $view = $this->load->view( 'admin/crud/index', array_merge($data, $this->data), true);
    $this->output->set_output(html_compress($view));
  }

  function _try_save(&$data, $method='novo', $id=null) {
    $c =& $this;
    $model = $this->_model();
    $crud = $this->_crud();
    $url = site_url($this->base_url);

    if ($_POST) {

      foreach (array_keys($model->fields) as $k)
        $data[0]['values'][$k] = $this->input->post($k);

      $this->_call_pre_validate($method);

      if ($model->validar()) {
        $_data = array ();
        foreach (array_keys($model->fields) as $item)
          $_data[$item] = $data[0]['values'][$item];
        if ($id) $_data[$model->id_col] = $id;
        $this->_call_filter_pre_save($_data);
        // Salvando

        $id = $model->save($_data);
        $this->_call_filter_pos_save($_data, $id);
        if( gettype($id)!=gettype(1))
          $id = $_data[$model->id_col];
        
          
        if (in_array('U', $crud))
          redirect($this->base_url.'/editar/'.$id.'/ok');
        else
          redirect($this->base_url);
      }
    }
  }

  function novo() {
    if(!$this->session->userdata('logado')){
      redirect('auth');
      exit();
    }

    $this->data['c'] =& $this;
    $this->data['model'] = $this->_model();
    $this->data['crud'] = $this->_crud();
    $this->data['url'] = site_url($this->base_url);
    $this->data['action'] = '/novo';
    $this->data['ok'] = false;
    $this->data['titulo'] = "Cadastrar {$this->titulo}";
    $data = array();
    $this->data['data'] = $data;

    $this->_try_save($data);

    $this->_call_pre_view($data);
    $view = $this->load->view('admin/crud/form' , $this->data, true);
    $this->output->set_output(html_compress($view));
  }

  function editar($id, $ok=null) {
    
    if(!$this->session->userdata('logado')){
      redirect('auth');
    }
    
    $c =& $this;
    $model = $this->_model();
    $crud = $this->_crud();
    
    if(!in_array('U', $crud)){
      redirect($this->base_url);
      exit();
    }
    
    $url = site_url($this->base_url);
    $action = '/editar/'.$id;
    $titulo = 'Editar';
    $vars = array();

    $r = $model->get($id)->row();
    if (!$r) {
      redirect($this->base_url);
    }

    $data = array (array('values' => (array) $r));

    $this->_call_pre_view($vars);

    $this->_try_save($data, 'update', $id);

    $dados = compact( 'titulo', 'url', 'model', 'data', 'c', 'action', 'ok', 'crud','vars');
    $dados_view = array_merge($dados, $this->data);
    $view = $this->load->view('admin/crud/form', $dados_view, true);
    $this->output->set_output(html_compress($view));
  }

  function deletar($id) {
    if(!$this->session->userdata('logado')){
      redirect('auth');
      exit();
    }
    
    if(!in_array('D', $this->_crud())){
      redirect($this->base_url);
    }
    
    $model = $this->_model();
    if($this->_call_filter_pre_delete($id)){
      $model->delete( $id );
      $this->_call_filter_pos_delete($id);
      print "ok";
    }else{
      print "erro ao deletar registro";
    }
    
  }
  
  public function visualizar($id){
    if(!$this->session->userdata('logado')){
      redirect('auth');
      exit();
    }
    
    if(!in_array('P', $this->_crud())){
      redirect($this->base_url);
    }
    
  	$this->load->model($this->modelname."_model", $this->modelname);
  	foreach($this->models_visualizar as $kModel => $titulo)
  		$this->load->model("{$kModel}_model", $kModel);
  	
  	if(method_exists($this, '_pre_visualizar'))
      $this->_pre_visualizar();
      
  	$this->data['models'] = array_merge(array($this->modelname => array('titulo' => $this->titulo)), $this->models_visualizar);
  	
  	$select = array();
  	foreach($this->data['models'] as $model => $values){
  		foreach($this->{$model}->fields as $k => $v){
  			$this->{$model}->fields[$this->{$model}->table.'.'.$k] = $v;
  			unset($this->{$model}->fields[$k]);
  			$select[] = $this->{$model}->table.'.'.$k .' as '.$this->{$model}->table.'_'.$k;
  		}
  	}
  	$this->db->select(implode(',',$select));
  	if(count($this->models_visualizar) > 0){
	  	foreach($this->models_visualizar as $tabela => $compara){
	  		if(is_array($compara['join']))
	  			$this->db->join($tabela, $compara['join'][0], $compara['join'][1]);
	  		else
		  		$this->db->join($tabela, $compara['join']);
	  	}
  	}
  	$query = $this->{$this->modelname}->get_where($id);
  	
  	//Verifica se existe esse cadastro
  	if($query->num_rows() == 0){
  	  redirect($this->base_url);
  	}
  	
  	$this->data['item'] = $query->result(); 
  	
  	foreach($this->data['models'] as $model => $values){
  		foreach($this->{$model}->fields as $k => $v){
  			$this->{$model}->fields[str_replace('.','_',$k)] = $v;
  			unset($this->{$model}->fields[$k]);
  		}
  	}
  	
  	$this->data['titulo'] = $this->titulo.' #'.$id;
  	$this->data['crud'] = $this->_crud();
  	$this->data['url'] = site_url($this->base_url);
  	$this->data['ok'] = false;
  	$this->data['fields'] = $this->{$this->modelname}->fields;
  	$this->data['c'] =& $this;
  	
  	$view = $this->load->view('admin/crud/visualizar', $this->data, true);
    $this->output->set_output(html_compress($view));
  }

}
