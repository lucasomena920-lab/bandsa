// CARRINHO COM DEBUG VISUAL 
console.log('=== 🎵 CARRINHO DA BANDA INICIADO ===');

// Sistema de carrinho SUPER SIMPLES
class CarrinhoBanda {
    constructor() {
        console.log('🔄 Carrinho inicializado');
        this.carregarDados();
        this.mostrarStatus(); // DEBUG VISUAL
    }

    carregarDados() {
        const dados = localStorage.getItem('carrinhoIngressos');
        console.log('📦 Lendo localStorage:', dados);
        
        try {
            this.itens = JSON.parse(dados) || [];
        } catch (e) {
            console.error('❌ Erro ao carregar:', e);
            this.itens = [];
        }
        
        console.log('🎫 Itens no carrinho:', this.itens);
        return this.itens;
    }

    salvarDados() {
        localStorage.setItem('carrinhoIngressos', JSON.stringify(this.itens));
        console.log('💾 Salvando no localStorage:', this.itens);
        
        // DEBUG: Mostrar o que foi salvo
        this.mostrarStatus();
    }

    adicionarIngresso(evento, quantidade, preco) {
        console.log('🎯🎯🎯 ADICIONANDO INGRESSO 🎯🎯🎯');
        console.log('Evento:', evento);
        console.log('Quantidade:', quantidade);
        console.log('Preço:', preco);
        
        // Carregar dados atuais
        this.carregarDados();
        
        // Verificar se já existe
        const itemExistente = this.itens.find(item => item.evento === evento);
        
        if (itemExistente) {
            itemExistente.quantidade += quantidade;
            console.log('📈 Quantidade atualizada');
        } else {
            this.itens.push({
                evento: evento,
                quantidade: quantidade,
                preco: preco,
                id: Date.now()
            });
            console.log('🆕 Novo item adicionado');
        }
        
        // SALVAR e ATUALIZAR
        this.salvarDados();
        this.atualizarContador();
        this.mostrarAlertaSucesso(evento, quantidade);
        
        return true;
    }

    atualizarContador() {
        const contador = document.getElementById('carrinho-count');
        if (contador) {
            const total = this.itens.reduce((sum, item) => sum + item.quantidade, 0);
            contador.textContent = total;
            console.log('🔢 Contador atualizado para:', total);
            
            // Efeito visual no contador
            contador.style.transform = 'scale(1.3)';
            setTimeout(() => {
                contador.style.transform = 'scale(1)';
            }, 300);
        }
    }

    mostrarAlertaSucesso(evento, quantidade) {
        const nomes = {
            'lisboa': '🎵 Lisboa - Coliseu dos Recreios',
            'porto': '🎵 Porto - Super Bock Arena', 
            'madrid': '🎵 Madrid - WiZink Center'
        };
        
        const mensagem = `
            <div style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: #28a745;
                color: white;
                padding: 15px;
                border-radius: 10px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                z-index: 9999;
                font-weight: bold;
            ">
                ✅ <strong>${quantidade} INGRESSO(S) ADICIONADO(S)!</strong><br>
                <small>${nomes[evento]}</small>
            </div>
        `;
        
        // Adicionar alerta visual
        const alerta = document.createElement('div');
        alerta.innerHTML = mensagem;
        document.body.appendChild(alerta);
        
        // Remover após 3 segundos
        setTimeout(() => {
            alerta.remove();
        }, 3000);
        
        console.log('📢 Alerta mostrado:', mensagem);
    }

    // DEBUG VISUAL - Mostrar status atual
    mostrarStatus() {
        const total = this.itens.reduce((sum, item) => sum + item.quantidade, 0);
        console.log(`%c📊 STATUS DO CARRINHO: ${total} ITENS`, `
            background: #007bff;
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
        `);
        
        this.itens.forEach(item => {
            console.log(`   🎫 ${item.evento}: ${item.quantidade} x €${item.preco}`);
        });
    }

    getItens() {
        return this.itens;
    }
}

// ✅ INICIALIZAR CARRINHO GLOBAL
window.carrinhoBanda = new CarrinhoBanda();

// ✅ FUNÇÃO PRINCIPAL - ADICIONAR AO CARRINHO
window.adicionarAoCarrinho = function(evento) {
    console.log('🖱️🖱️🖱️ BOTÃO CLICADO! 🖱️🖱️🖱️');
    console.log('Evento recebido:', evento);
    
    // VALORES FIXOS para garantir que funciona
    const quantidade = 1; // Sempre 1 ingresso para teste
    const precos = {
        'lisboa': 45,
        'porto': 55, 
        'madrid': 60
    };
    
    const preco = precos[evento];
    console.log('💰 Preço definido:', preco);
    
    if (!preco) {
        console.error('❌ EVENTO NÃO RECONHECIDO:', evento);
        alert('❌ Erro: Evento não reconhecido: ' + evento);
        return;
    }
    
    // CHAMAR FUNÇÃO DO CARRINHO
    if (window.carrinhoBanda && window.carrinhoBanda.adicionarIngresso) {
        console.log('🚀 Chamando carrinhoBanda.adicionarIngresso...');
        window.carrinhoBanda.adicionarIngresso(evento, quantidade, preco);
        console.log('✅ Função executada com sucesso!');
    } else {
        console.error('❌ CARRINHO NÃO DISPONÍVEL');
        alert('❌ Erro: Sistema de carrinho não carregado!');
    }
}

// ✅ FUNÇÃO COMPRAR AGORA
window.comprarAgora = function(evento) {
    console.log('⚡ COMPRAR AGORA:', evento);
    adicionarAoCarrinho(evento);
    
    // Redirecionar para carrinho após 1 segundo
    setTimeout(() => {
        console.log('🔄 Redirecionando para carrinho.html...');
        window.location.href = 'carrinho.html';
    }, 1000);
}

// ✅ ATUALIZAR QUANDO A PÁGINA CARREGA
document.addEventListener('DOMContentLoaded', function() {
    console.log('📄 Página carregada - Carrinho pronto!');
    if (window.carrinhoBanda) {
        window.carrinhoBanda.atualizarContador();
    }
});

console.log('🎉🎉🎉 CARRINHO CONFIGURADO COM SUCESSO! 🎉🎉🎉');
console.log('Comandos disponíveis:');
console.log(' - adicionarAoCarrinho("lisboa")');
console.log(' - carrinhoBanda.mostrarStatus()');