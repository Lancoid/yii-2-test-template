$(function () {
    setPhoneMask();
});

/**
 * Applies phone input mask to the registration form.
 */
function setPhoneMask() {
    const $phoneInput = $('input[name="UserRegistrationForm[phone]"]');
    if ($phoneInput.length && $.fn.mask) {
        $phoneInput.mask('+7 (999) 999-99-99', { autoclear: false });
    }
}