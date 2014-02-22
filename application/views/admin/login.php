<style>
.form-signin{
  max-width: 300px;
  padding: 19px 29px 29px;
  margin: 40px auto 20px;
  background-color: #fff;
  border: 1px solid #e5e5e5;
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border-radius: 5px;
  -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
  -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
  box-shadow: 0 1px 2px rgba(0,0,0,.05);
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin input[type="text"],
.form-signin input[type="password"] {
  font-size: 16px;
  height: auto;
  margin-bottom: 15px;
  padding: 7px 9px;
}
</style>
<?php if(isSet($msg)):?>
<div class="alert alert-danger"><?=$msg;?></div>
<?php endif;?>
<form method='post' action='<?php echo site_url('auth/login');?>' class="form-signin">
	<fieldset>
		<label>Login</label>
		<input type='text' name='login' id='login' value='' required />
		<label>Senha</label>
		<input type='password' name='senha' id='senha' value='' required />
    <div class="control-group">
		  <div class="controls">
        <input type='submit' value="Entrar" class="btn btn-primary" />
      </div>
    </div>
	</fieldset>
</form>
