<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Poppins:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500&display=swap" rel="stylesheet">
</head>

<body style="display: flex; justify-content: center; align-items: center; font-family: 'Inter', sans-serif; font-family: 'Poppins', sans-serif; background-color: #e5e5e5;">
    <table style="width: 100%; background-color: #fff; border-collapse: collapse">
        <tr>
            <td style="padding: 8px 34px 18px 34px;">
                @yield('content')
            </td>
        </tr>
    </table>
</body>

</html>
