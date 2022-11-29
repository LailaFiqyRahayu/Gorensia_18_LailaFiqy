<?php

namespace App\Http\Controllers\Api\User;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CarController extends Controller
{
    public function index()
    {
        $car = Car::where('rented', '0')->get();
        $car->map(function ($car) {
            $car->image = asset('storage/' . $car->image);
            return $car;
        });
 
        if ($car) {
            return ResponseFormatter::success($car, 'Data List Mobil Berhasil Diambil');
        } else {
            return ResponseFormatter::error($car, 'Data Gagal Diambil');
        }
    }

    public function show(Car $car)
    {
        return ResponseFormatter::success($car, 'Data Mobil Berhasil Diambil');
    }
}
