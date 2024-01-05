<?php

namespace App\Http\Controllers;

use App\Imports\CustomerImport;
use App\Models\Comment;
use App\Models\Customer;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
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
        $customers = Customer::with('user');
        $search = $request->search;
        $status = $request->customer_status;
        $follow_up = $request->follow_up;

        if (!empty($request->search)) {
            $customers->where(function ($subquery) use ($search) {
                $subquery->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('follow_up', 'like', '%' . $search . '%')
                    ->orWhere('company_name', 'like', '%' . $search . '%');
            });
        }

        if (!empty($request->customer_status)) {
            $customers->where('status', $status);
        }

        if (!empty($request->follow_up)) {
            $customers->where('follow_up', $follow_up);
        }
        $customers = $customers->paginate(10);
        $users = User::where(['status' => 'active',])->whereHas('roles', function ($query) {
            $query->where('name', 'user')->whereNotIn('name', ['superadmin']);
        })->get();

        return view('superadmin.customer.all', compact('customers', 'users'));
    }


    public function create()
    {
        $users = User::where(['status' => 'active',])->whereHas('roles', function ($query) {
            $query->where('name', 'user')->whereNotIn('name', ['superadmin']);
        })->get();
        return view('superadmin.customer.add', compact('users'));
    }

    public function store(Request $request)
    {
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

            if(isset($request->user_id)){
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
        $customer = Customer::with('comments', 'user')->find($customerId);
        $comments = $customer->comments()->paginate(10);
        return view('superadmin.customer.view', compact('customer', 'comments'));
    }

    public function bulkUploadCustomerEdit(Customer $customer)
    {
        $users = User::where(['status' => 'active',])->whereHas('roles', function ($query) {
            $query->where('name', 'user')->whereNotIn('name', ['superadmin']);
        })->get();
        return view('superadmin.customer.edit', compact('customer','users'));
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
            $allotedUser = Customer::where('user_id',$request->user_id)->first();
            if(!$allotedUser){
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
        return view('superadmin.customer.bulk_upload');
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
}
