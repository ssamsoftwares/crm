<?php

namespace App\Http\Controllers;

use App\Imports\CustomerImport;
use App\Models\Comment;
use App\Models\Customer;
use App\Models\ProjectDetails;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;
use Spatie\Permission\Models\Role;

class CustomerController extends Controller
{

    /**
     *---------------------------------------------------------------------
     * BULK UPLOAD CUSTOMERS FUNCTIONS
     *---------------------------------------------------------------------
     */

    public function bulkUploadCustomer(Request $request)
    {
        $customersQuery = Customer::with('user', 'comments');
        $search = $request->search;
        $status = $request->customer_status;
        $communication_medium = $request->communication_medium;

        $authUser = Auth::user();

        // Apply filters based on user role
        $customersQuery->when($authUser->hasRole('superadmin'), function ($query) use ($search, $status, $communication_medium) {
            $query->where(function ($subquery) use ($search) {
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

            if (!empty($status)) {
                $query->where('status', $status);
            }

            if (!empty($communication_medium)) {
                $query->where('communication_medium', $communication_medium);
            }
        });

        // For users, only show their own records
        $customersQuery->when($authUser->hasRole('user'), function ($query) use ($authUser) {
            $query->where('user_id', $authUser->id);
        });

        $customers = $customersQuery->paginate(10);

        return view('customer.all', compact('customers'));
    }

    public function create()
    {
        $users = User::where(['status' => 'active',])->whereHas('roles', function ($query) {
            $query->where('name', 'user')->whereNotIn('name', ['superadmin']);
        })->get();
        return view('customer.add', compact('users'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:customers,email',
            'phone_number' => 'required',
            'company_name' => 'required',
            'customer_status' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['status'] = $request->customer_status;

            if (!empty($request->user_id)) {
                $data['alloted_date'] = Carbon::now();
            }
            Customer::create($data);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', 'Customer Added Successfully !');
    }


    public function bulkUploadCustomerView(Request $request, $customerId)
    {
        $customer = Customer::with(['user', 'comments' => function ($query) use ($request) {
            $query->orderBy('id', 'desc');
            // Search by created_at in comments relation
            $search = $request->search;
            if (!empty($search)) {
                if (Carbon::hasFormat($search, 'd-M-Y')) {
                    $formattedDate = Carbon::createFromFormat('d-M-Y', $search)->format('Y-m-d');
                    $query->whereDate('created_at', $formattedDate);
                } else {
                    $query->where(function ($subquery) use ($search) {
                        $subquery->where('comments.comments', 'like', '%' . $search . '%')
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('name', 'like', '%' . $search . '%');
                            });
                    });
                }
            }
        }])
            // ->where('user_id', auth()->user()->id)
            ->where('id', $customerId)
            ->first();

        if (!$customer) {
            abort(404);
        }

        $comments = $customer->comments()->paginate(15);
        return view('customer.view', compact('customer', 'comments'));
    }

    public function bulkUploadCustomerEdit(Customer $customer)
    {
        $users = User::where(['status' => 'active',])->whereHas('roles', function ($query) {
            $query->where('name', 'user')->whereNotIn('name', ['superadmin']);
        })->get();
        return view('customer.edit', compact('customer', 'users'));
    }

    public function bulkUploadCustomerUpdate(Request $request, Customer $customer)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
            'company_name' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();
            if ($customer->user_id != $request->user_id) {
                $data['alloted_date'] = Carbon::now();
            }

            $data['status'] = $request->customer_status;

            $customer->update($data);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', 'Customer Updated Successfully !');
    }

    public function importFileView()
    {
        return view('customer.bulk_upload');
    }

    public function customerImport(Request $request)
    {
        DB::beginTransaction();
        try {
            $file = $request->file('file');
            $import = new CustomerImport();
            Excel::import($import, $file);
            $newCustomerCount = $import->getNewCustomerCount();
            $updatedCustomerCount = $import->getUpdatedCustomerCount();
            $totalStudentCount = $newCustomerCount + $updatedCustomerCount;
            $uploadedFileName = $file->getClientOriginalName();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'File import failed: ' . $e->getMessage()]);
        }
        DB::commit();
        $message = "File <strong>'{$uploadedFileName}'</strong> successfully imported. <strong>{$newCustomerCount}</strong> new Customer added and <strong>{$updatedCustomerCount}</strong> updated from <strong>{$totalStudentCount}</strong> records.";
        return response()->json(['message' => $message]);
    }

    public function downloadCustomerSampleCsv()
    {
        DB::beginTransaction();
        $filePath = storage_path('app/public/customers_sample.csv');
        $fileName = 'customers_sample.csv';
        if (file_exists($filePath)) {
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/csv',
                'Content-Disposition' => 'attachment; filename=' . $fileName,
            ]);
        }
        return redirect()->back()->with('status', 'Sample CSV file Not Found.');
    }

    public function assignCustomer(Request $request)
    {
        $cId_arr = explode(',', $request->input('c_ids'));

        $customer = Customer::whereIn('id', $cId_arr)->update(['user_id' => $request->input('user_id')]);
        return redirect()->route('customers')->with('status', "Customer Successfully Assigned on Selected User.");
    }


    public function projectDetails(Request $request, ProjectDetails $projectDetails)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $customer = Customer::find($request->customer_id);
            $customer->update(['project_details' => $request->project_details_status]);

            if ($request->project_details_status == 'Yes') {
                ProjectDetails::create([
                    'user_id' => $request->user_id,
                    'customer_id' => $request->customer_id,
                    'project_details_comment' => $request->project_details,
                ]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }

        DB::commit();
        return redirect()->back()->with('status', "Project Details Added Successfully Done!");
    }


    // public function projectDetailsList(Request $request)
    // {
    //     dd($request->all());
    //     $query = ProjectDetails::query();
    //     $usersData = User::where(['status' => 'active',])->whereHas('roles', function ($que) {
    //             $que->where('name', 'user')->whereNotIn('name', ['superadmin']);
    //         })->get();
    //     $searchTerm = $request->input('search');
    //     // If the user has the 'superadmin' role,
    //     if (auth()->user()->hasRole('superadmin')) {
    //         $query->with('user', 'customer');

    //     } else {
    //         // If the user has the 'user' role,
    //         $query->where('user_id', auth()->user()->id);
    //     }

    //     if (!empty($request->search) || !empty($request->user) && (!empty($request->search)) {
    //         if (Carbon::hasFormat($searchTerm, 'd-M-Y')) {
    //             $formattedDate = Carbon::createFromFormat('d-M-Y', $searchTerm)->format('Y-m-d');
    //             $query->whereDate('created_at', $formattedDate);
    //         } else {
    //             $query->where(function ($q) use ($searchTerm) {
    //                 $q->whereHas('user', function ($userQuery) use ($searchTerm) {
    //                     $userQuery->where('users.name', 'like', "%$searchTerm%");
    //                 })
    //                     ->orWhereHas('customer', function ($customerQuery) use ($searchTerm) {
    //                         $customerQuery->where('name', 'like', "%$searchTerm%")
    //                             ->orWhere('phone_number', 'like', "%$searchTerm%");
    //                     })
    //                     ->orWhere('project_details_comment', 'like', "%$searchTerm%");
    //             });
    //         }
    //     }
    //     $projectDetailsList = $query->paginate(15);

    //     return view('project_details.all', compact('projectDetailsList','usersData'));
    // }


    public function projectDetailsList(Request $request)
    {
        $query = ProjectDetails::query();
        $usersData = User::where(['status' => 'active',])->whereHas('roles', function ($que) {
            $que->where('name', 'user')->whereNotIn('name', ['superadmin']);
        })->get();

        $searchTerm = $request->search;
        $selectedUser = $request->user;

        // If the user has the 'superadmin' role,
        if (auth()->user()->hasRole('superadmin')) {
            $query->with('user', 'customer');
        } else {
            // If the user has the 'user' role,
            $query->where('user_id', auth()->user()->id);
        }

        // Apply user filter
        if (!empty($selectedUser)) {
            $query->where('user_id', $selectedUser);
        }

        if (!empty($searchTerm)) {
            if (Carbon::hasFormat($searchTerm, 'd-M-Y')) {
                $formattedDate = Carbon::createFromFormat('d-M-Y', $searchTerm)->format('Y-m-d');
                $query->whereDate('created_at', $formattedDate);
            } else {
                $query->where(function ($q) use ($searchTerm) {
                    $q->whereHas('user', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('users.name', 'like', "%$searchTerm%");
                    })
                        ->orWhereHas('customer', function ($customerQuery) use ($searchTerm) {
                            $customerQuery->where('name', 'like', "%$searchTerm%")
                                ->orWhere('phone_number', 'like', "%$searchTerm%");
                        })
                        ->orWhere('project_details_comment', 'like', "%$searchTerm%");
                });
            }
        }

        $projectDetailsList = $query->paginate(15);

        return view('project_details.all', compact('projectDetailsList', 'usersData', 'selectedUser'));
    }

    public function editProjectDetails($projectdetails_id)
    {
        $projectDetails = ProjectDetails::with('customer', 'user')
            ->where('id', $projectdetails_id)
            ->first();
        return response()->json(['status' => 200, 'data' => $projectDetails]);
    }

    public function updateProjectDetails(Request $request)
    {

        $this->validate($request, [
            'project_details_comment' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $projectDetails = ProjectDetails::find($request->id);
            $projectDetails->update($request->all());
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', "Project Details Updated Successfully Done!");
    }

    // add multiple customer name and phone
    public function addcustNamePhoneNumber(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $customer = Customer::find($request->id);
            if (!empty($request->name)) {
                $customer->update([
                    'name' => $customer->name . ',' . $request->name,
                ]);
            } else {
                $customer->update([
                    'phone_number' => $customer->phone_number . ',' . $request->phone_number,
                ]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', "Name Or phone added Successfully Done!");
    }
}
