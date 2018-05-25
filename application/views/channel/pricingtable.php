<style>
.content {
    display: none;
    padding: 20px 0 0;
    border-top: 1px solid #ddd;
}

.inputt {
    display: none;
}

.labelt {
    display: inline-block;
    margin: 0 0 -1px;
    padding: 15px 25px;
    font-weight: 900;
    text-align: center;
    color: #bbb;
    border: 1px solid transparent;
}

.labelt:before {
    font-family: fontawesome;
    font-weight: normal;
    margin-right: 10px;
}


.labelt:hover {
    color: #888;
    cursor: pointer;
}

.inputt:checked+label {
    color: #555;
    border: 1px solid #ddd;
    border-top: 2px solid orange;
    border-bottom: 1px solid #fff;
}

#tab1:checked~#content1,
#tab2:checked~#content2,
#tab3:checked~#content3,
#tab4:checked~#content4 {
    display: block;
}

@media screen and (max-width: 650px) {
    .labelt.content {
        font-size: ;
    }

    .labelt.content:before {
        margin: 0;
        font-size: 18px;
    }
}

@media screen and (max-width: 400px) {
    .labelt.content {
        padding: 15px;
    }
}
</style>
<input class="inputt"  id="tab1" type="radio" name="tabs" checked>
<label class="labelt" for="tab1"> Individual Property</label>
<input class="inputt" id="tab2" type="radio" name="tabs">
<label class="labelt" for="tab2">Multi Property</label>
<section class="content" id="content1">
    <div class="pricing_table pricing_six">
        <!-- BEGIN TABLE CONTAINER -->
        <ul style="width: 300px;" class="pricing_column_first">
            <!-- BEGIN DESCRIPTION COLUMN -->
            <li class="pricing_header1"></li>
            <li class="odd"><span>Front Desk</span></li>
            <li class="even"><span>Multi Language and Multi Currency</span></li>
            <li class="odd"><span>Customer Relations Management (CRM)</span></li>
            <li class="even"><span>Online Guest Satisfaction Surveys </span></li>
            <li class="odd"><span>Housekeeping </span></li>
            <li class="even"><span>Unlimited Points of Sale</span></li>
            <li class="odd"><span>Customizable Reports </span></li>
            <li class="even"><span>Competitive Set Analytics Dashboard </span></li>
            <li class="odd"><span>Reputation Management System</span></li>
            <li class="even">
                <a class="tooltip2" href="#">6 Website Booking Engine Templates
                        <span>with Payment Gateway.</span>
                    </a>
            </li>
            <li class="odd"><span>Rate Management System</span></li>
            <li class="even"><span>Facebook Booking Button </span></li>
            <li class="odd"><span>Management Mobile App </span></li>
            <li class="even"><span>Guests Mobile App </span></li>
            <li class="odd"><span>Booking Widget for your website </span></li>
            <li class="even"><span>Accounting Module </span></li>
            <li class="odd"><span>Human Resources Module  </span></li>
            <li class="even"><span>Purchasing </span></li>
            <li class="odd"><span>Costs Module</span></li>
            <li style="text-align: center;" class="pricing_footer">
                <h4>Interface</h4></li>
            <li class="odd">
                <a class="tooltip2" href="#">Channel Manager: 3 Channels
                        <span>View the list of Channel Managers</span>
                    </a>
            </li>
            <li class="even">
                <a class="tooltip2" href="#">Channel Manager: 8 Channels
                        <span>View the list of Channel Managers.</span>
                    </a>
            </li>
            <li class="odd">
                <a class="tooltip2" href="#">Channel Manager: Unlimited Channels 
                        <span>View the list of Channel Managers.</span>
                    </a>
            </li>
            <li class="even"><span>GDS Connectivity</span></li>
            <li class="odd"><span>Quickbooks Interface</span></li>
            <li style="text-align: center;" class="pricing_footer">
                <h4>Services</h4></li>
            <li class="odd"><span>24 x 7 Live Chat Support </span></li>
            <li class="even"><span>Setup &amp Training  </span></li>
            <li class="odd"><span>Unlimited No. of Users</span></li>
            <li style="text-align: center;" class="pricing_footer"></li>
            <li style="text-align: center;" class="pricing_footer"><h4>Choose a Plan</h4></li>
        </ul>
        <!-- END DESCRIPTION COLUMN -->
        <div class="pricing_hover_area">
            <!-- BEGIN HOVER AREA -->
            <ul class="pricing_column gradient_blue">
                <!-- BEGIN FIRST CONTENT COLUMN -->
                <li class="pricing_header1">Free Trial</li>
                <li class="odd" data-table="Front Desk"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Multi Language and Multi Currency"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customer Relations Management (CRM)"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Online Guest Satisfaction Surveys"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Housekeeping"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Unlimited Points of Sale"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customizable Reports"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Competitive Set Analytics Dashboard"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Reputation Management System"><span class="pricing_yes"></span></li>
                <li class="even" data-table="6 Website Booking Engine Template"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Rate Management System"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Facebook Booking Button"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Management Mobile App"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Guests Mobile App"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Booking Widget for your website"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Accounting Module"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Human Resources Module"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Purchasing"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Costs Module"><span class="pricing_yes"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Interface"></li>
                <li class="odd" data-table="Channel Manager: 3 Channels"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Channel Manager: 8 Channels"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Channel Manager: Unlimited Channels "><span class="pricing_yes"></span></li>
                <li class="even" data-table="GDS Connectivity"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Quickbooks Interface"><span class="pricing_yes"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Services"></li>
                <li class="odd" data-table="24 x 7 Live Chat Support"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Setup &amp Training"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Unlimited No. of Users "><span class="pricing_yes"></span></li>
                <li class="pricing_footer"><a href="#0" onclick="tst()" class="pricing_button">Sign Up</a></li>
                <li class="pricing_footer"> <a href="mailto:info@hoteratus.com?subject=The%20Price%20of%20Plans">Contact us for Pricing</a></li>
            </ul>
            <!-- END FIRST CONTENT COLUMN -->
            <ul class="pricing_column gradient_green">
                <!-- BEGIN SECOND CONTENT COLUMN -->
                <li class="pricing_header1">Calendar</li>
                <li class="odd" data-table="Front Desk"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Multi Language and Multi Currency"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customer Relations Management (CRM)"><span class="pricing_no"></span></li>
                <li class="even" data-table="Online Guest Satisfaction Surveys"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Housekeeping"><span class="pricing_no"></span></li>
                <li class="even" data-table="Unlimited Points of Sale"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Customizable Reports"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Competitive Set Analytics Dashboard"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Reputation Management System"><span class="pricing_no"></span></li>
                <li class="even" data-table="6 Website Booking Engine Template"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Rate Management System"><span class="pricing_no"></span></li>
                <li class="even" data-table="Facebook Booking Button"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Management Mobile App"><span class="pricing_no"></span></li>
                <li class="even" data-table="Guests Mobile App"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Booking Widget for your website"><span class="pricing_no"></span></li>
                <li class="even" data-table="Accounting Module"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Human Resources Module"><span class="pricing_no"></span></li>
                <li class="even" data-table="Purchasing"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Costs Module"><span class="pricing_no"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Interface"></li>
                <li class="odd" data-table="Channel Manager: 3 Channels"><span class="pricing_no"></span></li>
                <li class="even" data-table="Channel Manager: 8 Channels"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Channel Manager: Unlimited Channels "><span class="pricing_no"></span></li>
                <li class="even" data-table="GDS Connectivity"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Quickbooks Interface"><span class="pricing_no"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Services"></li>
                <li class="odd" data-table="24 x 7 Live Chat Support"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Setup &amp Training"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Unlimited No. of Users "><span class="pricing_yes"></span></li>
                <li class="pricing_footer"><a onclick="tst()" class="pricing_button">Sign Up</a></li>
                <li class="pricing_footer"> <a href="mailto:info@hoteratus.com?subject=The%20Price%20of%20Plans">Contact us for Pricing</a></li>
            </ul>
            <!-- END SECOND CONTENT COLUMN -->
            <ul class="pricing_column gradient_yellow">
                <!-- BEGIN THIRD CONTENT COLUMN -->
                <li class="pricing_header1">Deluxe</li>
                <li class="odd" data-table="Front Desk"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Multi Language and Multi Currency"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customer Relations Management (CRM)"><span class="pricing_no"></span></li>
                <li class="even" data-table="Online Guest Satisfaction Surveys"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Housekeeping"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Unlimited Points of Sale"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Customizable Reports"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Competitive Set Analytics Dashboard"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Reputation Management System"><span class="pricing_no"></span></li>
                <li class="even" data-table="6 Website Booking Engine Template"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Rate Management System"><span class="pricing_no"></span></li>
                <li class="even" data-table="Facebook Booking Button"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Management Mobile App"><span class="pricing_no"></span></li>
                <li class="even" data-table="Guests Mobile App"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Booking Widget for your website"><span class="pricing_no"></span></li>
                <li class="even" data-table="Accounting Module"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Human Resources Module"><span class="pricing_no"></span></li>
                <li class="even" data-table="Purchasing"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Costs Module"><span class="pricing_no"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Interface"></li>
                <li class="odd" data-table="Channel Manager: 3 Channels"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Channel Manager: 8 Channels"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Channel Manager: Unlimited Channels "><span class="pricing_no"></span></li>
                <li class="even" data-table="GDS Connectivity"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Quickbooks Interface"><span class="pricing_no"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Services"></li>
                <li class="odd" data-table="24 x 7 Live Chat Support"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Setup &amp Training"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Unlimited No. of Users "><span class="pricing_yes"></span></li>
                <li class="pricing_footer"><a onclick="tst()" class="pricing_button">Sign Up</a></li>
                <li class="pricing_footer"> <a href="mailto:info@hoteratus.com?subject=The%20Price%20of%20Plans">Contact us for Pricing</a></li>
            </ul>
            <!-- END THIRD CONTENT COLUMN -->
            <ul class="pricing_column gradient_orange">
                <!-- BEGIN FOURTH CONTENT COLUMN -->
                <li class="pricing_header1">Premium</li>
                <li class="odd" data-table="Front Desk"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Multi Language and Multi Currency"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customer Relations Management (CRM)"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Online Guest Satisfaction Surveys"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Housekeeping"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Unlimited Points of Sale"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customizable Reports"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Competitive Set Analytics Dashboard"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Reputation Management System"><span class="pricing_no"></span></li>
                <li class="even" data-table="6 Website Booking Engine Template"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Rate Management System"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Facebook Booking Button"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Management Mobile App"><span class="pricing_no"></span></li>
                <li class="even" data-table="Guests Mobile App"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Booking Widget for your website"><span class="pricing_no"></span></li>
                <li class="even" data-table="Accounting Module"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Human Resources Module"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Purchasing"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Costs Module"><span class="pricing_yes"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Interface"></li>
                <li class="odd" data-table="Channel Manager: 3 Channels"><span class="pricing_no"></span></li>
                <li class="even" data-table="Channel Manager: 8 Channels"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Channel Manager: Unlimited Channels "><span class="pricing_no"></span></li>
                <li class="even" data-table="GDS Connectivity"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Quickbooks Interface"><span class="pricing_no"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Services"></li>
                <li class="odd" data-table="24 x 7 Live Chat Support"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Setup &amp Training"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Unlimited No. of Users "><span class="pricing_yes"></span></li>
                <li class="pricing_footer"><a onclick="tst()" class="pricing_button">Sign Up</a></li>
                <li class="pricing_footer"> <a href="mailto:info@hoteratus.com?subject=The%20Price%20of%20Plans">Contact us for Pricing</a></li>
            </ul>
            <!-- END FOURTH CONTENT COLUMN -->
            <ul class="pricing_column gradient_red">
                <!-- BEGIN FIFTH CONTENT COLUMN -->
                <li class="pricing_header1">Unlimited</li>
                <li class="odd" data-table="Front Desk"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Multi Language and Multi Currency"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customer Relations Management (CRM)"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Online Guest Satisfaction Surveys"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Housekeeping"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Unlimited Points of Sale"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customizable Reports"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Competitive Set Analytics Dashboard"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Reputation Management System"><span class="pricing_yes"></span></li>
                <li class="even" data-table="6 Website Booking Engine Template"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Rate Management System"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Facebook Booking Button"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Management Mobile App"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Guests Mobile App"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Booking Widget for your website"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Accounting Module"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Human Resources Module"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Purchasing"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Costs Module"><span class="pricing_yes"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Interface"></li>
                <li class="odd" data-table="Channel Manager: 3 Channels"><span class="pricing_no"></span></li>
                <li class="even" data-table="Channel Manager: 8 Channels"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Channel Manager: Unlimited Channels "><span class="pricing_yes"></span></li>
                <li class="even" data-table="GDS Connectivity"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Quickbooks Interface"><span class="pricing_yes"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Services"></li>
                <li class="odd" data-table="24 x 7 Live Chat Support"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Setup &amp Training"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Unlimited No. of Users "><span class="pricing_yes"></span></li>
                <li class="pricing_footer"><a onclick="tst()" class="pricing_button">Sign Up</a></li>
                <li class="pricing_footer"> <a href="mailto:info@hoteratus.com?subject=The%20Price%20of%20Plans">Contact us for Pricing</a></li>
            </ul>
            <!-- END FIFTH CONTENT COLUMN -->
        </div>
        <!-- END HOVER AREA -->
    </div>
    <!-- END TABLE CONTAINER -->
