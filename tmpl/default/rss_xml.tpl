<?='<?xml version="1.0" encoding="utf-8"?>'?>
<rss xmlns:yandex="http://news.yandex.ru" xmlns:media="http://search.yahoo.com/mrss/" xmlns:turbo="http://turbo.yandex.ru" version="2.0">
	<channel>
		<title>АНО ЦНСЭ - Экспертизы</title>
		<link>https://<?=$_SERVER['HTTP_HOST']?></link>
		<description>Независимая строительная экспертиза</description>
		<yandex:logo type="square">https://<?=$_SERVER['HTTP_HOST']?>/public/images/logoNew.png</yandex:logo>
		<?php if($this->data) : ?>
			<?php foreach($this->data as $val) : ?>
				<item turbo="true">
					<title><?=$val['h1']?></title>
					<link>https://<?=$_SERVER['HTTP_HOST']?><?=$val['href']?></link>
					<pdalink>https://<?=$_SERVER['HTTP_HOST'].$val['href']?></pdalink>
					<description><?=strip_tags($val['mini_desc'])?></description>
					<category><?=$val['title_category']?></category>
					<author>Заказать экспертизу 8(495)477-96-95</author>
					<turbo:content>
					<![CDATA[
						<header>
						<?php if($val['avatar'] and !empty($val['avatar']) ) : ?>
							<figure> <img src="https://cnse24.ru<?=$val['avatar']?>" /> </figure>
						<?php endif; ?>
						<h1><?=$val['h1']?></h1>
								<menu> 	
										<a href="/#Callback_footer_ID">Оставить заявку</a> 
										<a href="/kontakty.html">Контакты</a> 
										<a href="/o-kompanii.html">О компании</a>
										<a href="/licenzii.html">Лицензии</a> 
										<a href="/otzyvy-o-cnse.html">Отзывы</a> 
										<a href="/stoimosty.html">Стоимость</a>
								</menu> 
						</header>
						<body>
							<?=$val['description']?>
							<h2>Цена и сроки на <span style='text-transform:lowercase;'><?=$val['h1']?></span></h2>
								<p><span>Цена от </span><b><?=$val['pole1']?></b><span> руб.</span</p>
								<p><span> Срок от </span><b><?=$val['pole2']?></b><span> дней.</span></p>
						</body>
					]]>
					</turbo:content>
					<yandex:full-text><![CDATA[<?=$val['description']?>]]></yandex:full-text>
				</item>	
			<?php endforeach;?>
		<?php endif; ?>
	</channel>
</rss>