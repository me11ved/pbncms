<?php 

ini_set('max_execution_time', 300);

Class Files {
	
	public function __construct() {
		
		/* Папка или название базы для бэкапа */
		$this->source = dirname(__DIR__);
		/* Папка храниение файлов */	
		$this->dump_dir = dirname(__DIR__)."/public/backup/";
		/* Время жизнь файла */
		$this->delay_delete = 60; // сек.
		$this->ts = time();
		/* Название архива */
		$this->filezip = "backup".$this->ts.".zip"; 
		/* Файлы или папки для исключения из копирования*/	
		$this->drops = NULL;
	 }
	
	// Создает архив из указанной директории
	public function copy_files()
	{
		$this->create_dir();
		
		$list = $this->list_files($this->source);
		
		$data = array(	'zip' => "file".$this->filezip,
						'file' => $list);
		
		$result = $this->create_arhive($data);
		
		return $result;
	}
	
	public function copy_database($sql)
	{
		$this->create_dir();
		
		$filename = $this->source.".sql";
		
		$this->check_file();
		
		/* Запросы к базе */
		
		$q1 = "SHOW TABLES";
		$q2 = "SHOW CREATE TABLE ?n";
		$q3 = "SELECT * FROM  ?n";
		
		$fp = fopen($_SERVER['DOCUMENT_ROOT']."/".$filename, "a"); // Открываем файл
		
		$tables = $sql->GetAll($q1); // Запрашиваем все таблицы из базы
			
		foreach ( $tables as $key => $val ) 
		{	
			$t = $val['Tables_in_'.$this->source];
			 
			if ($fp) 
			{	
				$r1 = $sql->GetAll($q2,$t); // Получаем запрос на создание таблицы
				
				fwrite($fp, "\n".$r1[0]["Create Table"].";\n"); // Добавляем результат в файл
				
				$r2 = $sql->GetAll($q3,$t); // Получаем список всех записей в таблице
				
				foreach ($r2 as $row) 
				{
				  $query = "";
				  
				  /* Путём перебора всех записей добавляем запросы на их создание в файл */
				  foreach ($row as $field) 
				  {
					if ( is_null($field) ) 
					{
						$field = "NULL";
					}
					else {
						$field = $sql->escapeString($field); // Экранируем значения
					}
					if ($query == "")
					{
						$query = $field;
					}	
					else 
					{
						$query .= ", ".$field;
					}	
				  }
				  
				  $query = "INSERT INTO `".$t."` VALUES (".$query.");";
				  
				  fwrite($fp, $query);
				  
				}
			}
		}
		
		fclose($fp); // Закрываем файл
		
		$data = array(	'zip' => "base".$this->filezip,
						'file' => array($_SERVER['DOCUMENT_ROOT']."/".$filename)
						);
		
		$r3 = $this->create_arhive($data);
		
		if($r3['status'] == 'success') unlink( $_SERVER['DOCUMENT_ROOT']."/".$filename );
		
		return $r3;
		 
	}
	
	// Удалять файл бэкпов старше $delay_delete
	public function remove()
	{
		$ts = time();
		
		$files = $this->list_files($this->dump_dir);
		
		foreach ($files as $file)
		{
			if ($ts - filemtime($file) > $this->delay_delete) 
			{
				unlink($file);
			}
		}
		
			
	}
	
	// Если архив с таким именем уже есть, то заканчиваем скрипт
	private function check_file(){
		if (file_exists($this->filezip)) exit; 
	}
	
	// Создание Архива по параметрам
	private function create_arhive($data)
	{
		if( class_exists('ZipArchive') )
		{
			$zip = new ZipArchive();
			
			if ($zip->open($this->dump_dir.$data['zip'], ZipArchive::CREATE) === true) 
			{
					foreach($data['file'] as $val)
					{
						$local = substr( $val, strlen( $_SERVER['DOCUMENT_ROOT']."/" ) );
						
						if( isset($this->drops) )
						{
							if(!$this->offset_dirs($val)) $zip->addFile($val,$local);
						}
						else
						{
							$zip->addFile($val,$local);
						}
						
					}
				
				$zip->close();
				
				$result = array(	'status' => 'success',
									'file' => $data['zip'],
									'ts' => $this->ts,
									'path' => $this->dump_dir
								);
			}		
		}
		else {
			$result = array(	'status' => 'error',
								'description' => 'Класс ZipArchive не подключен в PHP');				
		}
		
		return $result;
 	}
	
	private function offset_dirs($file)
	{
		if( isset($this->drops) )
		{
			if(!is_array($this->drops))
			{
				if( strpos( $file, $this->drops ) !== false) return true;	
			}
			else
			{
				foreach($this->drops as $val)
				{
					if( strpos( $file, $val ) !== false ) return true;	
				}
			}
			return false;
		}
	}
	
	//Получения списка файлов
	
	private function list_files($path)
	{
		$files = array();
		$out = [];
		
		if ($path[mb_strlen($path) - 1] != '/') {
			$path .= '/';
		}

		$dh = opendir($path);
		while (false !== ($file = readdir($dh))) {
			
			if ($file != '.' && $file != '..' && $file[0] != '.' && !is_dir($path.$file)) {
				$files[] = $path.$file;
			}
			else if(is_dir($path.$file) && $file != '.' && $file != '..')
			{
				$files[] = $this->list_files($path.$file);
			}
			else if($file == ".htaccess") {
				$files[] = $path.$file;
			}
		}

		closedir($dh);
		
		array_walk_recursive($files, function($files) use (&$out) { $out[] = $files; });
		
		return $out;
	}
	
	
	private function create_dir() {
		
		if(!file_exists($this->dump_dir)) 
		{
			mkdir( $this->dump_dir, 0777 );
			$result = array(	"status" => "success",
								"description" => "Директория создана");
		}
		else {
			$result = array(	"status" => "success",
								"description" => "Директория уже существует");
		}
			
		return $result;		
	}
	
	// Вовращает размер файла
	// Добавлена: 10.10.2018
	
	public function fileSizes($f)
	{
		$filesize = filesize($f);
		
		$formats = array('Б','КБ','МБ','ГБ','ТБ');// варианты размера файла
		$format = 0;// формат размера по-умолчанию
		
		// прогоняем цикл
		while ($filesize > 1024 && count($formats) != ++$format)
		{
			$filesize = round($filesize / 1024, 2);
		}
		
		// если число большое, мы выходим из цикла с
		// форматом превышающим максимальное значение
		// поэтому нужно добавить последний возможный
		// размер файла в массив еще раз
		$formats[] = 'ТБ';
		
		return $filesize." ".$formats[$format];
	}
	
	//Возвращает дату создания/изменения файла
	// Добавлена: 05.02.2019
	
	public function fileDate($f)
	{
		$return = false;
		
		if (file_exists($f)) 
		{
			$return = date("m.d.Y H:i", filectime($f));
		}
		
		return $return;
	}
	
	//Создает папки в цикле по урлу
	// Добавлена: 05.02.2019
	
	public function createDirectory($f) 
	{
		$path = explode("/",$f);
		$add = '';
		
		
		if(count($path) >= 1)
		{
			/* Удаляем файл в массиве */
			if (end(explode('.', $f))) array_pop($path);
			
			/* В цикле создаем папки по очереди */
			foreach($path as $key => $val) 
			{
				if (strlen($val))
				{
					$folder = $add."/".$val;
					
					if (!file_exists($_SERVER["DOCUMENT_ROOT"]."/public/cache".$folder)) 
					{
						mkdir( $_SERVER["DOCUMENT_ROOT"]."/public/cache".$folder, 0700 );
					}
					
					$add = $folder;
				}
			}
			
			$test = implode("/",$path);
			
			
			if (file_exists($_SERVER["DOCUMENT_ROOT"]."/public/cache".$test."/"))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		
	}
	
	public function perms($filename)
	{
		return substr(decoct(fileperms($filename)), -3);
	}
		
}		
?>