<style>
/* Ajuste para colunas lado a lado e sem quebra */
.colunaPolo.nomePolo {
    flex: 1.5 1 180px;
    font-weight: bold;
    word-break: break-word;
}
.colunaPolo.enderecoPolo {
    flex: 2.2 1 200px;
    word-break: break-word;
}
.colunaPolo.telefonePolo {
    flex: 1.1 1 120px;
    word-break: break-word;
}
/*
.colunaPolo.emailPolo {
    flex: 1.1 1 100px;
    word-break: break-all;
}
*/
.colunaPolo.horarioPolo {
    flex: 1.7 1 180px;
    word-break: break-word;
}
.innerPolo {
    width: 100%;
    margin-bottom: 10px;
    display: flex;
    flex-wrap: nowrap;
    align-items: flex-start;
    gap: 8px;
}
</style>

<style>
@media (max-width: 768px) {
    .innerPolo {
        flex-direction: column !important;
        gap: 0;
        position: relative;
        margin-bottom: 0;
        margin-top: -30px;
    }
    .innerPolo::after {
        content: "";
        display: block;
        width: 100%;
        height: 1px;
        background: #d4d4d4;
        margin: 8px 0 12px 0;
    }
    .colunaPolo {
        margin-bottom: 0;
        width: 100%;
        padding: 0;
        line-height: 1.2;
        min-height: 0;
        max-height: 30px;
        overflow: hidden;
        display: flex;
        align-items: center;
    }
    .colunaPolo:last-child {
        margin-bottom: 0;
    }
}
</style>

