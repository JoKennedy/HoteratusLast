<?php

function theme_url($uri)
{
	
	$CI =& get_instance();
	return $CI->config->base_url('user_assets/'.$uri);
}

//to generate an image tag, set tag to true. you can also put a string in tag to generate the alt tag
function theme_img($uri, $tag=false)
{
	/*<img alt="" class="img-responsive" src="data:image/png;base64,<?php echo base64_encode(file_get_contents("uploads/customerimage/".$user_profilepics));?>">*/
	if($tag)
	{
		return  '<img src="'.theme_url('images/'.$uri).'" alt="'.$tag.'">';
	}
	else
	{
		return  theme_url('images/'.$uri);
	}
	
}

function theme_js($uri, $tag=false)
{
	if($tag)
	{
		return '<script type="text/javascript" src="'.theme_url('js/'.$uri).'"></script>';
	}
	else
	{
		return theme_url('js/'.$uri);
	}
}

//you can fill the tag field in to spit out a link tag, setting tag to a string will fill in the media attribute
function theme_css($uri, $tag=false)
{
	
	if($tag)
	{
		$media=false;
		if(is_string($tag))
		{
			$media = 'media="'.$tag.'"';
		}
		return  '<link href="'.theme_url('css/'.$uri).'" type="text/css" rel="stylesheet" '.$media.'/>';
	}
		return  theme_url('css/'.$uri);
		
}
function theme_fonts($uri, $tag=false)
{
	
	if($tag)
	{
		$media=false;
		if(is_string($tag))
		{
			$media = 'media="'.$tag.'"';
		}
		return  '<link href="'.theme_url('fonts/css/'.$uri).'" type="text/css" rel="stylesheet" '.$media.'/>';
	}
		return  theme_url('fonts/css/'.$uri);
		
}

