<?php
function image_resizer($path="",$image="",$height="",$width="",$ratio=FALSE)
{
	$ci =& get_instance();
	
	$ci->load->library('image_lib'); 
	
	$config['image_library'] = 'gd2';
	$config['source_image']	= $path.$image;
	$config['maintain_ratio'] = $ratio;
	$config['width']	 = $height;
	$config['height']	= $width;
	$config['overwrite']	= TRUE;
	$ci->image_lib->initialize($config);
	$ci->image_lib->resize();
	$ci->image_lib->clear();
	
	/*$config['image_library'] = 'gd2';
	$config['source_image'] = $path.$image;
	$config['new_image']	= $path.'newimg/'.$image;
	$config['maintain_ratio'] = $ratio;
	$config['width']	 = 64;
	$config['height']	= 64;
	$config['overwrite']	= TRUE;
	$ci->image_lib->initialize($config);
	$ci->image_lib->resize();
	$ci->image_lib->clear();*/
}

function image_resizer1($path="",$image="",$height="",$width="",$ratio=FALSE)
{
	$ci =& get_instance();
	
	$ci->load->library('image_lib'); 
	
	$config['image_library'] = 'gd2';
	$config['source_image']	= $path.$image;
	$config['maintain_ratio'] = $ratio;
	$config['width']	 = $height;
	$config['height']	= $width;
	$config['overwrite']	= TRUE;
	$ci->image_lib->initialize($config);
	$ci->image_lib->resize();
	$ci->image_lib->clear();
	
	$config['image_library'] = 'gd2';
	$config['source_image'] = $path.$image;
	$config['new_image']	= $path.'small/'.$image;
	$config['maintain_ratio'] = $ratio;
	$config['width']	 = 20;
	$config['height']	= 19;
	$config['overwrite']	= TRUE;
	$ci->image_lib->initialize($config);
	$ci->image_lib->resize();
	$ci->image_lib->clear();
	
	$config['image_library'] = 'gd2';
	$config['source_image'] = $path.$image;
	$config['new_image']	= $path.'channel/'.$image;
	$config['maintain_ratio'] = $ratio;
	$config['width']	 = 100;
	$config['height']	= 40;
	$config['overwrite']	= TRUE;
	$ci->image_lib->initialize($config);
	$ci->image_lib->resize();
	$ci->image_lib->clear();
	
	
}

function upload_this($path="",$image="",$ext_type=array(),$name="")
{	//echo count($_FILES[$image]["name"]); print_r($_FILES[$image]);exit;

		if($_FILES[$image]["error"]!=0)
			return false;
		if(!$ext_type)
		$ext_type = array('gif','jpg','jpe','jpeg','png');
		
		$ext = explode(".", $_FILES[$image]["name"]);
		
		$extension = end($ext);
		$encrypt=md5(date('Y-m-d H:i:s').rand(0,10));
		if($name)
		$image_name=$name.'.'.$extension;
		if(!$name)
		$image_name=$encrypt.'.'.$extension;
		if(in_array(strtolower($extension),$ext_type))
		{
			
			$dest=$path;
			$file_src=$dest.$image_name;
			if(move_uploaded_file($_FILES[$image]['tmp_name'], $file_src))
			{  
				return $image_name;
			}
			else
			{exit;
				return false;
			}
		}
}
?>