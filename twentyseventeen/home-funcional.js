// JQUERY's

// btn mobbile
$(".btnMenuMobileTop").click(function(){
    $(".wrapContentTop").slideToggle();
});

    
// VANILLA's

// disparo para getAPI para receber os dados dos cursos
document.addEventListener('DOMContentLoaded', function() {
    const boxes = document.querySelectorAll('.box-item');
    boxes.forEach(box => {
        const mneumonico = box.getAttribute('data-mneumonico');
        if (mneumonico) {
            fetch(`/novo-graduacao/wp-content/themes/twentyseventeen/getAPI.php?mneumonico=${mneumonico}`)
            .then(response => response.json())
            .then(data => {
                // console.log(data.investimentos.valor);
                if (data.investimentos) {
                    const valores = data.investimentos.map(i => i.valor).filter(v => v !== null && v !== '' && !isNaN(v));
                    const menorValor = valores.length > 0 ? Math.min(...valores) : 0;
                    const valorIntegral = (menorValor / 40) * 100;
                    // Filtra as unidades para remover "Campus" e o conteúdo entre parênteses
                    const unidadesFiltradas = [...new Set(data.investimentos.map(i => i.unidade))]
                    .map(unidade => {
                        // Remove "Campus" e tudo entre parênteses (inclusive os parênteses)
                        return unidade.replace(/Campus\s*/gi, '').replace(/\s*\([^)]*\)/g, '').trim();
                    })
                    .filter(unidade => unidade.length > 0);

                    // Filtra as unidades para remover qualquer que contenha "Polo" (case-insensitive)
                    const unidadesSemPolo = unidadesFiltradas.filter(unidade => !/polo/i.test(unidade));

                    // Primeiro, mostra o conteúdo normalmente, mas esconde a div de unidades
                    box.querySelector('.apiGets').innerHTML = `
                        <p class=" partir">A partir de:</p>
                        <span class=" dezoitoxSDesconto"> R$</span>
                        <span class="valorSDesconto ">${valorIntegral.toFixed(2).replace('.', ',')} por:</span>
                        <div class="pula"></div>
                        <span class="dozexCDesconto apenasEAD">R$</span> 
                        <span class="dozexCDesconto apenasPresencial">R$</span> 
                        <span class="valorCDesconto">${menorValor.toFixed(2).replace('.', ',')}</span><div class="pula"></div>
                        <span class="apenasEAD"></span>
                        <div class="wrapThings">
                            <h6 class="carga">Duração:</h6>
                            <p class="conteudoBox"><span class="cargaHoraria">${data.resumo['semestres']}</span><span> Semestres</span></p>
                        </div>
                        <div class="wrapThings apenasPresencial apenasPresencialUnidade" style="display:none;">
                            <h6 class="unidades">Unidades:</h6>
                            ${unidadesSemPolo.join(' | ')}
                        </div>
                    `;

                    // Se for Digital, remove o elemento de unidade presencial
                    const modalidade = box.querySelector('.innerMod span')?.textContent.trim().toUpperCase();
                    if (modalidade === 'DIGITAL') {
                        const unidadeDiv = box.querySelector('.apenasPresencialUnidade');
                        if (unidadeDiv) {
                            unidadeDiv.remove();
                        }
                    } else {
                        // Depois de 5 segundos, mostra a div de unidades
                        setTimeout(() => {
                            const unidadeDiv = box.querySelector('.apenasPresencialUnidade');
                            if (unidadeDiv) {
                                unidadeDiv.style.display = '';
                            }
                        }, 5000);
                    }
                } else {
                    box.remove();
                    // box.querySelector('.apiGets').innerHTML = '<p>Em breve.</p>';
                }
            })
            .catch(error => {
                box.remove();
                // console.error('Erro:', error);
                // box.querySelector('.apiGets').innerHTML = '<p>Erro ao carregar dados.</p>';
            });
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const tops = document.querySelectorAll('.innerProcurado');
    tops.forEach(top => {
        const mneumonicotop = top.getAttribute('data-mneumonico-top');
        if (mneumonicotop) {
            fetch(`/novo-graduacao/wp-content/themes/twentyseventeen/getAPI.php?mneumonico=${mneumonicotop}`)
            .then(response => response.json())
            .then(data => {
                if (data.investimentos) {
                    const menorValor = Math.min(...data.investimentos.map(i => i.valor));
                    const valorIntegral = (menorValor / 40) * 100;
                    top.querySelector('.valorTop10').innerHTML = `
                        <span class="valorCDescontoTop">${menorValor.toFixed(2).replace('.', ',')}</span>
                    `;

                    const categorias = document.querySelectorAll(".categoriaTop");
                    for(let categoria of categorias) {
                        if(categoria.innerHTML == "Digital") {
                            $(categoria).parent().parent().parent().parent().find(".apenasEADTop").show();
                            $(categoria).parent().parent().parent().parent().css("background"," #E5457A");
                            $(categoria).css("color"," #E5457A");
                        } else if(categoria.innerHTML == "WEBCONFERÊNCIA"){
                            $(categoria).parent().parent().parent().parent().find(".apenasPresencialTop").show();
                            $(categoria).parent().parent().parent().parent().css("background"," #7D378D");
                            $(categoria).css("color"," #7D378D");
                            $(categoria).parent().css("right"," 50px");
                        } else {
                            $(categoria).parent().parent().parent().parent().find(".apenasPresencialTop").show();
                            // $(categoria).parent().find(".apenasPresencialUnidade").show();
                            $(categoria).parent().parent().parent().parent().css("background"," #076B8F");
                            $(categoria).css("color"," #076B8F");
                        }
                    }

                } 
                else {
                    top.remove();
                }
            })
            .catch(error => {
                top.remove();
            });
        }
    });
});
// controle dos filtros da busca 
document.addEventListener('DOMContentLoaded', function() {
    const boxes = document.querySelectorAll('.box-item');
    const areaInteresseSelect = document.getElementById('areaInteresse');
    const modalidadeSelect = document.getElementById('modalidade');

    function updateModalidadeOptions() {
        const selectedCategoria = areaInteresseSelect.value;
        modalidadeSelect.innerHTML = '<option value="">Todas</option>';
        const filteredModalidades = new Set();

        boxes.forEach(box => {
            const boxCategorias = Array.from(box.querySelectorAll('.categoria')).map(cat => cat.textContent.trim());
            const boxModalidade = box.querySelector('.innerMod').textContent.trim();

            if (!selectedCategoria || boxCategorias.includes(selectedCategoria)) {
                filteredModalidades.add(boxModalidade);
            }
        });

        Array.from(filteredModalidades).sort().forEach(mod => {
            const option = document.createElement('option');
            option.value = mod;
            option.textContent = mod;
            modalidadeSelect.appendChild(option);
        });
    }

    areaInteresseSelect.addEventListener('change', () => {
        updateModalidadeOptions();
        filterBoxes();
        clearClean();
    });

    modalidadeSelect.addEventListener('change', () => {
        if (modalidadeSelect.value == "PRESENCIAL") {
            $(".selectUnidade").fadeIn();
        } else {
            $(".selectUnidade").fadeOut();
        }
        filterBoxes();
        clearClean();
    });

    const limparFiltrosBtn = document.getElementById('limparFiltros');

    const categorias = new Set();
    const modalidades = new Set();

    boxes.forEach(box => {
        const categoriasBox = box.querySelectorAll('.categoria');
        categoriasBox.forEach(cat => {
            const catName = cat.textContent.trim();
            if (!['PRESENCIAL', 'Presencial', 'Digital Online', 'EAD', 'Online', 'DIGITAL ONLINE', 'Digital'].includes(catName)) {
                categorias.add(catName);
            }
        });
        modalidades.add(box.querySelector('.innerMod').textContent.trim());
    });

    Array.from(categorias).sort().forEach(cat => {
        const option = document.createElement('option');
        option.value = cat;
        option.textContent = cat;
        areaInteresseSelect.appendChild(option);
    });

    Array.from(modalidades).sort().forEach(mod => {
        const option = document.createElement('option');
        option.value = mod;
        option.textContent = mod;
        modalidadeSelect.appendChild(option);
    });

    function filterBoxes() {
        const selectedCategoria = areaInteresseSelect.value;
        const selectedModalidade = modalidadeSelect.value;

        boxes.forEach(box => {
            const boxCategorias = Array.from(box.querySelectorAll('.categoria')).map(cat => cat.textContent.trim());
            const boxModalidade = box.querySelector('.innerMod').textContent.trim();

            let show = true;

            if (selectedCategoria && !boxCategorias.includes(selectedCategoria)) {
                show = false;
            }

            if (selectedModalidade && boxModalidade !== selectedModalidade) {
                show = false;
            }

            box.style.display = show ? 'inline-block' : 'none';
        });
    }

    areaInteresseSelect.addEventListener('change', filterBoxes);
    modalidadeSelect.addEventListener('change', filterBoxes);
    limparFiltrosBtn.addEventListener('click', () => {
        areaInteresseSelect.value = '';
        modalidadeSelect.value = '';
        filterBoxes();
        document.getElementById("findCurso").value = "";
    });
});

// limpa option se estiver vazio
function clearClean() {
    const options = document.querySelectorAll('option');
    for (let option of options) {
        if (option.textContent == "") {
            option.remove();
        }
    }
}

// controle das cores dos boxes individualizando por modalidade 
function coresBox() {
    const colorsControl = document.querySelectorAll(".innerMod span");
    for(let colorControl of colorsControl) {
        if(colorControl.textContent == "Digital") {
            colorControl.parentNode.classList.add("innerRed");
            colorControl.parentNode.parentElement.children[0].classList.add("bgRed");
            colorControl.parentNode.parentElement.children[1].classList.add("colorRed");
            setTimeout(() => {
                $(".loading").hide();
                $(colorControl).parent().parent().find(".partir").addClass("colorRed");
                $(colorControl).parent().parent().find(".carga").addClass("colorRed");
                $(colorControl).parent().parent().find(".unidades").html("Polos Digitais:");
                $(colorControl).parent().parent().find(".unidades").addClass("colorRed");
                $(colorControl).parent().parent().find(".apenasPresencial").hide();
                $(colorControl).parent().parent().find(".apenasEAD").show();
                $(".valorCDesconto").show();
            }, 3000);
        } 
        if(colorControl.textContent == "Presencial") {
            colorControl.parentNode.classList.add("innerGreen");
            colorControl.parentNode.parentElement.children[0].classList.add("bgGreen");
            colorControl.parentNode.parentElement.children[1].classList.add("colorGreen");
            setTimeout(() => {
                $(".loading").hide();
                $(colorControl).parent().parent().find(".partir").addClass("colorGreen");
                $(colorControl).parent().parent().find(".carga").addClass("colorGreen");
                $(colorControl).parent().parent().find(".unidades").addClass("colorGreen");
                $(colorControl).parent().parent().find(".apenasEAD").hide();
                $(colorControl).parent().parent().find(".apenasPresencial").show();
                $(".valorCDesconto").show();
            }, 3000);
        } 
        if(colorControl.textContent == "Webconferência") {
            colorControl.parentNode.classList.add("innerPurple");
            colorControl.parentNode.parentElement.children[0].classList.add("bgPurple");
            colorControl.parentNode.parentElement.children[1].classList.add("colorPurple");
            setTimeout(() => {
                $(".loading").hide();
                $(colorControl).parent().parent().find(".partir").addClass("colorPurple");
                $(colorControl).parent().parent().find(".carga").addClass("colorPurple");
                $(colorControl).parent().parent().find(".unidades").addClass("colorPurple");
                $(colorControl).parent().parent().find(".apenasEAD").hide();
                $(colorControl).parent().parent().find(".apenasPresencial").show();
                $(colorControl).parent().parent().find(".apiGets").css("margin-top", "-20px");
                $(".valorCDesconto").show();
            }, 3000);
        } 
    }
}

window.onload = function() {
    coresBox();
    
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

    function verMais() {
        const btnVerMais = document.querySelector(".btnVerMaisCursos");
        const areaCursos = document.querySelector(".box-container");
        let currentHeight = 705;

        btnVerMais.addEventListener("click", function() {
            currentHeight += 702;
            areaCursos.style.maxHeight = `${currentHeight}px`;

            if (areaCursos.scrollHeight <= currentHeight) {
                btnVerMais.style.display = 'none';
            }
        });

        coresBox();
    }

    // document.800uerySelectorAll('.titleBox a').forEach(title => {
    //     if (title.textContent.length > 45) {
    //         $(title).parent().find(".pontinhos").show();
    //     }
    // });

    clearClean();
    verMais();
    // $(".wrapPolosD").click(function(){
    //     $(".wrapPolos").slideToggle();
    // });
}

function removeInfinityBoxes() {
    const boxes = document.querySelectorAll('.box-item');
    boxes.forEach(box => {
        const valorCDesconto = box.querySelector('.valorCDesconto');
        if (valorCDesconto && valorCDesconto.textContent.includes('Infinity')) {
            box.remove();
        }
    });
};

$(document).scroll(function(){
    coresBox();
    removeInfinityBoxes();
});

document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const boxes = document.querySelectorAll('.box-item');
        const unidadeSelect = document.getElementById('unidade');

        const unidades = new Set();

        boxes.forEach(box => {
            const modalidade = box.querySelector('.innerMod').textContent.trim();
            if (modalidade === 'PRESENCIAL') {
                const unidade = box.querySelector('.unidades').innerHTML.trim();
                unidades.add(unidade);
                // console.log(unidade);
            }
        });
        
        Array.from(unidades).sort().forEach(unidade => {
            const option = document.createElement('option');
            option.value = unidade;
            option.textContent = unidade;
            unidadeSelect.appendChild(option);
        });
    }, 4000);
});



