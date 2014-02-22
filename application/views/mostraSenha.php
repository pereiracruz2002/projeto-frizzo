<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Peixaria Frizzo</title>
	<script>var base_url = "<?php echo base_url(); ?>";</script>
	<script type="text/javascript" src="<?php echo base_url();?>static/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>static/js/jquery.bxslider/jquery.bxslider.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>static/js/utility.js"></script>
	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #000080;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		text-align: center;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		padding: 10px;
		/*border: 1px solid #D0D0D0;*/
		-webkit-box-shadow: 0 0 8px #D0D0D0;
		background-color: #000080;
	}

	#container h1{
		text-transform: uppercase;
		color:#FFFF00;
		
		font-size: 50px;
	}

	.table{
		display: table;
		width: 100%;
	}
	
	.table .box{
		padding-top:0px;
	}

	.esquerda{
		float:left;
		width: 49%;
		text-align: center;
		height: 609px;
	}
	.direita{
		float:right;
		width: 49%;
	}

	.direita img{
		width: 547px;
	}

	h3{
		color: #FFFFFF;
		font-size: 43px;
		text-transform: uppercase;
		text-align: center;
	}

	.destaque{
		color: #FFFF00;
		font-size: 215px;
		margin-top: 105px;
		margin-bottom:43px;		
		font-weight: 900;
		text-align: center;
	}

	.box{
	    background-color: #0000FF;
	    border-radius: 10px 6px;
	    padding: 25px 0;
	    margin: 10px 0;
    }

    .rodape{
    	color:#ff0000;
    	font-size: 38px;
    	text-align: center;

    }

    .direita p{
    	text-align: center;
    	color:#FFFF00;
    	font-size: 50px;
		margin:20px 0;
    }
	
	.direita p span{
		line-height: 35px;
	}

    #clima{
    	margin-left:122px;
    }

    input#senha{
    	background-color:#0000FF;
    	border:none;
		border-color:#0000FF;
    	color:#0000FF;
    }

    .padLeft10{
    	padding-left: 10px;
    }

    .esquerda p.last{
    	color: #FFFF00;		
		font-weight: 900;
		text-align: center;
    	font-size: 69px;
    }



	</style>
</head>
<body>

<div id="container">

<h1 class="box">Peixaria Frizzo</h1>
	<div class="box">
	<h3>Senha</h3>
				<p class="destaque"><?= $senha?></p>
				<br />
				<?php 
	$attributes = array('class' => 'form', 'name' => 'myform');
	echo form_open('site',$attributes);
	$data = array(
              'name'        => 'senha',
              'id'          => 'senha',
              'value'       => '',
              'maxlength'   => '5',
              'size'        => '3',
              'style'       => 'width:10%',
            );
echo form_input($data);
?>
	</div>
	<div class="table">
		<div class="esquerda box">

			<div class="bloco2 box">
			<h3>Últimas senhas</h3>
		<?php 
		if(($this->session->userdata('last_password'))){
			$i = 0;
			$array_senha = $this->session->userdata('last_password');
			$results = array_reverse($array_senha);
			foreach ($results as $key=> $result) {
				if($key!=0){
					if($i<5){

					echo "<p class='last'>".$result."</p>";
				}

				$i++;
				}
				
			}
		}
		?>
			</div><!--bloco1-->
		</div><!--esquerda-->
		<div class="direita box">
			<div class="padLeft10">
				<h3>Ofertas do Dia</h3>
				<ul class="bxslider">
				<?php 
				//var_dump($promocao);
				//exit();
				//shuffle($promocao);
				//$produto = $promocao[0]['nome'];
				foreach($promocao as $promo){
					echo "<li><img src=".base_url()."/static/uploads/".$promo->src." /><p class='preco'>".$promo->nome."</p><p class='preco'>".$promo->link."</p></li>";
				}?>
				</ul>
			</div>
		</div><!--direita-->
	</div><!--table-->

</div>

</body>
</html>