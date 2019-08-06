<?php if(Session::get('loggedIn') == true): ?> 

		<!--content-->
		<div class="TableContent">
			<div id='breadcrumbs'>
				<div></div>
			</div>
				
				<div class="ContentMiniBox" style="width: 95%;">
					<div id="header">
						<div id="title"></div>
						<div id="setting">
							&nbsp;
						</div>
					</div>
					
					<div id="content">
						<form action="/integration/webmasterSettingSave/" method="POST" name='data'>
						<table id="hor-minimalist-b">
							<tr>
								<td>Token </td><td><input type='text' name="token" readonly="readonly" value="<?=$this->result->response->token; ?>"></td>
							</tr>
							<tr>
								<td>UserId </td><td><input type='text'name="user_id" readonly="readonly" value="<?=$this->result->response->user_id?>"></td>
							</tr>
							<?php if($this->host) : ?>
							<tr>
								<td>Host List <span class="tag">сделайте выбор хоста по которому необходимо получать данные</span></td>
								<td>
									<select name="hostid">
									<?php foreach($this->host as $key => $val) : ?>
										<?php if($val['host_id'] == $data['hostid']) : ?>
											<option value="<?php echo $val['host_id'];?>" selected='selected'><?php echo $val['unicode_host_url'];?></option>
										<?php else : ?>
											<option value="<?php echo $val['host_id'];?>"><?php echo $val['unicode_host_url'];?></option>
										<?php endif; ?>
									<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<? else : ?>
								<tr>
									<td>Логин</td><td><input name='l' placeholder="Вид: без @yandex.ru" value="<?php echo $data['id'];?>"></td>
								</tr>
								<tr>
									<td>Пароль</td><td><input name='p' placeholder="" value="<?php echo $data['secret'];?>"></td>
								</tr>
							<?php endif; ?>
						<table>
						<input type='submit' class="ButtonStyle" value='Save' style="padding:5px 0;width:100px;">
						</form>
						</br>
					</div>
				</div>
				
		</div>
	</div>
	
</div>

</body>
</html>

<?php endif; ?>