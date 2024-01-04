<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->customer_status;
        $follow_up = $request->follow_up;
        $authUser = Auth::user();
        $customersQuery = Customer::where('user_id', $authUser->id);


        if (!empty($search)) {
            $customersQuery->where(function ($subquery) use ($search) {
                $subquery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%')
                    ->orWhere('company_name', 'like', '%' . $search . '%');
            });
        }

        if (!empty($request->customer_status)) {
            $customersQuery->where('status', $status);
        }

        if (!empty($request->follow_up)) {
            $customersQuery->where('follow_up', $follow_up);
        }

        $customers = $customersQuery->orderBy('id', 'desc')->paginate(15);

        return view('user.customers', compact('customers'));
    }


    public function addComments($customerId)
    {
        $authUser = Auth::user();
        $customer = Customer::with(['comments' => function ($query) {
            $query->orderBy('id', 'desc')->take(5);
        }])
            ->where('user_id', $authUser->id)
            ->where('id', $customerId)
            ->first();
        return view('user.comments.add', compact('customer'));
    }

    public function storeComments(Request $request, Comment $comment)
    {
        $this->validate($request, [
            'comments' => 'required',
        ]);
        DB::beginTransaction();
        try {
            Comment::create($request->all());
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', "Comment Add Successfully Done!");
    }

    public function viewAllComments(Request $request, $customerId)
    {
        $customer = Customer::with(['comments' => function ($query) use ($request) {
            $query->orderBy('id', 'desc');

            // Search by created_at in comments relation
            $search = $request->search;
            if (!empty($search)) {
                if (Carbon::hasFormat($search, 'd-M-Y')) {
                    $formattedDate = Carbon::createFromFormat('d-M-Y', $search)->format('Y-m-d');
                    $query->whereDate('created_at', $formattedDate);
                } else {
                    $query->where('comments', 'like', '%' . $search . '%');
                }
            }
        }])
            ->where('user_id', auth()->user()->id)
            ->where('id', $customerId)
            ->first();

        if (!$customer) {
            abort(404); // Handle the case when customer is not found
        }

        $comments = $customer->comments()->paginate(15);

        return view('user.comments.viewAll_comment', compact('customer', 'comments'));
    }

    public function editComment(Comment $comment)
    {
        $comment = Comment::with('customer', 'customer.user')
            ->where('user_id', auth()->user()->id)
            ->where('id', $comment->id)
            ->first();
            return response()->json(['status'=>200,'data'=>$comment]);

    }

    public function updateComments(Request $request)
    {
        $this->validate($request, [
            'comments' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $comment = Comment::find($request->id);
            $comment->update($request->all());
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', "Comment Updated Successfully Done!");
    }


    public function updateFollowUpStatus(Request $request, $id)
    {
        $customer = Customer::find($id);

        if ($customer) {
            $customer->follow_up  = $request->input('follow_up_status');
            $customer->save();

            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false, 'message' => 'Customer not found']);
    }


    public function updateCustomerStatus(Request $request, $id)
    {
        $customer = Customer::find($id);

        if ($customer) {
            $customer->status  = $request->input('customer_status');
            $customer->save();

            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false, 'message' => 'Customer not found']);
    }
}
