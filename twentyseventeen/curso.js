// JQUERY's

// manipula o bg da imagem de destaque
    $(document).ready(function () {
        // Captura o SRC da imagem
        let bgCapture = $(".single-featured-image-header img").attr("src");

        // Define a imagem e o gradiente como background em camadas
        $(".single-featured-image-header").css("background", 
            `linear-gradient(262deg, #CFEAEF 0%, #076B8F 50%), url(${bgCapture})`
        );
        $(".single-featured-image-header").css({
            "background-size": "cover", /* Faz a imagem preencher o espaço */
            "background-blend-mode": "overlay" /* Combina gradiente e imagem */
        });

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

    // movimento box 
    const box = document.getElementById('box');
    const footer = document.getElementById('footer');

    window.addEventListener('scroll', () => {
      const footerRect = footer.getBoundingClientRect();
      const boxRect = box.getBoundingClientRect();

      // Verifica se o box está tocando o footer
      if (footerRect.top <= boxRect.bottom) {
        box.style.right = "-200%"; // Adiciona a classe para sumir para a direita
      } else {
        box.style.right = "10%"; // Remove a classe caso volte
      }
    });

    const valorCDesconto = document.getElementById('valorCDesconto').textContent;
    const valorCDescontoNum = parseFloat(valorCDesconto.replace(',', '.'));
    const valorSDesconto = (valorCDescontoNum / 40) * 100;
    
    // const btnNovo = document.getElementById('btnNovo');
    // const btnEx = document.getElementById('btnEx');

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
        const valorTotal = parseFloat(document.getElementById("valorSDesconto").textContent);
        valor65Porcento = valorTotal * 0.35;
        valor65PorcentoArredondado = Math.ceil(valor65Porcento);
        valorCDesconto.textContent = valor65PorcentoArredondado.toFixed(2).replace('.', ',');

        btnNovo.classList.remove('active');
        this.classList.add('active');
    });

};

