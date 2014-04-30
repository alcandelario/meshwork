<h1><?php echo lang('index_heading');?></h1>

<h5><?php echo anchor('logout','Logout') ?> </h5>

<p><?php echo lang('index_subheading');?></p>

<div id="infoMessage"><?php echo $message;?></div>

<table cellpadding=5 cellspacing=10>
	<tr>
		<th><?php echo lang('index_fname_th');?></th>
		<th><?php echo lang('index_lname_th');?></th>
		<th><?php echo lang('index_email_th');?></th>
		<th><?php echo lang('index_groups_th');?></th>
		<th><?php echo lang('index_status_th');?></th>
		<?php 
                    if(isset($is_super))
                    {
                        echo "<th>".lang('index_action_th')."</th>";
                    }
                ?>
                
                
	</tr>
	<?php foreach ($users as $user):?>
		<tr>
			<td><?php echo $user->first_name;?></td>
			<td><?php echo $user->last_name;?></td>
			<td><?php echo $user->email;?></td>
			<td>
				<?php 
                                    foreach ($user->groups as $group)
                                    {
                                        if(!isset($is_super))
                                        {
                                            echo $group->name."<br>";
                                        }
                                        else
                                        {
					   echo anchor("edit_group/".$group->id, $group->name)."<br>";
                                        }
                                    }
                               ?>
                       <br/>
                	</td>
			<td>
                            <?php 
                            $groups = $user->groups;
                            $super = FALSE;
                            foreach($groups as $user_group)
                            {
                                if($user_group->id == '3')
                                {
                                    $super = TRUE;
                                }
                            }
                            
                            if(!$super)
                            {   
                                echo ($user->active) ? anchor("deactivate/".$user->id, lang('index_active_link')) : anchor("activate/". $user->id, lang('index_inactive_link'));
                            }
                            else 
                            {
                                echo '';
                            }
                            ?>
                        </td>
			
                        <?php 
                            if(!$super)
                            {
                                echo "<td>".anchor("edit_user/".$user->id, 'Edit')."</td>";
                            }
                            else
                            {
                                echo "<td></td>";
                            }
                        ?>
		</tr>
	<?php endforeach;?>
</table>

<p><?php echo anchor('create_user', lang('index_create_user_link'))?> 
   <?php 
         if(isset($is_super))
         {
             echo " | ".anchor('create_group', lang('index_create_group_link'));
         }
   ?>
</p>