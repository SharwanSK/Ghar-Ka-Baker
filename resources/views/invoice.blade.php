<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Invoice | Ghar Ka Baker</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      rel="stylesheet"
    />
    <link href="/assets/css/style.css" rel="stylesheet" />
  </head>
  <body>
    <div class="app-shell">
      <aside id="sidebar" class="sidebar"></aside>
      <main class="main-content">
        <header class="topbar no-print">
          <div class="d-flex gap-2">
            <button class="icon-button mobile-menu" onclick="toggleSidebar()">
              <i class="bi bi-list"></i>
            </button>
            <div>
              <h1 class="page-title">Invoice</h1>
              <p class="page-subtitle">
                A professional, print-ready order invoice.
              </p>
            </div>
          </div>
          <div class="top-actions">
            <select class="form-select language-select">
              <option>English</option>
              <option>Urdu</option>
              <option>Roman Urdu</option></select
            ><button class="theme-button" type="button"></button
            ><button class="btn btn-gradient" onclick="window.print()">
              <i class="bi bi-printer"></i> Print Invoice
            </button>
          </div>
        </header>

        @php
          $paidTotal = $order->payments->sum('amount');
          $remaining = $order->total_amount - $paidTotal;
          $lastPayment = $order->payments->sortByDesc('id')->first();
        @endphp

        <article class="card invoice-card">
          <div
            class="invoice-top d-flex flex-column flex-sm-row justify-content-between gap-4"
          >
            <div>
              <div class="invoice-brand">
                <i class="bi bi-cake2 me-2"></i>Ghar Ka Baker
              </div>
              <p class="mb-0 mt-2 opacity-75">
                Freshly baked happiness, delivered with love.
              </p>
            </div>
            <div class="text-sm-end">
              <h2 class="mb-1 fw-bold">INVOICE</h2>
              <span class="invoice-number">#{{ $order->order_number }} · {{ $order->created_at ? \Carbon\Carbon::parse($order->created_at)->format('d F Y') : '' }}</span>
            </div>
          </div>
          <div class="card-body p-md-5">
            <div class="row g-4 mb-4">
              <div class="col-md-6">
                <p class="text-muted-custom text-uppercase small fw-bold mb-2">
                  Bill To
                </p>
                <h3 class="h5 fw-bold mb-1">{{ $order->customer->name ?? '—' }}</h3>
                <p class="text-muted-custom mb-0">
                  {{ $order->delivery_address ?? $order->customer->address ?? '' }}<br />
                  {{ $order->delivery_city ?? $order->customer->city ?? '' }}<br />
                  {{ $order->customer->whatsapp_number ?? $order->customer->phone ?? '' }}
                </p>
              </div>
              <div class="col-md-6">
                <div class="invoice-summary">
                  <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted-custom">Order Number</span
                    ><strong>#{{ $order->order_number }}</strong>
                  </div>
                  <div class="d-flex justify-content-between small mb-2">
                    <span class="text-muted-custom">Delivery Date</span
                    ><strong>
                      {{ \Carbon\Carbon::parse($order->delivery_date)->format('d F Y') }}
                      @if($order->delivery_time)
                        , {{ \Carbon\Carbon::parse($order->delivery_time)->format('g:i A') }}
                      @endif
                    </strong>
                  </div>
                  <div class="d-flex justify-content-between small">
                    <span class="text-muted-custom">Payment Method</span
                    ><strong>{{ $lastPayment->method ?? $order->payment_method ?? '—' }}</strong>
                  </div>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table mb-4">
                <thead>
                  <tr>
                    <th>Item Description</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Price</th>
                    <th class="text-end">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order->items as $item)
                  <tr>
                    <td>
                      <strong>{{ $item->item_name }}</strong>
                      @if($item->description)
                        <br /><small class="text-muted-custom">{{ $item->description }}</small>
                      @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-end">PKR {{ number_format($item->unit_price, 0) }}</td>
                    <td class="text-end fw-bold">PKR {{ number_format($item->amount, 0) }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="row justify-content-end">
              <div class="col-md-6">
                <div class="invoice-total">
                  <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted-custom">Order Total</span
                    ><strong>PKR {{ number_format($order->total_amount, 0) }}</strong>
                  </div>
                  <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted-custom">Paid</span
                    ><strong class="text-success">PKR {{ number_format($paidTotal, 0) }}</strong>
                  </div>
                  <hr />
                  <div class="d-flex justify-content-between fs-5">
                    <strong>Remaining</strong
                    ><strong class="{{ $remaining > 0 ? 'text-danger' : 'text-success' }}">
                      PKR {{ number_format(max($remaining, 0), 0) }}
                    </strong>
                  </div>
                </div>
              </div>
            </div>
            <div
              class="border-top mt-5 pt-4 text-center text-muted-custom small"
            >
              <p class="mb-1">Thank you for choosing Ghar Ka Baker!</p>
              @if($remaining > 0)
                <p class="mb-0">
                  Please pay the remaining amount before or on delivery.
                  JazakAllah khair.
                </p>
              @endif
            </div>
          </div>
        </article>
      </main>
    </div>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div id="appToast" class="toast border-0">
        <div class="toast-body bg-dark text-white rounded">
          <span id="toastText"></span>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
  </body>
</html>