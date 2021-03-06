<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(Request $request, Category $category, Topic $topic, User $user, Link $link)
    {
        // 读取分类 ID 关联的话题，并按每页 20 条分页
        $topics = $topic->withOrder($request->order)
                        ->where('category_id', $category->id)
                        ->paginate(20);
        // 活跃用户
        $active_users = $user->getActiveUsers();

        // 资源链接
        $links = $link->getAllCached();

        // 传参变量话题和分类到模版中
        return view('topics.index', compact('topics', 'category', 'active_users', 'links'));
    }
}
