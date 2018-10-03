<div class="outter-wp">
    <!--custom-widgets-->
    <div class="custom-widgets">
        <div class="row-one">
            <div class="col-md-3 widget">
                <div class="stats-left ">
                    <h4>Today</h4>
                    <h5> New Reservations</h5>
                </div>
                <div class="stats-right">
                    <label>
                        <?= $AllHotelInfo['reserve'] ?>
                    </label>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="col-md-3 widget states-mdl">
                <div class="stats-left">
                    <h4>Today</h4>
                    <h5>New Cancelations</h5>
                </div>
                <div class="stats-right">
                    <label>
                        <?= $AllHotelInfo['cancel'] ?>
                    </label>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="col-md-3 widget states-thrd">
                <div class="stats-left">
                    <h4>Today</h4> 
                    <h5>Arrivals</h5>
                </div>
                <div class="stats-right">
                    <label>
                        <?= $AllHotelInfo['arrival']  ?>
                    </label>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="col-md-3 widget states-last">
                <div class="stats-left">
                    <h4>Today</h4>
                    <h5>Departure</h5>
                </div>
                <div class="stats-right">
                    <label>
                        <?= $AllHotelInfo['depature']  ?>
                    </label>
                </div>
                <div class="clearfix"> </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
    <!--//custom-widgets-->
    <!--//charts-->
    <div class="area-charts">
        <div class="col-md-6 panel-chrt">
            <h4 class="sub-tittle">Channel Status </h4>
            <div style="height:315px; overflow:auto; ">
                <?php  if ($allConection) {         

                                            foreach ($allConection as  $value) {
                                                echo'<ul class="timeline" >';
                                                if ($value->status=='enabled') {

                                                    if ($value->message=='') {
                                                        echo'<li>
                                                                <div class="timeline-badge success"><i class="fa fa-check-circle"></i></div>
                                                                    <div class="timeline-panel">
                                                                    <div class="timeline-heading">
                                                                        <h4 class="timeline-title">'.$value->channel_name.'</h4>
                                                                    </div>
                                                                    <div class="timeline-body">
                                                                        <p>GOOD PERFORMANCE</p>
                                                                    </div>
                                                                </div>
                                                            </li>';
                                                    } 
                                                    else
                                                    {
                                                        echo'<li>
                                                                <div class="timeline-badge success"><i class="fa fa-star"></i></div>
                                                                    <div class="timeline-panel">
                                                                    <div class="timeline-heading">
                                                                        <h4 class="timeline-title">'.$value->channel_name.'</h4>
                                                                    </div>
                                                                    <div class="timeline-body">
                                                                        <p>'.$value->message.'</p>
                                                                    </div>
                                                                </div>
                                                            </li>';
                                                    }
                                                    
                                                }
                                                else
                                                {
                                                    echo'<li>
                                                        <div class="timeline-badge danger"><i class="fa fa-times-circle"></i></div>
                                                            <div class="timeline-panel">
                                                            <div class="timeline-heading">
                                                                <h4 class="timeline-title">'.$value->channel_name.'</h4>
                                                            </div>
                                                            <div class="timeline-body">
                                                                <p>'.strtoupper($value->status).'</p>
                                                            </div>
                                                        </div>
                                                    </li>';
                                                }

                                                echo '</ul>';
                                            } 
                                        }
                                        else
                                        {
                                            echo'<div class="stats-info graph"
                                                    <div class="stats">
                                                            <ul class="list-unstyled">
                                                                <h4 class="sub-tittle"> No Channels Connected!!</h4>
                                                            </ul>
                                                        </div>
                                                </div>';
                                        }
                                        ?>
            </div>
        </div>
        <div class="col-md-6 tini-time-line">
            <h4 class="sub-tittle">Top Channels, Last 30 days</h4>
            <div class="stats-info graph">
                <div class="stats">
                    <ul class="list-unstyled">
                        <?php if (count($TopChannel)>0) { 
                                                    arsort($TopChannel);
                                                    foreach ($TopChannel as $key => $value) {
                                                        echo '<li>'.$key.'<div class="text-success pull-right">'.$value.'<i class="fa fa-level-up-alt"></i></div></li>';
                                                    }
                                                    
                                                    }
                                                    else
                                                    {
                                                        
                                                        echo'<h4 class="sub-tittle"> No Records Found!!</h4>';
                                                    }

                                                ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <!--/bottom-grids-->
    <div class="bottom-grids">
        <div class="dev-table">
            <div class="col-md-4 dev-col">
                <div class="dev-widget dev-widget-transparent">
                    <h2 class="inner one">Today</h2>
                    <p>Today's Occupation Percentage</p>
                    <div class="dev-stat"><span class="counter"><?= $Percentage['hoy'] ?></span>%</div>
                    <div class="progress progress-bar-xs">
                        <div class="progress-bar <?= ($Percentage['hoy']<30?'progress-bar-danger':($Percentage['hoy']<50?'progress-bar-info':'progress-bar-success')) ?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?= $Percentage['hoy'].'%' ?> ;">
                        </div>
                    </div>
                    <!--<p>We Todayly recommend you change your plan to <strong>Pro</strong>. Click here to get more details.</p>-->
                    <a href="#" class="dev-drop">More Info<span class="fa fa-angle-right pull-right"></span></a>
                </div>
            </div>
            <div class="col-md-4 dev-col mid">
                <div class="dev-widget dev-widget-transparent dev-widget-success">
                    <h3 class="inner">Week</h3>
                    <p>Occupation Percentage for the next 7 days</p>
                    <div class="dev-stat"><span class="counter"><?= $Percentage['semana'] ?></span>%</div>
                    <div class="progress progress-bar-xs">
                        <div class="progress-bar <?= ($Percentage['semana']<30?'progress-bar-danger':($Percentage['semana']<50?'progress-bar-info':'progress-bar-success')) ?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?= $Percentage['semana'].'%' ?>;">
                        </div>
                    </div>
                    <a href="#" class="dev-drop">More Info<span class="fa fa-angle-right pull-right"></span></a>
                </div>
            </div>
            <div class="col-md-4 dev-col">
                <div class="dev-widget dev-widget-transparent dev-widget-danger">
                    <h3 class="inner">Month</h3>
                    <p>Occupation Percentage for the next 30 days</p>
                    <div class="dev-stat"><span class="counter"><?= $Percentage['mes'] ?></span>%</div>
                    <div class="progress progress-bar-xs">
                        <div class="progress-bar <?= ($Percentage['mes']<30?'progress-bar-danger':($Percentage['mes']<50?'progress-bar-info':'progress-bar-success')) ?> " role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?= $Percentage['mes'].'%' ?>;">
                        </div>
                    </div>
                    <a href="#" class="dev-drop">More Info<span class="fa fa-angle-right pull-right"></span></a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <!--/charts-inner-->
    </div>
    <!--//outer-wp-->
</div>
<!--footer section start-->
<footer>
    <p>&copy 2018. All Rights Reserved | Design by <a href="https://hoteratus.com/" target="_blank">Hoteratus.</a></p>
</footer>
<!--footer section end-->
</div>
</div>