<?php

class Formulator {
  var $inputs = array ();

  /*
   * Converte os argumentos de array para atributo valor
   * array("name" => "value") para name="value"
   *
   */
  function _args($type, $args) {
    extract($args, EXTR_SKIP);
    $_data_extra = array();

    if (!isset($extra)) $extra = array();

    if (gettype($extra)=='string') {
      $_data_extra[] = $extra;
      $extra = array();
    }

    foreach (array('name', 'value', 'size') as $item){
      if (isset($$item))
        if($item == 'value')
          $extra[$item] = trim($$item);
        else
          $extra[$item] = $$item;
    }

    if (isset($label)) {
      $uid = $type.uniqid();
      $extra['id'] = $uid;
    }

    foreach (array('checked', 'readonly', 'disabled', 'multiple') as $item)
      if (isset($$item) AND $$item==true)
        $extra[$item] = $item;

    if (gettype($extra)=='array')
      foreach ($extra as $key=>$value)
        $_data_extra[] = $key.'="'.addslashes($value).'"';

    $args = get_defined_vars();

    return $args;
  }

  function input ($type, $args=array ()) {
    if (!isset($type)) return $this;

    $args = $this->_args($type, $args);
    extract($args, EXTR_SKIP);

    $input = sprintf('<input type="%s" %s/>',
      $type,
      (count($_data_extra) ? join(' ', $_data_extra).' ' : '')
    );

    if (isset($label)) {
      $classes = '';
      if (isset($class))
        if (gettype($class)=='string')
          $classes = " $class";
      $label = "{$label}";
      
      if (in_array($type, array('checkbox', 'radio'))){ 
        $input = "<div class=\"{$type}\"><label for=\"{$uid}\" class=\"input".ucwords($type)."{$classes}\"><span>{$input}</span> {$label}</label></div>";
      }else{ 
        $input = "<section ".(trim($classes) ? 'class="'.$classes.'"' : '')."><label for=\"{$uid}\" class=\"input".ucwords($type)."{$classes}\">{$label}</label><div>{$input}";
        if(isset($extra['value']) and ($type == 'file' and $extra['value'])){
          $input .= '<span>'.$extra['value'].'</span>';
        }
        $input .= '</div></section>';
      }
      
    }

    $this->inputs[] = $input;
    return $this;
  }

  function text ($args=array ()) {
    return $this->input('text', $args);
  }
  
  function date ($args=array ()) {
    return $this->input('date', $args);
  }
  
  function tel($args=array ()) {
    return $this->input('tel', $args);
  }

  
  function email ($args=array ()) {
    return $this->input('email', $args);
  }
  
  function number ($args=array ()) {
    return $this->input('number', $args);
  }

	function file ($args=array ()) {
    return $this->input('file', $args);
  }
  function password ($args=array()) {
    return $this->input('password', $args);
  }

  function hidden ($args=array()) {
    return $this->input('hidden', $args);
  }

  function multiple($args=array()) {
    return $this->select($args, 'multiple');
  }

  function textarea ($args=array ()) {
    $args = $this->_args('textarea', $args);
    extract($args, EXTR_SKIP);
    $value = isset($extra['value']) ? $extra['value'] : '';
    $_data_extra = array_filter($_data_extra, create_function ('$i', 'return !preg_match("@^value=@", $i);') );
    $textarea = sprintf('<textarea %s>%s</textarea>',
      (count($_data_extra) ? join(' ', $_data_extra).' ' : ''),
      $value
    );
    if (isset($label)) {
      $classes = '';
      if (isset($class))
        if (gettype($class)=='string')
          $classes = " $class";
      $label = "{$label}";
      $textarea = "<section ".(trim($classes) ? 'class="'.$classes.'"' : '')."><label for=\"{$uid}\" class=\"input".ucwords($type)."{$classes}\">{$label}</label><div>{$textarea}</div></section>";

    }
    $this->inputs[] = $textarea;
	
    return $this;
  }

  function check ($args=array()) {
    $values = isset($args['values']) ? $args['values'] : array ();
    unset($args['values']);
    $val = isset($args['value']) ? unserialize($args['value']) : '';
    if(!$val) $val = array();
    unset($args['value']);
    $args['name'] = $args['name']."[]";
    
    if(isset($args['label']))
    	$this->inputs[] = '<section class="checks '.$args['class'].'"><label class="legend">' . $args['label'] . '</label>';
    else
    	$this->inputs[] = '<section class="checks '.$args['class'].'">';
    
    $this->inputs[] = '<div class="inputs">';
    foreach ($values as $key => $value) {
      if (in_array($key, $val)) $args['checked'] = 'checked';
      else $args['checked'] = false;
      $arg = array_merge($args,array('value' => $key, 'label' => $value));
      $this->input('checkbox', $arg);
    }
    $this->inputs[] = '</div></section>';
    
    return $this;
  }

  function radio ($args=array()) {
    $values = isset($args['values']) ? $args['values'] : array ();
    unset($args['values']);
    $val = isset($args['value']) ? $args['value'] : '';
    unset($args['value']);
    if(isset($args['label']))
    	$this->inputs[] = '<section class=\'radios '.$args['class'].'\'><label class="legend">' . $args['label'] . '</label>';
    else
    	$this->inputs[] = '<section class=\'radios\'>';
    
    $this->inputs[] = '<div class="inputs">';
    foreach ($values as $key => $value) {
      if ($val==$key) $args['checked'] = 'checked';
      else $args['checked'] = false;
      $arg = array_merge($args,array('value' => $key, 'label' => $value));
      $this->input('radio', $arg);
    }
    
    $this->inputs[] = '</div></section>';
    
    return $this;
  }

  function select($args=array(), $type="normal"){
    $args = $this->_args('select', $args);
    $value = '';
    extract($args, EXTR_SKIP);

    $options = array();
    foreach ($values as $k => $v)
    	$options[] = "  <option value=\"$k\"".((isset($extra['value']) AND ($extra['value']==$k))?' selected="selected"':'').">$v</option>";

    foreach ($_data_extra as $k=>$v) {
      if (preg_match('@^value=@', $v))
        unset($_data_extra[$k]);
    }

    $input = sprintf("<select %s>\n%s\n</select>",
      (count($_data_extra) ? join(' ', $_data_extra).' ' : ''),
      join("\n", $options)
    );

    if (isset($label)) {
      $classes = '';
      if (isset($class))
        if (gettype($class)=='string')
          $classes = " $class";
      $label = "{$label}";
      $input = "<section ".(trim($classes) ? 'class="'.$classes.'"' : '')."><label for=\"{$uid}\" class=\"input".ucwords($type)."{$classes}\">{$label}</label><div>{$input}</div></section>";

    }

    $this->inputs[] = $input;
    return $this;
  }

  function show($clear=true) {
    $saida = join("\n", $this->inputs);
    if ($clear) $this->inputs = array();
    return $saida;
  }
}
