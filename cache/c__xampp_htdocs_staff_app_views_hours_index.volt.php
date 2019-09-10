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
                  <tbody class="working_table_list">
                        <?php $this->partial('hours/table', ['datesMonth' => $datesMonth, 'users' => $users, 'currentDate' => $currentDate, 'userName' => $userName]); ?>
                  </tbody>
                </table>
             <?php } else { ?>
                 <hr>
                 <p>No users</p>
             <?php } ?>
        </div>
    </div>
</div>