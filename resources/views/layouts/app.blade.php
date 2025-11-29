<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>PensHelpDesk</title>
</head>
<body>

    <x-header />

    <div class="pt-32">
        @yield('content')
    </div>

</body>
</html>