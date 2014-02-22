<?php if(!$this->input->is_ajax_request()) include_once(dirname(__FILE__).'/../header.php'); ?>
<?php 
  if(method_exists($c, '_filter_pre_visualizar'))
    $c->_call_filter_pre_visualizar($item, $models);
?>
    
  <?php  
  if(function_exists('breadcrumbs') and $this->input->is_ajax_request())
    breadcrumbs();
  ?>
  <div class="g12">
    <h1><?=ucfirst($titulo)?></h1>
  
  
    <div class="tab">
    <ul>
  	<?php $i=0; foreach($models as $model => $titulo_model): ?>
    	<?php if($titulo_model['titulo']): $i++; ?>
    	<li><a href="#tabs-<?php echo $i ?>"><?php echo $titulo_model['titulo'] ?></a></li>
    	<?php endif; ?>
  	<?php endforeach; ?>
  	</ul>
  	<?php $e=0; foreach($models as $model => $titulo_model): ?>
  	<?php if($titulo_model['titulo']): $e++; ?>
  	<div id="tabs-<?php echo $e; ?>">
  	<?php endif; ?>

  	<?php if($titulo_model['titulo']): $i=0; ?>
  	
  	<?php if(isset($titulo_model['has_many'])): ?>

  	<?php foreach($item as $it): ?>
  	<table class="documentation">
		<?php foreach($this->{$model}->fields as $k => $v): ?>
			<tr>
  			<th><?php echo $v['label'] ?></th>
  			<td>
  			<?php if(strstr($v['class'], 'imagem')): ?>
  			<?php if($item[$i]->{$k}): ?>
  			<img src="<?php echo image_url($item[$i]->{$k}) ?>" width="180" />
  			<?php endif; ?>
  			 
  			<?php elseif(strstr($v['class'], 'valor')): ?>
  			R$ <?php echo formata_valor($item[$i]->{$k}) ?>
  			<?php elseif(strstr($v['class'], 'time')): ?>
  			<?php echo formata_time($item[$i]->{$k}) ?>
  			<?php elseif(strstr($v['class'], 'data')): ?>
  			<?php echo formata_data($item[$i]->{$k}) ?>
  			<?php elseif(isset($v['from'])): ?>
  			<?php echo get_from($v['from'], $item[$i]->{$k}) ?>
  			<?php elseif(isset($v['serialized'])): ?>
  			
  			  <?php $arrSerial = unserialize($item[$i]->{$k}) ?>
  			  <?php foreach($arrSerial as $sK => $sV): ?>
  			  <p><?php echo is_int($sK) ? '' : $sK.': ' ?> <?php echo $sV ?></p>
  			  <?php endforeach; ?>
  			
  			<?php else: ?>
  			<?php echo $item[$i]->{$k} ?>
  			<?php endif; ?>
  			
  			
  		<?php endforeach; ?>
			</td>
			</tr>
  	</table>
    <?php $i++; endforeach; ?>
  	
  	<?php else: ?>
  	<div class="dataTables_wrapper">
  	<table class="documentation">
		<?php foreach($this->{$model}->fields as $k => $v): ?>
		  <tr>
		  
			<th><?php echo $v['label'] ?></th>
			<td>
			<?php if(strstr($v['class'], 'imagem')): ?>
			<img src="<?php echo image_url($item[$i]->{$k}) ?>" width="150" />
			<?php elseif(strstr($v['class'], 'valor')): ?>
			R$ <?php echo formata_valor($item[$i]->{$k}) ?>
			<?php elseif(strstr($v['class'], 'time')): ?>
			<?php echo formata_time($item[$i]->{$k}) ?>
			<?php elseif(strstr($v['class'], 'data')): ?>
			<?php echo formata_data($item[$i]->{$k}) ?>
			<?php elseif(isset($v['from'])): ?>
			<?php echo get_from($v['from'], $item[$i]->{$k}) ?>
			<?php else: ?>
			<?php echo $item[$i]->{$k} ?>
			
			<?php endif; ?>
			</td>
			
			</tr>
		<?php endforeach; ?>
			
  	</table>
  	</div>
  	<?php endif; ?>
  	
  	<?php endif; ?> 
    	<?php if($titulo_model['titulo']): ?>
    	</div> <!-- tab-<?php echo $e; ?> -->
    	<?php endif; ?>
  	<?php endforeach; ?>
    </div>
  </div>
  <?php  
    if(method_exists($c, '_pos_visualizar'))
      $c->_pos_visualizar($item);
    ?>
  
<?php if(!$this->input->is_ajax_request()) include_once(dirname(__FILE__).'/../footer.php'); ?>
