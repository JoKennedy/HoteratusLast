<style type="text/css">
 select
 {
   width: 100%; padding: 9px;
 }
</style>
<script>
function background_change() {
    var type = $('select[name=background_type]').val();
    switch (type) {
        case '0':
            $("#background").hide();
            $('input[name=background_img]').hide();
            $('input[name=background]').show();
            break;
        case '1':
            $('input[name=background_img]').show();
            $("#background").show();
            $('input[name=background]').hide();
    }
}
</script>


<div class="outter-wp">
    <!--sub-heard-part-->
    <div class="sub-heard-part">
        <ol class="breadcrumb m-b-0">
            <li><a href="<?php echo base_url();?>channel/dashboard">Home</a></li>
            <li class="active">Manage Engine</li>
        </ol>
    </div>
    <div class="profile-widget" style="background-color: white;">
    </div>
    <div class="tab-main">
        <div class="tab-inner">
            <div id="tabs" class="tabs">
                <div class="">
                    <nav>
                        <ul>
                            <li><a onclick="showtab(1,this);" class="maintab"> <span>Booking Engine</span></a></li>
                            <li><a onclick="showtab(2,this);" class="maintab" style="color:#fff; background:#00C6D7;"> <span>Booking Widget</span></a></li>
                            <li><a onclick="showtab(3,this);" class="maintab" style="color:#fff; background:#00C6D7;"> <span>Widget Multi Property</span></a></li>
                        </ul>
                    </nav>
                    <div class="content tab">
                        <section id="section-1" class=" sec">
                            <form action="<?= lang_url().'booking/update_engine' ?>" method="post" enctype="multipart/form-data">
                                <div style="float:left; width:50%;">
                                    <div class="col-md-12 form-group1">
                                        <p>
                                            <label for="logo">Logo</label>
                                        </p>
                                        <img id="logo" <?=( isset($booking['logo'])) ? 'src="'.base_url( 'uploads/'.$booking[ 'logo']). '"' : '' ?> width="240" height="180">
                                        <input type="file" name="logo" style="background:white; color:black;">
                                        <script>
                                        $("input[name=logo]").on("change", function(e) {
                                            var file = e.target.files[0];
                                            imageType = /image.*/;

                                            var reader = new FileReader();
                                            reader.onload = loadlogo;
                                            reader.readAsDataURL(file);
                                        });

                                        function loadlogo(e) {
                                            var result = e.target.result;
                                            $('#logo').attr("src", result);
                                        }
                                        </script>
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <label for="descripcion">Description</label>
                                        <textarea name="description" class="form-control" id="" cols="30" rows="10">
                                            <?= (isset($booking['description'])) ? $booking['description'] : '' ?>
                                        </textarea>
                                    </div>
                                </div>
                                <div style="float:right; width:50%; padding-left: 20px;">
                                    <script src="<?php echo base_url();?>user_assets/js/jscolor.js"></script>
                                    <div class="col-md-12 form-group1">
                                        <label for="header_color">Background</label>
                                        <select onchange="background_change()" name="background_type" id="">
                                            <option value="0">Color</option>
                                            <option value="1">Imagen</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <input type="text" name="background" id="backgroundc" class="jscolor form-control" value="<?= (isset($booking['background'])) ? $booking['background'] : '0' ?>">
                                        <img id="background" <?=( isset($booking[ 'background'])) ? 'src="'.base_url( 'uploads/'.$booking[ 'background']). '"' : '' ?> width="240" height="180" style="display:none">
                                        <input type="file" name="background_img" class="form-control" style="display:none">
                                        <script>
                                        $('select[name=background_type]').val('<?= (isset($booking['
                                            background_type '])) ? $booking['
                                            background_type '] : '
                                            0 ' ?>');
                                        background_change();



                                        $("input[name=background_img]").on("change", function(e) {
                                            var file = e.target.files[0];
                                            imageType = /image.*/;

                                            var reader = new FileReader();
                                            reader.onload = loadbackground;
                                            reader.readAsDataURL(file);
                                        });

                                        function loadbackground(e) {
                                            var result = e.target.result;
                                            $('#background').attr("src", result);
                                        }
                                        </script>
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <label for="header_color">Header Color</label>
                                        <input type="text" name="header_color" id="header_color" class="jscolor form-control" value="<?= (isset($booking['header_color'])) ? $booking['header_color'] : '2993BC' ?>">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="submit" value="Submit" class="btn btn-primary">
                                </div>
                            </form>
                            <div class="clearfix"></div>
                        </section>
                        <section id="section-2" class="content-current sec">
                            <form id="form-widget">
                                <div style="float:left; width:50%;">
                                    <h3>Widget</h3>
                                    <div class="col-md-12 form-group1">
                                        <h4>Show Header</h4>
                                        <input onchange="update_widget()" type="checkbox" name="header" class="checkbox">
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Guest Number</h4>
                                        <select onchange="update_widget()" name="guest_number" id="" >
                                            <option value="0">Hidden</option>
                                            <option value="1">Automatic</option>
                                            <option value="2">All</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Ask For Children</h4>
                                        <input onchange="update_widget()" type="checkbox" name="children" id="" class="checkbox">
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Layout</h4>
                                        <select onchange="update_widget()" name="layout" id="" >
                                            <option value="0">DEFAULT</option>
                                            <option value="1">FORM</option>
                                            <option value="2">VERTICAL</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Floating position</h4>
                                        <select onchange="update_widget()" name="floating_position" id="" >
                                            <option value="0">EMBEDDED</option>
                                            <option value="1">LEFT</option>
                                            <option value="2">CENTER</option>
                                            <option value="3">RIGHT</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Open page</h4>
                                        <select onchange="update_widget()" name="open_page" id="" >
                                            <option value="0" id="">New tab</option>
                                            <option value="1" id="">Same Page</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Theme</h4>
                                        <select onchange="update_widget()" name="theme" id="" >
                                            <option value="0">GRAY</option>
                                            <option value="1">BLUE</option>
                                            <option value="2">GREEN</option>
                                            <option value="3">LIGHT BLUE</option>
                                            <option value="4">ORANGE</option>
                                            <option value="5">RED</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Font</h4>
                                        <select onchange="update_widget()" name="font" id="" >
                                            <option value="0">DEFAULT</option>
                                            <option value="1">ARIAL</option>
                                            <option value="2">TIMES NEW ROMAN</option>
                                            <option value="3">COURIER</option>
                                            <option value="4">VERDANA</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Custom Css</h4>
                                        <textarea name="custom_css" id="" cols="30" rows="10" >
                                            <?= (isset($widget['css'])) ? $widget['css'] : '' ?>
                                        </textarea>
                                    </div>
                                </div>
                            </form>
                            
                            <div style="float:right; width:50%; padding-left: 20px;">
                                <h3>Preview widget</h3>
                                <iframe src="<?= lang_url().'booking/test'; ?>" id="preview" frameborder="0" width="100%" height="260"></iframe>
                                <div class="col-sm-6">
                                    <a href="<?= lang_url().'booking/test'; ?>" target="_blank">
                                        <h3>View in blank page</h3>
                                    </a>
                                </div>
                                <div class="col-sm-6" style="margin-bottom: 80px;">
                                    <a href="javascript:load_preview();">
                                        <h3>Reload Preview</h3>
                                    </a>
                                </div>
                                <h3>Source Code For Individual Property</h3>
                                <h4>Copy de following code and paste in your page inside the tag body</h4>
                                <blockquote>
                                    &lt;iframe src="
                                    <?= lang_url() ?>booking/widget/<?=insep_encode(hotel_id())?>" frameborder="0" width="100%" height="260"&gt;&lt;/iframe&gt;
                                </blockquote>
                                
                                <div id="facebook">
                                    <h2 style="background-color: #00FFFF; ">Facebook Button</h2>
                                    <h3>Facebook Button For Individual Property </h3>
                                    <h4>Copy de following code and paste in Facebook Button</h4>
                                    <blockquote>
                                        <?= lang_url() ?>booking/widget/<?=insep_encode(hotel_id())?>
                                    </blockquote>
                                </div>
                            </div>
                        </section>
                        <section id="section-3" class="content-current sec">
                            <form id="form-widget2">
                                <div style="float:left; width:50%;">
                                    <h3>Widget</h3>
                                    <div class="col-md-12 form-group1">
                                        <h4>Show Header</h4>
                                        <input onchange="update_widget2()" type="checkbox" name="header2" class="checkbox">
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Guest Number</h4>
                                        <select onchange="update_widget2()" name="guest_number2" id="" >
                                            <option value="0">Hidden</option>
                                            <option value="1">Automatic</option>
                                            <option value="2">All</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Ask For Children</h4>
                                        <input onchange="update_widget2()" type="checkbox" name="children2" id="" class="checkbox">
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Layout</h4>
                                        <select onchange="update_widget2()" name="layout2" id="" >
                                            <option value="0">DEFAULT</option>
                                            <option value="1">FORM</option>
                                            <option value="2">VERTICAL</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Floating position</h4>
                                        <select onchange="update_widget2()" name="floating_position2" id="" >
                                            <option value="0">EMBEDDED</option>
                                            <option value="1">LEFT</option>
                                            <option value="2">CENTER</option>
                                            <option value="3">RIGHT</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Open page</h4>
                                        <select onchange="update_widget2()" name="open_page2" id="" >
                                            <option value="0" id="">New tab</option>
                                            <option value="1" id="">Same Page</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Theme</h4>
                                        <select onchange="update_widget2()" name="theme2" id="" >
                                            <option value="0">GRAY</option>
                                            <option value="1">BLUE</option>
                                            <option value="2">GREEN</option>
                                            <option value="3">LIGHT BLUE</option>
                                            <option value="4">ORANGE</option>
                                            <option value="5">RED</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Font</h4>
                                        <select onchange="update_widget2()" name="font2" id="" >
                                            <option value="0">DEFAULT</option>
                                            <option value="1">ARIAL</option>
                                            <option value="2">TIMES NEW ROMAN</option>
                                            <option value="3">COURIER</option>
                                            <option value="4">VERDANA</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group1">
                                        <h4>Custom Css</h4>
                                        <textarea name="custom_css2" id="" cols="30" rows="10" >
                                            <?= (isset($widgetM['css'])) ? $widgetM['css'] : '' ?>
                                        </textarea>
                                    </div>
                                </div>
                            </form>
                            
                            <div style="float:right; width:50%; padding-left: 20px;">
                                <h3>Preview widget</h3>
                                <iframe src="<?= lang_url().'booking/test2'; ?>" id="preview2" frameborder="0" width="100%" height="260"></iframe>
                                <div class="col-sm-6">
                                    <a href="<?= lang_url().'booking/test2'; ?>" target="_blank">
                                        <h3>View in blank page</h3>
                                    </a>
                                </div>
                                <div class="col-sm-6" style="margin-bottom: 80px;">
                                    <a href="javascript:load_preview2();">
                                        <h3>Reload Preview</h3>
                                    </a>
                                </div>
                                <h3>Source Code For MultiProperty</h3>
                                <h4>Copy de following code and paste in your page inside the tag body</h4>
                                <blockquote>
                                    &lt;iframe src="
                                    <?= lang_url() ?>booking/widgetmulti/<?=insep_encode(user_id())?>" frameborder="0" width="100%" height="260"&gt;&lt;/iframe&gt;
                                </blockquote>
                                <div id="facebook">
                                    <h2 style="background-color: #00FFFF; ">Facebook Button</h2>
                                    <h3>Facebook Button For  MultiProperty</h3>
                                    <h4>Copy de following code and paste in Facebook Button</h4>
                                    <blockquote>
                                        <?= lang_url() ?>booking/widgetmulti/<?=insep_encode(user_id()); ?>
                                    </blockquote>
                                </div>
                            </div>
                        </section>
                    </div>
                    <!-- /content -->
                </div>
                <!-- /tabs -->
            </div>
        </div>
    </div>
