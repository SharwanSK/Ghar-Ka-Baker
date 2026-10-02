<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Customers | Ghar Ka Baker</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      rel="stylesheet"
    />
    <link href="assets/css/style.css" rel="stylesheet" />
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
              <h1 class="page-title">Customers</h1>
              <p class="page-subtitle">
                Keep your customer details in one place.
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
              data-bs-target="#customerModal"
              onclick="openAddCustomerModal()"
            >
              <i class="bi bi-person-plus"></i> Add Customer
            </button>
          </div>
        </header>
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <p class="text-muted-custom mb-1">Total Customers</p>
                <h2 class="mb-0 fw-bold">{{ $customers->count() }}</h2>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <p class="text-muted-custom mb-1">Repeat Customers</p>
                <h2 class="mb-0 fw-bold">
                  0 <small class="fs-6 text-muted-custom">Coming soon</small>
                </h2>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card">
              <div class="card-body">
                <p class="text-muted-custom mb-1">New This Month</p>
                <h2 class="mb-0 fw-bold">
                  {{ $customers->where('created_at', '>=', now()->startOfMonth())->count() }}
                </h2>
              </div>
            </div>
          </div>
        </div>
        <section class="table-card">
          <div class="table-card-header">
            <div>
              <h2 class="section-title">All Customers</h2>
              <p class="section-note">
                Search or add customers for your next order.
              </p>
            </div>
            <div class="input-group" style="max-width: 250px">
              <span class="input-group-text bg-transparent border-end-0"
                ><i class="bi bi-search text-muted-custom"></i></span
              ><input
                id="customerSearch"
                class="form-control border-start-0"
                placeholder="Search customer"
              />
            </div>
          </div>
          <div class="table-responsive">
            <table id="customerTable" class="table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>WhatsApp</th>
                  <th>City</th>
                  <th>Total Orders</th>
                  <th>Total Spending</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($customers as $customer)
                <tr>
                  <td>
                    <span class="avatar">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                    <strong>{{ $customer->name }}</strong>
                  </td>
                  <td>{{ $customer->phone }}</td>
                  <td>
                    @if($customer->whatsapp_number)
                      <a class="text-success" href="https://wa.me/{{ $customer->whatsapp_number }}" target="_blank"
                        ><i class="bi bi-whatsapp"></i> Message</a
                      >
                    @else
                      <span class="text-muted-custom">—</span>
                    @endif
                  </td>
                  <td>{{ $customer->city ?? '—' }}</td>
                  <td>0</td>
                  <td><strong>PKR 0</strong></td>
                  <td>
                    <button type="button" class="btn btn-sm btn-soft"
                      data-bs-toggle="modal" data-bs-target="#customerModal"
                      data-id="{{ $customer->id }}"
                      data-name="{{ $customer->name }}"
                      data-phone="{{ $customer->phone }}"
                      data-whatsapp="{{ $customer->whatsapp_number }}"
                      data-city="{{ $customer->city }}"
                      data-address="{{ $customer->address }}"
                      onclick="openEditCustomerModal(this)">
                      <i class="bi bi-pencil"></i>
                    </button>
                    <form action="{{ route('customer.destroy', $customer->id) }}" method="POST"
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
                    Abhi tak koi customer add nahi hua.
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </section>
      </main>
    </div>
    <div class="modal fade" id="customerModal" tabindex="-1">
      <div class="modal-dialog">
        <form class="modal-content demo-form" id="customerForm" action="/customers" method="POST">
          @csrf
          <input type="hidden" name="_method" id="customerFormMethod" value="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="customerModalTitle">Add New Customer</h5>
            <button
              type="button"
              class="btn-close"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Customer Name</label
              ><input
                class="form-control"
                name="name"
                placeholder="e.g. Mariam Ahmed"
                required
              />
            </div>
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="form-label">Phone Number</label
                ><input
                  class="form-control"
                  name="phone"
                  placeholder="0300 1234567"
                  required
                />
              </div>
              <div class="col-sm-6">
                <label class="form-label">WhatsApp Number (optional)</label
                ><input
                  class="form-control"
                  name="whatsapp_number"
                  placeholder="923001234567"
                />
              </div>
            </div>
<!-- ===================for city=============================== -->
           <div class="row g-3 mt-1">
  <div class="col-sm-6">
    <label class="form-label">City</label
    ><input
      class="form-control"
      name="city"
      list="cityList"
      placeholder="e.g. Karachi"
      autocomplete="off"
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
</div>
<!-- =====================for city================================== -->
            <div class="mt-3">
              <label class="form-label">Address (optional)</label
              ><textarea
                class="form-control"
                name="address"
                rows="2"
                placeholder="Area, street, house number"
              ></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
              Cancel</button
            ><button type="submit" class="btn btn-gradient">Save Customer</button>
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
    <script src="assets/js/app.js"></script>
  </body>
</html>