<div class="innerPolo">
    <div class="colunaPolo nomePolo">Cabo Frio</div>
    <div class="colunaPolo enderecoPolo">Rua Getúlio Vargas, 150 - Parque Central, Cabo Frio</div>
    <div class="colunaPolo telefonePolo">Tel: (22) 97403-8332</div>
    <!-- <div class="colunaPolo emailPolo"><a href="mailto:polo.jc@unisuam.edu.br" target="_blank" style="color:#0F96AE;">polo.jc@unisuam.edu.br</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a sexta: 10h às 20h | Sábado: 08h ás 12h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Pedra de Guaratiba</div>
    <div class="colunaPolo enderecoPolo">Estrada da matriz, 190, Centro - Pedra de Guaratiba</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 97066-8618</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-pedra-de-guaratiba-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Encantado</div>
    <div class="colunaPolo enderecoPolo">Rua Clarimundo de Melo, n 79, Encantado, Rio de Janeiro</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 3296-5000</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-encantado-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">São Cristóvão</div>
    <div class="colunaPolo enderecoPolo">Rua Bela, nº 509/202, Bairro Imperial de São Cristóvão</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 98638-4708</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-sao-cristovao-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Barra da Tijuca</div>
    <div class="colunaPolo enderecoPolo">Av. das Américas, 700, Bl 08, Loja 207-C, CEP 22640-100, Shopping Città</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 3030-5802</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-barra-da-tijuca-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Barra do Piraí</div>
    <div class="colunaPolo enderecoPolo">Praça Heitor Vale 37, Centro, Barra do Piraí</div>
    <div class="colunaPolo telefonePolo">Tel: (24) 99875-2052</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-barra-do-pirai-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Guaratiba</div>
    <div class="colunaPolo enderecoPolo">Rua Canoanã, Lote 32, Quadra 91, em Guaratiba</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 97356-4357</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-guaratiba-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Macaé</div>
    <div class="colunaPolo enderecoPolo">Rua Silva Jardim,412 - Lj - 3A - Centro / Macaé</div>
    <div class="colunaPolo telefonePolo">Tel: (22) 2023-6646</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-macae-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Campo Grande (Mendanha)</div>
    <div class="colunaPolo enderecoPolo">Estrada do Mendanha, 898 - Campo Grande</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 97010-6334</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-campo-grande-mendanha-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Nova Iguaçu</div>
    <div class="colunaPolo enderecoPolo">Rua Francisca Melo, 88 - Nova Iguaçu -RJ (Centro)</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 99519-5222</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-nova-iguacu-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Jardim Paulista</div>
    <div class="colunaPolo enderecoPolo">Alameda Jau, 1177 - Jardim Paulista - SP</div>
    <div class="colunaPolo telefonePolo">Tel: (11) 2096-3133</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-jardim-paulista-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">São Gonçalo</div>
    <div class="colunaPolo enderecoPolo">Rua Alagoas, 235, Raul Veiga - São Gonçalo - RJ</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 98031-1104</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-sao-goncalo-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Iguaba Grande</div>
    <div class="colunaPolo enderecoPolo">Av. Paulino Rodrigues de Souza n°4960 (Rodovia Amaral Peixoto km 100) - Centro - Iguaba Grande - RJ</div>
    <div class="colunaPolo telefonePolo">Tel: (22) 2624-4487</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-iguaba-grande-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Nilópolis</div>
    <div class="colunaPolo enderecoPolo">Av. Carmela Dutra, n° 1880, Sala 03 | Centro</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 4137-4385</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-nilopolis-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Niterói Centro</div>
    <div class="colunaPolo enderecoPolo">Av. Enarni do Amaral Peixoto, 116 - Centro, Niterói | CEP: 24.020-076</div>
    <div class="colunaPolo telefonePolo">(21) 2613-5891</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-niteroi-centro-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Tijuca</div>
    <div class="colunaPolo enderecoPolo">Rua General Roca, nº 818, sobreloja, Sans Pena, Tijuca, Rio de Janeiro</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 98486-9690</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-tijuca-25.2" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Vila da Penha</div>
    <div class="colunaPolo enderecoPolo">Av. Meriti, nº 892 , Salas 201 e 301, Vila da Penha/RJ</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 97010-6334</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-vila-da-penha-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Centro</div>
    <div class="colunaPolo enderecoPolo">Rua Camerino, 132, Centro - RJ</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 98478-7058</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-centro-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Duque de Caxias</div>
    <div class="colunaPolo enderecoPolo">Av. Presidente Vargas 220, Centro, Duque de Caxias, RJ</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 2671-9478</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-duque-de-caxias-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Piabetá</div>
    <div class="colunaPolo enderecoPolo">Rua Brasil nº 275, Piabetá, Magé | CEP: 25.931-778</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 98450-6648 / (21) 96719-1574</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-piabeta-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Santa Cruz</div>
    <div class="colunaPolo enderecoPolo">Rua Lopes Moura, 59 A - Sala 104 | CEP: 23.515-020</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 97010-6334</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-santa-cruz-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Belford Roxo</div>
    <div class="colunaPolo enderecoPolo">Rua Mauá, 97. Belford Roxo | CEP: 26.165-110</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 2660-0875</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-belford-roxo-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Itaboraí</div>
    <div class="colunaPolo enderecoPolo">Av. 22 de Maio, 6331, loja 120. | CEP: 24.800-258</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 98615-0542</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-itaborai-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Ilha do Governador</div>
    <div class="colunaPolo enderecoPolo">Rua Cambaúba, nº 114 - Jardim Guanabara | CEP: 21931-340</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 96980-4942</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-ilha-do-governador-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda à sexta: 10h às 20h | Sábado: 09h às 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Taquara</div>
    <div class="colunaPolo enderecoPolo">Rua Apiacás 320, Taquara | CEP: 22.730-190</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 96887-0293 / (21) 96948-9357 / (21) 96719-1574</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-taquara-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">São João de Meriti</div>
    <div class="colunaPolo enderecoPolo">Rua da Matriz nº 204, São João de Meriti | CEP: 25.520-640</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 97083-7692 / (21) 96719-1574</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-sao-joao-de-meriti-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Seropédica</div>
    <div class="colunaPolo enderecoPolo">Avenida Fernando Ministro Costa, nº 483 - 112</div>
    <div class="colunaPolo telefonePolo">Tel.: (21) 99652-7466</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-fazenda-caxias-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda à sexta: 08h às 19h | Sábado: 08h às 15h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Vilar dos Teles</div>
    <div class="colunaPolo enderecoPolo">Rua Aldenor Ribeiro de Matos, 153. Salas 203 e 204. | CEP: 25.576-281</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 96719-1574</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-vilar-dos-teles-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Realengo</div>
    <div class="colunaPolo enderecoPolo">Estrada do Realengo, 289 - Colégio Ypisilon | CEP: 21.715-331</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 97286-2826</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-realengo-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Mangaratiba</div>
    <div class="colunaPolo enderecoPolo">Rua Arthur Pires, Nº1083  | CEP: 23.860-000</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 98739-9265</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-mangaratiba-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">Xerém</div>
    <div class="colunaPolo enderecoPolo">Estrada Carlos Mateus, 54 | CEP: 25.245-620</div>
    <div class="colunaPolo telefonePolo">Tel: (21) 3135-3953 / (21) 97990-4400</div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-xerem-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>
<div class="innerPolo">
    <div class="colunaPolo nomePolo">São Gonçalo- Centro</div>
    <div class="colunaPolo enderecoPolo">Rua Doutor Feliciano Sodré, nº 82, Sala 103, Centro, São Gonçalo/RJ, CEP: 24440-440</div>
    <div class="colunaPolo telefonePolo"></div>
<!--     <div class="colunaPolo emailPolo"><a href="https://hs.unisuam.edu.br/polo-sao-goncalo-centro-26.1" target="_blank" style="color:#0F96AE;">Página do polo</a></div> -->
    <div class="colunaPolo horarioPolo">Segunda a Sexta: 9h - 18h | Sábado: 9h - 13h</div>
</div><br>