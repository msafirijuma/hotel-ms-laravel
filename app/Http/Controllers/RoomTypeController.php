<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoomType;
use App\Models\RoomTypeImage;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roomTypes = RoomType::with('images')->orderBy('name', 'asc')->get();
        $roomTypes = RoomType::latest()->paginate(10);
        return view('room-types.index', compact('roomTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('room-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:100|unique:room_types,name',
            'description'      => 'nullable|string|max:500',
            'price_per_night'  => 'required|numeric|min:1000|max:1000000',
            'max_occupancy'    => 'required|integer|min:1|max:10',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        // Image Upload Logic
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Create directory if not exists
            $path = public_path('uploads/room_types');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            $image->move($path, $imageName);
            $validated['image'] = 'uploads/room_types/' . $imageName;
        }

        RoomType::create($validated);

        LogActivity::log('Create Room Type', 'Has created room type: ' . $request->name);

        return redirect()->route('room-types.index')
                        ->with('success', 'Room Type created successfully with image!');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoomType $roomType)
    {
        $roomType->load('images');
        return view('room-types.edit', compact('roomType'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, RoomType $roomType)
    {
        // Basic validation
        $request->validate([
            'name'       => 'required|string|max:255|unique:room_types,name,' . $roomType->id,
            'price_per_night' => 'required|numeric|min:1',
            'max_adults'      => 'required|integer|min:1',
            'max_children'    => 'nullable|integer|min:0',
            'description'     => 'nullable|string',
            'main_image'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB
            'gallery.*'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB for image
        ], [
            'name.unique' => 'Jina hili la aina ya chumba tayari linatumika.',
        ]);

        $imagePath = $roomType->image; // Keeping old as alternative

        // Main Image Update
        if ($request->hasFile('main_image')) {
            // Delete main image from Storage if available
            if ($roomType->image) {
                Storage::delete('public/' . $roomType->image);
            }
            // Save image to the folder 'public/uploads/room_types'
            $imagePath = $request->file('main_image')->store('uploads/room_types', 'public');
        }

        // Multiple Upload Gallery Images 
        $firstNewGallery = '';
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $galleryPath = $file->store('uploads/room_types', 'public');
                
                // insert data into gallery pictue table
                $newImg = RoomTypeImage::create([
                    'room_type_id' => $roomType->id,
                    'image_path'   => $galleryPath,
                    'is_primary'   => 0
                ]);

                if (empty($firstNewGallery)) {
                    $firstNewGallery = $galleryPath;
                    $firstNewGalleryId = $newImg->id;
                }
            }
        }

        // Auto set first new gallery as primary if no main image
        if (empty($imagePath) && !empty($firstNewGallery)) {
            $imagePath = $firstNewGallery;
            
            // Putting is_primary = 1 for this main image
            RoomTypeImage::where('id', $firstNewGalleryId)->update(['is_primary' => 1]);
        }

        // Update room type information 
        $roomType->update([
            'name'       => trim($request->name),
            'price_per_night' => $request->price_per_night,
            'max_adults'      => $request->max_adults,
            'max_children'    => $request->max_children ?? 0,
            'description'     => trim($request->description),
            'image'           => $imagePath,
        ]);

        LogActivity::log('Update Room Type', 'Has created room type: ' . $request->name);

        return redirect()->route('room-types.index')
            ->with('success', 'Room Type update successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomType $roomType)
    {
        // Optional: Check if any rooms are using this type
        if ($roomType->rooms()->count() > 0) {
            return redirect()->route('room-types.index')
                             ->with('error', 'Cannot delete this room type because it is being used by some rooms.');
        }

        $roomType->delete();

        LogActivity::log('Delete Room Type', 'Has deleted room type: ' . $roomType->name);

        return redirect()->route('room-types.index')
                         ->with('success', 'Room Type deleted successfully!');
    }

    /**
     * Futa picha maalum ya Gallery kutoka kwenye Database na Storage
     */
    public function destroyGalleryImage($id)
    {
        // search image in database
        $image = RoomTypeImage::findOrFail($id);

        // delete right image in Storage folder
        if ($image->image_path) {
            Storage::delete('public/' . $image->image_path);
        }

        // delete also in db
        $image->delete();
        
        LogActivity::log('Delete Image', 'Has deleted: ' . $image . ' from database.');

        return back()->with('success', 'Gallery image has been deleted successfully!');
    }

    /**
     * Set Primary Image of Room Type
     */
    public function setPrimaryImage($id)
    {
        // Find selected image
        $selectedImage = RoomTypeImage::findOrFail($id);

        // Set all images from this room type to not primary first (is_primary = 0)
        RoomTypeImage::where('room_type_id', $selectedImage->room_type_id)
            ->update(['is_primary' => 0]);

        // Set selected image to primary (is_primary = 1)
        $selectedImage->update(['is_primary' => 1]);

        // Update 'image' in the table 'room_types' 
        // Set this new image to be primary in the selected room type
        $roomType = \App\Models\RoomType::find($selectedImage->room_type_id);
        if ($roomType) {
            $roomType->update(['image' => $selectedImage->image_path]);
        }

        LogActivity::log('Update Primary Image', 'Has updated primary image.');

        return back()->with('success', 'Primary image has been updated successfully!');
    }

}
