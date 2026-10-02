<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard | Ghar Ka Baker</title>
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
              <h1 class="page-title">
                Assalam-o-Alaikum, {{ $bakery->owner_name ?? 'there' }}! <span>👋</span>
              </h1>
              <p class="page-subtitle">
                Here is what is happening with your business today.
              </p>
            </div>
          </div>
          <div class="top-actions">
            <select class="form-select language-select">
              <option>English</option>
              <option>Urdu</option>
              <option>Roman Urdu</option></select
            ><button class="theme-button" type="button"></button
            ><a
              href="/orders"
              class="btn btn-gradient d-none d-sm-inline-block"
              ><i class="bi bi-plus-lg"></i> Add Order</a
            >
            <form action="/logout" method="POST">
                 @csrf
            <button type="submit" class="btn btn-soft">Logout</button>
             </form>
          </div>
        </header>

        <section class="row g-3 mb-4">
          <div class="col-sm-6 col-xl">
            <div class="card metric-card">
              <div class="card-body">
                <div class="metric-icon">
                  <i class="bi bi-calendar-check"></i>
                </div>
                <p class="metric-label">Today's Orders</p>
                <div class="d-flex align-items-center justify-content-between">
                  <span class="metric-value">{{ $todaysOrdersCount }}</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl">
            <div class="card metric-card">
              <div class="card-body">
                <div class="metric-icon"><i class="bi bi-box-seam"></i></div>
                <p class="metric-label">Upcoming Orders</p>
                <div class="d-flex align-items-center justify-content-between">
                  <span class="metric-value">{{ $upcomingOrdersCount }}</span
                  ><span class="metric-change">This week</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl">
            <div class="card metric-card">
              <div class="card-body">
                <div class="metric-icon"><i class="bi bi-wallet2"></i></div>
                <p class="metric-label">Pending Payments</p>
                <div class="d-flex align-items-center justify-content-between">
                  <span class="metric-value">PKR {{ number_format($pendingAmount, 0) }}</span
                  ><span class="badge badge-soft-warning">{{ $pendingOrdersCount }} orders</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl">
            <div class="card metric-card highlight-card">
              <div class="card-body">
                <div class="metric-icon bg-white text-primary">
                  <i class="bi bi-graph-up-arrow"></i>
                </div>
                <p class="metric-label">Total Revenue</p>
                <div class="d-flex align-items-center justify-content-between">
                  <span class="metric-value">PKR {{ number_format($totalRevenue, 0) }}</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl">
            <div class="card metric-card">
              <div class="card-body">
                <div class="metric-icon"><i class="bi bi-people"></i></div>
                <p class="metric-label">Total Customers</p>
                <div class="d-flex align-items-center justify-content-between">
                  <span class="metric-value">{{ $totalCustomers }}</span>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section class="row g-4">
          <div class="col-xl-8">
            <div class="table-card h-100">
              <div class="table-card-header">
                <div>
                  <h2 class="section-title">Recent Orders</h2>
                  <p class="section-note">Latest orders from your customers</p>
                </div>
                <a href="/orders" class="small-link">View all</a>
              </div>
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Customer</th>
                      <th>Order</th>
                      <th>Delivery</th>
                      <th>Total</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                      <td>
                        <span class="avatar">{{ strtoupper(substr($order->customer->name ?? '?', 0, 2)) }}</span>
                        <strong>{{ $order->customer->name ?? '—' }}</strong>
                      </td>
                      <td>
                        #{{ $order->order_number }}<br /><small class="text-muted-custom">
                          {{ $order->items->first()->item_name ?? '' }}
                        </small>
                      </td>
                      <td>
                        {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M') }}
                        @if($order->delivery_time)
                          , {{ \Carbon\Carbon::parse($order->delivery_time)->format('g:i A') }}
                        @endif
                      </td>
                      <td>PKR {{ number_format($order->total_amount, 0) }}</td>
                      <td>
                        @php
                          $statusClass = match($order->order_status) {
                            'New' => 'badge-soft-warning',
                            'Confirmed' => 'badge-soft-info',
                            'In Preparation' => 'badge-soft-info',
                            'Ready' => 'badge-soft-success',
                            'Delivered' => 'badge-soft-success',
                            'Completed' => 'badge-soft-success',
                            'Cancelled' => 'badge-soft-danger',
                            default => 'badge-soft-warning',
                          };
                        @endphp
                        <span class="badge {{ $statusClass }}">{{ $order->order_status }}</span>
                      </td>
                    </tr>
                    @empty
                    <tr>
                      <td colspan="5" class="text-center text-muted-custom py-4">
                        Abhi tak koi order nahi hua.
                      </td>
                    </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-xl-4">
            <div class="card h-100">
              <div class="card-body">
                <div
                  class="d-flex justify-content-between align-items-center mb-3"
                >
                  <div>
                    <h2 class="section-title">Upcoming Deliveries</h2>
                    <p class="section-note">Don't miss these dates</p>
                  </div>
                  <i class="bi bi-calendar-heart text-primary fs-4"></i>
                </div>
                @forelse($upcomingDeliveries as $order)
                  @php
                    $remaining = $order->total_amount - $order->payments->sum('amount');
                    $deliveryDate = \Carbon\Carbon::parse($order->delivery_date);
                  @endphp
                  <div class="d-flex gap-3 py-3 border-bottom">
                    <div class="text-center">
                      <strong class="d-block fs-5 text-primary">{{ $deliveryDate->format('d') }}</strong
                      ><small class="text-muted-custom">{{ strtoupper($deliveryDate->format('M')) }}</small>
                    </div>
                    <div>
                      <strong>{{ $order->customer->name ?? '—' }}</strong>
                      <p class="text-muted-custom mb-0 small">
                        {{ $order->items->first()->item_name ?? '' }}
                        @if($order->delivery_city)
                          · {{ $order->delivery_city }}
                        @endif
                      </p>
                      @if($remaining > 0)
                        <span class="badge badge-soft-warning mt-1">PKR {{ number_format($remaining, 0) }} due</span>
                      @else
                        <span class="badge badge-soft-success mt-1">Advance paid</span>
                      @endif
                    </div>
                  </div>
                @empty
                  <p class="small text-muted-custom">No upcoming deliveries.</p>
                @endforelse
                <a href="/calendar" class="btn btn-soft w-100 mt-2"
                  >Open Calendar</a
                >
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
      <div id="appToast" class="toast border-0">
        <div class="toast-body bg-dark text-white rounded">
          <i class="bi bi-check-circle me-1"></i> <span id="toastText"></span>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
  </body>
</html>