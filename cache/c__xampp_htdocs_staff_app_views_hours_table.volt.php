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
                 <input type="checkbox" disabled checked>

                 <?php if ($user->name === $userName) { ?>
                    <?php foreach ($user->hours as $hour) { ?>
                        <?php if ($hour->createdAt == $date['date']) { ?>
                            <?php $endStop = ($hour->end ? $hour->end : ' - <a href="" name="stop" class="update-hours">stop</a>'); ?>

                            <input type="hidden" value="<?= $this->url->get(['for' => 'hours-update']) ?>" id="update-hours-link">
                            <center><?= ($hour->start ? $hour->start . ' - ' . $endStop : '<a href="" name="start" class="update-hours">start</a>') ?></center>
                            <center>
                                <?php if (!empty($hour->total)) { ?>
                                    <span class="total-hour">total: <?= $hour->total ?></span>
                                <?php } ?>

                                <?php if (!empty($hour->less)) { ?>
                                    <span class="less-hour">less: <?= $hour->less ?></span>
                                <?php } ?>
                            </center>
                        <?php } ?>
                    <?php } ?>
                 <?php } else { ?>
                    <?php if ($currentDate != $date['date']) { ?>
                        <?php foreach ($user->hours as $hour) { ?>
                            <?php if ($hour->createdAt == $date['date']) { ?>
                                 <center><?= $hour->start ?> - <?= $hour->end ?></center>
                                 <center>
                                    <?php if (!empty($hour->total)) { ?>
                                        <span class="total-hour">total: <?= $hour->total ?></span>
                                    <?php } ?>

                                    <?php if (!empty($hour->less)) { ?>
                                        <span class="less-hour">less: <?= $hour->less ?></span>
                                    <?php } ?>
                                 </center>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                 <?php } ?>
             </div>
         </td>
      <?php } ?>
    </tr>
<?php } ?>