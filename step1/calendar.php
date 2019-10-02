<?php

final class Calendar
{
    private $date;
    private $days;
    private $calendar = [];

    public function __construct(array $date = null)
    {
        $this->date = $this->setDateFormat($date);
        $this->days = ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di'];
        $this->calendar = [
            'title' => $this->titleBuilder(),
            'days' => $this->navBuilder(),
            'dates' => $this->datesBuilder()

        ];
        $this->displayCalendar();
    }

    private function displayCalendar()
    {
        foreach($this->calendar as $string) {
            echo $string;
        }
    }

    /**
     * manage errors on args and set date
     */
    private function setDateFormat(array $date)
    {
        if(count($date) > 2 || count($date) === 0) {
            $this->errorDisplay('args');
        }
        if(count($date) === 1) {
            $date = explode('-', $date[0]);
            $date = [empty($date[1]) ? null : $date[1], $date[0]];
        }
        if(
            !ctype_digit($date[0])
            || !ctype_digit($date[1])
            || !checkdate($date[0], 1, $date[1])
        ) {
            $this->errorDisplay('args');
        }
        return $date;
    }

    private function errorDisplay($type)
    {
        switch($type) {
            case 'args' :
             $display = 'Arguments aren\'t valid,'
                .' it should be : month year or year-month'
                . PHP_EOL . 'Example : 01 2018 / 01-2018';
        }
        exit($display);
    }

    private function titleBuilder()
    {
        $pad = 36;
        $borderLines = str_pad('', $pad, '=');
        $middleLine = '';
        $month = date('F', mktime(0, 0, 0, $this->date[0]));
        $date = $month . ' ' . $this->date[1];
        $pad = $pad - 4 - strlen($date);
        $middleLine = '||' . str_pad('', $pad / 2, ' ');
        $middleLine .= $date;
        $middleLine = str_pad($middleLine, 34, ' ');
        $middleLine .= '||';
        return PHP_EOL
            . $borderLines
            . PHP_EOL . $middleLine . PHP_EOL
            . $borderLines
            . PHP_EOL;
    }

    private function navBuilder()
    {
        return '| ' . implode(' | ', $this->days) . ' |' . PHP_EOL;
    }

    private function datesBuilder()
    {
        $dates = '|';
        $borderLines = str_pad('',36, '-');
        $daysNumber = date('t', mktime(0, 0, 0, $this->date[0], 1, $this->date[1]));
        $init = date('N', mktime(0, 0, 0, $this->date[0], 1, $this->date[1]));
        $unit = '    |';
        $unitLength = 5;
        for($count = 0; $count < $init; $count++) {
            $dates .= $unit;
        }
        for($day = 1; $day <= $daysNumber; $day++) {
            $init++;
            if($day < 10) {
                $dates .= '  ' . $day . ' |';
            } else {
                $dates .= ' ' . $day . ' |';
            }
            if($init % 7 === 0) {
                $dates .= PHP_EOL . $borderLines . PHP_EOL . '|';
            }
        }
        for($day; $day < $init; $day++) {
            $dates .= $unit;
        }
        return $borderLines . PHP_EOL . $dates . PHP_EOL . $borderLines;
    }
}
new Calendar(array_slice($argv, 1));