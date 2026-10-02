<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Orders | Ghar Ka Baker</title>
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
              <h1 class="page-title">{{ $editingOrder ? 'Edit Order' : 'Add New Order' }}</h1>
              <p class="page-subtitle">
                {{ $editingOrder ? 'Update the details below and save.' : 'Fill in the details below to create an order.' }}
              </p>
            </div>
          </div>
          <div class="top-actions">
            <select class="form-select language-select">
              <option>English</option>
              <option>Urdu</option>
              <option>Roman Urdu</option></select
            ><button class="theme-button" type="button"></button>
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

        <form class="demo-form" id="orderForm"
      action="{{ $editingOrder ? route('order.update', $editingOrder->id) : '/orders' }}"
      method="POST">
          @csrf
            @if($editingOrder)
             @method('PUT')
            @endif
          <div class="row g-4">
            <div class="col-lg-8">
              <div class="card">
                <div class="card-body">
                  <h2 class="form-section-title">Customer</h2>
                  <div class="row g-3">
                    <div class="col-md-12">
                      <label class="form-label">Customer</label>
<!-- ==============================for whatsapp================ ==============-->
                     <select name="customer_id" id="customerSelect" class="form-select" required>
                         <option value="">-- Select Customer --</option>
                          @foreach($customers as $customer)
                              <option value="{{ $customer->id }}"
                                   data-name="{{ $customer->name }}"
                                   data-whatsapp="{{ $customer->whatsapp_number ?? $customer->phone }}"
                                   {{ $editingOrder && $editingOrder->customer_id == $customer->id ? 'selected' : '' }}>
                                 {{ $customer->name }} ({{ $customer->phone }})
                             </option>
                         @endforeach
                     </select>
