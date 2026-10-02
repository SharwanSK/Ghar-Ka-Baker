<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Payments | Ghar Ka Baker</title>
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
        <header class="topbar">
          <div class="d-flex gap-2">
            <button class="icon-button mobile-menu" onclick="toggleSidebar()">
              <i class="bi bi-list"></i>
            </button>
            <div>
              <h1 class="page-title">Payments</h1>
              <p class="page-subtitle">
                Track received and remaining payments.
              </p>
            </div>
          </div>
          <div class="top-actions">
            <select class="form-select language-select">
              <option>English</option>
              <option>Urdu</option>
              <option>Roman Urdu</option></select
            ><button class="theme-button" type="button"></button
            ><button
              class="btn btn-gradient"
              data-bs-toggle="modal"
              data-bs-target="#paymentModal"
            >
              <i class="bi bi-plus-lg"></i> Record Payment
            </button>
          </div>
        </header>

        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @php
          $totalReceived = $payments->sum('amount');

          $today = now()->toDateString();
          $todaysPayments = $payments->filter(function ($payment) use ($today) {
              return \Carbon\Carbon::parse($payment->payment_date)->toDateString() === $today;
          })->sum('amount');

          $pendingPayments = $orders->sum(function ($order) {
              $remaining = $order->total_amount - $order->payments->sum('amount');
              return $remaining > 0 ? $remaining : 0;
          });
        @endphp

        <section class="row g-3 mb-4">
          <div class="col-md-4">
            <div class="card metric-card">
              <div class="card-body">
                <div class="metric-icon"><i class="bi bi-cash-stack"></i></div>
                <p class="metric-label">Total Received</p>
                <div class="metric-value">PKR {{ number_format($totalReceived, 0) }}</div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card metric-card highlight-card">
              <div class="card-body">
                <div class="metric-icon bg-white text-primary">
                  <i class="bi bi-hourglass-split"></i>
                </div>
                <p class="metric-label">Pending Payments</p>
                <div class="metric-value">PKR {{ number_format($pendingPayments, 0) }}</div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card metric-card">
              <div class="card-body">
                <div class="metric-icon">
                  <i class="bi bi-calendar-day"></i>
                </div>
                <p class="metric-label">Today's Payments</p>
                <div class="metric-value">PKR {{ number_format($todaysPayments, 0) }}</div>
              </div>
            </div>
          </div>
        </section>
        <section class="table-card">
          <div class="table-card-header">
            <div>
              <h2 class="section-title">Payment History</h2>
              <p class="section-note">All payments for your orders.</p>
            </div>
            <select class="form-select" style="max-width: 145px">
              <option>All Methods</option>
              <option>JazzCash</option>
              <option>Easypaisa</option>
              <option>Cash</option>
              <option>Bank Transfer</option>
            </select>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Customer</th>
                  <th>Order</th>
                  <th>Amount</th>
                  <th>Method</th>
                  <th>Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @forelse($payments as $payment)
                <tr>
                  <td>
                    <span class="avatar">{{ strtoupper(substr($payment->order->customer->name ?? '?', 0, 2)) }}</span>
                    <strong>{{ $payment->order->customer->name ?? '—' }}</strong>
                  </td>
                  <td>#{{ $payment->order->order_number ?? '—' }}</td>
                  <td><strong>PKR {{ number_format($payment->amount, 0) }}</strong></td>
                  <td>{{ $payment->method }}</td>
                  <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                  <td>
                    <span class="badge {{ $payment->status == 'Paid' ? 'badge-soft-success' : 'badge-soft-warning' }}">
                      {{ $payment->status }}
                    </span>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="6" class="text-center text-muted-custom py-4">
                    Abhi tak koi payment record nahi hua.
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </section>
      </main>
    </div>
    <div class="modal fade" id="paymentModal" tabindex="-1">
      <div class="modal-dialog">
        <form class="modal-content demo-form" action="/payments" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Record Payment</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Order</label
              ><select name="order_id" class="form-select" required>
                <option value="">-- Select Order --</option>
                @foreach($orders as $order)
                  <option value="{{ $order->id }}">
                    #{{ $order->order_number }} — {{ $order->customer->name ?? '—' }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="form-label">Amount (PKR)</label
                ><input
                  type="number"
                  name="amount"
                  class="form-control"
                  placeholder="1000"
                  min="1"
                  required
                />
              </div>
              <div class="col-sm-6">
                <label class="form-label">Method</label
                ><select name="method" class="form-select">
                  <option value="JazzCash">JazzCash</option>
                  <option value="Easypaisa">Easypaisa</option>
                  <option value="Cash">Cash</option>
                  <option value="Bank Transfer">Bank Transfer</option>
                </select>
              </div>
            </div>
            <div class="mt-3">
              <label class="form-label">Payment Date</label
              ><input type="date" name="payment_date" class="form-control" value="{{ now()->toDateString() }}" required />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
              Cancel</button
            ><button type="submit" class="btn btn-gradient">Save Payment</button>
          </div>
        </form>
      </div>
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