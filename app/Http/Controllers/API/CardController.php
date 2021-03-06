<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Card;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCardRequest;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Card::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCardRequest $request)
    {
        $card = Card::create($request->all());

        return response()->json([
            'message' => 'Card created successfully!',
            'data' => $card,
        ], 201);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function show(Card $card)
    {
        $card = Card::with('comments')->findOrFail($card->id);
        
        if (auth()->user()->id != $card->task->board->user_id) {
            return response()->json('Unauthorized!', 401);
        }

        return response()->json($card, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function update(CreateCardRequest $request, Card $card)
    {
        if (auth()->user()->id != $card->task->board->user_id) {
            return response()->json('Unauthorized!', 401);
        }

        $card->update($request->all());
        
        return response()->json([
            'message' => 'Card updated successfully!',
            'data' => $card,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Card  $card
     * @return \Illuminate\Http\Response
     */
    public function destroy(Card $card)
    {
        if (auth()->user()->id != $card->task->board->user_id) {
            return response()->json('Unauthorized!', 401);
        }

        if ($card->delete()) 
            return response()->json('Deleted card!', 200);
        
    }

}
