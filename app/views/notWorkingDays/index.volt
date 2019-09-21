{{ content() }}

<div class="page__wrapper">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                <div class="middle_wrapper_block">
                    {{ partial('common/sessionMessages', [
                        'successMessages': successMessages,
                        'errorMessages': errorMessages
                    ]) }}

                    {{ partial('admin/common/listCreateLinks', [
                        'listUrl': url(['for': 'not-working-days']),
                        'createUrl': url(['for': 'not-working-day-create'])
                    ]) }}

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">day</th>
                            <th scope="col">Month</th>
                            <th scope="col">Repeat</th>
                            <th scope="col">Created at</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        {% for position, notWorkingDay in notWorkingDays %}
                            <tr>
                                <td>{{ position }}</td>
                                <td>{{ notWorkingDay.day }}</td>
                                <td>{{ notWorkingDay.month }}</td>
                                <td>{{ notWorkingDay.repeat }}</td>
                                <td>{{ notWorkingDay.createdAt }}</td>
                                <td class="action__column">
                                    <div class="action__wrapper">
                                        <a href="{{ url(['for': 'not-working-day-delete', 'id': notWorkingDay.id]) }}"
                                           class="input-group-addon btn delete__icon_link" title="delete">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        {% endfor %}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>