<?php

use Phalcon\Mvc\User\Component;

class CustomDateTime extends Component
{
    /**
     * Возвращает массив всех месяцев
     *
     * @return array
     */
    public function getMonths()
    {
        $months = [];

        for ($month = 1; $month <= 12; $month++) {
            $months[date('m', mktime(0,0,0, $month, 1, date('Y')))] = date('F', mktime(0,0,0, $month, 1, date('Y')));
        }

        return $months;
    }

    /**
     * Возвращает массив годов
     *
     * @return array
     */
    public function getYears()
    {
        $years = [];

        for ($i = 10; $i >= 0; $i--) {
            $string = date('Y') . ' -' . $i . ' year';

            $years[date('Y', strtotime($string))] = date('Y', strtotime($string));
        }

        return $years;
    }

    /**
     * Возвращает массив дней заданного месяца
     *
     * @param $month
     * @param $year
     * @param $items
     * @return array
     */
    public function getDates($month, $year, $items)
    {
        $notWorkingDays = [];
        foreach ($items as $notWorkingDay) {
            $notWorkingDays[] = $notWorkingDay->day;
        }

        $individuallyWds = $this->getIndividuallyWdModel()->getByWorkingDay(1);
        $individuallyNotWds = $this->getIndividuallyWdModel()->getByWorkingDay(0);

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $datesMonth = [];

        for ($i = 1; $i <= $daysInMonth; $i++)  {
            $mktime = mktime(0,0,0, $month, $i, $year);

            $notWdForUsers = $this->getIndividuallyWdsForUser($individuallyNotWds, date('Y-m-d', $mktime));
            $wdForUsers = $this->getIndividuallyWdsForUser($individuallyWds, date('Y-m-d', $mktime));

            $datesMonth[$i] = [
                'day'  => date("l", $mktime),
                'date' => date('Y-m-d', $mktime),
                'timestamp' => strtotime(date('Y-m-d', $mktime)),
                'working_day' => [
                    'woDay' => (in_array(date("N", $mktime), [6, 7]) || in_array(date("j", $mktime), $notWorkingDays) ) ? 0 : 1,
                    'forUsers' => (in_array(date("N", $mktime), [6, 7]) || in_array(date("j", $mktime), $notWorkingDays) ) ? $wdForUsers : $notWdForUsers,
                ]
            ];
        }

        return $datesMonth;
    }

    /**
     * Проверяеть рабочий день или не рабочий день
     *
     * @param $currentDate
     * @param $items
     * @return bool
     */
    public function isNotWorkingDay($currentDate, $items)
    {
        $notWorkingDays = [];
        foreach ($items as $notWorkingDay) {
            $notWorkingDays[] = $notWorkingDay->day;
        }

        return (in_array(date("N", strtotime($currentDate)), [6, 7]) || in_array(date("j", strtotime($currentDate)), $notWorkingDays) ) ? true : false;
    }

    /**
     * Возвращает сумму отработанных часов в формате H:s
     *
     * @param $startEnds
     * @return false|string
     */
    public function getTotalDifference($startEnds)
    {
        $totalSecond = 0;

        foreach ($startEnds as $startEnd) {
            if($startEnd->start && $startEnd->start !== 'forgot' && $startEnd->stop !== 'forgot') {
                $stop = $startEnd->stop ?: date('H:i:s');
                $totalSecond = $totalSecond + (strtotime($stop) - strtotime($startEnd->start));
            }
        }

        return date('H:i', $totalSecond + strtotime('00:00:00'));
    }

    /**
     * Возвращает разницу между секундов в формате H:i:s
     *
     * @param $max
     * @param $min
     * @return false|string
     */
    public function getDiffBySecond($max, $min)
    {
        $timestamp = ($max - $min) + strtotime('00:00:00');

        return date('H:i:s', $timestamp);
    }

    /**
     * Возвращает сумму отработтаных часов в секундах
     *
     * @param $hours
     * @param $notWorkingDays
     * @return false|int
     */
    public function getTotalSecondOfHours($hours, $notWorkingDays)
    {
        $totalSecondPerMonth = 0;
        $individuallyNotWds = $this->getIndividuallyWdModel()->getByWorkingDay(0);
        $individuallyWds = $this->getIndividuallyWdModel()->getByWorkingDay(1);

        foreach($hours as $hour) {
            if($hour['total'] && (strtotime($hour['total']) - strtotime("00:00:00")) > 0) {
                $totalSecondPerMonth = $totalSecondPerMonth + (strtotime($hour['total']) - strtotime("00:00:00"));

                $notWdForUsers = $this->getIndividuallyWdsForUser($individuallyNotWds, $hour['createdAt']);
                $wdForUsers = $this->getIndividuallyWdsForUser($individuallyWds, $hour['createdAt']);

                if($this->isNotWorkingDay($hour['createdAt'], $notWorkingDays)) {
                    if(in_array($hour['usersId'], $wdForUsers)) {
                        $totalSecondPerMonth = $totalSecondPerMonth - 3600;
                    }
                } else {
                    if(!in_array($hour['usersId'], $notWdForUsers)) {
                        $totalSecondPerMonth = $totalSecondPerMonth - 3600;
                    }
                }
            }
        }

        return $totalSecondPerMonth;
    }

    /**
     * Превращает час в форматы H:i:s в секун
     *
     * @param $hour
     * @return false|int
     */
    public function parseHour($hour)
    {
        return strtotime($hour) - strtotime('00:00:00');
    }

    public function getArrayOfUsersHoursCreatedAt($users)
    {
        $hoursCreatedAts = [];

        foreach ($users as $user) {
            $hoursCreatedAts[$user->id] = [];

            foreach ($user->hours as $hour) {
                $hoursCreatedAts[$user->id][] = $hour->createdAt;
            }
        }

        return $hoursCreatedAts;
    }

    protected function getIndividuallyWdsForUser($individuallyWds, $date)
    {
        $forUsers = [];

        foreach ($individuallyWds as $individuallyWd) {
            if($individuallyWd->createdAt === $date) {
                $forUsers[] = $individuallyWd->userId;
            }
        }

        return $forUsers;
    }

    protected function getIndividuallyWdModel()
    {
        return new IndividuallyWd();
    }
}
