<?php
/*
 * [TURMAS_CONFIRMADAS_TEMP_START]
 *
 * Data: 2026-06-25
 *
 * Tarja "Turma confirmada em ..." (home .box-item e single #floatingOfferCard).
 * Match por NOME DO CURSO (titulo da pagina / card da API) + unidade.
 *
 * ROLLBACK: defina $turmas_confirmadas_temp_ativo = false;
 * ATUALIZAR LISTA: edite home_get_turmas_confirmadas_entradas() abaixo.
 *
 * Busque TURMAS_CONFIRMADAS_TEMP nos arquivos que incluem este helper.
 * [TURMAS_CONFIRMADAS_TEMP_END]
 */
if (!isset($turmas_confirmadas_temp_ativo)) {
	$turmas_confirmadas_temp_ativo = true;
}

if (!function_exists('home_get_turmas_confirmadas_entradas')) {
	function home_get_turmas_confirmadas_entradas() {
		static $cache = null;
		if ($cache !== null) {
			return $cache;
		}

		$cache = array(
			array('curso' => 'Análises Clínicas e Patológicas', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Controladoria, Auditoria e Compliance', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Engenharia Aeronáutica', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Engenharia de Segurança do Trabalho', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Engenharia de Sistemas Elétricos e Automação', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Engenharia de Sistemas Elétricos e Automação', 'unidade' => 'Campo Grande'),
			array('curso' => 'Engenharia Estrutural com Ênfase em Estruturas de Concreto', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Engenharia Estrutural com Ênfase em Estruturas de Concreto', 'unidade' => 'Campo Grande'),
			array('curso' => 'Engenharia Estrutural com Ênfase em Estruturas Metálicas', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Estética Clínica com Procedimentos Intradérmicos e Injetáveis', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Estética Clínica com Procedimentos Intradérmicos e Injetáveis', 'unidade' => 'Campo Grande'),
			array('curso' => 'Farmácia Clínica e Hospitalar', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Fisioterapia em Terapia Intensiva Adulto', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Fisioterapia Traumato-Ortopédica com Ênfase em Terapia Manual', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Gastronomia Contemporânea', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Gestão Comercial e Vendas', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Gestão de Obras Civis', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Gestão de Obras Civis', 'unidade' => 'Campo Grande'),
			array('curso' => 'Gestão de Processos e Métodos Ágeis', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Gestão e Projetos de Edificações em BIM', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Jornalismo e Marketing Esportivo', 'unidade' => 'Bonsucesso'),
			array('curso' => 'MBA em Inteligência Artificial e Transformação de Negócios', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Neurociência e Aprendizagem', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Nutrição Esportiva, Estética e Emagrecimento', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Pâtisserie e Boulangerie', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Perícias e Patologias das Edificações', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Planejamento Tributário', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Planejamento Tributário', 'unidade' => 'Campo Grande'),
			array('curso' => 'Psicologia Jurídica', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Psicologia Organizacional e do Trabalho', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Psicopedagogia Clínica-Institucional', 'unidade' => 'Bonsucesso'),
			array('curso' => 'Psicopedagogia Clínica-Institucional', 'unidade' => 'Campo Grande'),
			array('curso' => 'Terapia Cognitivo Comportamental', 'unidade' => 'Bonsucesso'),
		);

		return $cache;
	}
}

if (!function_exists('home_normalizar_nome_curso_turma_confirmada')) {
	function home_normalizar_nome_curso_turma_confirmada($texto) {
		$texto = (string) $texto;
		if (function_exists('remove_accents')) {
			$texto = remove_accents($texto);
		}

		$texto = str_replace(array("\xc2\xa0", '&nbsp;'), ' ', $texto);
		$texto = str_replace('&', ' e ', $texto);
		$texto = strtolower($texto);
		$texto = preg_replace('/[^a-z0-9\s]/iu', ' ', $texto);
		$texto = preg_replace('/\s+/', ' ', trim($texto));
		$texto = preg_replace('/^(mba em|pos graduacao em|pos em|especializacao em)\s+/iu', '', $texto);

		return trim($texto);
	}
}

if (!function_exists('home_cursos_coincidem_turma_confirmada')) {
	function home_cursos_coincidem_turma_confirmada($titulo_a, $titulo_b) {
		$a = home_normalizar_nome_curso_turma_confirmada($titulo_a);
		$b = home_normalizar_nome_curso_turma_confirmada($titulo_b);
		if ($a === '' || $b === '') {
			return false;
		}
		if ($a === $b) {
			return true;
		}

		$a_busca = ' ' . $a . ' ';
		$b_busca = ' ' . $b . ' ';
		return strpos($a_busca, $b_busca) !== false || strpos($b_busca, $a_busca) !== false;
	}
}

if (!function_exists('home_buscar_locais_confirmados_por_curso')) {
	function home_buscar_locais_confirmados_por_curso($titulo_curso) {
		$titulo_curso = trim((string) $titulo_curso);
		if ($titulo_curso === '') {
			return array();
		}

		$locais = array();
		foreach (home_get_turmas_confirmadas_entradas() as $entrada) {
			$nome_lista = trim((string) ($entrada['curso'] ?? ''));
			$unidade = trim((string) ($entrada['unidade'] ?? ''));
			if ($nome_lista === '' || $unidade === '') {
				continue;
			}
			if (!home_cursos_coincidem_turma_confirmada($titulo_curso, $nome_lista)) {
				continue;
			}
			if (!in_array($unidade, $locais, true)) {
				$locais[] = $unidade;
			}
		}

		return $locais;
	}
}

if (!function_exists('home_get_turmas_confirmadas_map')) {
	function home_get_turmas_confirmadas_map() {
		$mapa = array();
		foreach (home_get_turmas_confirmadas_entradas() as $entrada) {
			$nome = trim((string) ($entrada['curso'] ?? ''));
			$unidade = trim((string) ($entrada['unidade'] ?? ''));
			if ($nome === '' || $unidade === '') {
				continue;
			}
			if (!isset($mapa[$nome])) {
				$mapa[$nome] = array();
			}
			if (!in_array($unidade, $mapa[$nome], true)) {
				$mapa[$nome][] = $unidade;
			}
		}
		return $mapa;
	}
}

if (!function_exists('home_normalizar_unidade_turma_confirmada')) {
	function home_normalizar_unidade_turma_confirmada($valor) {
		$valor = (string) $valor;
		if (function_exists('remove_accents')) {
			$valor = remove_accents($valor);
		}
		$valor = strtolower(trim($valor));
		$valor = preg_replace('/\s+/', ' ', $valor);
		return $valor;
	}
}

if (!function_exists('home_campus_contem_unidade_turma')) {
	function home_campus_contem_unidade_turma($campus_bruto, $unidade_confirmada) {
		$campus_norm = home_normalizar_unidade_turma_confirmada($campus_bruto);
		$unidade_norm = home_normalizar_unidade_turma_confirmada($unidade_confirmada);
		if ($campus_norm === '' || $unidade_norm === '') {
			return false;
		}
		return strpos($campus_norm, $unidade_norm) !== false;
	}
}

if (!function_exists('home_resolver_texto_turma_confirmada_card')) {
	function home_resolver_texto_turma_confirmada_card($titulo_curso, $campus_bruto) {
		$locais_confirmados = home_buscar_locais_confirmados_por_curso($titulo_curso);
		if (empty($locais_confirmados)) {
			return '';
		}

		$campus_bruto = trim((string) $campus_bruto);
		$locais_exibir = array();

		if ($campus_bruto !== '') {
			foreach ($locais_confirmados as $local) {
				if (home_campus_contem_unidade_turma($campus_bruto, $local)) {
					$locais_exibir[] = $local;
				}
			}
		}

		if (empty($locais_exibir) && count($locais_confirmados) === 1) {
			$locais_exibir = $locais_confirmados;
		}

		if (empty($locais_exibir)) {
			return '';
		}

		if (count($locais_exibir) === 1) {
			return 'Turma confirmada em ' . $locais_exibir[0];
		}

		return 'Turma confirmada em ' . implode(' e ', $locais_exibir);
	}
}

if (!function_exists('home_montar_campus_turma_confirmada_investimentos')) {
	function home_montar_campus_turma_confirmada_investimentos($investimentos, $resumo = array()) {
		$unidades = array();
		$chaves_unidade = array('unidade', 'unidade_nome', 'unidadeNome', 'campus', 'local', 'polo');

		if (is_array($investimentos)) {
			foreach ($investimentos as $investimento) {
				if (!is_array($investimento)) {
					continue;
				}
				foreach ($chaves_unidade as $chave_unidade) {
					$unidade = trim((string) ($investimento[$chave_unidade] ?? ''));
					if ($unidade !== '') {
						$unidades[$unidade] = $unidade;
					}
				}
			}
		}

		if (empty($unidades) && is_array($resumo) && !empty($resumo['campus']) && is_array($resumo['campus'])) {
			foreach ($resumo['campus'] as $campus_item) {
				if (is_array($campus_item)) {
					foreach ($chaves_unidade as $chave_unidade) {
						$unidade = trim((string) ($campus_item[$chave_unidade] ?? ''));
						if ($unidade !== '') {
							$unidades[$unidade] = $unidade;
						}
					}
				} elseif (is_string($campus_item) && trim($campus_item) !== '') {
					$unidades[trim($campus_item)] = trim($campus_item);
				}
			}
		}

		return !empty($unidades) ? implode(' | ', array_values($unidades)) : '';
	}
}

if (!function_exists('home_carregar_turmas_confirmadas_temp')) {
	function home_carregar_turmas_confirmadas_temp() {
		static $carregado = false;
		if ($carregado) {
			return true;
		}

		$candidatos = array(
			get_stylesheet_directory() . '/turmas-confirmadas-temp.php',
			get_template_directory() . '/turmas-confirmadas-temp.php',
		);

		foreach ($candidatos as $caminho) {
			if (is_readable($caminho)) {
				require_once $caminho;
				$carregado = true;
				return true;
			}
		}

		if (function_exists('locate_template')) {
			$localizado = locate_template('turmas-confirmadas-temp.php', false, false);
			if ($localizado !== '' && is_readable($localizado)) {
				require_once $localizado;
				$carregado = true;
				return true;
			}
		}

		return false;
	}
}

if (!function_exists('home_extrair_titulo_curso_turma_confirmada')) {
	function home_extrair_titulo_curso_turma_confirmada($titulo_post, $data = array(), $post_id = 0) {
		$candidatos = array();

		if (is_string($titulo_post) && trim($titulo_post) !== '') {
			$candidatos[] = trim($titulo_post);
		}

		if ($post_id > 0) {
			$titulo_wp = trim((string) get_the_title($post_id));
			if ($titulo_wp !== '') {
				$candidatos[] = $titulo_wp;
			}
		}

		if (is_array($data)) {
			foreach (array(
				array('resumo', 'curso'),
				array('resumo', 'nome'),
				array('resumo', 'titulo'),
				array('curso', 'nome'),
				array('curso', 'titulo'),
				array('curso'),
				array('nome'),
				array('titulo'),
			) as $caminho) {
				$cursor = $data;
				$encontrado = true;
				foreach ($caminho as $parte) {
					if (!is_array($cursor) || !array_key_exists($parte, $cursor)) {
						$encontrado = false;
						break;
					}
					$cursor = $cursor[$parte];
				}
				if ($encontrado && is_string($cursor) && trim($cursor) !== '') {
					$candidatos[] = trim($cursor);
				}
			}
		}

		$candidatos = array_values(array_unique(array_filter($candidatos)));
		return !empty($candidatos) ? $candidatos[0] : '';
	}
}

if (!function_exists('home_coletar_titulos_turma_confirmada')) {
	function home_coletar_titulos_turma_confirmada($titulos_extra = array(), $data = array(), $post_id = 0) {
		$candidatos = array();

		if (is_array($titulos_extra)) {
			foreach ($titulos_extra as $titulo_extra) {
				if (is_string($titulo_extra) && trim($titulo_extra) !== '') {
					$candidatos[] = trim($titulo_extra);
				}
			}
		} elseif (is_string($titulos_extra) && trim($titulos_extra) !== '') {
			$candidatos[] = trim($titulos_extra);
		}

		$titulo_extraido = home_extrair_titulo_curso_turma_confirmada('', $data, $post_id);
		if ($titulo_extraido !== '') {
			$candidatos[] = $titulo_extraido;
		}

		$unicos = array();
		foreach ($candidatos as $candidato) {
			if (!in_array($candidato, $unicos, true)) {
				$unicos[] = $candidato;
			}
		}

		return $unicos;
	}
}

if (!function_exists('home_resolver_texto_turma_confirmada_contexto')) {
	function home_resolver_texto_turma_confirmada_contexto($titulos_extra = array(), $data = array(), $post_id = 0) {
		global $turmas_confirmadas_temp_ativo;

		if (!function_exists('home_resolver_texto_turma_confirmada_card')) {
			return '';
		}
		if (!isset($turmas_confirmadas_temp_ativo) || !$turmas_confirmadas_temp_ativo) {
			return '';
		}

		$campus_bruto = '';
		if (function_exists('home_montar_campus_turma_confirmada_investimentos')) {
			$campus_bruto = home_montar_campus_turma_confirmada_investimentos(
				is_array($data) ? ($data['investimentos'] ?? array()) : array(),
				is_array($data) ? ($data['resumo'] ?? array()) : array()
			);
		}

		$titulos = home_coletar_titulos_turma_confirmada($titulos_extra, $data, $post_id);
		foreach ($titulos as $titulo_curso) {
			$texto = home_resolver_texto_turma_confirmada_card($titulo_curso, $campus_bruto);
			if ($texto !== '') {
				return $texto;
			}
		}

		return '';
	}
}
