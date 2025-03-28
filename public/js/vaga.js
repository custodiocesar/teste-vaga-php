function confirmDelVag(id) {
    if (confirm('Tem certeza que deseja excluir esta vaga?')) {
      deleteVaga(id);
    }
  }
  
    // Deletar Vaga
  function deleteVaga(id) {
    const formData = new FormData();
    formData.append('id', id);
  
    fetch('/src/vdeleta.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        alert("Erro: " + data.error);
      } else {
        alert("Vaga excluída com sucesso!");
        
        const row = document.getElementById(`row-${id}`);
        if (row) row.remove();
        
        setTimeout(() => {
          window.location.href = '../views/index.php';
        }, 1000);
      }
    })
    .catch(error => {
      alert("Erro na requisição: " + error.message);
    });
  }

// Cadastrar Vaga
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('#modalVagaForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const vagaData = {
            titulo: document.getElementById('titulo').value.trim(),
            descricao: document.getElementById('descricao').value.trim(),
            tipo: document.getElementById('tipo').value,
            status: document.getElementById('status').checked ? 'pausada' : 'ativa'
        };

        if (!vagaData.titulo) return alert('Título é obrigatório!');
        if (!vagaData.descricao) return alert('Descrição é obrigatória!');
        if (!vagaData.tipo || !['CLT', 'PJ', 'Freelancer'].includes(vagaData.tipo)) {
            return alert('Tipo inválido! Escolha entre CLT, PJ ou Freelancer.');
        }

        try {
            const response = await fetch('../addVaga.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(vagaData)
            });

            const result = await response.json();
            
            if (!response.ok || !result.success) {
                throw new Error(result.error || 'Falha ao salvar');
            }

            alert('Vaga salva!');

            const modalElem = document.getElementById('modalVaga');
            const modalInstance = bootstrap.Modal.getInstance(modalElem);
            if (modalInstance) modalInstance.hide();

            this.reset();
            
            setTimeout(() => {
              window.location.href = '../views/index.php';
            }, 1000);

        } catch (error) {
            console.error('Erro:', error);
            alert(error.message);
        }
    });
});