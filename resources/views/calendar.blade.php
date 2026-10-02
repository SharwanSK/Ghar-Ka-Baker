<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Calendar | Ghar Ka Baker</title>
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
              <h1 class="page-title">Delivery Calendar</h1>
              <p class="page-subtitle">
                See upcoming cakes and catering deliveries at a glance.
              </p>
            </div>
          </div>
          <div class="top-actions">
            <select class="form-select language-select">
              <option>English</option>
              <option>Urdu</option>
              <option>Roman Urdu</option></select
            ><button class="theme-button" type="button"></button
            ><a href="/orders" class="btn btn-gradient"
              ><i class="bi bi-plus-lg"></i> Add Order</a
            >
          </div>
        </header>

        @php
          // Work out prev/next month + year for the navigation links
          $prevMonthDate = $monthStart->copy()->subMonth();
          $nextMonthDate = $monthStart->copy()->addMonth();

          $daysInMonth   = $monthStart->daysInMonth;
          $startWeekday  = $monthStart->copy()->startOfMonth()->dayOfWeek; // 0 = Sunday

          $isCurrentMonth = $monthStart->year == now()->year && $monthStart->month == now()->month;

          // Group this month's orders by day-of-month for quick lookup below
          $ordersByDay = [];
          foreach ($orders as $order) {
              $day = \Carbon\Carbon::parse($order->delivery_date)->day;
              $ordersByDay[$day][] = $order;
          }
        @endphp

        <section class="row g-4">
          <div class="col-xl-9">
            <div class="card">
              <div class="card-body p-0">
                <div
                  class="d-flex align-items-center justify-content-between p-3 px-md-4 border-bottom"
                >
                  <a href="/calendar?month={{ $prevMonthDate->month }}&year={{ $prevMonthDate->year }}" class="btn btn-soft">
                    <i class="bi bi-chevron-left"></i>
                  </a>
                  <div class="text-center">
                    <h2 class="section-title">{{ $monthStart->format('F Y') }}</h2>
                    <p class="section-note">Today is {{ now()->format('l, j F') }}</p>
                  </div>
                  <a href="/calendar?month={{ $nextMonthDate->month }}&year={{ $nextMonthDate->year }}" class="btn btn-soft">
                    <i class="bi bi-chevron-right"></i>
                  </a>
                </div>
                <div id="calendarGrid" class="calendar-grid">
                  @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $weekday)
                    <div class="calendar-weekday">{{ $weekday }}</div>
                  @endforeach

                  @for($i = 0; $i < $startWeekday; $i++)
                    <div class="calendar-day muted"></div>
                  @endfor

                  @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                      $isToday = $isCurrentMonth && now()->day == $day;
                      $dayOrders = $ordersByDay[$day] ?? [];
                    @endphp
                    <div class="calendar-day {{ $isToday ? 'today' : '' }}">
                      <strong>{{ $day }}</strong>
                      @foreach($dayOrders as $order)
                        @php
                          $isCatering = $order->items->contains(function ($item) {
                              return stripos($item->item_name, 'catering') !== false
                                  || stripos($item->description ?? '', 'catering') !== false;
                          });
                        @endphp
                        <span class="calendar-event {{ $isCatering ? '' : 'blue' }}"
                              style="{{ $isCatering ? 'background:#ffe0ef;color:#b1276e;' : '' }}"
                              title="{{ $order->customer->name ?? '' }}">
                          {{ $order->items->first()->item_name ?? $order->order_number }}
                        </span>
                      @endforeach
                    </div>
                  @endfor
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-3">
            <div class="card">
              <div class="card-body">
                <h2 class="section-title">Delivery Legend</h2>
                <div class="mt-3 small">
                  <div class="mb-2">
                    <span class="badge badge-soft-info me-2">Cake</span> Cake &
                    dessert order
                  </div>
                  <div class="mb-2">
                    <span
                      class="badge"
                      style="background: #ffe0ef; color: #b1276e"
                      >Catering</span
                    >
                    Catering order
                  </div>
                  <div>
                    <span class="badge badge-soft-warning me-2">Due</span>
                    Remaining payment
                  </div>
                </div>
                <hr class="my-4" />
                <h2 class="section-title">Next Delivery</h2>
                <div class="mt-3">
                  @if($nextOrder)
                    @php
                      $remaining = $nextOrder->total_amount - $nextOrder->payments->sum('amount');
                    @endphp
                    <span class="badge badge-soft-info">
                      {{ \Carbon\Carbon::parse($nextOrder->delivery_date)->format('d M') }}
                      @if($nextOrder->delivery_time)
                        · {{ \Carbon\Carbon::parse($nextOrder->delivery_time)->format('g:i A') }}
                      @endif
                    </span>
                    <h3 class="h6 fw-bold mt-2">{{ $nextOrder->items->first()->item_name ?? $nextOrder->order_number }}</h3>
                    <p class="small text-muted-custom mb-1">
                      {{ $nextOrder->customer->name ?? '—' }}
                      @if($nextOrder->delivery_address)
                        · {{ $nextOrder->delivery_address }}
                      @endif
                    </p>
                    @if($remaining > 0)
                      <p class="small fw-bold mb-3">PKR {{ number_format($remaining, 0) }} remaining</p>
                    @else
                      <p class="small fw-bold mb-3 text-success">Fully paid</p>
                    @endif
                    <a href="/orders" class="btn btn-soft w-100">View Order</a>
                  @else
                    <p class="small text-muted-custom">No upcoming deliveries.</p>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </section>
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