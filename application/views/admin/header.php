<?php
echo doctype('html');
echo "<html>";
echo "<head>";
echo "<title>Administrador Frizzo</title>";
	echo meta('Content-type', 'text/html; charset=utf-8', 'equiv');
	$meta = array(
	    array('name' => 'robots', 'content' => 'no-cache'),
	    array('name' => 'Content-type', 'content' => 'text/html; charset=utf-8', 'type' => 'equiv')
	);
	echo meta($meta); 	

	echo link_tag(array('href' => 'static/css/bootstrap.min.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
	echo link_tag(array('href' => 'assets/css/styles.css', 'rel' => 'stylesheet', 'type' => 'text/css'));
	echo link_tag(array('href' => 'assets/css/smoothness/jquery-ui.min.css', 'rel' => 'stylesheet', 'type' => 'text/css'));

	echo '<script>
    var base_url = "'.base_url().'";
    var assets_url = "'.base_url().'assets/";
  </script>'.
	 '<script src="' . base_url() . 'assets/javascript/jquery.js" type="text/javascript"></script>'.
	 '<script src="' . base_url() . 'assets/javascript/jquery-ui.min.js" type="text/javascript"></script>'.
	 '<script src="' . base_url() . 'assets/javascript/mask.js" type="text/javascript"></script>'.
	 '<script src="' . base_url() . 'assets/javascript/mascaras.js" type="text/javascript"></script>'.
	 '<script src="' . base_url() . 'assets/javascript/jquery.scrollTo-1.4.3.1-min.js" type="text/javascript"></script>'.
	 '<script src="' . base_url() . 'assets/javascript/cidades-estados-1.2-utf8.js" type="text/javascript"></script>'.
	 '<script src="' . base_url() . 'assets/tinymce/jquery.tinymce.min.js" type="text/javascript"></script>'.
	 '<script src="' . base_url() . 'assets/javascript/bootstrap.js" type="text/javascript"></script>'.
	 '<script src="' . base_url() . 'assets/javascript/general.js" type="text/javascript"></script>';
  if(file_exists(FCPATH.'assets/javascript/'.$this->uri->segment(1).'.js'))
	  echo '<script src="' . base_url() . 'assets/javascript/'.$this->uri->segment(1).'.js" type="text/javascript"></script>';
echo "</head>";
echo "<body>";
if($this->uri->segment(1)<>"auth")
	$this->load->view('admin/menu');
?>

<div class="container-fluid">
