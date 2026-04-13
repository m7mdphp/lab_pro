<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\View\View;

class BranchesController extends Controller
{
    public function index(): View
    {
        $branches = Branch::active()
            ->with('translations')
            ->get();

        return view('branches.index', compact('branches'));
    }
}
