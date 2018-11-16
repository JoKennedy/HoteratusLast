<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');

class developer extends Front_Controller {



 	  public function __construct()
    {
        parent::__construct();

    }
    public function task()
    {
      is_login();
      $hotelid=hotel_id();
      $data['page_heading'] = 'Developer Task';
      $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
      $data= array_merge($user_details,$data);
      $data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
      $data['Developers']=get_data('Developers',array('active'=>1))->result_array();
      $data['DeveloperTaskStatus']=get_data('DeveloperTaskStatus',array('active'=>1))->result_array();
      $this->views('developer/task',$data);
    }
    function taskhtml()
    {
      $status=$_POST['status'];
      $developer=$_POST['developerid'];

      $TaskInfo=$this->db->query("select a.*,concat(b.fname,' ',b.lname) usercreate
      from DeveloperTask a
      left join manage_users b on a.usercreatedid=b.user_id
      where (StatusId=$status or $status=0)
      and  (DeveloperAssignedId=$developer or $developer=0) ")->result_array();

      $html='';
        $html.= '<div class="graph-visual tables-main">
          <div class="graph">
              <div class="table-responsive">
                      <table id="myTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                          <thead>
                              <tr style="height:2px;">
                                  <th>#</th>
                                  <th>'.infolang('subject').'</th>
                                  <th>'.infolang('createby').'</th>
                                  <th width="10%">'.infolang('developer').'</th>
                                  <th>'.infolang('created').'</th>
                                  <th>'.infolang('percentage').'</th>
                                  <th><center>'.infolang('status').'</center></th>
                                  <th width="5%">'.infolang('action').'</th>
                              </tr>
                          </thead>
                          <tbody>';



                  if (count($TaskInfo)>0) {
                      $i=0;
                      foreach ($TaskInfo as  $value) {
                          $i++;
                          $class=($value['PercentageProccess']<=10?'danger':($value['PercentageProccess']<=20?'warning':($value['PercentageProccess']<=50?'info':($value['PercentageProccess']<100?'inverse':'success'))));

                          $developer='<a style="padding: 0px;" href="#" class="inline_username" data-type="select" data-name="DeveloperAssignedId"  data-pk="'.$value['DeveloperTaskId'].'" data-value="'.$value['DeveloperAssignedId'].'" data-source="'.lang_url().'developer/developerlist" title="Select a Developer"></a>';
                          $status='<a style="padding: 0px;" href="#" class="inline_username" data-type="select" data-name="StatusId"  data-pk="'.$value['DeveloperTaskId'].'" data-value="'.$value['StatusId'].'" data-source="'.lang_url().'developer/developerstatuslist" title="Select a Status"></a>';
                          $percentage='<a style="padding: 0px;" href="#" class="inline_username" data-type="number" data-name="PercentageProccess" data-pk="'.$value['DeveloperTaskId'].'" data-value="'.$value['PercentageProccess'].'" title="Change Percentege"></a>';
                          $html.=' <tr id="row'.$value['DeveloperTaskId'].'" scope="row" class="active"> <th scope="row">'.$i.'</th>
                          <td><a onclick="viewtask('.$value['DeveloperTaskId'].')">'.$value['SubjectTask'].'</a></td>
                          <td>'.$value['usercreate'].'</td>
                          <td>'.$developer.'</td>
                          <td>'.date('m/d/Y',strtotime($value['DateCreation'])).'</td>
                          <td align="center"> <span id="percentage'.$value['DeveloperTaskId'].'" class="percentage">'.$percentage.'%</span> <div class="progress progress-striped active">
                          <div id="class'.$value['DeveloperTaskId'].'" class="progress-bar progress-bar-'.$class.'" style="width: '.$value['PercentageProccess'].'%"></div></div></td>
                          <td><center>'.$status.'</center></td>
                          <td><center><a onclick="deletetask('.$value['DeveloperTaskId'].')"><i class="fas fa-trash-alt"></i></a></center></td>';

                      }
                       $html.='</tbody> </table> </div></div></div>';
                       echo $html;
                  }
                  else
                  {
                    $html='<center><h1><span class="label label-danger">'.infolang('norecordfound').'</span></h1></center>';
                    echo $html;
                  }

    }
    public function deletetask()
    {
      $this->db->query("delete from DeveloperTask where DeveloperTaskId=".$_POST['id']);
    }
    public function developerlist()
    {
      $developer= $this->db->query("select DeveloperId value, concat(FirstName,' ',LastName) text from Developers where active=1")->result_array();
        echo json_encode($developer);
    }
    public function developerstatuslist()
    {
      $developer= $this->db->query("select DeveloperTaskStatusId value, Description text from DeveloperTaskStatus where active=1")->result_array();
        echo json_encode($developer);
    }

    public function savechange()
    {
      $data[$_POST['name']]=$_POST['value'];
      $where['DeveloperTaskId']=$_POST['pk'];
      $result['success']=false;
      if(update_data('DeveloperTask',$data,$where))
      {
        $result['success']=true;
      }

      echo json_encode($result);
    }

    public function savetask()
    {
      $fileattached='';
      $errores='';

      if (isset($_FILES["Image"]))
  		{
  			if(count($_FILES["Image"])>0)
  			{


          $file = $_FILES["Image"];
  				for ($i=0; $i < count($_FILES["Image"]["tmp_name"]); $i++) {

              if(strlen($file["name"][$i])>0)
              {
                $nombre = $file["name"][$i] ;
                 $tipo = $file["type"][$i];
                 $ruta_provisional = $file["tmp_name"][$i];
                 $size = $file["size"][$i];
                 $dimensiones = getimagesize($ruta_provisional);
                 $width = $dimensiones[0];
                 $height = $dimensiones[1];
                 $carpeta = "user_assets/images/Task/";
                 if ($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif')
                 {
                   $errores.= "The File[$nombre] isn't a imagen <br>";
                 }
                 else
                 {
                     $src = $carpeta.'Task'.user_id().hotel_id().$nombre;
                     move_uploaded_file($ruta_provisional, $src);
                     if(true)
                     {
                         $fileattached.=(strlen($src)>0?'###':'')."/".$src;
                     }
                     else
                     {
                       $errores.= "El File[$nombre] has problem to Load <br>";
                     }
                 }
              }



  				}


          $data['SubjectTask']=$_POST['subject'];
          $data['Description']=$_POST['description'];
          $data['LinkFileAttached']=$fileattached;
          $data['UserCreatedId']=user_id();
          $data['DeveloperAssignedId']=$_POST['DeveloperId'];
          $data['PercentageProccess']=0;
          $data['StatusId']=2;
          $data['Active']=1;

          insert_data('DeveloperTask',$data);
  				if(strlen($errores)>0)
  				{
  					echo json_encode(array("success"=>false,'message'=>$errores));
  				}
  				else
  				{
  					echo json_encode(array("success"=>true,'message'=>$errores));
  				}
  			}

      }
    }
}
