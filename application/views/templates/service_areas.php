<div class="service_areas_container clearfix">
        <?php
            if(isset($service_areas) && count($service_areas) > 0){
                echo "<h5>Neighborhoods Served</h5>";
                
               $per_column = 20;
                 $complete = FALSE; 
                        $i = 0;
//                echo "<div class='clearfix'>";
                while(!$complete){
                    echo "<div class=''>";
                    foreach($service_areas as $key => $service_area){
//                        $service_area = trim($service_area);
                        if(isset($checked_service_areas) && in_array($service_area['service_area_name'], $checked_service_areas)){
                            $checked = ' checked ';
                        }
                        else{
                            $checked = '';
                        }
                        echo "<input type='checkbox' name= service_areas[] value=".$service_area['serviceareaID']." $checked> 
                              &nbsp;&nbsp;".$service_area['service_area_name']."<br>";
                        unset($service_areas[$key]);
                        $i++;

                        if($i == $per_column || empty($service_areas)){
                            echo "</div>";
                            if(!empty($service_areas)){
                                $i = 0; 
                                break;
                            }
                            else{
                                $complete = TRUE;
//                                echo "</div>";
                            }
                        }
                    }
                }
            }
        ?> 
</div>
