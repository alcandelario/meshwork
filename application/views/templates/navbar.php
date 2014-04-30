<nav class='main_nav clearfix'>
    <ul class='clearfix' id=''>
        <li    id='<?=@$active['0']?>'><span id='home_link'><?php echo anchor(base_url(),   'Home',"title='Meshwork - Home'")?></span></li>
        <li class='<?=@$active['1']?>'><?php echo anchor('calendar' ,  'Events', "title='See all the Events!'"  )  ?></li>
        <li class='<?=@$active['2']?>'><?php echo anchor('asset_map',  'Map', "title='See a map of all the Orgs'"     )     ?></li>
        <li class='<?=@$active['3']?>'><?php echo anchor('users'    ,  'Everyone',"title='Connect to other people'")?></li>
        <li class='<?=@$active['4']?> glow_post glow_post_speed'><?php echo anchor('messages/post','Post',"title='Post a message or create an event'")?></li>
         
    </ul>
</nav>