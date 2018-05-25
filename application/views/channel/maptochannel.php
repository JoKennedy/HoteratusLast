<div class="content container-fluid pad_adjust  mar-top-30 cls_mapsetng">
  <div class="mar-bot30">

  <div class="cls_comm_in">
      <div class="clearfix">
        <div class="pull-left">
          <h5> <?php echo $channelname;?> Room Mapping</h5>
        </div>        
      </div>
    </div>


  <div class=" cls_billing_form">
        <div class="col-md-12 col-sm-12">
          <!-- <div class="cls_comm_in">
            <h5>How to Get Started</h5>
            <p>If your company is interested in integrating with us, please read our policies and fill out the form.</p>
          </div> -->          
          <form class="cls_profile" method="post" name="set_roommap" id="set_roommap" action="<?php echo lang_url();?>mapping/save_mapping" onsubmit="return validate();">

          <?php
  if(user_type()==1 || admin_id()!='' && admin_type()=='1')
  {
    $user_ids = user_id();
  }
  else if(user_type()==2)
  {
    $user_ids = owner_id();
  }
  $propertyid="";
  $enabled="";
    $rate_id="";
  $included_occupancy="";
  $extra_adult="";
  $extra_child="";
  $single_guest="";
  $rate_conversion = "";
  if($result)
  {
    foreach ($result as $val) 
    {
      $propertyid=$val->property_id;
      $enabled=$val->enabled;
        $rate_id = $val->rate_id;
        $included_occupancy=$val->included_occupancy;
        $rate_conversion = $val->rate_conversion;
      $extra_adult=$val->extra_adult;
      $extra_child=$val->extra_child;
      $single_guest=$val->single_quest;
      $map_guest_count=$val->guest_count;
      $map_refun_type=$val->refun_type;
      $mapping_id=$val->mapping_id;
      $import_mapping_id = $val->import_mapping_id;
      $optionenable = $val->enabled;
      $bnowlevel    = $val->explevel;
      $exp_occupancy    = $val->exp_occupancy;
      $update_rate = $val->update_rate;
      $update_avail = $val->update_availability;
      $ChargeType = $val->ChargeType;
      $Adults = $val->Adults;
      $Children = $val->Children;
      $Infants = $val->Infants;
      $extra_infants = $val->extra_infants;
      if(insep_decode($channel_id) == 1){
        $rate_acq_type = $val->rateAcquisitionType;
      }

      if($val->property_id!='' && $val->rate_id==0)
      {
        $property_name = get_data(TBL_PROPERTY,array('owner_id'=>$user_ids,'hotel_id'=>hotel_id(),'property_id'=>$val->property_id,'status'=>'Active'))->row()->  property_name;
      }
      if($val->property_id!='' && $val->rate_id!=0)
      {
        $property_name = get_data(RATE_TYPES,array('user_id'=>$user_ids,'hotel_id'=>hotel_id(),'room_id'=>$val->property_id,'rate_type_id'=>$val->rate_id))->row()->  rate_name;
      }
      if($val->property_id!='' && $val->rate_id==0 && $val->guest_count!='' && $val->refun_type!='')
      {
        if($val->refun_type=='1')
        {
          $property_name=$property_name.'-'.$val->guest_count.' Guest';
        }
        elseif($val->refun_type=='2')
        {
          $property_name=$property_name.'-'.$val->guest_count.' Guest Non refundable';
        }
      }
      if($val->property_id!='' && $val->rate_id!='0' && $val->guest_count!='' && $val->refun_type!='')
      {
        if($val->refun_type=='1')
        {
          $property_name=$property_name.'-'.$val->guest_count.' Guest';
        }
        elseif($val->refun_type=='2')
        {
          $property_name=$property_name.'-'.$val->guest_count.' Guest Non refundable';
        }
      }
    }
  }
?>

            <div class="row">
              <div class="col-md-6 col-sm-6 col-xs-12 cls_resp50">
                <div class="form-group">
                  <label><?php echo $channelname.' '.'Rooms :';?> </label>
                  <div class="select-style1">
                  <?php
