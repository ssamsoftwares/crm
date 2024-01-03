<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */

    public function __invoke(Request $request)
    {
        $total['users'] = User::count();
        $total['customers'] = Customer::count();
        $total['allotCustomerUser'] = Customer::where('user_id',auth()->user()->id)->count();
        return view('dashboard')->with(compact('total'));
    }
}
