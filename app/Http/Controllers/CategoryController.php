<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        return view('categories.index');
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }
} 