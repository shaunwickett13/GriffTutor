<?php require_once( 'checkLogin.php'); ?>
<?php include 'header.php'; ?>
<?php $isSupervisor = $USER instanceof Supervisor; ?>

    <script src="validators.js"></script>
<script>
    function updateProfile() {
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
    }

    function updatePIN() {
        $('*').addClass('working-cursor');
        $.post("../ajax/ajax_updatePIN.php", {
            oldPIN: $('#oldPIN').val(),
            newPIN1: $('#newPIN1').val(),
            newPIN2: $('#newPIN2').val()
        }).done(function (data) {
            $('*').removeClass('working-cursor');
            if (data == 'wrong')
                showErrorMessage('The PIN entered is incorrect!');
            else if (data == 'different')
                showErrorMessage('The new PINs are different!');
            else if (data == 'correct') {
                showMessage('The PIN has been updated!');
                $('#oldPIN').val('');
                $('#newPIN1').val('');
                $('#newPIN2').val('');
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
        if (!validatePIN($('#newPIN1').val())) {
            showError('error-pin');
            return false;
        }
        hideError('error-pin');
        return true;
    }

    $(function () {
        // Update profile
        $('#updateProfileForm').submit(function (evt) {
            evt.preventDefault();
            if (isNameValid() && isPhoneValid() && isEmailValid())
                updateProfile();
        });

        // Update profile
        $('#updatePINForm').submit(function (evt) {
            evt.preventDefault();
            if (isPINValid())
                updatePIN();
        });

    });
</script>

<?php
if ($isSupervisor) {
    include 'modifyProfileSupervisor.php';
}
else {
    include 'modifyProfileTutor.php';
}
?>

<!-- CLEAN COLUMNS -->
<div class="clear"></div>


<?php include 'footer.php'; ?>
