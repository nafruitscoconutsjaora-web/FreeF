/**
 * FF Panel Store - Frontend Core (Vanilla JavaScript)
 * Strictly Public Storefront with Separate User Panel & Admin Panel
 */

// Application State
const state = {
  isLoggedIn: false,
  user: {
    id: 1,
    name: 'Aaris Ali',
    email: 'aaris@example.com',
    ffUid: '5482910482',
    walletBalance: 1250.00,
  },
  categories: [
    { id: 1, name: 'Diamonds', slug: 'diamonds', icon: 'fa-gem', color: 'text-blue-400' },
    { id: 2, name: 'Membership', slug: 'membership', icon: 'fa-crown', color: 'text-amber-400' },
    { id: 3, name: 'Elite Pass', slug: 'elite-pass', icon: 'fa-ticket', color: 'text-yellow-500' },
    { id: 4, name: 'Character', slug: 'character', icon: 'fa-user-ninja', color: 'text-rose-400' },
    { id: 5, name: 'Weapon Skin', slug: 'weapon-skin', icon: 'fa-crosshairs', color: 'text-red-400' },
    { id: 6, name: 'Bundle', slug: 'bundle', icon: 'fa-box', color: 'text-purple-400' },
    { id: 7, name: 'Pet', slug: 'pet', icon: 'fa-paw', color: 'text-pink-400' },
    { id: 8, name: 'ID / UID', slug: 'id-uid', icon: 'fa-id-card', color: 'text-emerald-400' },
    { id: 9, name: 'Special Offers', slug: 'special-offers', icon: 'fa-percent', color: 'text-amber-500' },
  ],
  products: [
    {
      id: 1,
      categorySlug: 'diamonds',
      name: '100 Diamonds',
      description: 'Free Fire Direct UID Top-Up',
      price: 20.00,
      originalPrice: 25.00,
      badge: 'Top Selling',
      instant: true,
      serviceMode: 'api',
      icon: 'fa-gem',
      iconColor: 'text-blue-400',
    },
    {
      id: 2,
      categorySlug: 'diamonds',
      name: '520 Diamonds',
      description: 'Free Fire Direct UID Top-Up',
      price: 95.00,
      originalPrice: 110.00,
      badge: 'Popular',
      instant: true,
      serviceMode: 'api',
      icon: 'fa-gem',
      iconColor: 'text-blue-400',
    },
    {
      id: 3,
      categorySlug: 'diamonds',
      name: '1060 Diamonds',
      description: 'Free Fire Direct UID Top-Up',
      price: 180.00,
      originalPrice: 210.00,
      badge: 'Best Value',
      instant: true,
      serviceMode: 'api',
      icon: 'fa-gem',
      iconColor: 'text-blue-400',
    },
    {
      id: 4,
      categorySlug: 'diamonds',
      name: '2180 Diamonds',
      description: 'Free Fire Direct UID Top-Up',
      price: 340.00,
      originalPrice: 400.00,
      badge: 'High Demand',
      instant: true,
      serviceMode: 'api',
      icon: 'fa-gem',
      iconColor: 'text-blue-400',
    },
    {
      id: 5,
      categorySlug: 'membership',
      name: 'Weekly Membership',
      description: 'Instant 450 Diamonds + Daily Claims',
      price: 70.00,
      originalPrice: 90.00,
      badge: 'Popular',
      instant: true,
      serviceMode: 'manual',
      icon: 'fa-crown',
      iconColor: 'text-amber-400',
      isCardBadge: 'W',
    },
    {
      id: 6,
      categorySlug: 'membership',
      name: 'Monthly Membership',
      description: 'Instant 2600 Diamonds Total Benefit',
      price: 199.00,
      originalPrice: 250.00,
      badge: 'Best Value',
      instant: true,
      serviceMode: 'manual',
      icon: 'fa-crown',
      iconColor: 'text-yellow-400',
      isCardBadge: 'M',
    },
    {
      id: 7,
      categorySlug: 'elite-pass',
      name: 'Elite Pass',
      description: 'Unlock Premium Season Badges & Rewards',
      price: 120.00,
      originalPrice: 150.00,
      badge: 'Trending',
      instant: true,
      serviceMode: 'manual',
      icon: 'fa-ticket',
      iconColor: 'text-yellow-500',
    },
    {
      id: 8,
      categorySlug: 'character',
      name: 'Character - Alok',
      description: 'Drop the Beat Ability Speed & Healing',
      price: 299.00,
      originalPrice: 399.00,
      badge: 'Popular',
      instant: true,
      serviceMode: 'manual',
      icon: 'fa-user-ninja',
      iconColor: 'text-rose-400',
    },
  ],
  cart: [],
  appliedCoupon: null,
  activeFilter: 'all',
  userOrders: [
    {
      id: 'FF-8841',
      productName: '520 Diamonds',
      quantity: 1,
      amount: 95.00,
      ffUid: '5482910482',
      status: 'completed',
      date: '28 Sep 2026, 09:20 PM',
      mode: 'API'
    },
    {
      id: 'FF-8712',
      productName: 'Weekly Membership',
      quantity: 1,
      amount: 70.00,
      ffUid: '5482910482',
      status: 'completed',
      date: '26 Sep 2026, 04:15 PM',
      mode: 'Manual'
    }
  ]
};

