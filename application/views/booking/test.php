<?php  
    $widget['layout'] = (isset($widget['layout'])) ? $widget['layout'] : '0';
    switch($widget['layout']){
        case '0':
            $layout = 'col-xs-6';
        break;

        case '1':
            $layout = 'col-xs-12';
        break;

        case '2':
            $layout = 'col-xs-3';
        break;
    }

    $widget['floating_position'] = (isset($widget['floating_position'])) ? $widget['floating_position'] : '0';
    switch($widget['floating_position']){
        case '0':
            $floating = '';
        break;

        case '1':
            $floating = 'col-xs-6';
        break;

        case '2':
            $floating = 'col-xs-6 col-xs-offset-3';
        break;

        case '3':
            $floating = 'col-xs-6 col-xs-offset-6';
        break;
    }

    $widget['theme'] = (isset($widget['theme'])) ? $widget['theme'] : '0';
    switch($widget['theme']){
        case '0':
            $theme = 'default';
        break;
        case '1':
            $theme = 'primary';
        break;
        case '2':
            $theme = 'success';
        break;
        case '3':
            $theme = 'info';
        break;
        case '4':
            $theme = 'warning';
        break;
        case '5':
            $theme = 'danger';
    }

    $widget['font'] = (isset($widget['font'])) ? $widget['font'] : '0';
    switch($widget['font']){
        case '0':
            $font = '';
        break;
        case '1':
            $font = 'arial';
        break;
        case '2':
            $font = 'Times New Roman';
        break;
        case '3':
            $font = 'courier';
        break;
        case '4':
            $font = 'verdana';
    }
?>
<style>
    body{
        overflow: hidden;
        font-family: <?= $font; ?>;
    }
    .middle{
        margin-top: 30vh;
    }

    .bottom{
        margin-top: 75vh;
    }
</style>
<div  class="<?= $floating; ?>">
    <div class="panel panel-<?= $theme; ?>">
        <?php  
            $widget['show_header'] = (isset($widget['show_header'])) ? $widget['show_header'] : '1';
            if($widget['show_header'] !== '0'){
                echo '<div class="panel-heading" >Booking Online</div>';        
            }
        ?>
        <div class="panel-body">
            <div class="<?= $layout ?>">
                <div class="form-group">
                    <label for="dp1">Checkin</label>
                    <input type="text" name="dp1" id="dp1" class="form-control">
                </div>
            </div>
            <div class="<?= $layout ?>">
                <div class="form-group">
                    <label for="dp1">Checkout</label>
                    <input type="text" name="dp2" id="dp2" class="form-control">
                </div>
            </div>
            <?php  
                $widget['guest_number'] = (isset($widget['guest_number'])) ? $widget['guest_number'] : '0';
                if($widget['guest_number'] !== '0'){
                    echo '<div class="'.$layout.'">';
                        echo '<div class="form-group">';
                            echo '<label for="dp1">Adult</label>';
                            echo '<select name="dp1" class="form-control">';
                                echo '<option>1 Adult</option>';
                            echo '</select>';
                        echo '</div>';
                    echo '</div>';
                }

                $widget['ask_children'] = (isset($widget['ask_children'])) ? $widget['ask_children'] : '0';
                if($widget['ask_children'] !== '0'){
                    echo '<div class="'.$layout.'">';
                        echo '<div class="form-group">';
                            echo '<label for="dp1">Children</label>';
                            echo '<select name="dp1" class="form-control">';
                                echo '<option>0 Child</option>';
                            echo '</select>';
                        echo '</div>';
                    echo '</div>';
                }
            ?>
            <div class="<?= $layout ?>">
                <input type="submit" value="Book" class="btn btn-<?= $theme; ?>">   
            </div>
        </div>
    </div>
</div>
<script>
    $("#dp1").datepicker({
        minDate:new Date()
    });


    $("#dp2").datepicker({
        minDate:new Date()
    });

    $("#dp1").on("change", function(){
        $("#dp2").datepicker( "option", "minDate", $( "#dp1" ).datepicker( "getDate")  );
    })
</script>