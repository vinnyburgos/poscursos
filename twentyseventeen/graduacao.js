// JQUERY's
$(document).ready(function(){
    const baseUrl = window.location.origin + "/";
    const urlsGraduacao = document.querySelectorAll("a");
    for(let urlGraduacao of urlsGraduacao) { 
        if(urlGraduacao.href === baseUrl + "graduacao/") {
            urlGraduacao.href = baseUrl;
        }
    }
});

// manipula o bg da imagem de destaque
    $(document).ready(function () {
        // Captura a imagem destacada e usa fallback quando necessario.
        let bgCapture = $(".single-featured-image-header img").attr("src");
        const fallbackBg = window.DEFAULT_COURSE_HERO_BG || "";
        if (!bgCapture || bgCapture === "undefined") {
            bgCapture = fallbackBg;
        }

        if (bgCapture) {
            $(".single-featured-image-header").css("background", `url(${bgCapture})`);
            $(".single-featured-image-header").css({
                "background-size": "cover", /* Faz a imagem preencher o espaco */
                "background-repeat": "no-repeat",
                "background-position": "center center",
                "background-blend-mode": "overlay" /* Combina gradiente e imagem */
            });
        }

        const modalidadeGet = document.querySelector("#modalidadeBox").innerHTML;
        if(modalidadeGet == "Online") {
            document.querySelector("#modalidadeBox").innerHTML = "Digital";
            document.querySelector("#innerMod").innerHTML = "Digital";
        } else {
            const articles = document.querySelectorAll("article");
            for(article of articles) {
                articleWeb = $(article).attr("class");
                if(articleWeb.match("category-webconferencia")) {
                    document.querySelector("#modalidadeBox").innerHTML = "Webconferência";
                    document.querySelector("#innerMod").innerHTML = "Webconferência";
                } else {
                    document.querySelector("#innerMod").innerHTML = modalidadeGet;
                }
            }
        }

        // dispara informacoes para página cadastro 
        $("#btnComprar").click(function(e){
            e.preventDefault();
            
        })

        // control quais conteudos vai cursar 
        $(".wrapModulo").css({
            "transition": "height 0.4s ease"
        });

        $(".wrapModulo").each(function() {
            // Store the original height on page load (minimum 78px)
            const originalHeight = Math.max($(this).height(), 78);
            $(this).data("originalHeight", originalHeight);
            $(this).css("min-height", "78px");
        });

        $(".wrapModulo").click(function(){
            const $this = $(this);
            const $ruleModulo = $this.find(".ruleModulo");
            const isExpanded = $this.data("expanded");

            if (isExpanded) {
            // Animate back to original height (minimum 78px)
            $this.css({
                "height": $this.data("originalHeight"),
                "overflow": "hidden"
            });
            $ruleModulo.removeClass("active");
            } else {
            // Temporarily set height to auto to get full height
            $this.css({
                "height": "auto",
                "overflow": "visible"
            });
            const autoHeight = Math.max($this[0].scrollHeight + 20, 78); // 20px extra, min 78px
            $this.css("height", $this.data("originalHeight"));
            // Animate to expanded height
            setTimeout(() => {
                $this.css({
                "height": autoHeight,
                "overflow": "visible"
                });
            }, 10);
            $ruleModulo.addClass("active");
            }
            $this.data("expanded", !isExpanded);
        });

        // disparo para a TI 
        // $("#btnComprar").click(function(e) {
        //     e.preventDefault();
        //     const oferta = $("#idCurso").text().trim();
        //     const descricao_curso = [...new Set($(".entry-title")
        //         .map(function() { return $(this).text().trim(); })
        //         .get())].join(' ');
        //     // Supondo que a variável tokenAutenticacao contém o token gerado na sendAPI_interna
        //     // const token_auth = tokenAutenticacao; 

        //     fetch('https://cursos.unisuam.edu.br/wp-content/themes/twentyseventeen/sendAPI_interna.php', {   
        //         method: "POST",
        //         headers: {
        //             "Content-Type": "application/json"
        //         },
        //         body: JSON.stringify({
        //             oferta: oferta,
        //             descricao_curso: descricao_curso
        //             // nome: "Seu nome aqui"
        //             // cupom: "CATIA55"
        //         })
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         // console.log(data.data.redirect_url);
        //         if(data.data.redirect_url) {
        //             window.location.href = data.data.redirect_url;
        //             // console.log("Redirecting to: " + data.data.redirect_url);
        //         } else {
        //             console.error("No redirect_url in response");
        //         }
        //     })
        //     .catch(error => {
        //         $(".selecioneError").remove();
        //         $("#btnComprar").after("<p class='selecioneError'>Selecione as opções acima.   </p>");
        //     });
        // });
    });

// VANILLA's

// muda de HOME para INÍCIO
window.onload = function() {
    const iniciais = document.querySelectorAll('a');
    iniciais.forEach((inicial) => {
        if(inicial.innerText == 'Home') {
            inicial.innerText = 'Início';
        }
    });

    // CONTROL BOX


    const valorCDesconto = document.getElementById('valorCDesconto').textContent;
    const valorCDescontoNum = parseFloat(valorCDesconto.replace(',', '.'));
    // const valorSDesconto = (valorCDescontoNum / 50) * 100;
    
    const btnNovo = document.getElementById('btnNovo');
    const btnEx = document.getElementById('btnEx');

    btnNovo.addEventListener('click', function() {
        const valorSaga = document.getElementById('valorSaga').textContent;
        const valorCDesconto = document.getElementById('valorCDesconto');
        valorCDesconto.textContent = valorSaga;
        btnEx.classList.remove('active');
        this.classList.add('active');
    });

    btnEx.addEventListener('click', function() {
        const valorSaga = parseFloat(document.getElementById('valorSaga').textContent);
        const valorCDesconto = document.getElementById('valorCDesconto');
        const valorTotal = parseFloat(document.querySelector(".valorSDesconto").textContent);
        valor65Porcento = valorTotal * 0.35;
        valor65PorcentoArredondado = Math.ceil(valor65Porcento);
        valorCDesconto.textContent = valor65PorcentoArredondado.toFixed(2).replace('.', ',');

        btnNovo.classList.remove('active');
        this.classList.add('active');
    });

};