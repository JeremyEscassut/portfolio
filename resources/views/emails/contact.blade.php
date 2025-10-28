<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Nouveau message depuis le portfolio</title>
</head>

<body style="font-family:system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial,sans-serif;color:#111">
    <h2>Nouveau message depuis le portfolio</h2>

    @if(!empty($payload['name']))
    <p><strong>Nom :</strong> {{ e($payload['name']) }}</p>
    @endif

    <p><strong>Email :</strong> {{ e($payload['email']) }}</p>

    <hr>

    <div style="white-space:pre-wrap">{!! nl2br(e($payload['message'])) !!}</div>

    <hr>
    <p>— Message envoyé via le formulaire de contact du portfolio.</p>
</body>

</html>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Contact</title>
</head>

<body>
    <h2>Nouveau message depuis le portfolio</h2>

    @if(!empty($payload['name']))
    <p><strong>Nom :</strong> {{ e($payload['name']) }}</p>
    @endif

    <p><strong>Email :</strong> {{ e($payload['email']) }}</p>

    <hr>

    <p>{!! nl2br(e($payload['message'])) !!}</p>

    <hr>
    <p>— Ce message a été envoyé depuis le formulaire de contact du portfolio.</p>
</body>

</html>