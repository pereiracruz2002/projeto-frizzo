<?php if(!$this->input->is_ajax_request()) include_once(dirname(__FILE__).'/../header.php'); ?>
  <?php if (in_array('C', $crud)): ?>
    <a href="<?=$url?>/novo" class="btn icon i_plus">Cadastrar Novo</a>
  <?php endif ?>
  
  <?php if(isset($acoes_controller)): ?>                
    <?php foreach($acoes_controller as $acao_extra): ?>
      <a href="<?=site_url($acao_extra['url']);?>" title="<?=$acao_extra['title'];?>" class="<?=$acao_extra['class'];?>"><?=$acao_extra['title'];?></a>
    <?php endforeach;?>
  <?php endif; ?>
  
  <div class="g12">
  
  
  <h1><?=ucfirst($titulo)?></h1>
    <?=$c->_call_pre_form($model, $data);?>
    <?php
    if (validation_errors())
      print box_alert(validation_errors());
    if ($ok)
      print box_success('Dados salvos com sucesso!');
    ?>
      
    
    <form action="<?=$url.$action?>" method="post" class="curd" enctype="multipart/form-data">
      <? $c->_call_filter_pre_form($data); ?>
      <? if (is_array($c->fields_groups)): ?>
          <? foreach ($c->fields_groups as $key =>$value):?>
            <fieldset>
              <label>Informe os dados <?=$key; ?></label>
              <?php $campos = explode(',', $value);
              if(!isset($data[0])) $data[0] = array();
              foreach($campos as $campo)
                print $model->form($data[0], trim($campo)); 
              ?>
            </fieldset>
          <?php endforeach; ?>
        <? else: ?>
        <fieldset>
          <label>Informe os dados</label>
          <?=call_user_func_array (array($model, 'form'), $data)?>
        </fieldset>
        <?php endif; ?>
        <?php $c->_call_in_form()?>

        <button class="purple">Salvar</button>
    </form>
    <? if(isset($data[0]['values']['estado'])): ?>
    <input type="hidden" id="estado_val" value="<?=$data[0]['values']['estado'];?>" />
    <input type="hidden" id="cidade_val" value="<?=$data[0]['values']['cidade'];?>" />
    <? endif;?>
  <?=$c->_call_pos_form()?>
  </div>
<?php if(!$this->input->is_ajax_request()) include_once(dirname(__FILE__).'/../footer.php'); ?>
