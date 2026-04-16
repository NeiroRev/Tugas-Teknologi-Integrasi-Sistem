<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContainerController extends Controller
{
    private $containers = [
        [
            "container_id" => "WH12345",
            "waste_type" => "Plastic",
            "weight_kg" => 500,
            "status" => "Active",
            "tracking_logs" => [
                ["location" => "Gudang A", "timestamp" => "2026-04-16 10:00:00", "description" => "Received"]
            ]
        ],
        [
            "container_id" => "CH98765",
            "waste_type" => "Chemical",
            "weight_kg" => 800,
            "status" => "Archived",
            "tracking_logs" => [
                ["location" => "Pabrik", "timestamp" => "2026-04-15 08:00:00", "description" => "Packed"],
                ["location" => "Disposal Unit", "timestamp" => "2026-04-16 14:00:00", "description" => "Disposed"]
            ]
        ]
    ];

    public function index()
    {
        return response()->json(["message" => "Success", "data" => $this->containers], 200);
    }
    public function search(Request $request)
    {
        $type = $request->query('type');
        $minWeight = $request->query('min_weight');

        $result = array_filter($this->containers, function($c) use ($type, $minWeight) {
            $match = true;
            if ($type && strtolower($c['waste_type']) !== strtolower($type)) $match = false;
            if ($minWeight && $c['weight_kg'] < $minWeight) $match = false;
            return $match;
        });

        return response()->json(["message" => "Search results", "data" => array_values($result)], 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'container_id' => 'required|string|regex:/^[A-Z]{2}[0-9]{5}$/',
            'waste_type' => 'required|string',
            'weight_kg' => 'required|numeric|min:10|max:5000',
            'status' => 'required|in:Active,Archived'
        ]);

        $validator->after(function ($validator) use ($request) {
            foreach ($this->containers as $c) {
                if ($c['container_id'] === $request->container_id) {
                    $validator->errors()->add('container_id', 'Container ID sudah terdaftar.');
                }
            }
            if ($request->waste_type === 'Chemical' && $request->weight_kg > 1000) {
                $validator->errors()->add('weight_kg', 'Kapasitas Chemical tidak boleh melebihi 1000 kg.');
            }
        });

        if ($validator->fails()) {
            return response()->json(["message" => "Validation failed", "errors" => $validator->errors()], 422);
        }

        return response()->json(["message" => "Container logged successfully", "data" => $request->all()], 201);
    }
    public function updateStatus($id)
    {
        return response()->json(["message" => "Container {$id} status updated to Archived"], 200);
    }

    public function destroy($id)
    {
        return response()->json(["message" => "Container {$id} deleted successfully"], 200);
    }
    public function logs($id)
    {
        foreach ($this->containers as $container) {
            if ($container['container_id'] === $id) {
                return response()->json(["message" => "Logs retrieved", "data" => $container['tracking_logs']], 200);
            }
        }
        return response()->json(["message" => "Container not found"], 404);
    }
}