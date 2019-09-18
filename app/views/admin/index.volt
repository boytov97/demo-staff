{{ content() }}

<div class="page__wrapper">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                {{ partial('common/filter', [
                    'action': url(['for': 'admin-index']),
                    'months': months,
                    'years': years,
                    'defaultMonth': defaultMonth,
                    'defaultYear': defaultYear
                ]) }}

                <div class="table__wrapper">
                    {{ partial('common/staffTable', [
                        'users': users,
                        'datesMonth': datesMonth,
                        'currentDate': currentDate,
                        'authUser': authUser
                    ]) }}
                </div>
            </div>
        </div>
    </div>
</div>