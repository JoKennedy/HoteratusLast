<div class="dash-b4-n calender-n">
<div class="row-fluid clearfix">
<div class="col-md-2 col-sm-2">
<div class="cal-lef">
</div>
<div class="new-left-menu">
<div class="nav-side-menu">
        <div class="menu-list">
  <div class="tab-room"><div class="new-left-menu"><div class="nav-side-menu"><div class="menu-list">
            <ul id="menu-content" class="menu-content out">
                <li class="active"> 
                  <a href="#tab_default_1" data-toggle="tab">
                  <i class="fa fa-info fa-lg"></i> Summary
                  </a>
                </li>
                 <li>
                  <a href="#tab_default_2" data-toggle="tab"> <!-- class="acc-mn" -->
                  <i class="fa fa-money fa-lg"></i> Adjustments
                  </a>
                </li>
                 <li>
                  <a href="#tab_default_3" data-toggle="tab">
                  <i class="fa fa-sitemap fa-lg"></i> History 
                  </a>
                  </li>
            </ul>
     <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>  
</div></div></div></div>            
     </div>
</div>
</div>
</div>
<div class="col-md-10 col-sm-10" style="padding-right:0;">
<div class="tab-content">
<div class="tab-pane active" id="tab_default_1">
  <div class="">

<div class="col-md-9 col-sm-9">
<?php 
$uri = insep_decode(uri(4));
// echo $uri;die;
$room = $this->reservation_model->select_reservation($uri);
	// echo $room->reservation_id;die;	 ?>
<div class="bg-neww lcs_pad_20">
<div class="pa-n nn2"><h4><a href="#"> Reservation </a>
    <i class="fa fa-angle-right"></i>
   <?php echo $room->reservation_code; ?>
</h4></div>

<?php $error = $this->session->flashdata('error'); 
		if($error!="") {
			echo '<div class="alert alert-danger"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Error! </strong>'.$error.'</div>';
		}
		$success = $this->session->flashdata('success');
		if($success!="") {
			echo '<div class="alert alert-success"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">&times;</button><strong>Success! </strong>'.$success.'</div>';
		} ?>
		
<h5>Room - 1  <span class="label label-success"><a href="#" style="color:#fff;"> Confirmed </a></span></h5>

<div  class="table-responsive cls_table_info">
<table class="table table-striped">
      <thead>
        <tr>
          <th> Room </th>
          <th> Check-in date </th>
          <th> Check-out date </th>
          <th> Average daily rate </th>
          <th> Number of nights </th>
          <th> Number of guests  </th>
          <th> Total </th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td> dfdsf <a href="#">(More)</a> </td>
          <td> <?php echo date('M d,Y',strtotime(str_replace('/','-',$room->start_date))); ?> </td>
          <td> <?php echo date('M d,Y',strtotime(str_replace('/','-',$room->end_date))); ?> </td>
          <td> ₨ <?php echo $room->price; ?> </td>	
		  <?php $nig = $room->end_date-$room->start_date; ?>
          <td><?php echo $nig; ?></td>
          <td>  1 </td>		  
          <td> ₨<?php echo $room->price*$nig; ?> </td>
        </tr>
        
        <tr>
          <td colspan="6"> Subtotal </td>
          <td> ₨<?php echo $room->price*$nig; ?>  </td>
        </tr>
        
         <tr>
          <td colspan="6"> Grand total </td>
          <td> ₨<?php echo $room->price*$nig; ?>  </td>
        </tr>
      
      </tbody>
    </table>
</div>

<button type="button" class="btn btn-info"> Resend Information </button>

</div>
</div>
              
<div class="col-md-3 col-sm-3">

<h3> Payment method </h3>

<p> Cash </p>

<hr>

<p><i class="fa fa-print"></i> <a href="<?php echo base_url(); ?>reservation/reservation_print/<?php echo insep_encode($room->reservation_id); ?>" target="_blank"> Print</a> </p>
<hr>

<h3> Guest details <small> <a href="#" data-toggle="modal" data-target="#edit_resevation">  (Edit) </a> </small> </h3>
<p><?php echo $room->guest_name; ?><br>
<?php $country_name = $this->reservation_model->get_country_name_id($room->country);
				$country_nam=$country_name->country_name;
				?>
