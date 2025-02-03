<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{app()->getLocale() == 'ar'? 'rtl': 'ltr'}}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>@yield('title', 'Export')</title>

    <style>
        @page {
                /* padding-top: 65mm;            Set the top padding to 50mm */
                /* padding-bottom: 40mm;            Set the bottom padding */
                /* padding-left: 15mm;            Set the left padding */
                /* padding-right: 15mm;            Set the right padding */
                /* background-image: url('{{ asset("assets/img/ReportBackground.jpg") }}') !important; */
                /* background-size: 210mm 297mm; */
                /* background-repeat: repeat-y; */
            }
           
            body{
               
                /* background-image: url('{{ asset("assets/img/ReportBackground.jpg") }}'); */
                /* background-size: 210mm 297mm; */
                margin: 0;
                padding: 0.39in;
                width: 210mm;
                min-height: 297mm;

            }
            
    </style>
    
    @stack('styles') <!-- Allows child views to push additional styles -->
</head>

<body>
    <div class="export-container w-full px-5">
        @yield('content')
    </div>
</body>

</html>
