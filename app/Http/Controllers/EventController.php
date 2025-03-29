<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Events",
 *     description="Endpoint per la gestione degli eventi"
 * )
 */
class EventController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/events",
     *     summary="Recupera la lista di tutti gli eventi",
     *     tags={"Events"},
     *     security={{"api_key":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista degli eventi",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                  @OA\Property(property="id", type="integer", example=1),
     *                  @OA\Property(property="title", type="string", example="Laravel Conference"),
     *                  @OA\Property(property="description", type="string", example="Una conferenza su Laravel."),
     *                  @OA\Property(property="scheduled_at", type="string", format="date-time", example="2025-03-30T10:00:00Z"),
     *                  @OA\Property(property="location", type="string", example="Milano"),
     *                  @OA\Property(property="max_attendees", type="integer", example=100),
     *                  @OA\Property(property="created_at", type="string", format="date-time"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time")
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return Event::with('attendees')->get();
    }

    /**
     * @OA\Post(
     *     path="/api/events",
     *     summary="Crea un nuovo evento",
     *     tags={"Events"},
     *     security={{"api_key":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","scheduled_at","location","max_attendees"},
     *             @OA\Property(property="title", type="string", example="Laravel Conference"),
     *             @OA\Property(property="description", type="string", example="Una conferenza su Laravel."),
     *             @OA\Property(property="scheduled_at", type="string", format="date-time", example="2025-03-30T10:00:00Z"),
     *             @OA\Property(property="location", type="string", example="Milano"),
     *             @OA\Property(property="max_attendees", type="integer", example=100)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Evento creato con successo",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Laravel Conference"),
     *             @OA\Property(property="description", type="string", example="Una conferenza su Laravel."),
     *             @OA\Property(property="scheduled_at", type="string", format="date-time", example="2025-03-30T10:00:00Z"),
     *             @OA\Property(property="location", type="string", example="Milano"),
     *             @OA\Property(property="max_attendees", type="integer", example=100),
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
            'title'         => 'required|string',
            'description'   => 'nullable|string',
            'scheduled_at'  => 'required|date',
            'location'      => 'required|string',
            'max_attendees' => 'required|integer|min:1',
        ]);

        $event = Event::create($data);


        return $event->load('attendees');
    }

    /**
     * @OA\Get(
     *     path="/api/events/{id}",
     *     summary="Recupera i dettagli di un evento",
     *     tags={"Events"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID dell'evento",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Dettagli dell'evento",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Laravel Conference"),
     *             @OA\Property(property="description", type="string", example="Una conferenza su Laravel."),
     *             @OA\Property(property="scheduled_at", type="string", format="date-time", example="2025-03-30T10:00:00Z"),
     *             @OA\Property(property="location", type="string", example="Milano"),
     *             @OA\Property(property="max_attendees", type="integer", example=100),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Evento non trovato"
     *     )
     * )
     */
    public function show(Event $event)
    {
        return $event->load('attendees');
    }

    /**
     * @OA\Put(
     *     path="/api/events/{id}",
     *     summary="Aggiorna un evento esistente",
     *     tags={"Events"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID dell'evento da aggiornare",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","scheduled_at","location","max_attendees"},
     *             @OA\Property(property="title", type="string", example="Laravel Conference"),
     *             @OA\Property(property="description", type="string", example="Una conferenza aggiornata su Laravel."),
     *             @OA\Property(property="scheduled_at", type="string", format="date-time", example="2025-04-01T09:00:00Z"),
     *             @OA\Property(property="location", type="string", example="Roma"),
     *             @OA\Property(property="max_attendees", type="integer", example=150)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Evento aggiornato con successo",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="title", type="string", example="Laravel Conference"),
     *             @OA\Property(property="description", type="string", example="Una conferenza aggiornata su Laravel."),
     *             @OA\Property(property="scheduled_at", type="string", format="date-time", example="2025-04-01T09:00:00Z"),
     *             @OA\Property(property="location", type="string", example="Roma"),
     *             @OA\Property(property="max_attendees", type="integer", example=150),
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
    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'scheduled_at' => 'sometimes|required|date',
            'location' => 'sometimes|required|string',
            'max_attendees' => 'sometimes|required|integer|min:1',
        ]);

        $event->update($data);
        return $event;
    }

    /**
     * @OA\Delete(
     *     path="/api/events/{id}",
     *     summary="Elimina un evento",
     *     tags={"Events"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID dell'evento da eliminare",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Evento eliminato con successo"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Evento non trovato"
     *     )
     * )
     */
    public function destroy(Event $event)
    {
        $event->attendees()->detach();
        $event->delete();
        return response()->noContent();
    }
}
