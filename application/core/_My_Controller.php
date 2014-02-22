<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once(dirname(__FILE__).'/../controllers/facebook.php');
class My_Controller extends CI_Controller
{
  var $signed_request =false; 
  
  var $data = array(
    'titulo' => '',
    'descricao' => ''
  );
  
  var $fbPermissoes = array(
        'redirect_uri' => CANVAS_URL
      );


  public function __construct() {
    parent::__construct();
    $this->connect_fb();
  }
  
  public function connect_fb(){
    

    $this->facebook = new Facebook(array(
          'appId' => APP_ID,
          'secret' => SECRET,
          'cookie' => true
          ));

    $this->signed_request = $this->facebook->getSignedRequest();

    if(ENVIRONMENT == 'development'){
      $this->signed_request = array('page' => array('id' => '171343306225394', 'admin' => 1, 'liked' => 1), 
          'user_id' => '571553462',
          'oauth_token' => 'AAAEWS93Krc8BAJZC3WVknEO0AvkV3XzaNX5iakSZCRGapH7xeFpZBkHsuoYtGXZB79nKXRTAFYYOycleJhAIKzc8oMqvL6UNYDHgfEe6jAZDZD'
          );
    }

    
    if($this->signed_request){
      $this->fbLogged();
      $this->session->unset_userdata('logado');
      $this->session->unset_userdata('signed_request');
      $this->session->set_userdata('signed_request', $this->signed_request);

      

      if(isset($this->signed_request['oauth_token']))
        $this->facebook->setAccessToken($this->signed_request['oauth_token']);
      if($this->signed_request['page']['admin'])
        $this->session->set_userdata('logado', 1);
    }else{
      $this->signed_request = $this->session->userdata('signed_request');
    }
  }
  
  /**
   * fbLogged
   * Verifica se o usuário está logado e redireciona ele
   * para a tela de permissões do FB
   *
   * @access public
   * @return void
   */
  public function fbLogged()
  {
    if(!isset($this->signed_request['user_id'])){
      $url = $this->facebook->getLoginUrl($this->fbPermissoes);
      $this->output->set_output('<script>top.window.location="'.$url.'";</script>');
    }
  }
  
  
}
