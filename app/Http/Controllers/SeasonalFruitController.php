<?php

namespace App\Http\Controllers;

use App\Models\SeasonalFruit;
use Illuminate\Http\Request;

class SeasonalFruitController extends Controller
{
    public function create()
    {
        // return the view
        return view('season.seasoncreate');
    }
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'season_from' => 'required|date',
            'season_to' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remarks' => 'nullable|string',
        ]);

        $imagePath = null;

        // Handle file upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('images/season'); // public/images/season
            $image->move($destinationPath, $filename);
            $imagePath = 'images/season/' . $filename; // Save relative path in DB
        }

        // Save data to database
        SeasonalFruit::create([
            'name' => $request->name,
            'from_month' => $request->season_from,
            'to_month' => $request->season_to,
            'img' => $imagePath,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('seasonalfruit.create')->with('success', 'Seasonal fruit added successfully!');
    }
    public function show()
    {
        // Get all fruits from DB
        $fruits = \App\Models\SeasonalFruit::all();

        // Pass them to the view
        return view('season.fruits', compact('fruits'));
    }
    public function destroy($id)
    {
        $fruit = SeasonalFruit::findOrFail($id);

        // Delete image if exists
        if ($fruit->img && file_exists(public_path($fruit->img))) {
            unlink(public_path($fruit->img));
        }

        // Delete record
        $fruit->delete();

        return redirect()->route('seasonalfruit.show')->with('success', 'Fruit deleted successfully!');
    }
}
