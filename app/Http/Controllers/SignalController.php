<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class SignalController extends Controller
{
    public function index()
    {
        return view('signals.index');
    }

    public function start(Request $request)
   {
    $messages = [
        'sequence.required' => 'The sequence field is required.',
        'sequence.array' => 'The sequence field must be an array.',
        'sequence.min' => 'The sequence field must have at least :min items.',
        'sequence.max' => 'The sequence field must not have more than :max items.',
        'sequence.*.required' => 'Sequence :position is required.',
        'sequence.*.integer' => 'Sequence :position must be an integer.',
        'sequence.*.distinct' => 'Sequence :position must be distinct.',
        'sequence.*.between' => 'Sequence :position must be between :min and :max.',
        'greenInterval.required' => 'The green interval is required.',
        'greenInterval.integer' => 'The green interval must be an integer.',
        'greenInterval.min' => 'The green interval must be at least :min seconds.',
        'yellowInterval.required' => 'The yellow interval is required.',
        'yellowInterval.integer' => 'The yellow interval must be an integer.',
        'yellowInterval.min' => 'The yellow interval must be at least :min seconds.',
    ];

    try {
        $request->validate([
            'sequence' => 'required|array|min:4|max:4',
            'sequence.*' => 'required|integer|distinct|between:1,4',
            'greenInterval' => 'required|integer|min:1',
            'yellowInterval' => 'required|integer|min:1',
        ], $messages);

        $sequence = $request->input('sequence');
        $greenInterval = $request->input('greenInterval');
        $yellowInterval = $request->input('yellowInterval');

        return response()->json([
            'status' => 'Signal started',
            'sequence' => $sequence,
            'greenInterval' => $greenInterval,
            'yellowInterval' => $yellowInterval,
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'status' => 'Validation Error',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'Error',
            'message' => 'An unexpected error occurred.',
            'error' => $e->getMessage()
        ], 500);
    }
 }

}