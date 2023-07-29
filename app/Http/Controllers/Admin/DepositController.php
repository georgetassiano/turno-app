<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;

class DepositController extends Controller
{
    public function index() {
        return response()->json([
            'message' => 'Admin deposit index'
        ]);
    }

    public function show(Deposit $deposit) {
        return response()->json([
            'message' => 'Admin deposit show'
        ]);
    }

    public function acceptOrReject(Deposit $deposit, Request $request) {
        return response()->json([
            'message' => 'Admin deposit accept or reject'
        ]);
    }

}
