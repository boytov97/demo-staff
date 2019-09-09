
var fullDays = document.querySelectorAll('.full_day');

document.getElementById('hide-show').onclick = function () {

    fullDays.forEach(function(element) {
        element.classList.toggle('not_current_working_line');
    });
};

