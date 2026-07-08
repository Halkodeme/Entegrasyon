@extends('layouts.app')

@php
    $data = request('data') ?? $data ?? [];
    $statusCode = $data['status_code'] ?? null;
    $isSuccess = ($statusCode == 100);

    // Dinamik sınıflar (Senin sade örneğindeki gibi)
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
                @if(isset($data['status_description']))
                    <div class="alert alert-outline-{{ $isSuccess ? 'success' : 'danger' }} {{ $alertClass }} mb-4" style="background: transparent; border: 1px solid;">
                        <strong>Banka Mesajı:</strong> {{ $data['status_description'] }}
                    </div>
                @endif

                <div class="row">
                    {{-- Fatura ID (Invoice ID) --}}
                    @if(isset($data['invoice_id']))
                        <div class="col-md-6 mb-2">
                            <span class="font-weight-bold text-info">Fatura ID:</span> {{ $data['invoice_id'] }}
                        </div>
                    @endif

                    {{-- Sipariş No (Order ID) --}}
                    @if(isset($data['order_id']) && $data['order_id'] !== "")
                        <div class="col-md-6 mb-2">
                            <span class="font-weight-bold text-info">Sipariş No:</span> {{ $data['order_id'] }}
                        </div>
                    @endif

                    {{-- İşlem Durumu (Transaction Status) --}}
                    @if(isset($data['transaction_status']))
                        <div class="col-md-6 mb-2">
                            <span class="font-weight-bold text-info">İşlem Durumu:</span> {{ $data['transaction_status'] }}
                        </div>
                    @endif

                    {{-- Durum Kodu --}}
                    @if($statusCode)
                        <div class="col-md-6 mb-2">
                            <span class="font-weight-bold text-info">Durum Kodu:</span>
                            <span class="badge {{ $badgeClass }}">{{ $statusCode }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                {{-- Hata durumunda senin rotan: confirm-payment.index --}}
                @if(!$isSuccess)
                    <a href="{{ route('confirm-payment.index') }}" class="btn btn-warning">Tekrar Dene</a>
                @else
                    <button onclick="window.print()" class="btn btn-info text-white">Yazdır / Dekont</button>
                @endif

                <a href="/" class="btn btn-outline-light">Ana Sayfaya Dön</a>
            </div>
        </div>
    </div>
@endsection
