<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Images;
use App\Models\PropertyType;
use Illuminate\Support\Facades\Storage;
class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('images')->latest()->get();

        // Format the response to include images with URLs
        $propertiesWithImages = $properties->map(function ($property) {
            $images = $property->images->map(function ($image) {
                return [
                    'image_url' => $image->url
                ];
            });
            return [
                'id' => $property->id,
                'name' => $property->name,
                'address' => $property->address,
                'city' => $property->city,
                'state' => $property->state,
                'country' => $property->country,
                'pincode' => $property->pincode,
                'price' => $property->price,
                'bedrooms' => $property->num_bedrooms,
                'washrooms' => $property->num_bathrooms,
                'type' => $property->propertyType->type,
                'created_at' => $property->created_at,
                'images' => $images
            ];
        });

        return response()->json($propertiesWithImages);
    }

    public function featuredProperties()
    {
        $properties = Property::where('is_featured', true)->get();
        $propertiesWithImages = $properties->map(function ($property) {
            $images = $property->images->map(function ($image) {
                return [
                    'image_url' => $image->url
                ];
            });
            return [
                'id' => $property->id,
                'name' => $property->name,
                'address' => $property->address,
                'city' => $property->city,
                'state' => $property->state,
                'country' => $property->country,
                'pincode' => $property->pincode,
                'price' => $property->price,
                'bedrooms' => $property->num_bedrooms,
                'washrooms' => $property->num_bathrooms,
                'type' => $property->propertyType->type,
                'created_at' => $property->created_at,
                'images' => $images
            ];
        });

        return response()->json($propertiesWithImages);
    }

    public function store(Request $request)
    {
        // Create the property with request data
        $property = Property::create([
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'country' => $request->input('country'),
            'pincode' => $request->input('pincode'),
            'property_type_id' => $request->input('property_type_id'),
            'num_bathrooms' => $request->input('num_bathrooms'),
            'num_bedrooms' => $request->input('num_bedrooms'),
            'price' => $request->input('price'),
            'is_featured' => $request->input('is_featured', false) // Default to false if not provided
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                // Store each image and link to the property
                $path = $file->store('property_images', 'public');
                Images::create([
                    'property_id' => $property->id,
                    'image_path' => $path
                ]);
            }
        }

        // Load the images relationship and return the created property
        $propertyWithImages = $property->load('images');

        return response()->json([
            'message' => 'Property created successfully',
            'property' => $propertyWithImages
        ], 201);
    }


    public function show($id)
{
    $property = Property::with('images')->find($id);

    if (!$property) {
        // Return a 404 response if the property is not found
        return response()->json(['error' => 'Property not found'], 404);
    }

    $images = $property->images->map(function ($image) {
        return [
            'image_url' => $image->url
        ];
    });

    $propertyData = [
        'id' => $property->id,
        'name' => $property->name,
        'address' => $property->address,
        'city' => $property->city,
        'state' => $property->state,
        'country' => $property->country,
        'pincode' => $property->pincode,
        'price' => $property->price,
        'bedrooms' => $property->num_bedrooms,
        'washrooms' => $property->num_bathrooms,
        'type' => $property->propertyType->id,
        'is_featured' => $property->is_featured,
        'created_at' => $property->created_at,
        'images' => $images
    ];

    return response()->json($propertyData);
}

