<?php echo theme_js('jquery.min.js', true);?>
<?php echo theme_js('jquery-ui.min.js', true);?>
<?php $this->load->view('channel/dash_sidebar'); ?>

<style type="text/css">
  

</style>
<script>
  function background_change(){
    var type = $('select[name=background_type]').val();
    switch(type){
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
<div class="contents">
  <div role="tabpanel">
    <!-- Nav tabs -->
      <div class="col-md-12 col-sm-4 col-xs-12 cls_resp50">
        <div class="cls_side_bar">
          <ul class="list-unstyled clearfix cls_menu_side">
            <li class="active"><a href="#tab1" data-toggle="tab" role="tab">  Booking Engine </a> </li>
            <li><a href="#tab2" data-toggle="tab" role="tab">  Booking Widget</a> </li>
          </ul>
          
        </div>
      </div>
  
    <!-- Tab panes -->
    <div class="tab-content cls-exchng-tab-cont">
      <div role="tabpanel" class="tab-pane fade in active w3-animate-right" id="tab1">
        <form action="<?= lang_url().'booking/update_engine' ?>" method="post" enctype="multipart/form-data">
          <div class="col-sm-6">

            <div class="form-group">
              <p><label for="logo">Logo</label></p>
              <img id="logo" <?= (isset($booking['logo'])) ? 'src="'.base_url('uploads/'.$booking['logo']).'"' : '' ?> width="240" height="180">
              <input type="file" name="logo" class="form-control">
              <script>
                $("input[name=logo]").on("change", function(e){
                  var file = e.target.files[0];
                  imageType = /image.*/;

                    var reader = new FileReader();
                    reader.onload = loadlogo;
                    reader.readAsDataURL(file);
                });

                function loadlogo(e){
                  var result=e.target.result;
                  $('#logo').attr("src",result);
                }
              </script>
            </div>

            <div class="form-group">
              <label for="descripcion">Description</label>
              <textarea name="description" class="form-control" id="" cols="30" rows="10"><?= (isset($booking['description'])) ? $booking['description'] : '' ?></textarea>
            </div>
          </div>

          <div class="col-sm-6">
            <script src="<?php echo base_url();?>user_assets/js/jscolor.js"></script>
            <div class="form-group">
              <label for="header_color">Background</label>
              <select onchange="background_change()" name="background_type" id="">
                <option value="0">Color</option>
                <option value="1">Imagen</option>
              </select>
            </div>
            <div class="form-group">
              <input type="text" name="background" class="jscolor form-control" value="<?= (isset($booking['background'])) ? $booking['background'] : '0' ?>">
              <img id="background" <?= (isset($booking['background'])) ? 'src="'.base_url('uploads/'.$booking['background']).'"' : '' ?> width="240" height="180" style="display:none">
              <input type="file" name="background_img" class="form-control" style="display:none">
              <script>
                $('select[name=background_type]').val('<?= (isset($booking['background_type'])) ? $booking['background_type'] : '0' ?>');
                background_change();

                $("input[name=background_img]").on("change", function(e){
                  var file = e.target.files[0];
                  imageType = /image.*/;

                    var reader = new FileReader();
                    reader.onload = loadbackground;
                    reader.readAsDataURL(file);
                });

                function loadbackground(e){
                  var result=e.target.result;
                  $('#background').attr("src",result);
                }
              </script>
            </div>

            <div class="form-group">
              <label for="header_color">Header Color</label>
              <input type="text" name="header_color" class="jscolor form-control" value="<?= (isset($booking['header_color'])) ? $booking['header_color'] : '2993BC' ?>">
            </div>
          </div>
          <div class="col-sm-12">
            <input type="submit" value="Submit" class="btn btn-primary">
          </div>
        </form>
      </div>
      <div role="tabpanel" class="tab-pane fade in w3-animate-right" id="tab2">
        <form id="form-widget">
          <div class="col-sm-6">
              <h3>Widget</h3>
              <div class="form-group">
                <h4>Show Header</h4>
                <input onchange="update_widget()" type="checkbox" name="header" class="checkbox">
              </div>

              <div class="form-group">
                <h4>Guest Number</h4>
                <select onchange="update_widget()" name="guest_number" id="" class="form-control">
                  <option value="0">Hidden</option>
                  <option value="1">Automatic</option>
                  <option value="2">All</option>
                </select>
              </div>

              <div class="form-group">
                <h4>Ask For Children</h4>
                <input onchange="update_widget()" type="checkbox" name="children" id="" class="checkbox">
              </div>
  
              <div class="form-group">
                <h4>Layout</h4>
                <select onchange="update_widget()" name="layout" id="" class="form-control">
                  <option value="0">DEFAULT</option>
                  <option value="1">FORM</option>
                  <option value="2">VERTICAL</option>
                </select>
              </div>

              <div class="form-group">
                <h4>Floating position</h4>
                <select onchange="update_widget()" name="floating_position" id="" class="form-control">
                  <option value="0">EMBEDDED</option>
                  <option value="1">LEFT</option>
                  <option value="2">CENTER</option>
                  <option value="3">RIGHT</option>
                </select>
              </div>

              <div class="form-group">
                <h4>Open page</h4>
                <select onchange="update_widget()" name="open_page" id="" class="form-control">
                  <option value="0" id="">New tab</option>
                  <option value="1" id="">Same Page</option>
                </select>
              </div>

              <div class="form-group">
                <h4>Theme</h4>
                <select onchange="update_widget()" name="theme" id="" class="form-control">
                  <option value="0">GRAY</option>
                  <option value="1">BLUE</option>
                  <option value="2">GREEN</option>
                  <option value="3">LIGHT BLUE</option>
                  <option value="4">ORANGE</option>
                  <option value="5">RED</option>
                </select>
              </div>

              <div class="form-group">
                <h4>Font</h4>
                <select onchange="update_widget()" name="font" id="" class="form-control">
                  <option value="0">DEFAULT</option>
                  <option value="1">ARIAL</option>
                  <option value="2">TIMES NEW ROMAN</option>
                  <option value="3">COURIER</option>
                  <option value="4">VERDANA</option>
                </select>
              </div>

              <div class="form-group">
                <h4>Custom Css</h4>
                <textarea name="custom_css" id="" cols="30" rows="10" class="form-control"><?= (isset($widget['css'])) ? $widget['css'] : '' ?></textarea>
              </div>
        </form>
        </div>
        <div class="col-sm-6">
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
            &lt;iframe src="<?= lang_url() ?>booking/widget/<?= insep_encode(hotel_id()); ?>" frameborder="0" width="100%" height="260"&gt;&lt;/iframe&gt;        
          </blockquote>

           <h3>Source Code For MultiProperty</h3>
          <h4>Copy de following code and paste in your page inside the tag body</h4>
          <blockquote>
            &lt;iframe src="<?= lang_url() ?>booking/widgetmulti/<?= insep_encode(user_id()); ?>" frameborder="0" width="100%" height="260"&gt;&lt;/iframe&gt;        
          </blockquote>

            <div id="facebook">
              <h2 style="background-color: #00FFFF; ">Facebook Button</h2>
               <h3>Facebook Button For Individual Property </h3>
              <h4>Copy de following code and paste in Facebook Button</h4>
              <blockquote>
                <?= lang_url() ?>booking/widget/<?= insep_encode(hotel_id()); ?>        
              </blockquote>

               <h3>Facebook Button For  MultiProperty</h3>
              <h4>Copy de following code and paste in Facebook Buttony</h4>
              <blockquote>
                  <?= lang_url() ?>booking/widgetmulti/<?= insep_encode(user_id()); ?>  
              </blockquote>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<script>
$("input[name=header]")[0].checked = <?= (isset($widget['show_header'])) ? $widget['show_header'] : '1' ?>;
$("select[name=guest_number]")[0].value = <?= (isset($widget['guest_number'])) ? $widget['guest_number'] : '0' ?>;
$("input[name=children]")[0].checked = <?= (isset($widget['ask_children'])) ? $widget['ask_children'] : '0' ?>;
$("select[name=layout]")[0].value = <?= (isset($widget['layout'])) ? $widget['layout'] : '0' ?>;
$("select[name=floating_position]")[0].value = <?= (isset($widget['floating_position'])) ? $widget['floating_position'] : '0' ?>;
$("select[name=open_page]")[0].value = <?= (isset($widget['open_page'])) ? $widget['open_page'] : '0' ?>;
$("select[name=theme]")[0].value = <?= (isset($widget['theme'])) ? $widget['theme'] : '0' ?>;
$("select[name=font]")[0].value = <?= (isset($widget['font'])) ? $widget['font'] : '0' ?>;

function load_preview(){
  $("#preview")[0].contentWindow.location.reload();
}

function update_widget(){
  form = $("#form-widget")[0];
  if(form.children.checked){
    children = "&children=1";
  }else{
    children = "&children=0";
  }

  if(form.header.checked){
    header = "&header=1";
  }else{
    header = "&header=0";
  }

  form = $(form).serialize();
  form += children+header;

  $.ajax({
    url:"<?= lang_url().'booking/update_widget' ?>",
    type:"POST",
    data:form
  }).done(function(){
    load_preview();
  });
}
</script>