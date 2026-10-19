// Função para iniciar o checkout
function iniciarCheckout() {
    // Obter os dados do carrinho da página
    const cartItems = JSON.parse(localStorage.getItem('carrinhoItens') || '[]');
    const subtotal = parseFloat(document.getElementById('resumo-subtotal').textContent.replace('€', '').replace(',', '.'));
    const total = parseFloat(document.getElementById('resumo-total').textContent.replace('€', '').replace(',', '.'));
    const taxa = parseFloat(document.getElementById('resumo-taxa').textContent.replace('€', '').replace(',', '.'));

    // Verificar se o carrinho tem itens
    if (cartItems.length === 0) {
        alert('Seu carrinho está vazio! Adicione itens antes de finalizar.');
        return;
    }

    // Criar formulário oculto para enviar dados para o PHP
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'checkout.php';
    form.style.display = 'none';

    // Adicionar dados totais
    addHiddenField(form, 'subtotal', subtotal);
    addHiddenField(form, 'taxa', taxa);
    addHiddenField(form, 'total', total);
    addHiddenField(form, 'item_count', cartItems.length);

    // Adicionar cada item
    cartItems.forEach((item, index) => {
        addHiddenField(form, `items[${index}][id]`, item.id);
        addHiddenField(form, `items[${index}][nome]`, item.nome);
        addHiddenField(form, `items[${index}][preco]`, item.preco);
        addHiddenField(form, `items[${index}][quantidade]`, item.quantidade);
        addHiddenField(form, `items[${index}][subtotal]`, item.preco * item.quantidade);
        addHiddenField(form, `items[${index}][imagem]`, item.imagem || '');
        addHiddenField(form, `items[${index}][data]`, item.data || '');
        addHiddenField(form, `items[${index}][local]`, item.local || '');
    });

    // Enviar formulário
    document.body.appendChild(form);
    form.submit();
}

// Função auxiliar para adicionar campos ocultos
function addHiddenField(form, name, value) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value;
    form.appendChild(input);
}

// Salvar dados do carrinho no localStorage sempre que houver alterações
function salvarCarrinhoNoLocalStorage() {
    const items = [];
    
    // Obter itens da página (ajuste conforme sua estrutura)
    document.querySelectorAll('.carrinho-item').forEach(itemElement => {
        const nome = itemElement.querySelector('.evento-nome')?.textContent || 'Ingresso';
        const precoTexto = itemElement.querySelector('.price-tag')?.textContent || '€0,00';
        const preco = parseFloat(precoTexto.replace('€', '').replace(',', '.'));
        const quantidade = parseInt(itemElement.querySelector('.quantity-value')?.textContent || '1');
        
        if (nome !== 'Ingresso' && preco > 0) {
            items.push({
                id: Date.now() + Math.random(), // ID único
                nome: nome,
                preco: preco,
                quantidade: quantidade,
                imagem: itemElement.querySelector('.evento-image img')?.src || ''
            });
        }
    });

    localStorage.setItem('carrinhoItens', JSON.stringify(items));
}

// Atualizar botão de checkout
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se há itens no carrinho
    const cartItems = JSON.parse(localStorage.getItem('carrinhoItens') || '[]');
    
    if (cartItems.length > 0) {
        // Atualizar contador
        document.getElementById('carrinho-count').textContent = cartItems.length;
        
        // Atualizar total na página
        let subtotal = 0;
        cartItems.forEach(item => {
            subtotal += item.preco * item.quantidade;
        });
        
        const taxa = subtotal * 0.10; // 10% de taxa
        const total = subtotal + taxa;
        
        // Atualizar resumo
        document.getElementById('resumo-subtotal').textContent = `€${subtotal.toFixed(2).replace('.', ',')}`;
        document.getElementById('resumo-taxa').textContent = `€${taxa.toFixed(2).replace('.', ',')}`;
        document.getElementById('resumo-total').textContent = `€${total.toFixed(2).replace('.', ',')}`;
    }
});