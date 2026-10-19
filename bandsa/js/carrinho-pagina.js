// CARRINHO-PAGINA - COM MELHOR ESPAÇAMENTO
console.log('=== 🛒 PÁGINA DO CARRINHO INICIADA ===');

function carregarPaginaCarrinho() {
    console.log('🎯 CARREGANDO PÁGINA DO CARRINHO...');
    
    // 1. LER DADOS do localStorage
    const dados = localStorage.getItem('carrinhoIngressos');
    console.log('📦 Dados do localStorage:', dados);
    
    let itens = [];
    if (dados) {
        try {
            itens = JSON.parse(dados);
            console.log('✅ Dados parseados com sucesso:', itens);
        } catch (e) {
            console.error('❌ Erro ao parsear dados:', e);
            itens = [];
        }
    } else {
        console.log('ℹ️ Nenhum dado encontrado no localStorage');
        itens = [];
    }
    
    console.log('🎫 Itens para exibir:', itens);
    
    // 2. ELEMENTOS DA PÁGINA
    const secaoVazia = document.getElementById('carrinho-vazio');
    const secaoComItens = document.getElementById('carrinho-com-itens');
    const listaItens = document.getElementById('carrinho-itens-lista');
    const totalItensElement = document.getElementById('total-itens');
    
    console.log('📍 Elementos encontrados:');
    console.log('   - Carrinho vazio:', secaoVazia ? '✅' : '❌');
    console.log('   - Carrinho com itens:', secaoComItens ? '✅' : '❌');
    console.log('   - Lista de itens:', listaItens ? '✅' : '❌');
    
    // 3. DECIDIR O QUE MOSTRAR
    if (!itens || itens.length === 0) {
        console.log('🎯 Mostrando: CARRINHO VAZIO');
        if (secaoVazia) secaoVazia.classList.remove('d-none');
        if (secaoComItens) secaoComItens.classList.add('d-none');
    } else {
        console.log('🎯 Mostrando: CARRINHO COM ITENS');
        if (secaoVazia) secaoVazia.classList.add('d-none');
        if (secaoComItens) secaoComItens.classList.remove('d-none');
        
        // Atualizar contador de itens no header
        const totalItens = itens.reduce((sum, item) => sum + item.quantidade, 0);
        if (totalItensElement) {
            totalItensElement.textContent = `${totalItens} ${totalItens === 1 ? 'item' : 'itens'}`;
        }
        
        // 4. CRIAR HTML DOS ITENS - COM MELHOR ESPAÇAMENTO
        let html = '';
        let totalGeral = 0;
        
        itens.forEach((item, index) => {
            // Informações dos eventos
            const info = {
                'lisboa': {
                    nome: 'Lisboa - Coliseu dos Recreios',
                    data: '15 de Junho, 2023 - 21:00',
                    local: 'Coliseu dos Recreios',
                    icone: '🏛️',
                    cor: 'primary'
                },
                'porto': {
                    nome: 'Porto - Super Bock Arena', 
                    data: '22 de Junho, 2023 - 20:30',
                    local: 'Super Bock Arena',
                    icone: '🏟️',
                    cor: 'success'
                },
                'madrid': {
                    nome: 'Madrid - WiZink Center',
                    data: '30 de Junho, 2023 - 21:30', 
                    local: 'WiZink Center',
                    icone: '🎪',
                    cor: 'warning'
                }
            };
            
            const eventoInfo = info[item.evento] || {
                nome: item.evento,
                data: 'Data a definir',
                local: 'Local a definir',
                icone: '🎵',
                cor: 'secondary'
            };
            
            const subtotal = item.preco * item.quantidade;
            totalGeral += subtotal;
            
           html += `
    <div class="carrinho-item">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <!-- Ícone do Evento COM MARGEM -->
                <div class="col-md-2 mb-3 mb-md-0">
                    <div class="evento-image d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                        <span style="font-size: 2.5rem;">${eventoInfo.icone}</span>
                    </div>
                </div>
                
                <!-- Informações do Evento COM MENOS COLUNA -->
                <div class="col-md-3 mb-3 mb-md-0">
                    <h5 class="fw-bold text-${eventoInfo.cor} mb-3">${eventoInfo.nome}</h5>
                    <div class="evento-info">
                        <p class="text-muted mb-2">
                            <i class="far fa-calendar me-2"></i>
                            <strong>Data:</strong> ${eventoInfo.data}
                        </p>
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <strong>Local:</strong> ${eventoInfo.local}
                        </p>
                        <p class="text-muted mb-0">
                            <i class="fas fa-ticket-alt me-2"></i>
                            <strong>Quantidade:</strong> ${item.quantidade} ingresso(s)
                        </p>
                    </div>
                </div>
                
                <!-- Controles de Quantidade -->
                <div class="col-md-2 mb-3 mb-md-0">
                    <div class="quantity-controls d-flex align-items-center justify-content-center">
                        <button class="btn btn-outline-${eventoInfo.cor} me-2" onclick="diminuirQuantidade(${index})">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="mx-2 fw-bold fs-4">${item.quantidade}</span>
                        <button class="btn btn-outline-${eventoInfo.cor} ms-2" onclick="aumentarQuantidade(${index})">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Preço -->
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    <div class="price-tag text-${eventoInfo.cor} mb-2">
                        €${subtotal.toFixed(2)}
                    </div>
                    <small class="text-muted">
                        €${item.preco.toFixed(2)} cada
                    </small>
                </div>
                
                <!-- Botão Remover -->
                <div class="col-md-2 text-center">
                    <button class="btn btn-outline-danger btn-sm" onclick="removerDoCarrinho(${index})">
                        <i class="fas fa-trash me-1"></i>Remover
                    </button>
                </div>
            </div>
        </div>
    </div>

            `;
        });
        
        console.log('📄 Inserindo HTML na página...');
        if (listaItens) {
            listaItens.innerHTML = html;
            console.log('✅ HTML inserido com sucesso!');
        } else {
            console.error('❌ ERRO: Elemento listaItens não encontrado!');
        }
        
        // 5. ATUALIZAR RESUMO
        const taxa = totalGeral * 0.05;
        const totalFinal = totalGeral + taxa;
        
        console.log('💰 Atualizando resumo...');
        console.log('   Subtotal:', totalGeral);
        console.log('   Taxa:', taxa);
        console.log('   Total:', totalFinal);
        
        if (document.getElementById('resumo-subtotal')) {
            document.getElementById('resumo-subtotal').textContent = `€${totalGeral.toFixed(2)}`;
            document.getElementById('resumo-taxa').textContent = `€${taxa.toFixed(2)}`;
            document.getElementById('resumo-total').textContent = `€${totalFinal.toFixed(2)}`;
            console.log('✅ Resumo atualizado!');
        }
        
        // 6. ATUALIZAR CONTADOR DO MENU
        const contadorMenu = document.getElementById('carrinho-count');
        if (contadorMenu) {
            contadorMenu.textContent = totalItens;
            console.log('🔢 Contador do menu atualizado:', totalItens);
        }
        
        console.log('🎉🎉🎉 CARRINHO CARREGADO COM SUCESSO! 🎉🎉🎉');
    }
}

