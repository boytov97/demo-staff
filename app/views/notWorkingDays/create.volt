{{ content() }}

<div class="page__wrapper">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="middle_wrapper_block">
                    {{ partial('admin/common/listCreateLinks', [
                        'listUrl': url(['for': 'not-working-days']),
                        'createUrl': url(['for': 'not-working-day-create'])
                    ]) }}

                    <form action="{{ url(['for': 'not-working-day-create']) }}" method="POST">
                        <div class="form-group">
                            <label for="monthsSelect">Months</label>
                            <select name="month" id="" class="form-control months_select"
                                    id="monthsSelect">
                                {% for key, month in months %}
                                    <option value="{{ key }}" {{ (key == defaultMonth) ? 'selected' : '' }}>{{ month }}</option>
                                {% endfor %}
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="daysSelect">Days</label>
                            <select name="day" class="form-control days_select" id="daysSelect"></select>
                        </div>

                        <div class="form-group form-check">
                            <input type="checkbox" name="repeat" class="form-check-input" id="exampleCheck1" value="Y">
                            <label class="form-check-label" for="exampleCheck1">Repeat</label>
                        </div>

                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var today = new Date();

    function daysInMonth (month) {
        return new Date(today.getFullYear(), month, 0).getDate();
    }

    function setMonthDays(days) {
        var options = '';
        var arrayOfDays = [];

        for (var day = 1; day <= days; day++) {

            var stringDay = day.toString();

            if(stringDay.length === 1) {
                arrayOfDays[Number.parseInt('0' + stringDay)] = '0' + stringDay;
            } else {
                arrayOfDays[stringDay] = stringDay;
            }
        }

        $.each(arrayOfDays, function (key, value) {
            if (value !== undefined) {
                if(today.getDate() === value) {
                    selected = 'selected';
                }

                options += '<option value="' +  value + '">' + value + '</option>';
            }
        });

        $('.days_select').html(options);
    }

    $('.months_select').change(function() {
        setMonthDays(daysInMonth($(this).val()));
    });

    setMonthDays(daysInMonth($('.months_select').val()));
</script>