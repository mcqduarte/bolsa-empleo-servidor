<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Professional;

class ProfessionalReferenceController extends Controller
{
    function getAllProfessionalReferences(Request $request)
    {
        if ($request->isJson()) {
            try {
                $professionalReferences = ProfessionalReference::orderby($request->field, $request->order)->paginate($request->limit);
                return response()->json([
                    'pagination' => [
                        'total' => $professionalReferences->total(),
                        'current_page' => $professionalReferences->currentPage(),
                        'per_page' => $professionalReferences->perPage(),
                        'last_page' => $professionalReferences->lastPage(),
                        'from' => $professionalReferences->firstItem(),
                        'to' => $professionalReferences->lastItem()
                    ], 'professionalReferences' => $professionalReferences], 200);
            } catch (ModelNotFoundException $e) {
                return response()->json('ModelNotFound', 200);
            } catch (NotFoundHttpException  $e) {
                return response()->json('NotFoundHttp', 200);
            } catch (Exception $e) {
                return response()->json('Exception', 500);
            } catch (Error $e) {
                return response()->json('Error', 500);
            }
        }
        return response()->json(['error' => 'Unsupported Media Type'], 415, []);
    }

    function showProfessionalReference($id)
    {
        try {
            $professionalReference = ProfessionalReference::findOrFail($id);
            return response()->json($professionalReference, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json('ModelNotFound', 200);
        } catch (NotFoundHttpException  $e) {
            return response()->json('NotFoundHttp', 200);
        } catch (Exception $e) {
            return response()->json('Exception', 500);
        } catch (Error $e) {
            return response()->json('Error', 500);
        }
    }

    function createProfessionalReference(Request $request)
    {
        if ($request->isJson()) {
            try {
                $data = $request->json()->all();
                $professional = Professional::findOrFail($request->professional_id);
                $response = $professional->professionalReferences()->create([
                    'description' => $data['description'],
                    'written_level' => $data['written_level'],
                    'spoken_level' => $data['spoken_level'],
                    'reading_level' => $data['reading_level'],
                ]);
                return response()->json($response, 201);
            } catch (ModelNotFoundException $e) {
                return response()->json('ModelNotFound', 200);
            } catch (NotFoundHttpException  $e) {
                return response()->json('NotFoundHttp', 200);
            } catch (Exception $e) {
                return response()->json('Exception', 500);
            } catch (Error $e) {
                return response()->json('Error', 500);
            }
        }
        return response()->json(['error' => 'Unsupported Media Type'], 415, []);
    }

    function updateProfessionalReference(Request $request)
    {
        if ($request->isJson()) {
            try {
                $data = $request->json()->all();
                $professionalReference = ProfessionalReference::findOrFail($data['id'])->update([
                    'description' => $data['description'],
                    'written_level' => $data['written_level'],
                    'spoken_level' => $data['spoken_level'],
                    'reading_level' => $data['reading_level'],
                ]);
                return response()->json($professionalReference, 201);
            } catch (ModelNotFoundException $e) {
                return response()->json('ModelNotFound', 200);
            } catch (NotFoundHttpException  $e) {
                return response()->json('NotFoundHttp', 200);
            } catch (Exception $e) {
                return response()->json('Exception', 500);
            } catch (Error $e) {
                return response()->json('Error', 500);
            }
        }
        return response()->json(['error' => 'Unsupported Media Type'], 415, []);
    }

    function deleteProfessionalReference(Request $request)
    {
        if ($request->isJson()) {
            try {
                $professionalReference = ProfessionalReference::findOrFail($request->id)->delete();
                return response()->json($professionalReference, 201);
            } catch (ModelNotFoundException $e) {
                return response()->json('ModelNotFound', 200);
            } catch (NotFoundHttpException  $e) {
                return response()->json('NotFoundHttp', 200);
            } catch (Exception $e) {
                return response()->json('Exception', 500);
            } catch (Error $e) {
                return response()->json('Error', 500);
            }
        }
        return response()->json(['error' => 'Unsupported Media Type'], 415, []);
    }
}