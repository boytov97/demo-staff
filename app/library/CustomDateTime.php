<?php

use Phalcon\Mvc\User\Component;

class CustomDateTime extends Component
{
    /**
     *
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
     *
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
     *
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
                'working_day' => (in_array(date("N", $mktime), [7]) || in_array(date("j", $mktime), $notWorkingDays) ) ? 0 : 1
            ];
        }

        return $datesMonth;
    }

    /**
     *
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

        return (in_array(date("N", strtotime($currentDate)), [7]) || in_array(date("j", strtotime($currentDate)), $notWorkingDays) ) ? true : false;
    }

    /**
     *
     *
     * @param $start
     * @return mixed
     */
    public function getDifference($start)
    {
        $strStart = date('Y-m-d') . ' ' . $start;
        $strEnd = date('Y-m-d H:i:s');

        $dteStart = new DateTime($strStart);
        $dteEnd   = new DateTime($strEnd);

        $dteDiff  = $dteStart->diff($dteEnd);

        return $dteDiff->format("%H:%I:%S");
    }

    /**
     *
     *
     * @param $hours
     * @return false|int
     */
    public function getTotalTimeStampOfHours($hours)
    {
        $totalPerMonthTimeStamp = 0;

        foreach($hours as $hour) {
            if($hour['total']) {
                $totalPerMonthTimeStamp = $totalPerMonthTimeStamp + (strtotime($hour['total']) - strtotime("00:00:00"));
            }
        }

        return $totalPerMonthTimeStamp;
    }
}
