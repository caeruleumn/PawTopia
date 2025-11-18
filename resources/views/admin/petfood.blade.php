@include('layoutadmin.navbar')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
        /* ===== GLOBAL STYLES ===== */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #EAE6E1;
            color: #6B4F3A;
            margin: 0;
        }

        .content {
            margin-left: 300px;
            padding: 40px 32px 32px 32px;
            max-width: 100%;
        }

        /* ===== HEADER SECTION ===== */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .header h2 {
            font-size: 2.2rem;
            font-weight: 800;
            color: #8A6552;
            letter-spacing: -0.02em;
        }

        .btn-add {
            background: #E57300;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .btn-add:hover {
            background: #D16500;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(229,115,0,0.3);
        }
        /* ===== FILTER SECTION ===== */
        .filter-container {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        select, input[type="text"] {
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 12px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: 0.3s;
            color: #6B4F3A;
            background: #F7F5F2;
            font-weight: 500;
        }

        select:focus, input:focus {
            border-color: #E57300;
            outline: none;
            box-shadow: 0 0 6px rgba(229,115,0,0.15);
            background: #fff;
        }
        /* ===== TABLE SECTION ===== */
        .table-title {
            font-size: 1.3rem;
            font-weight: 800;
            margin: 30px 0 18px;
            color: #6B4F3A;
            letter-spacing: -0.01em;
        }

        .table-container {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 8px 32px rgba(230,161,93,0.08);
            overflow: hidden;
            margin-bottom: 24px;
            border: 1px solid rgba(255,255,255,0.4);
            transition: 0.3s;
        }

        .table-container:hover {
            box-shadow: 0 12px 40px rgba(230,161,93,0.13);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
            background: #fff;
        }

        th {
            background: linear-gradient(135deg, #FFE0B2, #F9D9A7);
            padding: 20px 16px;
            text-align: left;
            font-weight: 700;
            font-size: 0.95rem;
            color: #6B4F3A;
            text-transform: uppercase;
            border-bottom: 2px solid rgba(169,123,93,0.1);
            letter-spacing: 0.5px;
        }

        td {
            padding: 20px 16px;
            border-top: 1px solid rgba(240,240,240,0.8);
            font-size: 0.98rem;
            color: #6B4F3A;
            font-weight: 500;
            vertical-align: middle;
        }

        td img {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 10px;
        }
        /* ===== ACTION BUTTONS ===== */
        .action-btns {
            text-align: center;
        }

        .btn-edit, .btn-delete {
            border: none;
            padding: 8px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.92rem;
            font-weight: 600;
            transition: 0.3s;
            margin-right: 6px;
        }

        .btn-edit {
            background: #FF9800;
            color: #fff;
        }

        .btn-edit:hover {
            background: #e68a00;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(255,152,0,0.23);
        }

        .btn-delete {
            background: #DC3545;
            color: #fff;
            margin-right: 0;
        }

        .btn-delete:hover {
            background: #C82333;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220,53,69,0.22);
        }

        .btn-see-more {
            display: block;
            margin: 20px auto 0 auto;
            padding: 12px 28px;
            border-radius: 16px;
            border: none;
            background: #E57300;
            color: #fff;
            cursor: pointer;
            font-size: 0.98rem;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s;
            box-shadow: 0 4px 12px rgba(229,115,0,0.07);
        }

        .btn-see-more:hover {
            background: #D16500;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(229,115,0,0.17);
        }
        /* ===== PAGINATION ===== */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 25px;
        }

        .pagination button {
            padding: 9px 18px;
            border-radius: 12px;
            border: 1px solid #E57300;
            background: white;
            color: #6B4F3A;
            cursor: pointer;
            font-size: 0.96rem;
            font-weight: 600;
            transition: 0.3s;
        }

        .pagination button.active {
            background: #E57300;
            color: white;
            border-color: #E57300;
        }

        .pagination button:hover {
            background: #FFF3E0;
        }

        /* ===== MODAL STYLES ===== */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(107,79,58,0.7);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            border-radius: 24px;
            padding: 32px 38px;
            width: 420px;
            box-shadow: 0px 12px 40px rgba(107,79,58,0.18);
            animation: fadeIn 0.3s ease-in-out;
            text-align: left;
        }

        .modal-content h3 {
            margin-bottom: 20px;
            color: #6B4F3A;
            font-size: 1.3rem;
            font-weight: 800;
            text-align: center;
        }
        /* ===== MODAL FORM ELEMENTS ===== */
        .image-upload {
            width: 120px;
            height: 120px;
            background: #f1f1f1;
            border: 2px dashed #ccc;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 15px auto;
            font-size: 28px;
            color: #777;
            cursor: pointer;
            transition: 0.3s;
        }

        .image-upload:hover {
            background: #fafafa;
            border-color: #E57300;
        }

        .modal-content input, .modal-content select {
            width: 100%;
            padding: 12px 16px;
            margin: 10px 0;
            border-radius: 12px;
            border: 1px solid #ddd;
            background: #F7F5F2;
            color: #6B4F3A;
            font-size: 0.98rem;
            font-weight: 500;
            transition: 0.3s;
        }

        .modal-content input:focus, .modal-content select:focus {
            border-color: #E57300;
            outline: none;
            box-shadow: 0 0 0 2px rgba(229,115,0,0.09);
            background: #fff;
        }

        .category-btns {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
        }

        .category-btns button {
            flex: 1;
            margin: 0 4px;
            padding: 10px 0;
            border-radius: 12px;
            border: 1px solid #E57300;
            background: #fff;
            color: #E57300;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.98rem;
            transition: 0.3s;
        }

        .category-btns button:hover {
            background: #FFF3E0;
        }

        .category-btns button.active {
            background: #E57300;
            color: #fff;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
            gap: 10px;
        }

        .btn-cancel, .btn-save {
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 0.96rem;
            cursor: pointer;
            transition: 0.3s;
            font-weight: 600;
            border: none;
        }

        .btn-cancel {
            background: #F7F5F2;
            color: #6B4F3A;
        }

        .btn-cancel:hover {
            background: #e8e5e0;
        }

        .btn-save {
            background: #E57300;
            color: #fff;
        }

        .btn-save:hover {
            background: #D16500;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 1000px) {
            .content { 
                padding: 20px 10px; 
            }
            
            .table-container { 
                padding: 0; 
            }
            
            th, td { 
                padding: 14px 8px; 
            }
        }
