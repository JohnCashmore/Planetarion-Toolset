<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Tag;

class TagController extends ApiController
{
    public function index()
    {
        return Tag::all();
    }
}