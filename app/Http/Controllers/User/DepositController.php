<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index() {
        return response()->json([
            'message' => 'User deposit index'
        ]);
    }

    public function show(Deposit $deposit) {
        return response()->json([
            'message' => 'User deposit show'
        ]);
    }

    public function store(Request $request) {
        return response()->json([
            'message' => 'User deposit store'
        ]);
    }
}
