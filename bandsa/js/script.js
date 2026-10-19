document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const nome = document.getElementById('nome').value;
    const email = document.getElementById('email').value;
    
    if (nome === '' || email === '') {
        alert('Preencha os campos obrigatórios!');
        return;
    }
    
    document.getElementById('successMessage').classList.remove('d-none');
    this.reset();
});

