<?php
$structure = $VARS->get('structure');
$data = $VARS->get('data');
//vd($data);
//vd($structure);
$counter = 0;
if (($data != "false")) {
    $structure = $data;
}
?>

<form method="post" action="<?php echo(getLink("dbtableconfig/save_table")); ?>" >
    <input type="submit" value="Save">
    <input type="hidden" value="<?php echo($VARS->get('filename')) ?>" name="file">

    <table border="0">
        <thead>
            <tr>
                <th style="width: 30px">ACTIVE</th>
                <th style="width: 30px">SORT</th>
                <th style="width: 30px">LIST</th>
                <th style="width: 30px">VIEW</th>
                <th style="width: 30px">EDIT</th>
                <th style="width: 30px">UP</th>
                <th style="width: 30px">DOWN</th>
                <th style="width: 100px">Column</th>
                <th style="width: 150px">Titel</th>
                <th style="width: 100px">Type</th>
                <th style="width: 100px">Class</th>
                <th style="width: 100px">Type for HTML</th>
                <th style="width: 100px">-</th>
                <th style="width: 100px">-</th>
            </tr>
        </thead>
        <tbody>


            <?php foreach ($structure as $key => $row) { ?>

                <tr style="width: 100%;">



                    <?php if ($row->get("Check") == "active") { ?>

                        <td><input type="checkbox" value="active" checked="checked" name="Check[]"></td>
                    <?php } else { ?>
                        <td><input type="checkbox" value="active"  name="Check[]"></td>
                    <?php } ?>

                    <td> <input type="radio" name="Sort[]" value="<?php echo($row->get("Field")); ?>"> </td> 


                    <?php if ($row->get("List") == "active") { ?>

                        <td><input type="checkbox" value="active" checked="checked" name="List[]"></td>
                    <?php } else { ?>
                        <td><input type="checkbox" value="active"  name="List[]"></td>
                    <?php } ?>

                    <?php if ($row->get("View") == "active") { ?>

                        <td><input type="checkbox" value="active" checked="checked" name="View[]"></td>
                    <?php } else { ?>
                        <td><input type="checkbox" value="active"  name="View[]"></td>
                    <?php } ?>

                    <?php if ($row->get("Edit") == "active") { ?>

                        <td><input type="checkbox" value="active" checked="checked" name="Edit[]"></td>
                    <?php } else { ?>
                        <td><input type="checkbox" value="active"  name="Edit[]"></td>
                    <?php } ?>



                    <td><input type="button" class="mybup" value="UP"  onclick="" name=""></td>
                    <td><input type="button" class="mybdown" value="DOWN" onclick="" name=""></td>

                    <td><?php echo($row->get("Field")); ?>

                    </td>

                    <td>
                        <input type="text" value="<?php echo($row->get("Titel")); ?>" name="Titel[<?php $row->get("Field"); ?>]">
                    </td>

                    <td><?php echo($row->get("Type")); ?></td>

                    <td>
                        <input type="text" value="<?php echo($row->get("Class")); ?>" name="Class[<?php $row->get("Class"); ?>]">
                    </td>

                    <td>
                        <?php
                        $selectedOption = $row->get("Type");


                        $options = array("", "", "", "", "", "");

                        if ($row->get("Selector") == "Text") {
                            $options[0] = "selected='selected'";
                        }
                        if ($row->get("Selector") == "TextBox") {
                            $options[1] = "selected='selected'";
                        }
                        if ($row->get("Selector") == "SelectBox") {
                            $options[2] = "selected='selected'";
                        }
                        if ($row->get("Selector") == "CheckBox") {
                            $options[3] = "selected='selected'";
                        }
                        if ($row->get("Selector") == "Password") {
                            $options[4] = "selected='selected'";
                        }
                        if ($row->get("Selector") == "Date") {
                            $options[5] = "selected='selected'";
                        }
                        if ($row->get("Selector") == "Time") {
                            $options[6] = "selected='selected'";
                        }
                        if ($row->get("Selector") == "File") {
                            $options[7] = "selected='selected'";
                        }
                        if ($row->get("Selector") == "Image") {
                            $options[8] = "selected='selected'";
                        }
                        if ($row->get("Selector") == "HTML") {
                            $options[9] = "selected='selected'";
                        }
                        if ($row->get("Selector") == "Callback") {
                            $options[10] = "selected='selected'";
                        }
                        if ($row->get("Selector") == "Hidden") {
                            $options[11] = "selected='selected'";
                        }
                        ?>
                        <select name="Selector[]" class="myselector">
                            <option value ="Text" <?php echo $options[0]; ?>>Text</option>
                            <option value ="TextBox" <?php echo $options[1]; ?>>TextBox</option>
                            <option value ="SelectBox" <?php echo $options[2]; ?>>SelectBox</option>
                            <option value ="CheckBox" <?php echo $options[3]; ?>>CheckBox</option>
                            <option value ="Password" <?php echo $options[4]; ?>>Password</option>
                            <option value ="Date" <?php echo $options[5]; ?>>Date</option>
                            <option value ="Time" <?php echo $options[6]; ?>>Time</option>
                            <option value ="File" <?php echo $options[7]; ?>>File</option>
                            <option value ="Image" <?php echo $options[8]; ?>>Image</option>
                            <option value ="HTML" <?php echo $options[9]; ?>>HTML</option>
                            <option value ="Callback" <?php echo $options[10]; ?>>Callback</option>  
                            <option value ="Hidden" <?php echo $options[11]; ?>>Hidden</option>  

                        </select>
                    </td>

                    <td class="in1"><div style="display: none;"><input class="in1i" type="text" value="<?php echo($row->get("In1")); ?>" name="In1[]"></div>
                        <div style="display: none;"><input class="in2i" type="text" value="<?php echo($row->get("In2")); ?>" name="In2[]">
                        </div></td>
                </tr>

            <?php } ?>

        </tbody>
    </table>
</form>

<script>

    $(document).ready(function() {

        $('.in1i').each(function() {
            if ($(this).val() !== "") {
                $(this).closest('tr').find('.in1').find('div').show();
            }
        });




        $('.mybup').click(function() {

            var row = $(this).closest('tr');

            var rowprev = $(this).closest('tr').prev();

            rowprev.before(row).fadeOut(200);
            row.fadeOut(200);
            rowprev.fadeOut(200);

            rowprev.before(row).fadeIn(400);
            row.fadeIn(400);
            rowprev.fadeIn(400);


        });

        $('.mybdown').click(function() {
            var row = $(this).closest('tr');
            var rownext = $(this).closest('tr').next();

            rownext.after(row).fadeOut(200);
            row.fadeOut(200);
            rownext.fadeOut(200);

            rownext.after(row).fadeIn(200);
            row.fadeIn(200);
            rownext.fadeIn(200);


        });





        $('.myselector').bind('change', function() {
            console.log($(this).val());

            if ($(this).val() === "SelectBox") {



                $(this).closest('tr').find('.in1').find('div').show();

            }


            else if ($(this).val() === "CheckBox") {
                $(this).closest('tr').find('.in1').find('div').show();



            } else {

                $(this).closest('tr').find('.in1').find('div').hide();

            }


        });

    });

</script>