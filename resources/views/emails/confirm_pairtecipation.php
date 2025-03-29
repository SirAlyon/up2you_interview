<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Conferma partecipazione evento</title>
</head>
<body>
<p>Ciao {{ $attendee->firstname }},</p>
<p>Sei registrato all'evento: <strong>{{ $event->title }}</strong>.</p>
<p>Grazie per la partecipazione!</p>
</body>
</html>
