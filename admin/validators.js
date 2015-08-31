function validateName(name) {
    return /^([a-zA-Z] ?)+$/.test(name);
}

function validateEmail(email) {
    /*
     * JS (jQuery) validate Email in IDN (Internationalized domain names)
     * http://stackoverflow.com/a/15406614/4699902
     */
    return /^((([a-zA-Z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-zA-Z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-zA-Z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-zA-Z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-zA-Z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-zA-Z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-zA-Z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-zA-Z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-zA-Z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-zA-Z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/.test(email);
}

function validatePhone(phone) {
    return /^(\d ?){10}$/.test(phone);
}

function validatePIN(PIN) {
    return /^(\d){4}$/.test(PIN);
}

function validateHoursList(hours) {
    return /^(([01]\d|2[0-3])(00|15|30|45)-([01]\d|2[0-3])(00|15|30|45) ?, ?)*(([01]\d|2[0-3])(00|15|30|45)-([01]\d|2[0-3])(00|15|30|45))$/.test(hours);
}

function validateDate(date) {
    return /^(0\d|1[0-2])\/(0[1-9]|[1-2]\d|3[0-1])\/(20)\d\d$/.test(date);
}