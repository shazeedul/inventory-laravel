<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class VisitorCounterController extends Controller
{
    /**
     * Get visitors count
     *
     * @return mixed
     */
    public function index()
    {
        return response()->success(Visitor::getVisitorsCount(), 'Visitors count retrieved successfully.');
    }

    /**
     * Get visitors count svg
     *
     * @return mixed
     */
    public function svg()
    {
        $response = Response::make(View::make('components.visitor'), 200);
        $response->header('Content-Type', 'image/svg+xml');

        return $response;
    }
}
