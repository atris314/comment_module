<?php

namespace Modules\Comment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Comment\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::with('children')->get();
        return view('comments::index',get_defined_vars());
    }

    public function reply(Request $request)
    {
        try {
            $comment_old = Comment::findOrFail($request->comment_id);
            $comment = new Comment();
            $comment->comments_type = $comment_old->comments_type;
            $comment->comments_id = $comment_old->comments_id;
            $comment->parent_id = $comment_old->id;
            $comment->name = Auth::user()->name;
            $comment->text = $request->text;
            $comment->status = 'accepted';

            $comment->save();
            return redirect()->back()->with('success', 'با موفقیت ثبت شد');

        } catch (\Exception $e) {
            return redirect()->back()->with('warning', 'خطایی رخ داده است!');
        }
    }


    public function status(Comment $comment , Request $request)
    {
        try {
            $comment->status = $request->status;
            $comment->update();
            return redirect()->back()->with('success', ' با موفقیت انجام شد');

        } catch (\Exception $e) {
            return redirect()->back()->with('warning', 'خطایی رخ داده است!');
        }
    }

    public function statusShow(Comment $comment , Request $request)
    {
        try {
            if ($request->status == 'on')
            {
                $comment->status = 'accepted';
            }

            $comment->update();
            return redirect()->back()->with('success', 'با موفقیت انجام شد');

        } catch (\Exception $e) {
            return redirect()->back()->with('warning', 'خطایی رخ داده است!');
        }
    }
}
