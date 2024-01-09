<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{


    public function addComments($customerId)
    {
        $authUser = Auth::user();
        $customer = Customer::with(['comments' => function ($query) {
            $query->orderBy('id', 'desc')->take(5);
        }])
            // ->where('user_id', $authUser->id)
            ->where('id', $customerId)
            ->first();
        return view('comment.add_comment', compact('customer'));
    }

    public function storeComments(Request $request, Comment $comment)
    {
        dd($request->all());
        $this->validate($request, [
            'comments' => 'required',
        ]);


        DB::beginTransaction();
        try {
            $data = $request->all();
            Comment::create($data);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('status', $e->getMessage());
        }
        DB::commit();
        return redirect()->back()->with('status', "Comment Add Successfully Done!");
    }


    public function editComment(Comment $comment)
    {
        $comment = Comment::with('customer', 'customer.user')
            // ->where('user_id', auth()->user()->id)
            ->where('id', $comment->id)
            ->first();
        return response()->json(['status' => 200, 'data' => $comment]);
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

    public function updateFollowUpStatus(Request $request, $customerId)
    {
        $customer = Customer::find($customerId);
        if ($customer) {
            $comment = new Comment();
            $comment->user_id = auth()->user()->id;
            $comment->customer_id = $customer->id;
            $comment->comments = $request->input('follow_up_status');
            $comment->save();

            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false, 'message' => 'Customer not found']);
    }
    public function updateCustomerStatus(Request $request, $id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->status = $request->input('customer_status');
            $customer->update();

            return response()->json(['status' => true]);
        }
        return response()->json(['status' => false, 'message' => 'Customer not found']);
    }


    public function CustomerCommunicationMedium(Request $request, $id)
    {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->communication_medium = $request->input('communication_medium');
            $customer->update();

            return response()->json(['status' => true]);
        }
        return response()->json(['status' => false, 'message' => 'Customer not found']);
    }





}
