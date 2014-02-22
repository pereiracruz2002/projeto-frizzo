<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Site extends CI_Controller {

	public function index(){		
		if($this->input->post('senha')){
			$data['senha'] = $this->input->post('senha'); 
			$senha_anterior = array();
			$senha_anterior  = $this->session->userdata('last_password');
			if(!empty($senha_anterior)){
				//$last_password = $senha_anterior;
				$senha_anterior[]= $this->input->post('senha');  
	
			}else{
				$senha_anterior[] = $this->input->post('senha'); 
			}


			$dados_session= array('last_password'  => $senha_anterior);
  				$this->session->set_userdata($dados_session);
		}else{
			$data['senha'] = "****";
		}
		$this->db->select('*');
		$this->db->from('banner');
    	$this->db->where(array("banner.ativo" =>1));
    	$query = $this->db->get();
		$data['promocao'] = $query->result();
		/*
		$data['promocao'][0] = array('tipo'=>"sardinha.jpg",'preco'=>"R$ 8,00 Kg",'nome'=>'Sardinha');
		$data['promocao'][1] = array('tipo'=>"tambaqui.jpg",'preco'=>"R$ 15,00 Kg",'nome'=>'Tambaqui');
		$data['promocao'][1] = array('tipo'=>"Pacu.jpg",'preco'=>"R$ 16,00 Kg",'nome'=>'Pacu');
		$data['promocao'][1] = array('tipo'=>"Tilapia.jpg",'preco'=>"R$ 12,00 Kg",'nome'=>'Tilápia');
		$data['msg'] = array(0=>"Obrigado volte sempre!!",1=>"Orgulho por ter você como cliente!",2=>"Peixaria Frizzo, os melhores preços sempre!!");
		*/
		$this->load->view('mostraSenha',$data);
	}
}

/* End of file site.php */
/* Location: ./application/controllers/welcome.php */