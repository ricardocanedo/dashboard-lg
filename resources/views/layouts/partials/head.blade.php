<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard LG - Plant A</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome 6 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Favicon -->
<link rel="icon" href="{{ asset('favicon-lg.png') }}" type="image/x-icon">

<!-- Custom Styles -->
<style>
    :root {
        --lg-red: #EA1917;
        --lg-dark: #1a1a1a;
    }

    body {
        background: #F0ECE4;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .navbar-brand {
        color: var(--lg-red) !important;
        font-weight: bold;
        font-size: 1.5rem;
    }

    .card {
        border: none;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .card-header {
        background: var(--lg-red);
        color: white;
        font-weight: bold;
    }

    .btn-lg-primary {
        background: var(--lg-red);
        border-color: var(--lg-red);
        color: white;
    }

    .btn-lg-primary:hover {
        background: white;
        border-color: var(--lg-dark);
    }

    .main-content {
        min-height: 81.5vh;
    }
</style>
