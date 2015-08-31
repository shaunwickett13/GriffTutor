<?php require_once( 'checkLogin.php'); restrictSupervisor($USER); ?>

<?php include 'header.php'; ?>

    <script src="validators.js"></script>
    <script>
        /*function updateProfile() {
            $('*').addClass('working-cursor');
            $.post("../ajax/ajax_updateProfile.php", {
                name: $('#name').val(),
                email: $('#email').val(),
                phone: $('#phone').val(),
                bio: $('#bio').val()
            }).done(function (data) {

                $('*').removeClass('working-cursor');
                $('#welcome-username').html($('#name').val());
                showMessage('The profile has been updated!');
            });
        }*/

        function createTutor() {
            $('*').addClass('working-cursor');
            $.post("../ajax/ajax_addTutor.php", {
                ID: $('#ID').val(),
                PIN: $('#PIN').val(),
                name: $('#name').val(),
                email: $('#email').val(),
                phone: $('#phone').val()
            }).done(function (data) {

                $('*').removeClass('working-cursor');
                if (data == 'userAlreadyExists') {
                    showErrorMessage('A registered user with the ID '+$('#ID').val()+ ' already exists');
                }
                else {
                    var id = $('#ID').val();
                    var name = $('#name').val();
                    $('#tutorSelect').append('<option value="'+ id +'">'+ name +'</option>' );
                    showMessage('The tutor has been added!');
                    $('#ID').val('');
                    $('#PIN').val('');
                    $('#name').val('');
                    $('#email').val('');
                    $('#phone').val('');
                }
            });
        }

        function isNameValid() {
            if (!validateName($('#name').val())) {
                showError('error-name');
                return false;
            }
            hideError('error-name');
            return true;
        }

        function isEmailValid() {
            if (!validateEmail($('#email').val())) {
                showError('error-email');
                return false;
            }
            hideError('error-email');
            return true;
        }

        function isPhoneValid() {
            if (!validatePhone($('#phone').val())) {
                showError('error-phone');
                return false;
            }
            hideError('error-phone');
            return true;
        }

        function isPINValid() {
            if (!validatePIN($('#PIN').val())) {
                showError('error-pin');
                return false;
            }
            hideError('error-pin');
            return true;
        }


        $(function () {

            $("#confirmRemoveDiv").hide();

            // Update profile
            $('#registerTutorForm').submit(function (evt) {
                evt.preventDefault();
                if (isNameValid()  &&  isEmailValid() && isPINValid() && ($('#phone').val()==''  ||  isPhoneValid())){
                    createTutor();
		}
            });

            $("#removeButton").click( function () {
                var value = $("#tutorSelect").val();

                if (value == null) {
                    showErrorMessage("Please select a tutor to remove.");
                }
                else {
                    $("#confirmRemoveDiv").fadeIn(1000);    
                }
                
            });


            $("#confirm").click( function () {
                
                var tutorName = $("#tutorSelect option:selected").text();
                var ID = $("#tutorSelect").val();

                $("#confirmRemoveDiv").fadeOut();
                setTimeout(function() {
                    $("#tutorSelect option:selected").remove();
                    $("#tutorSelect").prop('selectedIndex', 0);
                    
                    $.post("../ajax/ajax_removeTutor.php", {
                        "tutorID": ID
                    }).done( function(data) {
                        if (data == 'success') {
                            showMessage(tutorName + " has been removed.");
                        }
                    });
                }, 400);
            });

            $("#deny").click( function () {
                $("#confirmRemoveDiv").fadeOut();
                setTimeout(function() {
                    $("#tutorSelect").prop('selectedIndex', 0);
                }, 400);
                
            });


        });
    </script>

<style>
    input {
        margin: 3px 0;
    }
</style>

<div class="manageTutors">
    <h2>Register new tutor</h2>
    <form id="registerTutorForm">
        <input type="hidden" name="action" value="registerTutor"/><br/>
        <input type="text" id="ID" placeholder="ID" size="10"/>*<br/>
	<span id="error-pin" class="error-message">Enter a valid PIN</span>
        <input type="password" id="PIN" placeholder="PIN" size="5" maxlength="4"/>*<br/>
        <span id="error-name" class="error-message">Enter a valid name</span>
        <input type="text" id="name" placeholder="name"/>*<br/>
        <span id="error-email" class="error-message">Enter a valid email</span>
        <input type="email" id="email" placeholder="email"/>*<br/>
        <span id="error-phone" class="error-message">Enter a valid phone, without parenthesis</span>
        <input type="tel" id="phone"  placeholder="phone" maxlength="10" size="11"/><br/>
        <input type="submit" value="Register"/>
    </form>
    <p style="margin-top:20px">* Mandatory fields</p>
</div>

<div class="manageTutors">
    <?php
        $userManager = new UserManager();

        $tutorList = $userManager -> getTutors();

        if (is_null($tutorList)) {
            $isTutorsToRemove = false;
        }
        else {
            $isTutorsToRemove = true;
            $arrayIDs = array();
            $arrayNames = array();
            foreach ($tutorList as $tutor) {
                $arrayIDs[] = $tutor -> getID();
                $arrayNames[] = $tutor -> getName();
            }
        }
    ?>


    <h2>Remove a tutor</h2>
    <div id="removeTutorDiv">

        <span id="selectTutor" class="styled-select">
        <select id="tutorSelect">
            <option value="none" disabled selected>Select a tutor</option>
            <?php
                $numTutors = count($arrayIDs);
                for ($i = 0; $i < $numTutors; $i++) {
                    $id = $arrayIDs[$i];
                    $name = $arrayNames[$i];

                    echo '<option value="'. $id .'">'. $name .'</option>';
                }
            ?>
        </select>
        </span>
        <button id="removeButton">Remove</button>
        
    </div>
    <div id="confirmRemoveDiv">
        <div id="confirmRemoveText">
            All records and documents associated with this tutor will be removed from the system.
            This action can not be undone.
            Are you sure you would like to remove this tutor?
        </div>
        <div id="confirmRemoveButtons">
            <button id="confirm">Yes</button>
            <button id="deny">No</button>
        </div>
    </div>
</div>

<div class="clear"></div>

<?php include 'footer.php'; ?>
