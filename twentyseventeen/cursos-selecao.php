<?php
    /**
     * Template Name: Seleção Progressiva de Cursos
     *
     * Este é o template específico para a página de cadastro.
     *
     * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
     *
     * @package WordPress
     * @subpackage Twenty_Seventeen
     * @since Twenty Seventeen 1.0
     * @version 1.0
     */


    global $post;


    // Inclui o header padrão do tema
    get_header();

    // Chama a API PHP para obter os dados necessários
    $api_url = get_template_directory() . '/getAPICards.php';
    $api_data = [];
    if (file_exists($api_url)) {
        if (!defined('CURSOS_SELECAO_INTERNO')) {
            define('CURSOS_SELECAO_INTERNO', true);
        }
        ob_start();
        include($api_url);
        $api_json = ob_get_clean();
        $api_data = json_decode($api_json, true);
    }

    // var_dump($api_data);

    $curso_nome_opcoes = [];
    if (!function_exists('cursos_selecao_normalizar_nome')) {
        function cursos_selecao_normalizar_nome($valor) {
            if (!is_string($valor)) {
                return '';
            }
            $tratado = remove_accents($valor);
            $tratado = strtolower($tratado);
            return trim($tratado);
        }
    }
    if (is_array($api_data)) {
        // Percorre a resposta da API para montar uma lista única de nomes de cursos.
        $nomes_map = [];
        $pilha = [$api_data];

        while (!empty($pilha)) {
            $atual = array_pop($pilha);
            if (!is_array($atual)) {
                continue;
            }

            $nome_detectado = $atual['curso'] ?? $atual['nome'] ?? $atual['titulo'] ?? null;
            if (is_string($nome_detectado) && $nome_detectado !== '') {
                $nomes_map[$nome_detectado] = true;
            }

            foreach ($atual as $valor) {
                if (is_array($valor)) {
                    $pilha[] = $valor;
                }
            }
        }

        if (!empty($nomes_map)) {
            $curso_nome_opcoes = array_keys($nomes_map);
            natcasesort($curso_nome_opcoes);
            $curso_nome_opcoes = array_values($curso_nome_opcoes);
        }
    }

    $curso_links_map = [];
    if (!empty($curso_nome_opcoes)) {
        $public_post_types = get_post_types(['public' => true]);
        foreach ($curso_nome_opcoes as $nome_curso) {
            $slug = sanitize_title($nome_curso);
            $possiveis_caminhos = [
                $slug,
                'curso/' . $slug,
                'cursos/' . $slug
            ];
            $pagina = null;
            foreach ($possiveis_caminhos as $caminho) {
                $pagina = get_page_by_path($caminho, OBJECT, $public_post_types);
                if ($pagina instanceof WP_Post) {
                    break;
                }
            }

            if ($pagina instanceof WP_Post) {
                $url_destino = get_permalink($pagina);
            } else {
                $url_destino = home_url('/' . $slug . '/');
            }

            $chave_normalizada = cursos_selecao_normalizar_nome($nome_curso);
            if ($chave_normalizada !== '') {
                $curso_links_map[$chave_normalizada] = esc_url_raw($url_destino);
            }
        }
    }

    $curso_dados_raw = [];
    if (is_array($api_data)) {
        $curso_dados_raw = $api_data;
    }

?>

