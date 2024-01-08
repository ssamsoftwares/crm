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
        $authUser = Auth::user();
        $search = $request->search;
        $total = [
            'users' => User::whereNotIn('id', [$authUser->id])
                ->whereDoesntHave('roles', function ($query) {
                    $query->where('name', 'superadmin');
                })
                ->count(),
            'customers' => Customer::count(),
            'allotCustomerUser' => Customer::where('user_id', auth()->user()->id)->count(),
        ];

        $customerQuery = Customer::where('status', 'today');

        if ($authUser->hasRole('superadmin')) {
            if (!empty($search)) {
                $total['customerTodayStatus'] = $customerQuery
                    ->where(function ($subquery) use ($search) {
                        $subquery->where('name', 'like', '%' . $search . '%')
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('name', 'like', '%' . $search . '%');
                            })
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('phone_number', 'like', '%' . $search . '%')
                            ->orWhere('status', 'like', '%' . $search . '%')
                            ->orWhere('communication_medium', 'like', '%' . $search . '%')
                            ->orWhere('company_name', 'like', '%' . $search . '%');
                    })
                    ->paginate(10);
            } else {
                $total['customerTodayStatus'] = $customerQuery->paginate(10);
            }
        } elseif ($authUser->hasRole('user')) {
            if (!empty($search)) {
                $total['customerTodayStatus'] = $customerQuery
                    ->where('user_id', $authUser->id)
                    ->where(function ($subquery) use ($search) {
                        $subquery->where('name', 'like', '%' . $search . '%')
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('name', 'like', '%' . $search . '%');
                            })
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('phone_number', 'like', '%' . $search . '%')
                            ->orWhere('status', 'like', '%' . $search . '%')
                            ->orWhere('communication_medium', 'like', '%' . $search . '%')
                            ->orWhere('company_name', 'like', '%' . $search . '%');
                    })
                    ->paginate(10);
            } else {
                $total['customerTodayStatus'] = $customerQuery->where('user_id', $authUser->id)->paginate(10);
            }
        }

        return view('dashboard')->with(compact('total'));
    }
}
