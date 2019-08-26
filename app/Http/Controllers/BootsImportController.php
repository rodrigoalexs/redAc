<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BootsImportController extends Controller
{
    public function import(Request $request)
    {
        if($request->hasFile('original')){

            $file = Storage::putFile('uploads', $request->file('original'));
            $completePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix().$file;

            $reader = IOFactory::load($completePath);

            foreach ($reader->getAllSheets() as $sheet) {
                var_dump($sheet);
            }

            Storage::delete($file);

            return response('aaaa',200);
        }

        return response('File not found',500);
    }
}