</div>
</div>
</div>


<script src="<?php echo base_url();?>user_asset/back/js/colorpicker.js"></script>
<script>
$("input[name=header]")[0].checked = <?= (isset($widget['show_header'])) ? $widget['show_header'] : '1' ?>;
$("select[name=guest_number]")[0].value = <?= (isset($widget['guest_number'])) ? $widget['guest_number'] : '0' ?>;
$("input[name=children]")[0].checked = <?= (isset($widget['ask_children'])) ? $widget['ask_children'] : '0' ?>;
$("select[name=layout]")[0].value = <?= (isset($widget['layout'])) ? $widget['layout'] : '0' ?>;
$("select[name=floating_position]")[0].value = <?= (isset($widget['floating_position'])) ? $widget['floating_position'] : '0' ?>;
$("select[name=open_page]")[0].value = <?= (isset($widget['open_page'])) ? $widget['open_page'] : '0' ?>;
$("select[name=theme]")[0].value = <?= (isset($widget['theme'])) ? $widget['theme'] : '0' ?>;
$("select[name=font]")[0].value = <?= (isset($widget['font'])) ? $widget['font'] : '0' ?>;

$("input[name=header2]")[0].checked = <?= (isset($widgetM['show_header'])) ? $widgetM['show_header'] : '1' ?>;
$("select[name=guest_number2]")[0].value = <?= (isset($widgetM['guest_number'])) ? $widgetM['guest_number'] : '0' ?>;
$("input[name=children2]")[0].checked = <?= (isset($widgetM['ask_children'])) ? $widgetM['ask_children'] : '0' ?>;
$("select[name=layout2]")[0].value = <?= (isset($widgetM['layout'])) ? $widgetM['layout'] : '0' ?>;
$("select[name=floating_position2]")[0].value = <?= (isset($widgetM['floating_position'])) ? $widgetM['floating_position'] : '0' ?>;
$("select[name=open_page2]")[0].value = <?= (isset($widgetM['open_page'])) ? $widgetM['open_page'] : '0' ?>;
$("select[name=theme2]")[0].value = <?= (isset($widgetM['theme'])) ? $widgetM['theme'] : '0' ?>;
$("select[name=font2]")[0].value = <?= (isset($widgetM['font'])) ? $widgetM['font'] : '0' ?>;


