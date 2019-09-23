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

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        $datesMonth = [];

        for ($i = 1; $i <= $daysInMonth; $i++)  {
            $mktime = mktime(0,0,0, $month, $i, $year);

            $datesMonth[$i] = [
                'day'  => date("l", $mktime),
                'date' => date('Y-m-d', $mktime),
                'timestamp' => strtotime(date('Y-m-d', $mktime)),
                'working_day' => (in_array(date("N", $mktime), [6, 7]) || in_array(date("j", $mktime), $notWorkingDays) ) ? 0 : 1
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
     * @return false|int
     */
    public function getTotalSecondOfHours($hours)
    {
        $totalSecondPerMonth = 0;

        foreach($hours as $hour) {
            if($hour['total'] && (strtotime($hour['total']) - strtotime("00:00:00")) > 0) {
                $totalSecondPerMonth = $totalSecondPerMonth + (strtotime($hour['total']) - strtotime("00:00:00") - 3600);
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
}
