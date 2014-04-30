<div class='user_select_container clearfix'>
<?php 
    if(isset($users_select) && count($users_select) > 0)
    {
        $select = array();
        echo '<h5>Email this event to others?</h5>';
        foreach($users_select as $org_name => $users)
        {
            foreach($users as $user)
            {
                foreach($user as $email => $name)
                {
                    $array = array($email.",".$name => $name);
                }
                    $select = $select + $array; 
            }
        }
        
        foreach($select as $value => $name)
        {
            echo "&nbsp;&nbsp;<span class='pull-right event_attendee'>".$name."</span><input class='pull-right' type='checkbox' name='attendees[]' value='".$value."'><br>";
        }
        
      //  echo form_multiselect('attendees[]',$select,'');
    }
?>
</div>