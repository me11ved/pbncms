<?='<?xml version="1.0" encoding="UTF-8"?>'?>
	<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xmlns:xhtml="http://www.w3.org/1999/xhtml"
			xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"
			xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
		<url>
		<loc><?=URL."/"?></loc>
		<changefreq>weekly</changefreq>
		<priority>1.0</priority>
		</url> 
		<?php if($this->map_data['category']) : ?>
		<?php foreach($this->map_data['category'] as $val_c) : ?>
			<url>
			  <loc><?=URL.$val_c['url']?></loc>
			   <lastmod><?=$val_c['lastmod']?></lastmod>
			   <changefreq>weekly</changefreq>
			   <priority>1.0</priority>
			</url>
		<?php endforeach; ?>
		<?php endif; ?>
		<?php if($this->map_data['product']) : ?>
			<?php foreach($this->map_data['product'] as $val_p) : ?>
				<url>
				   <loc><?=URL.$val_p['url']?></loc>
				   <lastmod><?=$val_p['lastmod']?></lastmod>
				   <changefreq>weekly</changefreq>
				   <priority>0.9</priority>
				</url>
			<?php endforeach; ?>
		<?php endif; ?>	
	</urlset>