</style>

<div class="content">
    <div class="header">
        <h2>All Listed Pet Food</h2>
        <button class="btn-add" onclick="openAddProductModal()"><i class="bi bi-plus-circle"></i> Add Product</button>
    </div>

    <!-- FILTER -->
    <div class="filter-container">
        <select id="filterCategory">
            <option value="">All Pet Food</option>
            <option value="Cat Food">Cat Food</option>
            <option value="Dog Food">Dog Food</option>
        </select>
        <select id="sortPrice">
            <option value="">Sort by Price</option>
            <option value="asc">Lowest to Highest</option>
            <option value="desc">Highest to Lowest</option>
        </select>
        <input type="text" id="searchInput" placeholder="Search product...">
    </div>

    <!-- TABLE -->
    <h3 class="table-title">Pet Food List</h3>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody id="petFoodList">
                @isset($items)
                    @forelse($items as $p)
                        <tr data-id="{{ $p->id }}">
                            <td>
                                @php($img = $p->image_path ? asset('storage/'.$p->image_path) : asset('images/whiskas.svg'))
                                <img src="{{ $img }}" alt="{{ $p->name }}">
                            </td>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->category }}</td>
                            <td>Rp. {{ number_format((int) $p->price, 0, ',', '.') }}</td>
                            <td class="action-btns">
                                <button class="btn-edit" data-action="edit">Edit</button>
                                <button class="btn-delete" data-action="delete">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center;color:#888;">No Pet Food products yet.</td></tr>
                    @endforelse
                @endisset
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div id="paginationInfo" style="text-align:center;margin-top:8px;color:#6B4F3A;font-weight:600;"></div>
    <div class="pagination" id="pagination"></div>
</div>

<!-- ====== Modal Add Product ====== -->
<div class="modal" id="addProductModal">
    <div class="modal-content">
        <h3>New Product</h3>
        <div class="image-upload" onclick="document.getElementById('addProductImage').click()">
            <span id="addImagePreview">📷</span>
        </div>
        <input type="file" id="addProductImage" accept="image/*" style="display:none">
        <input type="text" id="addProductName" placeholder="Enter product name">
        <input type="text" id="addProductSku" placeholder="Enter unique SKU">
        <input type="text" id="addProductPrice" placeholder="Enter product price (e.g. Rp. 10.000)">
        <div class="category-btns" id="addCategoryBtns">
            <button type="button" class="active">Pet Food</button>
            <button type="button">Supplies</button>
            <button type="button">Vitamin</button>
        </div>
        <input type="number" id="addProductStock" placeholder="Enter stock">
        <select id="addProductStatus">
            <option value="available">Available</option>
            <option value="coming-soon">Coming Soon</option>
            <option value="preorder">Pre-order</option>
        </select>
        <div class="modal-actions">
            <button class="btn-cancel" id="closeModal">Back</button>
            <button class="btn-save" id="addSaveBtn">Add</button>
        </div>
    </div>
