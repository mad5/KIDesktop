
<?php $tables = $VARS->get('tables'); 
?>

<form method="post" action="<?php echo(getLink("dbtableconfig/edit_table"));?>" >
    Select Table: 
    <select name="tables">
        
        <?php 
        
        foreach ($tables as $key => $row) { 
                      
            ?>

        <option value="<?php echo ($row); ?>"><?php echo ($row); ?></option>       

        <?php } ?>
    </select>

   <input type="submit" value="OK">
</form> 