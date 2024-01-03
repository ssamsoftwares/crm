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
        $authUser = Auth::user();
        $customersQuery = Customer::where('user_id', $authUser->id);
        $search = $request->search;
        if (!empty($search)) {
            $customersQuery->where(function ($subquery) use ($search) {
                $subquery->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%')
                    ->orWhere('company_name', 'like', '%' . $search . '%');
            });
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

    // public function viewAllComments(Request $request, $customerId)
    // {
    //     $customer = Customer::with(['comments' => function ($query) {
    //         $query->orderBy('id', 'desc');
    //     }])
    //         ->where('user_id', auth()->user()->id)
    //         ->where('id', $customerId);
    //     // ->first();
    //     $search = $request->search;
    //     if (!empty($search)) {

    //     }
    //     $customer->first();
    //     $comments = $customer->comments()->paginate(15);
    //     return view('user.comments.viewAll_comment', compact('customer', 'comments'));
    // }

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
        return view('user.comments.edit', compact('comment'));
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
}
