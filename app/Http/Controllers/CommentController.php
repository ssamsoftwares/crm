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
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\Return_;

class CommentController extends Controller
{

    // add Comment
    public function addComments($customerId)
    {
        $customer = Customer::with(['comments' => function ($query) {
            $query->orderBy('id', 'desc')->get();
        }])
            ->where('id', $customerId)
            ->first();
        return view('comment.add_comment', compact('customer'));
    }

    // Store Comment
    public function storeComments(Request $request, Comment $comment)
    {
        $this->validate($request, [
            'comments' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->all();
            Comment::create($data);
        } catch (Exception $e) {
            DB::rollBack();
            return Redirect::back()->with('status', $e->getMessage());
        }
        DB::commit();
        return Redirect::back()->with('status', "Comment Add Successfully Done!");
    }


    // Edit Comment
    public function editComment(Comment $comment)
    {
        $comment = Comment::with('customer', 'customer.user')
            ->where('id', $comment->id)
            ->first();
        return response()->json(['status' => 200, 'data' => $comment]);
    }


    // Update Comment
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
            return Redirect::back()->with('status', $e->getMessage());
        }
        DB::commit();
        return Redirect::back()->with('status', "Comment Updated Successfully Done!");
    }


    // Update Follow Up (Comment)
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



    // Update Customer Status

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


    // Update Customer Communication Medium


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



    // Customer All Comment List

    public function customerAllComment(Request $request, $customerId = null)
    {
        $customer = Customer::with(['user'])->where('id', $customerId)->first();

        if (!$customer) {
            abort(404);
        }

        $commentsQuery = $customer->comments()->orderBy('id', 'desc');

        $search = $request->search;

        if (!empty($search)) {
            if (Carbon::hasFormat($search, 'd-M-Y')) {
                $formattedDate = Carbon::createFromFormat('d-M-Y', $search)->format('Y-m-d');
                $commentsQuery->whereDate('created_at', $formattedDate);
            } else {
                $commentsQuery->where(function ($subquery) use ($search) {
                    $subquery->where('comments', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('customer', function ($customerQuery) use ($search) {
                            $customerQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('company_name', 'like', '%' . $search . '%');
                        });
                });
            }
        }

        $comments = $commentsQuery->paginate(10);

        return view('comment.index', compact('customer', 'comments'));
    }



    // Update Customer ProjectDetails AddEdit

    public function customerProjectDetailsAddEdit(Request $request, $customerId = null)
    {
        if ($customerId) {
            // Edit
            $customer = Customer::findOrFail($customerId);
            $customer->update([
                'project_details' => $request->project_details,
            ]);
        } else {
            $customer = new Customer();
            $customer->project_details = $request->project_details;
            $customer->save();
        }
        return Redirect::back()->with('status', 'Project details saved successfully');
    }
}
