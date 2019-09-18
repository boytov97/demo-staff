$(document).ready(function () {
    $('#hide-show').on('click', function () {
        $('.full_day').each(function () {
            $(this).toggleClass('not_current_working_line');
        });
    });

    $('.edit__name').on('click', function (event) {
        event.preventDefault();

        $('#nameInput').prop('disabled', false).focus();
    });

    $('.user_is_late').parent().parent().css('background-color', '#ffb9b2');
    $('.auth_user_is_late').parent().parent().css('background-color', '#ffb9b2');
});