<?php

namespace App\Http\Controllers;

use App\Models\Boiler;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BoilerController extends Controller
{
    // GET /api/boilers
    public function index(Request $request)
    {
        // Filter by manufacturer name
        $query = Boiler::with(['manufacturer', 'fuelType']);

        if ($request->has('manufacturer')) {
            $query->whereHas('manufacturer', function ($q) use ($request) {
                $q->where('boiler_manufacturer_id', $request->manufacturer)
                  ->orWhere('name', $request->manufacturer);
            });
        }

        // Filter by fuel type name
        if ($request->has('fuel_type')) {
            $query->whereHas('fuelType', function ($q) use ($request) {
                $q->where('name', $request->fuel_type)
                  ->orWhere('fuel_type_ref', $request->fuel_type);
            });
        }

        return response()->json($query->get());
    }

    // GET /api/boilers/{id}
    public function show($id)
    {
        $boiler = Boiler::with(['manufacturer', 'fuelType'])->find($id);

        if (!$boiler) {
            return response()->json([
                'error' => 'Boiler not found.',
                'boiler_id' => $id
            ], 404);
        }

        return response()->json($boiler);
    }

    // POST /api/boilers
    public function store(Request $request)
    {
            $validator = \Validator::make($request->all(),[
                'name' => 'required|string',
                'manufacturer_part_number' => 'required|string',
                'boiler_manufacturer_id' => 'required|exists:boiler_manufacturers,id',
                'fuel_type_id' => 'required|exists:boiler_fuel_types,id',
                'description' => 'nullable|string',
                'sku' => 'nullable|string',
            ]);


           if ($validator->fails()) {
    		return response()->json([
        	  'error' => 'Validation failed.',
        	  'details' => $validator->errors(),
    		], 422);
           }

           $data = $validator->validated();

           $boiler = Boiler::create($data);

           return response()->json($boiler, 201);
    }

    // PUT /api/boilers/{id}
    public function update(Request $request, $id)
    {
        try {
            $boiler = Boiler::findOrFail($id);

            $data = $request->validate([
                'name' => 'sometimes|string',
                'manufacturer_part_number' => 'sometimes|string',
                'boiler_manufacturer_id' => 'sometimes|exists:boiler_manufacturers,id',
                'fuel_type_id' => 'sometimes|exists:fuel_types,id',
                'description' => 'nullable|string',
                'sku' => 'nullable|string',
            ]);

            if (isset($data['name'])) {
                $data['url'] = \Illuminate\Support\Str::slug($data['name']);
            }

            $boiler->update($data);

            return response()->json([
                'message' => 'Boiler updated successfully.',
                'boiler' => $boiler,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => "Boiler with ID {$id} not found."
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed.',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }


    // DELETE /api/boilers/{id}
    public function destroy($id)
    {
        try {
            $boiler = Boiler::findOrFail($id);
            $boiler->delete();

            return response()->json([
                'message' => "Boiler ID {$id} soft deleted."
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => "Boiler with ID {$id} not found."
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Could not delete boiler.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function trashed()
    {
        $boilers = Boiler::onlyTrashed()->with(['manufacturer', 'fuelType'])->get();

        if ($boilers->isEmpty()) {
            return response()->json([
               'message' => 'No trashed boilers found.'
           ], 404);
        }

        return response()->json($boilers);
    }

    public function restore($id)
    {
        $boiler = Boiler::onlyTrashed()->find($id);

        if (!$boiler) {
            return response()->json([
                'error' => "Boiler with ID {$id} not found in trash."
            ], 404);
        }

        $boiler->restore();

        return response()->json([
            'message' => "Boiler ID {$id} restored successfully.",
            'boiler' => $boiler
        ]);
    }

}
