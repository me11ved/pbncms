<?php if($this->data) : ?>
<style>

body {
	background:#f6f6f6;
}

</style>
<div style="background:#fff;width:50%; margin:5% auto;border:1px solid #ccc;word-break: break-all;padding:10px;">
	<table style="border-collapse: collapse;" cellpadding="7" width="100%">
		
		<tr>
			<td style="border-bottom: 1px solid #ccc;width:30%;"><b>Время заявки:</b></td>
			<td style="border-bottom: 1px solid #ccc;"><?php echo $this->data[0]['time']; ?></td>
		</tr>
	 <tr>
		<td style="border-bottom: 1px solid #ccc;"><b>Имя:</b></td>
		<td style="border-bottom: 1px solid #ccc;"><?php echo $this->data[0]['name']; ?></td>
	</tr>
	<?php if($this->data[0]['description']) : ?>
		<tr>
			<td style="border-bottom: 1px solid #ccc;"><b>Комментарий:</b></td>
			<td style="border-bottom: 1px solid #ccc;"><?php echo $this->data[0]['description']; ?></td>
		</tr>
	<?php endif; ?>	
	<tr>
		<td style="border-bottom: 1px solid #ccc;"><b>Данные об телефоне:</b></td>
		<td style="border-bottom: 1px solid #ccc;"></td></tr>
	 <tr>
		<td>Номер:</td><td><a href="tel:+<?php echo $this->data[0]['phone']; ?>"><?php echo $this->data[0]['phone']; ?></a></td></tr>
	 <tr>
		<td>Оператор:</td>
		<td><?php echo $this->data[0]['phone_operator']; ?></td></tr>
	 <tr>
		<td>Регион:</td>
		<td><?php echo $this->data[0]['phone_region']; ?></td></tr>
	 <tr>
		<td style="border-bottom: 1px solid #ccc;"><b>Данные об ip:</b></td>
		<td style="border-bottom: 1px solid #ccc;"></td></tr>
	 <tr>
		<td>Город:</td><td><?php echo $this->data[0]['ip_city']; ?></td></tr>
	 <tr>
		<td>Регион:</td><td><?php echo $this->data[0]['ip_region']; ?></td></tr>
	 <tr>
		<td style="border-bottom: 1px solid #ccc;"><b>Данные об источнике:</b></td>
		<td style="border-bottom: 1px solid #ccc;"></td></tr>
	 <tr>
		<td>Заказ со старницы:&nbsp;</td><td><?php echo $this->data[0]['url']; ?></td>
	</tr>
	 <tr>
		<td>Форма:</td><td><?php echo $this->data[0]['form']; ?></td>
	 </tr>
	 <tr>
		<td>Источник:</td>
		<td>
			<span title="<?php echo $this->data[0]['ref']; ?>"><?php echo $this->data[0]['host']; ?></span>
		</td>
	 </tr>
	 <tr>
		 <td>Фраза:</td>
		 <td><?php echo $this->data[0]['query']; ?></td>
	</tr>
	 <tr>
		 <td>Я.Метрика:</td>
		 <td><?php echo $this->data[0]['client_id'].".".$this->data[0]['ip_user']; ?></td>
	</tr>
	 </table>
</div>
<?php else: ?>

	Access denied

<?php endif; ?>	 