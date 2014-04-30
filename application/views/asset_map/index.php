<div class='asset_map_container clearfix'>
    <div id="map_filter_container" class='clearfix'>
        <BR>
                <?php
                    $begin = "<H4>All community orgs ";
                      $end = "</H4>";
                    if(isset($selected_issue))
                    {
                        echo $begin."specializing in <span class='highlight_item'>".$selected_issue."</span>".$end;
                    }
                    elseif(isset($selected_area))
                    {
                        echo $begin."servicing <span class='highlight_item'>".$selected_area."</span>".$end;
                    }
                 ?>
        <BR>
       
        <form action="<?php echo base_url().index_page()."/asset_map/filter"; ?>" method="post" onsubmit="
                      return btn_disable();">
            
            <h5 class='pull-left'>Filter by </h5> 
            <?php 
                  $specialties = array('' => 'Org Specialty','all' =>'"SHOW ALL"') + $specialties;
                $service_areas = array('' => 'Org Service Area') + $service_areas;

                echo form_dropdown('specialty', @$specialties, '','onchange="this.form.submit()"'); 
                echo "<BR>";
                echo form_dropdown('service_area', @$service_areas, '','onchange="this.form.submit()"'); 
            ?>        
           
<!--            <input type="submit" name="submit" id="form_submit_btn"/>-->
        </form>
    </div> 
    <?php
        // set a special class for this view if it is being loaded into another view
        if(isset($index_view))
        {
            $id = 'asset_map_index';
        }
        else 
        {
            $id = "canvas_container";
        }
    ?>
    <div class='asset_map_results_container'>
        <?=@$provider_index?>
    </div>
    

    <div id='<?=$id?>' class='clearfix'>
        
        <div id="map_canvas"></div>
    </div>
</div>


