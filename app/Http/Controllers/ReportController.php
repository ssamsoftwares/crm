<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    // Superadmin Show all User Report List

    public function allUsersReport(Request $request)
    {

        $query = User::withCount('customer');
        $search = $request->user;
        if (!empty($request->user)) {
            $query->where('id', $search);
        }

        $usersWithCustomerCount = $query->paginate(10);
        $users = User::get();

        return view('report.all_user_report', compact('usersWithCustomerCount','users'));
    }


    // View Customer List
    public function userAllotedCustomerDetails(Request $request,$userId=Null)
    {
        $user = User::findOrFail($userId);
        $query = Customer::where('user_id', $userId);

        $search = $request->search;
        if(!empty($search)){
            $query->where(function ($subquery) use ($search){
                $subquery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('company_name', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        $customers = $query->paginate(10);

        return view('report.view_cust_details', compact('customers', 'user','search'));
    }



    // User Side
    // User show alloted customer details
    public function allotedCustomersFromUser()
    {
        $total['customerTodayStatusCount'] = Customer::where('status', 'today')->where('user_id', Auth::id())->count();
        $total['customerHighStatusCount'] = Customer::where('status', 'high')->where('user_id', Auth::id())->count();
        $total['customerMediumStatusCount'] = Customer::where('status', 'medium')->where('user_id', Auth::id())->count();
        $total['customerLowStatusCount'] = Customer::where('status', 'low')->where('user_id', Auth::id())->count();
        $total['customerNoReqStatusCount'] = Customer::where('status', 'no_required')->where('user_id', Auth::id())->count();
        // $total['customerNoStatusCount'] = Customer::whereNull('status')->where('user_id', Auth::id())->count();
        $total['customerNoStatusCount'] = Customer::where(function ($query) {
            $query->where('status', 'no_status')
                ->orWhereNull('status');
        })
            ->where('user_id', Auth::id())
            ->count();

        return view('userAllotedCustDetails.all', compact('total'));
    }


    public function statusWiseShowCustomerList(Request $request, $status = null)
    {
        $query = Customer::where('user_id', Auth::id());
        if (!is_null($status)) {
            $query->where('status', $status);
        }

        $search = $request->input('search');
        if (!empty($search)) {
            $query->where(function ($subquery) use ($search) {
                $subquery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('company_name', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        $customers = $query->paginate(10);
        return view('userAllotedCustDetails.view', compact('customers', 'status', 'search'));
    }
}
