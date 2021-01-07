<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use PDF;
use ImalH\PDFLib\PDFLib;


trait FileTrait
{

    public function uploadPhoto(Request $request,$field_key){
        $file_key = $request->file_key;
        $a_file = [];
        $path = '';

        if ($request->hasFile($file_key))
        {
            $directory = storage_path('app/public/uploads/image-location');

            if(!File::exists($directory)){
                File::makeDirectory($directory,0777,true,true);
            }

            $file_prefix = $request->has('file_prefix') ? $request->input('file_prefix') : 'file';
            $file_path = $request->has('file_path') ? $request->input('file_path') : 'uploads/';
            $file = $request->file($file_key);
            $a_file['file_name'] = $file_prefix.time().'-'.rand(111,999).'.'.$file->getClientOriginalExtension();
            $a_file['mime_type'] = $file->getClientMimeType();
            $a_file['size'] = $file->getClientSize();
            $a_file[$field_key] = $file_path.'/'.$a_file['file_name'];

            $path = $request->file($file_key)->storeAs($file_path,$a_file['file_name'],'public');
            if($request->has('current_file_url')) {
                $this->storage->delete($request->input('current_file_url'));
            }
        }
        return $path;
    }


    public function generatePDFInvoice($data){
        $path = storage_path('app/public/'.\Config::get('proto.generate.invoice').'/'.date('Y').'/'.date('F'));
        if(!File::exists($path)){
            File::makeDirectory($path,0777,true,true);
        }

        $pdf = PDF::loadView('invoice',array('data'=>$data))->setPaper('a4','portrait');
        $pdf->save($path.'/'.$data['order_number'].'.pdf');

        return \Config::get('proto.generate.invoice').'/'.date('Y').'/'.date('F').'/'.$data['order_number'].'.pdf';
    }

    public function convertFileToJpg($file_path,$order_number,$receipt_url = null){
        if(!is_null($receipt_url)){
            $this->storage->delete($receipt_url);
        }
        $output_path  = storage_path('app/public/'.\Config::get('proto.generate.invoice').'/'.date('Y').'/'.date('F').'/');

        if(!File::exists($output_path)){
            File::makeDirectory($output_path,0777,true,true);
        }
        try{
            if ( strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ) {
                $scriptName = "start /B " .'gswin64c -q -dSAFER -dBATCH -dNOPAUSE -sDEVICE=jpeg -dPDFFitPage -r200 -dUseCropBox -sOutputFile='.$output_path.$order_number.'.jpeg '.storage_path('app/public/').$file_path;
                pclose( popen( $scriptName , "r" ) );
            } else {
                shell_exec('export HOME=/tmp/ && gs -q -dSAFER -dBATCH -dNOPAUSE -sDEVICE=jpeg  -dPDFFitPage -dUseCropBox -r200 -sOutputFile='.$output_path.$order_number.'.jpeg '.storage_path('app/public/').$file_path.'  > /dev/null &');
            }
            while(!File::exists($output_path.$order_number.'.jpeg')){
                sleep(1);
            }

            if(File::exists($output_path.$order_number.'.jpeg')){
                $this->storage->delete($file_path);
                return \Config::get('proto.generate.invoice').'/'.date('Y').'/'.date('F').'/'.$order_number.'.jpeg';
            }
            else{
                return false;
            }
        }catch(\Exception $e){
            return $e;
        }



    }
}
