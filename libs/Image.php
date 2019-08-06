<?php 

Class Image {
	
	public $watermark = false;
	public $path_watermark = 'watermark.png';
	public $path = "image/";
	public $name;
	public $size = '';
	public $id = '';
	private $ext = array("JPEG","jpeg","JPG","jpg","PNG","png");
	
	
	public function show() {
		
		$status_exists = $this->check_exists();
		
		if(!$status_exists['check']) {
			header("HTTP/1.0 404 Not Found"); 
			return false;
		} 
		
		$status_ext = $this->check_ext();
		
		if(!$status_ext['check']) {
			header("HTTP/1.0 404 Not Found"); 
			return false;
		}
		
		$file = $status_exists['file'];

		if($this->watermark) $file = $this->create_watermark($status_exists['file'],$status_ext['ext']);
		
		$base64 = $this->create_base64($file,$status_ext['ext']);
		
		return $base64;
		
		
		
	}
	
	private function create_base64($file,$ext) {
		
		$arr_ext = array(	"JPG" => "image/jpeg",
							"jpg" => "image/jpeg",
							"png" => " image/png",
							"PNG" => " image/png",
							"jpeg" => "image/jpeg",
							"JPEG" => "image/jpeg"
						);
		
		if(!$this->watermark) {
			$image = file_get_contents($file);
		}
		else {
			$image = $file;
		}
		
		$img_base64 = 'data:' . $arr_ext[$ext] . ';base64,' . base64_encode($image);
		
		return $img_base64;
		
	}
	
	private function create_watermark($file,$ext){
		
		$watermark = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/".$this->path_watermark);   

		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);  

		$image_path = $file;
		
		if($ext == 'jpg' or $ext == 'jpeg') {
			$image = imagecreatefromjpeg($image_path);
		}
		else {
			$image = imagecreatefrompng($image_path);
		}
		
		
		if ($image === false) {
			return false;
		}
		
		$size = getimagesize($image_path);
		
		$dest_x = $size[0] - $watermark_width - 5;
		$dest_y = $size[1] - $watermark_height - 5;

		imagealphablending($image, true);
		imagealphablending($watermark, true);
		
		imagecopy($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);
		
		ob_start();
		
		imagejpeg($image);
		
		if($ext == 'jpg' or $ext == 'jpeg') {
			imagejpeg($image);
		}
		else {
			imagepng($image);
		}
		
		$image_data = ob_get_contents(); 
		
		ob_end_clean();
		
		imagedestroy($image);
		imagedestroy($watermark); 
		
		return $image_data;
		
	}
	
	
	private function check_ext(){
		
		$ext = end(explode(".",$this->name));
		
		if(array_search($ext,$this->ext) !== false) {
			$check = true;
		}
		else {
			$check = false;
		}
		
		return $result = array(
								"ext" => $ext,
								"check" => $check
								);
		
	}
	
	
	private function check_exists() {
		
		$file = $_SERVER['DOCUMENT_ROOT']."/".$this->path.$this->size."/".$this->id."/".$this->name;
		
		if(file_exists($file)) {
			$check = true;
		}
		else {
			$check = false;
		}
		
		return $result = array(
								"file" => $file,
								"check" => $check
								);
	}
	
	
}



?>