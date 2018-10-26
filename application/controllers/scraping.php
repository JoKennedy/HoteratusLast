<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');

class scraping extends Front_Controller {



 	public function __construct()
    {

    	require_once('simple_html_dom.php');

        parent::__construct();

        //load base libraries, helpers and models

    
    }
    public function competitiveset()
    {
        is_login();
        $hotelid=hotel_id();
        $data['page_heading'] = 'Competitive Set Analisis';
        $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
        $data= array_merge($user_details,$data);
        $data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
        $data['allRooms']=$this->allmainroom(2);
        $data['allChannel']=$this->db->query("SELECT * FROM HotelOtas where active=1")->result_array();

        $this->views('salesmarketing/competitivesetanalisis',$data);
    }
    public function InfoPrices($date1,$date2,$channelid,$roomname)
    {


      $Hotels=$this->db->query("select * from HotelsOut where HotelID =".hotel_id()." and ChannelId = ".$_POST['channelid']." and active=1 ")->result_array();
      $mapping=$this->db->query("SELECT * FROM HotelOutRoomMapping where HotelID =".hotel_id()." and ChannelId = ".$_POST['channelid']." and  trim(RoomNameLocal)=trim('$roomname[0]') and trim(MaxPleopleLocal)=trim('$roomname[1]')")->result_array();

      $vMapping='';
      foreach ($mapping as  $value) {
        $vMapping[$value['HotelOutId']][trim($value['RoomNameLocal'])][$value['MaxPleopleLocal']][]=array(trim($value['RoomOutName']),trim($value['MaxPleopleOut']));
      }

      $Info=array();
      $i=1;
      $date2=date('Y-m-d',strtotime($date2));
      $date1=date('Y-m-d',strtotime($date1));
      $minimum=0;
      $mainprice=0;
      while ($date2 >= $date1) {
        foreach ($Hotels as $hotel)
        {

            if($hotel['Main']==1)
            {
                $price=$this->db->query("select `price_room_channel`(trim('$roomname[0]'),'$date1',".$hotel['HotelsOutId'].",'".$roomname[1]."') price,
                   `room_available` (RoomID,'".date('d/m/Y',strtotime($date1))."',a.HotelId) roomavailable, RoomID roomid
                   from HotelOutRoomMappingLocal a
                  where 
                  a.HotelId=".hotel_id()." 
                  and trim(a.RoomNameLocal) =trim('$roomname[0]')
                  and trim(a.MaxPleopleLocal) =trim('$roomname[1]')
                  and a.ChannelId=2
                  limit 1")->row_array();

                $roomava=explode(',',$price['roomavailable']);
                $Info[$date1][$hotel['HotelsOutId']][$roomname[0]]=$price['price'];
                $Info[$date1]['minimum']=doubleval(trim(str_replace('$', '',$price['price'])));
                $Info[$date1]['mainprice']=doubleval(trim(str_replace('$', '',$price['price'])));
                $Info[$date1]['roomavailable']=$roomava[0];
                $Info[$date1]['occupation']=(($roomava[1]-$roomava[0])/($roomava[1]==0?1:$roomava[1]))*100 ;
                $Info[$date1]['roomid']=$price['roomid'];
                $mainprice=doubleval(trim(str_replace('$', '',$price['price'])));
            }
            else
            {
              if(isset($vMapping[$hotel['HotelsOutId']][trim($roomname[0])][trim($roomname[1])]))
              {
                foreach ($vMapping[$hotel['HotelsOutId']][trim($roomname[0])][trim($roomname[1])] as  $value) {
                    $price=$this->db->query("select `price_room_channel`(trim('$value[0]'),'$date1',".$hotel['HotelsOutId'].",trim('$value[1]')) price")->row_array();
                    $Info[$date1][$hotel['HotelsOutId']][$value[0]]=$price['price'];
                    if($Info[$date1]['minimum']==0)
                    {
                        $Info[$date1]['minimum']=doubleval(trim(str_replace('$', '',$price['price'])));
                    }
                    else if(doubleval(trim(str_replace('$', '',$price['price'])))>0)
                    {
                      $Info[$date1]['minimum']=($Info[$date1]['minimum']<doubleval(trim(str_replace('$', '',$price['price'])))  ?$Info[$date1]['minimum']:doubleval(trim(str_replace('$', '',$price['price'])))) ;
                    }
                }
              }
            }
        }

        $date1=date('Y-m-d',strtotime($date1.'+1 days'));
        $i++;

      }
      return $Info;
    }
    public function DisplayHTML()
    {

        $joninfo['html']='';
        $joninfo['json']='';

        $date1=$_POST['yearid'].'-'.$_POST['monthid'].'-01';
        $primerdia = new DateTime($date1);
        $primerdia->modify('first day of this month');
        $ultimodia = new DateTime($date1);
        $ultimodia->modify('last day of this month');
        $date2=$_POST['yearid'].'-'.$_POST['monthid'].'-'.$ultimodia->format('d');

            $fecha1 = new DateTime();
            $fecha2 = new DateTime($date1);
            $resultado = $fecha1->diff($fecha2);
        $canal=$this->db->query("select * from HotelOutRoomMapping where HotelId=".hotel_id()." and ChannelId=".$_POST['channelid'])->result_array();

        if(count($canal)==0)
        {
          $joninfo['html']= '<center ><h1><span class="label label-danger">No Room Mapping</span></h1></center>';
           echo json_encode($joninfo);
          return;
        }

        if($resultado->format('%R%a')<-31)
        {
          $joninfo['html']= '<center><h1><span class="label label-danger">you can'."'".'t search past dates</span></h1></center>';
          echo json_encode($joninfo);
          return;
        }
        if($resultado->format('%R%a')>181)
        {
          $joninfo['html']=  '<center><h1><span class="label label-danger">You can only search up to 6 months</span></h1></center>';
           echo json_encode($joninfo);
          return;
        }
        $roomnameinfo=explode(',',$_POST['roomname']);

        $roominfo=$this->InfoPrices($date1,$date2,$_POST['channelid'],$roomnameinfo);

        $joninfo['json']=json_encode($roominfo);

        $month=array("1"=>"January","2"=>"February","3"=>"March","4"=>"April","5"=>"May","6"=>"June","7"=>"July","8"=>"August","9"=>"September","10"=>"October","11"=>"November","12"=>"December");

        $html='<table    class="tablanew" border=1 cellspacing=0 cellpadding=2 bordercolor="#B2BABB" > ';
        $header1='<thead> <tr> <th style="text-align:center; ">Room Type Name </th>';
        $header2=' <thead> <tr>  <th bgcolor="#E5E7E9"></th>';
        for ($i=1; $i <= $ultimodia->format('d')  ; $i++) {
          if ($i==1)
          {
            $header1 .='<th  COLSPAN="'.$ultimodia->format('d').'" style="text-align:center; margin: 15px; padding: 15px;"><strong>'.$month[$_POST['monthid']].'</strong> </th>';
          }
          $header2.= '<th bgcolor="'.($i%2?'#FBFCFC':'#E5E7E9').'" style=" font-size:18px; text-align:center;  margin: 10px; padding: 5px;">'.$i.'</th>';
        }
        $header1 .=' </tr> </thead> ';
        $header2 .='	</tr> </thead>';

        $body='<tbody>';
        $hotels=$this->db->query("select * from HotelsOut where HotelID =".hotel_id()." and ChannelId = ".$_POST['channelid']." and active=1 and main=0")->result_array();
        $mainhotel=$this->db->query("select * from HotelsOut where HotelID =".hotel_id()." and ChannelId = ".$_POST['channelid']." and active=1 and main=1")->result_array();
        foreach ($mainhotel as $main) {
            $precio='<tr>';
            $ava='<tr>';
            $ocupan='<tr >';
            $body .='<tr>  <td COLSPAN="'.($ultimodia->format('d')+1).'" style="width:200px; margin: 5px; padding:5px;"><center><h4><span class="label label-primary">'.$main['HotelName'].'</span></h4></center></td> </tr> ';
            $room2='';
            $datecurrent=date('Y-m-d',strtotime($date1."+0 days"));
            $r='';

            for ($i=1; $i <=$ultimodia->format('d') ; $i++) {

                foreach ($roominfo[$datecurrent][$main['HotelsOutId']] as $key => $priceinfo) {
                  if($i==1  )
                  { $r=$key;
                    $precio.='<td bgcolor="'.(($i-1)%2?'#FBFCFC':'#E5E7E9').'"  style=" font-size: 10px; text-align:center;" > <h5><span class="label label-info"> '.$key.'</span></h5></td>';
                    $ava.='<td bgcolor="'.(($i-1)%2?'#FBFCFC':'#E5E7E9').'"  style=" font-size: 10px; text-align:center;" > <h5><span class="label label-success"> Availability</span></h5></td>';
                    $ocupan.='<td bgcolor="'.(($i-1)%2?'#FBFCFC':'#E5E7E9').'"  style=" font-size: 10px; text-align:center;" > <h5><span class="label label-warning">Occupancy</span></h5></td>';
                  }

                  $precio.='<td bgcolor="'.($i%2?'#FBFCFC':'#E5E7E9').'"  style="font-size: 10px; text-align:center;" >'.(is_numeric($priceinfo)?round($priceinfo,2):$priceinfo).'</td>';
              
                    $ava.='<td bgcolor="'.($i%2?'#b3f5a4':'#4aaa34').'"  style="font-size: 10px; text-align:center;" >'.$roominfo[$datecurrent]['roomavailable'].'</td>';
                  $ocupan.='<td bgcolor="'.($i%2?'#f2f58d':'#bec246').'"  style="font-size: 10px; text-align:center;" >'.$roominfo[$datecurrent]['occupation'].'%</td>';
                  
                  
                  $datecurrent=date('Y-m-d',strtotime($date1."+$i days"));
                }

              }
            $precio.='</tr>';
            $ava.='</tr>';
            $ocupan.='</tr>';
            $body .=$precio.$ava.$ocupan;
        }
        foreach ($hotels as $hotel) {
          $datecurrent=date('Y-m-d',strtotime($date1."+0 days"));
          if(isset($roominfo[$datecurrent][$hotel['HotelsOutId']]))
          {
                $precio='<tr>';
                $body .='<tr>  <td COLSPAN="'.($ultimodia->format('d')+1).'" style=""width:200px; margin: 5px; padding:5px;"><center><h4><span class="label label-primary">'.$hotel['HotelName'].'</span></h4></center></td> </tr> ';
                $roomcount=count($roominfo[$datecurrent][$hotel['HotelsOutId']]);
                $precio2=($roomcount==2?'<tr>':'');
                $r1='';
                $r2='';
                for ($i=1; $i <=$ultimodia->format('d') ; $i++) {
                    foreach ($roominfo[$datecurrent][$hotel['HotelsOutId']] as $key =>  $priceinfo) {
                      if($r1=='' && $i==1  )
                      { $r1=$key;
                        $precio.='<td bgcolor="'.(($i-1)%2?'#FBFCFC':'#E5E7E9').'"  style=" font-size: 10px; text-align:center;" ><h5><span class="label label-info">'.$key.'</span></h5></td>';
                      }

                      if($roomcount==2 && $r1!=$key && $i==1 && $r2=='' )
                      {$r2=$key;
                        $precio2.='<td bgcolor="'.(($i-1)%2?'#FBFCFC':'#E5E7E9').'"  style=" font-size: 10px; text-align:center;" ><h5><span class="label label-info">'.$key.'</span></h5></td>';
                      }

                      if($key==$r1)
                      {
                        $precio.='<td bgcolor="'.(($i)%2?'#FBFCFC':'#E5E7E9').'"  style=" font-size: 10px; text-align:center;" >'.(is_numeric($priceinfo)?round($priceinfo,2):$priceinfo).'</td>';
                      }
                      if($key==$r2)
                      {
                        $precio2.='<td bgcolor="'.(($i)%2?'#FBFCFC':'#E5E7E9').'"  style=" font-size: 10px; text-align:center;" >'.(is_numeric($priceinfo)?round($priceinfo,2):$priceinfo).'</td>';
                      }

                      $datecurrent=date('Y-m-d',strtotime($date1."+$i days"));
                    }
                }

                $precio.='</tr>';
                $precio2.=($roomcount==2?'</tr>':'');

                $body .=$precio.$precio2;
            }
        }

        $precio='<tr>';
        $body .='<tr>  <td COLSPAN="'.($ultimodia->format('d')+1).'" style="width:200px; margin: 5px; padding:5px;"><center><h4><span class="label label-warning">Suggested Rate</span></h4></center></td> </tr> ';
        $precio.='</tr>';

        $datecurrent=date('Y-m-d',strtotime($date1."+0 days"));
        for ($i=1; $i <=$ultimodia->format('d') ; $i++) {

              if($i==1  )
              {
                $precio.='<td bgcolor="'.(($i-1)%2?'#FBFCFC':'#E5E7E9').'"  style=" font-size: 10px; text-align:center;" > <h5><span class="label label-info"> '.$roomnameinfo[0].'</span></h5></td>';
              }
              if($roominfo[$datecurrent]['mainprice']==0)
              {
                $p='<h5><span class="label label-default">SOLD</span></h5>';
              }
              else if($roominfo[$datecurrent]['mainprice']<=$roominfo[$datecurrent]['minimum'])
              {
                $p='<h5><span class="label label-success">BEST</span></h5>';
              }
              else {
                $p=$roominfo[$datecurrent]['minimum']-($roominfo[$datecurrent]['minimum']*0.03);
                $p='<h5><span class="label label-warning">'.round($p, 2).'</span></h5>';
              }
              $precio.='<td bgcolor="'.($i%2?'#FBFCFC':'#E5E7E9').'"  style="font-size: 10px; text-align:center;" >'.$p.'</td>';
              $datecurrent=date('Y-m-d',strtotime($date1."+$i days"));
          }
          $body .=$precio;
        $body .='</tbody> </table> <center><a onclick="BulkUpdate()" class="btn green"><i class="fas fa-calendar-check"></i><span>Update The Channels with These rates?</span></a></center>';
        $joninfo['html']=  $html.$header1.$header2.$body;

        echo json_encode($joninfo);

    }
    public function config()
    {
    	is_login();
      $hotelid=hotel_id();
      $data['page_heading'] = 'Configuration Competitive Set Analisis';
      $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
      $data= array_merge($user_details,$data);
      $data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
      $data['allRooms']=$this->db->query("select a.*, case a.pricing_type when 1 then 'Room based pricing' when 2 then 'Guest based pricing' else 'Not available' end  PricingName, case when b.meal_name is null then 'No Plan' else b.meal_name end meal_name   from manage_property a left join meal_plan b on a.meal_plan=meal_id where hotel_id=$hotelid")->result_array();
      $data['AllOtas']=$this->db->query("select * from HotelOtas where active =1")->result_array();
      $this->views('salesmarketing/config',$data);
    }
    public function savemaping()
    {
    	$map=explode(',', $_POST['pk']);
    	$value=explode(',',$_POST['value']);
    	$RoomOutName=$map[0];
    	$HotelOutId=$map[1];
    	$ChannelId=$map[2];
      $maxout=$map[3];

    	$hotelid=hotel_id();

    	$info=$this->db->query("select * from HotelOutRoomMapping where ChannelId =$ChannelId  and trim(RoomOutName) =trim('$RoomOutName')  and HotelId=$hotelid and trim(MaxPleopleOut)=trim('$maxout')")->row_array();

    	if(count($info)>0)
    	{

    		$data['RoomNameLocal']=trim($value[0]);
        $data['MaxPleopleLocal']=trim($value[1]);
    		update_data('HotelOutRoomMapping',$data,array('ChannelId'=>$ChannelId,'RoomOutName' =>trim($RoomOutName),'HotelId'=>$hotelid, 'MaxPleopleOut'=>trim($maxout)));
    	}
    	else
    	{
    		$data['RoomOutName']=$RoomOutName;
        $data['MaxPleopleOut']=$maxout;
    		$data['HotelId']=$hotelid;
    		$data['RoomNameLocal']=trim($value[0]);
        $data['MaxPleopleLocal']=trim($value[1]);
    		$data['HotelOutId']=$HotelOutId;
    		$data['ChannelId']=$ChannelId;
    		insert_data('HotelOutRoomMapping',$data);
    	}
    	$result['success']=true;
    	echo json_encode($result);

    }
     public function savemapinglocal()
    {
      
      $map=explode(',', $_POST['pk']);
      $value=$_POST['value'];
      $RoomLocalName=$map[0];
      $HotelOutId=$map[1];
      $ChannelId=$map[2];
      $maxout=$map[3];

      $hotelid=hotel_id();

      $info=$this->db->query("select * from HotelOutRoomMappingLocal where ChannelId =$ChannelId  and trim(RoomNameLocal) =trim('$RoomLocalName')  and HotelId=$hotelid and trim(MaxPleopleLocal)=trim('$maxout')")->row_array();

      if(count($info)>0)
      {

        $data['RoomID']=$value;
        update_data('HotelOutRoomMappingLocal',$data,array('ChannelId'=>$ChannelId,'RoomNameLocal' =>trim($RoomLocalName),'HotelId'=>$hotelid, 'MaxPleopleLocal'=>trim($maxout)));
      }
      else
      {
        $data['RoomID']=$value;
        $data['HotelId']=$hotelid;
        $data['RoomNameLocal']=$RoomLocalName;
        $data['ChannelId']=$ChannelId;
        $data['MaxPleopleLocal']=trim( $maxout);
        $data['Active']=1;
        insert_data('HotelOutRoomMappingLocal',$data);
      }
      $result['success']=true;
      echo json_encode($result);

    }
    public function saveproperty()
    {
    	$dato=array();
      foreach ($_POST as $key => $value) {
      	$room=explode('_',$key);

      	$data[$room[0]][$room[1]][$room[2]][$room[3]]=$value;
      }

      foreach ($data as $tipo => $canales) {


        if($tipo=='new')
        {

          foreach ($canales as $canalid => $info) {
            foreach ($info as $updateid => $infoto) {

              if(strlen($infoto[1])==0)continue;
              $savedata=array();
              $savedata['HotelName']=$infoto[1];
              $savedata['HotelNameChannel']=$infoto[2];
              $savedata['MinimumStay']=$infoto[3];
              $savedata['ChannelId']=$canalid;
              $savedata['HotelID']=hotel_id();
              $savedata['Active']=1;
              $savedata['Main']=0;
              insert_data('HotelsOut',$savedata);
            }
          }
        }
        else if($tipo=='main')
        {
          foreach ($canales as $canalid => $info) {
            foreach ($info as $updateid => $infoto) {


              $savedata=array();
              $savedata['HotelName']=$infoto[1];
              $savedata['HotelNameChannel']=$infoto[2];
              $savedata['MinimumStay']=$infoto[3];
              $exist=$this->db->query("select * from HotelsOut where HotelsOutId=$updateid and HotelID=".hotel_id()." and Main=1 and ChannelId=$canalid")->row_array();
              if(!isset($exist['HotelsOutId']))
              {
                $savedata['ChannelId']=$canalid;
                $savedata['HotelID']=hotel_id();
                $savedata['Active']=1;
                $savedata['Main']=1;
                insert_data('HotelsOut',$savedata);

              }
              else {
                update_data('HotelsOut',$savedata,array('HotelsOutId'=>$updateid,'HotelID'=>hotel_id(),'Main'=>1));
              }
            }
          }
        }
        else {

          foreach ($canales as $canalid => $info) {
            foreach ($info as $updateid => $infoto) {
              if(strlen($infoto[1])==0) {$this->db->query("delete from HotelsOut where HotelsOutId=$updateid and HotelID=".hotel_id()." and Main=0"); continue;}
              $savedata=array();
              $savedata['HotelName']=$infoto[1];
              $savedata['HotelNameChannel']=$infoto[2];
              $savedata['MinimumStay']=$infoto[3];
              update_data('HotelsOut',$savedata,array('HotelsOutId'=>$updateid,'HotelID'=>hotel_id()));
            }
          }
        }
      }

      $result['success']=true;
      echo json_encode($result);

    }
    function test2()
    {

      $date= new Datetime();

      $agent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36";

      $referer = '';
      $cookies = 'cookies.txt';
      $content= $this->cURL("https://www.expedia.com/infosite-api/1369424/getOffers?clientid=KLOUD-HIWPROXY&token=94ddf5664063164cac75a053876961d479aab676&brandId=4671&countryId=50&isVip=false&chid=&partnerName=HSR&partnerCurrency=USD&partnerTimestamp=".$date->getTimestamp()."&adults=2&children=0&chkin=1%2F26%2F2019&chkout=1%2F27%2F2019&hwrqCacheKey=06b9d0da-7fef-40fc-9003-419316a65be5HWRQ1540560018350&cancellable=false&regionId=6048790&vip=false&=undefined&exp_dp=173.33&exp_ts=".$date->getTimestamp()."&exp_curr=USD&swpToggleOn=false&exp_pg=HSR&daysInFuture=&stayLength=&ts=".$date->getTimestamp()."&evalMODExp=true&tla=DCF", '', $cookies, $referer, '',$agent);

      $html= $content;


    }

    public function allmainroom($ChannelId,$opt=0)
    {
      $allroom= $this->db->query("select concat(trim(b.RoomName),',',trim(b.MaxPeople)) value,  concat(b.RoomName,'-',b.MaxPeople) text
      from HotelsOut a
      left join HotelScrapingInfo b on a.HotelsOutId=b.HotelOutId
      where
      a.hotelid=".hotel_id()."
      and a.main=1
      and a.ChannelId=$ChannelId
      group by b.RoomName,b.MaxPeople,b.ChannelId ")->result_array();


      if($opt==1)
      {

        echo json_encode($allroom);
      }
      else {

        return $allroom;
      }
    }
    public function findroomtype($ChannelId='')
    {
      ignore_user_abort(true);
      set_time_limit(0);
        $ChannelId=($ChannelId==''?$_POST['channelid']:$ChannelId);

        switch ($ChannelId) {
          case '1':
            $result['success']=false;
            $result['message']='Channel Expedia not working by the moment';
            break;
          case '2':

                $ConfigHoteles=$this->db->query("SELECT * FROM HotelsOut where active=1 and ChannelId=2 and HotelID=".hotel_id())->result_array();
                $date=date('Y-m-d');
                $start=9;
                foreach ($ConfigHoteles as  $HotelInfo) {

                   for ($i=$start; $i <($start+10) ; $i++) {

                    $this->ScrapearBooking(date('Y-m-d',strtotime($date."+$i days")),$HotelInfo['HotelNameChannel'],$HotelInfo['HotelsOutId'],$HotelInfo['HotelID'],$HotelInfo['ChannelId'],$HotelInfo['MinimumStay']) ;
                  }
                  echo "Propiedad ".$HotelInfo['HotelID'].'<br>';
                }
                if(count($ConfigHoteles)>0)
                {
                  $result['success']=true;
                  $result['message']='All Rooms Type Were import!!';
                }
                else{
                  $result['success']=false;
                  $result['message']='You must config all hotel before importing rooms type!!';
                }
            break;
          default:
          $result['success']=false;
          $result['message']='This Channel not working by the moment';
          break;
            break;
        }

        echo json_encode($result);
    }
    public function ScrapearBooking($date,$HotelNameOut,$HotelOutId,$HotelId,$ChannelId,$MinimumStay)
  	{
  		$date1=$date;
  		$date2=date('Y-m-d',strtotime($date."+$MinimumStay days"));

  		$agent="Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36";

  		$referer = 'http://www.booking.com';
  		$cookies = 'cookies.txt';
  		$content= $this->cURL("https://www.booking.com/hotel/$HotelNameOut.html?checkin=$date1;checkout=$date2", '', $cookies, $referer, '',$agent);

  		$html= html_to_dom($content);

  		$result='';
  		$roomname='';

  		foreach ($html->find('#available_rooms') as $Rooms) {

  			foreach ($Rooms->find('tr') as $value) {

  			 	if(strlen($value->find('.hprt-roomtype-name .hprt-roomtype-icon-link',0))>0)
  			 	{
  			 		$roomname=$value->find('.hprt-roomtype-name .hprt-roomtype-icon-link',0)->text();

  			 	}
    			 	if (strlen($roomname)>0 && strlen($value->find('.invisible_spoken',0))>0 && count($value->find('.hprt-price-price'))>0) {

    			 		$person=$value->find('.invisible_spoken',0)->text();
    			 		$prices=$value->find('.hprt-price-price',0)->text();
             $prices=doubleval(substr(trim($prices), strpos(trim($prices), '$')+1));


              for ($i=0; $i <$MinimumStay ; $i++) { 
                $date3=date('Y-m-d',strtotime($date."+$i days"));
                $info['ChannelId']=$ChannelId;
                $info['RoomName']=trim($roomname);
                $info['HotelOutId']=$HotelOutId;
                $info['MaxPeople']=(string)trim($person);
                $info['DateCurrent']=$date3;
                $info['Prices']=($prices/$MinimumStay);
                insert_data('HotelScrapingInfo',$info);
              }
    			 		
    			 	}

  			 }

  		}


		    return;
	  }
  	public function scrapear2($date)
  	{
  		$date1=$date;
  		$date2=date('Y-m-d',strtotime($date."+1 days"));


  		$referer = 'http://www.hotelhunter.com';
  		$cookies = 'cookies2.txt';
  		$content= $this->cURL("https://www.hotelhunter.com/Hotel/Search?checkin=2018-10-01&checkout=2018-10-02&Rooms=1&adults_1=2&fileName=Lifestyle_Tropical_Beach_Resort_Spa&currencyCode=USD&languageCode=EN", '', $cookies, $referer, '','Mozilla/5.0 (Windows; U; Windows NT 5.1; es-MX; rv:1.8.1.13) Gecko/20080311 Firefox/3.6.3');

  		while ($content=='Forbidden') {
  			$content= $this->cURL("https://www.hotelhunter.com/Hotel/Search?checkin=2018-10-01&checkout=2018-10-02&Rooms=1&adults_1=2&fileName=Lifestyle_Tropical_Beach_Resort_Spa&currencyCode=USD&languageCode=EN", '', $cookies, $referer, '','Mozilla/5.0 (Windows; U; Windows NT 5.1; es-MX; rv:1.8.1.13) Gecko/20080311 Firefox/3.6.3');
  		}
  		$html= html_to_dom(str_replace('data-providername', 'name', $content));

  		$result='';
  		foreach ($html->find('#hc_htl_pm_rates_content') as $tarifainfo) {
  		     	$result.= $date1.'combied';
  			foreach ($tarifainfo->find('.hc-ratesmatrix__dealsrow') as  $value) {

  				if ($value->name=='Booking.com' || $value->name=='Agoda.com') {

  					$result.=  $value->name;
  					$result.=  $value->find('.hc-ratesmatrix__roomrate',0);
  					$result.=  $value->find('.hc-ratesmatrix__roomname',0).'<br>';
  				}


  			}

  		}


  		return ($result==''?$html:$result);
  	}
    public function clearscraping()
    {
       $this->db->query("delete FROM HotelScrapingInfo  where TIMESTAMPDIFF(MINUTE ,CreateDate,now() ) >1440 and HotelScrapingInfoId <>0");
    }
	public function ScrapingBooking($start)
	{
    ignore_user_abort(true);
    set_time_limit(0);
   
    $ConfigHoteles=$this->db->query("SELECT a.*,CreateDate
    FROM HotelsOut a
    left join HotelScrapingInfo b on a.HotelsOutId =b.HotelOutId
    where a.active=1 
    and a.ChannelId=2 
    group by HotelOutId
    order by max(b.CreateDate) asc 
    limit 3")->result_array();

		$date=date('Y-m-d');
		foreach ($ConfigHoteles as  $HotelInfo) {

			 for ($i=$start; $i <($start+30) ; $i++) {

				$this->ScrapearBooking(date('Y-m-d',strtotime($date."+$i days")),$HotelInfo['HotelNameChannel'],$HotelInfo['HotelsOutId'],$HotelInfo['HotelID'],$HotelInfo['ChannelId'],$HotelInfo['MinimumStay']) ;
			}
			echo "Propiedad ".$HotelInfo['HotelID'].'<br>';
		}
	}

	function index()
	{

		$date=date('Y-m-d',strtotime('2018-12-01'));
		$result='';



		for ($i=0; $i <1 ; $i++) {


			echo $i;

		}
		echo $result;

	return;
	// Create DOM from URL or file

		$html = file_get_html('https://www.hotelscombined.com/Hotel/Search?checkin=2018-10-07&checkout=2018-10-08&Rooms=1&adults_1=2&currencyCode=DOP&fileName=Lifestyle&languageCode=EN');
		//$html = file_get_html('https://www.booking.com/searchresults.es.html?&ss=Puerto+Plata+Province&ssne=Puerto+Plata+Province&ssne_untouched=Puerto+Plata+Province&region=1265&checkin_monthday=28&checkin_month=9&checkin_year=2018&checkout_monthday=29&checkout_month=9&checkout_year=2018&no_rooms=1&group_adults=2&group_children=0&b_h4u_keep_filters=&from_sf=1');

		echo $html; return;
	$i = 1;
		foreach ($html->find('li.channels-content-item') as $video) {

		        //echo $video;
		       foreach ($video->find('div.yt-lockup-content') as  $title) {

		       	echo $title->find('a.yt-uix-sessionlink', 0)->title;
		       	echo '<br>';

		       }

		        $i++;
		}
	return;
		echo $html;
		return;

	// creating an array of elements
		$videos = [];

		// Find top ten videos
		$i = 1;
		foreach ($html->find('li.expanded-shelf-content-item-wrapper') as $video) {
		        if ($i > 10) {
		                break;
		        }

		        // Find item link element
		        $videoDetails = $video->find('a.yt-uix-tile-link', 0);

		        // get title attribute
		        $videoTitle = $videoDetails->title;

		        // get href attribute
		        $videoUrl = 'https://youtube.com' . $videoDetails->href;

		        // push to a list of videos
		        $videos[] = [
		                'title' => $videoTitle,
		                'url' => $videoUrl
		        ];

		        $i++;
		}

		var_dump($videos);
		}

	    function cURL($url, $posts, $cookies, $referer, $proxy,$agent){
	    $headers = array (
	        'Accept-Language: en-US;q=0.6,en;q=0.4',
	    );

	    $tiempo = time();


	    //Mozilla/5.0 (Windows; U; Windows NT 5.1; es-MX; rv:1.8.1.13) Gecko/20080311 Firefox/3.6.3";

	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	    curl_setopt($ch, CURLOPT_HEADER, 1);
	    curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($ch, CURLOPT_REFERER, $referer);
	    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
	    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	    if($proxy){
	        if(stristr($proxy, '@')){
	            $datosproxy = explode('@', $proxy);
	            curl_setopt($ch, CURLOPT_PROXY, $datosproxy[1]);
	            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $datosproxy[0]);
	            //echo $datosproxy[0];
	        }else{
	            curl_setopt($ch, CURLOPT_PROXY, $proxy);
	        }
	    }
	    if($posts){
	        curl_setopt($ch, CURLOPT_POST, 1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $posts);
	    }
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    $page = curl_exec($ch);
	    curl_close($ch);

	    if($page){
	        return $page;
	    }
	    return 'Forbidden';
	}




}
