<div style=''> 
        <?php
            if(isset($groups) && count($groups) > 0){
                echo "<h5>Select all that apply</h5>";
                             
                $numPerRow = 3;
                 $complete = FALSE; 
                        $i = 0;
                echo "<div class='clearfix'>";        
                while(!$complete){
                    echo "<div style='float:left; margin-bottom:0px;'>";
                    foreach($groups as $key => $group){
                          $id = $group->id;
                        $name = $group->description;
                        
                        if(isset($checked_groups) && in_array($name,$checked_groups)){
                            $checked = ' checked ';
                        }
                        else {
                            $checked = '';
                        }
                        
                        if(stripos($name,'Administrator') !== FALSE)
                        {
                            $name = $name." (Usually given to a provider's main contact only)";
                        }
                       
                        echo "<input type='checkbox' name= groups[] value=".$key." $checked> 
                              &nbsp;&nbsp;".$name."&nbsp;&nbsp;<br>";
                        unset($groups[$key]);
                        $i++;

                        if($i == $numPerRow || empty($groups)){
                            echo "</div>";
                            if(!empty($groups)){
                                $i = 0; 
                                break;
                            }
                            else{
                                $complete = TRUE;
                            }
                        }
                    }
                }
                echo "</div>";
            }
        ?>
</div>
