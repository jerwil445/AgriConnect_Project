<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard with Proper Alignment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-transition {
            transition: all 0.3s ease;
        }

        .content-transition {
            transition: margin-left 0.3s ease;
        }

        @media (max-width: 768px) {
            .sidebar-mobile {
                transform: translateX(-100%);
            }

            .sidebar-mobile.active {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">

    {{-- sidebar and Header --}}
    @include('partials.admin.sidebar_and_header')

    {{-- main  --}}
    @include('admin.dashboard')



</body>

</html>
