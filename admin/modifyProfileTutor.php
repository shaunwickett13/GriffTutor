 <div class="modify-profile">
        <h2>Modify Profile</h2>
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