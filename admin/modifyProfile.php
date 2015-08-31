<?php require_once( 'checkLogin.php'); ?>
<?php include 'header.php'; ?>

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


<div class="modify-profile">
    <h2>Modify profile</h2>
    <form id="updateProfileForm">
        <table>
            <tr>
                <td>ID:</td>
                <td>
                    <input id="userID" type="text" value="<?php echo$USER->getID();?>" disabled/>
                </td>
            </tr>
            <tr>
                <td>Name:</td>
                <td>
                    <span id="error-name" class="error-message">Enter a valid name</span>
                    <input type="text" id="name" name="name" value="<?php echo $USER->getName();?>" />
                </td>
            </tr>
            <tr>
                <td>Phone:</td>
                <td>
                    <span id="error-phone" class="error-message">Enter a valid phone, without parenthesis</span>
                    <input type="tel" id="phone" name="phone" value="<?php echo $USER->getPhone();?>" />
                </td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>
                    <span id="error-email" class="error-message">Enter a valid email</span>
                    <input type="text" id="email" name="email" value="<?php echo $USER->getEmail();?>" />
                </td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Bio:</td>
                <td>
                    <textarea id="bio" name="bio" placeholder="Biography"><?php echo $USER->getBio();?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="Update profile" />
                </td>
            </tr>
        </table>
    </form>
</div>

<div class="modify-profile">
    <h2>Update PIN</h2>
    <form id="updatePINForm">
        <table>
            <tr>
                <td>Old PIN:</td>
                <td>
                    <input type="password" id="oldPIN" name="oldPIN" placeholder="Old PIN" maxlength="4" />
                </td>
            </tr>
            <tr>
                <td>New PIN:</td>
                <td>
                    <span id="error-pin" class="error-message">Enter a valid 4-digit PIN</span>
                    <input type="password" id="newPIN1" name="newPIN1" placeholder="New PIN" maxlength="4" />
                </td>
            </tr>
            <tr>
                <td>Repeat new:</td>
                <td>
                    <input type="password" id="newPIN2" name="newPIN2" placeholder="Repeat new" maxlength="4" />
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <input type="submit" value="Update password" />
                </td>
            </tr>
        </table>
    </form>
</div>

<!-- CLEAN COLUMNS -->
<div class="clear"></div>


<?php include 'footer.php'; ?>
