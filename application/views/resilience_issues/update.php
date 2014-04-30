<h4>Edit issue</h4>
<?php
    $this->load->helper('form');
    $this->load->library('form_validation');
     echo validation_errors(); 
?>

<?php echo form_open('issues/update/'.$issues['issueID']) ?>
        <input style="width: 400px;" type="input" name="issue" maxlength="30" value="<?=$issues['description']?>" /><br>
        <textarea rows='10' style="width:400px;" name="rootcause" placeholder="Root Cause Theory"><?=$issues['rootcausetheory']?> </textarea><br>    
        <input type="submit" name="submit" value="Update" /> 

</form>
