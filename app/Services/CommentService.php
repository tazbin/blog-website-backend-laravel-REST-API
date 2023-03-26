<?php

namespace App\Services;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Blog;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentService
{
    protected $blogService;

    public function __construct(
        BlogService $blogService,
       )
    {
        $this->blogService = $blogService;
    }

    // get list
    public function getList()
    {
        $selectColumns = ['id', 'title', 'content', 'author_id', 'category_id', 'created_at', 'updated_at'];
        $withRelations = ['author:id,name', 'category:id,name'];

        $comments = Comment::select($selectColumns)
                    ->with($withRelations)
                    ->get();

        return $comments;
    }

    // create new
    public function create($data, $blogId, $authorId)
    {
        $data['blog_id'] = $blogId;
        $data['commenter_id'] = $authorId;

        $blog = Blog::find($blogId);
        if( !$blog ) {
            throw new ModelNotFoundException('there is no blog with this id');
        }

        $comment = Comment::create($data);

        return $comment;
    }

    // search
    public function find($id)
    {
        $selectColumns = ['id', 'title', 'content', 'author_id', 'category_id', 'created_at', 'updated_at'];
        $withRelations = ['author:id,name', 'category:id,name'];

        $comment = Comment::select($selectColumns)
                    ->with($withRelations)
                    ->find($id);

        return $comment;
    }

    // edit item
    public function update($data, $id)
    {
        $comment = Comment::find($id);
        if( !$comment ) {
            throw new ModelNotFoundException('there is no comment with this id');
        }

        if( Auth::user()->id != $comment->author_id ) {
            throw new AuthorizationException('you do not have permission to edit this comment');
        }

        $comment = $comment->update($data);

        return $comment;
    }

    // delete item
    public function delete($blogId, $commentId)
    {
        $conditions = [
            'blog_id' => $blogId,
            'id' => $commentId,
        ];

        $comment = Comment::where($conditions)->first();
        if( !$comment ) {
            throw new ModelNotFoundException('there is no blog or comment with this id');
        }

        if( Auth::user()->id != $comment->commenter_id ) {
            throw new AuthorizationException('you do not have permission to delete this comment');
        }

        $comment->delete($commentId);

        return;
    }
}
