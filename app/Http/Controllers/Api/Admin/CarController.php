<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $car = Car::all();
        $car->map(function ($car) {
            $car->image = asset('storage/' . $car->image);
            return $car;
        });

        return ResponseFormatter::success($car, 'Data List Mobil Berhasil Diambil');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auth = $request->user();



        if ($auth->role == 'admin') {
            // $request->validate([
            //     'title' => 'required',
            //     'image' => 'required',
            //     'colour' => 'required',
            //     'description' => 'required',
            //     'price' => 'required',
            // ]);

            $image = $request->file('image')->store('car', 'public');

            $car = Car::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'colour' => $request->colour,
                'image' => $image,
                'description' => $request->description,
                'price' => $request->price,
            ]);

            if ($car) {
                return ResponseFormatter::success(
                    $car,
                    'Data Mobil Berhasil Ditambahkan'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data Mobil Gagal Ditambahkan',
                    404
                );
            }
        } else {
            return ResponseFormatter::error(
                null,
                'Anda Bukan Admin',
                404
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        return ResponseFormatter::success($car, 'Detail Mobil Berhasil Diambil');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $car = Car::find($id);

        if ($request->file('image')) {
            $image = $request->file('image')->store('car', 'public');
        } else {
            $image = $car->image;
        }

        if ($user->role == 'admin') {
            $car->update([
                'title' => $request->title ? $request->title : $car->title,
                'slug' => Str::slug($request->title) ? str::slug($request->title) : $car->slug,
                'colour' => $request->colour ?? $car->colour,
                'image' => $image ?? $car->image,
                'description' => $request->description ?? $car->description,
                'price' => $request->price ?? $car->price,
            ]);
        } else {
            return ResponseFormatter::success($car, 'Anda tidak memiliki akses');
        }

        return ResponseFormatter::success($car, 'Data mobil berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = auth()->user();

        $car = Car::find($id);

        if ($user->role == 'admin') {
            $car->delete();
        } else {
            return ResponseFormatter::success($car, 'Anda tidak memiliki akses untuk menghapus data mobil');
        }

        return ResponseFormatter::success($car, 'Data Mobil berhasil dihapus');
    }
}
