<?php if(count($issues) > 0) :?>
<table style="border: 1px solid black;">
    <tr><th>Summary</th><th>Cause Theory</th></tr>
    <?php 
        foreach ($issues as $issue){
            echo "<tr><td>".$issue['description'];
            echo "&nbsp;&nbsp;<span class='edit_link'>".anchor("issues/update/".$issue['issueID'],"Edit")."</span>";
            echo "</td>";
            echo "<td>".$issue['rootcausetheory']."</td></tr>";
        }
    ;?>
</table>
<?php endif ?>

        
