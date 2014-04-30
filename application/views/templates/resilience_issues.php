<div class='resilience_issues_container clearfix'> 
        <?php
            if(isset($specialties) && count($specialties) > 0){
                echo "<h5>Specialties</h5>";
              
                $per_column = 10;
                 $complete = FALSE; 
                        $i = 0;
//                echo "<div class='clearfix'>";        
                while(!$complete){
                    echo "<div class='pull-left'>";
                    foreach($specialties as $key => $specialty){
//                        $specialty = trim($specialty);
                        if(isset($checked_issues) && in_array($specialty,$checked_issues)){
                            $checked = ' checked ';
                        }
                        else {
                            $checked = '';
                        }
                        echo "<input type='checkbox' name= specialties[] value=".$key." $checked> 
                              &nbsp;&nbsp;".$specialty."&nbsp;&nbsp;<br>";
                        unset($specialties[$key]);
                        $i++;

                        if($i == $per_column || empty($specialties)){
                            echo "</div>";
                            if(!empty($specialties)){
                                $i = 0; 
                                break;
                            }
                            else{
                                $complete = TRUE;
                            }
                        }
                    }
                }
//                echo "</div>";
            }
        ?>
</div>
