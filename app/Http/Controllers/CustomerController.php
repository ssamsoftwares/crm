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
use Illuminate\Support\Facades\Redirect;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;
use Spatie\Permission\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class CustomerController extends Controller
{

    /**
     *---------------------------------------------------------------------
     * BULK UPLOAD CUSTOMERS FUNCTIONS
     *---------------------------------------------------------------------
     */



    public function getBulkUploadCustomerData(Request $request)
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

            $status = $request->customer_status;
            $communication_medium = $request->communication_medium;
            $selectedUser = $request->input('user');

            if (!empty($status)) {
                $query->where('status', $status);
            }

            if (!empty($communication_medium)) {
                $query->where('communication_medium', $communication_medium);
            }

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

            $status = $request->customer_status;
            $communication_medium = $request->communication_medium;

            if (!empty($status)) {
                $query->where('status', $status);
            }

            if (!empty($communication_medium)) {
                $query->where('communication_medium', $communication_medium);
            }
        });

        $perPage = 10;
        $customers = $customersQuery->orderBy('id', 'desc')->paginate($perPage);

        $users = User::get();
        return [$customers, $users];
    }

    // customer list
    public function bulkUploadCustomer(Request $request)
    {
        [$customers, $users] = $this->getBulkUploadCustomerData($request);
        return view('customer.all', compact('customers', 'users'));
    }



    //Customer Create
    public function create()
    {
        // $users = User::where(['status' => 'active',])->whereHas('roles', function ($query) {
        //     $query->where('name', 'user')->whereNotIn('name', ['superadmin']);
        // })->get();

        $users = User::get();
        return view('customer.add', compact('users'));
    }


    //Customer Store
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:customers,email',
            'phone_number' => 'required',
            'company_name' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['status'] = isset($request->customer_status) ? $request->customer_status : 'no_status';

            if (!empty($request->user_id)) {
                $data['alloted_date'] = Carbon::now();
            }
            Customer::create($data);
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('status', $e->getMessage());
        }
        DB::commit();
        return Redirect::route('customers')->with('status', 'Customer Added Successfully !');
    }


    //Customer View Profile

    public function bulkUploadCustomerView(Request $request, $customerId = null)
    {
        $customer = Customer::with(['user'])->where('id', $customerId)->first();

        if (!$customer) {
            abort(404);
        }

        $commentsQuery = Comment::where('customer_id', $customerId)->orderBy('id', 'desc');


        $search = $request->search;

        if (!empty($search)) {
            $commentsQuery->where(function ($subquery) use ($search) {
                if (Carbon::hasFormat($search, 'd-M-Y')) {

                    $formattedDate = Carbon::createFromFormat('d-M-Y', $search)->format('Y-m-d');
                    $subquery->whereDate('created_at', $formattedDate);
                } else {

                    $subquery->where('comments', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('company_name', 'like', '%' . $search . '%');
                        });
                }
            });
        }


        $comments = $commentsQuery->paginate(10);

        return view('customer.view', compact('customer', 'comments'));
    }



    //Customer Edit
    public function bulkUploadCustomerEdit(Customer $customer)
    {
        $users = User::get();
        return view('customer.edit', compact('customer', 'users'));
    }


    //Customer Update
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

            if ($customer->user_id == NULL) {
                $data['alloted_date']  = Carbon::now();
            }

            $data['status'] = isset($request->customer_status) ? $request->customer_status : 'no_status';;

            $customer->update($data);
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('status', $e->getMessage());
        }
        DB::commit();
        // return redirect()->back()->with('status', 'Customer Updated Successfully !');
        return Redirect::route('customer.bulkUploadCustomerView', ['customerId' => $customer->id])->with('status', 'Customer Updated Successfully!');
    }


    //Customer Details Import file
    public function importFileView()
    {
        return view('customer.bulk_upload');
    }


    // Customer Import
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


    //  Download Customer SampleCsv
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
        return Redirect::back()->with('status', 'Sample CSV file Not Found.');
    }


    // Allot Customer to user (Checkbox)
    public function assignCustomer(Request $request)
    {
        $cId_arr = explode(',', $request->input('c_ids'));

        $customer = Customer::whereIn('id', $cId_arr)
            ->update([
                'user_id' => $request->input('user_id'),
                'alloted_date' =>  Carbon::now()
            ]);
        return Redirect::route('customers')->with('status', "Customer Successfully Assigned on Selected User.");
    }


    // add multiple customer Name
    public function addcustName(Request $request)
    {
        DB::beginTransaction();
        try {
            $customer = Customer::find($request->id);
            $customer->update([
                'name' => $customer->name . ',' . $request->name,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('status', $e->getMessage());
        }
        DB::commit();
        return Redirect::back()->with('status', "Name added Successfully Done!");
    }



    // add multiple customer Phone
    public function addcustNamePhoneNumber(Request $request)
    {
        DB::beginTransaction();
        try {
            $customer = Customer::find($request->id);
            $customer->update([
                'phone_number' => $customer->phone_number . ',' . $request->phone_number,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('status', $e->getMessage());
        }
        DB::commit();
        return Redirect::back()->with('status', "Phone added Successfully Done!");
    }


    // get  customer Comment
    public function getCustomerComment($customerId)
    {
        $comments = Comment::where('customer_id', $customerId)->get();
        return response()->json(['comments' => $comments]);
    }
}
