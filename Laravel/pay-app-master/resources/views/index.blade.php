<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ödeme Ekranı')</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    @include('layouts.style_index')
</head>
<body>
<div class="background-image"></div>

@include('layouts.nav')

<div class="content">
    @yield('content')
    <div class="container mt-5">
        <div class="content text-md-left ml-md-5" style="padding-left: 15px;">
            <h3 class="text-white">Halk Elektronik Para ve Ödeme Hizmetleri</h3>
            <h3 class="text-white">Laravel Entegrasyonuna Hoş Geldiniz</h3>
        </div>
    </div>
</div>

@include('layouts.footer')

</body>
</html>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajax({
            url: "{{ route('get-token') }}",
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log("Token Durumu: Başarılı",response);
            },
            error: function() {
                console.log("Token Durumu: Alınamadı");
            }
        });
    });
</script>
