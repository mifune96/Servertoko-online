<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaksi;


class TransaksiController extends Controller
{
    public function index(){
        $transaksiPending['listPending'] = Transaksi::whereStatus("MENUNGGU")->get();
        $transaksiSelesai['listDone'] = Transaksi::where("Status", "NOT LIKE", "%MENUNGGU%")->get();
        return view('transaksi')->with($transaksiPending)->with($transaksiSelesai);
    }

    public function batal($id){
        $transaksi = Transaksi::where('id', $id)->first();
            
        $transaksi->update([
            'status' => "BATAL"
        ]);
        return redirect('transaksi');
    }

    public function confirm($id){
        $transaksi = Transaksi::where('id', $id)->first();
            
        $transaksi->update([
            'status' => "PROSES"
        ]);
        return redirect('transaksi');
    }

    public function kirim($id){
        $transaksi = Transaksi::where('id', $id)->first();
            
        $transaksi->update([
            'status' => "DIKIRIM"
        ]);
        return redirect('transaksi');
    }

    public function selesai($id){
        $transaksi = Transaksi::where('id', $id)->first();
            
        $transaksi->update([
            'status' => "SELESAI"
        ]);
        return redirect('transaksi');
    }
}
