<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Professional;

class AbilityController extends Controller
{
    function getAllAbilities(Request $request)
    {
        if ($request->isJson()) {
            try {
                $abilities = Ability::orderby($request->field, $request->order)->paginate($request->limit);
                return response()->json([
                    'pagination' => [
                        'total' => $abilities->total(),
                        'current_page' => $abilities->currentPage(),
                        'per_page' => $abilities->perPage(),
                        'last_page' => $abilities->lastPage(),
                        'from' => $abilities->firstItem(),
                        'to' => $abilities->lastItem()
                    ], 'abilities' => $abilities], 200);
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

    function showAbility($id)
    {
        try {
            $ability = Ability::findOrFail($id);
            return response()->json($ability, 200);
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

    function createAbility(Request $request)
    {
        if ($request->isJson()) {
            try {
                $data = $request->json()->all();
                $professional = Professional::findOrFail($request->professional_id);
                $response = $professional->abilities()->create([
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

    function updateAbility(Request $request)
    {
        if ($request->isJson()) {
            try {
                $data = $request->json()->all();
                $ability = Ability::findOrFail($data['id'])->update([
                    'description' => $data['description'],
                    'written_level' => $data['written_level'],
                    'spoken_level' => $data['spoken_level'],
                    'reading_level' => $data['reading_level'],
                ]);
                return response()->json($ability, 201);
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

    function deleteAbility(Request $request)
    {
        if ($request->isJson()) {
            try {
                $ability = Ability::findOrFail($request->id)->delete();
                return response()->json($ability, 201);
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