// tour-compras.js - Sistema de Compra de Bilhetes
console.log('=== 🎵 SISTEMA DE COMPRAS INICIADO ===');

class SistemaCompras {
    constructor() {
        this.precos = {
            'lisboa': 45.00,
            'porto': 55.00,
            'madrid': 60.00
        };
        
        this.init();
    }

    init() {
        console.log('🛒 Inicializando sistema de compras...');
        this.configurarEventos();
        this.atualizarContadorCarrinho();
    }

    configurarEventos() {
        // Evento para botão "Comprar" (adiciona ao carrinho)
        document.querySelectorAll('.btn-comprar').forEach(botao => {
            botao.addEventListener('click', (e) => {
                const evento = e.target.getAttribute('data-evento');
                this.comprarIngresso(evento, false); // false = não redireciona
            });
        });

        // Evento para botão "Comprar Agora" (adiciona e redireciona)
        document.querySelectorAll('.btn-comprar-agora').forEach(botao => {
            botao.addEventListener('click', (e) => {
                const evento = e.target.getAttribute('data-evento');
                this.comprarIngresso(evento, true); // true = redireciona
            });
        });

        console.log('✅ Eventos de compra configurados');
    }

    comprarIngresso(evento, redirecionar = false) {
        console.log(`🎫 Comprando ingresso para: ${evento}`);
        
        // Obter quantidade selecionada
        const select = document.querySelector(`.quantidade-select[data-evento="${evento}"]`);
        const quantidade = parseInt(select ? select.value : 1);
        
        const preco = this.precos[evento];
        
        if (!preco) {
            console.error('❌ Preço não encontrado para:', evento);
            this.mostrarAlerta('Erro: Evento não encontrado!', 'error');
            return;
        }

        console.log(`📊 Detalhes: ${quantidade} ingresso(s) a €${preco}`);

        // Adicionar ao carrinho
        this.adicionarAoCarrinho(evento, quantidade, preco);
        
        // Feedback visual
        this.mostrarFeedbackCompra(evento, quantidade);
        
        // Redirecionar se for "Comprar Agora"
        if (redirecionar) {
            setTimeout(() => {
                window.location.href = 'carrinho.html';
            }, 1500);
        }
    }

    adicionarAoCarrinho(evento, quantidade, preco) {
        console.log('🛒 Adicionando ao carrinho...');
        
        // Carregar carrinho atual
        let carrinho = JSON.parse(localStorage.getItem('carrinhoIngressos')) || [];
        
        // Verificar se já existe no carrinho
        const itemExistente = carrinho.find(item => item.evento === evento);
        
        if (itemExistente) {
            // Atualizar quantidade se já existir
            itemExistente.quantidade += quantidade;
            console.log('📈 Quantidade atualizada no item existente');
        } else {
            // Adicionar novo item
            carrinho.push({
                evento: evento,
                quantidade: quantidade,
                preco: preco,
                id: Date.now(),
                dataAdicao: new Date().toISOString()
            });
            console.log('🆕 Novo item adicionado ao carrinho');
        }
        
        // Salvar no localStorage
        localStorage.setItem('carrinhoIngressos', JSON.stringify(carrinho));
        console.log('💾 Carrinho salvo:', carrinho);
        
        // Atualizar contador
        this.atualizarContadorCarrinho();
        
        return true;
    }

    atualizarContadorCarrinho() {
        const carrinho = JSON.parse(localStorage.getItem('carrinhoIngressos')) || [];
        const totalItens = carrinho.reduce((total, item) => total + item.quantidade, 0);
        
        const contador = document.getElementById('carrinho-count');
        if (contador) {
            contador.textContent = totalItens;
            
            // Efeito visual quando atualiza
            if (totalItens > 0) {
                contador.style.transform = 'scale(1.3)';
                setTimeout(() => {
                    contador.style.transform = 'scale(1)';
                }, 300);
            }
        }
        
        console.log('🔢 Contador atualizado:', totalItens);
    }

    mostrarFeedbackCompra(evento, quantidade) {
        const nomesEventos = {
            'lisboa': '🎵 Lisboa - Coliseu dos Recreios',
            'porto': '🎵 Porto - Super Bock Arena',
            'madrid': '🎵 Madrid - WiZink Center'
        };
        
        const nomeEvento = nomesEventos[evento] || evento;
        
        // Criar notificação personalizada
        const notificacao = document.createElement('div');
        notificacao.className = 'compra-notificacao';
        notificacao.innerHTML = `
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" 
                 style="z-index: 9999; min-width: 300px;" role="alert">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="alert-heading mb-1">✅ Ingresso Adicionado!</h6>
                        <p class="mb-1"><strong>${quantidade}x</strong> ${nomeEvento}</p>
                        <small class="text-muted">Acesse o carrinho para finalizar</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        document.body.appendChild(notificacao);
        
        // Remover após 4 segundos
        setTimeout(() => {
            if (notificacao.parentNode) {
                notificacao.remove();
            }
        }, 4000);
        
        console.log('📢 Feedback de compra exibido');
    }

    mostrarAlerta(mensagem, tipo = 'info') {
        const alerta = document.createElement('div');
        alerta.className = `alert alert-${tipo} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
        alerta.style.zIndex = '9999';
        alerta.innerHTML = `
            ${mensagem}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alerta);
        
        setTimeout(() => {
            if (alerta.parentNode) {
                alerta.remove();
            }
        }, 3000);
    }

    // Método para limpar carrinho (útil para testes)
    limparCarrinho() {
        localStorage.removeItem('carrinhoIngressos');
        this.atualizarContadorCarrinho();
        console.log('🗑️ Carrinho limpo');
    }
}

// ✅ Inicializar sistema quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 Página tour.html carregada - Iniciando sistema de compras...');
    window.sistemaCompras = new SistemaCompras();
});

// ✅ Funções globais para uso em outros lugares
window.comprarIngresso = function(evento, redirecionar = false) {
    if (window.sistemaCompras) {
        window.sistemaCompras.comprarIngresso(evento, redirecionar);
    }
};

window.limparCarrinho = function() {
    if (window.sistemaCompras) {
        window.sistemaCompras.limparCarrinho();
    }
};

console.log('🎉 SISTEMA DE COMPRAS CONFIGURADO!');
console.log('Comandos disponíveis:');
console.log('- comprarIngresso("lisboa")');
console.log('- comprarIngresso("lisboa", true) → Comprar e redirecionar');
console.log('- limparCarrinho()');