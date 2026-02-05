<!DOCTYPE html>
<html lang="pt-BR">

<head>
    @include('layouts.partials.head')
</head>

<body>
    @include('layouts.partials.navbar')

    <main class="container-fluid main-content px-4">
        @include($mainView ?? 'layouts.main.default')
    </main>

    @include('layouts.partials.footer')
</body>

</html>