$('#header_color, #backgroundc').simpleColor();


function showtab(id,oje) {
    $(".sec").removeClass("content-current");
    $("#section-" + id).addClass("content-current");
    $(".maintab").css({
                      background:'#fff' ,
                      color: '#00C6D7'
                    });
    $(oje).css({
      background: '#00C6D7',
      color: '#fff'
    });
}

function load_preview() {
    $("#preview")[0].contentWindow.location.reload();
}
function load_preview2() {
    $("#preview2")[0].contentWindow.location.reload();
}

function update_widget() {
    form = $("#form-widget")[0];
    if (form.children.checked) {
        children = "&children=1";
    } else {
        children = "&children=0";
    }

    if (form.header.checked) {
        header = "&header=1";
    } else {
        header = "&header=0";
    }

    form = $(form).serialize();
    form += children + header;

    $.ajax({
        url: "<?= lang_url().'booking/update_widget' ?>",
        type: "POST",
        data: form
    }).done(function() {
        load_preview();
    });
}
function update_widget2() {

    form = $("#form-widget2")[0];
    if (form.children2.checked) {
        children = "&children=1";
    } else {
        children = "&children=0";
    }

    if (form.header2.checked) {
        header = "&header=1";
    } else {
        header = "&header=0";
    }

    form = $(form).serialize();
    form += children + header;


    $.ajax({
        url: "<?= lang_url().'booking/update_widget2' ?>",
        type: "POST",
        data: form
    }).done(function(m) {
        load_preview2();
    });
}

</script>