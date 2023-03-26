<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Services\BlogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    protected $blogService;


    public function __construct(
        BlogService $blogService,
        )
    {
        $this->blogService = $blogService;

    }

    public function createBlog(CreateBlogRequest $request)
    {
        $blogData = $request->validated();
        $createdBlog = $this->blogService->create($blogData, Auth::user()->id);

        return response([
            'data' => $createdBlog
        ], Response::HTTP_CREATED);
    }

    public function getBlogs(Request $request)
    {
        $blogs = $this->blogService->getList();

        return response([
            'data' => $blogs
        ], Response::HTTP_OK);
    }

    public function getBlog(Request $request, $blogId)
    {
        $blog = $this->blogService->find($blogId);

        return response([
            'data' => $blog
        ], Response::HTTP_OK);
    }

    public function getUserBlogs(Request $request, $userId)
    {
        $blogs = $this->blogService->getUserBlogs($userId);

        return response([
            'data' => $blogs
        ], Response::HTTP_OK);
    }

    public function updateBlog(UpdateBlogRequest $request, $blogId)
    {
        $blogData = $request->validated();
        $updatedblog = $this->blogService->update($blogData, $blogId);

        return response([
            'data' => $updatedblog
        ], Response::HTTP_OK);
    }

    public function deleteBlog($blogId)
    {
        $updatedblog = $this->blogService->delete($blogId);

        return response([
            'data' => $updatedblog
        ], Response::HTTP_OK);
    }
}
