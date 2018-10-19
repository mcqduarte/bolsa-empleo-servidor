<?php

namespace App\Http\Controllers;

use App\Professional;
use App\Language;
use App\Offer;
use Illuminate\Http\Request;
Use Exception;
Use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
Use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProfessionalController extends Controller
{
    function filterOffers(Request $request)
    {

        //para tener varias condiciones en un array
        //$users = User::orWhere([$request->conditions])
        $data = $request->json()->all();
        $offers = Offer::orWhere('broad_field', 'like', $data['broad_field'] . '%')
            ->orWhere('specific_field', 'like', $data['specific_field'] . '%')
            ->orWhere('position', 'like', $data['position'] . '%')
            ->orWhere('remuneration', 'like', $data['remuneration'] . '%')
            ->orWhere('working_day', 'like', $data['working_day'] . '%')
            ->orderby($request->field, $request->order)
            ->paginate($request->limit);
        return response()->json([
            'pagination' => [
                'total' => $offers->total(),
                'current_page' => $offers->currentPage(),
                'per_page' => $offers->perPage(),
                'last_page' => $offers->lastPage(),
                'from' => $offers->firstItem(),
                'to' => $offers->lastItem()
            ], 'offers' => $offers], 200);

    }

    /* Metodo para obtener todas las ofertas a las que aplico el profesional*/
    function getAllOffers(Request $request)
    {

        try {
            $professional = Professional::findOrFail($request->professional_id);
            $offers = $professional->offers;
            return response()->json($offers, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json("ModelNotFoundException", 204);
        } catch (NotFoundHttpException $e) {
            return response()->json("NotFoundHttpException", 204);
        } catch (Exception $e) {
            return response()->json("Exception", 500);
        } catch (Error $e) {
            return response()->json("Error", 500);
        }

    }

    /* Metodo para filtrar a los profesionales*/
    function filterProfessionals(Request $request)
    {

        $data = $request->json()->all();
        $professionals = Professional::
        join('academic_formations', 'academic_formations.professional_id', '=', 'professionals.id')
            ->orWhere('academic_formations.professional_degree', 'like', $data['professional_degree'] . '%')
            ->orWhere('academic_formations.professional_degree', 'like', $data['professional_degree'] . '%')
            ->get();
        return $professionals;
        $professionals = Professional::orWhere('broad_field', 'like', $data['broad_field'] . '%')
            ->orWhere('specific_field', 'like', $data['specific_field'] . '%')
            ->orWhere('position', 'like', $data['position'] . '%')
            ->orWhere('remuneration', 'like', $data['remuneration'] . '%')
            ->orWhere('working_day', 'like', $data['working_day'] . '%')
            ->orderby($request->field, $request->order)
            ->paginate($request->limit);
        return response()->json([
            'pagination' => [
                'total' => $professionals->total(),
                'current_page' => $professionals->currentPage(),
                'per_page' => $professionals->perPage(),
                'last_page' => $professionals->lastPage(),
                'from' => $professionals->firstItem(),
                'to' => $professionals->lastItem()
            ], 'offers' => $professionals], 200);

    }

    /* Metodo para asignar ofertas a un profesional*/
    function createOffer(Request $request)
    {

        try {
            $professional = Professional::findOrFail($request->professional_id);
            $response = $professional->offers()->attach($request->offer_id);
            return response()->json($response, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json('ModelNotFound', 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json('NotFoundHttp', 405);
        } catch (Exception $e) {
            return response()->json('Exception', 500);
        } catch (Error $e) {
            return response()->json('Error', 500);
        }

    }

    /* Metodos para gestionar los datos personales*/

    function showProfessional($id)
    {
        try {
            $professional = Professional::where('user_id', $id)->first();
            return response()->json(['professional' => $professional], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json($e, 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json($e, 405);
        } catch (QueryException  $e) {
            return response()->json($e, 405);
        } catch (Exception $e) {
            return response()->json($e, 500);
        } catch (Error $e) {
            return response()->json($e, 500);
        }
    }

    function updateProfessional(Request $request)
    {
        try {
            $data = $request->json()->all();
            $dataProfessional = $data['professional'];
            $professional = Professional::findOrFail($dataProfessional['id'])->update([
                'identity' => $dataProfessional['identity'],
                'first_name' => $dataProfessional['first_name'],
                'last_name' => $dataProfessional['last_name'],
                'nationality' => $dataProfessional['nationality'],
                'civil_status' => $dataProfessional['civil_status'],
                'birthdate' => $dataProfessional['birthdate'],
                'gender' => $dataProfessional['gender'],
                'phone' => $dataProfessional['phone'],
                'address' => $dataProfessional['address'],
                'about_me' => $dataProfessional['about_me'],
            ]);
            return response()->json($professional, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json('ModelNotFound', 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json('NotFoundHttp', 405);
        } catch (QueryException $e) {
            return response()->json($e, 500);
        } catch (Exception $e) {
            return response()->json($e, 500);
        } catch (Error $e) {
            return response()->json($e, 500);
        }
    }

    function deleteProfessional(Request $request)
    {
        try {
            $professional = Professional::findOrFail($request->id)->delete();
            return response()->json($professional, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json('ModelNotFound', 405);
        } catch (NotFoundHttpException  $e) {
            return response()->json('NotFoundHttp', 405);
        } catch (Exception $e) {
            return response()->json('Exception', 500);
        } catch (Error $e) {
            return response()->json('Error', 500);
        }
        return response()->json(['error' => 'Unsupported Media Type'], 415, []);
    }

}
