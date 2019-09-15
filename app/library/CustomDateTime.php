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
                'working_day' => (in_array(date("N", $mktime), []) || in_array(date("j", $mktime), $notWorkingDays) ) ? 0 : 1
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

        return (in_array(date("N", strtotime($currentDate)), []) || in_array(date("j", strtotime($currentDate)), $notWorkingDays) ) ? true : false;
    }

    /**
     * Возвращает разницу между часов в формате H:i:s
     *
     * @param $start
     * @param null $end
     * @return string
     */
    public function getDifference($start, $end = null)
    {
        $strStart = date('Y-m-d') . ' ' . $start;
        $strEnd = $end ? date('Y-m-d') . ' ' . $end : date('Y-m-d H:i:s');

        $dteStart = new DateTime($strStart);
        $dteEnd   = new DateTime($strEnd);

        $dteDiff  = $dteStart->diff($dteEnd);

        return $dteDiff->format("%H:%I:%S");
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
            if($hour['total']) {
                $totalSecondPerMonth = $totalSecondPerMonth + (strtotime($hour['total']) - strtotime("00:00:00")) - 3600;
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
}