</div>

<!-- ====== Modal Edit Product ====== -->
<div class="modal" id="editProductModal">
    <div class="modal-content">
        <h3>Edit Product</h3>
        <div class="image-upload" onclick="document.getElementById('editProductImage').click()">
            <span id="editImagePreview">📷</span>
        </div>
        <input type="hidden" id="editProductId">
        <input type="file" id="editProductImage" accept="image/*" style="display:none">
        <input type="text" id="editProductName" placeholder="Enter product name">
        <input type="text" id="editProductSku" placeholder="Enter unique SKU">
        <input type="text" id="editProductPrice" placeholder="Enter product price (e.g. Rp. 10.000)">
        <div class="category-btns" id="editCategoryBtns">
            <button type="button" class="active">Pet Food</button>
            <button type="button">Supplies</button>
            <button type="button">Vitamin</button>
        </div>
        <input type="number" id="editProductStock" placeholder="Enter stock">
        <select id="editProductStatus">
            <option value="available">Available</option>
            <option value="coming-soon">Coming Soon</option>
            <option value="preorder">Pre-order</option>
        </select>
        <div class="modal-actions">
            <button class="btn-cancel" id="closeEditModal">Back</button>
            <button class="btn-save" id="editSaveBtn">Save Changes</button>
        </div>
    </div>
</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const intToRupiah = (n) => new Intl.NumberFormat('id-ID').format(n);
const currencyToInt = (val) => {
  if (typeof val !== 'string') return Number(val) || 0;
  return Number(val.replace(/[^0-9]/g, '')) || 0;
};

async function apiFetch(url, options = {}) {
  const opts = { ...options };
  opts.headers = opts.headers || {};
  if (!(opts.body instanceof FormData)) {
    opts.headers['Content-Type'] = 'application/json';
  }
  // Ensure Laravel returns JSON validation on FormData too
  opts.headers['Accept'] = 'application/json';
  opts.headers['X-Requested-With'] = 'XMLHttpRequest';
  opts.headers['X-CSRF-TOKEN'] = csrfToken;
  const res = await fetch(url, opts);
  if (!res.ok) {
    const t = await res.text();
    throw new Error(t || 'Request failed');
  }
  const ct = res.headers.get('content-type') || '';
  return ct.includes('application/json') ? res.json() : res.text();
}

function rowTemplate(p){
  const img = p.image_path ? ('/storage/' + p.image_path) : `{{ asset('images/whiskas.svg') }}`;
  return `
    <tr data-id="${p.id}">
      <td><img src="${img}" alt="${p.name}"></td>
      <td>${p.name}</td>
      <td>${p.category}</td>
      <td>Rp. ${intToRupiah(Number(p.price)||0)}</td>
      <td class="action-btns">
        <button class="btn-edit" data-action="edit">Edit</button>
        <button class="btn-delete" data-action="delete">Delete</button>
      </td>
    </tr>`;
}

function bindActions(container, items) {
  container.querySelectorAll('button[data-action]')?.forEach(btn => {
    btn.addEventListener('click', async (e) => {
      const tr = e.currentTarget.closest('tr');
      const id = Number(tr.getAttribute('data-id'));
      const action = e.currentTarget.getAttribute('data-action');
      if (action === 'delete') {
        if (!confirm('Delete this product?')) return;
        try {
          const fd = new FormData();
          fd.append('_method', 'DELETE');
          await apiFetch(`{{ url('/admin/products') }}/${id}`, { method: 'POST', body: fd });
          // Refresh daftar agar konsisten
          await loadPetFood();
          alert('Product deleted successfully!');
        } catch (e) {
          console.error('Failed to delete product:', e);
          alert('Failed to delete product: ' + (e.message || e));
        }
      } else if (action === 'edit') {
        // Open edit modal and preload data from API
        try {
          const p = await apiFetch(`{{ url('/admin/products') }}/${id}`);
          openEditProductModal(p);
        } catch (e) {
          console.error('Failed to load product:', e);
          alert('Failed to load product details');
        }
      }
    });
  });
}