// UI Initialization
document.addEventListener('DOMContentLoaded', () => {
  renderCategories();
  renderProducts();
  setupEventListeners();
  updateAuthUI();
});

// Toast Helper
function showToast(message, isSuccess = true) {
  const toast = document.getElementById('toast');
  const msgEl = document.getElementById('toastMsg');
  const iconEl = document.getElementById('toastIcon');

  if (!toast || !msgEl || !iconEl) return;

  msgEl.textContent = message;
  iconEl.className = isSuccess 
    ? 'fa-solid fa-circle-check text-rose-500 text-sm' 
    : 'fa-solid fa-triangle-exclamation text-amber-500 text-sm';

  toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
  setTimeout(() => {
    toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
  }, 3000);
}

// Render Categories matching Reference
function renderCategories() {
  const container = document.getElementById('categoryGrid');
  if (!container) return;

  container.innerHTML = state.categories.map(cat => `
    <button class="cat-card bg-[#111723] hover:bg-[#161F2E] border border-gray-800 hover:border-rose-500/50 rounded-2xl p-2.5 flex flex-col items-center justify-center text-center transition group transform hover:-translate-y-0.5" data-category="${cat.slug}">
      <div class="w-10 h-10 rounded-xl bg-gray-900/80 flex items-center justify-center mb-1.5 group-hover:scale-110 transition">
        <i class="fa-solid ${cat.icon} ${cat.color} text-lg"></i>
      </div>
      <span class="text-[11px] font-semibold text-gray-200 group-hover:text-white leading-tight">${cat.name}</span>
    </button>
  `).join('');

  container.querySelectorAll('.cat-card').forEach(btn => {
    btn.addEventListener('click', () => {
      const slug = btn.getAttribute('data-category');
      filterServices(slug);
    });
  });
}

