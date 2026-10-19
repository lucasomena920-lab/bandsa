// Seleção de método de pagamento
function selectPayment(method) {
    // Remover seleção anterior
    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('selected');
    });
    
    // Adicionar seleção atual
    const selectedMethod = document.querySelector(`.payment-method input[value="${method}"]`);
    selectedMethod.checked = true;
    selectedMethod.closest('.payment-method').classList.add('selected');
    
    // Mostrar/ocultar campos do cartão de crédito
    const creditCardFields = document.getElementById('creditCardFields');
    if (method === 'cartao') {
        creditCardFields.style.display = 'block';
        setupCreditCardValidation();
    } else {
        creditCardFields.style.display = 'none';
    }
}

// Validação do formulário
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (validateForm()) {
        // Mostrar loading
        const finalizeBtn = document.getElementById('finalizeBtn');
        finalizeBtn.disabled = true;
        finalizeBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Processando...';
        
        // Enviar formulário após breve delay para ver o loading
        setTimeout(() => {
            this.submit();
        }, 1000);
    }
});

function validateForm() {
    let isValid = true;
    
    // Validar campos obrigatórios
    const requiredFields = document.querySelectorAll('input[required], select[required]');
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            showFieldError(field, 'Este campo é obrigatório');
            isValid = false;
        } else {
            clearFieldError(field);
        }
    });
    
    // Validar método de pagamento
    const paymentSelected = document.querySelector('input[name="paymentMethod"]:checked');
    if (!paymentSelected) {
        alert('Por favor, selecione um método de pagamento');
        isValid = false;
    }
    
    return isValid;
}

// Funções auxiliares de validação
function showFieldError(field, message) {
    clearFieldError(field);
    field.classList.add('is-invalid');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

function clearFieldError(field) {
    field.classList.remove('is-invalid');
    const existingError = field.parentNode.querySelector('.invalid-feedback');
    if (existingError) {
        existingError.remove();
    }
}

// Preenchimento automático
document.addEventListener('DOMContentLoaded', function() {
    // Preencher dados do utilizador se disponíveis
    autoFillUserData();
});

function autoFillUserData() {
    // Em produção, isto viria da sessão PHP
    // Por enquanto, vamos apenas garantir que os campos estão válidos
    console.log('Checkout carregado com sucesso!');
}