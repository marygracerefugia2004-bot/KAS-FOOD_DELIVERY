<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Favorite;
use App\Models\Food;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Food::where('available', true);
        if ($request->search) {
            $q = strip_tags($request->search);
            $query->where('name', 'like', "%{$q}%")
                ->orWhere('description', 'like', "%{$q}%");
        }
        if ($request->category) {
            $query->where('category', $request->category);
        }
        $foods = $query->withCount('ratings')->paginate(12);
        $favIds = auth()->check()
            ? Favorite::where('user_id', auth()->id())->pluck('food_id')->toArray()
            : [];
        $cartCount = auth()->check() ? auth()->user()->cartItems()->count() : 0;

        return view('user.foods', compact('foods', 'favIds', 'cartCount'));
    }

    public function show(Food $food)
    {
        $food->load('ratings.user');

        return view('user.food-detail', compact('food'));
    }

    // Admin CRUD
    public function adminIndex()
    {
        $foods = Food::latest()->paginate(15);

        return view('admin.foods.index', compact('foods'));
    }

    public function create()
    {
        return view('admin.foods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|max:2048',
            'category' => 'required|string',
            'prep_time' => 'required|integer|min:1',
        ]);

        $path = $request->file('image')->store('foods', 'public');
        $food = Food::create([
            'name' => strip_tags($request->name),
            'description' => strip_tags($request->description),
            'price' => $request->price,
            'image_path' => $path,
            'category' => $request->category,
            'prep_time' => $request->prep_time,
        ]);

        AuditLog::record('food_create', "Food created: {$food->name}", null, $food->toArray());

        return redirect()->route('admin.foods.index')->with('success', 'Food added!');
    }

    public function edit(Food $food)
    {
        return view('admin.foods.edit', compact('food'));
    }

    public function update(Request $request, Food $food)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $old = $food->toArray();
        $data = [
            'name' => strip_tags($request->name),
            'description' => strip_tags($request->description),
            'price' => $request->price,
            'category' => $request->category,
            'available' => $request->boolean('available'),
            'prep_time' => $request->prep_time,
        ];
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('foods', 'public');
        }
        $food->update($data);
        AuditLog::record('food_update', "Food updated: {$food->name}", $old, $food->fresh()->toArray());

        return redirect()->route('admin.foods.index')->with('success', 'Food updated!');
    }

    public function destroy(Food $food)
    {
        AuditLog::record('food_delete', "Food deleted: {$food->name}", $food->toArray());
        $food->delete();

        return redirect()->route('admin.foods.index')->with('success', 'Food deleted!');
    }

    public function toggle(Food $food)
    {
        $food->is_available = ! $food->is_available;
        $food->save();

        return back()->with('success', 'Food status updated!');
    }
}