// controle de UNIDADE 
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        const boxes = document.querySelectorAll('.box-item'); // Seleciona todos os boxes
        const unidadeSelect = document.getElementById('unidade'); // O select de unidade

        const unidades = new Set(); // Usamos um Set para evitar duplicatas

        // Iterar pelos boxes e capturar o conteúdo após o elemento "unidades"
        boxes.forEach(box => {
            const unidadeElement = box.querySelector('.unidades'); // Busca o elemento com a classe "unidades"
            if (unidadeElement) {
                const nextSibling = unidadeElement.nextSibling; // Captura o próximo nó após o elemento "unidades"
                if (nextSibling && nextSibling.nodeType === Node.TEXT_NODE) {
                    const unidadeText = nextSibling.textContent.trim(); // Captura o texto do próximo nó
                    if (unidadeText) {
                        unidadeText.split('|').forEach(unidade => {
                            const trimmedUnidade = unidade.trim();
                            if (trimmedUnidade.toLowerCase() !== 'webconferência') { // Ignora "webconferência"
                                unidades.add(trimmedUnidade); // Adiciona cada unidade ao Set, removendo espaços extras
                            }
                        });
                    }
                }
            }
        });

        // Adicionar as opções únicas ao select
        Array.from(unidades).sort().forEach(unidade => {
            if (!unidade.toLowerCase().includes('polo')) { // Não adiciona se contiver "polo"
                const option = document.createElement('option');
                option.value = unidade;
                option.textContent = unidade;
                unidadeSelect.appendChild(option);
            }
        });

        // Tira o elemento UNIDADES do filtro unidade 
        const unidadesVin = document.querySelectorAll("#unidade option")
        for(let unidadeVin of unidadesVin) {
            if(unidadeVin.textContent == "Unidades:") {
                unidadeVin.remove();
            }
        }

        // Filtrar os boxes com base na unidade selecionada
        unidadeSelect.addEventListener('change', () => {
            const selectedUnidade = unidadeSelect.value; // Unidade selecionada no select
            boxes.forEach(box => {
            const unidadeElement = box.querySelector('.unidades'); // Busca o elemento com a classe "unidades"
            const modalidade = box.querySelector('.innerMod').textContent.trim();
            if (modalidade === 'PRESENCIAL' && unidadeElement) {
                const nextSibling = unidadeElement.nextSibling; // Captura o próximo nó após o elemento "unidades"
                if (nextSibling && nextSibling.nodeType === Node.TEXT_NODE) {
                const unidadeText = nextSibling.textContent.trim(); // Captura o texto do próximo nó
                const unidadesBox = unidadeText.split('|').map(u => u.trim()); // Divide e remove espaços extras
                if (selectedUnidade === '' || unidadesBox.includes(selectedUnidade)) {
                    box.style.display = 'inline-block'; // Mostra o box
                } else {
                    box.style.display = 'none'; // Esconde o box
                }
                } else {
                box.style.display = 'none'; // Esconde se não houver unidade
                }
            } else {
                box.style.display = 'none'; // Esconde se não for PRESENCIAL
            }
            });
        });
    }, 4000); // Timeout para garantir que os boxes estejam carregados
});
// controle de UNIDADE 



$(document).mousemove(function(){
    $(".naoclicado").click(function(){
        $(this).addClass("clicado");
        $(this).removeClass("naoclicado");
        $(".wrapPolosD").css("height", "auto");
        $(".static").css("rotate","180deg");
    });
    $(".clicado").click(function(){
        $(this).addClass("naoclicado");
        $(this).removeClass("clicado");
        $(".wrapPolosD").css("height", "80px");
        $(".static").css("rotate","0deg");
    });
});