<?php echo $country_nam; ?></p>

<p><i class="fa fa-phone"></i> <?php echo $room->mobile; ?> </p>

<p><i class="fa fa-envelope-o"></i> <a href="#"><?php echo $room->email; ?> </a> </p>
<hr> 

<p><strong>Booked date </strong> :<?php echo date('M d,Y',strtotime(str_replace('/','-',$room->booking_date))); ?>  </p>

<p><strong> Last Update  </strong> : October 10, 2015 16:24   </p>

<hr> 

<h3>Notes</h3>

<p> fdfddf dgg </p>

</div>
              
</div>               


</div>
<div class="tab-pane" id="tab_default_2"><!-- tab2 -->

<div class="box-m2">


<div class="">

<div class="col-md-12 col-sm-12">

<div class="bg-neww lcs_pad_20">
<div class="pa-n nn2"><h4><a href="#"> Reservation </a>
    <i class="fa fa-angle-right"></i>
    R432025617  <i class="fa fa-angle-right"></i>  Adjustments

</h4></div>
<br>


<div style="clear:both;overflow:hidden;" class="mar-bot20"> <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#adjustment"> <i class="fa fa-plus"></i> Add Adjustment </button> </div>

<div  class="table-responsive">
<table class="table table-striped">
      <thead>
        <tr>
          <th> Date </th>
          <th> Time  </th>
          <th> Description  </th>
          <th> Amount  </th>
         </tr>
      </thead>
      <tbody>
        <tr>
          <td>  <a href="#"> Oct 10, 2015 </a> </td>
          <td>  	06:09  </td>
          <td> VAT (0.0% Not Included)</td>
          <td><a href="#"> ₨0 </a></td>
        </tr>
      
      </tbody>
    </table>
</div>


</div>
</div>
              

              
</div>


</div>

</div>
<!-- end tab2 -->
<div class="tab-pane" id="tab_default_3"><!-- tab3 -->


<div class="box-m2 bg-neww lcs_pad_20">
<div class="row">
<div class="col-md-12 col-sm-12">



