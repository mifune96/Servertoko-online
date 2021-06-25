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
        $transaksi = Transaksi::with(['details.produk'])->where('id', $id)->first();

        $this->pushNotif('Transaksi',"Produk ". $transaksi->details[0]->produk->name ."Telah Berhasil Dibatalkan");
        $transaksi->update([
            'status' => "BATAL"
        ]);
        return redirect('transaksi');
    }

    public function confirm($id){
        $transaksi = Transaksi::with(['details.produk'])->where('id', $id)->first();

        $this->pushNotif('Transaksi Telah Dikonfirmasi',"Produk ". $transaksi->details[0]->produk->name ." telah kami konfirmasi Silahkan Tunggu Produk yg anda pesan telahkami konfirmasi");
        $transaksi->update([
            'status' => "PROSES"
        ]);
        return redirect('transaksi');
    }

    public function kirim($id){
        $transaksi = Transaksi::with(['details.produk'])->where('id', $id)->first();
        
        $this->pushNotif('Transaksi Telah Dikirim',"Produk ". $transaksi->details[0]->produk->name ." Telah kami kirim Silahkan Tunggu Produk yg anda pesan");
        $transaksi->update([
            'status' => "DIKIRIM"
        ]);
        return redirect('transaksi');
    }

    public function selesai($id){
        $transaksi = Transaksi::with(['details.produk'])->where('id', $id)->first();
        
        $this->pushNotif('Transaksi Telah diterima',"Produk ". $transaksi->details[0]->produk->name ."Telah Sampai Semoga Puas dengan pelayanan kami");
        $transaksi->update([
            'status' => "SELESAI"
        ]);
        return redirect('transaksi');
    }

    public function pushNotif($title, $message) {

        $mData = [
            'title' => $title,
            'body' => $message
        ];

        $fcm[] = "cewbqnRZSFyp1MJtIJyePw:APA91bFSIPh2W4gh-4XF134qPTTBhPjw7EVTT0c6wkiGhGSGNq0bLQrffm9eN16y0ei8nyZiRKqAvFHWvueb59N1EFJ7STPfdUtpT8YxqcGG3tJ7ca_D77dIXiE8J3CacPBQqfKrUT3N";

        $payload = [
            'registration_ids' => $fcm,
            'notification' => $mData
        ];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Content-type: application/json",
                "Authorization: key=AAAAqc1OZHM:APA91bH1gDjCGyptfey0__-H2MUCtHWuyCFokp8BoDZHCPSgBb5L_MxY1WeTgWxQjBH6M8YDRNIvHX92IiGKCZA3ck1bEIfpbPJVbNld4BKWPog6eS6_lXrEyTPzZKF0W6mqqy_pGqOo"
            ),
        ));
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($curl);
        curl_close($curl);

        $data = [
            'success' => 1,
            'message' => "Push notif success",
            'data' => $mData,
            'firebase_response' => json_decode($response)
        ];
        return $data;
    }
}
