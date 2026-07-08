@extends('layouts.app')

@section('title', 'Ödeme Tamamlama')

@section('content')
    <div class="container mt-4 mb-5">
        <div class="card shadow-lg border-0" style="background: rgba(255, 255, 255, 0.9); border-radius: 15px;">
            <div class="card-body p-4">

                <h2 class="h4 text-dark mb-4 border-bottom pb-2">Ödeme Tamamlama</h2>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('confirm-payment') }}" method="POST" id="confirm-payment-form">
                    @csrf
                    <input type="hidden" name="hash_key" id="hash_key" value="">

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="invoice_id" class="font-weight-bold text-dark small text-uppercase">Fatura Numarası</label>
                            <input type="text" class="form-control bg-light" id="invoice_id" name="invoice_id" value="">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="total" class="font-weight-bold text-dark small text-uppercase">Tutar</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="total" name="total" placeholder="0.00" required
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, '');">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-white">TL</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success btn-lg btn-block shadow" style="height: 55px; font-weight: bold; font-size: 1.1rem;">
                                <i class="fas fa-shield-alt mr-2"></i> Ödemeyi Tamamla
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
