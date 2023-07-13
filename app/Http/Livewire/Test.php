<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Test extends Component
{
//    public $data;
    public function render()
    {
//        $this->data = DB::connection('sqlsrv')->table('accmast')->get();
//        return DB::connection('sqlsrv')->table('accmast')->get();
/////////
//        $conn = sqlsrv_connect('51.211.167.159,8084', array('Database'=>'AccountsC5','UID'=>'IbrahimBinAlshikh', 'PWD'=>'124AlyassX'));
//        if(is_resource($conn)){
//            return "connected";
//        }
//        throw new \Exception(json_encode(sqlsrv_errors()));
//        throw new \Exception('Connection to Scribes Database Failed.');
        ////////////////////

//        $serverName = "192.168.1.100";
        $serverName = "51.211.167.159:8084";
        $database = "AccountsC5";
        $uid = "IbrahimBinAlshikh";
//        $uid = "SA";
        $pass = "124AlyassX";
//        $pass = "SqlP@$$@Alyaseen";

        $connection = ["Database" => $database, "Uid" => $uid, "PWD" => $pass];


        $conn = sqlsrv_connect('51.211.167.159,8084', array('Database'=>'AccountsC5','UID'=>'IbrahimBinAlshikh', 'PWD'=>'124AlyassX'));
//        $conn = odbc_connect("Driver={ODBC Driver 13 for SQL Server};Server=$serverName;Database=$database;", $uid, $pass);
        if(!$conn)
//            die(print_r('bad'));
            die(print_r(sqlsrv_errors(), true));
        else
            dd("connection established");

        return view('livewire.test', )
            ->layout('layouts.dashboard');
    }
}
