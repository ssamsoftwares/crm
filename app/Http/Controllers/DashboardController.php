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
        $customerStatus = $request->customer_status;
        $communicationMedium = $request->communication_medium;

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
        $selectedUser = $request->input('user');

        if ($authUser->hasRole('superadmin')) {
            $customerQuery->where(function ($subquery) use ($search, $customerStatus, $communicationMedium, $selectedUser) {
                if (!empty($search)) {
                    $subquery->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('phone_number', 'like', '%' . $search . '%')
                        ->orWhere('status', 'like', '%' . $search . '%')
                        ->orWhere('communication_medium', 'like', '%' . $search . '%')
                        ->orWhere('company_name', 'like', '%' . $search . '%');
                }

                // Filters customer_status and communication_medium
                if (!empty($customerStatus)) {
                    $subquery->where('status', $customerStatus);
                }

                if (!empty($communicationMedium)) {
                    $subquery->where('communication_medium', $communicationMedium);
                }

                // "Not Allot" user
                if ($selectedUser === '-1') {
                    $subquery->whereNull('user_id');
                } elseif (!empty($selectedUser)) {
                    $subquery->where('user_id', $selectedUser);
                }
            });

            $total['customerTodayStatus'] = $customerQuery->paginate(10);
        } elseif ($authUser->hasRole('user')) {
            $customerQuery->where('user_id', $authUser->id)
                ->where(function ($subquery) use ($search, $customerStatus, $communicationMedium) {
                    if (!empty($search)) {
                        $subquery->where('name', 'like', '%' . $search . '%')
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('name', 'like', '%' . $search . '%');
                            })
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('phone_number', 'like', '%' . $search . '%')
                            ->orWhere('status', 'like', '%' . $search . '%')
                            ->orWhere('communication_medium', 'like', '%' . $search . '%')
                            ->orWhere('company_name', 'like', '%' . $search . '%');
                    }

                    // Filters customer_status and communication_medium
                    if (!empty($customerStatus)) {
                        $subquery->where('status', $customerStatus);
                    }

                    if (!empty($communicationMedium)) {
                        $subquery->where('communication_medium', $communicationMedium);
                    }
                });

            $total['customerTodayStatus'] = $customerQuery->paginate(10);
        }

        $users = User::where(['status' => 'active'])
            ->whereHas('roles', function ($query) {
                $query->where('name', 'user')->whereNotIn('name', ['superadmin']);
            })->get();

        return view('dashboard', compact('total', 'users'));
    }
}
