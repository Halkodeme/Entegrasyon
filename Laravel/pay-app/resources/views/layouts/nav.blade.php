<nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand mx-auto" style="height: 60px; " href="{{ route('payment.index') }}">
                <img src="{{ asset('assets/LOGO.png') }}" alt="Ödeme Sistemi" style="height: 50px; width:150px;">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('payment.index') }}">Anasayfa</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Entegrasyonlar
                        </a>
                        <div class='dropdown-menu' aria-labelledby='navbarDropdown'>
                            <a class='dropdown-item' href="{{ route('payment-2d.index') }}">2D Entegrasyonu</a>
                            <a class="dropdown-item" href="{{ route('payment-3d.index') }}">3D Entegrasyonu</a>
                            <a class='dropdown-item' href="{{ route('purchase-link.index') }}">Link ile Ödeme Entegrasyonu</a>
                            <a class='dropdown-item' href={{ route('refund-view.index') }}>İade Entegrasyonu</a><!-- refund -->
                            <a class='dropdown-item' href='{{ route('check-status.index') }}'>Ödeme Sorgulama</a> <!-- checkstatus -->
                            <a class='dropdown-item' href='{{ route('confirm-payment.index') }}'>Ödeme Tamamlama</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
