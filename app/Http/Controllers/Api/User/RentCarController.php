<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;

class RentCarController extends Controller
{
    public function store(Car $car, Request $request)
    {
        $auth = auth()->user();
        $formated_date_1 = Carbon::parse($request->date_end);
        $formated_date_2 = Carbon::parse($request->date_start);
        $total_days =  $formated_date_1->diffInDays($formated_date_2);

        // jumlah hari dan harga
        $price = $car->price * $total_days;
        $image_sim = $request->file('image_sim')->store('sim', 'public');

        $transaction = Transaction::create([
            'car_id' => $car->id,
            'user_id' => $auth->id,
            'transaction_code' => 'TRX' . mt_rand(10000, 99999) . mt_rand(100, 999),
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            'phone' => $request->user()->phone,
            'address' => $request->user()->address,
            'identiity_number' => $request->user()->identity_number,
            'image_sim' => $image_sim,
            'date_start' => $request->date_start,
            'date_end' => $request->date_end,
            'transaction_total' => $price,
            'transaction_status' => 'PENDING',
        ]);

        Car::where('id', $car->id)->update([
            'rented' => '1'
            // rented 1 kalau sudah dirental
        ]);


        if ($transaction) {
            return ResponseFormatter::success($transaction, 'Transaksi berhasil, silahkan lanjutkan pembayaran');
        } else {
            return ResponseFormatter::error(null, 'Transaction Failed', 500);
        }


    }
}
