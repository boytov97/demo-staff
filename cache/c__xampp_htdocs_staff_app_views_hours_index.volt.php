<div class="col-md-12">
    <div class="row">
        <div class="row statistics__wrapper">
            <div class="col-md-6 user_statistics__wrapper">
                <p>Total hours per month: <span class="total_hours_per_month"><?= $totalPerMonth ?></span></p>
                <p>You have/Assigned: <span class="total_hours_per_month"><?= $percentOfTotal ?>%</span></p>
                <p>Assigned: <span class="total_hours_per_month"><?= $workingDaysCount * 8 ?></span></p>
                <span>Ты опаздал: <span class="total_hours_per_month">1 раз</span></span><br>
                <span>Если общее кол-во опозданий превысит в сентябре.</span><br>
                <span>В сентябре будут применятся штрафные санкции.</span>
            </div>

            <div class="col-md-6 lates_statistics__wrapper">
                <p>Главные опоздуны</p>

                <div class="late__card">
                    <div class="late__image__wrapper">
                        <img src="/staff/img/Elon-Musk-2010.jpg" alt="" class="late__image">
                    </div>

                    <br>
                    <p>Elon-Musk</p>
                    <span>4 pаз</span>
                </div>

                <div class="late__card">
                    <div class="late__image__wrapper">
                        <img src="/staff/img/Elon-Musk-2010.jpg" alt="" class="late__image">
                    </div>

                    <br>
                    <p>Elon-Musk</p>
                    <span>4 pаз</span>
                </div>

                <div class="late__card">
                    <div class="late__image__wrapper">
                        <img src="/staff/img/Elon-Musk-2010.jpg" alt="" class="late__image">
                    </div>

                    <br>
                    <p>Elon-Musk</p>
                    <span>4 pаз</span>
                </div>

                <div class="late__card">
                    <div class="late__image__wrapper">
                        <img src="/staff/img/Elon-Musk-2010.jpg" alt="" class="late__image">
                    </div>

                    <br>
                    <p>Elon-Musk</p>
                    <span>4 pаз</span>
                </div>
            </div>
        </div>

        <div class="year-month_selector">
            <form action="<?= $this->url->get(['for' => 'hours-index']) ?>" method="GET">
                <div class="year-month__wrapper">
                    <select name="month" id="" onchange="this.form.submit();">
                        <?php foreach ($months as $key => $month) { ?>
                            <option value="<?= $key ?>" <?= (($key == $defaultMonth) ? 'selected' : '') ?>><?= $month ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="year-month__wrapper">
                    <select name="year" id="" onchange="this.form.submit();">
                        <?php foreach ($years as $key => $year) { ?>
                            <option value="<?= $key ?>" <?= (($year === $defaultYear) ? 'selected' : '') ?>><?= $year ?></option>
                        <?php } ?>
                    </select>
                </div>
            </form>
        </div>

        <div class="table__wrapper">
            <table class="table table-bordered">
             <?php if (isset($users)) { ?>
                  <thead>
                    <tr>
                      <th scope="col" style="width: 200px;">
                        <a href="#" id="hide-show">Hide/Show</a>
                      </th>
                        <?php foreach ($users as $user) { ?>
                             <th scope="col"><?= $user->name ?></th>
                        <?php } ?>
                    </tr>
                  </thead>

                  <tbody class="working_table_list">
                      <?php foreach ($datesMonth as $position => $date) { ?>
                          <tr class="<?= (($currentDate == $date['date']) ? 'current_working_line' : 'full_day not_current_working_line') ?>">
                              <td scope="row">
                                  <center>
                                      <?= $position ?> <br>
                                      <span class="day_of_weeks"><?= $date['day'] ?></span>
                                  </center>
                              </td>

                              <?php foreach ($users as $user) { ?>
                                  <td>
                                      <div class="hours__wrapper">
                                          <input type="checkbox" disabled <?= ($date['working_day'] ? 'checked' : '') ?>>

                                          <?php foreach ($user->hours as $hour) { ?>
                                                <?php if ($hour->createdAt == $date['date']) { ?>
                                                    <?php if ($user->id == $authUser['id'] && $currentDate === $date['date']) { ?>
                                                          <input type="hidden" id="update-hours-link" value="<?= $this->url->get(['for' => 'hours-update-total', 'id' => $hour->id]) ?>">

                                                          <?php foreach ($hour->startEnds as $startEnd) { ?>
                                                              <?php $endStop = ($startEnd->end ? $startEnd->end : '<a href="' . $this->url->get(['for' => 'hours-update', 'id' => $hour->id, 'startEndId' => $startEnd->id]) . '" name="stop" class="update-hours">stop</a>'); ?>

                                                              <center>
                                                                  <span class="start-end_<?= $startEnd->id ?>"><?= ($startEnd->start ? $startEnd->start . ' - ' . $endStop : '<a href="' . $this->url->get(['for' => 'hours-update', 'id' => $hour->id, 'startEndId' => $startEnd->id]) . '" name="start" class="update-hours">start</a>') ?>
                                                                  </span>
                                                              </center>
                                                          <?php } ?>

                                                          <center>
                                                              <span class="total-hour auth-user-total">
                                                                  <?php if (!empty($hour->total)) { ?>
                                                                      total: <?= $hour->total ?>
                                                                  <?php } ?>
                                                              </span>

                                                              <?php if (!empty($hour->less)) { ?>
                                                                  <span class="less-hour">less: <?= $hour->less ?></span>
                                                              <?php } ?>
                                                          </center>
                                                    <?php } elseif ($currentDate !== $date['date']) { ?>
                                                          <?php foreach ($hour->startEnds as $startEnd) { ?>
                                                              <center><?= $startEnd->start ?> - <?= $startEnd->end ?></center>
                                                          <?php } ?>
                                                          <center>
                                                              <?php if (!empty($hour->total)) { ?>
                                                                  <span class="total-hour">total: <?= $hour->total ?></span>
                                                              <?php } ?>

                                                              <?php if (!empty($hour->less)) { ?>
                                                                  <br>
                                                                  <span class="less-hour">less: <?= $hour->less ?></span>
                                                              <?php } ?>
                                                          </center>
                                                    <?php } ?>
                                                 <?php } ?>
                                          <?php } ?>
                                      </div>
                                  </td>
                              <?php } ?>
                          </tr>
                      <?php } ?>
                  </tbody>
                </table>
             <?php } else { ?>
                 <hr>
                 <p>No users</p>
             <?php } ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#hide-show').on('click', function () {
            $('.full_day').each(function () {
                $(this).toggleClass('not_current_working_line');
            });
        });

        var updateTotalInterval;

        function startUpdateInterval() {
            updateTotalInterval = setInterval(updateTotal, 60000);
        }

        function stopUpdateInterval() {
            clearInterval(updateTotalInterval);
        }

        function initializeStartAndStop() {

            $('.update-hours').on('click', function (e) {
                e.preventDefault();

                var element = $(this);
                var updateActions = {};

                if(element.attr('name') === 'start') {
                    updateActions['action'] = 'start';

                    startUpdateInterval();
                }

                if(element.attr('name') === 'stop') {
                    updateActions['action'] = 'stop';

                    stopUpdateInterval();
                }

                updateWorkingHours(updateActions, element);
            });
        }

        function updateWorkingHours(updateActions, element) {

            $.ajax({
                type: 'POST',
                url: element.attr('href'),
                data: updateActions,
                beforeSend: function() {
                    if(element !== null) {
                        element.prop('disabled', true);
                    }
                },
                success: function (data) {
                    var parsedData = $.parseJSON(data);

                    $.each(parsedData.startEnds, function(key, value) {
                        var startEnd = '.start-end_' + value.id;

                        if(value.end === null && value.start !== null) {
                            $(startEnd).find('a').attr('name', 'stop').html('stop');

                            $(startEnd).html(value.start + ' - ' + $(startEnd).html());
                        } else {
                            if(value.end === null && value.start === null && parsedData.urlForNewStartEnd !== null) {
                                $('.new-startEnd').last().append('<span class="start-end_'+ value.id +'"><a href="' + parsedData.urlForNewStartEnd + '" name="start" class="update-hours" >start</a></span>');
                            } else {
                                $(startEnd).html(value.start + ' - ' + value.end);

                                if (parsedData.startEnds.length - 2 === key && parsedData.action === 'stop') {
                                    $(startEnd).parent().after('<center class="new-startEnd"></center>');
                                }
                            }
                        }

                        $('.auth-user-total').html('total: ' + parsedData.total);
                    });

                    initializeStartAndStop();
                },
                error: function (errors) {
                    alert(errors.status + ' ' + errors.statusText);
                }
            });
        }

        function updateTotal() {
            var url = $('#update-hours-link').val();

            $.ajax({
                type: 'POST',
                url: url,
                data: {},
                success: function (data) {
                    function isJson(data) {
                        try {
                            $.parseJSON(data);
                        } catch (e) {
                            return false;
                        }
                        return true;
                    }

                    if (isJson(data)) {
                        var parsedData = $.parseJSON(data);

                        $('.auth-user-total').html('total: ' + parsedData.total);
                    }
                },
                error: function (errors) {
                    alert(errors.status + ' ' + errors.statusText);
                }
            });
        }

        initializeStartAndStop();
        startUpdateInterval();

        <?php if (empty($lastStartTime)) { ?>
            stopUpdateInterval();
        <?php } ?>
    });
</script>
