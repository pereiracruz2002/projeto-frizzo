<?php

function box($type, $titulo, $mensagem=null) {
  if ($mensagem) $mensagem = "<h2>{$titulo}</h2>{$mensagem}";
  else $mensagem = $titulo;
  print '<div class="notification '.$type.'">'.$mensagem.'</div>';
}

function box_alert($titulo, $mensagem=null) {
  box('errors', $titulo, $mensagem);
}

function box_success($titulo, $mensagem=null){
  box('successfull', $titulo, $mensagem);
}


function formata_data($data){
  if (strstr($data, "/")){
    $A = explode ("/", $data);
    $V_data = $A[2] . "-". $A[1] . "-" . $A[0];
  }else{
    $A = explode ("-", $data);
    $V_data = $A[2] . "/". $A[1] . "/" . $A[0];	
  }
  return $V_data;
}

function formata_time($time, $separar=" "){
  $data = explode(" ", $time);
  if (strstr($data[0], "-")){
    $A = explode ("-", $data[0]);
    $V_data = $A[2] . "/". $A[1] . "/" . $A[0];	
  }else{
    $A = explode ("/", $data[0]);
    $V_data = $A[2] . "-". $A[1] . "-" . $A[0];	
  }
  if(count($data) < 2){
    $data[1] = "00:00:00";
  }
  return $V_data.$separar.$data[1];
}

function formata_valor($valor){
  if(!$valor)
    return false;
  $formato = strstr($valor, ',');
  if($formato){
    return str_replace(",", ".", str_replace(".", "", $valor));
  }else{
    return number_format($valor, 2, ',','.');
  }
}

function formata_porcentagem($valor){
  $v = explode(".", $valor);
  if($v[1] != "00")
    return formata_valor($valor);
  else
    return $v[0];
}

if (!function_exists('force_ssl')){
  function force_ssl() {
    if($_SERVER['SERVER_NAME'] != 'localhost'){
      $CI =& get_instance();
      $CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
      if ($_SERVER['SERVER_PORT'] != 443){
        redirect($CI->uri->uri_string());
      }
    }
  }
}  

function base_ssl(){
  return str_replace('http://', 'https://', base_url());
}

function site_ssl($url) {
  return str_replace('http://', 'https://', site_url($url));
}
function order_array_num($array, $key, $order = "ASC"){
  $tmp = array();
  foreach($array as $akey => $array2) 
    $tmp[$akey] = str_replace(",", ".", str_replace(".", "", $array2[$key]));

  if($order == "DESC")
    arsort($tmp , SORT_NUMERIC);
  else
    asort($tmp , SORT_NUMERIC);

  $tmp2 = array();       
  foreach($tmp as $key => $value)
    $tmp2[$key] = $array[$key];

  return $tmp2;
} 

function economia($total, $parte){
  return ($total/100)*$parte;
}

function calcula_preco($total, $parte){
  return $total - (economia($total, $parte));
}

function preenche_form(&$campos, $dados){
  $ci =& get_instance();
  
  foreach($campos as $key => $val){
    if(strstr($val['class'], 'data'))
      $campos[$key]['value'] = formata_data($dados->{$key});
    elseif(strstr($val['class'], 'valor'))
      $campos[$key]['value'] = formata_valor($dados->{$key});
    elseif(strstr($val['class'], 'date_time'))
      $campos[$key]['value'] = formata_time($dados->{$key});
    elseif(strstr($val['type'], 'password'))
      $campos[$key]['value'] = $ci->encrypt->decode($dados->{$key});
    else
      $campos[$key]['value'] = (isset($dados->{$key}) ? $dados->{$key} : '');
  }
}
function valor($fields, &$data){
  foreach($data as $key => $val){
		if(array_key_exists($key, $fields))
		  if(strstr($fields[$key]['class'], "valor"))
		    $data[$key] = formata_valor($val);
	}
}

function dohash($string){
  if((isset($string)) && (is_string($string))){
      $enc_string = base64_encode($string);
      $enc_string = str_replace("=","",$enc_string);
      $enc_string = strrev($enc_string);
      $md5 = md5($string);
      $enc_string = substr($md5,0,3).$enc_string.substr($md5,-3);
  }else{
      $enc_string = "Parâmetro incorreto ou inexistente!";
  }
  return $enc_string;
}

function unhash($string){
  if((isset($string)) && (is_string($string))){
      $ini = substr($string,0,3);
      $end = substr($string,-3);
      $des_string = substr($string,0,-3);
      $des_string = substr($des_string,3);
      $des_string = strrev($des_string);
      $des_string = base64_decode($des_string);
      $md5 = md5($des_string);
      $ver = substr($md5,0,3).substr($md5,-3);
      if($ver != $ini.$end){
          $des_string = "Erro na desencriptação!";
      }
  }else{
      $des_string = "Parâmetro incorreto ou inexistente!";
  }
  return $des_string;
}
function image_url($image_url){
    return base_url().'static/uploads/'.$image_url;
}
function is_image($img){
  $mimes = array('image/jpeg', 'image/pjpeg', 'image/png',  'image/x-png', 'image/gif');
  if(in_array(mime_content_type($img), $mimes))
    return true;
  else
    return false;
}
function html_compress($html){
  if(ENVIRONMENT == 'development'){
    return $html;
  }else{
    preg_match_all('!(<(?:code|pre).*>[^<]+</(?:code|pre)>)!',$html,$pre);#exclude pre or code tags
     
    $html = preg_replace('!<(?:code|pre).*>[^<]+</(?:code|pre)>!', '#pre#', $html);#removing all pre or code tags
    $html = preg_replace('#<!–[^\[].+–>#', '', $html);#removing HTML comments
    $html = preg_replace('/[\r\n\t]+/', ' ', $html);#remove new lines, spaces, tabs
    $html = preg_replace('/>[\s]+</', '><', $html);#remove new lines, spaces, tabs
    $html = preg_replace('/[\s]+/', ' ', $html);#remove new lines, spaces, tabs
    
    if(!empty($pre[0]))
      foreach($pre[0] as $tag)
      $html = preg_replace('!#pre#!', $tag, $html,1);#putting back pre|code tags
    
    return $html;  
  }
}
