<?php

class TestingController extends ControllerBase
{

    public function indexAction()
    {
        $lateUsers = $this->modelsManager->createBuilder()
            ->from('Users')
            ->columns([
            'Users.id',
            'Users.name',
            'Users.image',
            'SUM(uh.late) AS beenLate',
            ])
            ->join('Hours', 'uh.usersId = Users.id', 'uh')
            ->where('uh.late = 1')
            ->groupBy('Users.id')->orderBy('SUM(late) DESC')->limit(3)->getQuery()
            ->execute();


        $post = $this->modelsManager->createBuilder()
            ->from('Punch')
            ->columns(['Punch.user_id','Punch.punch_date','Punch.start_time','Punch.stop_time','Punch.full_hours', 'Users.name', 'Users.active'])
            ->where('MONTH(punch_date) = "'.$monthget.'"')
            ->andWhere('YEAR(punch_date) = "'.$yearget.'"')
            ->andWhere('Users.active = "Y"')
            ->join('Users', 'Punch.user_id = Users.id')
            ->orderBy(array('Punch.punch_date', 'Punch.user_id', 'Punch.start_time'))
            ->getQuery()
            ->execute();

        echo '<pre>';
        print_r( $lateUsers );
        echo '</pre>';
    }
}

