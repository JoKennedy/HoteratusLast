<?php
ini_set('memory_limit', '-1');
ini_set('display_errors','1');
defined('BASEPATH') OR exit('No direct script access allowed');

class developer extends Front_Controller {



 	  public function __construct()
    {
        parent::__construct();

    }
    function mailsettings()
    {
        $this->load->library('email');
        $config['wrapchars'] = 76; // Character count to wrap at.
        $config['priority']  = 1; // Character count to wrap at.
        $config['mailtype']  = 'html'; // text or html Type of mail. If you send HTML email you must send it as a complete web page. Make sure you don't have any relative links or relative image paths otherwise they will not work.
        $config['charset']   = 'utf-8'; // Character set (utf-8, iso-8859-1, etc.).
        $this->email->initialize($config);
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
      $data['Priorities']=$this->Priority(false);
      $this->views('developer/task',$data);
    }
    public function viewtask($taskid)
    {
      is_login();
      $taskid= insep_decode($taskid);
      $hotelid=hotel_id();
      $data['page_heading'] = 'Developer Task';
      $user_details = get_data(TBL_USERS,array('user_id'=>user_id()))->row_array();
      $data= array_merge($user_details,$data);
      $data['HotelInfo']= get_data('manage_hotel',array('hotel_id'=>$hotelid))->row_array();
      $data['Developers']=get_data('Developers',array('active'=>1))->result_array();
      $data['DeveloperTaskStatus']=get_data('DeveloperTaskStatus',array('active'=>1))->result_array();
      $data['TaskInfo']= get_data('DeveloperTask',array('DeveloperTaskId'=>$taskid))->row_array();
      
      $this->views('developer/edittask',$data);
    }
    function taskhtml()
    {
      $status=$_POST['status'];
      $developer=$_POST['developerid'];
      $Closed=$_POST['closed'];
      $date1=$_POST['date1'];
      $date2=$_POST['date2'];
      if(strlen($date1)==0){$date1='2000-01-01';}else{$date1=date('Y-m-d',strtotime($date1));}
      if(strlen($date2)==0){$date2='3000-01-01';}else{$date2=date('Y-m-d',strtotime($date2));}

      $TaskInfo=$this->db->query("select a.*,concat(b.fname,' ',b.lname) usercreate
      from DeveloperTask a
      left join manage_users b on a.usercreatedid=b.user_id
      where (StatusId=$status or $status=0)
      and  (DeveloperAssignedId=$developer or $developer=0)
      and Closed=$Closed 
      and DateCreation between '$date1' and '$date2'
      and (UserCreatedId =".user_id()." or ".user_id()."=13) order by priority desc" )->result_array();

      $html='';
        $html.= '<div class="graph-visual tables-main">
          <div class="graph">
              <div class="table-responsive">
                      <table id="myTable" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                          <thead>
                              <tr style="height:2px;">
                                  <th>#</th>
                                  <th>'.infolang('priority').'</th>
                                  <th>'.infolang('category').'</th>
                                  <th>'.infolang('subject').'</th>
                                  <th>'.infolang('createby').'</th>
                                  <th width="10%">'.infolang('developer').'</th>
                                  <th>'.infolang('created').'</th>
                                  <th>'.infolang('percentage').'</th>
                                  <th><center>'.infolang('status').'</center></th>
                                  <th width="5%">'.infolang('action').'</th>
                                  <th width="5%">'.infolang('closed').'</th>
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
                          $priority='<a style="padding: 0px;" href="#" class="inline_username" data-type="select" data-name="Priority" data-pk="'.$value['DeveloperTaskId'].'" data-value="'.$value['Priority'].'" data-source="'.lang_url().'developer/Priority" title="Change Priority"></a>';
                          $html.=' <tr id="row'.$value['DeveloperTaskId'].'" scope="row" class="active"> <th scope="row">'.$i.'</th>
                          <td>'.$priority.'</td>
                          <td>'.$value['Category'].'</td>
                          <td><a href="'.base_url().'developer/viewtask/'.insep_encode($value['DeveloperTaskId']).'">'.$value['SubjectTask'].'</a></td>
                          <td>'.$value['usercreate'].'</td>
                          <td>'.$developer.'</td>
                          <td>'.date('m/d/Y h:m:s',strtotime($value['DateCreation'])).'</td>
                          <td align="center"> <span id="percentage'.$value['DeveloperTaskId'].'" class="percentage">'.$percentage.'%</span> <div class="progress progress-striped active">
                          <div id="class'.$value['DeveloperTaskId'].'" class="progress-bar progress-bar-'.$class.'" style="width: '.$value['PercentageProccess'].'%"></div></div></td>
                          <td><center>'.$status.'</center></td>
                          <td><center>'.($value['PercentageProccess']>=100 || $value['Closed']==1?'<i class="fas fa-check"></i>':'<a onclick="deletetask('.$value['DeveloperTaskId'].')"><i class="fas fa-trash-alt"></i></a>').'</center></td>
                          <td id="rowclosed'.$value['DeveloperTaskId'].'"> <center>'.($value['Closed']==1?'<i class="fas fa-lock"></i>':'<a onclick="closedtask('.$value['DeveloperTaskId'].')"><i class="fas fa-lock-open"></i></a>').'</center></td>';

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
    public function addNote()
    {
      is_login();
      $result['success']=false;
      $data['DeveloperTaskId']=$_POST['taskid'];
      $data['Description']=$_POST['description'];
      $data['Status']=1;
      $data['UserId']=user_id();
      if(insert_data('DeveloperTaskFollow',$data))
      {
        $result['success']=true;
        $campomodificado='';
        $message='';
        $task='';
        $taskinfo=$this->db->query("select a.*,concat(b.FirstName,' ',b.LastName) Developer,c.description status, b.email email
        from DeveloperTask a
        left join Developers b on a.DeveloperAssignedId=b.DeveloperId
        left join DeveloperTaskStatus c on a.StatusId=c.DeveloperTaskStatusId
        where DeveloperTaskId=".$_POST['taskid'])->row_array();

        $ALLUsersNotes=$this->db->query("SELECT a.*, concat(b.fname,' ' , b.lname) Username
					FROM DeveloperTaskFollow a
					left join manage_users b on a.Userid=b.user_id
					where
          a.DeveloperTaskId=".$_POST['taskid']." order by CreateDate desc")->result_array();
        $task.='<br><h1>Follow Up</h1><br>';
        foreach ($ALLUsersNotes as  $value) {
          $task.="</p><h2>".$value['Username']."</h2><p>".$value['Description']."</p>";
        }
        $userinfo=$this->db->query("select * from manage_users  where user_id=".user_id())->row_array();
        $message.="<h1>Nombre de la tarea</h1><p>".$taskinfo['SubjectTask']."</p><h2>Detalle de la Tarea</h2><p>".$taskinfo['Description']."</p>";
        $subject='Nuevo Comentario Creado by '.$userinfo['fname'].' '.$userinfo['lname'];
        $headers = "From: ".$userinfo['email_address']."\r\n";
        $headers .= "Reply-To: ".$userinfo['email_address']."\r\n";
        $headers .= "CC: XML@hoteratus.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        if(strlen($taskinfo['email'])>0)
        {
          mail($taskinfo['email'], $subject, $message.$task, $headers);
        }
      }
      echo json_encode($result);
    }
    public function deletetask()
    {
     
        $campomodificado='';
        $message='';
        $taskinfo=$this->db->query("select a.*,concat(b.FirstName,' ',b.LastName) Developer,c.description status, b.email email
        from DeveloperTask a
        left join Developers b on a.DeveloperAssignedId=b.DeveloperId
        left join DeveloperTaskStatus c on a.StatusId=c.DeveloperTaskStatusId
        where DeveloperTaskId=".$_POST['id'])->row_array();

        $userinfo=$this->db->query("select * from manage_users  where user_id=".user_id())->row_array();
       
        $result['success']=true;
        $message.="<h1>Nombre de la tarea</h1><p>".$taskinfo['SubjectTask']."</p><h2>Detalle de la Tarea</h2><p>".$taskinfo['Description']."</p>";
        $subject='Eliminacion de Tarea by '.$userinfo['fname'].' '.$userinfo['lname'];
        $headers = "From: ".$userinfo['email_address']."\r\n";
        $headers .= "Reply-To: ".$userinfo['email_address']."\r\n";
        $headers .= "CC: XML@hoteratus.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        if(strlen($taskinfo['email'])>0)
        {
          mail($taskinfo['email'], $subject, $message, $headers);
        }

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
        $campomodificado='';
        $message='';
        $taskinfo=$this->db->query("select a.*,concat(b.FirstName,' ',b.LastName) Developer,c.description status, b.email email
        from DeveloperTask a
        left join Developers b on a.DeveloperAssignedId=b.DeveloperId
        left join DeveloperTaskStatus c on a.StatusId=c.DeveloperTaskStatusId
        where DeveloperTaskId=".$_POST['pk'])->row_array();

        switch ($_POST['name']) {
          case 'DeveloperAssignedId':
            $campomodificado='Cambio de Personal Asignado';
            $message="<div><p>El nuevo Encargado para esta Tarea Es:<strong>".$taskinfo['Developer']."</strong></p></div>";
            break;
            case 'PercentageProccess':
            $campomodificado='Cambio de Porcentaje Completado';
            $message="<div><p>El nuevo porcentaje Es:<strong>".$taskinfo['PercentageProccess']."</strong></p></div>";
            break;
            case 'StatusId':
            $campomodificado='Cambio de Estatus';
            $message="<div><p>El nuevo Estatus Es:<strong>".$taskinfo['status']."</strong></p></div>";
            break;
          default:
            # code...
            break;
        }
        $userinfo=$this->db->query("select * from manage_users  where user_id=".user_id())->row_array();
       
        $result['success']=true;
        $message.="<h1>Nombre de la tarea</h1><p>".$taskinfo['SubjectTask']."</p><h2>Detalle de la Tarea</h2><p>".$taskinfo['Description']."</p>";
        $subject='Modificacion de '.$campomodificado.' by '.$userinfo['fname'].' '.$userinfo['lname'];
        $headers = "From: ".$userinfo['email_address']."\r\n";
        $headers .= "Reply-To: ".$userinfo['email_address']."\r\n";
        $headers .= "CC: XML@hoteratus.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        if(strlen($taskinfo['email'])>0)
        {
          mail($taskinfo['email'], $subject, $message, $headers);
        }
        

      }
      echo json_encode($result);
    }
    public function updatetask()
    {
      $data['Category']=$_POST['category'];
      $data['SubCategory']=$_POST['subcategory'];
      $data['StatusId']=$_POST['statusid'];
      $where['DeveloperTaskId']=$_POST['taskid'];
      $result['success']=false;
      if(update_data('DeveloperTask',$data,$where))
      {

        $message='';
        $taskinfo=$this->db->query("select a.*,concat(b.FirstName,' ',b.LastName) Developer,c.description status, b.email email
        from DeveloperTask a
        left join Developers b on a.DeveloperAssignedId=b.DeveloperId
        left join DeveloperTaskStatus c on a.StatusId=c.DeveloperTaskStatusId
        where DeveloperTaskId=".$_POST['taskid'])->row_array();

        $userinfo=$this->db->query("select * from manage_users  where user_id=".user_id())->row_array();
       
        $result['success']=true;
        $message.="<h1>Nombre de la tarea</h1><p>".$taskinfo['SubjectTask']."</p><h2>Detalle de la Tarea</h2><p>".$taskinfo['Description']."</p>";
        $subject='Tarea Actualizada by '.$userinfo['fname'].' '.$userinfo['lname'];
        $headers = "From: ".$userinfo['email_address']."\r\n";
        $headers .= "Reply-To: ".$userinfo['email_address']."\r\n";
        $headers .= "CC: XML@hoteratus.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        if(strlen($taskinfo['email'])>0)
        {
          mail($taskinfo['email'], $subject, $message, $headers);
        }
        

      }
      echo json_encode($result);
    }
    public function closetask()
    {
      $data['CloseDateTime']=date('Y-m-d h:m:s');
      $data['CloseUserid']=user_id();
      $data['Closed']=1;
      $where['DeveloperTaskId']=$_POST['id'];
      $result['success']=false;
      if(update_data('DeveloperTask',$data,$where))
      {

        $message='';
        $taskinfo=$this->db->query("select a.*,concat(b.FirstName,' ',b.LastName) Developer,c.description status, b.email email
        from DeveloperTask a
        left join Developers b on a.DeveloperAssignedId=b.DeveloperId
        left join DeveloperTaskStatus c on a.StatusId=c.DeveloperTaskStatusId
        where DeveloperTaskId=".$_POST['id'])->row_array();

        $userinfo=$this->db->query("select * from manage_users  where user_id=".user_id())->row_array();
       
        $result['success']=true;
        $message.="<h1>Nombre de la tarea</h1><p>".$taskinfo['SubjectTask']."</p><h2>Detalle de la Tarea</h2><p>".$taskinfo['Description']."</p>";
        $subject='Tarea Cerrada by '.$userinfo['fname'].' '.$userinfo['lname'];
        $headers = "From: ".$userinfo['email_address']."\r\n";
        $headers .= "Reply-To: ".$userinfo['email_address']."\r\n";
        $headers .= "CC: XML@hoteratus.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        if(strlen($taskinfo['email'])>0)
        {
          mail($taskinfo['email'], $subject, $message, $headers);
        }
        

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
                     move_uploaded_file($ruta_provisional, FCPATH.$src);
                     if(true)
                     {
                         $fileattached.=(strlen($src)>0?'###':'').'/'.$src;
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
          $data['Category']=$_POST['category'];
          $data['SubCategory']=$_POST['subcategory'];
          $data['Priority']=$_POST['priority'];
          $data['PercentageProccess']=0;
          $data['StatusId']=2;
          $data['Active']=1;

          if(insert_data('DeveloperTask',$data))
          {
            $campomodificado='';
            $message='';
            $taskinfo=$this->db->query("select a.*,concat(b.FirstName,' ',b.LastName) Developer,c.description status, b.email email
            from DeveloperTask a
            left join Developers b on a.DeveloperAssignedId=b.DeveloperId
            left join DeveloperTaskStatus c on a.StatusId=c.DeveloperTaskStatusId
            where DeveloperTaskId=".getinsert_id())->row_array();
            $userinfo=$this->db->query("select * from manage_users  where user_id=".user_id())->row_array();
            $message.="<h1>Nombre de la tarea</h1><p>".$taskinfo['SubjectTask']."</p><h2>Detalle de la Tarea</h2><p>".$taskinfo['Description']."</p>";
            $subject='Nueva Tarea Creada by '.$userinfo['fname'].' '.$userinfo['lname'];
            $headers = "From: ".$userinfo['email_address']."\r\n";
            $headers .= "Reply-To: ".$userinfo['email_address']."\r\n";
            $headers .= "CC: XML@hoteratus.com\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            if(strlen($taskinfo['email'])>0)
            {
              mail($taskinfo['email'], $subject, $message, $headers);
            }
          }
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
    public function Priority($type=true)
    {
      $data[0]['value']='0';
      $data[0]['text']='Low';
      $data[1]['value']='1';
      $data[1]['text']='Medium';
      $data[2]['value']='2';
      $data[2]['text']='High';
      if($type)
      {
        echo json_encode($data);
      }
      else{
        return $data;
      }
      
    }
}
