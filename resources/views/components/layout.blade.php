{{-- resources/views/components/layout.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>{{ $title ?? 'Secret Party 2.0' }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('public/css/style.css') }}">
  
</head>
<body>
  <div class="screen" role="application">
    <div class="screen-overlay">
        {{ $slot }}
    </div>
  </div>
</body>
</html>
