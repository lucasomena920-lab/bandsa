(function () {
  function parsePrice(str) {
    if (str == null) return 0;
    let s = String(str).replace(/[^\d,.\-]/g, '').trim();
    if (s.indexOf(',') !== -1 && s.indexOf('.') === -1) s = s.replace(',', '.');
    return Number(parseFloat(s) || 0);
  }

  function collectCartFromDOM() {
    const items = [];
    document.querySelectorAll('.carrinho-item').forEach(node => {
      const dataset = node.dataset || {};
      let id = dataset.id ? (dataset.id|0) : null;
      let name = dataset.name || null;
      let price = dataset.price ? parsePrice(dataset.price) : null;
      let qty = dataset.qty ? parseInt(dataset.qty, 10) || 1 : null;

      if (!name) {
        const nameEl = node.querySelector('.item-name, .title, h4, h5, .produto-nome, .card-title');
        if (nameEl) name = nameEl.textContent.trim();
      }
      if (price == null) {
        const priceEl = node.querySelector('.price-tag, .price, .valor, .product-price, .badge');
        if (priceEl) price = parsePrice(priceEl.textContent);
      }
      if (qty == null) {
        const qtyInput = node.querySelector('input[type="number"], .qty input');
        if (qtyInput) qty = Math.max(1, parseInt(qtyInput.value || 1));
        else {
          const qtyEl = node.querySelector('.qty, .quantity, .item-qty');
          if (qtyEl) {
            const q = (qtyEl.textContent || '').match(/\d+/);
            qty = q ? Math.max(1, parseInt(q[0])) : 1;
          } else qty = 1;
        }
      }
      if (name) items.push({ product_id: id, name: name, price: Number((price||0).toFixed(2)), qty: qty|0 });
    });

    if (!items.length) {
      const rows = document.querySelectorAll('[data-cart-item], .cart-row, .cart-item-row, .cart-item');
      rows.forEach(r => {
        const id = r.dataset.id ? (r.dataset.id|0) : null;
        const nameEl = r.querySelector('.name, .product-name, h4, h5');
        const priceEl = r.querySelector('.price, .product-price');
        const qtyInput = r.querySelector('input[type="number"], .qty input');
        const name = nameEl ? nameEl.textContent.trim() : null;
        const price = priceEl ? parsePrice(priceEl.textContent) : 0;
        const qty = qtyInput ? Math.max(1, parseInt(qtyInput.value || 1)) : (r.dataset.qty ? (r.dataset.qty|0) : 1);
        if (name) items.push({ product_id: id, name, price: Number(price.toFixed(2)), qty: qty|0 });
      });
    }

    if (!items.length) {
      const legacy = JSON.parse(localStorage.getItem('cart_items') || 'null');
      if (Array.isArray(legacy) && legacy.length) {
        legacy.forEach(it => items.push({ product_id: it.product_id ?? null, name: it.name, price: Number((it.price||0)), qty: it.qty|0 }));
      }
    }

    return items;
  }

  function computeTotal(items) {
    return items.reduce((s,i) => s + (Number(i.price || 0) * (Number(i.qty) || 1)), 0);
  }

  function goToCheckout() {
    const items = collectCartFromDOM();
    if (!items.length) { alert('O carrinho parece vazio.'); return; }
    const payload = items.map(i => ({ product_id: i.product_id ?? null, name: i.name, price: Number(i.price), qty: Number(i.qty) }));
    localStorage.setItem('checkout_cart', JSON.stringify(payload));
    localStorage.setItem('checkout_total', computeTotal(payload).toFixed(2));
    window.location.href = 'checkout.html';
  }

  function attachFinalizeBtn() {
    const btn = document.querySelector('#finalizarBtn') || document.querySelector('.btn-checkout') ||
                Array.from(document.querySelectorAll('button')).find(b => /finalizar compra|finalizar|checkout|pagar/i.test(b.textContent));
    if (btn) btn.addEventListener('click', e => { e.preventDefault(); goToCheckout(); });
    else document.querySelectorAll('[data-action="checkout"]').forEach(b => b.addEventListener('click', e => { e.preventDefault(); goToCheckout(); }));
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', attachFinalizeBtn);
  else attachFinalizeBtn();
})();