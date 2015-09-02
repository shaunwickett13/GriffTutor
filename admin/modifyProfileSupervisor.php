
<div class="modify-profile">
    <h2>Modify user's profiles</h2>
    <!-- TODO make dropdown to select and add ability to modify user's profiles -->
</div>

<div class="modify-profile">
    <h2>Update your PIN</h2>
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