// FUNÇÕES AUXILIARES (mantenha as mesmas)
function aumentarQuantidade(index) {
    console.log('➕ Aumentando quantidade do item:', index);
    const itens = JSON.parse(localStorage.getItem('carrinhoIngressos')) || [];
    itens[index].quantidade += 1;
    localStorage.setItem('carrinhoIngressos', JSON.stringify(itens));
    
    // Feedback visual
    mostrarFeedback('✅ Quantidade aumentada!', 'success');
    carregarPaginaCarrinho();
}

function diminuirQuantidade(index) {
    console.log('➖ Diminuindo quantidade do item:', index);
    const itens = JSON.parse(localStorage.getItem('carrinhoIngressos')) || [];
    
    if (itens[index].quantidade > 1) {
        itens[index].quantidade -= 1;
        localStorage.setItem('carrinhoIngressos', JSON.stringify(itens));
        mostrarFeedback('✅ Quantidade diminuída!', 'info');
        carregarPaginaCarrinho();
    } else {
        if (confirm('❓ Remover este item do carrinho?')) {
            removerDoCarrinho(index);
        }
    }
}

function removerDoCarrinho(index) {
    console.log('🗑️ Removendo item:', index);
    const itens = JSON.parse(localStorage.getItem('carrinhoIngressos')) || [];
    const itemRemovido = itens.splice(index, 1)[0];
    localStorage.setItem('carrinhoIngressos', JSON.stringify(itens));
    mostrarFeedback(`✅ "${itemRemovido.evento}" removido do carrinho!`, 'warning');
    carregarPaginaCarrinho();
}

function mostrarFeedback(mensagem, tipo) {
    // Criar toast de feedback
    const toast = document.createElement('div');
    toast.className = `position-fixed top-0 end-0 p-3`;
    toast.style.zIndex = '9999';
    
    toast.innerHTML = `
        <div class="toast show align-items-center text-bg-${tipo} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${tipo === 'success' ? 'check' : 'info'}-circle me-2"></i>
                    ${mensagem}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// INICIALIZAR
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 Página carrinho.html carregada!');
    carregarPaginaCarrinho();
});

console.log('✅ PÁGINA DO CARRINHO PRONTA!');