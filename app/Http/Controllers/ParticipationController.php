<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmParticipationMail;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * @OA\Tag(
 *     name="Participation",
 *     description="Endpoint per la registrazione degli iscritti agli eventi"
 * )
 */
class ParticipationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/events/{event_id}/register",
     *     summary="Registra un iscritto a un evento",
     *     tags={"Participation"},
     *     security={{"api_key":{}}},
     *     @OA\Parameter(
     *         name="event_id",
     *         in="path",
     *         description="ID dell'evento",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"attendee_id"},
     *             @OA\Property(property="attendee_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Registrazione completata con successo"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Errore: limite di partecipanti raggiunto o iscritto già registrato",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Numero massimo di partecipanti raggiunto")
     *         )
     *     )
     * )
     */
    public function register(Request $request, Event $event)
    {
        $data = $request->validate([
            'attendee_id' => 'required|exists:attendees,id',
        ]);

        $attendee = Attendee::find($data['attendee_id']);

        //Controllo partecipanti
        if ($event->attendees()->count() >= $event->max_attendees) {
            return response()->json(['message' => 'Numero massimo di partecipanti raggiunto'], 400);
        }

        //Controllo Utentr già registrato
        if ($event->attendees()->where('attendee_id', $attendee->id)->exists()) {
            return response()->json(['message' => 'Iscritto già registrato per questo evento'], 400);
        }

        $event->attendees()->attach($attendee->id);

        //Spedisci mail
        Mail::to($attendee->email)->send(new ConfirmParticipationMail($attendee, $event));

        return response()->noContent();
    }
}
