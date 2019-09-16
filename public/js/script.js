$(document).ready(function () {
    $('.edit__name').on('click', function (event) {
        event.preventDefault();

        $('#nameInput').prop('disabled', false).focus();
    });
});