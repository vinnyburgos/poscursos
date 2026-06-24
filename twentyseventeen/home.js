
document.addEventListener('DOMContentLoaded', function() {
    // ===== INICIO ALTERACAO TOPCARDS (REFERENCIA POR .titleTop10) =====
    const normalizarTexto = (valor) => (valor || '')
        .toString()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .replace(/\s+/g, ' ')
        .trim();

    // Usa o titulo do Top 10 para buscar a modalidade no card principal equivalente.
    const modalidadePorTitulo = new Map();
    const valorPorTitulo = new Map();
    document.querySelectorAll('.box-item').forEach((box) => {
        const nomeCurso = box.querySelector('.nameCurso');
        const categoria = box.querySelector('.categoria');
        const valorCurso = box.querySelector('.valorCDesconto');
        if (!nomeCurso || !categoria) return;

        const chaveCurso = normalizarTexto(nomeCurso.textContent);
        modalidadePorTitulo.set(chaveCurso, categoria.textContent || '');

        const valorTexto = (valorCurso ? valorCurso.textContent : '')
            .replace(/R\$\s*/g, '')
            .trim();
        if (valorTexto) {
            valorPorTitulo.set(chaveCurso, valorTexto);
        }
    });

    const aplicarValorTopCard = (top) => {
        const tituloTop = top.querySelector('.titleTop10');
        const valorTop = top.querySelector('.valorTop10');
        if (!tituloTop || !valorTop) return false;

        const valorRef = valorPorTitulo.get(normalizarTexto(tituloTop.textContent));
        if (!valorRef) return false;

        valorTop.textContent = valorRef;
        return true;
    };

    const aplicarCorTopCard = (top) => {
        const tituloTop = top.querySelector('.titleTop10');
        const categoriaTop = top.querySelector('.categoriaTop');
        if (!tituloTop || !categoriaTop) return;

        const modalidadeRef = modalidadePorTitulo.get(normalizarTexto(tituloTop.textContent)) || categoriaTop.textContent || '';
        const modalidadeNorm = normalizarTexto(modalidadeRef);

        let cor = '#076B8F'; // Presencial
        if (modalidadeNorm.includes('digital ao vivo') || modalidadeNorm.includes('webconferencia') || modalidadeNorm.includes('semipresencial')) {
            cor = '#7D378D';
        } else if (modalidadeNorm.includes('ead') || modalidadeNorm.includes('digital')) {
            cor = '#E5457A';
        }

        top.style.background = cor;
        categoriaTop.style.color = cor;
    };

    const tops = document.querySelectorAll('.innerProcurado');
    tops.forEach(top => {
        // NOVO (ativo): aplica cor usando correspondencia por .titleTop10.
        aplicarCorTopCard(top);
        // NOVO (ativo): valor do Top10 vem do card correspondente em .box-item.
        const valorAplicadoDoCard = aplicarValorTopCard(top);

        // LEGADO (desativado): cor baseada apenas no texto da .categoriaTop global.
        // Para voltar ao comportamento anterior, comente aplicarCorTopCard(top)
        // e reative um bloco como o abaixo.
        // const categorias = document.querySelectorAll('.categoriaTop');
        // for (let categoria of categorias) {
        //     if (categoria.innerHTML == 'Digital (EaD)') {
        //         $(categoria).parent().parent().parent().parent().css('background', ' #E5457A');
        //         $(categoria).css('color', ' #E5457A');
        //     } else if (categoria.innerHTML == 'WEBCONFERÊNCIA' || categoria.innerHTML == 'Semipresencial') {
        //         $(categoria).parent().parent().parent().parent().css('background', ' #7D378D');
        //         $(categoria).css('color', ' #7D378D');
        //     } else {
        //         $(categoria).parent().parent().parent().parent().css('background', ' #076B8F');
        //         $(categoria).css('color', ' #076B8F');
        //     }
        // }

        const mneumonicotop = top.getAttribute('data-mneumonico-top');
        if (mneumonicotop) {
            fetch(`https://poscursos.unisuam.edu.br/wp-content/themes/twentyseventeen/getAPI.php?mneumonico=${mneumonicotop}`)
            .then(response => response.json())
            .then(data => {
                if (data.investimentos) {
                    // Só usa API se nao conseguiu casar com o valor do .box-item.
                    if (!valorAplicadoDoCard) {
                        vinValor = Math.ceil(data.investimentos[0].valor);
                        top.querySelector('.valorTop10').innerHTML = `
                        <span class="valorCDescontoTop">${vinValor}</span><span style="font-weight:600">,00</span>
                        `;
                    }
                    //  console.log(data.investimentos[0].valor);

                    // NOVO (ativo): reaplica cor por titulo apos atualizar dados do card.
                    aplicarCorTopCard(top);
                } 
                else {
                    // Regra desativada: não remover top card quando API vier sem investimentos.
                    // top.remove();
                }
            })
            .catch(error => {
                // Regra desativada: não remover top card quando a API falhar.
                // top.remove();
            });
        }
    });
    // ===== FIM ALTERACAO TOPCARDS (REFERENCIA POR .titleTop10) =====
});


