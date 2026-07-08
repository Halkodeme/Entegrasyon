@extends('layouts.app')

@section('title', 'İşlem Başarısız')

@section('content')
    <div class="container mt-5">
        <div class="card bg-dark text-white shadow">
            <div class="card-header bg-danger">
                <h1 class="h4 mb-0">İşlem Başarısız!</h1>
            </div>
            <div class="card-body">
                <p class="text-warning">Ödeme işlemi sırasında bir hata oluştu. Lütfen bilgilerinizi kontrol edip tekrar deneyiniz.</p>
                <hr class="bg-secondary">

                <div class="result-item mb-2">
                    <span class="font-weight-bold text-info">Sipariş No (order_no):</span> {{ request('order_no', 'N/A') }}
                </div>
                <div class="result-item mb-2">
                    <span class="font-weight-bold text-info">Sipariş ID (order_id):</span> {{ request('order_id', 'N/A') }}
                </div>
                <div class="result-item mb-2">
                    <span class="font-weight-bold text-info">Fatura ID (invoice_id):</span> {{ request('invoice_id', 'N/A') }}
                </div>
                <div class="result-item mb-2">
                    <span class="font-weight-bold text-info">Hata Kodu (status_code):</span>
                    <span class="badge badge-danger">{{ request('status_code') }}</span>
                </div>
                <div class="result-item mb-2">
                    <span class="font-weight-bold text-info">İşlem Tipi:</span> {{ request('transaction_type') }}
                </div>
                <div class="result-item mb-2">
                    <span class="font-weight-bold text-info">Ödeme Durumu:</span> {{ request('payment_status') }}
                </div>
                <div class="result-item mb-2">
                    <span class="font-weight-bold text-info">3D Durumu (md_status):</span> {{ request('md_status') }}
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('payment.index') }}" class="btn btn-warning">Tekrar Dene</a>
                <a href="/" class="btn btn-outline-light">Ana Sayfaya Dön</a>
            </div>
        </div>
    </div>
@endsection
