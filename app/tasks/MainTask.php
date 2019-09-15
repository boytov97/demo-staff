<?php

use Phalcon\Cli\Task;

class MainTask extends Task
{
    public function mainAction()
    {
        $hours = Hours::find([
            'conditions' => 'createdAt = :createdAt:',
            'bind' => [
                'createdAt' => date('Y-m-d')
            ]
        ]);

        foreach ($hours as $hour) {

            foreach ($hour->startEnds as $startEnd) {
                if(!$startEnd->start && !$startEnd->stop) {
                    $emptyStartEnd = StartEnd::findFirstById($startEnd->id);
                    $emptyStartEnd->delete();
                }

                if($startEnd->start && !$startEnd->stop) {
                    $forgotten = StartEnd::findFirstById($startEnd->id);
                    $forgotten->assign([
                        'stop' => 'forgot'
                    ]);

                    $forgotten->save();

                    $forgottenHour = Hours::findFirstById($hour->id);
                    $forgottenHour->assign([
                        'total' => '00:00:00',
                        'less'  => '09:00:00'
                    ]);

                    $forgottenHour->save();
                }
            }
        }
    }
}