// controle dos filtros da busca 

document.addEventListener('DOMContentLoaded', function() {
    // Bloco legado desativado: ele recriava opcoes e sobrescrevia o filtro correto por categoria.
    return;

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

    // modalidadeSelect.addEventListener('change', () => {
    //     if (modalidadeSelect.value == "PRESENCIAL") {
    //         $(".selectUnidade").fadeIn();
    //     } else {
    //         $(".selectUnidade").fadeOut();
    //     }
    //     filterBoxes();
    //     clearClean();
    // });

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
            // Considera apenas os boxes visíveis (display: inline-block)
            if (box.style.display !== 'inline-block') return;
            const boxCategorias = Array.from(box.querySelectorAll('.categoria')).map(cat => cat.textContent.trim());
            const boxModalidade = box.querySelector('.innerMod').textContent.trim();

            let show = true;

            // Se nenhuma categoria selecionada, mostra todos os cards
            if (!selectedCategoria) {
                show = true;
            } else if (!boxCategorias.includes(selectedCategoria)) {
                show = true;
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
        // Zera selects
        areaInteresseSelect.value = '';
        modalidadeSelect.value = '';
        const unidadeSelect = document.getElementById('unidade');
        if (unidadeSelect) {
            unidadeSelect.value = '';
        }
        // Mostra todos os boxes e remove a classe 'selecionados'
        document.querySelectorAll('.box-item').forEach(box => {
            box.style.display = '';
            box.classList.remove('selecionados');
        });
        // Atualiza as opções de modalidade para todas as possíveis
        const modalidadesSet = new Set();
        document.querySelectorAll('.box-item').forEach(box => {
            const categorias = box.querySelectorAll('.categoria');
            categorias.forEach(cat => {
                modalidadesSet.add(cat.textContent.trim());
            });
        });
        modalidadeSelect.innerHTML = '<option value="">Todas</option>';
        Array.from(modalidadesSet).sort().forEach(mod => {
            const option = document.createElement('option');
            option.value = mod;
            option.textContent = mod;
            modalidadeSelect.appendChild(option);
        });
        // Limpa o campo de busca se existir
        const findCurso = document.getElementById("findCurso");
        if (findCurso) findCurso.value = "";
        // Atualiza unidade se necessário
        if (typeof updateDisableClass === 'function') updateDisableClass();

        $(".box-item").addClass("selecionados");
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
        if(colorControl.textContent == "PRESENCIAL") {
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
        if(colorControl.textContent == "WEBCONFERÊNCIA") {
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
        let currentHeight = 1150;

        btnVerMais.addEventListener("click", function() {
            currentHeight += 1150;
            areaCursos.style.maxHeight = `${currentHeight}px`;

            if (areaCursos.scrollHeight <= currentHeight) {
                btnVerMais.style.display = 'none';
            }
        });

        coresBox();
    }

    clearClean();
    verMais();
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
            const unidadeContentEls = box.querySelectorAll('.unidadeContent');
            unidadeContentEls.forEach(el => {
                el.textContent.split(',').forEach(unidade => {
                    const trimmed = unidade.trim();
                    if (trimmed) {
                        unidades.add(trimmed);
                    }
                });
            });
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

        // Adicionar as opções únicas ao select, filtrando por vírgula e removendo duplicatas
        const unidadesUnicas = new Set();
        Array.from(unidades).forEach(unidade => {
            unidade.split(',').forEach(u => {
            const trimmedUnidade = u.trim();
            if (
                trimmedUnidade &&
                !trimmedUnidade.toLowerCase().includes('polo') &&
                trimmedUnidade.toLowerCase() !== 'unidades:'
            ) {
                unidadesUnicas.add(trimmedUnidade);
            }
            });
        });

        Array.from(unidadesUnicas).sort().forEach(unidade => {
            const option = document.createElement('option');
            option.value = unidade;
            option.textContent = unidade;
            unidadeSelect.appendChild(option);
        });

        // Tira o elemento UNIDADES do filtro unidade 
        const unidadesVin = document.querySelectorAll("#unidade option");
        for(let unidadeVin of unidadesVin) {
            if(unidadeVin.textContent == "Unidades:") {
            unidadeVin.remove();
            }
        }

        // Filtrar os boxes com base na unidade selecionada
        unidadeSelect.addEventListener('change', () => {
            const selectedUnidade = unidadeSelect.value.toLowerCase().trim();
            // Filtra apenas os boxes que possuem a classe 'selecionados'
            const selectedBoxes = Array.from(boxes).filter(box => box.classList.contains('selecionados'));
            selectedBoxes.forEach(box => {
            const apenasPresencialUnidade = box.querySelectorAll('.unidadeContent');
            let showBox = false;
            apenasPresencialUnidade.forEach(el => {
                // Verifica se a unidade selecionada existe em qualquer parte do texto
                if (el.textContent.toLowerCase().includes(selectedUnidade) && selectedUnidade !== '') {
                showBox = true;
                }
            });
            if (selectedUnidade === '') {
                box.style.display = 'inline-block';
            } else {
                box.style.display = showBox ? 'inline-block' : 'none';
            }
            });
            // Esconde os boxes que não estão selecionados
            Array.from(boxes).forEach(box => {
            if (!box.classList.contains('selecionados')) {
                box.style.display = 'none';
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

// cria pagina em função dos cards 

function enviarCardsParaCriacao() {
    const cards = [];
    const vistos = new Set();
    document.querySelectorAll('.box-item').forEach(card => {
        const nomeCurso = (
            card.querySelector('.nameCurso')?.textContent ||
            card.querySelector('.titleBox')?.textContent ||
            ''
        ).trim();
        const mneumonico = (card.getAttribute('data-mneumonico') || '').trim();
        const categoria = (card.querySelector('.categoria')?.textContent || '').trim();

        if (!nomeCurso) return;

        const chave = (mneumonico || nomeCurso).toLowerCase();
        if (vistos.has(chave)) return;
        vistos.add(chave);

        cards.push({
            nome: nomeCurso,
            mneumonico,
            categoria
        });
    });

    if (cards.length === 0) return;

    fetch('./wp-admin/admin-ajax.php?action=criar_graduacoes_automaticamente', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            post_type: 'graduacao',
            cards
        })
    });
}

document.addEventListener('DOMContentLoaded', function() {
    let assinaturaAnterior = '';
    const dispararSeMudou = () => {
        const boxes = Array.from(document.querySelectorAll('.box-item'));
        if (!boxes.length) return;

        const assinaturaAtual = boxes
            .map((box) => `${(box.getAttribute('data-mneumonico') || '').trim()}|${(box.querySelector('.nameCurso')?.textContent || box.querySelector('.titleBox')?.textContent || '').trim()}`)
            .join('||');

        if (!assinaturaAtual || assinaturaAtual === assinaturaAnterior) return;
        assinaturaAnterior = assinaturaAtual;
        enviarCardsParaCriacao();
    };

    setTimeout(dispararSeMudou, 1200);

    const container = document.querySelector('.box-container');
    if (!container) return;

    let debounceId = null;
    const observer = new MutationObserver(() => {
        if (debounceId) {
            clearTimeout(debounceId);
        }
        debounceId = setTimeout(dispararSeMudou, 500);
    });

    observer.observe(container, { childList: true, subtree: true });
});

// Normaliza links dos cards para evitar base duplicada e garantir rota canônica em graduacao.
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('.box-item .nameCurso, .box-item .cursoPage');
    links.forEach((link) => {
        const href = link.getAttribute('href');
        if (!href || href === '#') return;

        let url;
        try {
            url = new URL(href, window.location.origin);
        } catch (e) {
            return;
        }

        const partes = url.pathname.split('/').filter(Boolean);
        if (partes.length >= 2 && partes[0] === partes[1]) {
            partes.splice(1, 1);
        }

        const idxPos = partes.indexOf('posgraduacao');
        if (idxPos !== -1) {
            partes[idxPos] = 'graduacao';
        }

        url.pathname = '/' + partes.join('/') + '/';
        link.setAttribute('href', url.toString());
    });
});


document.addEventListener('DOMContentLoaded', function() {
    // Bloco legado desativado: mantinha filtro por texto parcial e conflita com o filtro principal.
    return;

    const areaInteresse = document.getElementById('areaInteresse');
    const modalidade = document.getElementById('modalidade');
    const boxes = document.querySelectorAll('.box-item');

    function atualizarModalidades() {
        // Atualiza as opções de modalidade com base nos cards visíveis
        const modalidadesSet = new Set();
        boxes.forEach(function(box) {
            if (box.style.display !== 'none') {
                const cat = box.querySelector('.categoria');
                if (cat) modalidadesSet.add(cat.textContent.trim());
            }
        });
        modalidade.innerHTML = '<option value="">Todas</option>';
        modalidadesSet.forEach(function(mod) {
            const opt = document.createElement('option');
            opt.value = mod;
            opt.textContent = mod;
            modalidade.appendChild(opt);
        });
    }

    function filtrarAreaInteresse() {
        const selectedOption = areaInteresse.options[areaInteresse.selectedIndex];
        const selectedText = selectedOption.text.trim();
        boxes.forEach(function(box) {
            const catText = box.querySelector('.selecionaCategoria').textContent;
            if (selectedText === "Todas" || selectedOption.value === "") {
                box.style.display = '';
            } else if (catText.includes(selectedText)) {
                box.style.display = '';
            } else {
                box.style.display = 'none';
            }
        });
        atualizarModalidades();
        filtrarModalidade; // Garante que o filtro de modalidade é aplicado sobre os cards visíveis
    }

    function filtrarModalidade() {
        const selectedModalidade = modalidade.value;
        boxes.forEach(function(box) {
            if (box.style.display === 'none') return; // Só atua nos visíveis
            const cat = box.querySelector('.categoria');
            if (!selectedModalidade || (cat && cat.textContent.trim() === selectedModalidade)) {
                box.style.display = '';
            } else {
                box.style.display = 'none';
            }
        });
    }

    areaInteresse.addEventListener('change', function() {
        const selectedOption = areaInteresse.options[areaInteresse.selectedIndex];
        const selectedText = selectedOption.text.trim();
        const boxes = document.querySelectorAll('.box-item');
        boxes.forEach(function(box) {
            // Se "Todas" selecionado, mostra tudo
            if (selectedText === "Todas" || selectedOption.value === "") {
                box.style.display = '';
            } else {
                var catText = box.querySelector('.selecionaCategoria').textContent;
                if (catText.includes(selectedText)) {
                    box.style.display = '';
                } else {
                    box.style.display = 'none';
                }
            }
            // Adiciona ou remove a classe "selecionados"
            if (box.style.display !== 'none') {
                box.classList.add('selecionados');
            } else {
                box.classList.remove('selecionados');
            }
        });
        setTimeout(atualizarModalidades, 100);
    });
    modalidade.addEventListener('change', filtrarModalidade);

    // Inicializa ao carregar a página
    filtrarAreaInteresse();
});


document.addEventListener('DOMContentLoaded', function() {
    const modalidade = document.getElementById('modalidade');
    const selectUnidade = document.querySelector('.innerSelect.selectUnidade');
    const areaInteresse = document.getElementById('areaInteresse');
    
    function updateDisableClass() {
        if (modalidade && modalidade.value === 'Digital (EaD)') {
            selectUnidade.classList.add('disable');
            // Cria uma div transparente por cima do elemento para bloquear interações
            let overlay = selectUnidade.querySelector('.select-unidade-overlay'); 
            if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'select-unidade-overlay';
            Object.assign(overlay.style, {
                position: 'absolute',
                top: 0,
                left: 0,
                width: '100%',
                height: '100%',
                background: 'rgba(255,255,255,0)',
                zIndex: 10,
                cursor: 'not-allowed'
            });
            selectUnidade.style.position = 'relative';
            selectUnidade.appendChild(overlay);
            }
        } else {
            selectUnidade.classList.remove('disable');
            // Remove a div de overlay se existir
            const overlay = selectUnidade.querySelector('.select-unidade-overlay');
            if (overlay) {
            selectUnidade.removeChild(overlay);
            };
        }

        // Também aplica ao clicar em #limparFiltros
        // Adiciona a classe "selecionados" em todos os .box-item
        // document.querySelectorAll('.box-item').forEach(box => {
        //     box.classList.add('selecionados');
        // });

        const limparFiltrosBtn = document.getElementById('limparFiltros');
        if (limparFiltrosBtn) {
            // limparFiltrosBtn.removeEventListener('click', function() {
            //     updateDisableClass();
            // });
            limparFiltrosBtn.addEventListener('click', updateDisableClass);
        }
    }
    
    if (modalidade && selectUnidade && areaInteresse) {
        modalidade.addEventListener('change', updateDisableClass);
        areaInteresse.addEventListener('change', updateDisableClass);
    }
});