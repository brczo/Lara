<?php

/**
 * 首页控制器
 * User: zfs
 * Date: 2019/8/17
 * Time: 22:34
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use App\Handlers\Level;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use Auth;

class IndexController extends BaseController
{
	//首页
	public function index()
	{
		$list = Post::getRecent();
		return view('index.index', ['list'=> $list]);
	}
	
	//列表
	public function category(Category $category, Level $level)
	{
		$categorys = Category::all();
		$childs_id_arr = $level->formatChild($categorys, $category->id);
		$list = Post::where('status', 1)->whereIn('category_id', $childs_id_arr)->orderBy('id', 'desc')->paginate(10);

		return view('index.category', ['list'=>$list, 'category'=>$category]);
	}

	//详情
	public function post(Post $post)
	{
		Post::where('id', $post->id)->increment('views');
		return view('index.post', ['post'=> $post]);
	}
	
	public function comment(Post $Post, CommentRequest $request)
	{
		$comment = Comment::create([
			'user_id' => Auth::user()->id,
			'Post_id' => $Post->id,
			'content' => $request->content,
			'at_id' => 0,
			'ip' => $request->getClientIp(),
			'status' => 1,
			'is_new' => 1,
		]);

		return redirect()->back()->with('success', '评论成功');
	}

	public function search(Request $request)
	{
		$data = Post::getSearch($request);
		return view('index.search', $data);
	}
}
