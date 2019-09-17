<div class="year-month_selector">
    <form action="{{ action }}" method="GET">
        <div class="year-month__wrapper">
            <select name="month" id="" onchange="this.form.submit();">
                {% for key, month in months %}
                    <option value="{{ key }}" {{ (key == defaultMonth) ? 'selected' : '' }}>{{ month }}</option>
                {% endfor %}
            </select>
        </div>

        <div class="year-month__wrapper">
            <select name="year" id="" onchange="this.form.submit();">
                {% for key, year in years %}
                    <option value="{{ key }}" {{ (year === defaultYear) ? 'selected' : '' }}>{{ year }}</option>
                {% endfor %}
            </select>
        </div>
    </form>
</div>