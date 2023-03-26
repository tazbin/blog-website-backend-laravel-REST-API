<?php

namespace App\Services;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BlogService
{
    protected $userService;

    public function __construct(
        UserService $userService,
        )
    {
        $this->userService = $userService;
    }

    // get list
    public function getList()
    {
        $blogs = Blog::all();
        $blogs->load('author:id,name', 'category:id,name');
        $blogs->loadCount('comments');

        return $blogs;
    }

    // create new
    public function create($data, $authorId)
    {
        $data['author_id'] = $authorId;
        $blog = Blog::create($data);

        return $blog;
    }

    // search
    public function find($id)
    {
        $blog = Blog::find($id);
        if( !$blog ) {
            throw new ModelNotFoundException('there is no blog with this id');
        }
        $blog->load('author:id,name', 'category:id,name', 'comments.commenter:id,name');

        return $blog;
    }

    public function getUserBlogs($userId)
    {
        $user = User::find($userId);
        if( !$user ) {
            throw new ModelNotFoundException('there is no user with this id');
        }

        $blogs = $user->blogs;
        $blogs->load('category:id,name');
        $blogs->loadCount('comments');

        return $blogs;
    }

    // edit item
    public function update($data, $id)
    {
        $blog = Blog::find($id);
        if( !$blog ) {
            throw new ModelNotFoundException('there is no blog with this id');
        }

        if( Auth::user()->id != $blog->author_id ) {
            throw new AuthorizationException('you do not have permission to edit this blog');
        }

        $blog = $blog->update($data);

        return $blog;
    }

    // delete item
    public function delete($id)
    {
        $blog = Blog::find($id);
        if( !$blog ) {
            throw new ModelNotFoundException('there is no blog with this id');
        }

        if( Auth::user()->id != $blog->author_id ) {
            throw new AuthorizationException('you do not have permission to delete this blog');
        }

        $blog->delete($id);

        return;
    }
}
