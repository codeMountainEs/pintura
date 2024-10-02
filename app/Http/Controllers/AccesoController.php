<?php

namespace App\Http\Controllers;

use App\Models\Acceso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccesoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        return view('teclado');
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Acceso $acceso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Acceso $acceso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Acceso $acceso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Acceso $acceso)
    {
        //
    }
}
