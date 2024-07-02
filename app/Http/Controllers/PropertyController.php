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
        $properties = Property::with('images')->get();

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

    public function store(Request $request)
    {
        $property = Property::create($request->all());

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('property_images', 'public');
                Images::create([
                    'property_id' => $property->id,
                    'image_path' => $path
                ]);
            }
        }

        return response()->json($property->load('images'), 201);
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
        'type' => $property->propertyType->type,
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
    $property = Property::findOrFail($id);

    // Update the property with the request data
    $property->update($request->all());

    // Check if the request has any images and process them
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $path = $file->store('property_images', 'public');
            Images::create([
                'property_id' => $property->id,
                'image_path' => $path
            ]);
        }
    }

    // Load the images relationship and return the updated property
    return response()->json($property->load('images'), 200);
}


    public function destroy($id)
    {
        $property = Property::findOrFail($id);
        $property->delete();

        return response()->json(null, 204);
    }

    public function latest()
    {
        $properties = Property::with('images')->orderBy('id','Desc')->get();

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