if(insep_decode($channel_id)=='1')
{
  $chek_import = get_data('import_mapping',array('channel'=>insep_decode($channel_id)))->row_array();
  //echo insep_decode($channel_id);
  $this->db->where('channel',insep_decode($channel_id));
  $query=$this->db->get('import_mapping')->row();
  if(count($chek_import)!=0)
  {
  $roomtype=explode(',',rtrim($query->roomtype_name,','));
  $rate_plan=explode(',',rtrim($query->name,','));
  $rate_type_id=explode(',',rtrim($query->rate_type_id,','));
  $combine=array_merge($roomtype, $rate_plan);
  
  $output = array();
  $size = sizeof($roomtype);
  for ( $i = 0; $i < $size; $i++ ) {
    if ( !isset($output[$roomtype[$i]][$rate_type_id[$i]]) ) {
      $output[$roomtype[$i]][$rate_type_id[$i]] = array();
    }
    $output[$roomtype[$i]][$rate_type_id[$i]] = $rate_plan[$i];
  }
  //echo count($output);
  }
}
elseif(insep_decode($channel_id)=='11')
{
  $reconline = get_data(IM_RECO,array('user_id'=>user_id(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->result();
}
elseif(insep_decode($channel_id)=='5')
{
  $hotelbeds = get_data('import_mapping_HOTELBEDS_ROOMS',array('user_id'=>user_id(),'hotel_id'=>hotel_id(),'channel_id'=>insep_decode($channel_id)))->result();
}
elseif(insep_decode($channel_id)=='9')
{
  $airbnb = get_data('import_mapping_AIRBNB',array('user_id'=>user_id(),'hotel_id'=>hotel_id()))->result();
}


?>
                    <select id="import_mapping_id" name="import_mapping_id">
                     <?php
if((insep_decode($channel_id))=='1')
{
  if(count($expedia)!=0)
  {
    foreach($expedia as $ex_val)
    {
      if($ex_val->name!='')
      {
        if($ex_val->map_id==$import_mapping_id)
        {
          ?>
                    <option value="<?php echo $ex_val->map_id;?>" <?php if($ex_val->map_id==$import_mapping_id){?> selected="selected" <?php } ?>> <?php echo $ex_val->roomtype_name.' - '.$ex_val->roomtype_id.' - '.$ex_val->distributionModel.' - '.$ex_val->name;?></option>
                    <?php 
        }
        elseif(!in_array($ex_val->map_id,$connected_room))
        {
          echo '<option value="'.$ex_val->map_id.'">'.$ex_val->roomtype_name.' - '.$ex_val->roomtype_id.' - '.$ex_val->distributionModel.' - '.$ex_val->name.'</option>';
        }
      }
      else
      {
        if($ex_val->map_id==$import_mapping_id)
        {
          ?>
                    <option value="<?php echo $ex_val->map_id;?>" <?php if($ex_val->map_id==$import_mapping_id){?> selected="selected" <?php } ?>> <?php echo $ex_val->roomtype_name;?></option>
                    <?php 
        }
        elseif(!in_array($ex_val->map_id,$connected_room))
        {
          echo '<option value="'.$ex_val->map_id.'">'.$ex_val->roomtype_name.'</option>';
        }
      }
    }
  }
}
elseif(insep_decode($channel_id)=='11')
{
  if(count($reconline)!=0)
  {
    foreach($reconline as $re_val)
    {
      if($re_val->re_id==$import_mapping_id)
      {
      ?>
            <option value="<?php echo $re_val->re_id;?>" <?php if($re_val->re_id==$import_mapping_id){?> selected="selected" <?php } ?>> <?php echo $re_val->CODE.' - '.$re_val->IDROOM;?></option>
            <?php
      //echo '<option value="'.$re_val->re_id.'">'.$re_val->CODE.' - '.$re_val->IDROOM.'</option>';
      }
      elseif(!in_array($re_val->re_id,$connected_room))
      {
        echo '<option value="'.$re_val->re_id.'">'.$re_val->CODE.' - '.$re_val->IDROOM.'</option>';
      }

    }
  }
}
elseif((insep_decode($channel_id))=='2')
{
  if(count($booking)!=0)
  {
    foreach($booking as $bk_val)
    {
      if($bk_val->B_rate_id!='0')
      {
        if($bk_val->import_mapping_id==$import_mapping_id)
        {
          ?>
                    <option value="<?php echo $bk_val->import_mapping_id;?>" <?php if($bk_val->import_mapping_id==$import_mapping_id){?> selected="selected" <?php } ?>> <?php echo $bk_val->room_name.' - '.$bk_val->rate_name;?></option>
                    <?php 
        }
        elseif(!in_array($bk_val->import_mapping_id,$connected_room))
        {
          echo '<option value="'.$bk_val->import_mapping_id.'">'.$bk_val->room_name.' - '.$bk_val->rate_name.'</option>';
        }
      }
      else
      {
        if($bk_val->import_mapping_id==$import_mapping_id)
        {
          ?>
                    <option value="<?php echo $bk_val->import_mapping_id;?>" <?php if($bk_val->import_mapping_id==$import_mapping_id){?> selected="selected" <?php } ?>> <?php echo $bk_val->room_name;?></option>
                    <?php 
        }
        elseif(!in_array($bk_val->import_mapping_id,$connected_room))
        {
          echo '<option value="'.$bk_val->import_mapping_id.'">'.$bk_val->room_name.'</option>';
        }
      }
    }
  }
}
elseif(insep_decode($channel_id)=='8')
{
  if(count($gta)!=0)
  {
    foreach($gta as $gt_val)
    {
      if($gt_val->GTA_id==$import_mapping_id)
      {
      ?>
            <option value="<?php echo $gt_val->GTA_id;?>" <?php if($gt_val->GTA_id==$import_mapping_id){?> selected="selected" <?php } ?>> <?php echo $gt_val->RoomType.' - '.$gt_val->Description.'-'.$gt_val->ID.'('.$gt_val->rateplan_code.'/'.$gt_val->RateBasis.')'.'- Occupancy ('.$gt_val->MaxOccupancy.')-'.$gt_val->contract_type;?></option>
            <?php
      //echo '<option value="'.$re_val->re_id.'">'.$re_val->CODE.' - '.$re_val->IDROOM.'</option>';
      }
      elseif(!in_array($gt_val->GTA_id,$connected_room))
      {
        echo '<option value="'.$gt_val->GTA_id.'">'.$gt_val->RoomType.' - '.$gt_val->Description.'-'.$gt_val->ID.'('.$gt_val->rateplan_code.'/'.$gt_val->RateBasis.')'.'- Occupancy ('.$gt_val->MaxOccupancy.')-'.$gt_val->contract_type.'</option>';
      }

    }
  }
}

elseif(insep_decode($channel_id)=='5')
{
  if(count($hotelbeds)!=0)
  {
    foreach($hotelbeds as $re_val)
    {
      if($re_val->map_id==$import_mapping_id)
      {
      ?>
            <option value="<?php echo $re_val->map_id;?>" <?php if($re_val->map_id==$import_mapping_id){?> selected="selected" <?php } ?>> <?php echo $re_val->contract_name.' - '.$re_val->roomtype.' - '.$re_val->characterstics.' - '.$re_val->sequence;?></option>
            <?php
      //echo '<option value="'.$re_val->re_id.'">'.$re_val->CODE.' - '.$re_val->IDROOM.'</option>';
      }
      elseif(!in_array($re_val->map_id,$connected_room))
      {
        echo '<option value="'.$re_val->map_id.'">'.$re_val->contract_name.' - '.$re_val->roomtype.' - '.$re_val->characterstics.' - '.$re_val->sequence.'</option>';
      }

    }
  }
}
elseif(insep_decode($channel_id)=='17')
{
  if(count($bnow)!=0)
  {
    foreach($bnow as $re_val)
    {
      if($re_val->import_mapping_id==$import_mapping_id)
      {
      ?>
            <option value="<?php echo $re_val->import_mapping_id;?>" <?php if($re_val->import_mapping_id==$import_mapping_id){?> selected="selected" <?php } ?>> <?php echo $re_val->RoomTypeName.' - '.$re_val->RateTypeName;?></option>
            <?php
      }
      elseif(!in_array($re_val->import_mapping_id,$connected_room))
      {
        echo '<option value="'.$re_val->import_mapping_id.'">'.$re_val->RoomTypeName.' - '.$re_val->RateTypeName.'</option>';
      }

    }
  }
}
elseif(insep_decode($channel_id)=='15')
{
  if(count($travel)!=0)
  {
    foreach($travel as $re_val)
    {
      if($re_val->map_id==$import_mapping_id)
      {
      ?>
            <option value="<?php echo $re_val->map_id;?>" <?php if($re_val->map_id == $import_mapping_id){?> selected="selected" <?php } ?>> <?php echo $re_val->Description; ?></option>
            <?php
      }
      elseif(!in_array($re_val->import_mapping_id,$connected_room))
      {
        echo '<option value="'.$re_val->map_id.'">'.$re_val->Description.'</option>';
      }

    }
  }
}elseif(insep_decode($channel_id)=='14')
{
  if(count($wbeds)!=0)
  {
    foreach($wbeds as $re_val)
    {
      if($re_val->import_mapping_id==$import_mapping_id)
      {
      ?>
            <option value="<?php echo $re_val->import_mapping_id;?>" <?php if($re_val->import_mapping_id==$import_mapping_id){?> selected="selected" <?php } ?>> <?php echo $re_val->nameRoomType.' ( '.$re_val->codeRate .' : '.$re_val->boardCodeBase.' : '.$re_val->maximumPaxes.' : '.$re_val->supportSaleSystem.' : '.$re_val->boardCodeBase.' )';?></option>
            <?php
      }
      elseif(!in_array($re_val->import_mapping_id,$connected_room))
      {
        echo '<option value="'.$re_val->import_mapping_id.'">'.$re_val->nameRoomType.' ( '.$re_val->codeRate.' : '.$re_val->nameRoomFeature.' : '.$re_val->boardCodeBase.' : '.$re_val->maximumPaxes.' : '.$re_val->supportSaleSystem.' : '.$re_val->boardCodeBase.' ) </option>';
      }

    }
  }
}
elseif(insep_decode($channel_id)=='36')
{
  if(count($despegar)!=0)
  {
    foreach($despegar as $re_val)
    {
      if($re_val->import_mapping_id==$import_mapping_id)
      {
      ?>
            <option value="<?php echo $re_val->import_mapping_id;?>" <?php if($re_val->import_mapping_id==$import_mapping_id){?> selected="selected" <?php } ?>> <?php echo $re_val->nameRoomType.' ( '.$re_val->rate_name .')';?></option>
            <?php
      }
      elseif(!in_array($re_val->import_mapping_id,$connected_room))
      {
        echo '<option value="'.$re_val->import_mapping_id.'">'.$re_val->nameRoomType.' ( '.$re_val->rate_name.' ) </option>';
      }

    }
  }
}
elseif(insep_decode($channel_id)=='19')
{
  if(count($agoda)!=0)
  {
    foreach($agoda as $re_val)
    {
      if($re_val->map_id==$import_mapping_id)
      {
      ?>
            <option value="<?php echo $re_val->map_id;?>" <?php if($re_val->map_id==$import_mapping_id){?> selected="selected" <?php } ?>> <?php echo $re_val->roomtype_name.' ( '.$re_val->name .')';?></option>
            <?php
      }
      elseif(!in_array($re_val->map_id,$connected_room))
      {
        echo '<option value="'.$re_val->map_id.'">'.$re_val->roomtype_name.' ( '.$re_val->name.' ) </option>';
      }

    }
  }
}
elseif(insep_decode($channel_id)=='9')
{
  
  if(count($airbnb)!=0)
  {
    foreach($airbnb as $re_val)
    {
      if($re_val->import_mapping_id==$import_mapping_id)
      {
      ?>
            <option value="<?php echo $re_val->import_mapping_id;?>" <?php if($re_val->import_mapping_id==$import_mapping_id){?> selected="selected" <?php } ?>> <?php echo $re_val->RoomName;?></option>
            <?php
      }
      elseif(!in_array($re_val->import_mapping_id,$connected_room))
      {
        echo '<option value="'.$re_val->import_mapping_id.'">'.$re_val->RoomName.'</option>';
      }

    }
  }
}
elseif(insep_decode($channel_id)=='40'||insep_decode($channel_id)=='41'||insep_decode($channel_id)=='42')
{
  
  if(count($hotusagroup)!=0)
  {
    foreach($hotusagroup as $re_val)
    {
      if($re_val->map_id==$import_mapping_id)
      {
      ?>
            <option value="<?php echo $re_val->map_id;?>" <?php if($re_val->map_id==$import_mapping_id){?> selected="selected" <?php } ?>> <?php echo $re_val->roomname.' ['.$re_val->roomcode . ']'.$re_val->vr_rate.' ('.$re_val->mealplan.')';?></option>
            <?php
      }
      elseif(!in_array($re_val->map_id,$connected_room))
      {
        echo '<option value="'.$re_val->map_id.'">'.$re_val->roomname.' ['.$re_val->roomcode . ']'.$re_val->vr_rate.' ('.$re_val->mealplan.')'.'</option>';
      }

    }
  }
}
else
{
?>
<option value="" > No rooms are imported </option>
<?php 
}
?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12 cls_resp50">
                <div class="form-group">
                  <label>Channel Manager Room </label>

                <input type="hidden" value="" name="guest_count" id="guest_count"/>
                <input type="hidden" value="" name="refun_type" id="refun_type"/>
                <input type="hidden" value="" id="rate_type" name="rate_type" />
                <input type="hidden" name="channel_id" value="<?php echo $channel_id; ?>">
                <input type="hidden" name="room_ids" value="" id="room_ids">
                <input type="hidden" name="mapping_id" value="<?php echo $mapping_id;?>" />

                  <div class="select-style1">
                    <select class="channel_manager_room" id="channel_manager_room" name="channel_manager_room">
<?php
if($not_channel_details)
{
  foreach ($not_channel_details as $nc)
  {
    if($nc->non_refund==1)
    {
      $members = $nc->member_count+$nc->member_count;
    }
    else
    {
      $members = $nc->member_count;
    }
?>
<option value="<?php echo $nc->property_id;?>" guest_count="0" refun="0" mapp_type="main_room" property="<?php echo insep_encode($nc->property_id);?>" <?php if($val->property_id==$nc->property_id && $val->guest_count=='0' && $val->refun_type=='0') { ?> selected="selected" <?php } ?>> <?php echo $nc->property_name;?> </option>
<?php 
if($nc->pricing_type==2) 
{
  if($nc->non_refund==1)
  {
    for($k=1;$k<=$members;$k++)
    {
    
      if($nc->member_count < $members)
      {
        if($k%2 == 0)
        {
          $name = 'Guest Non refundable';
          $v = ceil($k/2);
          $refun = '2';
        }
        else
        {
          $name = 'Guest';
          $v = ceil($k/2);
          $refun = '1';
        }
      }
      else
      {
        $name = 'Guest';
        $v = $k;
        $refun = '1';
      }
?>

<option value="<?php echo $nc->property_id;?>" guest_count="<?php echo $v;?>" refun="<?php echo $refun;?>" mapp_type="sub_room" property="<?php echo insep_encode($nc->property_id);?>" <?php if($val->property_id == $nc->property_id && $val->guest_count==$v && $val->refun_type == $refun) { ?> selected="selected" <?php } ?>> <?php echo ucfirst($nc->property_name);?> - <?php echo $v.' '.$name;?> </option>

<?php   
    }
  }
  else
  {
    for($k=1;$k<=$members;$k++)
    {
      
      if($nc->member_count < $members)
      {
        if($k%2 == 0)
        {
          $name = 'Guest Non refundable';
          $v = ceil($k/2);
          $refun=2;
        }
        else
        {
          $name = 'Guest';
          $v = ceil($k/2);
          $refun=1;
        }
      }
      else
      {
        $name = 'Guest';
        $v = $k;
        $refun=1;
        
      }
  ?>
    <option value="<?php echo $nc->property_id;?>" guest_count="<?php echo $v;?>" refun="<?php echo $refun;?>" mapp_type="sub_room" property="<?php echo insep_encode($nc->property_id);?>" <?php if($val->property_id == $nc->property_id && $val->guest_count==$v && $val->refun_type == $refun) { ?> selected="selected" <?php } ?>> <?php echo ucfirst($nc->property_name);?> - <?php echo $v.' '.$name;?> </option>
    
<?php 
    }
  }
}
else if($nc->pricing_type==1 && $nc->non_refund==1)
{
  $v=1;
  $refun=2;
?>
<option value="<?php echo $nc->property_id;?>" guest_count="<?php echo $v;?>" refun="<?php echo $refun;?>" mapp_type="sub_room" property="<?php echo insep_encode($nc->property_id);?>" <?php if($val->property_id == $nc->property_id && $val->guest_count==$v && $val->refun_type == $refun) { ?> selected="selected" <?php } ?>> <?php echo ucfirst($nc->property_name);?> - Non refundable </option>

<?php
}
?>
<?php 
    $rate_types = $this->mapping_model->get_rate_types($nc->property_id);
    if($rate_types)
    {
      foreach($rate_types as $rate)
      {

 ?>
 
 <option value="<?php echo $rate->rate_type_id;?>" guest_count="0" refun="0" mapp_type="main_rate" property="<?php echo insep_encode($nc->property_id);?>" <?php if($val->rate_id == $rate->rate_type_id && $val->guest_count=="0" && $val->refun_type == "0") { ?> selected="selected" <?php } ?>> <?php if($rate->rate_name!='') { echo ucfirst($rate->rate_name); } else { echo '#'. $rate->uniq_id;}?></option>
 
<?php 

if($rate->pricing_type==2) 
{
  if($rate->non_refund==1)
  {
    for($k=1;$k<=$members;$k++)
    {
    
      if($nc->member_count < $members)
      {
        if($k%2 == 0)
        {
          $name = 'Guest Non refundable';
          $v = ceil($k/2);
          $refun = '2';
        }
        else
        {
          $name = 'Guest';
          $v = ceil($k/2);
          $refun = '1';
        }
      }
      else
      {
        $name = 'Guest';
        $v = $k;
        $refun = '1';
      }

?>
<option value="<?php echo $rate->rate_type_id;?>" guest_count="<?php echo $v;?>" refun="<?php echo $refun;?>" mapp_type="sub_rate_room" property="<?php echo insep_encode($nc->property_id);?>" <?php if($val->rate_id == $rate->rate_type_id && $val->guest_count==$v && $val->refun_type == $refun) { ?> selected="selected" <?php } ?>> <?php if($rate->rate_name!='') { echo ucfirst($rate->rate_name); } else { echo '#'. $rate->uniq_id;}?> - <?php echo $v.' '.$name;?> </option>
<?php 
    }
  }
  else
  {
    for($k=1;$k<=$members;$k++)
    {
      
      if($nc->member_count < $members)

      {
        if($k%2 == 0)
        {
          $name = 'Guest Non refundable';
          $v = ceil($k/2);
          $refun=2;
        }
        else
        {
          $name = 'Guest';
          $v = ceil($k/2);
          $refun=1;
        }
      }
      else
      {
        $name = 'Guest';
        $v = $k;
        $refun=1;
        
      }

  ?>
    <option value="<?php echo $rate->rate_type_id;?>" guest_count="<?php echo $v;?>" refun="<?php echo $refun;?>" mapp_type="sub_rate_room" property="<?php echo insep_encode($nc->property_id);?>" <?php if($val->rate_id == $rate->rate_type_id && $val->guest_count==$v && $val->refun_type == $refun) { ?> selected="selected" <?php } ?>> <?php if($rate->rate_name!='') { echo ucfirst($rate->rate_name); } else { echo '#'. $rate->uniq_id;}?> - <?php echo $v.' '.$name;?> </option>
<?php 

    }
  }
}
else if($rate->pricing_type==1 && $rate->non_refund==1)
{
  $v=1;
  $refun=2;
?>
<option value="<?php echo $rate->rate_type_id;?>" guest_count="<?php echo $v;?>" refun="<?php echo $refun;?>" mapp_type="sub_rate_room" property="<?php echo insep_encode($nc->property_id);?>" <?php if($val->rate_id == $rate->rate_type_id && $val->guest_count==$v && $val->refun_type == $refun) { ?> selected="selected" <?php } ?>> <?php if($rate->rate_name!='') { echo ucfirst($rate->rate_name); } else { echo '#'. $rate->uniq_id;}?> - Non refundable </option>

<?php
}
?>
<?php     }
    }?>

<?php } 
}
?>
</select>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="col-md-12 col-sm-12 cls_comm_in">
                <h5>Room Rate Mapping</h5>
                <h4 class="cls_headsm">Settings</h4>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12 cls_resp50">
                <div class="row form-group">
                  <label class="comm_label col-md-3 col-sm-4 col-xs-12">Enable </label>
                  <div class="radio-inline col-md-9 col-sm-8 col-xs-12 text-left">
                    <label class="rad">
                    <input type="radio" value="enabled" <?php if($optionenable=='enabled') { ?>checked="checked" <?php } ?> id="inlineRadio2" name="optionenable">
                    <i></i> Enabled </label>
                    <label class="rad">
                     <input type="radio" value="disabled" <?php if($optionenable=='disabled') { ?>checked="checked" <?php } ?> id="inlineRadio3" name="optionenable">
                    <i></i> Disabled </label>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>

              <?php
if(insep_decode($channel_id)=='17')
{
  $add_class = 'bnow_map';
?>
<div class="form-group">
<label class="col-sm-3 control-label" for="inputPassword3">Price Type</label>
<div class="col-sm-6">
<label class="radio-inline">
<input type="radio" value="BRP" <?php if($bnowlevel=='BRP') { ?>checked="checked" <?php } ?> id="price_type1" name="price_type" class="price_type"> Base Room Price
</label>
<label class="radio-inline">
<input type="radio" value="OBP" <?php if($bnowlevel=='OBP') { ?>checked="checked" <?php } ?> id="price_type2" name="price_type" class="price_type"> Occupancy Based Price
</label>
</div>
</div>
<?php
}
else
{
  $add_class='';
}
?>
<?php
if($mapping_values)
{ ?>
              <div class="col-md-6 col-sm-6 col-xs-12 cls_resp50">
                <div class="form-group">
                  <label>Rate Conversion Multiplier</label>
                  <input type="text" name="rate_conversion" placeholder="" id="inputEmail3" class="form-control cls_tbox2" value="<?php echo $rate_conversion; ?>">
                  <p class="cls_labeltxt">Optional Decimal number Cannot be lower than 0.5</p>
                </div>
              </div>


              <?php 
/* echo $bnowlevel;
echo '<pre>';
  print_r($mapping_values); */
  if($bnowlevel=='BRP')
  {
    $label = str_replace(array('- Room Rrice For Single Adult ','- Room Rrice For Two Adults ','- Room Rrice For Three Adults ','- Room Rrice For Four Adults ','- Room Rrice For Five Adults ','- Room Rrice For Six Adults '), array('','','',''), $mapping_values['label']);
    
    $title=str_replace(',,,,,,','Room Rrice For Single Adult,Room Rrice For Two Adults,Room Rrice For Three Adults,Room Rrice For Four Adults,Room Rrice For Five Adults,Room Rrice For Six Adults,',$mapping_values['title']);
  }
  else
  {
    $label=$mapping_values['label'];
    $title=$mapping_values['title'];
  }
  $val=$mapping_values['value'];  
    
  $label_split=explode(",",$label);
  $val_split=explode(",",$val);
  $split_titile=explode(",",$title);
  $set_arr=array_combine($label_split,$val_split);
  if(insep_decode($channel_id)=='17')
  {
    $arr_count = count($set_arr);
  }
  $i=0;
  foreach($set_arr as $k=>$v)
  { 
    if(insep_decode($channel_id)=='17')
    {
      if($i+1 == $arr_count-1 || $i+1 == $arr_count)
      {
        $add_class = '';
      }
    }
  ?>
              <div class="col-md-6 col-sm-6 col-xs-12 cls_resp50 <?php echo $add_class;?>">
                <div class="form-group">
                  <label><?php echo $k;?></label>
                  <input type="text" name="<?php echo $k;?>" placeholder="" id="<?php echo $k;?>" class="form-control cls_tbox2" value="<?php echo $v;?>">
                  <p class="cls_labeltxt"><?php echo $split_titile[$i];?></p>
                </div>
              </div>
<?php 
   $i++;
  }
}
else if((insep_decode($channel_id))!='5')
{  

?>

            <div class="col-md-6 col-sm-6 col-xs-12 cls_resp50">
                <div class="form-group">
                  <label>Rate Conversion Multiplier</label>
                   <input type="text" name="rate_conversion" placeholder="" id="inputEmail3" class="form-control" value="<?php echo $rate_conversion; ?>">
                  <p class="cls_labeltxt">Optional Decimal number Cannot be lower than 0.5</p>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12 cls_resp50">
                <div class="form-group">
                  <label>Included Occupancy</label>
                   <input type="text" name="included_occupancy" placeholder="" id="inputEmail3" class="form-control" value="<?php echo $included_occupancy;?>">                  
                </div>
              </div>
              <div class="clearfix"></div>



              <div class="col-md-6 col-sm-6 col-xs-12 cls_resp50">
                <div class="form-group">
                  <label>Extra Adult rate </label>
                   <input type="text" value="<?php echo $extra_adult;?>" name="extra_adult" placeholder="" id="inputEmail3" class="form-control">                 
                </div>
              </div>
              <div class="clearfix"></div>


              <div class="col-md-6 col-sm-6 col-xs-12 cls_resp50">
                <div class="form-group">
                  <label>Extra Child Rate </label>
                  <input type="text" value="<?php echo $extra_child;?>" name="extra_child" placeholder="" id="inputEmail3" class="form-control">             
                </div>
              </div>
              <div class="clearfix"></div>

              <div class="col-md-6 col-sm-6 col-xs-12 cls_resp50">
                <div class="form-group">
                  <label>Single guest count  </label>
                   <input type="text" value="<?php echo $single_guest;?>" name="single_guest" placeholder="" id="inputEmail3" class="form-control">
                </div>
              </div>
              <div class="clearfix"></div>
<?php
}else{
?>
           

           <div class="col-md-6 col-sm-6 col-xs-12 cls_resp50">
                <div class="form-group">
                  <label>Rate Conversion Multiplier  </label>
                  <input type="text" name="rate_conversion" placeholder="" id="inputEmail3" class="form-control" value="<?php echo $rate_conversion; ?>">
                  <p class="cls_labeltxt">Optional Decimal number Cannot be lower than 0.5</p>
                </div>
              </div>
              <div class="clearfix"></div>

    <?php } ?>
<?php if((insep_decode($channel_id))=='1' )
{ 
  
?>

			<div class="col-md-6 col-sm-6 col-xs-12 cls_resp50">
  
			<div class="form-group">
			<label>Occupancy One</label>
			<input type="text" value="<?php echo $exp_occupancy;?>" class="form-control" id="occupancy_two" placeholder="" name="occupancy_two">
			<p class="cls_labeltxt"></p>
			</div>

			</div>
              <div class="col-md-6 col-sm-6 col-xs-12 cls_resp50">
                <div class="row form-group">
                  <label class="comm_label col-md-3 col-sm-4 col-xs-12">Options </label>
                  <div class="radio-inline col-md-9 col-sm-8 col-xs-12 text-left">
                    <label class="rad">
                  <?php if($explevel == "room"){?>
                  <input type="radio" value="room" checked="checked" id="room_level" name="levelmapp"> Room Level
                  <?php }else{?>
                  <input type="radio" value="room" id="room_level" name="levelmapp"> Room Level
                  <?php } ?>                    
                    </label>
                  <label class="rad">
                  <?php if($explevel == "rate"){?>
                  <input type="radio" value="rate" checked="checked" id="rate_level" name="levelmapp"> Rate Level
                  <?php } else { ?>
                  <input type="radio" value="rate" id="rate_level" name="levelmapp"> Rate Level
                  <?php }?>
                  </label>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>

<?php  } ?>

  <div class="col-md-6 col-sm-6 col-xs-12 cls_resp50">
              <div class="row form-group">
                  <label class="comm_label col-md-3 col-sm-4 col-xs-12"> Update </label>
                  <div class="checkbox-inline col-md-9 col-sm-8 col-xs-12 text-left">
                    <div class="cls_bulk_checkbox">
                    <?php if($update_rate == 1){ ?>
                    <input type="checkbox" value="1" checked="checked" id="rate" name="rate">  <label for="rate"> Rate </label>
                    <?php }else if($update_rate == 0) {?>
                    <input type="checkbox" value="1" id="rate" name="rate">  <label for="rate"> Rate </label>
                    <?php } ?>                      
                    </div>
                  <div class="cls_bulk_checkbox">
                  <?php if($update_avail == 1){?>
                  <input type="checkbox" value="1" checked="checked" id="availabilites" name="availabilites">  <label for="availabilites">Availabilities</label>
                  <?php } else if($update_avail == 0){ ?>
                  <input type="checkbox" value="1" id="availabilites" name="availabilites"> <label for="availabilites">Availabilities</label>

        <?php }?>
                      
                    </div>




              

                  </div>
                </div>
                </div>
              </div>
              <h5>Rate Config</h5>
              <!--<h4 class="cls_headsm"></h4>-->

              </div>
              <div class="col-md-6 col-sm-6 col-xs-12 cls_resp50">
                <div class="row form-group">
                  <label class="comm_label col-md-3 col-sm-4 col-xs-12">Charge Type </label>
                  <div class="radio-inline col-md-9 col-sm-8 col-xs-12 text-left">
                    <label class="rad">
                    <input type="radio" value="1" <?php if($ChargeType ==1) { ?>checked="checked" <?php } ?> id="inlineRadio10" name="ChargeTypeID">
                    <i></i> Per room per night  </label>
                    <label class="rad">
                     <input type="radio" value="2" <?php if($ChargeType==2) { ?>checked="checked" <?php } ?> id="inlineRadio11" name="ChargeTypeID">
                    <i></i> Per person per night  </label>
                  </div>

                  <div>
                   <div class="col-md-2 col-sm-2 col-xs-8 cls_resp50">
                <div class="form-group">
                  <label></label>
                  <input type="number" name="Adult1" placeholder="" id="Adult1" class="form-control cls_tbox2" value="<?php echo $Adults; ?>">
                  <p><i><font face="times, serif" size="3">Maximum Adults</font></i></p>
                </div>
              </div>
              </div>
                <div class="col-md-2 col-sm-2 col-xs-8 cls_resp50">
                <div class="form-group">
                  <label></label>
                  <input type="number" name="Children1" placeholder="" id="Children1" class="form-control cls_tbox2" value="<?php echo $Children; ?>">
                  <p><i><font face="times, serif" size="3">Maximum Children</font></i></p>
                </div>
              </div>

                <div class="col-md-2 col-sm-2 col-xs-8 cls_resp50">
                <div class="form-group">
                  <label></label>
                  <input type="number" name="Infants1" placeholder="" id="Infants1" class="form-control cls_tbox2" value="<?php echo $Infants; ?>">
                  <p><i><font face="times, serif" size="3">Maximum Infants</font></i></p>
                </div>
              </div>
                
                 <div class="cls_bulk_checkbox">
                  <?php if($extra_adult == 1){?>
                  <input type="checkbox" value="1" checked="checked" id="extra_adults" name="extra_adults">  <label for="extra_adults">Extra Adults</label>
                  <?php } else if($extra_adult == 0){ ?>
                  <input type="checkbox" value="1" id="extra_adults" name="extra_adults"> <label for="extra_adults">Extra Adults</label>

        <?php }?>
                      
                    </div>

                     <div class="cls_bulk_checkbox">
                  <?php if($extra_child == 1){?>
                  <input type="checkbox" value="1" checked="checked" id="extra_children" name="extra_children">  <label for="extra_children">Extra Children</label>
                  <?php } else if($extra_child == 0){ ?>
                  <input type="checkbox" value="1" id="extra_children" name="extra_children"> <label for="extra_children">Extra Children</label>

        <?php }?>
                      
                    </div>


                     <div class="cls_bulk_checkbox">
                  <?php if($extra_infants == 1){?>
                  <input type="checkbox" value="1" checked="checked" id="extra_infants" name="extra_infants">  <label for="extra_infants">Extra Infants</label>
                  <?php } else if($extra_infants == 0){ ?>
                  <input type="checkbox" value="1" id="extra_infants" name="extra_infants"> <label for="extra_infants">Extra Infants</label>

        <?php }?>
                      
                    </div>

                </div>
              </div>

  

              <div class="clearfix"></div>
           
              </div>

              
            </div>

             <div class="text-center">              

              <button type="button" id="save_clicking" class="cls_com_yelbtn"> Save </button> 
            </div>
            
            <br>
          </form>

        
        </div>
      </div>
  </div>
  </div>

  <?php $this->load->view('channel/dash_sidebar'); ?>  
    </div><!-- /scroller-inner -->
   
    
</div><!-- /scroller -->

</div><!-- /pusher -->

<script type="text/javascript" src="<?php echo base_url();?>user_assets/js/jquery.min.js"></script> 

<script type="text/javascript">
$(document).ready(function(){

<?php 
if(insep_decode($channel_id)==17)
{
  if($bnowlevel=='BRP')
  {
?>
$('.bnow_map').hide();
<?php
  }
}
?>
  var room_name=$( "#room_name option:selected" ).text();
  var room_id=$( "#room_name option:selected" ).val();
 /* var select_rate = $("#room_name option:selected").text();
  $('#select_rate').html(select_rate);*/
  $('#select_room').html(room_name);
  $('#room_id').val(room_id);
$.validator.addMethod('positiveNumber',
function(value) {
    return Number(value) > 0;
}, 'Enter a positive number.');

jQuery.validator.addMethod("lettersonly", 
function(value, element) {
   return this.optional(element) || /^[a-z,""]+$/i.test(value);
}, "Letters only please");

$.validator.addMethod("customemail", 
  function(value, element) {
    return /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
  }, 
  "Sorry, I've enabled very strict email validation"
);
$('#set_roommap').validate({
rules:
{
included_occupancy:
{
  required:true,
    number:true

},
extra_adult:
{
  required:true,
    number:true

},
extra_child:
{
  required:true,
    number : true

},
single_guest:
{
  required:true,
    number:true
},
    
},
errorPlacement: function (error, element) {
  return false;
},
highlight: function (element) { // hightlight error inputs
      $(element)
        .closest('.form-control').addClass('customErrorClass'); // set error class to the control group
    },
unhighlight: function (element) { // revert the change done by hightlight
      $(element)
        .closest('.form-control').removeClass('customErrorClass'); // set error class to the control group
    },
});
$('#save_clicking').click(function(){
   if($('#set_roommap').valid()){
    $('#set_roommap').submit();
   }
});
});
function set_room(val){
  var room_name=$( "#room_name option:selected" ).text();
  var room_id=$( "#room_name option:selected" ).val();
  $('#room_ids').val(room_id);
  $('#select_room').html(room_name);
  $('#room_ids').val(room_id);
}

 $('#room_name').change(function(){
  // alert('hi hello');
    var room_name=$('#room_name').val();
    // alert(room_name);
    if(room_name)
    {
     $.ajax({

     type: "POST",

     url: "<?php echo lang_url(); ?>mapping/get_rate_types",

     data: {'val':room_name},

     beforeSend : function()

     {

       $('#rate_type').html('<option>loading..</option>');

     },

     success: function(msg)

     {

      // alert(msg);

       $('#rate_type').html(msg);

     }

    });

    }

    });

function validate() 
  {

  var checkbox1 = document.getElementById('rate').checked;
  var checkbox2 = document.getElementById('availabilites').checked;
  var inlineRadio10a = document.getElementById('inlineRadio10').checked;
  var inlineRadio11a = document.getElementById('inlineRadio11').checked;
    if ((checkbox1 || checkbox2 ) == false) 
    {
      alert("Please Select Atleast one checkbox value");
      return false;
    } 

     if ((inlineRadio10a || inlineRadio11a ) == false) 
    {
      alert("Please Select a Charge Type");
      return false;
    } 



  }


    function showContent() {
        element = document.getElementById("Adult1");
        element2 = document.getElementById("Adult2");
        check = document.getElementById("inlineRadio11");
        check2 = document.getElementById("inlineRadio10");
        label2=document.getElementById("lblAdult2"); 

        if (check.checked ) {
            element.style.display='block';
            element2.style.display='block';
            label2.style.display='block';
        }
        else if (check2.checked){
            element.style.display='none';
            element2.style.display='none';
            label2.style.display='none';
        }
    }



</script>