// Search properties based on a query
public function search(Request $request)
{
    try {
        $query = $request->query('query', '');

        $properties = Property::where('name', 'like', '%' . $query . '%')
            ->orWhere('state', 'like', '%' . $query . '%')
            ->orWhere('country', 'like', '%' . $query . '%')
            ->get();


        // Format the response to include images with URLs
        $propertiesWithImages = $properties->map(function ($property) {
            $images = $property->images->map(function ($image) {
                return [
                    'image_url' => $image->url
                ];
            });
            return [
                'id' => $property->id,
                'name' => $property->name,
                'address' => $property->address,
                'city' => $property->city,
                'state' => $property->state,
                'country' => $property->country,
                'pincode' => $property->pincode,
                'price' => $property->price,
                'bedrooms' => $property->num_bedrooms,
                'washrooms' => $property->num_bathrooms,
                'type' => $property->propertyType->type,
                'created_at' => $property->created_at,
                'images' => $images
            ];
        });

        return response()->json($propertiesWithImages);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Internal Server Error'], 500);
    }
}
public function update(Request $request, $id)
{
    // Find the property or fail if not found
    $property = Property::findOrFail($id);

    // Update property attributes
    $property->name = $request->input('name');
    $property->address = $request->input('address');
    $property->city = $request->input('city');
    $property->state = $request->input('state');
    $property->country = $request->input('country');
    $property->pincode = $request->input('pincode');
    $property->property_type_id = $request->input('property_type_id');
    $property->num_bathrooms = $request->input('num_bathrooms');
    $property->num_bedrooms = $request->input('num_bedrooms');
    $property->is_featured = $request->input('is_featured');
    $property->price = $request->input('price');
    $property->save();

    // Handle image uploads
    if ($request->hasFile('images')) {
        // Delete existing images
        $property->images()->delete();

        // Upload new images
        foreach ($request->file('images') as $file) {
            $path = $file->store('property_images', 'public');
            Images::create([
                'property_id' => $property->id,
                'image_path' => $path
            ]);
        }
    }

    // Load the images relationship and return the updated property
    $propertyWithImages = $property->load('images');

    return response()->json([
        'message' => 'Property updated successfully',
        'property' => $propertyWithImages
    ], 200);
}



    public function destroy($id)
    {
        $property = Property::findOrFail($id);
        $property->delete();

        return response()->json(null, 204);
    }

    public function latest()
    {
        $properties = Property::with('images')->latest()->get();

        // Format the response to include images with URLs
        $propertiesWithImages = $properties->map(function ($property) {
            $images = $property->images->map(function ($image) {
                return [
                    'image_url' => $image->url
                ];
            });

            return [
                'id' => $property->id,
                'name' => $property->name,
                'address' => $property->address,
                'city' => $property->city,
                'state' => $property->state,
                'country' => $property->country,
                'pincode' => $property->pincode,
                'type' => $property->propertyType->type,
                'created_at' => $property->created_at,
                'images' => $images
            ];
        });

        return response()->json($propertiesWithImages);
    }

    public function filter(Request $request)
{
    $minPrice = $request->query('minPrice');
    $maxPrice = $request->query('maxPrice');
    $typeId = $request->query('typeId');
    $startDate = $request->query('startDate');
    $endDate = $request->query('endDate');

    $query = Property::query();

    if ($minPrice) {
        $query->where('price', '>=', $minPrice);
    }
    if ($maxPrice) {
        $query->where('price', '<=', $maxPrice);
    }
    if ($typeId) {
        $query->where('property_type_id', $typeId);
    }
    if ($startDate) {
        $query->whereDate('created_at', '>=', $startDate);
    }
    if ($endDate) {
        $query->whereDate('created_at', '<=', $endDate);
    }

    $properties = $query->get();
    $propertiesWithImages = $properties->map(function ($property) {
        $images = $property->images->map(function ($image) {
            return [
                'image_url' => $image->url
            ];
        });
        return [
            'id' => $property->id,
            'name' => $property->name,
            'address' => $property->address,
            'city' => $property->city,
            'state' => $property->state,
            'country' => $property->country,
            'pincode' => $property->pincode,
            'price' => $property->price,
            'bedrooms' => $property->num_bedrooms,
            'washrooms' => $property->num_bathrooms,
            'type' => $property->propertyType->type,
            'created_at' => $property->created_at,
            'images' => $images
        ];
    });

    return response()->json($propertiesWithImages);
}

public function getPropertyTypes()
{
    $types = PropertyType::all();
    return response()->json($types);
}
}