<!-- ==============================for whatsapp================ ==============-->

                    
                    </div>
                  </div>

                  <h2 class="form-section-title mt-4">Order Items</h2>
                  <div id="itemsContainer"></div>
                  <button type="button" class="btn btn-soft btn-sm mt-2" onclick="addItemRow()">
                    <i class="bi bi-plus-lg"></i> Add Item
                  </button>

                  <div class="mt-3">
                    <label class="form-label">Special Instructions</label
                    ><textarea
                      name="special_instructions"
                      class="form-control"
                      rows="3"
                      placeholder="e.g. Write 'Happy Birthday Hira' and no nuts"
                    >{{ $editingOrder->special_instructions ?? '' }}</textarea>
                  </div>

                  <h2 class="form-section-title mt-4">Delivery Details</h2>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label">Delivery Date</label
                      ><input
                        id="deliveryDate"
                        name="delivery_date"
                        type="date"
                        class="form-control"
                        value="{{ $editingOrder ? \Carbon\Carbon::parse($editingOrder->delivery_date)->format('Y-m-d') : '' }}"
                        required
                      />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Delivery Time</label
                      ><input
                        name="delivery_time"
                        type="time"
                        class="form-control"
                        value="{{ $editingOrder && $editingOrder->delivery_time ? \Carbon\Carbon::parse($editingOrder->delivery_time)->format('H:i') : '' }}"
                      />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Order Type</label
                      ><select name="order_type" class="form-select">
                        <option value="Delivery" {{ ($editingOrder->order_type ?? 'Delivery') == 'Delivery' ? 'selected' : '' }}>Delivery</option>
                        <option value="Pickup" {{ ($editingOrder->order_type ?? '') == 'Pickup' ? 'selected' : '' }}>Pickup</option>
                      </select>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Delivery City</label
                      ><input
                        class="form-control"
                        name="delivery_city"
                        list="cityList"
                        placeholder="e.g. Karachi"
                        autocomplete="off"
                        value="{{ $editingOrder->delivery_city ?? '' }}"
                      />
                      <datalist id="cityList">
                        <option value="Karachi">
                        <option value="Lahore">
                        <option value="Islamabad">
                        <option value="Rawalpindi">
                        <option value="Faisalabad">
                        <option value="Multan">
                        <option value="Peshawar">
                        <option value="Quetta">
                        <option value="Sialkot">
                        <option value="Gujranwala">
                        <option value="Hyderabad">
                        <option value="Bahawalpur">
                        <option value="Sargodha">
                        <option value="Sukkur">
                        <option value="Larkana">
                        <option value="Sheikhupura">
                        <option value="Mardan">
                        <option value="Gujrat">
                        <option value="Kasur">
                        <option value="Rahim Yar Khan">
                        <option value="Sahiwal">
                        <option value="Okara">
                        <option value="Wah Cantonment">
                        <option value="Dera Ghazi Khan">
                        <option value="Mirpur Khas">
                        <option value="Nawabshah">
                        <option value="Mingora">
                        <option value="Chiniot">
                        <option value="Kotri">
                        <option value="Jhang">
                      </datalist>
                    </div>
                    <div class="col-12">
                      <label class="form-label">Address</label
                      ><textarea name="delivery_address" class="form-control" rows="2">{{ $editingOrder->delivery_address ?? '' }}</textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card">
                <div class="card-body">
                  <h2 class="form-section-title">Payment & Status</h2>
                  <div class="mb-3">
                    <label class="form-label">Total Amount (PKR)</label
                    ><input
                      id="totalAmount"
                      type="text"
                      class="form-control fw-bold"
                      value="{{ $editingOrder->total_amount ?? 0 }}"
                      readonly
                    />
                    <small class="text-muted-custom">Auto-calculated from items above</small>
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Advance Payment (PKR, optional)</label
                    ><input
                      id="advancePayment"
                      name="advance_payment"
                      type="number"
                      class="form-control"
                      value="{{ $editingOrder ? $editingOrder->payments->sum('amount') : 0 }}"
                      min="0"
                      {{ $editingOrder ? 'readonly' : '' }}
                    />
                    @if($editingOrder)
                      <small class="text-muted-custom">Payments already recorded against this order.</small>
                    @endif
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Remaining Amount (PKR)</label
                    ><input
                      id="remainingAmount"
                      class="form-control fw-bold"
                      readonly
                    />
                  </div>
                  <div class="mb-3">
                    <label class="form-label">Payment Method</label
                    ><select name="payment_method" class="form-select">
                      <option value="JazzCash" {{ ($editingOrder->payment_method ?? '') == 'JazzCash' ? 'selected' : '' }}>JazzCash</option>
                      <option value="Easypaisa" {{ ($editingOrder->payment_method ?? '') == 'Easypaisa' ? 'selected' : '' }}>Easypaisa</option>
                      <option value="Cash" {{ ($editingOrder->payment_method ?? '') == 'Cash' ? 'selected' : '' }}>Cash</option>
                      <option value="Bank Transfer" {{ ($editingOrder->payment_method ?? '') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    </select>
                  </div>
                  <div class="mb-4">
                    <label class="form-label">Order Status</label
                    ><select name="order_status" class="form-select">
                      <option value="New" {{ ($editingOrder->order_status ?? 'New') == 'New' ? 'selected' : '' }}>New</option>
                      <option value="Confirmed" {{ ($editingOrder->order_status ?? '') == 'Confirmed' ? 'selected' : '' }}>Confirmed</option>
                      <option value="In Preparation" {{ ($editingOrder->order_status ?? '') == 'In Preparation' ? 'selected' : '' }}>In Preparation</option>
                      <option value="Ready" {{ ($editingOrder->order_status ?? '') == 'Ready' ? 'selected' : '' }}>Ready</option>
                      <option value="Delivered" {{ ($editingOrder->order_status ?? '') == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                      <option value="Completed" {{ ($editingOrder->order_status ?? '') == 'Completed' ? 'selected' : '' }}>Completed</option>
                      <option value="Cancelled" {{ ($editingOrder->order_status ?? '') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-gradient w-100 mb-2">
                    <i class="bi bi-check2-circle"></i> {{ $editingOrder ? 'Update Order' : 'Save Order' }}</button
                  >
                  @if($editingOrder)
                    <a href="/orders" class="btn btn-light w-100 mb-2">Cancel Edit</a>
                  @endif
                  <button
                    id="whatsappButton"
                    type="button"
                    class="btn btn-success w-100"
                  >
                    <i class="bi bi-whatsapp"></i> Send on WhatsApp
                  </button>
                  <p class="small text-muted-custom text-center mt-3 mb-0">
                    A pre-filled WhatsApp message will open in a new tab.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </form>

        <section class="table-card mt-4">
          <div class="table-card-header">
            <div>
              <h2 class="section-title">Recent Orders</h2>
              <p class="section-note">Your latest saved order list.</p>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Order</th>
                  <th>Customer</th>
                  <th>Items</th>
                  <th>Delivery</th>
                  <th>Remaining</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($orders as $order)
                <tr>
                  <td>#{{ $order->order_number }}</td>
                  <td>{{ $order->customer->name ?? '—' }}</td>
                  <td>
                    @if($order->items->count() > 0)
                      {{ $order->items->first()->item_name }}
                      @if($order->items->count() > 1)
                        <span class="text-muted-custom">+{{ $order->items->count() - 1 }} more</span>
                      @endif
                    @else
                      —
                    @endif
                  </td>
                  <td>{{ \Carbon\Carbon::parse($order->delivery_date)->format('d M') }}@if($order->delivery_time), {{ \Carbon\Carbon::parse($order->delivery_time)->format('g:i A') }}@endif</td>
                  <td>PKR {{ number_format($order->total_amount - $order->payments->sum('amount'), 0) }}</td>
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
                 <td>
                  <a href="{{ route('order.invoice', $order->id) }}" class="btn btn-sm btn-soft" target="_blank">
                     <i class="bi bi-receipt"></i>
                  </a>
                  <a href="{{ route('order.edit', $order->id) }}" class="btn btn-sm btn-soft">
                     <i class="bi bi-pencil"></i>
                  </a>
                  <form action="{{ route('order.destroy', $order->id) }}" method="POST"
                     style="display:inline"
                    onsubmit="return confirm('Are you sure want to delete?');">
                    @csrf
                    @method('DELETE')
                   <button type="submit" class="btn btn-sm btn-soft text-danger">
                        <i class="bi bi-trash"></i>
                   </button>
                </form>
               </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="text-center text-muted-custom py-4">
                    Abhi tak koi order nahi hua.
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
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

    {{-- Products data for JS (used to auto-fill price when product selected) --}}
    <script>
      window.availableProducts = [
        @foreach($products as $product)
          { id: {{ $product->id }}, name: @json($product->name), price: {{ $product->price }} },
        @endforeach
      ];
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>

    {{-- Pre-fill order items when editing an existing order --}}
    @if($editingOrder)
        @php
            $existingItemsForJs = $editingOrder->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'item_name' => $item->item_name,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                ];
            });
        @endphp
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        var container = document.getElementById("itemsContainer");
        if (!container) return;

        // app.js already added one empty row by default; clear it and start fresh
        container.innerHTML = "";
        itemIndex = 0;

        var existingItems = @json($existingItemsForJs);

        existingItems.forEach(function (item) {
          addItemRow();
          var rows = document.querySelectorAll(".item-row");
          var row = rows[rows.length - 1];

          if (item.product_id) {
            row.querySelector(".item-product").value = item.product_id;
          }
          row.querySelector(".item-name").value = item.item_name;
          row.querySelector(".item-description").value = item.description || "";
          row.querySelector(".item-qty").value = item.quantity;
          row.querySelector(".item-price").value = item.unit_price;
        });

        calculateTotal();
      });
    </script>
    @endif
  </body>
</html>