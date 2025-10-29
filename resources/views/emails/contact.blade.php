<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Nouveau message depuis le portfolio</title>
</head>

<body style="font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;color:#111">
    <h2>Nouveau message depuis le portfolio</h2>

    <p><strong>Nom :</strong> {{ $name ?? '—' }}</p>
    <p><strong>Email :</strong> {{ $email ?? '—' }}</p>

    <hr>
    <div style="white-space:pre-wrap">{{ $body ?? '' }}</div>
    <hr>

    <p>— Message envoyé via le formulaire de contact du portfolio.</p>
</body>

</html>