<?php if(!$this->input->is_ajax_request()) include_once(dirname(__FILE__).'/../header.php'); ?>
  <?php  
  if(function_exists('breadcrumbs') and $this->input->is_ajax_request())
    breadcrumbs();
  ?>
  <h1><?=ucfirst($titulo)?> </h1>
  <?php if (in_array('C', $crud)): ?>
    <a href="<?=$url?>/novo" class="btn icon i_plus">Cadastrar Novo</a>
  <?php endif ?>


  <div class="dataTables_wrapper">
    <div class="dataTables_filter">
      
    </div>
    <table width="99%" class="table">
      <thead>
        <tr>
          <?php foreach($campos as $campo):?>
            <th scope="col"><?=$model->fields[$campo]['label']?></th>
          <?php endforeach; ?>
            <th>&nbsp;</th>
        </tr>
      </thead>
      <tbody>
        <? foreach($itens as $row):?>
        <tr>
          <? foreach($campos as $campo):?>
            <td class="<?=url_title($campo) ; ?>"><?=$row->{$campo}?></td>
          <? endforeach?>
          <td class="acoes">
            <?php if (in_array('D', $crud)): ?>
              <a class="btn small deletar red" href="<?=$url?>/deletar/<?=$row->{$model->id_col}?>" title="Deletar este registro">Deletar</a>
            <?php endif ?>
            <?php if (in_array('U', $crud)): ?>
              <a href="<?=$url?>/editar/<?=$row->{$model->id_col}?>" title="Editar este registro" class="btn small yellow">Editar</a>
            <?php endif; ?>

            <?php if (in_array('P', $crud)): ?>
              <a href="<?=$url?>/visualizar/<?=$row->{$model->id_col}?>" title="Visulizar este registro" class="btn small blue">Ver</a>
            <?php endif; ?>
                        
            
            <?php foreach($acoes_extras as $acao_extra): ?>
              <a href="<?=site_url($acao_extra['url']."/".$row->{$model->id_col});?>" title="<?=$acao_extra['title'];?>" class="btn small <?=$acao_extra['class'];?>"><?=$acao_extra['title'];?></a>
            <?php endforeach;?>
          </td>
        </tr>
        <? endforeach?>
      </tbody>
    </table>
    <?=$paginacao ?>
  </div>
<?php if(!$this->input->is_ajax_request()) include_once(dirname(__FILE__).'/../footer.php'); ?>
