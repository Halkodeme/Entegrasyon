@extends('layouts.app')

@section('title', 'Test Kart Parametreleri')

@section('content')
    <div class="container mt-4 mb-5">
        <div class="row">
            <div class="col-12 mb-4">
                <h2 class="h3 mb-0" style="color:white">Test Kart Bilgileri</h2>
                <hr class="mt-2">
                <p class="text-muted small text-white">Entegrasyon süreçlerinde başarılı işlem simülasyonu için aşağıdaki kartları kullanabilirsiniz.</p>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm" style="background: rgba(255, 255, 255, 0.85); border-radius: 12px;">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="card-title font-weight-bold" style="color:#17456d">
                            <i class="fas fa-credit-card mr-2"></i> Test Kart 1
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                <span class="font-weight-bold text-muted">Kart Sahibi:</span>
                                <span>John Dao</span>
                            </li>
                            <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                <span class="font-weight-bold text-muted">Kart Numarası:</span>
                                <span class="text-primary font-weight-bold">4155141111113339</span>
                            </li>
                            <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                <span class="font-weight-bold text-muted">S.K.T:</span>
                                <span>12 / 29</span>
                            </li>
                            <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                <span class="font-weight-bold text-muted">CVV:</span>
                                <span>555</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow-sm" style="background: rgba(255, 255, 255, 0.85); border-radius: 12px;">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="card-title font-weight-bold" style="color:#17456d">
                            <i class="fas fa-credit-card mr-2"></i> Test Kart 2
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                <span class="font-weight-bold text-muted">Kart Sahibi:</span>
                                <span>Test Test</span>
                            </li>
                            <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                <span class="font-weight-bold text-muted">Kart Numarası:</span>
                                <span class="text-primary font-weight-bold">5406671111112026</span>
                            </li>
                            <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                <span class="font-weight-bold text-muted">S.K.T:</span>
                                <span>11 / 28</span>
                            </li>
                            <li class="list-group-item bg-transparent d-flex justify-content-between px-0">
                                <span class="font-weight-bold text-muted">CVV:</span>
                                <span>555</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
