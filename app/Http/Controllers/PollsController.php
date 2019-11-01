<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Poll;

class PollsController extends Controller
{
    //
    public function index()
    {
        return response()->json(Poll::get(), 200);
    }

    public function show($id)
    {
        return response()->json(Poll::find($id), 200);
    }

    public function store(Request $request)
    {
        $poll = Poll::create($request->all());
        return response()->json($poll, 201);
    }

    public function update(Request $request, Poll $poll)
    {
        $poll->update($request->all());
        return response()->json($poll, 200);
    }
}
