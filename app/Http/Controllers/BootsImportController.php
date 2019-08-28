<?php

namespace App\Http\Controllers;

use App\BookingData;
use App\LineData;
use App\Mail\SendMailable;
use App\Note;
use App\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Input;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use function Psy\debug;

class BootsImportController extends Controller
{
    public function import(Request $request)
    {
        $exceptions = [];
        $imported = [['Booking ID','Rate','Start','Finish','Comments']];
        if($request->hasFile('original')){

            $file = Storage::putFile('uploads', $request->file('original'));
            $completePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().$file;

            $reader = IOFactory::load($completePath);

            $importedSheet = new Spreadsheet();
            for ($i = 0 ; $i< $reader->getSheetCount(); $i++){
                $sheet = $reader->getSheet($i);
                $sheetName = $sheet->getTitle();
                $sheetData = $sheet->toArray(null,false,true, true);
                $lastRow = $sheet->getHighestRow();

                for($row = 2; $row <= $lastRow; $row++){
                    $hasNullValue = array_search(null, $sheetData[$row]);
                    if(!empty($sheetData[$row]) && ($hasNullValue === false || $hasNullValue === 'D')){
                        $lineData = new LineData($row, $sheetData[$row]);
                        $pharmacy = Pharmacy::where(['branch_identifier' => $lineData->location])->first();
                        if(!$pharmacy){
                            $exceptions[] = "{$sheetName} row {$lineData->lineRef} exception - Store {$lineData->location} does not exist";
                            continue;
                        }

                        /*    Note: Here, the filter could be changed from 'start > $startOfDay' to start >= $start and
                         * 'start < $endOfDay' to 'start <= $finish' in order to allow more than one booking per day
                         * with no overlapping between them.
                         *
                         *   I wasn't about this so, I deciced to keep just one booking per day as described on the challenge
                         * */
                        $bookingCount = BookingData::where([
                            ['pharmacy_id', $pharmacy->pharmacy_id],
                            ['start', '>', $lineData->startOfDay],
                            ['start' , '<', $lineData->endOfDay],
                        ])->count();

                        if($bookingCount){
                            $exceptions[] = "{$sheetName} row {$lineData->lineRef} exception - Booking on 25/08/2019 already exists";
                            continue;
                        }

                        switch ($lineData->day){
                            case 'SUN':
                                $rate = $pharmacy->pharmacist_sunday_rate;
                                break;
                            case 'SAT':
                                $rate = $pharmacy->pharmacist_saturday_rate;
                                break;
                            default:
                                $rate = $pharmacy->pharmacist_rate;
                        }

                        $newBooking = new BookingData();

                        $newBooking->pharmacy_id = $pharmacy->pharmacy_id;
                        $newBooking->role = 'Pharmacist';
                        $newBooking->booking_fee = $pharmacy->pharmacist_booking_fee;
                        $newBooking->rate = $rate;
                        $newBooking->start = $lineData->start;
                        $newBooking->finish = $lineData->finish;
                        $newBooking->public = 1;

                        if($newBooking->save() && $lineData->comments){
                            $newNote = new Note();
                            $newNote->note = $lineData->comments;
                            $newNote->private = 1;
                            $newNote->booking_id = $newBooking->id;

                            $newNote->save();
                        }

                        $imported[] = [
                            $newBooking->id,
                            $newBooking->rate,
                            $newBooking->start,
                            $newBooking->finish,
                            $newBooking->note->note ?? ''
                        ];

                    }else{
                        $exceptions[] = "{$sheetName} row {$row} exception - This line has columns with empty required values";
                    }
                }
            }
            $importedSheet->getActiveSheet()->fromArray($imported);

            $objectWriter = IOFactory::createWriter($importedSheet,'Xlsx');
            $pathImported = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().'/import.xlsx';
            $objectWriter->save($pathImported);


            $originalFile = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().$file;

            $content = $this->createContent($exceptions);


            $files = [
                ['path' => $originalFile, 'name'=> 'original.xlsx', 'mime' => 'xlsx'],
                ['path' => $pathImported, 'name'=> 'import.xlsx', 'mime'=>'xlsx']
            ];
            Mail::to('rodrigoalex.puc@gmail.com')->send(new SendMailable($content, 'boots', $files, 'PHP Developer'));

            Storage::delete($file);

            return response('Data imported and email sent',200);
        }

        return response('File not found',500);
    }

    public function createContent($data)
    {
        $content = '<table>';
        $content .= '<tr><th>Exceptions</th></tr>';

        foreach ($data as $row){
            $content .= "<tr><td>{$row}</td></tr>";
        }
        $content .= "</table>";


        return $content;
    }
}
