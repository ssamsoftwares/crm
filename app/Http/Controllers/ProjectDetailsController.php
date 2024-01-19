<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\ProjectDetails;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectDetailsController extends Controller
{
    // project details list
    public function projectDetailsList(Request $request)
    {
        $customersQuery = Customer::with('user', 'comments');
        $authUser = Auth::user();

        // Apply filters
        $customersQuery->when($authUser->hasRole('superadmin'), function ($query) use ($request) {
            $query->where(function ($subquery) use ($request) {
                $search = $request->search;
                $subquery->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('communication_medium', 'like', '%' . $search . '%')
                    ->orWhere('company_name', 'like', '%' . $search . '%')
                    ->orWhere('project_details', 'like', '%' . $search . '%');
            });

            $selectedUser = $request->input('user');
            // "Not Allot" user
            if ($selectedUser === '-1') {
                $query->whereNull('user_id');
            } elseif (!empty($selectedUser)) {
                $query->where('user_id', $selectedUser);
            }
        });

        $customersQuery->when($authUser->hasRole('user'), function ($query) use ($request, $authUser) {
            // For users, only show their own records
            $query->where('user_id', $authUser->id);

            $query->where(function ($subquery) use ($request) {
                $search = $request->search;
                $subquery->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('communication_medium', 'like', '%' . $search . '%')
                    ->orWhere('company_name', 'like', '%' . $search . '%');
            });
        });

        $customersQuery->whereNotNull('project_details');
        $paginatedCustomers = $customersQuery->paginate(10);


        $users = User::get();

        return view('project_details.all', compact('paginatedCustomers', 'users'));
    }


    // Customer Project Details Add
    public function projectDetails(Request $request, ProjectDetails $projectDetails)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $customer = Customer::find($request->customer_id);
            // $customer->update(['project_details' => $request->project_details_status]);
            $customer->update(['project_details' => $request->project_details]);

            if ($request->project_details_status == 'Yes') {
                ProjectDetails::create([
                    'user_id' => $request->user_id,
                    'customer_id' => $request->customer_id,
                    'project_details_comment' => $request->project_details,
                ]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('status', $e->getMessage());
        }

        DB::commit();
        return Redirect::back()->with('status', "Project Details Added Successfully Done!");
    }



    // Customer Project Details Edit

    public function editProjectDetails($projectdetails_id)
    {
        $projectDetails = Customer::with('user')
            ->where('id', $projectdetails_id)
            ->first();
        return response()->json(['status' => 200, 'data' => $projectDetails]);
    }


    // Customer Project Details Update
    public function updateProjectDetails(Request $request)
    {
        DB::beginTransaction();
        try {
            $projectDetails = Customer::find($request->id);
            $projectDetails->update([
                'project_details' => $request->project_details,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('status', $e->getMessage());
        }
        DB::commit();
        return Redirect::back()->with('status', "Project Details Updated Successfully Done!");
    }
}