// Fungsi untuk menambahkan event listener pada modal
function setupModalListeners() {
  const addModal = document.getElementById("addProductModal");
  const editModal = document.getElementById("editProductModal");
  const closeAddBtn = document.querySelector("#closeModal");
  const closeEditBtn = document.querySelector("#closeEditModal");

  window.openAddProductModal = function() {
    addModal.style.display = "flex";
    document.body.style.overflow = "hidden";
  };
  
  closeAddBtn.addEventListener("click", () => {
    addModal.style.display = "none";
    document.body.style.overflow = "auto";
  });
  
  window.openEditProductModal = function(p) {
    // Prefill form edit berdasarkan object produk
    document.getElementById('editProductId').value = p.id;
    document.getElementById('editProductName').value = p.name || '';
    document.getElementById('editProductSku').value = p.sku || '';
    document.getElementById('editProductPrice').value = `Rp. ${intToRupiah(Number(p.price)||0)}`;
    document.getElementById('editProductStock').value = p.stock ?? 0;
    document.getElementById('editProductStatus').value = p.status || 'available';
    // Set active category
    const cat = (p.category || '').trim();
    editModal.querySelectorAll('#editCategoryBtns button').forEach(btn => {
      btn.classList.toggle('active', btn.textContent.trim() === cat);
    });
    editModal.style.display = "flex";
    document.body.style.overflow = "hidden";
  };
  
  closeEditBtn.addEventListener("click", () => {
    editModal.style.display = "none";
    document.body.style.overflow = "auto";
  });
  
  window.addEventListener("click", (e) => {
    if (e.target === addModal) {
      addModal.style.display = "none";
      document.body.style.overflow = "auto";
    }
    if (e.target === editModal) {
      editModal.style.display = "none";
      document.body.style.overflow = "auto";
    }
  });
  
  document.querySelectorAll(".category-btns button").forEach(btn => {
    btn.addEventListener("click", () => {
      const parent = btn.parentElement;
      parent.querySelectorAll("button").forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
    });
  });

  // Bind tombol save pada masing-masing modal
  const addSaveBtn = document.getElementById('addSaveBtn');
  if (addSaveBtn) addSaveBtn.addEventListener('click', submitAddProduct);
  const editSaveBtn = document.getElementById('editSaveBtn');
  if (editSaveBtn) editSaveBtn.addEventListener('click', submitEditProduct);
}

let currentPage = 1;
const perPage = 10;
let currentCategory = '';
let currentSearch = '';
let currentSort = '';

function renderPagination(pageInfo){
  const pag = document.getElementById('pagination');
  if (!pag) return;
  pag.innerHTML = '';
  if (!pageInfo) return;
  const { current_page, last_page } = pageInfo;
  const addBtn = (label, page, disabled=false, active=false) => {
    const btn = document.createElement('button');
    btn.textContent = label;
    if (active) btn.classList.add('active');
    if (disabled) btn.disabled = true;
    btn.addEventListener('click', () => { currentPage = page; loadPetFood(); });
    pag.appendChild(btn);
  };
  addBtn('«', Math.max(1, current_page - 1), current_page === 1);
  // show limited pages
  const start = Math.max(1, current_page - 2);
  const end = Math.min(last_page, current_page + 2);
  for (let p = start; p <= end; p++) addBtn(String(p), p, false, p === current_page);
  addBtn('»', Math.min(last_page, current_page + 1), current_page === last_page);
}

function renderPaginationInfo(pageInfo){
  const info = document.getElementById('paginationInfo');
  if (!info || !pageInfo) return;
  const from = pageInfo.from ?? 0;
  const to = pageInfo.to ?? 0;
  const total = pageInfo.total ?? 0;
  info.textContent = total > 0 ? `Showing ${from}–${to} of ${total}` : 'No products found';
}

