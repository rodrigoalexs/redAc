<?php


namespace App;

/**
 * Class LineData
 * @package App
 *
 * Created to parse the data of the lines of the file.
 */
class LineData
{
    public $lineRef;
    public $location;
    public $start;
    public $finish;
    public $startOfDay;
    public $endOfDay;
    public $comments;
    public $day;
    public $originalData;

    public function __construct(int $line, array $data)
    {
        $this->originalData = $data;

        $this->lineRef = $line;
        $this->location = $data['A'];

        $times = explode(' - ',$data['D']);
        $start = $data['B'].' '.$times[0];
        $this->start = date('Y-m-d H:i:s', strtotime($start));

        $finish = $data['B'].' '.$times[1];
        $this->finish = date('Y-m-d H:i:s', strtotime($finish));

        $startOfDay = $data['B'].' 00:00:00';
        $this->startOfDay =  date('Y-m-d H:i:s', strtotime($startOfDay));

        $endOfDay = $data['B'].' 23:59:59';
        $this->endOfDay =  date('Y-m-d H:i:s', strtotime($endOfDay));

        $this->comments = $data['E'];

        //I've chosen to get the Day based on the date to reduce the chance of error on the rate calculation
        $this->day = date('D', strtotime($data['B']));
    }

}
