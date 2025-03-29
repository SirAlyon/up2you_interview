<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Attendees",
 *     description="Endpoint per la gestione degli iscritti"
 * )
 */
class AttendeeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/attendees",
     *     summary="Recupera la lista di tutti gli iscritti",
     *     tags={"Attendees"},
     *     security={{"api_key":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista degli iscritti",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="firstname", type="string", example="Mario"),
     *                  @OA\Property(property="lastname", type="string", example="Rossi"),
     *                  @OA\Property(property="email", type="string", example="mario.rossi@example.com"),
     *                  @OA\Property(property="created_at", type="string", format="date-time"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return Attendee::with('events')->get();
    }

    /**
     * @OA\Post(
     *     path="/api/attendees",
     *     summary="Crea un nuovo iscritto",
     *     tags={"Attendees"},
     *     security={{"api_key":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"firstname","lastname","email"},
     *             @OA\Property(property="firstname", type="string", example="Mario"),
     *             @OA\Property(property="lastname", type="string", example="Rossi"),
     *             @OA\Property(property="email", type="string", example="mario.rossi@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Iscritto creato con successo",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="firstname", type="string", example="Mario"),
     *             @OA\Property(property="lastname", type="string", example="Rossi"),
     *             @OA\Property(property="email", type="string", example="mario.rossi@example.com"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Errore di validazione"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'firstname'   => 'required|string',
            'lastname'    => 'required|string',
            'email'       => 'required|email|unique:attendees,email',
        ]);

        $attendee = Attendee::create($data);

        return $attendee->load('events');
    }

    /**
     * @OA\Get(
     *     path="/api/attendees/{id}",
     *     summary="Recupera i dettagli di un iscritto",
     *     tags={"Attendees"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID dell'iscritto",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dettagli dell'iscritto",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="firstname", type="string", example="Mario"),
     *             @OA\Property(property="lastname", type="string", example="Rossi"),
     *             @OA\Property(property="email", type="string", example="mario.rossi@example.com"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Iscritto non trovato"
     *     )
     * )
     */
    public function show(Attendee $attendee)
    {
        return $attendee->load('events');
    }

    /**
     * @OA\Put(
     *     path="/api/attendees/{id}",
     *     summary="Aggiorna un iscritto esistente",
     *     tags={"Attendees"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID dell'iscritto da aggiornare",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"firstname","lastname","email"},
     *             @OA\Property(property="firstname", type="string", example="Mario"),
     *             @OA\Property(property="lastname", type="string", example="Rossi"),
     *             @OA\Property(property="email", type="string", example="mario.rossi@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Iscritto aggiornato con successo",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="firstname", type="string", example="Mario"),
     *             @OA\Property(property="lastname", type="string", example="Rossi"),
     *             @OA\Property(property="email", type="string", example="mario.rossi@example.com"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Errore di validazione"
     *     )
     * )
     */
    public function update(Request $request, Attendee $attendee)
    {
        $data = $request->validate([
            'firstname'   => 'sometimes|required|string',
            'lastname'    => 'sometimes|required|string',
            'email'       => 'sometimes|required|email|unique:attendees,email,' . $attendee->id,

        ]);

        $attendee->update($data);

        return $attendee->load('events');
    }

    /**
     * @OA\Delete(
     *     path="/api/attendees/{id}",
     *     summary="Elimina un iscritto",
     *     tags={"Attendees"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID dell'iscritto da eliminare",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Iscritto eliminato con successo"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Iscritto non trovato"
     *     )
     * )
     */
    public function destroy(Attendee $attendee)
    {
        $attendee->events()->detach();
        $attendee->delete();
        return response()->noContent();
    }
}