async function loadPetFood(){
  const params = new URLSearchParams();
  params.set('per_page', String(perPage));
  params.set('page', String(currentPage));
  // Search
  if (currentSearch) params.set('q', currentSearch);
  // Category: if specific selected, send category; else constrain to petfood group on server
  if (currentCategory) {
    params.set('category', currentCategory);
  } else {
    params.set('category_group', 'petfood');
  }
  // Sorting: send to server so pagination meta is consistent
  if (currentSort === 'asc') {
    params.set('sort', 'price');
    params.set('direction', 'asc');
  } else if (currentSort === 'desc') {
    params.set('sort', 'price');
    params.set('direction', 'desc');
  }

  const res = await apiFetch(`{{ url('/admin/products') }}?${params.toString()}`);
  const data = res?.data || [];
  // Data dari server sudah ter-filter: jika category kosong, server pakai category_group=petfood
  const items = data;
  const tbody = document.getElementById('petFoodList');
  if (!tbody) return;
  tbody.innerHTML = '';
  if (items.length === 0) {
    tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;color:#888;">No products found</td></tr>';
  } else {
    items.forEach(p => tbody.insertAdjacentHTML('beforeend', rowTemplate(p)));
  }
  bindActions(tbody, items);
  renderPagination(res);
  renderPaginationInfo(res);
}

document.addEventListener("DOMContentLoaded", () => {
  // Setup modal listeners
  setupModalListeners();
  // Bind filters
  const catSel = document.getElementById('filterCategory');
  const sortSel = document.getElementById('sortPrice');
  const searchEl = document.getElementById('searchInput');
  if (catSel) catSel.addEventListener('change', () => { currentCategory = catSel.value; currentPage = 1; loadPetFood(); });
  if (sortSel) sortSel.addEventListener('change', () => { currentSort = sortSel.value; currentPage = 1; loadPetFood(); });
  if (searchEl) {
    let t;
    searchEl.addEventListener('input', () => {
      clearTimeout(t);
      t = setTimeout(() => { currentSearch = searchEl.value.trim(); currentPage = 1; loadPetFood(); }, 350);
    });
  }
  // Initial load
  loadPetFood();
});

async function submitAddProduct(){
  try {
    const btn = document.getElementById('addSaveBtn');
    btn.disabled = true; btn.textContent = 'Saving...';
    const activeCatBtn = document.querySelector('#addCategoryBtns button.active');
    const category = activeCatBtn ? activeCatBtn.textContent.trim() : 'Pet Food';
    const fd = new FormData();
    fd.append('name', document.getElementById('addProductName').value.trim());
    fd.append('sku', document.getElementById('addProductSku').value.trim());
    fd.append('category', category);
    fd.append('price', String(currencyToInt(document.getElementById('addProductPrice').value)));
    fd.append('stock', String(Number(document.getElementById('addProductStock').value || 0)));
    fd.append('status', document.getElementById('addProductStatus').value);
    const img = document.getElementById('addProductImage').files[0];
    if (img) fd.append('image', img);
    await apiFetch(`{{ url('/admin/products') }}`, { method: 'POST', body: fd });
    // close & refresh
    document.getElementById('addProductModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    await loadPetFood();
    alert('Product added');
  } catch(e){
    console.error(e);
    alert('Failed to add product: ' + (e.message || e));
  } finally {
    const btn = document.getElementById('addSaveBtn');
    if (btn){ btn.disabled = false; btn.textContent = 'Add'; }
  }
}

async function submitEditProduct(){
  try {
    const btn = document.getElementById('editSaveBtn');
    btn.disabled = true; btn.textContent = 'Saving...';
    const id = document.getElementById('editProductId').value;
    const activeCatBtn = document.querySelector('#editCategoryBtns button.active');
    const category = activeCatBtn ? activeCatBtn.textContent.trim() : 'Pet Food';
    const fd = new FormData();
    fd.append('_method', 'PUT');
    fd.append('name', document.getElementById('editProductName').value.trim());
    fd.append('sku', document.getElementById('editProductSku').value.trim());
    fd.append('category', category);
    fd.append('price', String(currencyToInt(document.getElementById('editProductPrice').value)));
    fd.append('stock', String(Number(document.getElementById('editProductStock').value || 0)));
    fd.append('status', document.getElementById('editProductStatus').value);
    const img = document.getElementById('editProductImage').files[0];
    if (img) fd.append('image', img);
    await apiFetch(`{{ url('/admin/products') }}/${id}`, { method: 'POST', body: fd });
    // close & refresh
    document.getElementById('editProductModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    await loadPetFood();
    alert('Product updated');
  } catch(e){
    console.error(e);
    alert('Failed to update product: ' + (e.message || e));
  } finally {
    const btn = document.getElementById('editSaveBtn');
    if (btn){ btn.disabled = false; btn.textContent = 'Save Changes'; }
  }
}
</script>