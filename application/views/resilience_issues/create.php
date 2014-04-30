<h4>Log a new issue</h4>
<h6>These are the items the Heatmaps can be filtered by<br>and the same items to be selected from<br>when creating a new entry in the incident log</h6>
<?php
    $this->load->helper('form');
    $this->load->library('form_validation');
     echo validation_errors(); 
?>

<?php echo form_open('issues/create') ?>
        <input style="width: 400px;" type="input" name="issue" maxlength="30" placeholder="BRIEF summary (e.g. Diabetes, Foreclosure, Abandoned Lot, etc" /><br>
        <textarea rows='10' style="width:400px;" name="rootcause" placeholder="Root Cause Theory"></textarea><br>    
        <input type="submit" name="submit" value="Create" /> 

</form>