<div class="">
<div id="original_container">
  <div id="history-timeline">
    <h2>TODAY</h2>
        <div class="event">
          <div class="left-column">
            <time>
                <span class="date">
                  Oct 10, 2015
                </span>
                <span class="clock">
                  16:24
                </span>
            </time>
          </div>
          <div class="right-column">
            <div class="description">
              <p><b>thiru A</b> confirmed reservation</p>
            </div>
          </div>
        </div>
        <div class="event">
          <div class="left-column">
            <time>
                <span class="date">
                  Oct 10, 2015
                </span>
                <span class="clock">
                  16:24
                </span>
            </time>
          </div>
          <div class="right-column">
            <div class="description">
              <p>Email sent to ggh@gmail.com <small>(HotelRunner - Reservation Confirmation #R432025617)</small></p><div class="danger"><p>E-mail could not be delivered to ggh@gmail.com</p></div>
            </div>
          </div>
        </div>
        <div class="event">
          <div class="left-column">
            <time>
                <span class="date">
                  Oct 10, 2015
                </span>
                <span class="clock">
                  11:39
                </span>
            </time>
          </div>
          <div class="right-column">
            <div class="description">
              <p>Email sent to ggh@gmail.com <small>(siva - Reservation Information #R432025617)</small></p><div class="danger"><p>E-mail could not be delivered to ggh@gmail.com</p></div>
            </div>
          </div>
        </div>
        <div class="event">
          <div class="left-column">
            <time>
                <span class="date">
                  Oct 10, 2015
                </span>
                <span class="clock">
                  11:39
                </span>
            </time>
          </div>
          <div class="right-column">
            <div class="description">
              <p>Email sent to thirumca88@gmail.com <small>(siva - New Reservation #R432025617)</small></p><div class="success"><p>E-mail has been delivered successfully</p></div>
            </div>
          </div>
        </div>
        <div class="event">
          <div class="left-column">
            <time>
                <span class="date">
                  Oct 10, 2015
                </span>
                <span class="clock">
                  11:39
                </span>
            </time>
          </div>
          <div class="right-column">
            <div class="description">
              <p><b>thiru A</b> made a reservation</p>
            </div>
          </div>
        </div>
  </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<p>&nbsp;</p>
<!-- dialog box -->
<div class="dial">
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">More options</h4>
      </div>
      <div class="modal-body">
      <div class="inner-dia">
       <h4>Rate types</h4>
       
       
       <div class="top-3">
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Apartment - 1 guest</span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input type="text" value="0.00" class="form-control widh">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Apartment - 1 guest Non refundable</span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input type="text" value="0.00" class="form-control widh">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Apartment - 2 guests </span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input type="text" value="0.00" class="form-control widh">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Apartment - 2 guests Non refundable  </span></div>
       <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>+</option>
  <option>-</option></select></div>
  <div class="col-md-1 col-sm-1"><select class="form-control">
  <option>%</option>
  <option>Rs</option></select></div>
   <div class="col-md-2 col-sm-2 ssw">
   <div class="form-group">
    <input type="text" value="0.00" class="form-control widh">
  </div>
   </div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Minimum stay   </span></div>
      <div class="col-md-3 col-sm-3 new-no">
    
      <form class="form-horizontal" role="form">
    <div class="form-group">

      <div class="col-lg-10">
        <select id="basic" class="selectpicker show-tick form-control" data-live-search="true">
          <option>Do not Change</option>
          <option selected>1</option>
          <option>2</option>
          <option>3</option>
          <option>4</option>
          <option>5</option>
          <option>6</option>
          <option>7</option>
          <option>8</option>
          <option>9</option>
          <option>10</option>
          <option>11</option>
          <option>12</option>
          <option>13</option>
          <option>14</option>
          <option>15</option>
          <option>16</option>
          <option>17</option>
          <option>18</option>
          <option>19</option>
          <option>20</option>
          <option>21</option>
          <option>22</option>
          <option>23</option>
          <option>24</option>
          <option>25</option>
          <option>26</option>
          <option>27</option>
          <option>28</option>
          <option>29</option>
          <option>30</option>
          <option>31</option>
        </select>
      </div>
    </div>
  </form>
  
       </div>
  
   
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Stop sell   </span></div>
      <div class="col-md-3 col-sm-3 new-no">
    
      <form class="form-horizontal" role="form">
    <div class="form-group">

      <div class="col-lg-10">
        <select class="form-control">
          <option>Do not Change</option>
          <option selected>Yes</option>
          <option>No</option>
        </select>
      </div>
    </div>
  </form>
  
       </div>
  
   
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Closed to arrival (CTA)  </span></div>
      <div class="col-md-3 col-sm-3 new-no">
    
      <form class="form-horizontal" role="form">
    <div class="form-group">

      <div class="col-lg-10">
        <select class="form-control">
          <option>Do not Change</option>
          <option selected>Yes</option>
          <option>No</option>
        </select>
      </div>
    </div>
  </form>
  
       </div>
  
   
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6"><span class="ne-n">Closed to departure (CTD)  </span></div>
      <div class="col-md-3 col-sm-3 new-no">
    
      <form class="form-horizontal" role="form">
    <div class="form-group">

      <div class="col-lg-10">
        <select class="form-control">
          <option>Do not Change</option>
          <option selected>Yes</option>
          <option>No</option>
        </select>
      </div>
    </div>
  </form>
  
       </div>
  
   
       </div>
       </div>
       
       
       
       
       
       
      </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary">ok</button>
      </div>
    </div>
  </div>
</div>
</div>



<div class="dial3">
<div class="modal fade" id="myModal-p1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title" id="myModalLabel">New Room Type</h3>
      </div>
      <div class="modal-body">
       <div class="box-dialog row-pad-top-20">
 <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Room Type Name</span></div>
 <div class="col-md-8 col-sm-8"> <input type="text" class="form-control" id="exampleInputEmail1" placeholder="E.g. double bed room"></div>
 </div>    
 <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Description</span></div>
 <div class="col-md-8 col-sm-8"><textarea class="form-control" rows="2" placeholder="Describe the room type. Maximum of 1000 characters."></textarea></div>
 </div>
 <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Number of rooms</span></div>
 <div class="col-md-4 col-sm-4"><select class="form-control">
  <option>1 room</option>
  <option>2 rooms</option>
  <option>3 rooms</option>
  <option>4 rooms</option>
  <option>5 rooms</option>
  <option>6 rooms</option>
  <option>7 rooms</option>
  <option>8 rooms</option>
  <option>9 rooms</option>
  <option>10 rooms</option>
  <option>11 rooms</option>
  <option>12 rooms</option>
  <option>13 rooms</option>
  <option>14 rooms</option>
  <option>15 rooms</option>
  <option>16 rooms</option>
  <option>17 rooms</option>
  <option>18 rooms</option>
  <option>19 rooms</option>
  <option>20 rooms</option>
  <option>21 rooms</option>
  <option>22 rooms</option>
  <option>23 rooms</option>
  <option>24 rooms</option>
  <option>25 rooms</option>
  <option>26 rooms</option>
  <option>27 rooms</option>
  <option>28 rooms</option>
  <option>29 rooms</option>
  <option>30 rooms</option>
  <option>31 rooms</option>
  <option>32 rooms</option>
  <option>33 rooms</option>
  <option>34 rooms</option>
  <option>35 rooms</option>
  <option>36 rooms</option>
  <option>37 rooms</option>
  <option>38 rooms</option>
  <option>39 rooms</option>
  <option>40 rooms</option>
  <option>41 rooms</option>
  <option>42 rooms</option>
  <option>43 rooms</option>
  <option>44 rooms</option>
  <option>45 rooms</option>
  <option>46 rooms</option>
  <option>47 rooms</option>
  <option>48 rooms</option>
  <option>49 rooms</option>
  <option>50 rooms</option>
  <option>51 rooms</option>
  
  
</select></div>
 </div>
 <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Room Type</span></div>
 <div class="col-md-9 col-sm-9"> 
 <div class="radio">
  <label>
    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
    Private Room
  </label>
</div>
 <div class="radio">
  <label>
    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
    Shared Room (dorm)
  </label>
</div>
Shared rooms are sold by the bed, private rooms only by full rooms
 </div>
 </div>
 <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Occupancy</span></div>
 <div class="col-md-9 col-sm-9"><select class="form-control">
  <option>1 persion</option>
  <option>2 persions</option>
  <option>3 persions</option>
  <option>4 persions</option>
  <option>5 persions</option>
  <option>6 persions</option>
  <option>7 persions</option>
  <option>8 persions</option>
  <option>9 persions</option>
  <option>10 persions</option>
  <option>11 persions</option>
  <option>12 persions</option>
  <option>13 persions</option>
  <option>14 persions</option>
  <option>15 persions</option>
  <option>16 persions</option>
  <option>17 persions</option>
  <option>18 persions</option>
  <option>19 persions</option>
  <option>20 persions</option>
  <option>21 persions</option>
  <option>22 persions</option>
  <option>23 persions</option>
  <option>24 persions</option>
  <option>25 persions</option>
  <option>26 persions</option>
  <option>27 persions</option>
  <option>28 persions</option>
  <option>29 persions</option>
  <option>30 persions</option>
  <option>31 persions</option>
  <option>32 persions</option>
  <option>33 persions</option>
  <option>34 persions</option>
  <option>35 persions</option>
  <option>36 persions</option>
  <option>37 persions</option>
  <option>38 persions</option>
  <option>39 persions</option>
  <option>40 persions</option>
  <option>41 persions</option>
  <option>42 persions</option>
  <option>43 persions</option>
  <option>44 persions</option>
  <option>45 persions</option>
  <option>46 persions</option>
  <option>47 persions</option>
  <option>48 persions</option>
  <option>49 persions</option>
  <option>50 persions</option>
  <option>51 persions</option>  
</select>
<p>Enter how many persons can stay in this room type.<br/>
For a private room, the Occupancy field is the Max number of People who can occupy the room.<br/>
For a shared dormitory, Occupancy is the total number of beds, and one person per bed is assumed. 
</p>
</div>
 </div>
 <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Dorm Gender</span></div>
 <div class="col-md-9 col-sm-9"> 
 <div class="radio">
  <label>
    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
    Mixed
  </label>
</div>
 <div class="radio">
  <label>
    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
    Males
  </label>
</div>
 <div class="radio">
  <label>
    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>
    Females
  </label>
</div>
Room gender is only applicable for dorms 
 </div>
 </div>
 <div class="row">
 <div class="col-md-3 col-sm-3"><span class="ne-n-pad">Images</span></div>
 <div class="col-md-9 col-sm-9"> 
 <input type="file" id="exampleInputFile">
Images are only required should you wish to use the TripConnect InstantBooking feature. They are currently not used anywhere else.
 </div>
 </div>
 <div class="row button">
 <div class="col-md-9 col-sm-9 col-md-offset-3 col-sm-offset-3 s1"> 
<a class="btn btn-primary hvr-sweep-to-right" href="#">Add</a>
 </div>
 </div>
 </div>
       
       
       
       
      </div>
      
    </div>
  </div>
</div>
</div>
<!-- end dialog box -->

<!-- Modal -->
<div class="modal fade lag" id="myModal-pt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        
        <div class="table table-responsive">
        <table class="table table-condensed">
        <thead>
        <tr>
        <th>Number of guests</th>
        <th>Refundable</th>
        <th>Non refundable</th>
        </tr>
        </thead>
        <tbody>
        <tr>
        <td class="pa-t-pa-b"><div class="sp"><span class="gray">1</span></div></td>
        <td class="pa-t-pa-b">

        <div class="col-md-3 col-sm-3">        
        <select class="form-control">
  <option>+</option>
  <option>-</option>
</select>
</div>
<div class="col-md-3 col-sm-3"> 
<select class="form-control">
  <option>Rs</option>
  <option selected>%</option>
</select></div>
 <div class="col-md-3 col-sm-3">
<div class="ssw ki"><form>
    <input type="text" value="0.00" class="form-control widh">
  </form></div></div>
  
 <div class="col-md-3 col-sm-3"><p class="tk">100.00</p></div>
        </td>
        <td class="pa-t-pa-b">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control">
  <option>+</option>
  <option>-</option>
</select>
</div>
<div class="col-md-3 col-sm-3"> 
<select class="form-control">
  <option>Rs</option>
  <option selected>%</option>
</select></div>
 <div class="col-md-3 col-sm-3">
<div class="ssw ki"><form>
    <input type="text" value="0.00" class="form-control widh">
  </form></div></div>
  
 <div class="col-md-3 col-sm-3"><p class="tk">100.00</p></div></td>
        </tr>
        <tr>
        <td class="pa-t-pa-b"><div class="sp"><span class="gray">2</span></div></td>
        <td class="pa-t-pa-b">

        <div class="col-md-3 col-sm-3">        
        <select class="form-control">
  <option>+</option>
  <option>-</option>
</select>
</div>
<div class="col-md-3 col-sm-3"> 
<select class="form-control">
  <option>Rs</option>
  <option selected>%</option>
</select></div>
 <div class="col-md-3 col-sm-3">
<div class="ssw ki"><form>
    <input type="text" value="0.00" class="form-control widh">
  </form></div></div>
  
 <div class="col-md-3 col-sm-3"><p class="tk">100.00</p></div>
        </td>
        <td class="pa-t-pa-b">
        <div class="col-md-3 col-sm-3">        
        <select class="form-control">
  <option>+</option>
  <option>-</option>
</select>
</div>
<div class="col-md-3 col-sm-3"> 
<select class="form-control">
  <option>Rs</option>
  <option selected>%</option>
</select></div>
 <div class="col-md-3 col-sm-3">
<div class="ssw ki"><form>
    <input type="text" value="0.00" class="form-control widh">
  </form></div></div>
  
 <div class="col-md-3 col-sm-3"><p class="tk">100.00</p></div></td>
        </tr>
        </tbody>
        </table>
        </div>
        
      </div>
    </div>
  </div>
</div>
<!-- end Modal -->


<!-- Modal -->
<div class="modal fade" id="myModal-tab-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">More options</h4>
      </div>
      <div class="modal-body">
<div class="calen-2 paf">

<div class="row">
<div class="col-md-4 col-sm-4"><span class="ne-k">Meal plan</span></div>
<div class="col-md-8 col-sm-8">
<select class="form-control">
  <option>Daily</option>
  <option>Weekly</option>
  <option>Monthly</option>
</select>
</div>
</div>

<div class="row">
<div class="col-md-4 col-sm-4"><span class="ne-k">Open to direct channels</span></div>
<div class="col-md-8 col-sm-8">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
  </label>
</div>
</div>
</div>

<div class="row">
<div class="col-md-4 col-sm-4"><span class="ne-k">Minimum stay:</span></div>
<div class="col-md-8 col-sm-8 ssw vi1"><form>
  <div class="form-group">
    <input type="number" value="0" step="any" class="form-control widh">
  </div>
  </form></div>
</div>

<div class="row">
<div class="col-md-4 col-sm-4"><span class="ne-k">Number of bedrooms:</span></div>
<div class="col-md-8 col-sm-8 ssw vi1"><form>
  <div class="form-group">
    <input type="text" value="" class="form-control widh">
  </div>
  </form></div>
</div>

<div class="row">
<div class="col-md-4 col-sm-4"><span class="ne-k">Number of bedrooms:</span></div>
<div class="col-md-8 col-sm-8 ssw vi1"><form>
  <div class="form-group">
    <input type="text" value="" class="form-control widh">
  </div>
  </form></div>
</div>

<div class="row">
<div class="col-md-4 col-sm-4"><span class="ne-k">Area (m²):</span></div>
<div class="col-md-8 col-sm-8 ssw vi1"><form>
  <div class="form-group">
    <input type="text" value="" class="form-control widh">
  </div>
  </form></div>
</div>

</div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->

<!-- Modal -->
<div class="modal fade" id="myModal-s1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">General options</h4>
      </div>
      <div class="modal-body">
       
       <div class="calen-2 paf">
       <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Desk  
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Desk lamp  
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Down-feather pillow 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Electronic door locks 
  </label>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Free local calls   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Free local calls (Paid)   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Free toiletries 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Full-length mirror 
  </label>
</div>
</div>
       </div>
        <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Hairdryer   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Hairdryer (Paid)  
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Hairdryer on request 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Hairdryer on request (Paid) 
  </label>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Handicap accessible   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Handicap facilities   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Heated towel rail in bathroom 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    High ceiling 
  </label>
</div>
</div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Indoor fireplace   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Newspaper   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Newspaper (Paid)  
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Non-smoking  
  </label>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Openable windows    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Operator 24 hours   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
   Pets allowed 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Pets not allowed 
  </label>
</div>
</div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Plan of emergency exits    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Private bathroom   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Roll-in shower   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
   Room safe  
  </label>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Room safe (Paid)     
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Smoking 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
   Sound-proofed windows 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Steel door 
  </label>
</div>
</div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6">
       <div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Vanity mirror 
  </label>
</div>
       </div>
       </div>
       </div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Done</button>
      </div>
    </div>
  </div>
</div>
<!-- end Modal -->

<!-- Modal -->
<div class="modal fade" id="myModal-s2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Bed types</h4>
      </div>
      <div class="modal-body">
       
       <div class="calen-2 paf">
       <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Single Extra Long  
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Single Extra Long  
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Small Double 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Small Single 
  </label>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Small Twin   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Sofa Bed 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Standard/Eastern king 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Twin 
  </label>
</div>
</div>
       </div>
        
       
       
       <div class="row">
       <div class="col-md-6 col-sm-6">
       <div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Twin Extra Long 
  </label>
</div>
       </div>
       </div>
       </div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Done</button>
      </div>
    </div>
  </div>
</div>
<!-- end Modal -->


<!-- Modal -->
<div class="modal fade" id="myModal-s3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Composition of room</h4>
      </div>
      <div class="modal-body">
       
       <div class="calen-2 paf">
       <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Equipped kitchen   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Executive lounge access   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Kitchenette 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Mosquito net  
  </label>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Non-equipped kitchen   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Seating area 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Separate luggage store 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Working room 
  </label>
</div>
</div>
       </div>
        
      
       </div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Done</button>
      </div>
    </div>
  </div>
</div>
<!-- end Modal -->

<!-- Modal -->
<div class="modal fade" id="myModal-s4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">View</h4>
      </div>
      <div class="modal-body">
       
       <div class="calen-2 paf">
       <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Mountain view    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    No view   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Ocean view 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Park view 
  </label>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Pool view  
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Restricted view 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    River view 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Sea view 
  </label>
</div>
</div>
       </div>
        
       <div class="row">
       <div class="col-md-6 col-sm-6">
       <div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Street view  
  </label>
</div>

       </div>
       </div>
       </div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Done</button>
      </div>
    </div>
  </div>
</div>
<!-- end Modal -->

<!-- Modal -->
<div class="modal fade" id="myModal-s5" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Air conditioning</h4>
      </div>
      <div class="modal-body">
       
       <div class="calen-2 paf">
       <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    <abbr title="Individually controlled ventilation (Paid)">Individually controlled ven...</abbr>   
  </label>
</div>
</div>
       </div>
     
       </div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Done</button>
      </div>
    </div>
  </div>
</div>
<!-- end Modal -->

<!-- Modal -->
<div class="modal fade" id="myModal-s6" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Domestic appliances</h4>
      </div>
      <div class="modal-body">
       
       <div class="calen-2 paf">
       <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Dishwasher (Paid)   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Electric cooker    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Freezer  
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Fridge   
  </label>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Fridge (Paid)   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Gas grill   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Grill 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Ice-maker 
  </label>
</div>
</div>
       </div>
     <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Ice-maker (Paid)   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Iron for pants    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Iron for pants (Paid) 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Iron with ironing board 
  </label>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    <abbr title="Iron with ironing board (Paid)">Iron with ironing board (Paid)</abbr>   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Juicer   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Kettle  
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Microwave oven  
  </label>
</div>
</div>
       </div>
       <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Microwave oven (Paid)    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
   Minibar    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Oven 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
   Refrigerator 
  </label>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Stove   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Tea-maker    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Tea-maker (Paid)  
  </label>
</div>
</div>
       </div>
       </div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Done</button>
      </div>
    </div>
  </div>
</div>
<!-- end Modal -->

<!-- Modal -->
<div class="modal fade" id="myModal-s7" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Electronics</h4>
      </div>
      <div class="modal-body">
       
       <div class="calen-2 paf">
       <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Cable TV (Paid)   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Computer in room     
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
   Computer in room (Paid) 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Cordless telephone   
  </label>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Cordless telephone (Paid)   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    DSL broadband internet   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    DSL broadband internet (Paid) 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    DVD player 
  </label>
</div>
</div>
       </div>
     
       <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    DVD player (Paid)    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
   Desktop telephone    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
  Desktop telephone (Paid)
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Flat Screen 
  </label>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Game console    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    ISDN connection 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    ISDN connection (Paid) 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
   Internet access 
  </label>
</div>
</div>
       </div>
       
       <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Internet access (Paid)     
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
   MP3 player   
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
  MP3 player (Paid) 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Pay TV 
  </label>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Pay TV (Paid)    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Radio 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Radio (Paid) 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
   Remote control TV 
  </label>
</div>
</div>
       </div>
       
       <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Remote control TV (Paid) 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
   Satellite TV    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
 Satellite TV (Paid) 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Telephone in bathroom 
  </label>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Telephone in bathroom (Paid)    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Video player (VCR) 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Video player (VCR) (Paid) 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
   Voicemail  
  </label>
</div>
</div>
       </div>
       
       <div class="row">
       <div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Voicemail (Paid) 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
   Washing machine    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
 Wireless internet 
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    Wireless internet (Paid) 
  </label>
</div>
</div>
<div class="col-md-6 col-sm-6">
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    iPad    
  </label>
</div>
<div class="checkbox">
  <label>
    <input type="checkbox" value="">
    iPod docking station 
  </label>
</div>
</div>
       </div>
       </div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Done</button>
      </div>
    </div>
  </div>
</div>
<!-- end Modal -->

<!-- Modal -->


<div class="modal fade" id="myModa-f1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Edit photo</h4>
      </div>
      <div class="modal-body">
      <div class="row">
      <div class="col-md-3 col-sm-3"><img src="assets/images/ligh-1.jpg" class="img img-responsive img-thumbnail">
      </div>
      <div class="col-md-9 col-sm-9">
       <div id="suggestOnClick" class="col-md-12"></div>
       </div>
       </div>
       <div class="row">
      <div class="col-md-9 col-sm-9 col-sm-offset-3 col-md-offset-3">
       <div id="suggestOnClick2" class="col-md-12"></div>
       </div>
       </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left">Delete</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="adjustment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Add adjustment </h4>
      </div>
      <div class="modal-body">
      
      <form class="form-horizontal">
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">Amount</label>
    <div class="col-sm-2">
      <select class="form-control"> <option> - </option>
       <option> + </option> </select>
    </div>
    <div class="col-md-4 col-sm-4"> <div class="input-group">
  <span class="input-group-addon" id="basic-addon1">INR</span>
  <input type="text" class="form-control" placeholder="Username" aria-describedby="basic-addon1">
</div> </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-2 control-label">Description</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="inputPassword3" placeholder="Description">
    </div>
  </div>
</form>
      
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit_resevation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
	 <form class="form-horizontal" method="POST" action="<?php echo lang_url(); ?>reservation/reservation_order">
	 <input type="hidden" name="reserve_id" value="<?php echo $room->reservation_id; ?>">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Edit guest details </h4>
      </div>
      <div class="modal-body">
     
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-4 control-label">First name</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="inputEmail3" name="guest_name" value="<?php echo $room->guest_name; ?>" required>
    </div>
  </div>
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-4 control-label">Last name</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="inputPassword3" name="last_name" value="<?php echo $room->last_name; ?>" required>
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-4 control-label">E-mail</label>
    <div class="col-sm-8">
      <input type="email" class="form-control"  name="email" id="inputPassword3" value="<?php echo $room->email; ?>" required>
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-4 control-label">Phone</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="inputPassword3" name="mobile" value="<?php echo $room->mobile; ?>" required>
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-4 control-label"> Country </label>
    <div class="col-sm-8">
					  <select class="form-control" name="country" required>
						  <option value=""> select </option>
						  <?php $country = $this->reservation_model->get_country_name();
									foreach($country as $country_name) {
									$country_id=$country_name->id;
									$country_name=$country_name->country_name;
									?>
									<option value="<?php echo $country_id; ?>" <?php if($room->country == $country_id){echo "selected=selected";} ?>><?php echo $country_name; ?></option>
									<?php } ?>
					  </select>
				</div>
  </div>
  
  <div class="form-group">
		<label for="inputPassword3" class="col-sm-4 control-label" name="province"> Province </label>
			<div class="col-sm-8">
				  <select class="form-control" name="province" required>
					  <option value="">select</option>
					  <?php $province_name=$this->reservation_model->get_country_name();
								foreach($province_name as $province) {
								$province_id=$province->id;
								$province_con=$province->country_name;
								?>
								<option value="<?php echo $province_id; ?>" <?php if($room->province == $province_id){echo "selected=selected";} ?>><?php echo $province_con; ?></option>
								<?php } ?>
				  </select>
			</div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-4 control-label"> Street address </label>
    <div class="col-sm-8">
      <input type="text" class="form-control" name="street_name" id="inputEmail3" value="<?php echo $room->street_name; ?>" required>
    </div>
  </div>
  
  <div class="form-group">
    <label for="inputPassword3" class="col-sm-4 control-label"> City </label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="inputEmail3" name="city_name" value="<?php echo $room->city_name; ?>" required>
    </div>
  </div>
</form>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default pull-left">Delete</button>
         <button type="submit" class="btn btn-default"  name="save" value="save">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- end Modal -->
</body>
</html>
 <div id="suggestOnClick"></div>