<?php foreach ($datesMonth as $position => $date) { ?>
    <tr class="<?= (($currentDate == $date['date']) ? 'current_working_line' : 'full_day not_current_working_line') ?>">
      <td scope="row"><?= $position ?>
           <span class="day_of_weeks"><?= $date['day'] ?></span>
      </td>

      <?php foreach ($users as $user) { ?>
         <td>
             <?php if ($user->name === $userName) { ?>
                <?php foreach ($user->hours as $hour) { ?>
                    <?php if ($hour->createdAt == $date['date']) { ?>
                        <?php $endStop = ($hour->end ? $hour->end : ' - <a href="/staff/hours/update" id="stop-time">stop</a>'); ?>
                        <?= ($hour->start ? $hour->start . ' - ' . $endStop : '<a href="/staff/hours/update" id="start-time">start</a>') ?>
                    <?php } ?>
                <?php } ?>
             <?php } else { ?>
                <?php if ($currentDate != $date['date']) { ?>
                    <?php foreach ($user->hours as $hour) { ?>
                        <?php if ($hour->createdAt == $date['date']) { ?>
                            <?= $hour->start ?> - <?= $hour->end ?>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
             <?php } ?>
         </td>
      <?php } ?>
    </tr>
<?php } ?>