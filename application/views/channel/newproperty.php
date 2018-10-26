<div class="outter-wp">
    <!--/sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Dashboard</a></li>
            <li class="active">Property Info</li>
        </ol>
    </div>
    <!--/sub-heard-part-->
    <!--/forms-->
    <div class="forms-main">
        <center><h2 ><span  class="label label-primary">New Property Info</span></h2></center>
        <div class="graph-form">
            <div class="validation-form">
                <!---->
                <form id="propertyinfo" onsubmit="return savepropertyinfo();" method="POST">
                    <div class="vali-form">
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Property Name</label>
                            <input name="propertyname" type="text" value="" placeholder="Property Name" required="">
                        </div>
                        <div class="col-md-6 form-group1 form-last">
                            <label class="control-label controls">Address</label>
                            <input type="text" id="address" name="address" value="" placeholder="Address" required="">
                        </div>
                        <div class="clearfix"> </div>
                    </div>
                    <div class="col-md-12 form-group1 group-mail">
                        <label class="control-label">Email</label>
                        <input type="text" name="email" value="" placeholder="Email" required="">
                    </div>
                    <div class="clearfix"> </div>
                    <div class="col-md-12 form-group1 group-mail">
                        <label class="control-label">Url</label>
                        <input type="text" name="website" value="" placeholder="Current Website Url" required="">
                    </div>
                    <div class="clearfix"> </div>
                    <div class="col-md-12 form-group2 group-mail">
                        
                        <label class="control-label">Currency</label>
                        <select name="currencyid">
                        	<?php
                            if (count($currency)>0) {
                                echo '<option value="0" >Select a Currency</option>';
                                foreach ($currency as $value) {                                    
                                    echo '<option  value="'.$value['currency_id'].'" >'.$value['currency_name'].'</option>';
                                }
                            }
                            else 
                            {
                                echo '<option value="0">Does not have Currency</option>';
                            }

                          ?>
                        
                        </select>



                    </div>
                    <div class="clearfix"> </div>
                    <div class="col-md-12 form-group2 group-mail">
                        <label class="control-label">Country</label>
                        <select name="countryid">
                          <?php
                            if (count($countries)>0) {
                                echo '<option value="0" >Select a Country</option>';
                                foreach ($countries as $value) {                                    
                                    echo '<option  value="'.$value['id'].'" >'.$value['country_name'].'</option>';
                                }
                            }
                            else 
                            {
                                echo '<option value="0">Does not have countries</option>';
                            }

                          ?>
                        </select>
                    </div>
                    <div class="clearfix"> </div>
                    <div class="vali-form">
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Phone Number</label>
                            <input name="Phone" type="text" value="" placeholder="Phone Number" required="">
                        </div>
                        <div class="col-md-6 form-group1">
                            <label class="control-label">Zip Code</label>
                            <input name="zipcode" type="text" value="" placeholder="Zip Code" required="">
                        </div>
                        <div class="clearfix"> </div>
                        <div class="col-md-6 form-group1 form-last">
                            <label class="control-label">Town</label>
                            <input name ="town" type="text" value="" placeholder="Town" required="">
                        </div>
                        <div class="clearfix"> </div>
                        <div class="col-md-12 form-group ">
                            
                            <center><button type="submit" class="btn btn-success">Save</button></center>
                            <!--<button type="reset" class="btn btn-default">Reset</button>-->
                        </div>
                        <div class="clearfix"> </div>
                       
                        <div class="map-bottm">
                            <h3 class="inner-tittle two">Map</h3>
                            <div class="graph gllpMap">
                                <iframe width="450" height="250" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/view?key=AIzaSyDYtqUCg0ts6msMJ-WY59w4BnUy5CS5O0Y&center=&zoom=20" allowfullscreen id="mapa">
                                </iframe>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <input id="localidad" type="hidden" name="localidad" value="<?=$HotelInfo['map_location']?>">
                        
                </form>
                <div id="map"></div>
                <script type="text/javascript">

                	var base_url  = '<?php echo lang_url();?>';
                function savepropertyinfo() {

                    $.ajax({
                        type: "POST",
                        dataType:"json",
                        url: base_url + 'channel/savepropertyinfo',
                        data: $("#propertyinfo").serialize(),
                        success: function(html) {
                          

	                		 if (html['success']) {
		                        swal({
		                            title: "Done!",
		                            text: "Property Created Successfully!",
		                            icon: "success",
		                            button: "Ok!",
		                        }).then((n)=>{
                                     
                                   location.reload();        
                                });                                
		                    }
		                    else
		                    {		                    	
		                        swal({
		                            title: "Error!",
		                            text: "Something went wrong!",
		                            icon: "warning",
		                            button: "Ok!",
		                        }); 
		                    }

                        }
                    });

                    return false;
                   
                }

                // This example adds a search box to a map, using the Google Place Autocomplete
                // feature. People can enter geographical searches. The search box will return a
                // pick list containing a mix of places and predicted search terms.

                // This example requires the Places library. Include the libraries=places
                // parameter when you first load the API. For example:
                // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

                function initAutocomplete() {

                    var inicial = "https://www.google.com/maps/embed/v1/view?key=AIzaSyDYtqUCg0ts6msMJ-WY59w4BnUy5CS5O0Y&center=&zoom=20";

                    var map = new google.maps.Map(document.getElementById('map'), {
                        center: { lat: -33.8688, lng: 151.2195 },
                        zoom: 13,
                        mapTypeId: 'roadmap'
                    });

                    // Create the search box and link it to the UI element.
                    var input = document.getElementById('address');
                    var searchBox = new google.maps.places.SearchBox(input);
                    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                    // Bias the SearchBox results towards current map's viewport.
                    map.addListener('bounds_changed', function() {
                        searchBox.setBounds(map.getBounds());
                    });

                    var markers = [];
                    // Listen for the event fired when the user selects a prediction and retrieve
                    // more details for that place.
                    searchBox.addListener('places_changed', function() {
                        var places = searchBox.getPlaces();

                        if (places.length == 0) {
                            return;
                        }

                        // Clear out the old markers.
                        markers.forEach(function(marker) {
                            marker.setMap(null);
                        });
                        markers = [];

                        // For each place, get the icon, name and location.
                        var bounds = new google.maps.LatLngBounds();
                        places.forEach(function(place) {
                            if (!place.geometry) {
                                console.log("Returned place contains no geometry");
                                return;
                            }
                            var icon = {
                                url: place.icon,
                                size: new google.maps.Size(71, 71),
                                origin: new google.maps.Point(0, 0),
                                anchor: new google.maps.Point(17, 34),
                                scaledSize: new google.maps.Size(25, 25)
                            };

                            // Create a marker for each place.
                            markers.push(new google.maps.Marker({
                                map: map,
                                icon: icon,
                                title: place.name,
                                position: place.geometry.location
                            }));
                            var locationfinal = place.geometry.location;

                            var final = "https://www.google.com/maps/embed/v1/view?key=AIzaSyDYtqUCg0ts6msMJ-WY59w4BnUy5CS5O0Y&center=" + locationfinal + "&zoom=20";
                            $("#mapa").attr("src", final.replace('(', '').replace(')', ''));
                            $("#localidad").attr("value", locationfinal);

                            if (place.geometry.viewport) {
                                // Only geocodes have viewport.
                                bounds.union(place.geometry.viewport);
                            } else {
                                bounds.extend(place.geometry.location);
                            }
                        });
                        map.fitBounds(bounds);
                    });
                }
                </script>
                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDYtqUCg0ts6msMJ-WY59w4BnUy5CS5O0Y&libraries=places&callback=initAutocomplete" async defer></script>
                <!--AIzaSyCIqhaou_lWYTKsyMoo2SYtXL91B7-Vd24-->
                </div>
            </div>
        </div>
        <!--//forms-->
    </div>
</div>
</div>
</div>