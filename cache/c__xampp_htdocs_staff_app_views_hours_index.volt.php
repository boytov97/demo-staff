<?= $this->getContent() ?>

<div class="col-md-12">
    <div class="row">
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
                  <tbody>
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
                                            <?= ($hour->start ? $hour->start : '<a href="">start</a>') ?>
                                             - <?= ($hour->end ? $hour->end : '<a href="">stop</a>') ?>
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
                  </tbody>
                </table>
             <?php } else { ?>
                 <hr>
                 <p>No users</p>
             <?php } ?>
        </div>
    </div>
</div>