</section>
<section class="content" id="content2">
    <div class="pricing_table pricing_six">
        <!-- BEGIN TABLE CONTAINER -->
        <ul style="width: 300px;" class="pricing_column_first">
            <!-- BEGIN DESCRIPTION COLUMN -->
            <li class="pricing_header1"></li>
            <li class="odd"><span>Front Desk</span></li>
            <li class="even"><span>Multi Language and Multi Currency</span></li>
            <li class="odd"><span>Customer Relations Management (CRM)</span></li>
            <li class="even"><span>Online Guest Satisfaction Surveys </span></li>
            <li class="odd"><span>Housekeeping </span></li>
            <li class="even"><span>Unlimited Points of Sale</span></li>
            <li class="odd"><span>Customizable Reports </span></li>
            <li class="even"><span>Competitive Set Analytics Dashboard </span></li>
            <li class="odd"><span>Reputation Management System</span></li>
            <li class="even">
                <a class="tooltip2" href="#">6 Website Booking Engine Templates
                        <span>with Payment Gateway.</span>
                    </a>
            </li>
            <li class="odd"><span>Rate Management System</span></li>
            <li class="even"><span>Facebook Booking Button </span></li>
            <li class="odd"><span>Management Mobile App </span></li>
            <li class="even"><span>Guests Mobile App </span></li>
            <li class="odd"><span>Booking Widget for your website </span></li>
            <li class="even"><span>Accounting Module </span></li>
            <li class="odd"><span>Human Resources Module  </span></li>
            <li class="even"><span>Purchasing </span></li>
            <li class="odd"><span>Costs Module</span></li>
            <li style="text-align: center;" class="pricing_footer">
                <h4>Interface</h4></li>
            <li class="odd">
                <a class="tooltip2" href="#">Channel Manager: 3 Channels
                        <span>View the list of Channel Managers</span>
                    </a>
            </li>
            <li class="even">
                <a class="tooltip2" href="#">Channel Manager: 8 Channels
                        <span>View the list of Channel Managers.</span>
                    </a>
            </li>
            <li class="odd">
                <a class="tooltip2" href="#">Channel Manager: Unlimited Channels 
                        <span>View the list of Channel Managers.</span>
                    </a>
            </li>
            <li class="even"><span>GDS Connectivity</span></li>
            <li class="odd"><span>Quickbooks Interface</span></li>
            <li style="text-align: center;" class="pricing_footer">
                <h4>Services</h4></li>
            <li class="odd"><span>24 x 7 Live Chat Support </span></li>
            <li class="even"><span>Setup &amp Training  </span></li>
            <li class="odd"><span>Unlimited No. of Users</span></li>
            <li style="text-align: center;" class="pricing_footer"></li>
            <li style="text-align: center;" class="pricing_footer"><h4>Choose a Plan</h4></li>
        </ul>
        <!-- END DESCRIPTION COLUMN -->
        <div class="pricing_hover_area">
            <!-- BEGIN HOVER AREA -->
            <ul class="pricing_column gradient_blue">
                <!-- BEGIN FIRST CONTENT COLUMN -->
                <li class="pricing_header1">Free Trial</li>
                <li class="odd" data-table="Front Desk"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Multi Language and Multi Currency"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customer Relations Management (CRM)"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Online Guest Satisfaction Surveys"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Housekeeping"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Unlimited Points of Sale"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customizable Reports"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Competitive Set Analytics Dashboard"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Reputation Management System"><span class="pricing_yes"></span></li>
                <li class="even" data-table="6 Website Booking Engine Template"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Rate Management System"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Facebook Booking Button"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Management Mobile App"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Guests Mobile App"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Booking Widget for your website"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Accounting Module"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Human Resources Module"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Purchasing"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Costs Module"><span class="pricing_yes"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Interface"></li>
                <li class="odd" data-table="Channel Manager: 3 Channels"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Channel Manager: 8 Channels"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Channel Manager: Unlimited Channels "><span class="pricing_yes"></span></li>
                <li class="even" data-table="GDS Connectivity"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Quickbooks Interface"><span class="pricing_yes"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Services"></li>
                <li class="odd" data-table="24 x 7 Live Chat Support"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Setup &amp Training"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Unlimited No. of Users "><span class="pricing_yes"></span></li>
                <li class="pricing_footer"><a onclick="tst()" class="pricing_button">Sign Up</a></li>
                <li class="pricing_footer"> <a href="mailto:info@hoteratus.com?subject=The%20Price%20of%20Plans">Contact us for Pricing</a></li>
            </ul>
            <!-- END FIRST CONTENT COLUMN -->
            <ul class="pricing_column gradient_green">
                <!-- BEGIN SECOND CONTENT COLUMN -->
                <li class="pricing_header1">Calendar</li>
                <li class="odd" data-table="Front Desk"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Multi Language and Multi Currency"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customer Relations Management (CRM)"><span class="pricing_no"></span></li>
                <li class="even" data-table="Online Guest Satisfaction Surveys"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Housekeeping"><span class="pricing_no"></span></li>
                <li class="even" data-table="Unlimited Points of Sale"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Customizable Reports"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Competitive Set Analytics Dashboard"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Reputation Management System"><span class="pricing_no"></span></li>
                <li class="even" data-table="6 Website Booking Engine Template"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Rate Management System"><span class="pricing_no"></span></li>
                <li class="even" data-table="Facebook Booking Button"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Management Mobile App"><span class="pricing_no"></span></li>
                <li class="even" data-table="Guests Mobile App"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Booking Widget for your website"><span class="pricing_no"></span></li>
                <li class="even" data-table="Accounting Module"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Human Resources Module"><span class="pricing_no"></span></li>
                <li class="even" data-table="Purchasing"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Costs Module"><span class="pricing_no"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Interface"></li>
                <li class="odd" data-table="Channel Manager: 3 Channels"><span class="pricing_no"></span></li>
                <li class="even" data-table="Channel Manager: 8 Channels"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Channel Manager: Unlimited Channels "><span class="pricing_no"></span></li>
                <li class="even" data-table="GDS Connectivity"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Quickbooks Interface"><span class="pricing_no"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Services"></li>
                <li class="odd" data-table="24 x 7 Live Chat Support"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Setup &amp Training"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Unlimited No. of Users "><span class="pricing_yes"></span></li>
                <li class="pricing_footer"><a onclick="tst()" class="pricing_button">Sign Up</a></li>
                <li class="pricing_footer"> <a href="mailto:info@hoteratus.com?subject=The%20Price%20of%20Plans">Contact us for Pricing</a></li>
            </ul>
            <!-- END SECOND CONTENT COLUMN -->
            <ul class="pricing_column gradient_yellow">
                <!-- BEGIN THIRD CONTENT COLUMN -->
                <li class="pricing_header1">Deluxe</li>
                <li class="odd" data-table="Front Desk"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Multi Language and Multi Currency"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customer Relations Management (CRM)"><span class="pricing_no"></span></li>
                <li class="even" data-table="Online Guest Satisfaction Surveys"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Housekeeping"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Unlimited Points of Sale"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Customizable Reports"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Competitive Set Analytics Dashboard"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Reputation Management System"><span class="pricing_no"></span></li>
                <li class="even" data-table="6 Website Booking Engine Template"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Rate Management System"><span class="pricing_no"></span></li>
                <li class="even" data-table="Facebook Booking Button"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Management Mobile App"><span class="pricing_no"></span></li>
                <li class="even" data-table="Guests Mobile App"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Booking Widget for your website"><span class="pricing_no"></span></li>
                <li class="even" data-table="Accounting Module"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Human Resources Module"><span class="pricing_no"></span></li>
                <li class="even" data-table="Purchasing"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Costs Module"><span class="pricing_no"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Interface"></li>
                <li class="odd" data-table="Channel Manager: 3 Channels"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Channel Manager: 8 Channels"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Channel Manager: Unlimited Channels "><span class="pricing_no"></span></li>
                <li class="even" data-table="GDS Connectivity"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Quickbooks Interface"><span class="pricing_no"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Services"></li>
                <li class="odd" data-table="24 x 7 Live Chat Support"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Setup &amp Training"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Unlimited No. of Users "><span class="pricing_yes"></span></li>
                <li class="pricing_footer"><a onclick="tst()" class="pricing_button">Sign Up</a></li>
                <li class="pricing_footer"> <a href="mailto:info@hoteratus.com?subject=The%20Price%20of%20Plans">Contact us for Pricing</a></li>
            </ul>
            <!-- END THIRD CONTENT COLUMN -->
            <ul class="pricing_column gradient_orange">
                <!-- BEGIN FOURTH CONTENT COLUMN -->
                <li class="pricing_header1">Premium</li>
                <li class="odd" data-table="Front Desk"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Multi Language and Multi Currency"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customer Relations Management (CRM)"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Online Guest Satisfaction Surveys"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Housekeeping"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Unlimited Points of Sale"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customizable Reports"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Competitive Set Analytics Dashboard"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Reputation Management System"><span class="pricing_no"></span></li>
                <li class="even" data-table="6 Website Booking Engine Template"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Rate Management System"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Facebook Booking Button"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Management Mobile App"><span class="pricing_no"></span></li>
                <li class="even" data-table="Guests Mobile App"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Booking Widget for your website"><span class="pricing_no"></span></li>
                <li class="even" data-table="Accounting Module"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Human Resources Module"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Purchasing"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Costs Module"><span class="pricing_yes"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Interface"></li>
                <li class="odd" data-table="Channel Manager: 3 Channels"><span class="pricing_no"></span></li>
                <li class="even" data-table="Channel Manager: 8 Channels"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Channel Manager: Unlimited Channels "><span class="pricing_no"></span></li>
                <li class="even" data-table="GDS Connectivity"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Quickbooks Interface"><span class="pricing_no"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Services"></li>
                <li class="odd" data-table="24 x 7 Live Chat Support"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Setup &amp Training"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Unlimited No. of Users "><span class="pricing_yes"></span></li>
                <li class="pricing_footer"><a onclick="tst()" class="pricing_button">Sign Up</a></li>
                <li class="pricing_footer"> <a href="mailto:info@hoteratus.com?subject=The%20Price%20of%20Plans">Contact us for Pricing</a></li>
            </ul>
            <!-- END FOURTH CONTENT COLUMN -->
            <ul class="pricing_column gradient_red">
                <!-- BEGIN FIFTH CONTENT COLUMN -->
                <li class="pricing_header1">Unlimited</li>
                <li class="odd" data-table="Front Desk"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Multi Language and Multi Currency"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customer Relations Management (CRM)"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Online Guest Satisfaction Surveys"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Housekeeping"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Unlimited Points of Sale"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Customizable Reports"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Competitive Set Analytics Dashboard"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Reputation Management System"><span class="pricing_yes"></span></li>
                <li class="even" data-table="6 Website Booking Engine Template"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Rate Management System"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Facebook Booking Button"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Management Mobile App"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Guests Mobile App"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Booking Widget for your website"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Accounting Module"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Human Resources Module"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Purchasing"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Costs Module"><span class="pricing_yes"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Interface"></li>
                <li class="odd" data-table="Channel Manager: 3 Channels"><span class="pricing_no"></span></li>
                <li class="even" data-table="Channel Manager: 8 Channels"><span class="pricing_no"></span></li>
                <li class="odd" data-table="Channel Manager: Unlimited Channels "><span class="pricing_yes"></span></li>
                <li class="even" data-table="GDS Connectivity"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Quickbooks Interface"><span class="pricing_yes"></span></li>
                <li style="font-size: 20px;" class="pricing_footer" data-table="Services"></li>
                <li class="odd" data-table="24 x 7 Live Chat Support"><span class="pricing_yes"></span></li>
                <li class="even" data-table="Setup &amp Training"><span class="pricing_yes"></span></li>
                <li class="odd" data-table="Unlimited No. of Users "><span class="pricing_yes"></span></li>
                <li class="pricing_footer"><a onclick="tst()" class="pricing_button">Sign Up</a></li>
                <li class="pricing_footer"> <a href="mailto:info@hoteratus.com?subject=The%20Price%20of%20Plans">Contact us for Pricing</a></li>
            </ul>
            <!-- END FIFTH CONTENT COLUMN -->
        </div>
        <!-- END HOVER AREA -->
    </div>
    <!-- END TABLE CONTAINER -->
</section>
<script type="text/javascript">
function tst() {
    swal({
        title: "Done!",
        text: "Coming Soon!",
        icon: "success",
        button: "Ok!",
    });

    return false;
}
</script>