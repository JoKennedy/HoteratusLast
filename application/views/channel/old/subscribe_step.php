<div class="dash-b4-n calender-n">
<div class="row-fluid clearfix">
<div class="" style="padding:20px;">

<div class="idealsteps-container" align="center" style="text-align:center">
<nav class="idealsteps-nav"></nav>
<?php 
if($this->session->flashdata('success')!='')
{
?>
<div class="alert alert-success">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('success');?>
</div>
<?php } ?>

<?php 
if($this->session->flashdata('error')!='')
{
?>
<div class="alert alert-danger">
<button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">&times; </i></span></button>
<?php echo $this->session->flashdata('error');?>
</div>
<?php } ?>


<h4><?php echo $channel_name?> setup</h4>
<?php $authentication = explode(',',$authentication_requirements);?>
<form action="" novalidate autocomplete="off" class="idealforms">
<input type="hidden" value="<?php echo $channel_id;?>" name="channel_name_id" id="channel_name_id" />
<div class="idealsteps-wrap col-md-6 col-md-offset-4" align="center"> 

<!-- Step 1 -->

<section class="idealsteps-step">
<?php if(in_array('Hotel id',$authentication)){ ?>
<div class="field">
<label class="main">Hotel Id:</label>
<input name="hotl_id" type="text">
<!--data-idealforms-ajax="<?php //echo base_url();?>channel/ajax"-->
<span class="error"></span> </div>
<?php } ?>
<?php if(in_array('Username',$authentication)){ ?>
<div class="field">
<label class="main">Username:</label>
<input name="username" type="text">
<!--data-idealforms-ajax="<?php //echo base_url();?>channel/ajax"-->
<span class="error"></span> </div>
<?php } ?>
<?php if(in_array('Password',$authentication)){ ?>
<div class="field">
<label class="main">Password:</label>
<input name="password" type="password">
<span class="error"></span> </div>
<?php } ?>
<div class="field buttons">
<label class="main">&nbsp;</label>
<button type="button" class="next">Next &raquo;</button>
</div>
</section>

<!-- Step 2 -->

<section class="idealsteps-step">
<div class="field">
<label class="main">Sex:</label>
<p class="group">
<label>
<input name="sex" type="radio" value="male">
Male</label>
<label>
<input name="sex" type="radio" value="female">
Female</label>
</p>
<span class="error"></span> </div>
<div class="field">
<label class="main">Hobbies:</label>
<p class="group">
<label>
<input name="hobbies[]" type="checkbox" value="football">
Football</label>
<label>
<input name="hobbies[]" type="checkbox" value="basketball">
Basketball</label>
<label>
<input name="hobbies[]" type="checkbox" value="dancing">
Dancing</label>
<label>
<input name="hobbies[]" type="checkbox" value="dancing">
Parkour</label>
<label>
<input name="hobbies[]" type="checkbox" value="dancing">
Videogames</label>
</p>
<span class="error"></span> </div>
<div class="field buttons">
<label class="main">&nbsp;</label>
<button type="button" class="prev">&laquo; Prev</button>
<button type="button" class="next">Next &raquo;</button>
</div>
</section>

<!-- Step 3 -->

<section class="idealsteps-step">
<div class="field">
<label class="main">Phone:</label>
<input name="phone" type="text" placeholder="000-000-0000">
<span class="error"></span> </div>
<div class="field">
<label class="main">Zip:</label>
<input name="zip" type="text" placeholder="00000">
<span class="error"></span> </div>
<div class="field">
<label class="main">Options:</label>
<select name="options" id="">
<option value="default">&ndash; Select an option &ndash;</option>
<option value="1">One</option>
<option value="2">Two</option>
<option value="3">Three</option>
<option value="4">Four</option>
</select>
<span class="error"></span> </div>
<div class="field">
<label class="main">Comments:</label>
<textarea name="comments" cols="30" rows="10"></textarea>
<span class="error"></span> </div>
<div class="field buttons">
<label class="main">&nbsp;</label>
<button type="button" class="prev">&laquo; Prev</button>
<button type="button" class="next">Next &raquo;</button>
</div>
</section>

<section class="idealsteps-step">
<div class="field">
<label class="main">Phone:</label>
<input name="phone" type="text" placeholder="000-000-0000">
<span class="error"></span> </div>
<div class="field">
<label class="main">Zip:</label>
<input name="zip" type="text" placeholder="00000">
<span class="error"></span> </div>
<div class="field">
<label class="main">Options:</label>
<select name="options" id="">
<option value="default">&ndash; Select an option &ndash;</option>
<option value="1">One</option>
<option value="2">Two</option>
<option value="3">Three</option>
<option value="4">Four</option>
</select>
<span class="error"></span> </div>
<div class="field">
<label class="main">Comments:</label>
<textarea name="comments" cols="30" rows="10"></textarea>
<span class="error"></span> </div>
<div class="field buttons">
<label class="main">&nbsp;</label>
<button type="button" class="prev">&laquo; Prev</button>
<button type="submit" class="submit">Submit</button>
</div>
</section>
</div>
<span id="invalid"></span>
</form>

<div class="cls_gray_bg">
<h3><?php echo $channel_name;?></h3>
<div class="" align="center">
<?php echo $channel_instruction;?>

</div>


</div>

</div>

</div>

</div>
</div>