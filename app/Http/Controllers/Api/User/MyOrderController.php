<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class MyOrderController extends Controller
{

    public function index()
    {
        $order = Transaction::with(['car'])->where('user_id',  Auth::user()->id)->get();

        return ResponseFormatter::success(
            $order,
            'Data List Order Berhasil Diambil'
        );
    }

    public function processPaymentRent(Request $request)
    {
        $image = $request->file('image')->store('payment', 'public');

        $payment = Payment::create([
            'user_id' => Auth::user()->id,
            'transaction_id' => $request->transaction_id,
            'image' => $image,
            'name' => $request->name,
            'type' => $request->type,
        ]);


        Transaction::where('id', $request->transaction_id)->update([
            'transaction_status' => 'WAITING'
        ]);

        if ($payment) {
            return ResponseFormatter::success($payment, 'success');
        } else {
            return ResponseFormatter::error(null, 'failed', 500);
        }
    }
}
