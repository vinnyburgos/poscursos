<section class="encontreCurso">

    <div class="center"> 
        <!-- BOXES -->

            <h3 class="titleConheca"></h3>
        
		<?php
			if (!function_exists('dadoshome_get_timezone_helper')) {
				function dadoshome_get_timezone_helper() {
					if (function_exists('wp_timezone')) {
						return wp_timezone();
					}
					$tz_string = get_option('timezone_string');
					if (!empty($tz_string)) {
						return new DateTimeZone($tz_string);
					}
					$offset = (float) get_option('gmt_offset', 0);
					$hours = (int) $offset;
					$minutes = (int) round(abs($offset - $hours) * 60);
					$sign = $offset >= 0 ? '+' : '-';
					$tz_fallback = sprintf('%s%02d:%02d', $sign, abs($hours), $minutes);
					return new DateTimeZone($tz_fallback);
				}
			}

			if (!function_exists('dadoshome_payload_has_courses')) {
				function dadoshome_extract_lista_cursos($payload) {
					if (!is_array($payload)) {
						return array();
					}

					if (isset($payload['posgraduacao']) && is_array($payload['posgraduacao']) && !empty($payload['posgraduacao'])) {
						return $payload['posgraduacao'];
					}

					return array();
				}

				function dadoshome_payload_has_courses($payload) {
					if (!is_array($payload)) {
						return false;
					}
					$lista = dadoshome_extract_lista_cursos($payload);
					if (!is_array($lista) || empty($lista)) {
						return false;
					}
					foreach ($lista as $item) {
						if (
							is_array($item) &&
							(!empty($item['curso']) || !empty($item['nome']) || !empty($item['mnemonico']) || !empty($item['mneumonico']))
						) {
							return true;
						}
					}
					return false;
				}
			}

			// Fulltime: carregar cards direto da API (mesma logica do front-page.php).
			// Antes este template lia/atualizava o cache em dadosHome.json via getAPICards.php.
			// Agora consome diretamente https://apimatricula.unisuam.edu.br com cache em transient (5 min).
			$api_cards_base_url = 'https://apimatricula.unisuam.edu.br';
			$api_cards_login = 'frog';
			$api_cards_senha = 'coSB5yJ7+t4+veJ6FE5S2ziL3EjrJ5IkEk+YiL9B/LA=';
			$cursos_data = array();
			$api_cards_token = '';
			$api_cards_cache_key = 'poscursos_home_api_cards_v1';
			$api_cards_cache_ttl = defined('MINUTE_IN_SECONDS') ? 5 * MINUTE_IN_SECONDS : 300;

			$cached_cards_data = get_transient($api_cards_cache_key);
			if (is_array($cached_cards_data) && !empty($cached_cards_data)) {
				$cursos_data = $cached_cards_data;
			} else {
				$token_response = wp_remote_post(
					trailingslashit($api_cards_base_url) . 'api/v1/token',
					array(
						'timeout' => 10,
						'headers' => array(
							'accept' => 'application/json',
							'Content-Type' => 'application/json',
						),
						'body' => wp_json_encode(array(
							'login' => $api_cards_login,
							'senha' => $api_cards_senha,
						)),
					)
				);

				if (!is_wp_error($token_response) && (int) wp_remote_retrieve_response_code($token_response) >= 200 && (int) wp_remote_retrieve_response_code($token_response) < 300) {
					$token_body = json_decode((string) wp_remote_retrieve_body($token_response), true);
					if (is_array($token_body) && !empty($token_body['token'])) {
						$api_cards_token = (string) $token_body['token'];
					}
				}

				if ($api_cards_token !== '') {
					$cards_response = wp_remote_get(
						trailingslashit($api_cards_base_url) . 'api/v1/cards/posgraduacao',
						array(
							'timeout' => 15,
							'headers' => array(
								'Authorization' => 'Bearer ' . $api_cards_token,
								'Content-Type' => 'application/json',
							),
						)
					);

					if (!is_wp_error($cards_response) && (int) wp_remote_retrieve_response_code($cards_response) >= 200 && (int) wp_remote_retrieve_response_code($cards_response) < 300) {
						$cards_body = json_decode((string) wp_remote_retrieve_body($cards_response), true);
						if (is_array($cards_body)) {
							$cursos_data = (isset($cards_body['data']) && is_array($cards_body['data'])) ? $cards_body['data'] : $cards_body;
						}
					}
				}

				if (!empty($cursos_data) && is_array($cursos_data)) {
					set_transient($api_cards_cache_key, $cursos_data, $api_cards_cache_ttl);
				}
			}

			if (empty($cursos_data) || !is_array($cursos_data)) {
				$cursos_data = array();
			}

			$cursos = dadoshome_extract_lista_cursos($cursos_data);
		?>
		<?php
			$find_cursos_wp_template = function_exists('locate_template') ? locate_template('findCursosWP.php', false, false) : '';
			if (is_string($find_cursos_wp_template) && $find_cursos_wp_template !== '' && file_exists($find_cursos_wp_template)) {
				include $find_cursos_wp_template;
			}
			$wp_cursos_map = [];
			$wp_cursos_map_by_slug = [];
			$normalizar_titulo_exato_home = function($valor) {
				$valor = (string) $valor;
				$valor = function_exists('remove_accents') ? remove_accents($valor) : $valor;
				$valor = strtolower($valor);
				$valor = preg_replace('/\s+/', ' ', trim($valor));
				return $valor;
			};
			$buscar_posts_por_titulo_exato_home = function($titulo) use ($normalizar_titulo_exato_home) {
				$titulo = trim((string) $titulo);
				if ($titulo === '') {
					return array();
				}

				$ids = get_posts(array(
					'post_type' => 'any',
					'post_status' => array('publish', 'private', 'draft', 'pending', 'future'),
					'posts_per_page' => 200,
					'fields' => 'ids',
					's' => $titulo,
					'suppress_filters' => true,
				));

				if (!is_array($ids) || empty($ids)) {
					return array();
				}

				$alvo = $normalizar_titulo_exato_home($titulo);
				$filtrados = array();
				foreach ($ids as $id_post) {
					$id_post = (int) $id_post;
					if ($id_post <= 0) {
						continue;
					}
					$titulo_post = get_the_title($id_post);
					if ($normalizar_titulo_exato_home($titulo_post) === $alvo) {
						$filtrados[] = $id_post;
					}
				}

				return array_values(array_unique(array_map('intval', $filtrados)));
			};
			if (!empty($cursos) && is_array($cursos)) {
				foreach ($cursos as $item) {
					if (!is_array($item)) {
						continue;
					}

					$key = $item['mneumonico'] ?? ($item['mnemonico'] ?? null);
					$perma = $item['permalink'] ?? null;
					$slug = $item['slug'] ?? null;
					$item_post_type = $item['post_type'] ?? '';

					if ($key && $perma) {
						if (!isset($wp_cursos_map[$key]) || $item_post_type === 'graduacao') {
							$wp_cursos_map[$key] = $perma;
						}
					}
					if ($slug && $perma) {
						if (!isset($wp_cursos_map_by_slug[$slug]) || $item_post_type === 'graduacao') {
							$wp_cursos_map_by_slug[$slug] = $perma;
						}
					}
				}
			}
		?>
		<div class="box-container">
		<?php if (!empty($cursos) && is_array($cursos)): ?>
			<div class="cursos-carousel" data-cursos-carousel>
				<button class="cursos-carousel-nav prev" type="button" aria-label="Card anterior">&#10094;</button>
				<div class="cursos-carousel-viewport">
					<div class="cursos-carousel-track">
			<?php foreach ($cursos as $curso): ?>
							<?php
								// Filtro funcional: exibe apenas a modalidade solicitada pela URL (se houver)
								$modalidade_original = $curso['modalidade'] ?? 'Presencial';
								$modalidade_normalizada = normalizar_modalidade_home($modalidade_original);
								if ($filtrar_modalidade_unica && $modalidade_normalizada !== $filtrar_modalidade_unica) continue;
								$modalidade = rotulo_modalidade_home($modalidade_original, $modalidade_normalizada);
							?>
				<?php
							// Força "EAD" para "Digital (EaD)" e controla classes
							$modalidade_original = $curso['modalidade'] ?? 'Presencial';
							$modalidade = rotulo_modalidade_home($modalidade_original, $modalidade_normalizada);
							$is_digital = ($modalidade_normalizada === 'digital');
							$is_digital_ao_vivo = ($modalidade_normalizada === 'digitalaovivo');
					$colorClass = $is_digital_ao_vivo ? 'colorPurple' : ($is_digital ? 'colorRed' : 'colorGreen');
					$bgClass = $is_digital_ao_vivo ? 'bgPurple' : ($is_digital ? 'bgRed' : 'bgGreen');
					$innerClass = $is_digital_ao_vivo ? 'innerPurple' : ($is_digital ? 'innerRed' : 'innerGreen');

							$mneumonico_curso = $curso['mnemonico'] ?? ($curso['mneumonico'] ?? '');
							$slug_curso = sanitize_title($curso['curso'] ?? $curso['nome'] ?? '');
							$permalink_curso = '#';
							$post_id_curso_card = 0;

							if (isset($wp_cursos_map[$mneumonico_curso])) {
								$permalink_curso = $wp_cursos_map[$mneumonico_curso];
							} elseif ($slug_curso && isset($wp_cursos_map_by_slug[$slug_curso])) {
								$permalink_curso = $wp_cursos_map_by_slug[$slug_curso];
							}

							if (function_exists('garantir_pagina_curso_por_card_home')) {
								$post_id_gerado = garantir_pagina_curso_por_card_home($curso, $modalidade_normalizada);
								if ($post_id_gerado) {
									$post_id_curso_card = (int) $post_id_gerado;
									$permalink_curso = get_permalink($post_id_gerado);
									if ($mneumonico_curso) {
										$wp_cursos_map[$mneumonico_curso] = $permalink_curso;
									}
									if ($slug_curso) {
										$wp_cursos_map_by_slug[$slug_curso] = $permalink_curso;
									}
								}
							}

							if (!$post_id_curso_card && is_string($permalink_curso) && $permalink_curso !== '#') {
								$post_id_curso_card = (int) url_to_postid($permalink_curso);
							}

							$categorias_modalidade_bloqueadas = array('presencial', 'digital (ead)', 'sem categoria', '100digital', 'semipresencial', 'digital ao vivo');
							$categorias_card_map = array();
							$debug_source_posts = array();
							$debug_source_terms = array();

							$adicionar_categorias_area_do_post = function($post_id_local) use (&$categorias_card_map, $categorias_modalidade_bloqueadas, &$debug_source_posts, &$debug_source_terms) {
								$post_id_local = (int) $post_id_local;
								if ($post_id_local <= 0) {
									return;
								}

								if (!in_array((string) $post_id_local, $debug_source_posts, true)) {
									$debug_source_posts[] = (string) $post_id_local;
								}

								$categorias_local = get_the_terms($post_id_local, 'category');
								if (!is_wp_error($categorias_local) && !empty($categorias_local)) {
									foreach ($categorias_local as $categoria_local) {
										$nome_categoria = (string) $categoria_local->name;
										$debug_source_terms[] = 'category:' . $nome_categoria;
										$nome_cat_val = strtolower(trim($nome_categoria));
										if (in_array($nome_cat_val, $categorias_modalidade_bloqueadas, true)) {
											continue;
										}
										$categorias_card_map[(string) $categoria_local->term_id] = $nome_categoria;
									}
								}

								// Fallback: quando a pagina usa outra taxonomia de area, mapeia por nome/slug para termos da taxonomia category.
								if (empty($categorias_card_map)) {
									$post_type_local = get_post_type($post_id_local);
									$taxonomias = get_object_taxonomies($post_type_local, 'objects');
									if (is_array($taxonomias)) {
										foreach ($taxonomias as $taxonomia_obj) {
											if (!($taxonomia_obj instanceof WP_Taxonomy)) {
												continue;
											}
											$tax_nome = (string) $taxonomia_obj->name;
											if ($tax_nome === 'category' || $tax_nome === 'post_tag' || $tax_nome === 'post_format') {
												continue;
											}

											$termos_outros = get_the_terms($post_id_local, $tax_nome);
											if (is_wp_error($termos_outros) || empty($termos_outros)) {
												continue;
											}

											foreach ($termos_outros as $termo_outro) {
												$nome_outro = trim((string) $termo_outro->name);
												$slug_outro = trim((string) $termo_outro->slug);
												if ($nome_outro === '' && $slug_outro === '') {
													continue;
												}

												$debug_source_terms[] = $tax_nome . ':' . ($nome_outro !== '' ? $nome_outro : $slug_outro);

												$termo_categoria = null;
												if ($slug_outro !== '') {
													$termo_categoria = get_term_by('slug', $slug_outro, 'category');
												}
												if (!$termo_categoria instanceof WP_Term && $nome_outro !== '') {
													$termo_categoria = get_term_by('name', $nome_outro, 'category');
												}
												if ($termo_categoria instanceof WP_Term) {
													$nome_categoria_mapeada = (string) $termo_categoria->name;
													$nome_cat_val = strtolower(trim($nome_categoria_mapeada));
													if (in_array($nome_cat_val, $categorias_modalidade_bloqueadas, true)) {
														continue;
													}
													$categorias_card_map[(string) $termo_categoria->term_id] = $nome_categoria_mapeada;
												}
											}
										}
									}
								}

								// Fallback adicional: tenta campos/meta de area/categoria/escola no post fonte.
								if (empty($categorias_card_map)) {
									$metas = get_post_meta($post_id_local);
									if (is_array($metas) && !empty($metas)) {
										foreach ($metas as $meta_chave => $meta_valores) {
											$chave_norm = strtolower((string) $meta_chave);
											if (!preg_match('/(area|categoria|category|escola|segmento|eixo|interesse)/', $chave_norm)) {
												continue;
											}

											if (!is_array($meta_valores)) {
												$meta_valores = array($meta_valores);
											}

											foreach ($meta_valores as $meta_valor) {
												if (is_array($meta_valor) || is_object($meta_valor)) {
													continue;
												}
												$partes = preg_split('/[,|;\/]+/', (string) $meta_valor);
												foreach ($partes as $parte_meta) {
													$parte_meta = trim((string) $parte_meta);
													if ($parte_meta === '') {
														continue;
													}

													$debug_source_terms[] = 'meta:' . $meta_chave . '=' . $parte_meta;
													$termo_categoria = get_term_by('name', $parte_meta, 'category');
													if (!$termo_categoria instanceof WP_Term) {
														$termo_categoria = get_term_by('slug', sanitize_title($parte_meta), 'category');
													}

													if ($termo_categoria instanceof WP_Term) {
														$nome_categoria_mapeada = (string) $termo_categoria->name;
														$nome_cat_val = strtolower(trim($nome_categoria_mapeada));
														if (in_array($nome_cat_val, $categorias_modalidade_bloqueadas, true)) {
															continue;
														}
														$categorias_card_map[(string) $termo_categoria->term_id] = $nome_categoria_mapeada;
													}
												}
											}
										}
									}
								}
							};

							// Regra global:
							// 1) categorias da pagina vinculada ao proprio card (href do Saiba Mais)
							// 2) agrega categorias dos posts-irmaos do mesmo curso (mesmo mneumonico)
							$post_ids_fontes = array();
							$post_id_fonte_principal = (int) $post_id_curso_card;

							if ($post_id_fonte_principal <= 0 && $mneumonico_curso !== '' && function_exists('buscar_curso_graduacao_por_mneumonico_e_modalidade')) {
								$post_por_mneumonico = buscar_curso_graduacao_por_mneumonico_e_modalidade($mneumonico_curso, $modalidade_normalizada);
								if ($post_por_mneumonico instanceof WP_Post) {
									$post_id_fonte_principal = (int) $post_por_mneumonico->ID;
								}
							}

							if ($post_id_fonte_principal > 0) {
								$post_ids_fontes[] = $post_id_fonte_principal;
								if ($permalink_curso === '#' || $permalink_curso === '') {
									$permalink_curso = get_permalink($post_id_fonte_principal);
								}
							}

							if ($mneumonico_curso !== '') {
								$query_irmaos = new WP_Query(array(
									'post_type' => 'any',
									'post_status' => array('publish', 'private', 'draft', 'pending', 'future'),
									'posts_per_page' => 80,
									'fields' => 'ids',
									'suppress_filters' => true,
									'meta_query' => array(
										'relation' => 'OR',
										array(
											'key' => 'mneumonico',
											'value' => $mneumonico_curso,
											'compare' => '=',
										),
										array(
											'key' => 'mnemonico',
											'value' => $mneumonico_curso,
											'compare' => '=',
										),
									),
								));
								if (!empty($query_irmaos->posts)) {
									foreach ($query_irmaos->posts as $id_irmao) {
										$post_ids_fontes[] = (int) $id_irmao;
									}
								}
								wp_reset_postdata();
							}

							$post_ids_fontes = array_values(array_unique(array_filter(array_map('intval', $post_ids_fontes))));
							foreach ($post_ids_fontes as $post_id_fonte) {
								$adicionar_categorias_area_do_post($post_id_fonte);
							}

							// Fallback global seguro: usa somente posts com titulo exatamente igual ao do card.
							if (empty($categorias_card_map)) {
								$titulo_curso_card = trim((string) ($curso['curso'] ?? $curso['nome'] ?? ''));
								$ids_titulo_exato = $buscar_posts_por_titulo_exato_home($titulo_curso_card);
								if (!empty($ids_titulo_exato)) {
									$debug_source_terms[] = 'title-exato:' . $titulo_curso_card;
									foreach ($ids_titulo_exato as $id_titulo_exato) {
										$adicionar_categorias_area_do_post($id_titulo_exato);
									}
								}
							}

							// Remove categorias de modalidade do filtro de area (presencial/digital/aovivo).
							$categorias_card_ids = array();
							$categorias_card_nomes = array();
							foreach ($categorias_card_map as $cat_id_val => $nome_cat_original) {
								$nome_cat_val = strtolower(trim((string) $nome_cat_original));
								if (in_array($nome_cat_val, $categorias_modalidade_bloqueadas, true)) {
									continue;
								}
								$cat_id_val = (string) $cat_id_val;
								if (!in_array($cat_id_val, $categorias_card_ids, true)) {
									$categorias_card_ids[] = $cat_id_val;
								}
								if ($nome_cat_val !== '' && !in_array($nome_cat_original, $categorias_card_nomes, true)) {
									$categorias_card_nomes[] = $nome_cat_original;
								}
							}

							$categorias_card_nomes_norm = array();
							foreach ($categorias_card_nomes as $nome_cat_card) {
								$nome_norm_card = function_exists('remove_accents') ? remove_accents((string) $nome_cat_card) : (string) $nome_cat_card;
								$nome_norm_card = strtolower(trim($nome_norm_card));
								if ($nome_norm_card !== '' && !in_array($nome_norm_card, $categorias_card_nomes_norm, true)) {
									$categorias_card_nomes_norm[] = $nome_norm_card;
								}
							}

							$categorias_card_nomes_texto = implode(', ', $categorias_card_nomes);
							$categorias_card_ids_texto = implode(',', $categorias_card_ids);
							$categorias_card_nomes_norm_texto = implode('|', $categorias_card_nomes_norm);
							$debug_source_posts_texto = implode(',', array_values(array_unique($debug_source_posts)));
							$debug_source_terms_texto = implode(' | ', array_values(array_unique($debug_source_terms)));
							$permalink_curso = aplicar_sufixo_modalidade_home_url($permalink_curso, $modalidade_normalizada);
				?>
				<div class="box-item cursos-carousel-item" data-mneumonico="<?php echo esc_attr($mneumonico_curso); ?>" data-modalidade="<?php echo esc_attr($modalidade_normalizada); ?>" data-category-ids="<?php echo esc_attr($categorias_card_ids_texto); ?>" data-category-names="<?php echo esc_attr($categorias_card_nomes_norm_texto); ?>" data-source-post-ids="<?php echo esc_attr($debug_source_posts_texto); ?>" data-source-terms="<?php echo esc_attr($debug_source_terms_texto); ?>">
					<span class="selecionaCategoria" style="opacity:0;position:absolute;z-index:-999">
						<?php echo esc_html($categorias_card_nomes_texto); ?>
					</span>
					<div class="boxColor <?php echo esc_attr($bgClass); ?>"></div>
					<p class="seloType <?php echo esc_attr($colorClass); ?>"><?php echo esc_html($curso['tipo'] ?? 'PÓS-GRADUAÇÃO'); ?></p>
					<div class="innerMod <?php echo esc_attr($innerClass); ?>">
						<span class="categoria"><?php echo esc_html($modalidade); ?></span>
					</div>
					<h3 class="titleBox" style="font-size: 15px;line-height: 16px;">
						<a class="nameCurso" href="<?php echo esc_url($permalink_curso); ?>" style="text-decoration: none; color: #333;">
							<?php echo esc_html($curso['curso'] ?? $curso['nome'] ?? ''); ?>
						</a>

					</h3>

					<div class="apiGets">
						<p class="partir <?php echo esc_attr($colorClass); ?>">A partir de:</p>
						<span class="valorSDesconto">
							<?php
							// Presencial e Digital ao Vivo: regra igual ao front-page.php (preco / 0.4, prefixo 18x).
							// Digital (EaD): mantem a regra atual do cursos-slide (preco * 2, sem prefixo).
							if (isset($curso['precos']) && is_numeric($curso['precos'])) {
								$preco = (float) $curso['precos'];
								if (in_array($modalidade_normalizada, array('presencial', 'digitalaovivo'), true)) {
									$valorIntegral = number_format($preco / 0.4, 2, ',', '.');
									echo "18x de R$ {$valorIntegral}";
								} else {
									$valorIntegral = number_format($preco * 2, 2, ',', '.');
									echo "R$ {$valorIntegral}";
								}
							}
							?>
						</span>
						<span class="dezoitoxSDesconto" style="text-decoration:none">&nbsp;por:</span>
						<div class="pula"></div>
						<!-- <span class="dozexCDesconto apenasPresencial <?php echo esc_attr($colorClass); ?>">R$</span> -->
						<span class="valorCDesconto" style="display: inline;" data-valor-base="<?php echo esc_attr(isset($curso['precos']) && is_numeric($curso['precos']) ? number_format((float) $curso['precos'], 2, '.', '') : ''); ?>">
							<?php
							// Presencial/Digital ao Vivo: mesma regra do front-page.php (chave = preco direto, prefixo "A partir de").
							// Digital (EaD): mantem a regra atual — valor cheio sem prefixo.
							$preco_base_card = (isset($curso['precos']) && is_numeric($curso['precos'])) ? (float) $curso['precos'] : 0;
							if ($preco_base_card > 0) {
								if (in_array($modalidade_normalizada, array('presencial', 'digitalaovivo'), true)) {
									$parcela_base_card = (float) $preco_base_card;
									$chave_parcela_base_card = number_format($parcela_base_card, 2, '.', '');
									$mapa_parcela_presencial_18x = array(
										'448.50' => 299.00,
										'598.50' => 399.00,
										'898.50' => 599.00,
									);
									$mapa_parcela_digitalaovivo_18x = array(
										'298.50' => 199.00,
										'448.50' => 299.00,
										'598.50' => 399.00,
									);

									if ($modalidade_normalizada === 'presencial' && isset($mapa_parcela_presencial_18x[$chave_parcela_base_card])) {
										echo '18x de R$ ' . number_format((float) $mapa_parcela_presencial_18x[$chave_parcela_base_card], 2, ',', '.');
									} elseif ($modalidade_normalizada === 'digitalaovivo' && isset($mapa_parcela_digitalaovivo_18x[$chave_parcela_base_card])) {
										echo '18x de R$ ' . number_format((float) $mapa_parcela_digitalaovivo_18x[$chave_parcela_base_card], 2, ',', '.');
									} else {
										echo '18x de R$ ' . number_format($parcela_base_card, 2, ',', '.');
									}
								} else {
									echo 'R$ ' . number_format($preco_base_card, 2, ',', '.');
								}
							}
							?>
						</span>
						<p class="ateFinal"><?php echo in_array($modalidade_normalizada, array('presencial', 'digitalaovivo'), true) ? 'no cartão de crédito' : 'até o final do curso'; ?></p>
						<div class="pula"></div>
						<span class="apenasEAD"></span>
						<div class="wrapThings">
							<h6 class="carga <?php echo esc_attr($colorClass); ?>">Duração:</h6>
							<p class="conteudoBox">
								<span class="cargaHoraria"><?php echo esc_html($curso['semestres'] ?? ''); ?></span>
								<span> meses</span>
							</p>
						</div>
						<?php
						$unidade = $curso['campus'] ?? '';
						$mostrarUnidade = true;

						if (stripos($unidade, 'Polo') !== false) {
							$mostrarUnidade = false;
						}

						if ($mostrarUnidade) {
							if (stripos($unidade, 'Campus') !== false) {
								$unidade = trim(str_ireplace('Campus', '', $unidade));
							}
							$unidade = preg_replace('/\s*\(.*?\)\s*/', '', $unidade);

							$unidade_normalizada = function_exists('remove_accents') ? remove_accents((string) $unidade) : (string) $unidade;
							$unidade_normalizada = strtolower(trim($unidade_normalizada));
							if ($unidade_normalizada === 'webconferencia') {
								$unidade = 'Digital ao Vivo';
							}
							?>
							<div class="wrapThings apenasPresencial apenasPresencialUnidade" style="">
								<h6 class="unidades <?php echo esc_attr($colorClass); ?>">Unidades:</h6>
								<div class="unidadeContent"><?php echo esc_html($unidade); ?></div>
							</div>
							<?php
						}
						?>
					</div>
					<div class="innerInscreva">
						<a href="<?php echo esc_url($permalink_curso); ?>" class="cursoPage">
							<div id="btnInscreva" class="btnInscreva btn">SAIBA MAIS</div>
						</a>
					</div>
				</div>
			<?php endforeach; ?>
					</div>
				</div>
				<button class="cursos-carousel-nav next" type="button" aria-label="Próximo card">&#10095;</button>
				<div class="cursos-carousel-dots" aria-label="Paginação de cards"></div>
			</div>
		<?php else: ?>
			<p>Nenhum curso encontrado.</p>
		<?php endif; ?>

		<!-- BOXES -->
		</div>










		<!-- CONFIGURACAO CENTRALIZADA DO CARROSSEL (CSS + JS) -->
		<?php if (!defined('DADOSHOME_CAROUSEL_ASSETS_INCLUDED')): ?>
			<?php define('DADOSHOME_CAROUSEL_ASSETS_INCLUDED', true); ?>
			<style>
				.cursos-carousel {
					position: relative;
					padding: 0 34px 28px;
				}

				.cursos-carousel-viewport {
					overflow: hidden;
					touch-action: pan-y;
					cursor: grab;
					margin: 0 auto;
				}

				.cursos-carousel-viewport.is-dragging {
					cursor: grabbing;
				}

				.cursos-carousel-track {
					display: flex;
					transition: transform 320ms ease;
					will-change: transform;
				}

				.cursos-carousel-track .box-item.cursos-carousel-item {
					box-sizing: border-box;
					flex: 0 0 auto;
					width: auto;
					max-width: none;
				}

				.cursos-carousel-nav {
					position: absolute;
					top: 50%;
					transform: translateY(-50%);
					z-index: 2;
					width: 28px;
					height: 28px;
					min-width: 28px;
					min-height: 28px;
					padding: 0;
                    padding-bottom: 4px;
					border: 0;
					border-radius: 50%;
					background: #1d4f91;
					background-image: none;
					color: #fff;
					display: flex;
					align-items: center;
					justify-content: center;
					line-height: 1;
					font-size: 15px;
					font-weight: 700;
					box-shadow: none;
					appearance: none;
					-webkit-appearance: none;
					overflow: hidden;
					cursor: pointer;
				}

				.cursos-carousel-nav::before,
				.cursos-carousel-nav::after {
					content: none;
					display: none;
				}

				.cursos-carousel-nav:disabled {
					opacity: 0.35;
					cursor: default;
				}

				.cursos-carousel-nav.prev {
					left: 2px;
				}

				.cursos-carousel-nav.next {
					right: 2px;
				}

				.cursos-carousel-dots {
					display: flex;
					justify-content: center;
					align-items: center;
					gap: 6px;
					margin-top: 12px;
				}

				.cursos-carousel-dot {
					width: 8px;
					height: 8px;
					border: 0;
					padding: 0;
					border-radius: 999px;
					background: #c6c6c6;
					cursor: pointer;
				}

				.cursos-carousel-dot.active {
					background: #1d4f91;
				}
			</style>
		<?php endif; ?>
		<?php if (!defined('DADOSHOME_CAROUSEL_INIT_INCLUDED')): ?>
			<?php define('DADOSHOME_CAROUSEL_INIT_INCLUDED', true); ?>
			<script>
				/*
				 * CARROSSEL EM JAVASCRIPT PURO (SEM SWIPER)
				 *
				 * Regras implementadas:
				 * 1) Cada ".box-item" funciona como um slide do carrossel.
				 * 2) Cada card mantem a largura original (fixada no primeiro render).
				 * 3) Quantidade por viewport fixa por breakpoint:
				 *    - Mobile/Tablet: 1 card
				 *    - Desktop: 3 cards
				 * 4) Navegacao por botoes, bolinhas, teclado e arraste touch/mouse.
				 * 5) Recalculo automatico ao redimensionar e apos filtros (change/load).
				 */
				(function() {
					function getPerView() {
						return window.innerWidth >= 1024 ? 3 : 1;
					}

					function isVisible(el) {
						return !!(el && el.offsetParent !== null && window.getComputedStyle(el).display !== 'none');
					}

					function buildCarousel(carousel) {
						var viewport = carousel.querySelector('.cursos-carousel-viewport');
						var track = carousel.querySelector('.cursos-carousel-track');
						var prevBtn = carousel.querySelector('.cursos-carousel-nav.prev');
						var nextBtn = carousel.querySelector('.cursos-carousel-nav.next');
						var dotsWrap = carousel.querySelector('.cursos-carousel-dots');
						if (!viewport || !track || !prevBtn || !nextBtn || !dotsWrap) {
							return;
						}

						var currentIndex = 0;
						var visibleCards = [];
						var perView = 1;
						var stepPx = 0;
						var dragging = false;
						var dragStartX = 0;
						var dragDeltaX = 0;
						var suppressClick = false;

						function maxStart() {
							return Math.max(0, visibleCards.length - perView);
						}

						function applyTransform(extraPx) {
							var basePx = stepPx * currentIndex;
							var extra = extraPx || 0;
							track.style.transform = 'translate3d(' + (-basePx + extra) + 'px, 0, 0)';
						}

						function updateControls() {
							var canMove = visibleCards.length > perView;
							prevBtn.style.display = canMove ? '' : 'none';
							nextBtn.style.display = canMove ? '' : 'none';
							dotsWrap.style.display = canMove ? '' : 'none';
							prevBtn.disabled = (currentIndex <= 0);
							nextBtn.disabled = (currentIndex >= maxStart());
						}

						function updateDots() {
							dotsWrap.innerHTML = '';
							var totalPages = maxStart() + 1;
							if (totalPages <= 1) {
								return;
							}
							for (var i = 0; i < totalPages; i++) {
								var dot = document.createElement('button');
								dot.type = 'button';
								dot.className = 'cursos-carousel-dot' + (i === currentIndex ? ' active' : '');
								dot.setAttribute('aria-label', 'Ir para grupo ' + (i + 1));
								dot.addEventListener('click', (function(index) {
									return function() {
										currentIndex = index;
										render();
									};
								})(i));
								dotsWrap.appendChild(dot);
							}
						}

						function render() {
							var allCards = Array.prototype.slice.call(track.querySelectorAll('.cursos-carousel-item'));
							visibleCards = allCards.filter(isVisible);
							if (!visibleCards.length) {
								track.style.transform = 'translate3d(0, 0, 0)';
								prevBtn.style.display = 'none';
								nextBtn.style.display = 'none';
								dotsWrap.style.display = 'none';
								dotsWrap.innerHTML = '';
								return;
							}

							if (!carousel.__baseCardWidth || carousel.__baseCardWidth <= 0) {
								carousel.__baseCardWidth = Math.round(visibleCards[0].getBoundingClientRect().width);
							}

							var fixedCardWidth = Math.max(1, carousel.__baseCardWidth);
							perView = getPerView();

							allCards.forEach(function(card) {
								card.style.flex = '0 0 ' + fixedCardWidth + 'px';
								card.style.width = fixedCardWidth + 'px';
								card.style.maxWidth = fixedCardWidth + 'px';
							});

							if (visibleCards.length > 1) {
								stepPx = Math.round(visibleCards[1].offsetLeft - visibleCards[0].offsetLeft);
							}
							if (!stepPx || stepPx <= 0) {
								stepPx = fixedCardWidth;
							}

							var carouselStyle = window.getComputedStyle(carousel);
							var padLeft = parseFloat(carouselStyle.paddingLeft) || 0;
							var padRight = parseFloat(carouselStyle.paddingRight) || 0;
							var availableWidth = Math.max(1, carousel.clientWidth - padLeft - padRight);
							var targetViewportWidth = Math.min(availableWidth, stepPx * perView);
							viewport.style.width = targetViewportWidth + 'px';

							currentIndex = Math.max(0, Math.min(currentIndex, maxStart()));
							track.style.transition = 'transform 320ms ease';
							applyTransform(0);

							updateControls();
							updateDots();
						}

						function onPointerDown(event) {
							if (event.pointerType === 'mouse' && event.button !== 0) {
								return;
							}

							var linkTarget = event.target && event.target.closest ? event.target.closest('a') : null;
							if (linkTarget) {
								suppressClick = false;
								return;
							}
							if (visibleCards.length <= perView) {
								return;
							}

							dragging = true;
							dragStartX = event.clientX;
							dragDeltaX = 0;
							suppressClick = false;
							track.style.transition = 'none';
							viewport.classList.add('is-dragging');

							if (typeof viewport.setPointerCapture === 'function' && typeof event.pointerId !== 'undefined') {
								try {
									viewport.setPointerCapture(event.pointerId);
								} catch (e) {
									// Ignore browsers that reject pointer capture in this context.
								}
							}
						}

						function onPointerMove(event) {
							if (!dragging) {
								return;
							}

							dragDeltaX = event.clientX - dragStartX;
							if (Math.abs(dragDeltaX) > 6) {
								suppressClick = true;
							}
							applyTransform(dragDeltaX);
						}

						function onPointerEnd() {
							if (!dragging) {
								return;
							}

							dragging = false;
							viewport.classList.remove('is-dragging');
							track.style.transition = 'transform 320ms ease';

							var threshold = Math.max(30, Math.min(140, stepPx * 0.2));

							if (dragDeltaX <= -threshold && currentIndex < maxStart()) {
								currentIndex++;
							} else if (dragDeltaX >= threshold && currentIndex > 0) {
								currentIndex--;
							}

							dragDeltaX = 0;
							applyTransform(0);
							updateControls();
							updateDots();
						}

						if (carousel.dataset.carouselBound !== '1') {
							prevBtn.addEventListener('click', function() {
								if (currentIndex > 0) {
									currentIndex--;
									render();
								}
							});

							nextBtn.addEventListener('click', function() {
								if (currentIndex < maxStart()) {
									currentIndex++;
									render();
								}
							});

							viewport.addEventListener('pointerdown', onPointerDown);
							viewport.addEventListener('pointermove', onPointerMove);
							viewport.addEventListener('pointerup', onPointerEnd);
							viewport.addEventListener('pointercancel', onPointerEnd);
							viewport.addEventListener('pointerleave', onPointerEnd);
							viewport.addEventListener('dragstart', function(event) {
								event.preventDefault();
							});

							track.addEventListener('click', function(event) {
								var clickedLink = event.target && event.target.closest ? event.target.closest('a') : null;
								if (clickedLink) {
									suppressClick = false;
									return;
								}

								if (suppressClick) {
									event.preventDefault();
									event.stopPropagation();
									suppressClick = false;
								}
							}, true);

							if (!carousel.hasAttribute('tabindex')) {
								carousel.setAttribute('tabindex', '0');
							}
							carousel.addEventListener('keydown', function(event) {
								if (event.key === 'ArrowRight' && currentIndex < maxStart()) {
									event.preventDefault();
									currentIndex++;
									render();
								}
								if (event.key === 'ArrowLeft' && currentIndex > 0) {
									event.preventDefault();
									currentIndex--;
									render();
								}
							});

							carousel.dataset.carouselBound = '1';
						}

						render();
						carousel.__renderCarousel = render;
					}

					function initDadosHomeCarousel() {
						var carousels = document.querySelectorAll('[data-cursos-carousel]');
						carousels.forEach(buildCarousel);
					}

					if (document.readyState === 'loading') {
						document.addEventListener('DOMContentLoaded', initDadosHomeCarousel);
					} else {
						initDadosHomeCarousel();
					}

					var debounceTimer = null;
					function scheduleRecalc() {
						clearTimeout(debounceTimer);
						debounceTimer = setTimeout(function() {
							document.querySelectorAll('[data-cursos-carousel]').forEach(function(carousel) {
								if (typeof carousel.__renderCarousel === 'function') {
									carousel.__renderCarousel();
								}
							});
						}, 120);
					}

					window.addEventListener('resize', scheduleRecalc);
					window.addEventListener('load', scheduleRecalc);
					document.addEventListener('change', scheduleRecalc, true);
				})();
			</script>
		<?php endif; ?>
		<!-- <div class="btnVerMaisCursos btn">VER MAIS +</div> -->
</section>