function confirmDelCand(id) {
    if (confirm('Tem certeza que deseja excluir este candidato?')) {
      deleteCandidato(id);
    }
  }
  
  // Deletar Candidato
  function deleteCandidato(id) {
    const formData = new FormData();
    formData.append('id', id);
  
    fetch('/src/cdeleta.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.error) {
        alert("Erro: " + data.error);
      } else {
        alert("Candidato excluído com sucesso!");
        
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

  //Cadastrar Candidato

  document.addEventListener('DOMContentLoaded', function() {
    const selectVagas = document.getElementById('vagas_list');
  
    if (!selectVagas) {
        console.error("Erro: O elemento tipo com ID 'vagas_list' não foi encontrado!");
        return;
    }
  
    document.querySelector('#modalCandidatoForm').addEventListener('submit', async function(e) {
        e.preventDefault();
  
        const candidatoData = {
            nome: document.getElementById('nome').value.trim(),
            email: document.getElementById('email').value.trim(),
            habilidades: document.getElementById('habilidades').value.trim(),
            vaga_id: Array.from(selectVagas.selectedOptions).map(option => option.value)
        };
  
        candidatoData.vaga_id = candidatoData.vaga_id.length ? candidatoData.vaga_id : [0];
  
        try {
            const response = await fetch('../addCandidato.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(candidatoData)
            });
  
            const result = await response.json();
            
            if (!response.ok || !result.success) {
                throw new Error(result.error || 'Erro ao salvar candidato.');
            }
  
            alert('Candidato salvo!');
            this.reset();
  
            // Fechar modal
            const modalElem = document.getElementById('modalCandidato');
            const modalInstance = bootstrap.Modal.getInstance(modalElem);
            if (modalInstance) modalInstance.hide();
  
            setTimeout(() => {
              window.location.href = '../views/index.php';
            }, 1000);
  
        } catch (error) {
            console.error('Erro:', error);
            alert(error.message);
        }
    });
  });