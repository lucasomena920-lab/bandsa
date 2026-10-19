(function () {
  function formatMoney(v) { return Number(v).toLocaleString('pt-PT',{minimumFractionDigits:2,maximumFractionDigits:2}) + '€'; }
  function escapeHtml(s){ return String(s).replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])); }

  let cart = null;
  try { cart = JSON.parse(localStorage.getItem('checkout_cart') || 'null'); } catch(e) { cart = null; }

  if ((!cart || !Array.isArray(cart) || !cart.length) && window.CHECKOUT_CART && Array.isArray(window.CHECKOUT_CART)) {
    cart = window.CHECKOUT_CART;
  }
  if (!cart || !Array.isArray(cart) || !cart.length) return;

  const listEl = document.getElementById('cartItems') || document.querySelector('.cart-items') || document.querySelector('#cartSummary') || document.querySelector('.resumo');
  const totalEl = document.getElementById('cartTotal') || document.querySelector('#orderTotal') || document.querySelector('.cart-total');
  const payBtnTotal = document.getElementById('totalText') || document.querySelector('.pay-total');

  if (listEl) {
    listEl.innerHTML = '';
    cart.forEach(it => {
      const row = document.createElement('div');
      row.className = 'd-flex justify-content-between align-items-center py-2 border-bottom';
      row.innerHTML = `<div><div class="fw-bold">${escapeHtml(it.name)}</div><small class="text-muted">Qt: ${Number(it.qty)}</small></div><div class="fw-semibold">${formatMoney(Number(it.price) * Number(it.qty))}</div>`;
      listEl.appendChild(row);
    });
  }

  const totalFromStorage = localStorage.getItem('checkout_total');
  let total = totalFromStorage ? Number(totalFromStorage) : cart.reduce((s,i)=> s + (Number(i.price) * Number(i.qty)), 0);
  if (totalEl) totalEl.textContent = formatMoney(total);
  if (payBtnTotal) payBtnTotal.textContent = formatMoney(total);
})();