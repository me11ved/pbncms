<?php
  class View {
	
	public function __construct() 
	{
	  $this->parser = new Parser();
	  $this->mobile = new Mobile_Detect();
	  $this->template = TEMPLATE;
	  $this->temp_folder = 'tmpl/';
	  $this->temp_files = (object) array( 	'start' => (object) array ('header','menu'),
											'finish' => (object) array ('footer','scripts'));
	}
	
	private function getExtInBase()
	   {
		   $db = new sql(array(
								'user'    => DB_USER,
								'pass'    => DB_PASSWORD,
								'db'      => DB_NAME,
								'host'	=> DB_HOST,
								'charset' => DB_CHARSET
				));
		   
		  $status = $db->GetOne("SELECT 
										content 
									FROM 
										setting 
									WHERE 
										name = ?s",
									'extredirect');
		  
		  return $status;
	   }
	  	
	public function render($name,$admin = false, $noInclude = false) {
		
		ob_start(); 
		
		$cat_template = null;
		$href = null;
		
		if($admin == true) $this->template = "admin";
		
		if (isset($this->data['href'])) $href = $this->data['href'];
		
		if ($name == 'product') $cat_template = $this->data['cat_path'];
		
		if($noInclude !== true) 
		{
			foreach($this->temp_files->start as $start) 
			{	
				if( file_exists($this->temp_folder.$this->template.'/'.$start.'.tpl') ) require $this->temp_folder.$this->template.'/'.$start.'.tpl';
			}
			
			// Подгружаем шаблон на категорию
			if ( file_exists($this->temp_folder.$this->template.'/'.$cat_template.'_category.tpl' ) )
			{
				require $this->temp_folder.$this->template.'/'.$cat_template.'_category.tpl';
			}
			// подгружаем свой шаблон
			else if ( file_exists($this->temp_folder.$this->template.'/'.$href.'.tpl' )) 
			{
				require $this->temp_folder.$this->template.'/'.$href.'.tpl';
			}
			// если нет подгружаем шаблон страницы 
			else if( file_exists($this->temp_folder.$this->template.'/'.$name.'.tpl' ) ) 
			{
				require $this->temp_folder.$this->template.'/'.$name.'.tpl';
			}
			
			foreach($this->temp_files->finish as $end) 
			{
				if( file_exists($this->temp_folder.$this->template.'/'.$end.'.tpl') ) require $this->temp_folder.$this->template.'/'.$end.'.tpl';
			}
	   } 
	   else 
	   {
		   // Подгружаем шаблон на категорию
			if ( file_exists($this->temp_folder.$this->template.'/'.$cat_template.'_category.tpl' ) )
			{
				require $this->temp_folder.$this->template.'/'.$cat_template.'_category.tpl';
			}
			// подгружаем свой шаблон
			else if( file_exists($this->temp_folder.$this->template.'/'.$href.'.tpl' ) ) 
			{
				require $this->temp_folder.$this->template.'/'.$href.'.tpl';
			}
			// если нет подгружаем шаблон страницы 
			else if( file_exists($this->temp_folder.$this->template.'/'.$name.'.tpl' ) ) 
			{
				require $this->temp_folder.$this->template.'/'.$name.'.tpl';
			}
	   }
			
		$result = ob_get_clean(); 
		
		$match_q = $this->parser->preg_match_search('/{"query_post"(.+)}/',$result,0);
		
		if($match_q)
		{
			foreach($match_q as $v)
			{
				$result = str_replace($v,'',$result);
			}
		}
		
		if($admin !== true)  
		{
			$menu = $this->render_menu();
		
			if($menu) 
			{
				foreach($menu as $k1 => $val) 
				{	
					
					$result = str_replace('{'.$val	["tag"].'}',$val['text'],$result);
					
				}
			}
			
			
			
			$bread = $this->render_breadcrumbs();
			
			$content_of_article = $this->render_content( $this->data['description']);
			
			
			$script_header = $this->render_scripts('header');
			$script_footer = $this->render_scripts('footer');
			
			$result = $this->replace_tags($result);
			
			if ($this->mobile->isMobile())
			{
				$avatar_mobile = explode("/",$this->data['avatar']);
	
				if (!empty($avatar_mobile))
				{
					if (count($avatar_mobile) > 1)
					{
						$file = array_pop($avatar_mobile);
						
						array_push($avatar_mobile,"mobile");
						array_push($avatar_mobile,$file);
						
						$avatar = implode("/",$avatar_mobile);
						
						if (file_exists($_SERVER["DOCUMENT_ROOT"].$avatar))
						{
							$this->data['avatar'] = $_SERVER["DOCUMENT_ROOT"].$avatar;
						}
					}
				}
			}
			
			
			$result = str_replace(	array(
									  "{meta_title}",
									  "{meta_description}",
									  "{telephone}",
									  "{address}",
									  "{email}",
									  "{h1}",
									  "{title}",
									  "{title_category}",
									  "{description}",
									  "{mini_description}",
									  "{id_category}",
									  "{id_product}",
									  "{avatar}",
									  "{pole1}",
									  "{pole2}",
									  "{pole3}",
									  "{pole4}",
									  "{bread_crumbs}",
									  "{bread_crumbs_jsonld}",
									  "{scripts_header}",
									  "{scripts_footer}",
									  "{content_of_article}"
									),
									array (
									  $this->data['meta_title'],
									  $this->data['meta_description'],
									  $this->meta_contacts['telephone'],
									  $this->meta_contacts['address'],
									  $this->meta_contacts['email'],
									  $this->data['h1'],
									  $this->data['title'],
									  $this->data['title_category'],
									  $this->data['description'],
									  $this->data['mini_desc'],
									  $this->data['id_category'],
									  $this->data['id_product'],
									  $this->data['avatar'],
									  $this->data['pole1'],
									  $this->data['pole2'],
									  $this->data['pole3'],
									  $this->data['pole4'],
									  $bread["bread"],
									  $bread["jsonld"],
									  $script_header,
									  $script_footer,
									  $content_of_article["content"]
									),
									$result);
			
		}
		
		echo $result;
	}
	
	private function replace_tags($data)
	{
		$array = array( "/{query_post(.+)}/", '/\n/', "/(?:(?<=\>)|(?<=\/\>))\s+(?=\<\/?)/");
		
		$res = preg_replace($array,"",$data);
		
		return $res;
	}
	
	private function render_menu()
	{
		$html = '';
		if($this->getExtInBase() == 1) $html = ".html";
		
		$res = [];
		$text = null;
		
		// $this->debug($this->menu); 
		
		if(count($this->menu) > 0)
		{
			foreach($this->menu as $key1 => $val1) 
			{
				$text = "<ul class='{$val1["name_en"]}_ul_1 menu'>";
				
				foreach($val1['menu'] as $val2)
				{
					$text .= "<li><a href='{$val2["href"]}";
					
					if($val2["href"] != "/") $text .= $html;
					
					$text .= "'>{$val2["name"]}</a>";
					
					if($val2["children"])
					{
						
						$text .= "<a href='javascript:void(0);' tabindex='1' class='sub_icon'></a><ul class='{$val1["name_en"]}_ul_2'>";
						
						foreach($val2["children"] as $val3) 
						{
							$text .= "<li><a href='{$val3["href"]}";
							
							if($val3["href"] != "/") $text .= $html;
							
							$text .= "'>{$val3["name"]}</a>";
						}
						
						$text .= "</ul></li>";
					}
				}
				
				$text .= "</ul>";
				
				$res[$key1]["tag"] = $val1["name_en"];
				$res[$key1]["text"] = $text;
			}
				// unset($menu); 
		}
		
		return $res;
	}
	
	
	private function render_breadcrumbs(){
		
		if($this->breadCrumbs->data) 
		{ 
			$bread = "<ul>";
			
			$jsonld = '<script type="application/ld+json">{"@context": "http://schema.org", "@type": "BreadcrumbList", "itemListElement": [';
			
			
			foreach($this->breadCrumbs->data as $key => $kroskha)
			{
				$key = $key+1;
				
				if($key != count($this->breadCrumbs->data)) 
				{
					$bread .= "<li><a href='{$kroskha['href']}'>{$kroskha['title']}</a></li>";
					
					if ($key != 1) $jsonld .= ',';
					
					$jsonld .= '{
									"@type": "ListItem",
									"position": '.$key.',
									"item": {
									  "@id": "'. $this->breadCrumbs->host . $kroskha['href'].'",
									  "name": "'. $kroskha['title'].'"';
									  
					if (isset($kroskha['avatar'])) 
					{
						$jsonld = ',"image": "'. $kroskha['avatar'] . '"';
					}				  
									 
					$jsonld .= "}}";
				}
				else 
				{
					$bread .= "<li><span>{$kroskha['title']}</span></li>";
					
					if ($key != 1) $jsonld .= ',';
					
					$jsonld .= '{
									"@type": "ListItem",
									"position": '.$key.',
									"item": {
									  "@id": "'. $this->breadCrumbs->host . $kroskha['href'].'",
									  "name": "'.$kroskha['title'].'"';
									  
					if (isset($kroskha['avatar'])) 
					{
						$jsonld = ',"image": "'. $this->breadCrumbs->host .  $kroskha['avatar'] . '"';
					}				  
									 
					$jsonld .= "}}";
				
				}
			} 
			$bread .= "</ul>";
				
			$jsonld .= "]}</script>";
		}
		
		return [ 
					"bread" => $bread,
					"jsonld" => $jsonld];
	}
	
	
	private function render_scripts($pos = false){
		
		if($this->script_static['header'] and $pos == 'header')
		{
			foreach($this->script_static['header'] as $val) 
			{
				$top .= $val;
			}
			return $top;
		} 
		else if($this->script_static['footer'] and $pos == 'footer')
		{
			foreach($this->script_static['footer'] as $val) 
			{
				$bottom .= $val;
			}
			return $bottom;
		} 
		
	}
	
	
	public function get_params($name,$template = false){
		
		$result = NULL;
		
		ob_start(); 
		
		 // Подгружаем шаблон на категорию
		if ( file_exists($this->temp_folder.$this->template.'/'.$template["template"].'_category.tpl' ) )
		{
			require $this->temp_folder.$this->template.'/'.$template["template"].'_category.tpl';
		}
		// подгружаем свой шаблон
		else if( file_exists($this->temp_folder.$this->template.'/'.$template["href"].'.tpl' ) ) 
		{
			require $this->temp_folder.$this->template.'/'.$template["href"].'.tpl';
		}
		// если нет подгружаем шаблон страницы 
		else if( file_exists($this->temp_folder.$this->template.'/'.$name.'.tpl' ) ) 
		{
			require $this->temp_folder.$this->template.'/'.$name.'.tpl';
		}
		
		$result = ob_get_clean(); 
		
		$match = $this->parser->preg_match_search('/{"query_post"(.+)}/',$result,0);
		
		if($match)
		{
			$result = [];
			foreach($match as $v)
			{
				$decode = json_decode($v,JSON_HEX_QUOT | JSON_HEX_TAG);
				$result[] = $decode['query_post'];
			}
		}
		
		return $result;
		 
	}
 
	private function render_content($data = NULL){
		
		$result = NULL;
		
		if( $data )
		{
				$h2 = $this->parser->preg_match_search( "|<h[^>]+>(.*)</h[^>]+>|i", $data,1);
				
				
				
				if($h2)
				{
					$result .= '<ol class="content_of_article">';
					foreach( $h2 as $key => $val)
					{
						$result .= "<li>{$val}</li>";
					}
					$result .= '</ol>';
					
					
					return array ( 	"status" => true,
									"content" => $result
								);
				}
				else
				{
					return false;
				}
		}
	}
	
	
	private function debug($data){
	
	echo "<pre>";
	
	var_dump($data);
	
	exit;
  
  }
	
  }
?>
