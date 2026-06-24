// JQUERY's

    
// VANILLA's

    // disparo para getAPI para receber os dados dos cursos
    document.addEventListener('DOMContentLoaded', function() {
        const boxes = document.querySelectorAll('.box-item');
        boxes.forEach(box => {
            const mneumonico = box.getAttribute('data-mneumonico');
            if (mneumonico) {
                fetch(`/novo-pos/wp-content/themes/twentyseventeen/getAPI.php?mneumonico=${mneumonico}`)
                .then(response => response.json())
                .then(data => {
                    if (data.investimentos) {
                        const menorValor = Math.min(...data.investimentos.map(i => i.valor));
                        const valorSemDesconto = Math.ceil(menorValor * 2.5);
                        box.querySelector('.apiGets').innerHTML = `
                            <p class="apenasPresencial partir colorGreen">A partir de:</p>
                            <span class="apenasPresencial">18x de R$</span> 
                            <span id="valorSDesconto">${valorSemDesconto.toFixed(2).replace('.', ',')}</span><br>
                            <span class="apenasPresencial">18x de R$</span> 
                            <span id="valorSDesconto">${menorValor.toFixed(2).replace('.', ',')}</span>
                            <h6>Carga horária:</h6>
                            <p class="conteudoBox"><span id="cargaHoraria">${data.resumo['carga-horaria']}</span></p>
                            <h6>Unidades:</h6>
                            ${[...new Set(data.investimentos.map(i => i.unidade))].map(unidade => `<p class="conteudoBox"><span id="cargaHoraria">${unidade}</span></p>`).join('')}
                        `;
                    } else {
                        box.querySelector('.apiGets').innerHTML = '<p>Em breve.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    box.querySelector('.apiGets').innerHTML = '<p>Erro ao carregar dados.</p>';
                });
            }
        });
    });

    // controle dos filtros da busca 
    document.addEventListener('DOMContentLoaded', function() {
		const boxes = document.querySelectorAll('.box-item');
		const areaInteresseSelect = document.getElementById('areaInteresse');
		const nomeCursoSelect = document.getElementById('areaNomeCurso');
		const modalidadeSelect = document.getElementById('modalidade');
		function updateNomeCursoOptions() {
			const selectedCategoria = areaInteresseSelect.value;
			nomeCursoSelect.innerHTML = '<option value="">Todos</option>';
			const filteredCursos = new Set();

			boxes.forEach(box => {
				const boxCategorias = Array.from(box.querySelectorAll('.categoria')).map(cat => cat.textContent.trim());
				const boxCurso = box.querySelector('.titleBox a').textContent.trim();

				if (!selectedCategoria || boxCategorias.includes(selectedCategoria)) {
					filteredCursos.add(boxCurso);
				}
			});

			filteredCursos.forEach(curso => {
				const option = document.createElement('option');
				option.value = curso;
				option.textContent = curso;
				nomeCursoSelect.appendChild(option);
			});
		}

		function updateModalidadeOptions() {
			const selectedCategoria = areaInteresseSelect.value;
			const selectedCurso = nomeCursoSelect.value;
			modalidadeSelect.innerHTML = '<option value="">Todas</option>';
			const filteredModalidades = new Set();

			boxes.forEach(box => {
				const boxCategorias = Array.from(box.querySelectorAll('.categoria')).map(cat => cat.textContent.trim());
				const boxCurso = box.querySelector('.titleBox a').textContent.trim();
				const boxModalidade = box.querySelector('.innerMod').textContent.trim();

				if ((!selectedCategoria || boxCategorias.includes(selectedCategoria)) &&
					(!selectedCurso || boxCurso === selectedCurso)) {
					filteredModalidades.add(boxModalidade);
				}
			});

			filteredModalidades.forEach(mod => {
				const option = document.createElement('option');
				option.value = mod;
				option.textContent = mod;
				modalidadeSelect.appendChild(option);
			});
		}

		areaInteresseSelect.addEventListener('change', () => {
			updateNomeCursoOptions();
			updateModalidadeOptions();
			filterBoxes();
		});

		nomeCursoSelect.addEventListener('change', () => {
			updateModalidadeOptions();
			filterBoxes();
		});
		const limparFiltrosBtn = document.getElementById('limparFiltros');

		const categorias = new Set();
		const cursos = new Set();
		const modalidades = new Set();

		boxes.forEach(box => {
			const categoriasBox = box.querySelectorAll('.categoria');
			categoriasBox.forEach(cat => {
				const catName = cat.textContent.trim();
				if (!['PRESENCIAL','Presencial', 'digital', 'EAD', 'Ead', 'Online'].includes(catName)) {
					categorias.add(catName);
				}
			});
			cursos.add(box.querySelector('.titleBox a').textContent.trim());
			modalidades.add(box.querySelector('.innerMod').textContent.trim());
		});

		categorias.forEach(cat => {
			const option = document.createElement('option');
			option.value = cat;
			option.textContent = cat;
			areaInteresseSelect.appendChild(option);
		});

		cursos.forEach(curso => {
			const option = document.createElement('option');
			option.value = curso;
			option.textContent = curso;
			nomeCursoSelect.appendChild(option);
		});

		modalidades.forEach(mod => {
			const option = document.createElement('option');
			option.value = mod;
			option.textContent = mod;
			modalidadeSelect.appendChild(option);
		});

		function filterBoxes() {
			const selectedCategoria = areaInteresseSelect.value;
			const selectedCurso = nomeCursoSelect.value;
			const selectedModalidade = modalidadeSelect.value;

			boxes.forEach(box => {
				const boxCategorias = Array.from(box.querySelectorAll('.categoria')).map(cat => cat.textContent.trim());
				const boxCurso = box.querySelector('.titleBox a').textContent.trim();
				const boxModalidade = box.querySelector('.innerMod').textContent.trim();

				let show = true;

				if (selectedCategoria && !boxCategorias.includes(selectedCategoria)) {
					show = false;
				}

				if (selectedCurso && boxCurso !== selectedCurso) {
					show = false;
				}

				if (selectedModalidade && boxModalidade !== selectedModalidade) {
					show = false;
				}

				box.style.display = show ? 'inline-block' : 'none';
			});
		}

		areaInteresseSelect.addEventListener('change', filterBoxes);
		nomeCursoSelect.addEventListener('change', filterBoxes);
		modalidadeSelect.addEventListener('change', filterBoxes);
		limparFiltrosBtn.addEventListener('click', () => {
			areaInteresseSelect.value = '';
			nomeCursoSelect.value = '';
			modalidadeSelect.value = '';
			filterBoxes();
		});
	});


    
    
    window.onload = function() {


        // seleção dos cards pelo nome no buscar 
        // versão clicando
        document.getElementById('btnFindCurso').addEventListener('click', function() {
            var searchQuery = document.getElementById('findCurso').value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            var boxItems = document.querySelectorAll('.box-item');
        
            boxItems.forEach(function(item) {
            var title = item.querySelector('.titleBox').textContent.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            if (title.includes(searchQuery)) {
                item.style.display = 'inline-block';
            } else {
                item.style.display = 'none';
            }
            });
        });

        // versão digitando
        document.getElementById('findCurso').addEventListener('input', function() {
            var searchQuery = this.value.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            var boxItems = document.querySelectorAll('.box-item');
    
            boxItems.forEach(function(item) {
                var title = item.querySelector('.titleBox').textContent.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                if (title.includes(searchQuery)) {
                    item.style.display = 'inline-block';
                } else {
                    item.style.display = 'none';
                }
            });
        });








    }
