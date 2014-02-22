<h2>Tem certeza que deseja remover este registro?</h2>

<?$__controller__ = &get_instance();?>
<?php foreach(explode(',', $c->delete_fields) as $k): ?>
<?if(method_exists($__controller__, '_pre_row_view')) $__controller__->_pre_row_view($data);?>
<p><strong><?=$model->fields[$k]['label']?></strong> <?=$data->{$k}?></p>

<?php endforeach ?>

<form action="" method="post" style="text-align:right">
<input type="submit" name="ok" value="SIM" />
<a href="<?=$url?>">não</a>
</form>