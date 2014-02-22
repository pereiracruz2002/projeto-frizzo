<?php  

class Auth extends CI_Controller{

  public function index(){


    if($this->session->userdata('logado'))
      redirect("main");
    else
      $this->login();
  }

    function login(){
    $this->load->model('admin_model', 'admin');
    $this->load->library('encrypt');
    $data['title'] = 'Admin Frizzo - Login';
		if($this->input->posts()){
      $where['admLogin'] = $this->input->post('login');
			//$where['admSenha'] = $this->encrypt->encode($this->input->post('senha'));
      $where['admSenha'] = $this->input->post('senha');
			$where['admAtivo'] = "Ativo";
      $consulta_admin = $this->admin->get_where($where);


      if($consulta_admin->num_rows()){
        $administrador = $consulta_admin->row();
  				$dados_session= array(
  										 'adminId'  => $administrador->id_usuario,
  										 'admLogin'  => $administrador->nome,
  										 'logado'     => 1,
  								 );
  				$this->session->set_userdata($dados_session);
          redirect("main");

      }else{
        $data['msg'] = "Seu Login ou sua senha está invalida";
      }
    }
    $this->load->view('admin/header', $data);
    $this->load->view('admin/login', $data);
    $this->load->view('admin/footer', $data);
    
  }

  function logout() {
    $this->session->sess_destroy();
    //$this->session->unset_userdata('admLogin');
    //unset($this->session->userdata('admLogin'));
		 redirect("auth");
  }
	
}
