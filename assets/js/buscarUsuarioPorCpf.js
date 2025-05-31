document.addEventListener('DOMContentLoaded', () => {
    const cpfInput = document.getElementById('cpf');

    if (cpfInput) {
        cpfInput.addEventListener('blur', () => {
            const cpf = cpfInput.value;

            if (cpf.length >= 11) {
                fetch('router.php?acao=buscarCpf', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'cpf=' + encodeURIComponent(cpf)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('nomeCompleto').value = data.nome;
                        document.getElementById('funcionario_id').value = data.id_usuario;
                    } else {
                        document.getElementById('nomeCompleto').value = '';
                        document.getElementById('funcionario_id').value = '';
                        alert('Usuário não encontrado!');
                    }
                })
                .catch(err => {
                    console.error('Erro na requisição:', err);
                });
            }
        });
    }
});