// Render Products Grid matching Reference
function renderProducts() {
  const container = document.getElementById('productsGrid');
  if (!container) return;

  const filtered = state.activeFilter === 'all'
    ? state.products
    : state.products.filter(p => p.categorySlug === state.activeFilter);

  if (filtered.length === 0) {
    container.innerHTML = `
      <div class="col-span-full bg-[#111723] border border-gray-800 rounded-2xl p-10 text-center text-gray-400 space-y-1">
        <i class="fa-solid fa-box-open text-3xl text-gray-600 mb-2 block"></i>
        <p class="font-bold text-sm text-gray-300">No products available.</p>
        <p class="text-xs text-gray-500">Please select another category or check back later.</p>
      </div>
    `;
    return;
  }

  container.innerHTML = filtered.map(p => {
    let graphic = '';
    if (p.categorySlug === 'diamonds') {
      graphic = `
        <div class="flex items-center gap-1.5 text-blue-400">
          <i class="fa-solid fa-gem text-3xl filter drop-shadow-[0_0_12px_rgba(96,165,250,0.6)]"></i>
        </div>
        <span class="absolute right-2 bottom-1.5 text-[9px] font-black tracking-widest text-white/30">FREE FIRE</span>
      `;
    } else if (p.categorySlug === 'membership') {
      if (p.isCardBadge === 'W') {
        graphic = `
          <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-pink-600 to-rose-400 flex items-center justify-center font-black text-white text-xl shadow-lg shadow-pink-600/30">
            W
          </div>
          <span class="absolute right-2 bottom-1.5 text-[9px] font-black tracking-widest text-white/30">FREE FIRE</span>
        `;
      } else {
        graphic = `
          <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-amber-500 to-yellow-300 flex items-center justify-center font-black text-black text-xl shadow-lg shadow-amber-500/30">
            M
          </div>
          <i class="fa-solid fa-crown absolute right-3 top-3 text-yellow-500/40 text-sm"></i>
        `;
      }
    } else if (p.categorySlug === 'elite-pass') {
      graphic = `
        <div class="flex flex-col items-center text-yellow-400">
          <i class="fa-solid fa-ticket text-3xl filter drop-shadow-[0_0_10px_rgba(250,204,21,0.5)]"></i>
          <span class="text-[9px] font-black tracking-wider mt-1 text-yellow-300">ELITE PASS</span>
        </div>
        <span class="absolute right-2 bottom-1.5 text-[9px] font-black tracking-widest text-white/30">FREE FIRE</span>
      `;
    } else {
      graphic = `
        <div class="flex items-center gap-1 text-rose-400">
          <i class="fa-solid fa-user-ninja text-3xl"></i>
        </div>
        <span class="absolute right-2 bottom-1.5 text-[9px] font-black tracking-widest text-white/30">ALOK</span>
      `;
    }

    return `
      <div class="bg-[#111723] border border-gray-800 hover:border-rose-500/40 rounded-2xl p-4 flex flex-col justify-between transition group transform hover:-translate-y-0.5 shadow-md">
        <!-- Visual Box -->
        <div class="w-full h-28 rounded-xl bg-gradient-to-br from-[#1C162E] via-[#161B2B] to-[#0E131E] border border-gray-800/80 flex items-center justify-center relative overflow-hidden mb-3">
          ${graphic}
        </div>

        <!-- Info -->
        <div class="space-y-1">
          <h3 class="font-bold text-white text-xs leading-snug group-hover:text-rose-400 transition truncate">${p.name}</h3>
          <p class="text-[11px] text-gray-400 truncate">${p.description}</p>

          <div class="flex items-center gap-1.5 pt-1">
            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-cyan-950/60 text-cyan-400 border border-cyan-800/40">Instant</span>
            ${p.badge ? `<span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-rose-950/60 text-rose-400 border border-rose-800/40">${p.badge}</span>` : ''}
          </div>
        </div>

        <!-- Price & Action -->
        <div class="pt-3 mt-2 border-t border-gray-800/80">
          <div class="flex items-baseline gap-2 mb-2">
            <span class="text-sm font-extrabold text-white font-mono">₹ ${p.price.toFixed(2)}</span>
            ${p.originalPrice ? `<span class="text-[10px] text-gray-500 line-through font-mono">₹ ${p.originalPrice.toFixed(2)}</span>` : ''}
          </div>
          <button class="add-to-cart-btn w-full py-2 px-3 rounded-xl bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 hover:to-rose-400 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition shadow" data-id="${p.id}">
            <i class="fa-solid fa-cart-shopping text-xs"></i> Buy Now
          </button>
        </div>
      </div>
    `;
  }).join('');

  container.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const id = parseInt(btn.getAttribute('data-id'), 10);
      addToCart(id);
    });
  });
}

function filterServices(category) {
  state.activeFilter = category;
  document.querySelectorAll('#serviceFilterTabs .filter-btn').forEach(tab => {
    if (tab.getAttribute('data-filter') === category) {
      tab.className = 'filter-btn active px-3.5 py-1 rounded-full font-semibold bg-[#E11D48] text-white transition';
    } else {
      tab.className = 'filter-btn px-3 py-1 rounded-full font-medium text-gray-400 hover:text-white bg-[#111723] border border-gray-800 transition';
    }
  });
  renderProducts();
  const el = document.getElementById('productsGrid');
  if (el) el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Cart Logic
function addToCart(productId, qty = 1, customUid = '') {
  const product = state.products.find(p => p.id === productId);
  if (!product) return;

  const existing = state.cart.find(i => i.product.id === productId);
  if (existing) {
    existing.quantity += qty;
  } else {
    state.cart.push({
      product,
      quantity: qty,
      ffUid: customUid || '5482910482'
    });
  }

  updateCartUI();
  showToast(`${product.name} added to cart!`);
  openCartModal();
}

function updateCartUI() {
  const badge = document.getElementById('cartCountBadge');
  const count = state.cart.reduce((sum, item) => sum + item.quantity, 0);

  if (badge) {
    if (count > 0) {
      badge.textContent = count;
      badge.classList.remove('hidden');
    } else {
      badge.classList.add('hidden');
    }
  }
}

function renderCartModalContent() {
  const list = document.getElementById('cartItemsList');
  const subtotalEl = document.getElementById('checkoutSubtotal');
  const discountEl = document.getElementById('checkoutDiscount');
  const totalEl = document.getElementById('checkoutTotal');

  if (!list) return;

  if (state.cart.length === 0) {
    list.innerHTML = `
      <div class="py-8 text-center text-gray-400 space-y-1 text-xs">
        <i class="fa-solid fa-cart-shopping text-2xl text-gray-600 block mb-1"></i>
        <p class="font-bold text-white">Your cart is empty.</p>
        <p class="text-gray-500">Add Free Fire services to checkout.</p>
      </div>
    `;
    if (subtotalEl) subtotalEl.textContent = '₹ 0.00';
    if (discountEl) discountEl.textContent = '- ₹ 0.00';
    if (totalEl) totalEl.textContent = '₹ 0.00';
    return;
  }

  list.innerHTML = state.cart.map((item, idx) => `
    <div class="bg-[#111723] p-3 rounded-2xl border border-gray-800 flex items-center justify-between gap-3 text-xs">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-lg bg-gray-800 flex items-center justify-center text-rose-500">
          <i class="fa-solid fa-gem text-xs"></i>
        </div>
        <div>
          <span class="font-bold text-white block truncate max-w-[140px]">${item.product.name}</span>
          <span class="text-[10px] text-gray-400">Qty: ${item.quantity} × ₹${item.product.price.toFixed(2)}</span>
        </div>
      </div>
      <div class="flex items-center gap-3">
        <span class="font-mono font-bold text-rose-400">₹ ${(item.product.price * item.quantity).toFixed(2)}</span>
        <button class="remove-item-btn text-gray-500 hover:text-rose-500 transition" data-idx="${idx}">
          <i class="fa-solid fa-trash text-xs"></i>
        </button>
      </div>
    </div>
  `).join('');

  list.querySelectorAll('.remove-item-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const idx = parseInt(btn.getAttribute('data-idx'), 10);
      state.cart.splice(idx, 1);
      updateCartUI();
      renderCartModalContent();
    });
  });

  const subtotal = state.cart.reduce((sum, item) => sum + (item.product.price * item.quantity), 0);
  let discount = 0;
  if (state.appliedCoupon) {
    discount = state.appliedCoupon.type === 'percentage' 
      ? (subtotal * state.appliedCoupon.value) / 100 
      : state.appliedCoupon.value;
  }
  const finalTotal = Math.max(0, subtotal - discount);

  if (subtotalEl) subtotalEl.textContent = `₹ ${subtotal.toFixed(2)}`;
  if (discountEl) discountEl.textContent = `- ₹ ${discount.toFixed(2)}`;
  if (totalEl) totalEl.textContent = `₹ ${finalTotal.toFixed(2)}`;
}

// Modals
function openCartModal() {
  renderCartModalContent();
  document.getElementById('cartModal')?.classList.remove('hidden');
}

function closeCartModal() {
  document.getElementById('cartModal')?.classList.add('hidden');
}

function openUserPanel() {
  const modal = document.getElementById('userPanelModal');
  if (!modal) return;

  document.getElementById('panelUserName').textContent = state.user.name;
  document.getElementById('panelUserEmail').textContent = state.user.email;
  document.getElementById('panelWalletBal').textContent = `₹ ${state.user.walletBalance.toFixed(2)}`;
  document.getElementById('panelUserUid').textContent = state.user.ffUid;
  document.getElementById('panelOrdersCount').textContent = `${state.userOrders.length} Orders`;

  const tbody = document.getElementById('panelOrdersTable');
  if (tbody) {
    tbody.innerHTML = state.userOrders.map(o => `
      <tr class="hover:bg-gray-800/40">
        <td class="p-3 font-mono font-bold text-rose-400">#${o.id}</td>
        <td class="p-3 font-semibold text-white">${o.productName}</td>
        <td class="p-3 font-mono text-gray-300">${o.ffUid}</td>
        <td class="p-3 font-mono font-bold text-emerald-400">₹ ${o.amount.toFixed(2)}</td>
        <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400">Completed</span></td>
      </tr>
    `).join('');
  }

  modal.classList.remove('hidden');
}

function closeUserPanel() {
  document.getElementById('userPanelModal')?.classList.add('hidden');
}

function openAdminModal() {
  const modal = document.getElementById('adminModal');
  if (!modal) return;

  const tbody = document.getElementById('adminOrdersTable');
  if (tbody) {
    tbody.innerHTML = state.userOrders.map(o => `
      <tr class="hover:bg-gray-800/40">
        <td class="p-3 font-mono font-bold text-rose-400">#${o.id}</td>
        <td class="p-3 font-semibold text-white">${o.productName}</td>
        <td class="p-3 font-mono text-gray-300">${o.ffUid}</td>
        <td class="p-3 font-mono font-bold text-emerald-400">₹ ${o.amount.toFixed(2)}</td>
        <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-mono bg-cyan-500/20 text-cyan-400">${o.mode}</span></td>
        <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400">Done</span></td>
        <td class="p-3"><button class="text-xs text-rose-400 hover:underline">View</button></td>
      </tr>
    `).join('');
  }

  modal.classList.remove('hidden');
}

function closeAdminModal() {
  document.getElementById('adminModal')?.classList.add('hidden');
}

function updateAuthUI() {
  const guestBtns = document.getElementById('authGuestButtons');
  const userBtns = document.getElementById('authUserButtons');
  const sidebarAuth = document.getElementById('sidebarAuthButtons');

  if (state.isLoggedIn) {
    guestBtns?.classList.add('hidden');
    userBtns?.classList.remove('hidden');
    if (sidebarAuth) {
      sidebarAuth.innerHTML = `
        <button id="sidebarDashboardBtn" class="w-full py-2 rounded-xl text-white bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-xs font-bold shadow-md transition flex items-center justify-center gap-1.5">
          <i class="fa-solid fa-chart-pie text-[10px]"></i> User Dashboard
        </button>
      `;
      document.getElementById('sidebarDashboardBtn')?.addEventListener('click', openUserPanel);
    }
  } else {
    guestBtns?.classList.remove('hidden');
    userBtns?.classList.add('hidden');
    if (sidebarAuth) {
      sidebarAuth.innerHTML = `
        <button id="sidebarLoginBtn" class="w-full py-2 rounded-xl text-gray-300 hover:text-white hover:bg-gray-800/60 text-xs font-semibold transition border border-gray-800 flex items-center justify-center gap-1.5">
          <i class="fa-solid fa-arrow-right-to-bracket text-[10px]"></i> Sign In
        </button>
        <button id="sidebarRegisterBtn" class="w-full py-2 rounded-xl text-white bg-gradient-to-r from-rose-600 to-rose-500 hover:from-rose-500 text-xs font-bold shadow-md shadow-rose-600/30 transition flex items-center justify-center gap-1.5">
          <i class="fa-solid fa-user-plus text-[10px]"></i> Create Account
        </button>
      `;
      document.getElementById('sidebarLoginBtn')?.addEventListener('click', () => document.getElementById('loginModal')?.classList.remove('hidden'));
      document.getElementById('sidebarRegisterBtn')?.addEventListener('click', () => document.getElementById('registerModal')?.classList.remove('hidden'));
    }
  }
}

// Event Listeners
function setupEventListeners() {
  // Navigation tabs
  document.querySelectorAll('.nav-tab').forEach(tab => {
    tab.addEventListener('click', (e) => {
      const view = tab.getAttribute('data-view');
      handleNavClick(view);
    });
  });

  // Sidebar nav items
  document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', () => {
      const view = item.getAttribute('data-view');
      const filter = item.getAttribute('data-filter');
      if (filter) {
        filterServices(filter);
      } else if (view) {
        handleNavClick(view);
      }
    });
  });

  // Filter pills
  document.querySelectorAll('#serviceFilterTabs .filter-btn, .filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const filter = btn.getAttribute('data-filter');
      if (filter) filterServices(filter);
    });
  });

  // Search input
  document.getElementById('searchInput')?.addEventListener('input', (e) => {
    const query = e.target.value.toLowerCase().trim();
    if (!query) {
      renderProducts();
      return;
    }
    const container = document.getElementById('productsGrid');
    if (!container) return;

    const matched = state.products.filter(p => 
      p.name.toLowerCase().includes(query) || 
      p.description.toLowerCase().includes(query) || 
      p.categorySlug.toLowerCase().includes(query)
    );

    if (matched.length === 0) {
      container.innerHTML = `
        <div class="col-span-full bg-[#111723] border border-gray-800 rounded-2xl p-10 text-center text-gray-400">
          <p class="font-bold text-sm text-gray-300">No services match "${e.target.value}".</p>
        </div>
      `;
    } else {
      const prevFilter = state.activeFilter;
      state.activeFilter = 'search';
      const tempProds = state.products;
      state.products = matched;
      renderProducts();
      state.products = tempProds;
      state.activeFilter = prevFilter;
    }
  });

  // Cart button
  document.getElementById('cartBtn')?.addEventListener('click', openCartModal);
  document.getElementById('closeCartBtn')?.addEventListener('click', closeCartModal);

  // Auth modals triggers
  document.getElementById('navLoginBtn')?.addEventListener('click', () => document.getElementById('loginModal')?.classList.remove('hidden'));
  document.getElementById('navRegisterBtn')?.addEventListener('click', () => document.getElementById('registerModal')?.classList.remove('hidden'));
  document.getElementById('sidebarLoginBtn')?.addEventListener('click', () => document.getElementById('loginModal')?.classList.remove('hidden'));
  document.getElementById('sidebarRegisterBtn')?.addEventListener('click', () => document.getElementById('registerModal')?.classList.remove('hidden'));

  document.getElementById('closeLoginBtn')?.addEventListener('click', () => document.getElementById('loginModal')?.classList.add('hidden'));
  document.getElementById('closeRegisterBtn')?.addEventListener('click', () => document.getElementById('registerModal')?.classList.add('hidden'));

  document.getElementById('switchToRegisterBtn')?.addEventListener('click', () => {
    document.getElementById('loginModal')?.classList.add('hidden');
    document.getElementById('registerModal')?.classList.remove('hidden');
  });

  document.getElementById('switchToLoginBtn')?.addEventListener('click', () => {
    document.getElementById('registerModal')?.classList.add('hidden');
    document.getElementById('loginModal')?.classList.remove('hidden');
  });

  // Login Form Submission
  document.getElementById('loginForm')?.addEventListener('submit', (e) => {
    e.preventDefault();
    state.isLoggedIn = true;
    updateAuthUI();
    document.getElementById('loginModal')?.classList.add('hidden');
    showToast('Signed in successfully! Welcome to FF Panel Store.');
  });

  // Register Form Submission
  document.getElementById('registerForm')?.addEventListener('submit', (e) => {
    e.preventDefault();
    const name = document.getElementById('regName').value.trim();
    const email = document.getElementById('regEmail').value.trim();
    const uid = document.getElementById('regUid').value.trim();

    state.user.name = name || state.user.name;
    state.user.email = email || state.user.email;
    if (uid) state.user.ffUid = uid;
    state.isLoggedIn = true;
    updateAuthUI();
    document.getElementById('registerModal')?.classList.add('hidden');
    showToast('Account created successfully!');
  });

  // User Dashboard Button
  document.getElementById('userDashboardBtn')?.addEventListener('click', openUserPanel);
  document.getElementById('closeUserPanelBtn')?.addEventListener('click', closeUserPanel);
  document.getElementById('userLogoutBtn')?.addEventListener('click', () => {
    state.isLoggedIn = false;
    updateAuthUI();
    closeUserPanel();
    showToast('Logged out successfully.');
  });

  // Admin Switcher
  document.getElementById('adminToggleBtn')?.addEventListener('click', openAdminModal);
  document.getElementById('closeAdminBtn')?.addEventListener('click', closeAdminModal);

  // Quick Recharge Form
  document.getElementById('quickRechargeForm')?.addEventListener('submit', (e) => {
    e.preventDefault();
    const uid = document.getElementById('qrUid').value.trim();
    const select = document.getElementById('qrSelectAmount');
    const prodId = parseInt(select.value, 10);

    if (!uid || uid.length < 5) {
      showToast('Please enter a valid Free Fire Player UID.', false);
      return;
    }

    addToCart(prodId, 1, uid);
  });

  // Place Order Checkout
  document.getElementById('placeOrderBtn')?.addEventListener('click', () => {
    if (state.cart.length === 0) {
      showToast('Your cart is empty.', false);
      return;
    }

    const uid = document.getElementById('checkoutUid').value.trim();
    if (!uid || uid.length < 5) {
      showToast('Please provide a valid Free Fire Player UID.', false);
      return;
    }

    const subtotal = state.cart.reduce((sum, item) => sum + (item.product.price * item.quantity), 0);
    let discount = 0;
    if (state.appliedCoupon) {
      discount = state.appliedCoupon.type === 'percentage' 
        ? (subtotal * state.appliedCoupon.value) / 100 
        : state.appliedCoupon.value;
    }
    const finalTotal = Math.max(0, subtotal - discount);

    // Record order in personal user orders
    const orderNumber = 'FF-' + Math.floor(1000 + Math.random() * 9000);
    state.cart.forEach(item => {
      state.userOrders.unshift({
        id: orderNumber,
        productName: item.product.name,
        quantity: item.quantity,
        amount: item.product.price * item.quantity,
        ffUid: uid,
        status: 'completed',
        date: 'Just now',
        mode: item.product.serviceMode === 'api' ? 'API' : 'Manual'
      });
    });

    state.cart = [];
    state.appliedCoupon = null;
    updateCartUI();
    closeCartModal();
    showToast(`Order #${orderNumber} confirmed! Recharged to UID ${uid}.`);
  });

  // Coupon application
  document.getElementById('applyCouponBtn')?.addEventListener('click', () => {
    const code = document.getElementById('couponCodeInput').value.trim().toUpperCase();
    const statusEl = document.getElementById('couponStatus');

    if (code === 'FFSAVE10') {
      state.appliedCoupon = { code: 'FFSAVE10', type: 'percentage', value: 10 };
      statusEl.textContent = "Promo 'FFSAVE10' applied: 10% OFF!";
      statusEl.classList.remove('hidden');
      renderCartModalContent();
      showToast("Coupon 'FFSAVE10' applied!");
    } else {
      showToast('Invalid coupon code. Try FFSAVE10', false);
    }
  });
}

function handleNavClick(view) {
  if (view === 'services') {
    const el = document.getElementById('productsGrid');
    if (el) el.scrollIntoView({ behavior: 'smooth' });
  } else if (view === 'about') {
    showToast('FF Panel Store: Authorized Free Fire Instant Top-Up Partner.');
  } else if (view === 'contact') {
    showToast('Customer Support: WhatsApp Helpline & Official Ticket Center.');
  } else if (view === 'terms') {
    showToast('Guaranteed Safe: Official Garena direct UID recharges only.');
  } else {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
}
