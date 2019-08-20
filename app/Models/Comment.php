<?php

/**
 * 评论模型
 * User: zfs
 * Date: 2019/8/17
 * Time: 22:34
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $fillable = [
		'post_id', 'user_id', 'content', 'at_id', 'ip', 'status', 'read'
	];
	
	public function post()
	{
		return $this->belongsTo(Post::class);
	}
	
	public function user()
	{
		return $this->belongsTo(User::class);
	}
	
	public function atUser()
	{
		return $this->belongsTo(User::class, 'at_id');
	}

	public static function getRecent($limit = 10)
	{
		return self::where('status', 1)->orderBy('id', 'desc')->limit($limit)->get();
	}

}
