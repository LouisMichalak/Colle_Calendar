<?php

final class Calendar
{
    private $date;
    private $days;
    private $calendar = [];

    public function __construct()
    {
        $this->initProgram();
    }

    private function initProgram() {
        unset($this->date);
        unset($this->days);
        unset($this->calendar);
        $date = readline(PHP_EOL . 'Choisissez une date : ');
        $date = preg_split('/\-/', trim($date));
        if(count($date) === 1) {
            $date = preg_split('/\s/', $date[0]);
        } else {
            foreach($date as $index => $value) {
                if($value === '') {
                    unset($date[$index]);
                }
                $date = array_values($date);
            }
            $date = [$date[1], $date[0]];
        }
        if(count($date) > 1) {
            $this->date = $this->setLongDateFormat($date);
            if($this->date !== false) {
                $this->runLongFormat();
            }
        }
        if(count($date) === 1) {
            $this->date = $this->setShortDateFormat($date[0]);
            if($this->date !== false) {
                $this->runShortFormat();
            }
        }
        if($this->date === false) {
            
            $this->initProgram();
        }
    }

    private function runShortFormat() {
        $this->days = ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di'];
        $this->setCalendar();
        $this->displayCalendars();
        $this->initProgram();
    }

    private function runLongFormat() {
        $this->days = ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di'];
        $this->calendar =
        [
            'title' => $this->titleBuilder(),
            'days' => $this->navBuilder(),
            'dates' => $this->datesBuilder()
        ];
        $this->displayCalendar();
        $this->initProgram();
    }

    private function displayCalendar()
    {
        foreach($this->calendar as $string) {
            echo $string;
        }
    }
    
    private function displayCalendars()
    {
        $this->explodeCalendar();
        for($index = 0; $index < count($this->calendar); $index += 2) {
            foreach($this->calendar[$index] as $key => $block) {
                foreach($block as $line => $string) {
                    if($string !== '') {
                        if(!empty($this->calendar[$index + 1][$key][$line])) {
                            echo $string
                                . ' '
                                . $this->calendar[$index + 1][$key][$line]
                                . PHP_EOL;
                        }
                    }
                    if(count($block) === $line + 1)) {
                        $ctr = $line + 1;
                        for($ctr; $ctr < count($this->calendar[$index + 1][$key]); $ctr++) {
                            if($this->calendar[$index + 1][$key][$ctr] !== '') {
                                echo str_pad('', 37, ' ')
                                    . $this->calendar[$index + 1][$key][$ctr]
                                    . PHP_EOL;
                            }
                        }
                    }
                }
            }
        }
    }

    private function explodeCalendar()
    {
        foreach($this->calendar as $index => $block) {
            foreach($block as $type => $string) {
                $this->calendar[$index][$type] = explode(
                    PHP_EOL,
                    $string
                );
            }
        }
    }

    private function setCalendar()
    {
        $this->date = [null, $this->date];
        for($month = 1; $month <= 12; $month++) {
            $this->date[0] = $month;
            $this->calendar[] =
            [
                'title' => $this->titleBuilder(),
                'days' => $this->navBuilder(),
                'dates' => $this->datesBuilder()
            ];
        }
    }

    /**
     * manage errors on args and set date
     */
    private function setShortDateFormat(string $date)
    {
        if(
            !ctype_digit($date)
            || !checkdate(1, 1, $date)
        ) {
            $this->errorDisplay('args');
            return false;
        }
        return $date;
    }

    private function setLongDateFormat($date)
    {
        if(
            !ctype_digit($date[0])
            || !ctype_digit($date[1])
            || !checkdate((int)$date[0], 1, (int)$date[1])
        ) {
            $this->errorDisplay('args');
            return false;
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
        echo PHP_EOL . $display . PHP_EOL;
        return false;
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
        $init = date('N', mktime(0, 0, 0, $this->date[0], 1, $this->date[1])) - 1;
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
        while($init % 7 !== 0) {
            $init++;
            $dates .= $unit;
        }
        return $borderLines . PHP_EOL . $dates . PHP_EOL . $borderLines;
    }
}
new Calendar();