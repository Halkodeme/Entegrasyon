@extends('layouts.app')

@php
    // status_code 100 ise başarılı kabul ediyoruz
    $statusCode = request('data.status_code');
    $isSuccess = ($statusCode == 100);

    // Dinamik sınıfları belirleyelim
    $headerClass = $isSuccess ? 'bg-success' : 'bg-danger';
    $alertClass = $isSuccess ? 'border-success text-success' : 'border-danger text-danger';
    $badgeClass = $isSuccess ? 'badge-success' : 'badge-danger';
    $title = $isSuccess ? 'İşlem Başarılı' : 'İşlem Başarısız';
@endphp

@section('title', $title)

@section('content')
    <div class="container mt-5">
        <div class="card bg-dark text-white shadow">
            {{-- Header Rengi Dinamik --}}
            <div class="card-header {{ $headerClass }}">
                <h1 class="h4 mb-0">{{ $title }}!</h1>
            </div>

            <div class="card-body">
                <p class="{{ $isSuccess ? 'text-light' : 'text-warning' }}">
                    {{ $isSuccess
                        ? 'İşleminiz başarıyla tamamlanmıştır.'
                        : 'İşlem gerçekleştirilirken bir hata oluştu. Lütfen detayları kontrol ediniz.'
                    }}
                </p>
                <hr class="bg-secondary">

                {{-- Banka Mesajı (Açıklama) --}}
                @if(request('data.status_description'))
                    <div class="alert alert-outline-{{ $isSuccess ? 'success' : 'danger' }} {{ $alertClass }} mb-4">
                        <strong>Banka Mesajı:</strong> {{ request('data.status_description') }}
                    </div>
                @endif

                <div class="row">
                    {{-- Sipariş No --}}
                    @if(request('data.order_no'))
                        <div class="col-md-6 result-item mb-2">
                            <span class="font-weight-bold text-info">Sipariş No:</span> {{ request('data.order_no') }}
                        </div>
                    @endif

                    {{-- Fatura ID --}}
                    @if(request('data.invoice_id'))
                        <div class="col-md-6 result-item mb-2">
                            <span class="font-weight-bold text-info">Fatura ID:</span> {{ request('data.invoice_id') }}
                        </div>
                    @endif

                    {{-- Durum Kodu --}}
                    @if($statusCode)
                        <div class="col-md-6 result-item mb-2">
                            <span class="font-weight-bold text-info">Durum Kodu:</span>
                            <span class="badge {{ $badgeClass }}">{{ $statusCode }}</span>
                        </div>
                    @endif

                    {{-- Varsa İade Edilen Tutar --}}
                    @if(request('data.total_refunded_amount'))
                        <div class="col-md-6 result-item mb-2">
                            <span class="font-weight-bold text-info">İade Tutarı:</span>
                            <span class="text-success">{{ number_format(request('data.total_refunded_amount'), 2) }} TRY</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">

                <a href="{{ route('check-status.index') }}" class="btn btn-warning">Tekrar Dene</a>

                <a href="/" class="btn btn-outline-light">Ana Sayfaya Dön</a>
            </div>
        </div>
    </div>
@endsection
