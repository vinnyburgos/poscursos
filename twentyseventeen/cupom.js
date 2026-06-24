// JQUERY's

      $(document).ready(function() {

        // disparo para a TI 
        $("#btnGerarURL").click(function(e) {
            e.preventDefault();
            
            // Captura o nome do curso selecionado (apenas o nome, sem mneumonico e modalidade)
            const selectCurso = document.getElementById('selectCurso');
            let descricao_curso = '';
            
            if (selectCurso && selectCurso.value) {
                try {
                    const cursoInfo = JSON.parse(selectCurso.value);
                    descricao_curso = cursoInfo.nome; // Apenas o nome do curso
                } catch (error) {
                    console.error('Erro ao extrair nome do curso:', error);
                    descricao_curso = selectCurso.options[selectCurso.selectedIndex].text.split(' -')[0]; // Fallback
                }
            }
            
            // Captura o valor do ID da combinação
            const inputId = document.getElementById('inputId');
            const oferta = inputId ? inputId.value.trim() : '';
            
            // Captura o valor do cupom
            const inputCupom = document.getElementById('inputCupom');
            const cupom = inputCupom ? inputCupom.value.trim() : '';
            
            console.log('Dados a serem enviados:', {
                descricao_curso: descricao_curso,
                oferta: oferta,
                cupom: cupom
            });
            
            // Validação básica
            if (!descricao_curso || !oferta || !cupom) {
                console.error('Dados incompletos:', {descricao_curso, oferta, cupom});
                $(".selecioneError").remove();
                $("#btnGerarURL").after("<p class='selecioneError'>Por favor, preencha todos os campos obrigatórios.</p>");
                return;
            }

            fetch('https://cursos.unisuam.edu.br/wp-content/themes/twentyseventeen/sendAPI_cupom.php', {   
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    descricao_curso: descricao_curso,  // Nome do curso apenas
                    oferta: oferta,                    // ID da combinação
                    cupom: cupom                       // Valor do cupom
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Resposta da API:', data);
                if(data.data && data.data.redirect_url) {
                    window.location.href = data.data.redirect_url;
                    console.log("Redirecting to: " + data.data.redirect_url);
                } else {
                    console.error("No redirect_url in response", data);
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
                $(".selecioneError").remove();
                $("#btnGerarURL").after("<p class='selecioneError'>Erro ao processar solicitação. Tente novamente.</p>");
            });
        });
    });

