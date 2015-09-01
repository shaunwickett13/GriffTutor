<?php require_once('checkLogin.php'); ?>
<?php include 'header.php'; ?>
  
<script>
    
    function getHoursChecked(day) {
        return $.map($('input[name='+day+']:checkbox:checked'), function(e, i) {
            return +e.value;
        });
    }
    
    function updateSchedule() {
        $('*').addClass('working-cursor');
        $.post("../ajax/ajax_updateSchedule.php", {
            monday: getHoursChecked('monday'),
            tuesday: getHoursChecked('tuesday'),
            wednesday: getHoursChecked('wednesday'),
            thursday: getHoursChecked('thursday'),
            friday: getHoursChecked('friday')
        }).done(function (data) {
            $('*').removeClass('working-cursor');
            if (data == 'correct') {
                showMessage('The schedule has been updated!');
            }
        });
    }
    
   $(function () {
        // Update profile
        $('#scheduleForm').submit(function (evt) {
            evt.preventDefault();
            updateSchedule();
        });

    });
    
</script>

<style>
    div label input {
        margin-right:100px;
    }

    #ck-button {
        margin:2px;
        background-color:#EFEFEF;
        border-radius:4px;
        border:1px solid #D0D0D0;
        overflow:auto;
        float:left;
    }
    #ck-button label {
        float:left;
        width:100px;
    }
    #ck-button label span {
        text-align:center;
        padding:3px 0px;
        display:block;
    }
    #ck-button label input {
        position:absolute;
        top:-20px;
    }
    #ck-button input:checked + span {
        background-color: rgba(34, 255, 94, 0.38);
        color:#fff;
    }
</style>

<h2>Update schedule</h2>
<form id="scheduleForm">
    <table>
    <thead>
    <tr>
        <th>Monday</th>
        <th>Tuesday</th>
        <th>Wednesday</th>
        <th>Thursday</th>
        <th>Friday</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $hours = array(
        Schedule::AM_8_00=>'8:00 AM', Schedule::AM_8_30=>'8:30 AM',
        Schedule::AM_9_00=>'9:00 AM',Schedule::AM_9_30=>'9:30 AM',
        Schedule::AM_10_00=>'10:00 AM',Schedule::AM_10_30=>'10:30 AM',
        Schedule::AM_11_00=>'11:00 AM',Schedule::AM_11_30=>'11:30 AM',
        Schedule::PM_12_00=>'12:00 PM',Schedule::PM_12_30=>'12:30 PM',
        Schedule::PM_1_30=>'1:00 PM',Schedule::PM_1_30=>'1:30 PM',
        Schedule::PM_2_00=>'2:00 PM',Schedule::PM_2_30=>'2:30 PM',
        Schedule::PM_3_00=>'3:00 PM',Schedule::PM_3_30=>'3:30 PM',
        Schedule::PM_4_00=>'4:00 PM',Schedule::PM_4_30=>'4:30 PM',
        Schedule::PM_5_00=>'5:00 PM',Schedule::PM_5_30=>'5:30 PM');

    foreach ($hours as $hourCode=>$hourName) {
        echo '<tr>';

        foreach (Schedule::$DAYS as $d) {

            echo '
                    <td>
                        <div id="ck-button" >
                            <label>
                                <input type = "checkbox" name = "'.$d.'" value = "'.$hourCode.'" '.($USER->getSchedule()->hourExists($d,$hourCode)? 'checked' :'').'><span>'.$hourName.'</span >
                            </label >
                        </div >
                    </td>';
        }
        echo '</tr>';
    }

    ?>
    <tr><td colspan="5"><input type="submit" value="Update schedule"/></td></tr>
    </tbody>
    </table>

</form>

<?php include 'footer.php'; ?>
