$(document).ready(function(){
    setPhoneMask();
});

function setPhoneMask() {
    let selector = $('input[name="UserRegistrationForm[phone]"]');

    selector.mask('+7 (999) 999-99-99',{autoclear: false});
}