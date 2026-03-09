<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nim' => 'required|digits:15',
                'nama' => 'required|string|max:50',
                'mataKuliah' => 'required|array',
                'mataKuliah.*.kode' => 'required|regex:/^[A-Z]{3}[0-9]{5}$/',
                'mataKuliah.*.nama' => 'required|string|max:50',
                'mataKuliah.*.sks' => 'required|numeric|min:1|max:6',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        return response()->json([
            'message' => 'Student created successfully (dummy)',
            'data' => $validated,
        ], 201);
    }
    
    public function index()
    {
        $students = [
            [
                "nim" => "123456789012345", 
                "nama" => "Citra Dewi", 
                "mataKuliah" => [ 
                    ["kode" => "CIE61205", "nama" => "PemWeb", "sks" => 3], 
                    ["kode" => "COM60015", "nama" => "MatDis", "sks" => 2] 
                ]
            ],
            [
                "nim" => "123456789012346", 
                "nama" => "Andy Lau", 
                "mataKuliah" => [ 
                    ["kode" => "CIE61205", "nama" => "PemWeb", "sks" => 3], 
                    ["kode" => "CIE61206", "nama" => "JarKom", "sks" => 3], 
                    ["kode" => "CIE61208", "nama" => "BasDat", "sks" => 3] 
                ]
            ]
        ];
        return response()->json($students); 
    }

    public function update(Request $request, $nim)
    {
        try {
            $validated = $request->validate([
                'nama' => 'sometimes|required|string|max:50',
                'mataKuliah' => 'sometimes|required|array',
                'mataKuliah.*.kode' => 'sometimes|required|regex:/^[A-Z]{3}[0-9]{5}$/',
                'mataKuliah.*.nama' => 'sometimes|required|string|max:50',
                'mataKuliah.*.sks' => 'sometimes|required|numeric|min:1|max:6',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                "message" => "Validation failed",
                "errors" => $e->validator->errors()
            ], 422);
        }
        return response()->json([
            "message" => "Student {$nim} updated successfully (dummy)",
            "data" => array_merge(['nim' => $nim], $validated)
        ]);
    }

    public function destroy($nim)
    {
        return response()->json([
            "message" => "Student {$nim} deleted successfully (dummy)"
        ]);
    }
    
}