<style>
    .obsvin {
        display: inline-block;
        margin-top: 12px;
        font-size: 13px;
        /* color: #0b7ac9; */
        text-decoration: none;
    }
    .obsvin1 {
        padding-left: 15px;
    }
    .cursos-selecao {
        font-family: "Libre Franklin", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: #233048;
        padding: 48px 16px 72px;
        background: #ffffff;
    }
    .cursos-selecao__inner {
        width: 95%;
        max-width: 1200px;
        margin: 0 auto;
    }
    .cursos-selecao h2 {
        color: var(--Expand-Palete-US-Grayscale-US-Dark-Gray-1-US, #57606F);
        leading-trim: both;
        text-edge: cap;
        font-family: Ubuntu;
        font-size: 32px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }
    .cursos-selecao p {
        margin: 0;
        font-size: 14px;
        color: #6a7383;
    }
    .cursos-selecao__field-group {
        margin-top: 32px;
    }
    .cursos-selecao__search {
        position: relative;
        margin-top: 16px;
    }
    .cursos-selecao__search input {
        width: 100%;
        border: 1px solid #cdd5e6;
        border-radius: 28px;
        padding: 14px 52px 14px 24px;
        font-size: 15px;
        background: #ffffff;
        transition: border-color 0.2s ease;
    }
    .cursos-selecao__search input:focus {
        outline: none;
        border-color: #0b9edb;
    }
    .cursos-selecao .is-hidden {
        display: none !important;
    }
    .cursos-selecao__suggestions {
        position: absolute;
        top: calc(100% + 10px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #d9e1f1;
        border-radius: 22px;
        box-shadow: 0 18px 34px rgba(23, 38, 64, 0.12);
        padding: 8px;
        display: none;
        z-index: 20;
        max-height: 320px;
        overflow-y: auto;
    }
    .cursos-selecao__suggestions.is-visible {
        display: block;
    }
    .cursos-selecao__suggestion {
        width: 100%;
        border: none;
        background: transparent;
        text-align: left;
        padding: 12px 18px;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 600;
        color: #3c4b65;
        letter-spacing: 0.02em;
        cursor: pointer;
        transition: background-color 0.18s ease, color 0.18s ease;
    }
    .cursos-selecao__suggestion:hover,
    .cursos-selecao__suggestion:focus {
        background: #eef1f8;
        color: #1d74d7;
        outline: none;
    }
    .cursos-selecao__link {
        display: inline-block;
        margin-top: 12px;
        font-size: 13px;
        color: #0b7ac9;
        text-decoration: none;
    }
    .cursos-selecao__link:hover {
        text-decoration: underline;
    }
    .cursos-selecao__link.is-disabled {
        pointer-events: none;
        opacity: 0.6;
    }
    .cursos-selecao__options {
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
        margin-top: 16px;
    }
    .cursos-selecao__option {
        --option-color: #233048;
        border: none;
        border-radius: 32px;
        font-size: 15px;
        font-weight: 600;
        padding: 14px 32px;
        background: var(--option-color, #233048);
        color: #ffffff;
        letter-spacing: 0.01em;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        cursor: pointer;
        text-transform: none;
    }
    .cursos-selecao__option[data-variant="presencial"] { --option-color: #079bb1; }
    .cursos-selecao__option[data-variant="ead"] { --option-color: #e8437b; }
    .cursos-selecao__option[data-variant="hybrid"] { --option-color: #7a3fb4; }
    .cursos-selecao__option[data-variant="transferencia"] { --option-color: #f28c1b; }
    .cursos-selecao__option[data-variant="vestibular"] {
        --option-color: #14a86f;
        --option-bg-hover: rgba(20, 168, 111, 0.08);
    }
    .cursos-selecao__option[data-variant="enem"] {
        --option-color: #1f96c9;
        --option-bg-hover: rgba(31, 150, 201, 0.08);
    }
    .cursos-selecao__option[data-variant="segunda-graduacao"] {
        --option-color: #1274b3;
        --option-bg-hover: rgba(18, 116, 179, 0.08);
    }
    .cursos-selecao__options--modalidades .cursos-selecao__option {
        background: #ffffff;
        border: 2px solid var(--option-color);
        border-radius: 8px;
        color: var(--option-color);
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.06em;
        padding: 12px 36px;
        text-transform: uppercase;
        transition: background-color 0.2s ease, color 0.2s ease;
        transform: none;
        box-shadow: none;
    }
    .cursos-selecao__options--modalidades .cursos-selecao__option:hover,
    .cursos-selecao__options--modalidades .cursos-selecao__option:focus,
    .cursos-selecao__options--modalidades .cursos-selecao__option:active,
    .cursos-selecao__options--modalidades .cursos-selecao__option.is-active {
        background: var(--option-color);
        border-color: var(--option-color);
        color: #ffffff;
        box-shadow: none;
    }
    :not( .mejs-button ) > button:hover, :not( .mejs-button ) > button:focus, input[type="button"]:hover, input[type="button"]:focus, input[type="submit"]:hover, input[type="submit"]:focus {
        color: #fff;
    }
    .cursos-selecao__options--ingresso {
        position: relative;
        gap: 28px;
        color: #ffffff;
        /* justify-content: center; */
        padding: 8px 0;
    }
    .cursos-selecao__options--ingresso .cursos-selecao__option {
        background: #ffffff;
        border: 2px solid var(--option-color);
        border-radius: 8px;
        color: var(--option-color);
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.08em;
        padding: 14px 40px;
        text-transform: uppercase;
        min-width: 250px;
        box-shadow: none;
        transform: none;
        transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
    }
    .cursos-selecao__options--ingresso .cursos-selecao__option:hover,
    .cursos-selecao__options--ingresso .cursos-selecao__option:focus,
    .cursos-selecao__options--ingresso .cursos-selecao__option:active,
    .cursos-selecao__options--ingresso .cursos-selecao__option.is-active {
        background: #eef1f8;
        color: #ffffff;
        border-color: #fff;
    }
    .cursos-selecao__selects {
        display: flex;
        gap: 16px;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        margin-top: 20px;
    }
    .cursos-selecao__selects select {
        border: 1px solid #cdd5e6;
        border-radius: 28px;
        padding: 12px 24px;
        /* line-height: 1.2; */
        background: #ffffff;
        color: #233048;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        min-width: 280px;
        color: var(--Expand-Palete-US-Grayscale-US-Dark-Gray-1-US, #57606F);
        leading-trim: both;
        text-edge: cap;
        font-family: Ubuntu;
        font-size: 15px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        text-transform: uppercase;
    }
     @media(max-width:768px) {
        .cursos-selecao__selects {
            display: block !important;
        }
        .cursos-selecao__selects label {
            width: 100% !important;
            display: block !important;
        }
        .cursos-selecao__selects select {
            width: 100% !important;
        }
    }
    .cursos-selecao__selects select:focus {
        outline: none;
        border-color: #0b9edb;
        box-shadow: 0 0 0 3px rgba(11, 158, 219, 0.18);
    }
    .cursos-selecao__card {
        margin-top: 40px;
        border: 1px solid #d9e1f1;
        padding: 48px 56px 56px;
        border-radius: 30px;
        background: #eef1f8;
        box-shadow: 0 24px 48px rgba(23, 38, 64, 0.08);
    }
    .cursos-selecao__card h3 {
        margin: 0 0 36px;
        font-size: 24px;
        text-align: center;
        color: #2f3f57;
        color: var(--Expand-Palete-US-Grayscale-US-Dark-Gray-1-US, #57606F);
        leading-trim: both;
        text-edge: cap;
        font-family: Ubuntu;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
    }
    .cursos-selecao__inputs {
        display: grid;
        gap: 20px 26px;
        grid-template-columns: repeat(auto-fit, minmax(463px, 8fr));
    }
    .cursos-selecao__inputs label {
        position: relative;
        display: flex;
        flex-direction: column;
    }
    .cursos-selecao__label-text {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
        font-weight: 400;
    }
    .cursos-selecao__inputs input {
        border: 2px solid #d5dced;
        border-radius: 999px;
        padding: 16px 30px;
        font-size: 15px;
        letter-spacing: 0.06em;
        /* text-transform: uppercase; */
        background: #ffffff;
        color: #3c4b65;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        font-weight: 400;
    }
    .cursos-selecao__inputs input::placeholder {
        color: #8d97ac;
    }
    .cursos-selecao__inputs input:focus {
        outline: none;
        border-color: #1d74d7;
        box-shadow: 0 0 0 3px rgba(29, 116, 215, 0.25);
        background: #ffffff;
    }
    .cursos-selecao__consent {
        margin-top: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        font-size: 12px;
        color: var(--Expand-Palete-US-Grayscale-US-Dark-Gray-1-US, #57606F);
        leading-trim: both;
        text-edge: cap;
        font-family: Ubuntu;
        font-style: normal;
        font-weight: 400;
        line-height: 24px; /* 150% */
        text-transform: uppercase;
    }
    .cursos-selecao__consent input {
        width: 18px;
        height: 18px;
        border-radius: 4px;
        border: 2px solid #d5dced;
        margin: 0;
        accent-color: #f28c1b;
    }
    .cursos-selecao__submit {
        margin-top: 36px;
        text-align: center;
    }
    .cursos-selecao__submit button {
        border: none;
        border-radius: 10px;
        padding: 18px 68px;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        background: #f28c1b;
        color: #ffffff;
        cursor: pointer;
        box-shadow: 0 18px 36px rgba(242, 140, 27, 0.26);
        transition: transform 0.18s ease, box-shadow 0.18s ease, background-color 0.18s ease;
    }
    .cursos-selecao__submit button:hover,
    .cursos-selecao__submit button:focus,
    .cursos-selecao__submit button:active {
        transform: translateY(-2px);
        box-shadow: 0 22px 40px rgba(242, 140, 27, 0.34);
        background: #df7d12;
    }
    .page:not(.home) #content {
        padding-bottom: 0;
    }
    @media (max-width: 640px) {
        .cursos-selecao__inner {
            padding: 32px 20px;
        }
        .cursos-selecao__options {
            gap: 8px;
        }
        .cursos-selecao__option {
            padding: 10px 20px;
        }
        .cursos-selecao__suggestions {
            max-height: 260px;
        }
        .cursos-selecao__options--ingresso {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
        }
        .cursos-selecao__options--ingresso .cursos-selecao__option {
            width: 100%;
            text-align: center;
        }
        .cursos-selecao__card {
            padding: 38px 28px 46px;
            border-radius: 24px;
        }
        .cursos-selecao__card h3 {
            font-size: 22px;
            margin-bottom: 32px;
        }
        .cursos-selecao__inputs {
            gap: 16px;
            grid-template-columns: 1fr;
        }
        .cursos-selecao__inputs input {
            padding: 16px 26px;
        }
        .cursos-selecao__consent {
            flex-direction: column;
            text-align: center;
            gap: 8px;
        }
        .cursos-selecao__submit button {
            width: 100%;
        }
    }
</style>

<style>
    .cursos-hero {
        background: linear-gradient(90deg, #053052 0%, #E5457A 100%);
        color: #ffffff;
        padding: 48px 0;
        margin-top: -10px;
    }
    .cursos-hero__inner {
        width: 95%;
        max-width: 1200px;
        margin: 0 auto;
    }
    .cursos-hero__breadcrumb {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        letter-spacing: 0.08em;
        /* text-transform: uppercase; */
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 18px;
    }
    .cursos-hero__breadcrumb a {
        color: inherit;
        text-decoration: none;
    }
    .cursos-hero__breadcrumb a:hover {
        text-decoration: underline;
    }
    .cursos-hero__breadcrumb-separator {
        opacity: 0.6;
    }
    .cursos-hero__title {
        font-family: Ubuntu, sans-serif;
        font-size: 36px;
        font-weight: 600;
        line-height: 1.2;
        margin: 0;
    }
    @media (max-width: 640px) {
        .cursos-hero {
            padding: 36px 0;
        }
        .cursos-hero__title {
            font-size: 28px;
        }
    }
</style>

<section class="cursos-hero">
    <div class="cursos-hero__inner">
        <nav class="cursos-hero__breadcrumb" aria-label="Breadcrumb">
            <a href="<?php echo esc_url(home_url('/')); ?>">Início</a>
            <span class="cursos-hero__breadcrumb-separator">›</span>
            <span>Inscreva-se!</span>
        </nav>
        <h1 class="cursos-hero__title">Selecione seu curso para saber mais</h1>
    </div>
</section>

<section class="cursos-selecao">
    <div class="cursos-selecao__inner">
        <div class="cursos-selecao__field-group">
            <h2>Qual curso você quer fazer?</h2>
            <div class="cursos-selecao__search">
                <input type="search" name="curso" placeholder="Busque pelo curso desejado" aria-label="Buscar curso">
                <?php if (!empty($curso_nome_opcoes)): ?>
                    <div class="cursos-selecao__suggestions" data-curso-sugestoes></div>
                <?php endif; ?>
            </div>
            <span class="obsvin obsvin1">Clique</span> <a href="#" class="cursos-selecao__link">aqui</a> <span class="obsvin">e saiba mais sobre o curso.</span>
        </div>

        <div class="cursos-selecao__field-group is-hidden" data-section-modalidade>
            <h2>Como você prefere estudar?</h2>
            <div class="cursos-selecao__options cursos-selecao__options--modalidades" role="radiogroup" aria-label="Modalidade de estudo">
                <button type="button" class="cursos-selecao__option is-active" data-variant="presencial">Pós-Graduação Presencial</button>
                <button type="button" class="cursos-selecao__option" data-variant="ead">Pós-Graduação Digital (EAD)</button>
                <button type="button" class="cursos-selecao__option" data-variant="hybrid">Pós-Graduação Semipresencial</button>
            </div>
        </div>

        <div class="cursos-selecao__field-group is-hidden" data-section-dependente data-section-turno>
            <h2>Em qual unidade e turno que deseja estudar?</h2>
            <div class="cursos-selecao__selects">
                <label>
                    <!-- <span>Selecione uma unidade</span> -->
                    <select name="unidade" aria-label="Selecione uma unidade">
                        <option value="" selected disabled>Selecione uma unidade</option>
                    </select>
                </label>
                <label>
                    <!-- <span>Escolha o turno</span> -->
                    <select name="turno" aria-label="Escolha o turno">
                        <option value="" selected disabled>Escolha o turno</option>
                    </select>
                </label>
            </div>
        </div>

        <!-- <div class="cursos-selecao__field-group is-hidden" data-section-dependente data-section-ingresso>
            <h2>E qual a sua forma de ingresso?</h2>
            <div class="cursos-selecao__options cursos-selecao__options--ingresso" role="group" aria-label="Forma de ingresso">
                <button type="button" class="cursos-selecao__option" data-variant="vestibular">Vestibular</button>
                <button type="button" class="cursos-selecao__option" data-variant="enem">ENEM</button>
                <button type="button" class="cursos-selecao__option" data-variant="transferencia">Transferência</button>
            </div>
        </div> -->

        <form class="cursos-selecao__card is-hidden" data-section-dependente data-section-form action="#" method="post">
            <h3>Complete com seus dados de contato para avançar.</h3>
            <div class="cursos-selecao__inputs">
                <label>
                    <span class="cursos-selecao__label-text">Nome*</span>
                    <input type="text" name="nome" placeholder="NOME *" required>
                </label>
                <label>
                    <span class="cursos-selecao__label-text">Sobrenome*</span>
                    <input type="text" name="sobrenome" placeholder="SOBRENOME *" required>
                </label>
                <label>
                    <span class="cursos-selecao__label-text">E-mail*</span>
                    <input type="email" name="email" placeholder="E-MAIL *" required>
                </label>
                <label>
                    <span class="cursos-selecao__label-text">Telefone*</span>
                    <input type="tel" name="telefone" placeholder="TELEFONE *" required>
                </label>
            </div>
            <label class="cursos-selecao__consent">
                <input type="checkbox" name="lgpd" required>
                <span>Autorizo o uso dos meus dados pessoais para contato e matrícula, em conformidade com a LGPD.</span>
            </label>
            <div class="cursos-selecao__submit">
                <button type="submit">AVANÇAR</button>
            </div>
        </form>
    </div>
</section>




<?php
    get_footer();

    if (!empty($curso_nome_opcoes)):
        $cursos_json = wp_json_encode(array_values($curso_nome_opcoes));
        $curso_dados_json = wp_json_encode($curso_dados_raw);
        $curso_links_json = wp_json_encode((object) $curso_links_map);
?>

<script>
    (function(){
        const nomesCursos = <?php echo $cursos_json ?: '[]'; ?>;
        const dadosCursos = <?php echo $curso_dados_json ?: '{}'; ?>;
        const cursoLinks = <?php echo $curso_links_json ?: '{}'; ?>;
        if (!Array.isArray(nomesCursos) || !nomesCursos.length) return;

        const input = document.querySelector('.cursos-selecao__search input[name="curso"]');
        const container = document.querySelector('[data-curso-sugestoes]');
        const cursoLink = document.querySelector('.cursos-selecao__link');
        const blocoModalidade = document.querySelector('[data-section-modalidade]');
        const blocosDependentes = document.querySelectorAll('[data-section-dependente]');
        const botoesModalidade = document.querySelectorAll('.cursos-selecao__options--modalidades .cursos-selecao__option');
        const blocoUnidadeTurno = document.querySelector('[data-section-turno]');
        const selectUnidade = blocoUnidadeTurno ? blocoUnidadeTurno.querySelector('select[name="unidade"]') : null;
        const selectTurno = blocoUnidadeTurno ? blocoUnidadeTurno.querySelector('select[name="turno"]') : null;
        const blocoFormaIngresso = document.querySelector('[data-section-ingresso]');
        const blocoFormulario = document.querySelector('[data-section-form]');
        const apiInternaEndpoint = <?php echo wp_json_encode(esc_url(get_template_directory_uri() . '/getAPI_selecao.php')); ?>;
        const botoesFormaIngresso = blocoFormaIngresso
            ? blocoFormaIngresso.querySelectorAll('.cursos-selecao__options--ingresso .cursos-selecao__option')
            : [];
        const normalizar = (valor) => valor
            .toString()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim();
        const incluiCidadeRestrita = (valorNormalizado) => {
            if (!valorNormalizado) return false;
            return valorNormalizado.includes('rio de janeiro') || valorNormalizado.includes('sao paulo');
        };
        const eCidadeRestritaExata = (valorNormalizado) => {
            if (!valorNormalizado) return false;
            return valorNormalizado === 'rio de janeiro' || valorNormalizado === 'sao paulo';
        };
        const eEnderecoSaoPaulo = (valorNormalizado) => {
            if (!valorNormalizado) return false;
            if (valorNormalizado.includes('sao paulo')) return true;
            return /(^|\s|\-|\/)(sp)(\s|$)/.test(valorNormalizado);
        };
        const possuiTextoBloqueadoRJ = (valorNormalizado) => {
            if (!valorNormalizado) return false;
            if (eEnderecoSaoPaulo(valorNormalizado)) return true;
            return valorNormalizado.includes('paulista') || valorNormalizado.includes('jau');
        };
        const encontrarOriginalPorNormalizado = (lista, alvoNormalizado) => {
            if (!alvoNormalizado) return '';
            const colecao = Array.isArray(lista) ? lista : [];
            for (const item of colecao) {
                if (normalizar(item) === alvoNormalizado) {
                    return item;
                }
            }
            return '';
        };
        const obterLinkCurso = (nome) => {
            const chave = normalizar((nome || '').toString());
            if (!chave) return '';
            return cursoLinks && Object.prototype.hasOwnProperty.call(cursoLinks, chave)
                ? cursoLinks[chave]
                : '';
        };

        const atualizarLinkCurso = () => {
            if (!cursoLink) return;
            const nomeBase = cursoSelecionado && cursoSelecionado.nome
                ? cursoSelecionado.nome
                : (input && typeof input.value === 'string' ? input.value : '');
            const destino = obterLinkCurso(nomeBase);
            if (destino) {
                cursoLink.href = destino;
                cursoLink.setAttribute('target', '_blank');
                cursoLink.classList.remove('is-disabled');
                cursoLink.removeAttribute('aria-disabled');
            } else {
                cursoLink.href = '#';
                cursoLink.removeAttribute('target');
                cursoLink.classList.add('is-disabled');
                cursoLink.setAttribute('aria-disabled', 'true');
            }
        };

        const configuracaoLocalizacaoPorVariante = {
            default: {
                titulo: 'Em qual unidade e turno que deseja estudar?',
                placeholderUnidade: 'Selecione uma unidade',
                placeholderTurno: 'Escolha o turno',
                ariaUnidade: 'Selecione uma unidade',
                ariaTurno: 'Escolha o turno',
                etiquetaUnidade: 'Unidade',
                etiquetaTurno: 'Turno',
                usarUnidadesNoTurno: false
            },
            ead: {
                titulo: 'Em qual estado e polo você deseja estudar?',
                placeholderUnidade: 'Selecione um estado',
                placeholderTurno: 'Escolha o polo',
                ariaUnidade: 'Selecione um estado',
                ariaTurno: 'Escolha o polo',
                etiquetaUnidade: 'Estado',
                etiquetaTurno: 'Polo',
                usarUnidadesNoTurno: true
            }
        };
        let configuracaoLocalizacaoAtual = configuracaoLocalizacaoPorVariante.default;
        let cursoSelecionado = null;
        let varianteSelecionada = null;
        let modalidadeSelecionadaPorClique = false;
        let ultimaConsultaModalidade = 0;
        let detalhesVarianteAtual = { unidades: [], turnos: [], codigos: [], ofertas: [], mapaOferta: Object.create(null), polosPorUnidade: Object.create(null) };

        const aplicarConfiguracaoLocalizacao = (slug) => {
            const proxima = slug === 'ead'
                ? configuracaoLocalizacaoPorVariante.ead
                : configuracaoLocalizacaoPorVariante.default;
            configuracaoLocalizacaoAtual = proxima;
            if (blocoUnidadeTurno) {
                const titulo = blocoUnidadeTurno.querySelector('h2');
                if (titulo) {
                    titulo.textContent = proxima.titulo;
                }
            }
            if (selectUnidade) {
                selectUnidade.setAttribute('aria-label', proxima.ariaUnidade);
            }
            if (selectTurno) {
                selectTurno.setAttribute('aria-label', proxima.ariaTurno);
            }
        };

        aplicarConfiguracaoLocalizacao(null);
        atualizarLinkCurso();

        if (!input || !container) {
            return;
        }

        const limparAnimacaoEntrada = (alvo) => {
            if (!alvo || !alvo.dataset) return;
            const identificador = Number.parseInt(alvo.dataset.fadeTimeoutId || '', 10);
            if (Number.isInteger(identificador)) {
                window.clearTimeout(identificador);
                delete alvo.dataset.fadeTimeoutId;
            }
            alvo.style.removeProperty('transition');
            alvo.style.removeProperty('opacity');
            alvo.style.removeProperty('willChange');
        };

        const animarEntradaSecao = (alvo) => {
            if (!alvo) return;
            limparAnimacaoEntrada(alvo);
            const DURACAO_FADE = 1000;
            alvo.style.opacity = '0';
            alvo.style.transition = `opacity ${DURACAO_FADE}ms ease`;
            alvo.style.willChange = 'opacity';

            const iniciar = () => {
                alvo.style.opacity = '1';
            };

            if (typeof requestAnimationFrame === 'function') {
                requestAnimationFrame(iniciar);
            } else {
                setTimeout(iniciar, 16);
            }

            const timeoutId = window.setTimeout(() => {
                alvo.style.removeProperty('transition');
                alvo.style.removeProperty('opacity');
                alvo.style.removeProperty('willChange');
                delete alvo.dataset.fadeTimeoutId;
            }, DURACAO_FADE + 120);
            alvo.dataset.fadeTimeoutId = String(timeoutId);
        };

        const exibirSecao = (elemento, { focusPrimeiro = false } = {}) => {
            if (!elemento) return;
            const estavaOculta = elemento.classList.contains('is-hidden');
            if (estavaOculta) {
                animarEntradaSecao(elemento);
            }
            elemento.classList.remove('is-hidden');
            if (!estavaOculta) return;

            const focar = () => {
                const deslocamentoExtra = 300;
                const rect = elemento.getBoundingClientRect();
                const posicaoAtual = window.pageYOffset || document.documentElement.scrollTop || 0;
                const destino = Math.max(0, posicaoAtual + rect.top - deslocamentoExtra);
                window.scrollTo({ top: destino, behavior: 'smooth' });
                if (focusPrimeiro) {
                    const alvo = elemento.querySelector('input, select, button, textarea, a[href]');
                    if (alvo) {
                        try {
                            alvo.focus({ preventScroll: true });
                        } catch (erro) {
                            alvo.focus();
                        }
                    }
                }
            };

            if (typeof requestAnimationFrame === 'function') {
                requestAnimationFrame(focar);
            } else {
                setTimeout(focar, 60);
            }
        };

        const ocultarSecao = (elemento) => {
            if (!elemento) return;
            limparAnimacaoEntrada(elemento);
            elemento.classList.add('is-hidden');
        };

        const identificarVariante = (texto) => {
            const base = normalizar((texto || '').toString());
            if (!base) return null;
            if (base.includes('semi') || base.includes('hibr')) return 'hybrid';
            if (base.includes('digital') || base.includes('ead') || base.includes('online')) return 'ead';
            if (base.includes('presencial')) return 'presencial';
            return 'presencial';
        };

        const separarTexto = (texto) => texto
            .split(/[,;|\n]+/)
            .map((parte) => parte.replace(/\s+/g, ' ').trim())
            .map((parte) => parte.replace(/^[\-]+/, '').trim())
            .filter(Boolean);

        const normalizarColecao = (entrada) => {
            const lista = Array.isArray(entrada) ? entrada : [];
            const tratados = lista
                .map((valor) => (typeof valor === 'string' ? valor : ''))
                .map((valor) => valor.replace(/\s+/g, ' ').trim())
                .filter(Boolean);
            const unicos = Array.from(new Set(tratados));
            unicos.sort((a, b) => a.localeCompare(b, 'pt-BR', { sensitivity: 'base' }));
            return unicos;
        };

        const normalizarOfertas = (entrada) => {
            const lista = Array.isArray(entrada) ? entrada : [];
            const numericos = lista
                .map((valor) => Number.parseInt(String(valor).trim(), 10))
                .filter((numero) => Number.isFinite(numero) && !Number.isNaN(numero));
            const unicos = Array.from(new Set(numericos));
            unicos.sort((a, b) => a - b);
            return unicos;
        };

        const clonarMapaOferta = (fonte) => {
            const destino = Object.create(null);
            if (!fonte || typeof fonte !== 'object') {
                return destino;
            }
            Object.keys(fonte).forEach((chave) => {
                destino[chave] = fonte[chave];
            });
            return destino;
        };
        const clonarMapaPolos = (fonte) => {
            const destino = Object.create(null);
            if (!fonte || typeof fonte !== 'object') {
                return destino;
            }
            Object.keys(fonte).forEach((chave) => {
                const lista = Array.isArray(fonte[chave]) ? [...fonte[chave]] : [];
                destino[chave] = lista;
            });
            return destino;
        };

        const gerarChaveOferta = (unidade, turno) => {
            const chaveUnidade = normalizar((unidade || '').toString());
            const chaveTurno = normalizar((turno || '').toString());
            return `${chaveUnidade}::${chaveTurno}`;
        };

        const obterPolosParaUnidade = (unidadeSelecionada) => {
            if (!configuracaoLocalizacaoAtual.usarUnidadesNoTurno) {
                return [];
            }
            const unidadeNormalizada = normalizar((unidadeSelecionada || '').toString());
            if (!unidadeNormalizada) {
                return [];
            }
            const mapaPolos = detalhesVarianteAtual && typeof detalhesVarianteAtual.polosPorUnidade === 'object'
                ? detalhesVarianteAtual.polosPorUnidade
                : Object.create(null);

            const filtrarPolosRestritosRJ = (lista) => {
                return (Array.isArray(lista) ? lista : [])
                    .filter((entrada) => !possuiTextoBloqueadoRJ(normalizar(entrada || '')));
            };

            if (unidadeNormalizada === 'rio de janeiro') {
                const todosPolos = [];
                Object.values(mapaPolos || {}).forEach((colecao) => {
                    if (Array.isArray(colecao)) {
                        colecao.forEach((polo) => todosPolos.push(polo));
                    }
                });
                if (!todosPolos.length) {
                    const turnosOriginais = Array.isArray(detalhesVarianteAtual.turnos) ? detalhesVarianteAtual.turnos : [];
                    turnosOriginais.forEach((turno) => todosPolos.push(turno));
                }
                const polosFiltrados = filtrarPolosRestritosRJ(todosPolos);
                if (polosFiltrados.length) {
                    return normalizarColecao(polosFiltrados);
                }
            }

            let listaPolos = Array.isArray(mapaPolos?.[unidadeNormalizada]) ? [...mapaPolos[unidadeNormalizada]] : [];
            if (unidadeNormalizada === 'rio de janeiro' && listaPolos.length) {
                listaPolos = filtrarPolosRestritosRJ(listaPolos);
            }
            if (listaPolos.length) {
                return normalizarColecao(listaPolos);
            }
            const mapaOferta = detalhesVarianteAtual && typeof detalhesVarianteAtual.mapaOferta === 'object'
                ? detalhesVarianteAtual.mapaOferta
                : Object.create(null);
            const turnosOriginais = Array.isArray(detalhesVarianteAtual.turnos) ? detalhesVarianteAtual.turnos : [];
            const polos = new Set();
            Object.keys(mapaOferta).forEach((chave) => {
                const partes = chave.split('::');
                if (partes.length !== 2) {
                    return;
                }
                const [unidadeChave, turnoChave] = partes;
                if (unidadeChave === unidadeNormalizada && turnoChave) {
                    const original = encontrarOriginalPorNormalizado(turnosOriginais, turnoChave) || turnoChave;
                    if (original) {
                        polos.add(original);
                    }
                }
            });
            if (!polos.size) {
                turnosOriginais.forEach((turno) => {
                    const chave = gerarChaveOferta(unidadeSelecionada, turno);
                    if (mapaOferta[chave]) {
                        polos.add(turno);
                    }
                });
            }
            return normalizarColecao(Array.from(polos));
        };

        const obterTurnosCompativeis = (unidadeSelecionada) => {
            if (!configuracaoLocalizacaoAtual.usarUnidadesNoTurno) {
                return Array.isArray(detalhesVarianteAtual.turnos) ? [...detalhesVarianteAtual.turnos] : [];
            }
            if (!unidadeSelecionada) {
                return [];
            }
            const polos = obterPolosParaUnidade(unidadeSelecionada);
            if (polos.length) {
                return polos;
            }
            return Array.isArray(detalhesVarianteAtual.turnos) ? [...detalhesVarianteAtual.turnos] : [];
        };

        const extrairPrimeiroTexto = (objeto, chaves) => {
            if (!objeto || typeof objeto !== 'object') {
                return '';
            }
            for (const chave of chaves) {
                const valor = objeto[chave];
                if (typeof valor === 'string') {
                    const tratado = valor.trim();
                    if (tratado) {
                        return tratado;
                    }
                }
            }
            return '';
        };

        const coletarPorChaves = (origem, chaves) => {
            if (!origem || typeof origem !== 'object') return [];
            const chavesNormalizadas = chaves.map((chave) => chave.toLowerCase());
            const encontrados = [];

            const processarValor = (valor) => {
                if (!valor && valor !== 0) return;
                if (typeof valor === 'string') {
                    separarTexto(valor).forEach((parte) => encontrados.push(parte));
                    return;
                }
                if (Array.isArray(valor)) {
                    valor.forEach(processarValor);
                    return;
                }
                if (typeof valor === 'object') {
                    Object.values(valor).forEach(processarValor);
                }
            };

            const explorar = (alvo) => {
                if (!alvo || typeof alvo !== 'object') return;
                Object.entries(alvo).forEach(([chave, valor]) => {
                    const chaveNormalizada = chave.toLowerCase();
                    if (chavesNormalizadas.includes(chaveNormalizada)) {
                        processarValor(valor);
                    }
                    if (Array.isArray(valor)) {
                        valor.forEach((interno) => {
                            if (interno && typeof interno === 'object') {
                                explorar(interno);
                            }
                        });
                    } else if (valor && typeof valor === 'object') {
                        explorar(valor);
                    }
                });
            };

            explorar(origem);
            return normalizarColecao(encontrados);
        };

        const coletarModalidades = (item) => coletarPorChaves(item, ['modalidade', 'modalidades', 'categoria', 'categorias']);
        const coletarUnidades = (item) => coletarPorChaves(item, ['campus', 'unidade', 'unidades', 'campi', 'polo', 'polos', 'local', 'locais', 'cidade', 'cidades']);
        const coletarTurnos = (item) => coletarPorChaves(item, ['turno', 'turnos', 'periodo', 'periodos', 'horario', 'horarios']);

        const ordenarLista = (lista) => [...lista].sort((a, b) => a.localeCompare(b, 'pt-BR', { sensitivity: 'base' }));

        const extrairCursos = (dados) => {
            if (!dados || (typeof dados !== 'object' && !Array.isArray(dados))) return [];
            const mapaCursos = new Map();
            const pilha = Array.isArray(dados) ? [...dados] : [dados];

            const registrar = (nomeCurso, variante, unidades, turnos, codigo, ofertaId) => {
                if (!variante) return;
                if (!mapaCursos.has(nomeCurso)) {
                    mapaCursos.set(nomeCurso, {
                        nome: nomeCurso,
                        variantes: new Set(),
                        detalhes: new Map()
                    });
                }
                const registro = mapaCursos.get(nomeCurso);
                registro.variantes.add(variante);
                if (!registro.detalhes.has(variante)) {
                    registro.detalhes.set(variante, { unidades: new Set(), turnos: new Set(), codigos: new Set(), ofertas: new Set(), mapaOferta: Object.create(null), polosPorUnidade: Object.create(null) });
                }
                const alvo = registro.detalhes.get(variante);
                unidades.forEach((entrada) => alvo.unidades.add(entrada));
                turnos.forEach((entrada) => alvo.turnos.add(entrada));
                if (codigo) {
                    alvo.codigos.add(codigo);
                }
                if (Number.isFinite(ofertaId)) {
                    alvo.ofertas.add(ofertaId);
                }
            };

            while (pilha.length) {
                const atual = pilha.pop();
                if (!atual || typeof atual !== 'object') continue;

                const nomeBruto = atual.curso || atual.nome || atual.titulo;
                const nomeCurso = typeof nomeBruto === 'string' ? nomeBruto.trim() : '';
                if (nomeCurso) {
                    const modalidadesEncontradas = coletarModalidades(atual);
                    const unidadesEncontradas = coletarUnidades(atual);
                    const turnosEncontrados = coletarTurnos(atual);
                    const codigo = typeof atual.mnemonico === 'string'
                        ? atual.mnemonico.trim()
                        : typeof atual.mneumonico === 'string'
                            ? atual.mneumonico.trim()
                            : '';
                    const ofertaBruta = atual.id_oferta ?? atual.oferta_id ?? atual.oferta ?? atual.id ?? null;
                    const ofertaId = Number.parseInt(String(ofertaBruta ?? '').trim(), 10);

                    if (modalidadesEncontradas.length) {
                        modalidadesEncontradas.forEach((entrada) => {
                            const variante = identificarVariante(entrada);
                            if (variante) {
                                registrar(nomeCurso, variante, unidadesEncontradas, turnosEncontrados, codigo, ofertaId);
                            }
                        });
                    } else {
                        registrar(nomeCurso, 'presencial', unidadesEncontradas, turnosEncontrados, codigo, ofertaId);
                    }
                }

                Object.values(atual).forEach((valor) => {
                    if (Array.isArray(valor) || (valor && typeof valor === 'object')) {
                        pilha.push(valor);
                    }
                });
            }

            return Array.from(mapaCursos.values()).map((registro) => {
                const detalhesNormalizados = {};
                registro.detalhes.forEach((info, variante) => {
                    detalhesNormalizados[variante] = {
                        unidades: ordenarLista(Array.from(info.unidades)),
                        turnos: ordenarLista(Array.from(info.turnos)),
                        codigos: ordenarLista(Array.from(info.codigos)),
                        ofertas: normalizarOfertas(Array.from(info.ofertas)),
                        mapaOferta: clonarMapaOferta(info.mapaOferta),
                        polosPorUnidade: clonarMapaPolos(info.polosPorUnidade)
                    };
                });
                return {
                    nome: registro.nome,
                    variantes: Array.from(registro.variantes),
                    detalhes: detalhesNormalizados
                };
            });
        };

        const cursosDetalhadosBrutos = extrairCursos(dadosCursos);
        const cursosDetalhados = Array.isArray(cursosDetalhadosBrutos) ? cursosDetalhadosBrutos : [];

        const localizarCurso = (nome) => {
            const alvo = normalizar((nome || '').trim());
            if (!alvo) return null;
            return cursosDetalhados.find((item) => normalizar(item.nome) === alvo) || null;
        };

        const resetarSelect = (select, placeholder) => {
            if (!select) return;
            select.innerHTML = '';
            const option = document.createElement('option');
            option.value = '';
            option.textContent = placeholder;
            option.disabled = true;
            option.selected = true;
            select.appendChild(option);
            select.disabled = true;
        };

        const preencherSelect = (select, valores, placeholder) => {
            if (!select) return;
            resetarSelect(select, placeholder);
            const lista = normalizarColecao(Array.isArray(valores) ? valores : []);
            if (!lista.length) return;
            select.disabled = false;
            let opcoesAdicionadas = 0;
            lista.forEach((valor) => {
                const destinoNormalizado = normalizar(valor || '');
                if (select === selectUnidade) {
                    const isDigital = configuracaoLocalizacaoAtual === configuracaoLocalizacaoPorVariante.ead;
                    if (isDigital) {
                        if (!incluiCidadeRestrita(destinoNormalizado)) {
                            return; // em Digital (EaD) exibe apenas cidades permitidas
                        }
                    } else if (incluiCidadeRestrita(destinoNormalizado)) {
                        return; // oculta cidades restritas nas demais modalidades
                    }
                }
                if (select === selectTurno && eCidadeRestritaExata(destinoNormalizado)) {
                    return; // evita exibir cidades como opção de turno
                }
                const option = document.createElement('option');
                option.value = valor;
                option.textContent = valor;
                select.appendChild(option);
                opcoesAdicionadas += 1;
            });
            if (!opcoesAdicionadas) {
                select.disabled = true;
            }
        };

        const atualizarExibicaoIngressoEFormulario = () => {
            const unidadePronta = !selectUnidade || selectUnidade.disabled || Boolean(selectUnidade.value);
            const turnoPronto = !selectTurno || selectTurno.disabled || Boolean(selectTurno.value);
            const requisitosSelecionados = modalidadeSelecionadaPorClique && Boolean(varianteSelecionada);
            const deveExibir = unidadePronta && turnoPronto && requisitosSelecionados;

            if (blocoFormaIngresso) {
                if (deveExibir) {
                    exibirSecao(blocoFormaIngresso);
                } else {
                    ocultarSecao(blocoFormaIngresso);
                    if (botoesFormaIngresso.length) {
                        botoesFormaIngresso.forEach((botao) => botao.classList.remove('is-active'));
                    }
                }
            }

            if (blocoFormulario) {
                const formularioJaVisivel = !blocoFormulario.classList.contains('is-hidden');
                if (deveExibir) {
                    exibirSecao(blocoFormulario, { focusPrimeiro: !formularioJaVisivel });
                } else {
                    ocultarSecao(blocoFormulario);
                }
            }
        };

        const manterDependentesOcultos = () => {
            blocosDependentes.forEach(ocultarSecao);
            resetarSelect(selectUnidade, configuracaoLocalizacaoAtual.placeholderUnidade);
            resetarSelect(selectTurno, configuracaoLocalizacaoAtual.placeholderTurno);
            atualizarExibicaoIngressoEFormulario();
        };

        const obterDetalhesVariante = (curso, variante) => {
            if (!curso || !variante) {
                return { unidades: [], turnos: [], codigos: [], ofertas: [], mapaOferta: Object.create(null) };
            }
            const detalhes = curso.detalhes?.[variante];
            return {
                unidades: Array.isArray(detalhes?.unidades) ? [...detalhes.unidades] : [],
                turnos: Array.isArray(detalhes?.turnos) ? [...detalhes.turnos] : [],
                codigos: Array.isArray(detalhes?.codigos) ? [...detalhes.codigos] : [],
                ofertas: Array.isArray(detalhes?.ofertas) ? [...detalhes.ofertas] : [],
                mapaOferta: clonarMapaOferta(detalhes?.mapaOferta),
                polosPorUnidade: clonarMapaPolos(detalhes?.polosPorUnidade)
            };
        };

        const preencherSelects = (unidades, turnos, { restaurarValores = true } = {}) => {
            if (!blocoUnidadeTurno) return;
            const valorUnidadeAnterior = restaurarValores && selectUnidade ? selectUnidade.value : '';
            const valorTurnoAnterior = restaurarValores && selectTurno ? selectTurno.value : '';
            const listaUnidades = Array.isArray(unidades) ? unidades : [];
            preencherSelect(selectUnidade, listaUnidades, configuracaoLocalizacaoAtual.placeholderUnidade);
            if (restaurarValores && selectUnidade && valorUnidadeAnterior) {
                const opcoesUnidade = Array.from(selectUnidade.options || []);
                const existeUnidade = opcoesUnidade.some((opcao) => opcao.value === valorUnidadeAnterior);
                if (existeUnidade) {
                    selectUnidade.value = valorUnidadeAnterior;
                    selectUnidade.disabled = false;
                }
            }
            let listaTurnos = Array.isArray(turnos) ? turnos : [];
            if (configuracaoLocalizacaoAtual.usarUnidadesNoTurno) {
                const unidadeAtual = selectUnidade && selectUnidade.value ? selectUnidade.value : '';
                listaTurnos = obterTurnosCompativeis(unidadeAtual);
            }
            preencherSelect(selectTurno, listaTurnos, configuracaoLocalizacaoAtual.placeholderTurno);
            let turnoParaRestaurar = valorTurnoAnterior;
            if (configuracaoLocalizacaoAtual.usarUnidadesNoTurno) {
                const unidadeAtual = selectUnidade && selectUnidade.value ? selectUnidade.value : '';
                if (!unidadeAtual) {
                    turnoParaRestaurar = '';
                }
            }
            if (restaurarValores && selectTurno && turnoParaRestaurar) {
                const opcoesTurno = Array.from(selectTurno.options || []);
                const existeTurno = opcoesTurno.some((opcao) => opcao.value === turnoParaRestaurar);
                if (existeTurno) {
                    selectTurno.value = turnoParaRestaurar;
                    selectTurno.disabled = false;
                }
            }

            if (modalidadeSelecionadaPorClique && varianteSelecionada) {
                exibirSecao(blocoUnidadeTurno);
            } else {
                ocultarSecao(blocoUnidadeTurno);
            }
            atualizarExibicaoIngressoEFormulario();
        };

        const aplicarVarianteSelecionada = ({ usarDadosAPI = true, resetDependentes = false } = {}) => {
            if (!blocoUnidadeTurno) return;
            aplicarConfiguracaoLocalizacao(varianteSelecionada);
            if (resetDependentes) {
                blocosDependentes.forEach((secao) => {
                    if (secao !== blocoUnidadeTurno) {
                        ocultarSecao(secao);
                    }
                });
                resetarSelect(selectUnidade, configuracaoLocalizacaoAtual.placeholderUnidade);
                resetarSelect(selectTurno, configuracaoLocalizacaoAtual.placeholderTurno);
            }
            const detalhes = obterDetalhesVariante(cursoSelecionado, varianteSelecionada);
            detalhesVarianteAtual = {
                unidades: Array.isArray(detalhes.unidades) ? [...detalhes.unidades] : [],
                turnos: Array.isArray(detalhes.turnos) ? [...detalhes.turnos] : [],
                codigos: Array.isArray(detalhes.codigos) ? [...detalhes.codigos] : [],
                ofertas: Array.isArray(detalhes.ofertas) ? [...detalhes.ofertas] : [],
                mapaOferta: clonarMapaOferta(detalhes.mapaOferta),
                polosPorUnidade: clonarMapaPolos(detalhes.polosPorUnidade)
            };
            preencherSelects(detalhesVarianteAtual.unidades, detalhesVarianteAtual.turnos, { restaurarValores: !resetDependentes });

            if (usarDadosAPI) {
                solicitarDetalhesViaAPI(detalhesVarianteAtual.codigos);
            }
        };

        const definirModalidadeAtiva = (botao, foiClique = false) => {
            botoesModalidade.forEach((elemento) => {
                elemento.classList.toggle('is-active', elemento === botao);
            });
            varianteSelecionada = botao ? botao.getAttribute('data-variant') : null;
            if (foiClique) {
                modalidadeSelecionadaPorClique = true;
            }
            aplicarVarianteSelecionada({ resetDependentes: foiClique });
        };

        const resetarModalidades = () => {
            botoesModalidade.forEach((botao) => {
                botao.classList.remove('is-hidden', 'is-active');
            });
            varianteSelecionada = null;
            modalidadeSelecionadaPorClique = false;
            ultimaConsultaModalidade += 1;
            aplicarVarianteSelecionada({ usarDadosAPI: false });
        };

        const solicitarDetalhesViaAPI = (codigos) => {
            if (!apiInternaEndpoint || !modalidadeSelecionadaPorClique || !varianteSelecionada) {
                return;
            }

            const listaCodigos = Array.isArray(codigos) ? codigos.filter(Boolean) : [];
            if (!listaCodigos.length) {
                return;
            }

            const codigo = listaCodigos[0];
            let urlRequisicao = apiInternaEndpoint;

            try {
                const urlObj = new URL(apiInternaEndpoint, window.location.origin);
                urlObj.searchParams.set('mnemonico', codigo);
                urlObj.searchParams.set('tipo', 'posgraduacao');
                urlObj.searchParams.set('_', Date.now().toString());
                urlRequisicao = urlObj.toString();
            } catch (erro) {
                const separador = apiInternaEndpoint.includes('?') ? '&' : '?';
                urlRequisicao = `${apiInternaEndpoint}${separador}mnemonico=${encodeURIComponent(codigo)}&tipo=posgraduacao&_=${Date.now()}`;
            }

            const marcadorLocal = ++ultimaConsultaModalidade;
            const chavesUnidadeAPI = ['unidade', 'unidades', 'campus', 'campi', 'local', 'locais', 'cidade', 'cidades', 'polo', 'polos'];
            const chavesCidadeAPI = ['cidade', 'cidades', 'local', 'locais'];
            const chavesTurnoPadraoAPI = ['horario', 'horarios', 'turno', 'turnos', 'periodo', 'periodos'];
            const chavesPoloAPI = ['polo', 'polos', 'unidade', 'unidades', 'campus', 'campi'];
            const ofertasSet = new Set(Array.isArray(detalhesVarianteAtual.ofertas) ? detalhesVarianteAtual.ofertas : []);
            const mapaOferta = Object.create(null);
            const polosPorUnidadeAtual = clonarMapaPolos(detalhesVarianteAtual.polosPorUnidade);

            const registrarOfertaNoMapa = (idValor, unidadeValor, turnoValor) => {
                const numero = Number.parseInt(String(idValor).trim(), 10);
                if (Number.isNaN(numero)) {
                    return;
                }
                ofertasSet.add(numero);

                const chaveCompleta = gerarChaveOferta(unidadeValor, turnoValor);
                if (chaveCompleta && !mapaOferta[chaveCompleta]) {
                    mapaOferta[chaveCompleta] = numero;
                }
                if (unidadeValor) {
                    const chaveUnidade = gerarChaveOferta(unidadeValor, '');
                    if (!mapaOferta[chaveUnidade]) {
                        mapaOferta[chaveUnidade] = numero;
                    }
                }
                if (turnoValor) {
                    const chaveTurno = gerarChaveOferta('', turnoValor);
                    if (!mapaOferta[chaveTurno]) {
                        mapaOferta[chaveTurno] = numero;
                    }
                }
            };

            fetch(urlRequisicao, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json'
                }
            }).then((resposta) => {
                if (!resposta.ok) {
                    throw new Error(`Falha ao carregar dados (${resposta.status})`);
                }
                return resposta.json();
            }).then((payload) => {
                if (marcadorLocal !== ultimaConsultaModalidade) {
                    return;
                }

                const raiz = payload?.data && typeof payload.data === 'object' ? payload.data : payload;
                if (!raiz || typeof raiz !== 'object') {
                    throw new Error('Resposta inválida da API interna');
                }

                const listaInvestimentos = Array.isArray(raiz.investimentos) ? raiz.investimentos : [];
                const unidadesSet = new Set();
                const turnosSet = new Set();

                const processarItem = (item) => {
                    if (!item || typeof item !== 'object') return;
                    const modalidadeItem = item.modalidade || item.Modalidade || item.modalidade_nome;
                    const varianteItem = identificarVariante(modalidadeItem || '');
                    if (varianteItem && varianteSelecionada && varianteItem !== varianteSelecionada) {
                        return;
                    }

                    const unidadesAssociadas = coletarPorChaves(item, chavesUnidadeAPI);
                    const turnosAssociados = (() => {
                        if (!configuracaoLocalizacaoAtual.usarUnidadesNoTurno) {
                            return coletarPorChaves(item, chavesTurnoPadraoAPI);
                        }
                        const polosBrutos = coletarPorChaves(item, chavesPoloAPI);
                        const polosFiltrados = polosBrutos
                            .map((valor) => typeof valor === 'string' ? valor.trim() : '')
                            .filter(Boolean)
                            .filter((valor) => !incluiCidadeRestrita(normalizar(valor)));
                        const cidadesPreferenciais = unidadesAssociadas.filter((texto) => incluiCidadeRestrita(normalizar(texto)));
                        const cidadesExtras = coletarPorChaves(item, chavesCidadeAPI);
                        const destinos = cidadesPreferenciais.length ? cidadesPreferenciais : cidadesExtras;
                        destinos.forEach((cidadeTexto) => {
                            const chaveCidade = normalizar(cidadeTexto || '');
                            if (!chaveCidade) {
                                return;
                            }
                            if (!polosPorUnidadeAtual[chaveCidade]) {
                                polosPorUnidadeAtual[chaveCidade] = [];
                            }
                            const listaDestino = polosPorUnidadeAtual[chaveCidade];
                            polosFiltrados.forEach((poloTexto) => {
                                const chavePolo = normalizar(poloTexto || '');
                                if (!chavePolo) {
                                    return;
                                }
                                const existe = listaDestino.some((entrada) => normalizar(entrada) === chavePolo);
                                if (!existe) {
                                    listaDestino.push(poloTexto);
                                }
                            });
                        });
                        return polosFiltrados;
                    })();
                    unidadesAssociadas.forEach((valor) => unidadesSet.add(valor));
                    turnosAssociados.forEach((valor) => turnosSet.add(valor));

                    const idOfertaPotencial = item.id || item.id_oferta || item.oferta_id || item.oferta;
                    if (idOfertaPotencial !== undefined && idOfertaPotencial !== null) {
                        if (unidadesAssociadas.length) {
                            unidadesAssociadas.forEach((unidadeTexto) => {
                                if (turnosAssociados.length) {
                                    turnosAssociados.forEach((turnoTexto) => {
                                        registrarOfertaNoMapa(idOfertaPotencial, unidadeTexto, turnoTexto);
                                    });
                                } else {
                                    registrarOfertaNoMapa(idOfertaPotencial, unidadeTexto, '');
                                }
                            });
                        } else if (turnosAssociados.length) {
                            turnosAssociados.forEach((turnoTexto) => {
                                registrarOfertaNoMapa(idOfertaPotencial, '', turnoTexto);
                            });
                        } else {
                            const unidadePrimaria = extrairPrimeiroTexto(item, ['unidade', 'campus', 'campi', 'local', 'locais', 'cidade', 'polo', 'polos']);
                            const turnoPrimario = extrairPrimeiroTexto(item, ['turno', 'horario', 'periodo', 'periodos']);
                            registrarOfertaNoMapa(idOfertaPotencial, unidadePrimaria, turnoPrimario);
                        }
                    }
                };

                listaInvestimentos.forEach(processarItem);

                if (!unidadesSet.size && !turnosSet.size) {
                    const unidadesAssociadas = coletarPorChaves(raiz, chavesUnidadeAPI);
                    const turnosAssociados = (() => {
                        if (!configuracaoLocalizacaoAtual.usarUnidadesNoTurno) {
                            return coletarPorChaves(raiz, chavesTurnoPadraoAPI);
                        }
                        const polosBrutos = coletarPorChaves(raiz, chavesPoloAPI);
                        const polosFiltrados = polosBrutos
                            .map((valor) => typeof valor === 'string' ? valor.trim() : '')
                            .filter(Boolean)
                            .filter((valor) => !incluiCidadeRestrita(normalizar(valor)));
                        const cidadesPreferenciais = unidadesAssociadas.filter((texto) => incluiCidadeRestrita(normalizar(texto)));
                        const cidadesExtras = coletarPorChaves(raiz, chavesCidadeAPI);
                        const destinos = cidadesPreferenciais.length ? cidadesPreferenciais : cidadesExtras;
                        destinos.forEach((cidadeTexto) => {
                            const chaveCidade = normalizar(cidadeTexto || '');
                            if (!chaveCidade) {
                                return;
                            }
                            if (!polosPorUnidadeAtual[chaveCidade]) {
                                polosPorUnidadeAtual[chaveCidade] = [];
                            }
                            const listaDestino = polosPorUnidadeAtual[chaveCidade];
                            polosFiltrados.forEach((poloTexto) => {
                                const chavePolo = normalizar(poloTexto || '');
                                if (!chavePolo) {
                                    return;
                                }
                                const existe = listaDestino.some((entrada) => normalizar(entrada) === chavePolo);
                                if (!existe) {
                                    listaDestino.push(poloTexto);
                                }
                            });
                        });
                        return polosFiltrados;
                    })();
                    unidadesAssociadas.forEach((valor) => unidadesSet.add(valor));
                    turnosAssociados.forEach((valor) => turnosSet.add(valor));
                    const idOfertaPotencial = raiz.id || raiz.id_oferta || raiz.oferta_id || raiz.oferta;
                    if (idOfertaPotencial !== undefined && idOfertaPotencial !== null) {
                        if (unidadesAssociadas.length) {
                            unidadesAssociadas.forEach((unidadeTexto) => {
                                if (turnosAssociados.length) {
                                    turnosAssociados.forEach((turnoTexto) => registrarOfertaNoMapa(idOfertaPotencial, unidadeTexto, turnoTexto));
                                } else {
                                    registrarOfertaNoMapa(idOfertaPotencial, unidadeTexto, '');
                                }
                            });
                        } else if (turnosAssociados.length) {
                            turnosAssociados.forEach((turnoTexto) => registrarOfertaNoMapa(idOfertaPotencial, '', turnoTexto));
                        } else {
                            const unidadePrimaria = extrairPrimeiroTexto(raiz, ['unidade', 'campus', 'campi', 'local', 'locais', 'cidade', 'polo', 'polos']);
                            const turnoPrimario = extrairPrimeiroTexto(raiz, ['turno', 'horario', 'periodo', 'periodos']);
                            registrarOfertaNoMapa(idOfertaPotencial, unidadePrimaria, turnoPrimario);
                        }
                    }
                }

                const unidades = ordenarLista(Array.from(unidadesSet));
                const turnos = ordenarLista(Array.from(turnosSet));
                const mapaOfertaFinal = (() => {
                    const anterior = clonarMapaOferta(detalhesVarianteAtual.mapaOferta);
                    Object.keys(mapaOferta).forEach((chave) => {
                        anterior[chave] = mapaOferta[chave];
                    });
                    return anterior;
                })();
                const mapaPolosFinal = (() => {
                    const anterior = clonarMapaPolos(detalhesVarianteAtual.polosPorUnidade);
                    Object.keys(polosPorUnidadeAtual).forEach((cidadeChave) => {
                        const lista = Array.isArray(polosPorUnidadeAtual[cidadeChave])
                            ? normalizarColecao(polosPorUnidadeAtual[cidadeChave])
                            : [];
                        anterior[cidadeChave] = lista;
                    });
                    return anterior;
                })();
                const proximoEstado = {
                    unidades: unidades.length ? unidades : detalhesVarianteAtual.unidades,
                    turnos: turnos.length ? turnos : detalhesVarianteAtual.turnos,
                    codigos: listaCodigos.length ? [...listaCodigos] : detalhesVarianteAtual.codigos,
                    ofertas: ofertasSet.size ? Array.from(ofertasSet).sort((a, b) => a - b) : detalhesVarianteAtual.ofertas,
                    mapaOferta: mapaOfertaFinal,
                    polosPorUnidade: mapaPolosFinal
                };
                detalhesVarianteAtual = proximoEstado;
                preencherSelects(detalhesVarianteAtual.unidades, detalhesVarianteAtual.turnos);
            }).catch(() => {
                if (marcadorLocal !== ultimaConsultaModalidade) {
                    return;
                }
                const fallback = obterDetalhesVariante(cursoSelecionado, varianteSelecionada);
                detalhesVarianteAtual = {
                    unidades: Array.isArray(fallback.unidades) ? [...fallback.unidades] : [],
                    turnos: Array.isArray(fallback.turnos) ? [...fallback.turnos] : [],
                    codigos: Array.isArray(fallback.codigos) ? [...fallback.codigos] : [],
                    ofertas: Array.isArray(fallback.ofertas) ? [...fallback.ofertas] : [],
                    mapaOferta: clonarMapaOferta(fallback.mapaOferta),
                    polosPorUnidade: clonarMapaPolos(fallback.polosPorUnidade)
                };
                preencherSelects(detalhesVarianteAtual.unidades, detalhesVarianteAtual.turnos);
            });
        };

        const hubspotEndpoint = 'https://api.hsforms.com/submissions/v3/integration/submit/3462868/a1b9edd0-f4ef-41e9-9cfd-9ea30175d051';
        const sagaEndpoint = <?php echo wp_json_encode(esc_url(get_template_directory_uri() . '/sendAPI_interna.php')); ?>;
        let envioFormularioEmAndamento = false;

        const mapearVarianteParaTitulo = (slug) => {
            if (!slug) return '';
            if (slug === 'presencial') return 'Pós-Graduação Presencial';
            if (slug === 'ead') return 'Pós-Graduação Digital (EAD)';
            if (slug === 'hybrid') return 'Pós-Graduação Semipresencial';
            return slug;
        };

        const coletarCamposUtm = () => {
            const utmChaves = [
                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_content',
                'utm_term',
                'utm_id',
                'utm_source_platform',
                'utm_campaign_id',
                'utm_creative_format',
                'utm_marketing_tactic'
            ];
            const parametros = new URLSearchParams(window.location.search || '');
            const campos = utmChaves.map((chave) => ({
                name: chave,
                value: parametros.get(chave) || ''
            }));
            campos.push({
                name: 'origem261',
                value: parametros.get('origemmkt') || ''
            });
            return campos;
        };

        const validarTelefoneContato = (valor) => {
            const numeros = (valor || '').replace(/\D/g, '');
            return numeros.length === 10 || numeros.length === 11;
        };

        const formatarTelefoneContato = (valor) => {
            const numeros = (valor || '').replace(/\D/g, '').slice(0, 11);
            if (numeros.length <= 2) return numeros;
            if (numeros.length <= 6) return `(${numeros.slice(0, 2)}) ${numeros.slice(2)}`;
            if (numeros.length <= 10) return `(${numeros.slice(0, 2)}) ${numeros.slice(2, 6)}-${numeros.slice(6)}`;
            return `(${numeros.slice(0, 2)}) ${numeros.slice(2, 7)}-${numeros.slice(7)}`;
        };

        const aplicarMascaraTelefone = (campo) => {
            if (!campo) return;
            const atualizar = () => {
                const formatado = formatarTelefoneContato(campo.value || '');
                campo.value = formatado;
            };
            campo.addEventListener('input', atualizar);
            campo.addEventListener('blur', atualizar);
            atualizar();
        };

        const montarDescricaoCurso = ({ curso, modalidade, unidade, turno }) => {
            const partes = [];
            if (curso) partes.push(curso);
            if (modalidade) partes.push(`Modalidade: ${modalidade}`);
            const etiquetaUnidade = configuracaoLocalizacaoAtual.etiquetaUnidade || 'Unidade';
            const etiquetaTurno = configuracaoLocalizacaoAtual.etiquetaTurno || 'Turno';
            if (unidade) partes.push(`${etiquetaUnidade}: ${unidade}`);
            if (turno) partes.push(`${etiquetaTurno}: ${turno}`);
            return partes.join(' | ');
        };

        const enviarParaHubspot = async (payload) => {
            const resposta = await fetch(hubspotEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            let corpo = null;
            try {
                corpo = await resposta.json();
            } catch (erro) {
                corpo = {};
            }
            if (!resposta.ok) {
                const erro = new Error((corpo && corpo.message) || 'Falha ao enviar para o HubSpot');
                erro.detalhes = corpo;
                throw erro;
            }
            return corpo;
        };

        const enviarParaSaga = async (payload) => {
            const resposta = await fetch(sagaEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            let corpo = null;
            try {
                corpo = await resposta.json();
            } catch (erro) {
                corpo = {};
            }
            if (!resposta.ok) {
                const erro = new Error((corpo && corpo.error) || 'Falha ao enviar para o SAGA');
                erro.detalhes = corpo;
                throw erro;
            }
            return corpo;
        };

        const obterSelecoesAtuais = () => {
            const curso = cursoSelecionado ? cursoSelecionado.nome : (input ? input.value.trim() : '');
            const unidade = selectUnidade && !selectUnidade.disabled ? selectUnidade.value : '';
            const turno = selectTurno && !selectTurno.disabled ? selectTurno.value : '';
            const modalidadeTitulo = mapearVarianteParaTitulo(varianteSelecionada);
            const ofertaCodigo = Array.isArray(detalhesVarianteAtual.codigos) && detalhesVarianteAtual.codigos.length
                ? detalhesVarianteAtual.codigos[0]
                : '';

            const mapaOferta = detalhesVarianteAtual && typeof detalhesVarianteAtual.mapaOferta === 'object'
                ? detalhesVarianteAtual.mapaOferta
                : Object.create(null);

            const chaveCompleta = gerarChaveOferta(unidade, turno);
            const chavePorUnidade = gerarChaveOferta(unidade, '');
            const chavePorTurno = gerarChaveOferta('', turno);

            let ofertaIdValor = mapaOferta[chaveCompleta] || mapaOferta[chavePorUnidade] || mapaOferta[chavePorTurno];
            if (!ofertaIdValor) {
                const listaOfertas = Array.isArray(detalhesVarianteAtual.ofertas) ? detalhesVarianteAtual.ofertas : [];
                if (listaOfertas.length) {
                    ofertaIdValor = listaOfertas[0];
                } else {
                    const numero = Number.parseInt(ofertaCodigo, 10);
                    if (!Number.isNaN(numero)) {
                        ofertaIdValor = numero;
                    }
                }
            }

            const ofertaId = ofertaIdValor ? String(ofertaIdValor) : '';
            return {
                curso,
                unidade,
                turno,
                modalidadeSlug: varianteSelecionada,
                modalidadeTitulo,
                ofertaCodigo,
                ofertaId
            };
        };

        const exibirMensagemErro = (elemento, mensagem) => {
            if (!elemento) {
                alert(mensagem);
                return;
            }
            let alvo = elemento.querySelector('[data-feedback]');
            if (!alvo) {
                alvo = document.createElement('p');
                alvo.setAttribute('data-feedback', 'true');
                alvo.style.marginTop = '16px';
                alvo.style.fontSize = '13px';
                alvo.style.color = '#d93025';
                elemento.appendChild(alvo);
            }
            alvo.textContent = mensagem;
        };

        const limparMensagemErro = (elemento) => {
            if (!elemento) {
                return;
            }
            const alvo = elemento.querySelector('[data-feedback]');
            if (alvo) {
                alvo.textContent = '';
            }
        };

        if (blocoFormulario) {
            const campoTelefone = blocoFormulario.elements?.telefone || blocoFormulario.querySelector('input[name="telefone"]');
            aplicarMascaraTelefone(campoTelefone);
            blocoFormulario.addEventListener('submit', async (evento) => {
                evento.preventDefault();
                if (envioFormularioEmAndamento) {
                    return;
                }
                limparMensagemErro(blocoFormulario);
                if (typeof blocoFormulario.checkValidity === 'function' && !blocoFormulario.checkValidity()) {
                    blocoFormulario.reportValidity();
                    return;
                }

                const selecoes = obterSelecoesAtuais();
                const nome = blocoFormulario.elements.nome ? blocoFormulario.elements.nome.value.trim() : '';
                const sobrenome = blocoFormulario.elements.sobrenome ? blocoFormulario.elements.sobrenome.value.trim() : '';
                const email = blocoFormulario.elements.email ? blocoFormulario.elements.email.value.trim() : '';
                const telefoneBruto = blocoFormulario.elements.telefone ? blocoFormulario.elements.telefone.value.trim() : '';
                const telefoneNumerico = telefoneBruto.replace(/\D/g, '');
                const etiquetaUnidadeMensagem = (configuracaoLocalizacaoAtual.etiquetaUnidade || 'Unidade').toLowerCase();
                const etiquetaTurnoMensagem = (configuracaoLocalizacaoAtual.etiquetaTurno || 'Turno').toLowerCase();

                if (!selecoes.curso || !selecoes.modalidadeSlug || (!selecoes.unidade && selectUnidade && !selectUnidade.disabled) || (!selecoes.turno && selectTurno && !selectTurno.disabled)) {
                    exibirMensagemErro(blocoFormulario, `Selecione curso, modalidade, ${etiquetaUnidadeMensagem} e ${etiquetaTurnoMensagem} antes de avançar.`);
                    return;
                }

                if (!selecoes.ofertaId) {
                    exibirMensagemErro(blocoFormulario, `Não foi possível identificar a oferta selecionada. Revise as opções de ${etiquetaUnidadeMensagem} e ${etiquetaTurnoMensagem}.`);
                    return;
                }

                const ofertaIdNumero = Number.parseInt(selecoes.ofertaId, 10);
                if (Number.isNaN(ofertaIdNumero)) {
                    exibirMensagemErro(blocoFormulario, 'Identificador de oferta inválido. Tente selecionar novamente.');
                    return;
                }

                if (!validarTelefoneContato(telefoneBruto)) {
                    exibirMensagemErro(blocoFormulario, 'Informe um telefone válido com DDD.');
                    return;
                }

                const botaoSubmit = blocoFormulario.querySelector('button[type="submit"]');
                const textoOriginal = botaoSubmit ? botaoSubmit.textContent : '';

                envioFormularioEmAndamento = true;
                if (botaoSubmit) {
                    botaoSubmit.disabled = true;
                    botaoSubmit.textContent = 'Enviando...';
                }

                const etiquetaUnidade = configuracaoLocalizacaoAtual.etiquetaUnidade || 'Unidade';
                const etiquetaTurno = configuracaoLocalizacaoAtual.etiquetaTurno || 'Turno';
                const formaDescricao = ['Seleção Progressiva']
                    .concat(selecoes.modalidadeTitulo ? [`Modalidade: ${selecoes.modalidadeTitulo}`] : [])
                    .concat(selecoes.unidade ? [`${etiquetaUnidade}: ${selecoes.unidade}`] : [])
                    .concat(selecoes.turno ? [`${etiquetaTurno}: ${selecoes.turno}`] : [])
                    .join(' | ');

                const botaoIngressoAtivo = blocoFormaIngresso
                    ? blocoFormaIngresso.querySelector('.cursos-selecao__options--ingresso .cursos-selecao__option.is-active')
                    : null;
                const formaIngressoTitulo = botaoIngressoAtivo
                    ? (botaoIngressoAtivo.textContent || '').trim()
                    : '';
                const formaIngressoSlug = botaoIngressoAtivo
                    ? (botaoIngressoAtivo.getAttribute('data-variant') || '').trim()
                    : '';
                const formaIngressoSelecionada = formaIngressoTitulo || formaIngressoSlug;
                const hubspotCampos = [
                    { name: 'firstname', value: nome },
                    { name: 'lastname', value: sobrenome },
                    { name: 'email', value: email },
                    { name: 'mobilephone', value: telefoneNumerico },
                    { name: 'id_da_oferta', value: selecoes.ofertaId || selecoes.ofertaCodigo },
                    { name: 'nota_calculadora_fdi', value: '' },
                    { name: 'forma_de_ingresso', value: formaIngressoSelecionada || '' }
                ].concat(coletarCamposUtm()).filter((campo) => campo.value !== undefined && campo.value !== null);

                const hubspotPayload = {
                    fields: hubspotCampos,
                    context: {
                        pageUri: window.location.href,
                        pageName: document.title
                    }
                };

                const sagaPayload = {
                    oferta: ofertaIdNumero,
                    descricao_curso: montarDescricaoCurso({
                        curso: selecoes.curso,
                        // modalidade: selecoes.modalidadeTitulo,
                        // unidade: selecoes.unidade,
                        // turno: selecoes.turno
                    }),
                    nome: `${nome} ${sobrenome}`.trim(),
                    email,
                    telefone: telefoneNumerico,
                    forma_ingresso: formaDescricao
                };

                try {
                    await enviarParaHubspot(hubspotPayload);
                    const respostaSaga = await enviarParaSaga(sagaPayload);
                    const urlRedirecionamento = respostaSaga && respostaSaga.data && respostaSaga.data.redirect_url;
                    if (urlRedirecionamento) {
                        window.location.href = urlRedirecionamento;
                        return;
                    }
                    exibirMensagemErro(blocoFormulario, 'Dados enviados, mas não recebemos o link de redirecionamento. Tente novamente.');
                } catch (erro) {
                    console.error('Falha ao enviar o formulário de seleção de cursos:', erro);
                    exibirMensagemErro(blocoFormulario, 'Não foi possível concluir o envio. Tente novamente.');
                } finally {
                    envioFormularioEmAndamento = false;
                    if (botaoSubmit) {
                        botaoSubmit.disabled = false;
                        botaoSubmit.textContent = textoOriginal || 'AVANÇAR';
                    }
                }
            });
        }

        const atualizarModalidadesDisponiveis = (registroCurso) => {
            if (!blocoModalidade || !botoesModalidade.length) return;

            const variantes = Array.isArray(registroCurso?.variantes) ? registroCurso.variantes : [];
            const deveFiltrar = variantes.length > 0;
            const varianteAnterior = varianteSelecionada;
            let botaoParaSelecionar = null;

            botoesModalidade.forEach((botao) => {
                const botaoVariante = botao.getAttribute('data-variant');
                const deveMostrar = !deveFiltrar || variantes.includes(botaoVariante);
                botao.classList.toggle('is-hidden', !deveMostrar);
                if (deveMostrar && botaoVariante === varianteAnterior) {
                    botaoParaSelecionar = botao;
                }
                if (deveMostrar && !botaoParaSelecionar) {
                    botaoParaSelecionar = botao;
                }
            });

            if (botaoParaSelecionar && modalidadeSelecionadaPorClique) {
                definirModalidadeAtiva(botaoParaSelecionar, false);
            } else {
                botoesModalidade.forEach((botao) => botao.classList.remove('is-active'));
                varianteSelecionada = null;
                aplicarVarianteSelecionada();
            }
        };

        botoesModalidade.forEach((botao) => {
            botao.addEventListener('click', () => {
                if (botao.classList.contains('is-hidden')) return;
                definirModalidadeAtiva(botao, true);
            });
        });

        if (botoesFormaIngresso.length && blocoFormulario) {
            botoesFormaIngresso.forEach((botao) => {
                botao.addEventListener('click', () => {
                    if (botao.classList.contains('is-hidden')) return;
                    botoesFormaIngresso.forEach((item) => item.classList.toggle('is-active', item === botao));
                    exibirSecao(blocoFormulario, { focusPrimeiro: true });
                });
            });
        }

        if (selectUnidade) {
            selectUnidade.addEventListener('change', () => {
                if (configuracaoLocalizacaoAtual.usarUnidadesNoTurno) {
                    const valorTurnoAnterior = selectTurno ? selectTurno.value : '';
                    const turnosCompativeis = obterTurnosCompativeis(selectUnidade.value);
                    preencherSelect(selectTurno, turnosCompativeis, configuracaoLocalizacaoAtual.placeholderTurno);
                    if (selectTurno && valorTurnoAnterior && turnosCompativeis.includes(valorTurnoAnterior)) {
                        selectTurno.value = valorTurnoAnterior;
                        selectTurno.disabled = false;
                    }
                }
                atualizarExibicaoIngressoEFormulario();
            });
        }

        if (selectTurno) {
            selectTurno.addEventListener('change', atualizarExibicaoIngressoEFormulario);
        }

        const exibir = (lista) => {
            container.innerHTML = '';
            if (!lista.length) {
                container.classList.remove('is-visible');
                return;
            }

            const fragment = document.createDocumentFragment();
            lista.forEach((curso) => {
                const opcao = document.createElement('button');
                opcao.type = 'button';
                opcao.className = 'cursos-selecao__suggestion';
                opcao.textContent = curso;
                opcao.addEventListener('mousedown', (evento) => {
                    evento.preventDefault();
                    input.value = curso;
                    container.classList.remove('is-visible');
                    atualizarFluxoPorCurso();
                });
                fragment.appendChild(opcao);
            });

            container.appendChild(fragment);
            container.classList.add('is-visible');
        };

        const filtrar = (valor) => {
            const consulta = normalizar(valor || '');
            if (!consulta) {
                return [...nomesCursos];
            }
            return nomesCursos.filter((curso) => normalizar(curso).includes(consulta));
        };

        const atualizarFluxoPorCurso = () => {
            const anterior = cursoSelecionado;
            const registro = localizarCurso(input.value);
            const nomeAnterior = anterior ? normalizar(anterior.nome) : '';
            const nomeAtual = registro ? normalizar(registro.nome) : '';
            const mudouCurso = nomeAnterior !== nomeAtual;

            cursoSelecionado = registro;

            if (cursoSelecionado) {
                if (mudouCurso) {
                    modalidadeSelecionadaPorClique = false;
                    varianteSelecionada = null;
                    botoesModalidade.forEach((botao) => botao.classList.remove('is-active'));
                    ultimaConsultaModalidade += 1;
                }

                manterDependentesOcultos();
                exibirSecao(blocoModalidade);
                atualizarModalidadesDisponiveis(cursoSelecionado);
            } else {
                ocultarSecao(blocoModalidade);
                resetarModalidades();
                manterDependentesOcultos();
            }

            atualizarLinkCurso();
        };

        input.addEventListener('input', () => {
            exibir(filtrar(input.value));
            atualizarFluxoPorCurso();
        });

        input.addEventListener('focus', () => {
            exibir(nomesCursos);
            atualizarFluxoPorCurso();
        });

        input.addEventListener('blur', () => {
            setTimeout(() => container.classList.remove('is-visible'), 120);
        });

        manterDependentesOcultos();
        resetarModalidades();
        atualizarFluxoPorCurso();
    })();
</script>


<?php endif; ?>
