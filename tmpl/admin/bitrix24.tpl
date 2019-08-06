<?php if(Session::get('loggedIn') == true): ?> 

		<!--content-->
		<div class="TableContent">
			<div id='breadcrumbs'>
				<div></div>
			</div>
			
			<?php if($this->result['status'] == 'success') : ?>
						<div>
							<p style='text-align:center;color:green;'>Токен получен</p>
						</div>
			<?php elseif($this->result['status'] == 'error') : ?>
						<div>
							<p style='text-align:center;color:red;'>Ошибка получения токена</p>
						</div>
			<?php endif; ?>
			
				<?php if ($this->data) : ?>
				<?php $data = $this->data;  ?>
				<?php endif; ?>
				
				<div class="ContentMiniBox" style="width: 95%;">
					<div id="header">
						<div id="title">Настройки</div>
						<div id="setting">
							&nbsp;
						</div>
					</div>
					</br></br></br>
					<div id="content">
						<form action="/admin/i_sbitrix24/" method="POST" name='data'>
						<table id="hor-minimalist-b">
							<tr>
								<td>Id<span class="tag">id</span> </td><td><input name='id' placeholder="Вид: 9ec369ab6a904bb892beae168ff0ba83" value="<?php echo $data['id'];?>"></td>
							</tr>
							<tr>
								<td>Secret <span class="tag">пароль</span></td><td><input name='secret' placeholder="Вид: c1bd8aaef0604f34acceadbc8a8bf43c" value="<?php echo $data['secret'];?>"></td>
							</tr>
							<tr>
								<td>Url<span class="tag">url</span> </td><td><input name='url' placeholder="Вид: 9ec369ab6a904bb892beae168ff0ba83" value="<?php echo $data['url'];?>"></td>
							</tr>
						
							<?php if($data['token']) : ?>
								<tr>
									<td>Token </td><td><input type='text' name="token" readonly="readonly" value="<?php echo $data['token'];?>"></td>
								</tr>
								<tr>
									<td>Refresh Token</td><td><input type='text'name="uptoken" readonly="readonly" value="<?php echo $data['uptoken'];?>"></td>
								</tr>
								<tr>
									<td>Expires</td><td><input type='text'name="uptoken" readonly="readonly" value="<?php echo date('d/m/Y H:i:s',$data['expires']);?>"></td>
								</tr>
							<?php endif; ?>
							
						<table>
						<input type='submit' class="ButtonStyle" value='Сохранить' style="padding:5px 0;width:100px;">
						</form>
						</br>
						<p style='text-align:center;float:left;'>Callback:</p> <input type="text" value="http://<?php echo $_SERVER['HTTP_HOST']; ?>/admin/i_sbitrix24/" style="margin-left:20px;margin-top:7px;width:300px;float:left"></p>
					</div>
				</div>
				
		</div>
	</div>
	
</div>

</body>
</html>

<?php endif; ?>