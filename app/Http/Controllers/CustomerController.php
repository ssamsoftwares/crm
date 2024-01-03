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
        if (!empty($request->search)) {
            $search = $request->search;
            $customers->where(function ($subquery) use ($search) {
                $subquery->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%')
                    ->orWhere('company_name', 'like', '%' . $search . '%');
            });
        }

        $customers = $customers->paginate(10);

        $users = User::where(['status' => 'active',])->whereHas('roles', function ($query) {
            $query->where('name', 'user')->whereNotIn('name', ['superadmin']);
        })->get();

        return view('superadmin.customer.all', compact('customers', 'users'));
    }

    public function bulkUploadCustomerView(Request $request, $customerId)
    {
        $customer = Customer::with('comments', 'user')->find($customerId);
        $comments = $customer->comments()->paginate(10);
        return view('superadmin.customer.view', compact('customer', 'comments'));
    }

    public function bulkUploadCustomerEdit(Customer $customer)
    {
        return view('superadmin.customer.edit', compact('customer'));
    }

    public function bulkUploadCustomerUpdate(Request $request, Customer $customer)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            // Customer PHOTO
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if (is_file(public_path($customer->image))) {
                    unlink(public_path($customer->image));
                }
                $customerImg = $request->file('image');
                $filename = uniqid() . '.' . $customerImg->getClientOriginalExtension();
                $customerImg->move(public_path('customer_img'), $filename);
                $data['image'] = 'customer_img/' . $filename;
            }

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
