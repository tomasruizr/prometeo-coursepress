<?php
/**
 * @package Prometeo_Coursepress
 * @version 1.0
 */
/*
Plugin Name: Prometeo Coursepress Adapter
Description: Este plugin incluye las cuztomizaciones de coursepress para Prometeo Online. El objetivo es que las modificaciones y cuztomizaciones realizadas persistan si se hace un upgrade de la versión de los plugins involucrados, y en cualquier caso, que sea facil la readaptación.
Author: Tomas Ruiz
Version: 1.0
*/
class Prometeo_CoursePress {

	public static function init() {
		add_shortcode(
			'course_list_prometeo',
			array( __CLASS__, 'course_list_prometeo' )
		);
		add_shortcode(
			'course_list_box_prometeo',
			array( __CLASS__, 'course_list_box_prometeo' )
		);
		add_shortcode(
			'course_categories_prometeo',
			array( __CLASS__, '_the_categories_prometeo' )
		);
		add_shortcode(
			'course_instructors_prometeo',
			array( __CLASS__, 'course_instructors_prometeo' )
		);
		add_shortcode(
			'course_media_prometeo',
			array( __CLASS__, 'course_media_prometeo' )
		);
		add_shortcode(
			'course_size_prometeo',
			array( __CLASS__, 'course_size_prometeo' )
		);
		add_shortcode(
			'course_description_prometeo',
			array( __CLASS__, 'course_description_prometeo' )
		);
		add_shortcode(
			'course_structure_prometeo',
			array( __CLASS__, 'course_structure_prometeo' )
		);
		// add_shortcode(
		// 	'course_instructors_prometeo',
		// 	array( __CLASS__, 'course_instructors_prometeo' )
		// );
		add_shortcode(
			'courses_student_dashboard_prometeo',
			array( __CLASS__, 'courses_student_dashboard_prometeo' )
		);
		add_shortcode(
			'course_unit_archive_submenu_prometeo',
			array( __CLASS__, 'course_unit_archive_submenu_prometeo' )
		);
		add_shortcode(
			'course_join_button_prometeo',
			array( __CLASS__, 'course_join_button_prometeo' )
		);
	}

	public static function get_query_var_prometeo($post_args){
		$tipos = array_filter((array)get_query_var("tipo"));
		$temas = array_filter((array)get_query_var("tema"));
		if (count($tipos)==0 && count($temas)==0) return $post_args;
		$post_args['tax_query'] = array();
		if (count($tipos)>0){
			array_push($post_args['tax_query'], 
				array(
					'taxonomy' => "course_category",
					'terms'    => $tipos
				)
			);
		}
		if (count($temas)>0){
			array_push($post_args['tax_query'], 
				array(
					'taxonomy' => "course_category",
					'terms'    => $temas
				)
			);
		}				
		
		return $post_args;
	}

	public static function render_course_filters(){
		$taxonomy = CoursePress_Data_Course::get_post_category_name();
		$TemaTerm = get_term_by("name", "TEMAS", $taxonomy);
		$TipoTerm = get_term_by("name", "TIPOS DE EXPERIENCIA DE APRENDIZAJE", $taxonomy);

		$Temas =  get_term_children( $TemaTerm->term_id, $taxonomy );
		$Tipos =  get_term_children( $TipoTerm->term_id, $taxonomy );

		$content = 
		'<div class="col filter-select-browse-course">
			<div class="styled-select three col">
				<select name="tipo" id="select-tipo">';
					$content .='<option value="todos">Todos</option>';
					foreach ($Tipos as $Tipo){
						$content .= sprintf('<option value="%1$s">%2$s</option>', $Tipo,  get_term( $Tipo)->name);
					}
		$content.= '</select>
			</div>
			<div class="styled-select three col">
				<select name="tema" id="select-tema">';
					$content .='<option value="todos">Todos</option>';
					foreach ($Temas as $Tema){
						$content .= sprintf('<option value="%1$s">%2$s</option>', $Tema,  get_term( $Tema)->name);
					}
		$content.= '</select>
			</div>
			<div class="three col">
				<a href="javascript:void(0)" onclick="search_courses()">Filtrar</a>
			</div>
		</div>';

		// /*Por tipo*/
		// $content = '<div class="col"><a href="javascript:void(0)" onclick="show_browse_courses()"><h3 class="buscar_button">Buscar...</h3></a></div><div id="div-browse-courses" class="col" style="display:none"><div id="cb-group-tipo" class=check-container>';
		// $content .= '<div class="squaredCheck">
		// 			<input onclick="todos_click(\'tipo\', this)" type="checkbox" value="0" id="squaredCheck-tipo-todos" name="check-tipo-todos" checked/> 
		// 			<label for="squaredCheck-tipo-todos"></label>
		// 			<spam class="cb-label">Todos</spam>
		// 		</div>
		// 		<br>
		// 		<br>';
		// foreach ($Tipos as $Tipo) {
		// 	//' . ((in_array($Tipo, get_query_var('tipo')) || !get_query_var('tipo')) ? 'checked' : '') . '
		// 	$content .= sprintf('<div class="squaredCheck disabled">
		// 			<input class="checkbox-search" type="checkbox" value="%1$s" id="squaredCheck-tipo-%1$s" name="check-tipo-%1$s"/> 
		// 			<label class="disabled-check" for="squaredCheck-tipo-%1$s"></label>
		// 			<spam class="cb-label disabled-check-label">%2$s</spam>
		// 		</div>
		// 		<br>
		// 		<br>', $Tipo,  get_term( $Tipo)->name);
		// }

	 //  	$content .= '</div>';
	 //  	/*Por tema*/
		// $content .= '<div id="cb-group-tema" class=check-container>';
		// $content .= '<div class="squaredCheck">
		// 			<input onclick="todos_click(\'tema\', this)" type="checkbox" value="0" id="squaredCheck-tema-todos" name="check-tema-todos" checked/> 
		// 			<label for="squaredCheck-tema-todos"></label>
		// 			<spam class="cb-label">Todos</spam>
		// 		</div>
		// 		<br>
		// 		<br>';
		// foreach ($Temas as $Tema) {
		// 	//' . ((in_array($Tipo, get_query_var('tema')) || !get_query_var('tema')) ? 'checked' : '') . '
		// 	$content .= sprintf('<div class="squaredCheck">
		// 			<input class="checkbox-search" type="checkbox" value="%1$s" id="squaredCheck-tema-%1$s" name="check-tema-%1$s"/>
		// 			<label class="disabled-check" for="squaredCheck-tema-%1$s"></label>
		// 			<spam class="cb-label disabled-check-label">%2$s</spam>
		// 		</div>
		// 		<br>
		// 		<br>', $Tema,  get_term( $Tema)->name);
		// }

	 //  	$content .= '</div>';
	 //  	$content .= '<div class="exec-buscar"><a class="prometeo-button" href="javascript:void(0)" onclick="search_courses()">Buscar</a></div></div>';
	  	return $content;
	}


	/**
	 * Funcion que retorna el número de alumnos de un curso.
	 * @param  [type] $a [description]
	 * @return [type]    [description]
	 */
	public static function course_size_prometeo( $a ) {
		
		$a = shortcode_atts( array(
			'course_id' => CoursePress_Helper_Utility::the_course( true ),
			'class' => '',
		), $a, 'course_size_prometeo' );
		$course_id = (int) $a['course_id'];
		$class = (int) $a['class'];
		if ($class != ''){
			$class = 'class='.$class;
		}
		$count = (int) CoursePress_Data_Course::count_students( $course_id );
		$label = $count == 1 ? "Persona inscrita" : "Personas inscritas";
		$template = "<div ". $class ."><p>". $count." ".$label."</p></div>" ;
		echo $template;
	}
	/**
	 * Este es el método que renderiza cada ocurrencia de los cursos en la lista.
	 * @param  [type] $a [description]
	 * @return [type]    [description]
	 */
	public static function course_list_box_prometeo( $attr ) {
		$a = shortcode_atts( array(
			'course_id' => CoursePress_Helper_Utility::the_course( true ),
			'clickable' => false,
			'clickable_label' => __( 'Course Details', 'cp' ),
			'override_button_text' => '',
			'override_button_link' => '',
			'button_label' => __( 'Details', 'cp' ),
			'echo' => false,
			'show_withdraw_link' => false,
			'prometeo_button' => false,
		), $attr, 'course_list_box' );

		$course_id = (int) $a['course_id'];
		$clickable_label = sanitize_text_field( $a['clickable_label'] );
		$echo = cp_is_true( $a['echo'] );
		$clickable = cp_is_true( $a['clickable'] );
		$url = CoursePress_Data_Course::get_course_url( $course_id );

		$course_image = CoursePress_Data_Course::get_setting( $course_id, 'listing_image' );
		$has_thumbnail = ! empty( $course_image );

		$clickable_link = $clickable ? 'data-link="' . esc_url( $url ) . '"' : '';
		$clickable_class = $clickable ? 'clickable' : '';
		$clickable_text = $clickable ? '<div class="clickable-label">' . $clickable_label . '</div>' : '';
		$button_label = $a['button_label'];
		$button_link = $url;
		$withdraw_from_course = '';

		if ( ! empty( $a['override_button_link'] ) ) {
			$button_link = $a['override_button_link'];
		}

		$button_text = sprintf( '<a href="%s" rel="bookmark" class="button apply-button apply-button-details prometeo-button-small prometeo-button course-list-entoll">%s</a>', esc_url( $button_link ), $button_label );
		// $button_text = do_shortcode(sprintf('[course_join_button course_id="%s" list_page="true" not_started_text="No disponible aun" instructor_text="Administrar" class="prometeo-button-small"]', $course_id));
		$instructor_link = $clickable ? 'no' : 'yes';
		$thumbnail_class = $has_thumbnail ? 'has-thumbnail' : '';

		$completed = false;
		$student_progress = false;
		if ( is_user_logged_in() ) {
			$student_progress = CoursePress_Data_Student::get_completion_data( get_current_user_id(), $course_id );
			$completed = isset( $student_progress['completion']['completed'] ) && ! empty( $student_progress['completion']['completed'] );
			/**
			 * Withdraw from course
			 */
			$show_withdraw_link = cp_is_true( $a['show_withdraw_link'] );
			if ( $show_withdraw_link && ! $completed ) {
				$withdraw_link = add_query_arg(
					array(
						'_wpnonce' => wp_create_nonce( 'coursepress_student_withdraw' ),
						'course_id' => $course_id,
						'student_id' => get_current_user_id(),
					)
				);
				$withdraw_from_course = sprintf( '<a href="%s" class="cp-withdraw-student">%s</a>', esc_url( $withdraw_link ), __( 'Withdraw', 'cp' ) );
			}
		}
		$completion_class = CoursePress_Data_Course::course_class( $course_id );
		$completion_class = implode( ' ', $completion_class );

		// Add filter to post classes

		// Override button
		if ( ! empty( $a['override_button_text'] ) && ! empty( $a['override_button_link'] ) ) {
			$button_text = '<button class="coursepress-course-link" data-link="' . esc_url( $a['override_button_link'] ) . '">' . esc_attr( $a['override_button_text'] ) . '</button>';
		}

		/**
		 * schema.org
		 */
		$schema = apply_filters( 'coursepress_schema', '', 'itemscope' );
		$course_title = do_shortcode( sprintf( '[course_title course_id="%s"]', $course_id ) );
		$course_title = sprintf( '<a href="%s" rel="bookmark">%s</a>', esc_url( $url ), $course_title );
		$course_image = esc_url(CoursePress_Data_Course::get_setting( $course_id, 'listing_image' ));
		$course_thumbnail = do_shortcode('[course_thumbnail course_id="' . $course_id . '"]');
		// if ($course_thumbnail == "") {
		// 	$course_thumbnail = '<div class="course-thumbnail course-featured-media course-featured-media-4 "><figure style="width:216px;height:122px;"><img src="/assets/img/image_missing.jpg" class="course-media-img"></figure></div>';
		// }
		// //216 122
		if ($course_image == ""){
			$course_image = '/assets/img/image_missing.jpg" class="course-media-img';
		}
		$course_thumbnail = sprintf( '<a href="%s" rel="bookmark">%s</a>', esc_url( $url ), $course_thumbnail);
		
		//****************************************************
		// Modificado por Tomás Ruiz: Agregado after en el shortcode course_categories para el separador de las categorías
		//****************************************************
		//$template = '<div class="course course_list_box_item course_' . $course_id . ' ' . $clickable_class . ' ' . $completion_class . ' ' . $thumbnail_class . '" ' . $clickable_link . ' ' . $//schema .'>
		if ($attr["prometeo_button"]){
			$buttonTAL = do_shortcode( '[course_join_button_prometeo course_id =' .$course_id. ' class="prometeo-button"]' );
		}
		$template = '<div class="col course_list_item_prometeo course_' . $course_id . ' ' . $clickable_class . ' ' . $completion_class . ' ' . $thumbnail_class . '" ' . $clickable_link . ' ' . $schema .' style="background-image:url('.$course_image.')">'.
			//$course_thumbnail.
			//'[course_thumbnail course_id="' . $course_id . '"]'.
			
			'<div class="course-data">'.
				'<div class="course-information">
					' . $course_title .
					//"[course_summary course_id="' . $course_id . '"]".
					'[course_instructors_prometeo style="list-flat" link="' . $instructor_link . '" course_id="' . $course_id . '"]
					<div class="course-meta-information">'.
						//'[course_start label="" course_id="' . $course_id . '"]'.
						//'[course_language label="" course_id="' . $course_id . '"]'.
	                    '[course_categories_prometeo before=" " after="," course_id="' . $course_id . '"]'.
						$withdraw_from_course.'
					</div>
				</div>' .
				'[course_cost label="" course_id="' . $course_id . '" class="list_price_prometeo"]' .
				((($attr["prometeo_button"]) == true) ? $buttonTAL : $button_text . $clickable_text) . '
				
			</div>
		</div>
		';

		$template = apply_filters( 'coursepress_template_course_list_box', $template, $course_id, $a );

		$content = do_shortcode( $template );

		if ( $echo ) {
			echo $content;
		}

		return $content;
	}

	
	/**
	 * Este objetivo de éste método es registrar un shortcode que permita tomar control de como se presentan las listas de cursos en Prometeo. Añade la funcionalidad de Paginación, y personaliza la presentación. Las modificaciones de la versión original (2.0.7) se encuentran rodeadas por comentarios de modificación.
	 * 
	 * @param  Array
	 * @return string
	 */
	public static function course_list_prometeo( $attrs ) {

		CoursePress_Core::$is_cp_page = true;
		$atts = CoursePress_Helper_Utility::sanitize_recursive(
			shortcode_atts(
				array(
					'completed_label' => __( 'Completed courses', 'cp' ),
					'context' => 'all', // <blank>, enrolled, completed
					'current_label' => __( 'Current Courses', 'cp' ),
					'dashboard' => false,
					'facilitator_label' => __( 'Facilitated Courses', 'cp' ),
					'facilitator' => '',
					'future_label' => __( 'Starting soon', 'cp' ),
					'incomplete_label' => __( 'Incomplete courses', 'cp' ),
					'instructor_msg' => __( 'The Instructor does not have any courses assigned yet.', 'cp' ),
					'instructor' => '', // Note, one or the other
					'limit' => - 1,
					'manage_label' => __( 'Manage Courses', 'cp' ),
					'order' => 'ASC',
					'orderby' => 'meta', /// possible values: meta, title
					'past_label' => __( 'Past courses', 'cp' ),
					'show_labels' => false,
					'status' => 'publish',
					'student_msg' => __( 'You are not enrolled in any courses. <a href="%s">See available courses.</a>', 'cp' ),
					'student' => '', // If both student and instructor is specified only student will be used
					'suggested_label' => __( 'Suggested courses', 'cp' ),
					'suggested_msg' => __( 'You are not enrolled in any courses.<br />Here are a few you might like, or <a href="%s">see all available courses.</a>', 'cp' ),
					'show_withdraw_link' => false,
					'show_browse_courses' => true,
					'prometeo_button' => false,
				),
				$attrs,
				'course_page'
			)
		);
		$instructor_list = false;
		$student_list = false;
		$atts['dashboard'] = cp_is_true( $atts['dashboard'] );
		$courses = array();
		$content = '';
		$student = 0;
		$include_ids = array();

		$content = '';

		/**
		 * Sanitize show_withdraw_link
		 */
		if ( empty( $atts['student'] ) || 'incomplete' != $atts['status'] ) {
			$atts['show_withdraw_link'] = false;
		}

		if ( ! empty( $atts['instructor'] ) ) {
			$include_ids = array();
			$instructors = explode( ',', $atts['instructor'] );
			if ( ! empty( $instructors ) ) {
				foreach ( $instructors as $ins ) {
					$ins = (int) $ins;
					if ( $ins ) {
						$course_ids = CoursePress_Data_Instructor::get_assigned_courses_ids( $ins, $atts['status'] );
						if ( $course_ids ) {
							$include_ids = array_unique( array_merge( $include_ids, $course_ids ) );
						}
					}
				}
			} else {
				$instructor = (int) $atts['instructor'];
				if ( $instructor ) {
					$course_ids = CoursePress_Data_Instructor::get_assigned_courses_ids( $instructor, $atts['status'] );
					if ( $course_ids ) {
						$include_ids = array_unique( array_merge( $include_ids, $course_ids ) );
					}
				}
			}
			$instructor_list = true;
			if ( empty( $include_ids ) ) { return ''; }
		}

		if ( ! empty( $atts['facilitator'] ) ) {
			$facilitator = $atts['facilitator'];
			$atts['context'] = 'facilitator';
			$include_ids = CoursePress_Data_Facilitator::get_facilitated_courses( $facilitator, 'publish', true );

			if ( empty( $include_ids ) ) {
				return '';
			}
		}

		if ( ! empty( $atts['student'] ) ) {
			$include_ids = array();
			$students = explode( ',', $atts['student'] );
			foreach ( $students as $student ) {
				$student = (int) $student;
				if ( $student ) {
					$courses_ids = array();
					$courses_to_add = CoursePress_Data_Student::get_enrolled_courses_ids( $student );
					if ( isset( $atts['status'] ) ) {
						foreach ( $courses_to_add as $course_id ) {
							$status = get_post_status( $course_id );
							if ( 'publish' != $status ) {
								continue;
							}
							$add = true;
							if ( $atts['status'] != 'publish') {
								$status = CoursePress_Data_Student::get_course_status( $course_id, $student, false );
								if ( $atts['status'] == 'completed' && $status != 'certified' ) {
									$add = false; 
								} else if ( $atts['status'] == 'incompleted' && $status == 'certified' ) {
									$add = false;
								}
							}
							if ( $add ) {
								$courses_ids[] = $course_id;
							}
						}
					} else {
						$courses_ids = $courses_to_add;
					}
					if ( $courses_ids ) {
						$include_ids = array_unique( array_merge( $include_ids, $courses_ids ) );
					}
				}
			}
			$student_list = true;
		}

		$post_args = array(
			'order' => $atts['order'],
			'post_type' => CoursePress_Data_Course::get_post_type_name(),
			'post_status' => $atts['status'],
			'posts_per_page' => (int) $atts['limit'],
			'suppress_filters' => true,
			'meta_key' => 'cp_course_start_date',
			'orderby' => 'meta_value_num',
		);

		$test_empty_courses_ids = false;

		switch ( $atts['context'] ) {
			case 'enrolled':
				$test_empty_courses_ids = true;
				$user_id = get_current_user_id();
				$include_ids = CoursePress_Data_Student::get_enrolled_courses_ids( $user_id );
			break;
			case 'incomplete':
				$test_empty_courses_ids = true;
				$user_id = get_current_user_id();
				$ids = CoursePress_Data_Student::get_enrolled_courses_ids( $user_id );
				foreach ( $ids as $course_id ) {
					$status = CoursePress_Data_Student::get_course_status( $course_id, $user_id, false );
					if ( 'certified' != $status ) {
						$include_ids[] = $course_id;
					}
				}
			break;
			case 'completed':
				$test_empty_courses_ids = true;
				$user_id = get_current_user_id();
				$ids = CoursePress_Data_Student::get_enrolled_courses_ids( $user_id );
				foreach ( $ids as $course_id ) {
					$status = CoursePress_Data_Student::get_course_status( $course_id, $user_id, false );
					if ( 'certified' == $status ) {
						$include_ids[] = $course_id;
					}
				}
			break;
			case 'future':
				unset( $post_args['meta_key'] );
				$post_args['meta_query'] = array(
				array(
					'key' => 'cp_course_start_date',
					'value' => time(),
					'type' => 'NUMERIC',
					'compare' => '>',
				),
				);
			break;
			case 'past':
				unset( $post_args['meta_key'] );
				$post_args['meta_query'] = array(
				'relation' => 'AND',
				array(
					'key' => 'cp_course_end_date',
					'compare' => 'EXISTS',
				),
				array(
					'key' => 'cp_course_end_date',
					'value' => 0,
					'type' => 'NUMERIC',
					'compare' => '>',
				),
				array(
					'key' => 'cp_course_end_date',
					'value' => time(),
					'type' => 'NUMERIC',
					'compare' => '<',
				),
				);
			break;
			case 'manage':
				$user_id = get_current_user_id();
				$test_empty_courses_ids = true;
				if ( CoursePress_Data_Capabilities::can_manage_courses( $user_id ) ) {
					$local_args = array(
					'post_type' => CoursePress_Data_Course::get_post_type_name(),
					'nopaging' => true,
					'fields' => 'ids',
					);
					$include_ids = get_posts( $local_args );
				} else {
					$include_ids = CoursePress_Data_Instructor::get_assigned_courses_ids( $user_id );
					if ( empty( $include_ids ) ) {
						$include_ids = CoursePress_Data_Facilitator::get_facilitated_courses( $user_id, array( 'all' ), true, 0, -1 );
					}
				}
			break;
			case 'all':
				$atts['orderby'] = strtolower( $atts['orderby'] );
				switch ( $atts['orderby'] ) {
					case 'title':
					case 'post_title':
						$post_args['orderby'] = 'title';
					break;
					default:
						$post_args['orderby'] = 'meta_value_num';
					break;
				}
				break;
		}

		if ( $test_empty_courses_ids && empty( $include_ids ) ) {
			/**
			 * do nothing if we have empty list
			 */
			$courses = array();
		} else if ( ( ( $student_list || $instructor_list ) && ! empty( $include_ids ) ) || ( ! $student_list && ! $instructor_list ) ) {
			$post_args = self::get_query_var_prometeo($post_args);
			
			if ( ! empty( $include_ids ) ) {
				$post_args = wp_parse_args( array( 'post__in' => $include_ids ), $post_args );
			}

			
			$courses = get_posts( $post_args );
		}

		$counter = 0;

		if ( ! $atts['dashboard'] ) {
			foreach ( $courses as $course ) {
				$shortcode_attributes  = array(
					'course_id' => $course->ID,
					'show_withdraw_link' => $atts['show_withdraw_link'],
					//Agregado por Tomás Ruiz
					'prometeo_button' => (array_key_exists('prometeo_button', $atts) ? ($atts['prometeo_button'] == 'true'? true : false) : false) ,
				);
				$shortcode_attributes = CoursePress_Helper_Utility::convert_array_to_params( $shortcode_attributes );
				$content .= do_shortcode( '[course_list_box_prometeo ' . $shortcode_attributes . ']' );
				$counter += 1;
			}
		} else {
			if ( $student_list ) {
				$my_courses = CoursePress_Data_Student::my_courses( $student, $courses );
				$context = $atts['context'];

				if ( isset( $my_courses[ $context ] ) ) {
					$courses = $my_courses[ $context ];
				}
				$courses = array_filter( $courses );

				if ( ! empty( $courses ) ) {
					$counter += count( $courses );
					$content .= CoursePress_Template_Course::course_list_table( $courses );
				}
			} else {
				foreach ( $courses as $course ) {
					$course_url = get_edit_post_link( $course->ID );
					$content .= do_shortcode( '[course_list_box_prometeo prometeo_button="true" course_id="' . $course->ID . '" override_button_text="' . esc_attr__( 'Manage Course', 'cp' ) . '" override_button_link="' . esc_url( $course_url ) . '"]' );
					$counter += 1;
				}
			}
		}

		$context = $atts['dashboard'] && $instructor_list ? 'manage' : $atts['context'];

		if ( ( $atts['dashboard'] && ! empty( $counter ) ) || ! empty( $atts['show_labels'] ) ) {
			$label = '';

			switch ( $context ) {
				case 'enrolled':
				case 'current':
				case 'all':
					$label = $atts['current_label'];
				break;

				case 'future':
					$label = $atts['future_label'];
				break;

				case 'incomplete':
					$label = $atts['incomplete_label'];
				break;

				case 'completed':
					$label = $atts['completed_label'];
				break;

				case 'past':
					$label = $atts['past_label'];
				break;

				case 'manage':
					$label = $atts['manage_label'];
				break;

				case 'facilitator':
					$label = $atts['facilitator_label'];
				break;
			}

			$content = '<div class="dashboard-course-list ' . esc_attr( $context ) . '">' .
						'<h3 class="section-title">' . esc_html( $label ) . '</h3>' .
						$content .
						'</div>';

		} elseif ( $atts['dashboard'] && 'enrolled' === $context ) {

			$label = $atts['suggested_label'];
			$message = sprintf( $atts['suggested_msg'], esc_url( CoursePress_Core::get_slug( 'courses', true ) ) );

			$content = '<div class="dashboard-course-list suggested">' .
						'<h3 class="section-title">' . esc_html( $label ) . '</h3>' .
						'<p>' . $message . '</p>' .
						do_shortcode( '[course_random featured_title="" media_type="image" media_priority="image"]' ) .
						'</div>';

		} 
		//** AGREGADO POR TOMAS RUIZ PARA CONTROLAR UN DIV CENTRADO EN PANTALLA EN CASO DE LA LISTA DE CURSOS
		
		if (empty($content)) return $content;
		$content_wrap = '';
		// if (!array_key_exists('show_browse_courses', $attrs)){
		if (!property_exists($attrs,'show_browse_courses')){
			$content_wrap .= self::render_course_filters();
		}
		else{
			if ($attrs['show_browse_courses'] == "true")
				$content_wrap .= self::render_course_filters();
		}

		$content_wrap .= '<div class="row course_list_prometeo_centered">' . $content . '</div>';
		return $content_wrap;
	}

	public static function _the_categories_prometeo( $atts ) {
		$atts = shortcode_atts(
			array(
				'course_id' => CoursePress_Helper_Utility::the_course( true ),
				'before' => '',
				'after' => '',
				'icon' => '<span class="dashicons dashicons-category"></span>',
			),
			$atts,
			'course_categories'
		);

		$categories = self::the_categories( $atts['course_id'], $atts['before'], $atts['after'] );
		$content = "";
		if ( ! empty( $categories ) ) {
			$strPre = "<table>";
			$strPost = "<table>";
			$Categorias = "";
			$Temas = "";
			$Otros = "";
			if (!empty ($categories["TIPOS DE EXPERIENCIA DE APRENDIZAJE"])) {
				//$formatCat = '<tr><td>Categoría:</td><td><div class="course-category course-category-%s">%s %s</div></td></tr>';
				$formatCat = '<div class="course-category course-category-%s">Categoría: %s %s</div>';
				$Categorias = sprintf( $formatCat, $atts['course_id'], $atts['icon'], $categories["TIPOS DE EXPERIENCIA DE APRENDIZAJE"] );
			}
			if (!empty ($categories["TEMAS"])) {
				//$formatTem = '<tr><td>Tema:</td><td><div class="course-category course-category-%s">%s %s</div></td></tr>';
				$formatTem = '<div class="course-category course-category-%s">Tema: %s %s</div>';
				$Temas = sprintf( $formatTem, $atts['course_id'], $atts['icon'], $categories["TEMAS"] );
			}
			if (!empty ($categories["Otro"])) {
				//$formatOtr = '<tr><td colspan=2><div class="course-category course-category-%s">%s %s</div></td></tr>';
				$formatOtr = '<div class="course-category course-category-%s">%s %s</div>';
				$Otros = sprintf( $formatOtr, $atts['course_id'], $atts['icon'], $categories["Otro"] );
			}
			$content = $Categorias.$Temas.$Otros;

		}
		return $content;
	}

	//*************************************************************
	// Modificado por Tomás Ruiz para poder formatear los temas y las categorias dependiendo de la taxonomía en la que las categorías fueron agrupadas.
	//*************************************************************
	public static function the_categories( $course_id, $before = '', $after = '' ) {
		$taxonomy = CoursePress_Data_Course::get_post_category_name();
		$terms = wp_get_object_terms( (int) $course_id, array( $taxonomy ) );
		$Tema = get_term_by("name", "TEMAS", $taxonomy);
		$Categoria = get_term_by("name", "TIPOS DE EXPERIENCIA DE APRENDIZAJE", $taxonomy);
		if ( ! empty( $terms ) ) {
			$links = array(
				"TEMAS" => array(),
				"TIPOS DE EXPERIENCIA DE APRENDIZAJE" => array(),
				"Otros" => array()
			);

			foreach ( $terms as $term ) {
				  // climb up the hierarchy until we reach a term with parent = '0'
			    $ascendance = array();
			    $parent = $term;
			    while ($parent->parent != '0'){
			        $parent_id = $parent->parent;
			        array_push($ascendance, $parent);
			        $parent = get_term($parent_id);
			    }
			    $ascendance = array_reverse($ascendance);
			    foreach ($ascendance as $asc) {	
					//TODO: Mosca con hacer que el vinculo de la categoría vaya a una busqueda
					// $link = get_term_link( $asc->term_id, $taxonomy );
					// $link = CoursePress_Core::get_slug("courses", true);
					$link = "/cursos/";
					
					if ($parent->term_id == $Tema->term_id){
						$link .= '?tema[]=' . $asc->term_id;
						array_push($links["TEMAS"], sprintf( '<a href="%s">%s</a>', $link, $asc->name ));
					} else if ($parent->term_id == $Categoria->term_id){
						$link .= '?tipo[]=' . $asc->term_id;
						array_push($links["TIPOS DE EXPERIENCIA DE APRENDIZAJE"], sprintf( '<a href="%s">%s</a>', $link, $asc->name ));
					} else{
						array_push($links["Otros"], sprintf( '<a href="%s">%s</a>', $link, $asc->name ));
					}
			    }
				//$links[] = sprintf( '<a href="%s">%s</a>', esc_url( $link ), $term->name );
			}

			$links["TEMAS"]  = $before . implode( $after . $before, $links["TEMAS"]);
			$links["TIPOS DE EXPERIENCIA DE APRENDIZAJE"]  = $before . implode( $after . $before, $links["TIPOS DE EXPERIENCIA DE APRENDIZAJE"]);
			$links["Otros"]  = $before . implode( $after . $before, $links["Otros"]);
			return $links;
		}

		return '';
	}

	

	/**
	 * Muestra la lista de instructores en el formato de PROMETEO.
	 *
	 * Supported styles:
	 *
	 * style="block" - List profile blocks including name, avatar, description
	 *                 (optional) and profile link. You can choose to make the
	 *                 entire block clickable ( link_all="yes" ) or only the
	 *                 profile link ( link_all="no", Default).
	 * style="list"  - Lists instructor display names (separated by list_separator).
	 * style="link"  - Same as 'list', but returns links to instructor profiles.
	 * style="count" - Outputs a simple integer value with the total of
	 *                 instructors for the course.
	 *
	 * @since  1.0.0
	 * @param  array $atts Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public static function course_instructors_prometeo( $atts ) {
		global $wp_query;
		global $ultimatemember;

		$instructor_profile_slug = CoursePress_Core::get_setting(
			'slugs/instructor_profile',
			'instructor'
		);
		// $instructor_profile_slug='area-de-miembros';
		extract( shortcode_atts( array(
			'course_id' => CoursePress_Helper_Utility::the_course( true ),
			'label' => __( 'Instructor', 'cp' ),
			'label_plural' => __( 'Instructors', 'cp' ),
			'label_delimeter' => ':&nbsp;',
			'label_tag' => '',
			'count' => false, // Deprecated.
			'list' => false, // Deprecated.
			'link' => false,
			'link_text' => __( 'View Full Profile', 'cp' ),
			'show_label' => 'no', // Yes, no.
			'summary_length' => 50,
			'style' => 'block', // List, list-flat, block, count.
			'list_separator' => ', ',
			'avatar_size' => 80,
			'avatar_position' => 'bottom',
			'default_avatar' => '',
			'show_divider' => 'yes',
			'link_all' => 'no',
			'class' => '',
		), $atts, 'course_instructors' ) );

		$course_id = (int) $course_id;

		$label = sanitize_text_field( $label );
		$label_plural = sanitize_text_field( $label_plural );
		$label_delimeter = sanitize_text_field( $label_delimeter );
		$label_tag = sanitize_html_class( $label_tag );
		$link = cp_is_true( sanitize_text_field( $link ) );
		$link_text = sanitize_text_field( $link_text );
		$show_label = cp_is_true( sanitize_text_field( $show_label ) );
		$summary_length = (int) $summary_length;
		$style = sanitize_html_class( $style );
		$avatar_size = (int) $avatar_size;
		$avatar_position = sanitize_text_field( $avatar_position );
		$show_divider = cp_is_true( sanitize_html_class( $show_divider ) );
		$link_all = cp_is_true( sanitize_html_class( $link_all ) );
		$class = sanitize_html_class( $class );

		// Support deprecated arguments.
		$count = cp_is_true( sanitize_html_class( $count ) );
		$list = cp_is_true( sanitize_html_class( $list ) );
		$style = $count ? 'count' : $style;
		$style = $list ? 'list-flat' : $style;
		//***************************************************************************************************
		//Tomás Ruiz: Modificación Hecha: quité esta condicional.
		//***************************************************************************************************
		//$show_label = 'list-flat' === $style && ! $show_label ? 'yes' : $show_label;

		if ( empty( $course_id ) ) {
			$instructors = get_users( array( 'meta_value' => 'instructor' ) );
//AGREGADO POR TOMÁS RUIZ PARA SOPORTAR EL ORDEN DE INSTRUCTORES.
			$instructors = instructors_order($instructors);
//AGREGADO POR TOMÁS RUIZ PARA SOPORTAR EL FILTRO DE INSTRUCTORES CON CURSOS PUBLICADOS.
			$instructors = filter_active_instructors($instructors);
		} else {
			$instructors = CoursePress_Data_Course::get_instructors( $course_id, true );
		}

		$list = array();
		$content = '';

		if ( 0 < count( $instructors ) && $show_label ) {
			if ( ! empty( $label_tag ) ) {
				$content .= '<' . $label_tag . '>';
			}

			if ( count( $instructors ) > 1 ) {
				$content .= $label_plural . $label_delimeter;
			} else {
				$content .= $label . $label_delimeter;
			}

			if ( ! empty( $label_tag ) ) {
				$content .= '</' . $label_tag . '>';
			}
		}
		
		if ( 'count' != $style ) {
			if ( ! empty( $instructors ) ) {
				foreach ( $instructors as $instructor ) {
					$profile_href = trailingslashit( home_url() ) . trailingslashit( $instructor_profile_slug );
					$hash = md5( $instructor->data->user_login );
					$instructor_hash = CoursePress_Data_Instructor::get_hash( $instructor );

					if ( empty( $instructor_hash ) ) {
						CoursePress_Data_Instructor::create_hash( $instructor );
					}

					$show_username = cp_is_true( CoursePress_Core::get_setting( 'instructor/show_username', true ) );
					$profile_href .= $show_username ? trailingslashit( $instructor->data->user_login ) : trailingslashit( $hash );
					// MODIFICADO POR TOMÁS RUIZ: CONVIERTE EL VINCULO AL INSTRUCTOR EN PERFIL DE USUARIO.
					$instructor_profile_slug = CoursePress_Core::get_setting(
						'slugs/instructor_profile',
						'instructor'
					);
					$profile_href = preg_replace("/.+?(?:". $instructor_profile_slug ."\/)/", get_permalink($ultimatemember->permalinks->core['user']), $profile_href);

					$display_name = ' ' . apply_filters(
						'coursepress_schema',
						esc_html( CoursePress_Helper_Utility::get_user_name( $instructor->ID, false, false ) ),
						'title'
					);

					switch ( $style ) {
						case 'block':
							/**
							 * schema.org
							 */
							$schema = apply_filters( 'coursepress_schema', '', 'itemscope-person' );

							um_fetch_user( $instructor->ID );
							$avatar = um_get_avatar_uri( um_profile('profile_photo'), 218 );
							// $avatar = um_get_avatar_uri( um_profile('profile_photo', "original"));
							$content .= '<div class="instructor-profile ' . $class . '"'.$schema.' style="background-image:url('.$avatar.')"><a href="' . esc_url_raw( $profile_href ) . '"><spam class="invicible-link"></spam></a>';

							if ( $link_all ) {
								$content .= '<a href="' . esc_url_raw( $profile_href ) . '">';
							}

							$content .= '<div class="instructor-data-prometeo">';
							if ( 'bottom' == $avatar_position ) {
								$content .= '<div class="profile-name">' . $display_name . '</div>';
							}

							/**
							 * schema.org
							 */
							$schema = apply_filters( 'coursepress_schema', '', 'image' );

							// $content .= '<div class="profile-avatar"'.$schema.'>';
							// $content .= '</div>';

							if ( 'top' == $avatar_position ) {
								$schema = apply_filters( 'coursepress_schema', '', 'itemscope-person' );
								$content .= sprintf(
									'<div class="profile-name" %s>%s</div>',
									$schema,
									$display_name
								);
							}

							if ( $link_all ) {
								$content .= '</a>';
							}

							if ( ! empty( $summary_length ) ) {
								$content .= '<div class="profile-description">' . CoursePress_Helper_Utility::author_description_excerpt( $instructor, $summary_length ) . '</div>';
							}

							if ( ! empty( $link_text ) ) {
								$content .= '<div class="profile-link">';
								$content .= ! $link_all ? '<a href="' . esc_url_raw( $profile_href ) . '">' : '';
								$content .= $link_text;
								$content .= ! $link_all ? '</a>' : '';
								$content .= '</div>';
							}

							$content .= '</div>';
							$content .= '</div>';
							break;

						case 'link':
						case 'list':
						case 'list-flat':
							if ( $link ) {
								$schema = apply_filters( 'coursepress_schema', '', 'itemscope-person' );
								$list[] = sprintf(
									'<a href="%s" %s>%s</a>',
									esc_url_raw( $profile_href ),
									$schema,
									$display_name
								);
							} else {
								$list[] = $display_name;
							}
							break;
					}
				}
			}
		}

		switch ( $style ) {
			case 'block':
				$content = '<div class="instructor-block ' . $class . '">' . $content . '</div>';
				if ( $show_divider && ( 0 < count( $instructors ) ) ) {
					$content .= '<div class="divider"></div>';
				}
				break;

			case 'list-flat':
				$content .= implode( $list_separator, $list );
				$content = '<div class="instructor-list instructor-list-flat ' . $class . '">' . $content . '</div>';
				break;

			case 'list':
				$content .= '<ul>';
				foreach ( $list as $instructor ) {
					$content .= '<li>' . $instructor . '</li>';
				}
				$content .= '</ul>';
				$content = '<div class="instructor-list ' . $class . '">' . $content . '</div>';
				break;

			case 'count':
				$content = count( $instructors );
				break;
		}

		return $content;
	}

	/**
	 * Shows the course media (video or image).
	 * Modificado por Tomás Ruiz para agregar el Wrapper class del video para centrarlo
	 *
	 * @since 1.0.0
	 * @param  array $atts Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public static function course_media_prometeo( $atts ) {
		extract( shortcode_atts( array(
			'course_id' => CoursePress_Helper_Utility::the_course( true ),
			'class' => '',
			'height' => CoursePress_Core::get_setting( 'course/image_height' ),
			'list_page' => 'no',
			'priority' => '', // Gives priority to video (or image).
			'type' => '', // Default, video, image.
			'width' => CoursePress_Core::get_setting( 'course/image_width' ),
			'wrapper' => '',
			//Agregado por Tomás Ruiz
			'wrapper_class' => '',
		), $atts, 'course_media' ) );

		$course_id = (int) $course_id;
		if ( empty( $course_id ) ) { return ''; }

		$type = sanitize_text_field( $type );
		$priority = sanitize_text_field( $priority );
		$list_page = cp_is_true( sanitize_html_class( $list_page ) );
		$class = sanitize_html_class( $class );
		$wrapper = sanitize_html_class( $wrapper );
		//Agregado por Tomás Ruiz
		$wrapper_class = sanitize_html_class( $wrapper_class );
		$height = sanitize_text_field( $height );
		$width = sanitize_text_field( $width );

		// We'll use pixel if none is set
		if ( ! empty( $width ) && (int) $width == $width ) {
			$width .= 'px';
		}
		if ( ! empty( $height ) && (int) $height == $height ) {
			$height .= 'px';
		}

		if ( ! $list_page ) {
			$type = empty( $type ) ? CoursePress_Core::get_setting( 'course/details_media_type', 'default' ) : $type;
			$priority = empty( $priority ) ? CoursePress_Core::get_setting( 'course/details_media_priority', 'video' ) : $priority;
		} else {
			$type = empty( $type ) ? CoursePress_Core::get_setting( 'course/listing_media_type', 'default' ) : $type;
			$priority = empty( $priority ) ? CoursePress_Core::get_setting( 'course/listing_media_priority', 'image' ) : $priority;
		}

		$priority = 'default' != $type ? false : $priority;

		// Saves some overhead by not loading the post again if we don't need to.
		$class = sanitize_html_class( $class );

		$course_video = CoursePress_Data_Course::get_setting( $course_id, 'featured_video' );
		$course_image = CoursePress_Data_Course::get_setting( $course_id, 'listing_image' );

		$content = '';

		if ( 'thumbnail' == $type ) {
			$type = 'image';
			$priority = 'image';
		}

		// If no wrapper and we're specifying a width and height, we need one, so will use div.
		if ( empty( $wrapper ) && ( ! empty( $width ) || ! empty( $height ) ) ) {
			$wrapper = 'div';
		}

		$wrapper_style = '';
		$wrapper_style .= ! empty( $width ) ? 'width:' . $width . ';' : '';
		$wrapper_style .= ! empty( $width ) ? 'height:' . $height . ';' : '';

		if ( is_singular( 'course' ) ) {
			$wrapper_style = '';
		}

		if ( ( ( 'default' == $type && 'video' == $priority ) || 'video' == $type || ( 'default' == $type && 'image' == $priority && empty( $course_image ) ) ) && ! empty( $course_video ) ) {

			$content = '<div class="video_player course-featured-media course-featured-media-' . $course_id . ' ' . $class . '">';

			$content .= ! empty( $wrapper ) ? '<' . $wrapper . ' class="'. $wrapper_class . '"'. ' style="' . $wrapper_style . '">' : '';

			$video_extension = pathinfo( $course_video, PATHINFO_EXTENSION );

			if ( ! empty( $video_extension ) ) {
				$attr = array(
					'src' => $course_video,
				);
				$content .= wp_video_shortcode( $attr );
			} else {
				$embed_args = array();
				// $embed_args["width"] = $width;
				// $embed_args["height"] = $height;
				// Add YouTube filter.
				if ( preg_match( '/youtube.com|youtu.be/', $course_video ) ) {
					add_filter( 'oembed_result', array(
						'CoursePress_Helper_Utility',
						'remove_related_videos',
					), 10, 3 );

				}

				$content .= wp_oembed_get( $course_video, $embed_args );
			}

			$content .= ! empty( $wrapper ) ? '</' . $wrapper . '>' : '';
			$content .= '</div>';
		}

		if ( ( ( 'default' == $type && 'image' == $priority ) || 'image' == $type || ( 'default' == $type && 'video' == $priority && empty( $course_video ) ) ) && ! empty( $course_image ) ) {

			$content .= '<div class="course-thumbnail course-featured-media course-featured-media-' . $course_id . ' ' . $class . '">';
			$content .= ! empty( $wrapper ) ? '<' . $wrapper . ' style="' . $wrapper_style . '">' : '';

			$content .= '<img src="' . esc_url( $course_image ) . '" class="course-media-img"></img>';

			$content .= ! empty( $wrapper ) ? '</' . $wrapper . '>' : '';
			$content .= '</div>';
		}

		return $content;
	}
	/**
	 * Muestra la descripción del curso.
	 *
	 * @since 1.0.0
	 * @param  array $atts Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public static function course_description_prometeo( $atts ) {
		
		extract( shortcode_atts( array(
			'course_id' => CoursePress_Helper_Utility::the_course( true ),
			'class' => '',
			'label' => '',
		), $atts, 'course_description' ) );

		$course_id = (int) $course_id;
		if ( empty( $course_id ) ) { return ''; }
		$class = sanitize_html_class( $class );
		$title = sanitize_text_field( $label );
		$title = ! empty( $title ) ? '<h3 class="section-title">' . esc_html( $title ) . '</h3>' : $title;
		$course = get_post( $course_id );

		/**
		 * schema.org
		 */
		$schema = apply_filters( 'coursepress_schema', '', 'description' );

		$content = '<div class="course-description course-description-' . $course_id . ' ' . $class . '"' . $schema . '>';
		$content .= $title;
		$content .= do_shortcode( $course->post_content );
		$content .= '</div>';
		
		// Return the html in the buffer.
		return $content;
	}
	/**
	 * Display course structure.
	 *
	 * @since  1.0.0
	 * @param  array $atts Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public static function course_structure_prometeo( $atts ) {
		$orig_atts = $atts;

		extract( shortcode_atts( array(
			'course_id' => CoursePress_Helper_Utility::the_course( true ),
			'free_text' => __( 'Preview', 'cp' ),
			'free_show' => 'true',
			'free_class' => 'free',
			'show_title' => 'no',
			'show_label' => 'no',
			'label_delimeter' => ': ',
			'label_tag' => 'h2',
			'show_divider' => 'yes',
			'show_estimates' => 'no',
			'label' => __( 'Course Structure', 'cp' ),
			'class' => '',
			'deep' => false,
		), $atts, 'course_structure' ) );

		$course_id = (int) $course_id;
		$free_text = sanitize_text_field( $free_text );
		$show_title = cp_is_true( sanitize_text_field( $show_title ) );
		$show_label = cp_is_true( sanitize_text_field( $show_label ) );
		$free_show = cp_is_true( sanitize_text_field( $free_show ) );
		$show_estimates = cp_is_true( sanitize_text_field( $show_estimates ) );
		$label_delimeter = sanitize_html_class( $label_delimeter );
		$label_tag = sanitize_html_class( $label_tag );
		$show_divider = cp_is_true( sanitize_text_field( $show_divider ) );
		$label = sanitize_text_field( $label );
		$title = ! empty( $label ) ? '<h3 class="section-title">' . esc_html( $label ) . '</h3>' : $label;
		$class = sanitize_html_class( $class );
		$deep = cp_is_true( sanitize_text_field( $deep ) );
		$view_mode = CoursePress_Data_Course::get_setting( $course_id, 'course_view', 'normal' );
		$with_modules = false;
		$counter = 0;

		$content = '';
		if ( empty( $course_id ) ) { return ''; }

		$structure_visible = cp_is_true(
			CoursePress_Data_Course::get_setting( $course_id, 'structure_visible' )
		);

		if ( ! $structure_visible ) { return ''; }

		$time_estimates = cp_is_true( CoursePress_Data_Course::get_setting( $course_id, 'structure_show_duration' ) );

		$preview = CoursePress_Data_Course::previewability( $course_id );
		
		$visibility = CoursePress_Data_Course::structure_visibility( $course_id );
		$structure_level = CoursePress_Data_Course::get_setting( $course_id, 'structure_level' );
		$is_unit_only = 'unit' === $structure_level;

		if ( ! $visibility['has_visible'] ) { return ''; }

		$student_id = is_user_logged_in() ? get_current_user_id() : 0;
		$enrolled = false;
		$student_progress = false;

		if ( ! empty( $student_id ) ) {
			$enrolled = CoursePress_Data_Course::student_enrolled( $student_id, $course_id );
		}
		if ( $enrolled ) {
			$student_progress = CoursePress_Data_Student::get_completion_data( $student_id, $course_id );
		}

		$units = CoursePress_Data_Course::get_units_with_modules( $course_id, array( 'publish' ) );
		$units = CoursePress_Helper_Utility::sort_on_key( $units, 'order' );

		if ( CoursePress_Data_Capabilities::can_update_course( $course_id ) ) {
			$enrolled = true;
		}

		$is_course_available = CoursePress_Data_Course::is_course_available( $course_id );
		$can_update_course = CoursePress_Data_Capabilities::can_update_course( $course_id );
		$enrolled_class = $enrolled ? 'enrolled' : '';
		$o_atts = '';

		foreach ( $orig_atts as $k => $v ) {
			$o_atts .= 'data-' . $k . '="' . esc_attr( $v ) . '"';
		}

		$classes = array(
			'course-structure-block',
			sprintf( 'course-structure-block-%d', $course_id ),
			$enrolled_class,
		);
		$classes[] = $enrolled? 'student-is-enroled' : 'student-not-enroled';

		$content .= sprintf(
			'<div class="%s" data-nonce="%s" data-course="%s" %s>',
			esc_attr( implode( ' ', $classes ) ),
			esc_attr( wp_create_nonce( 'course_structure_refresh' ) ),
			esc_attr( $course_id ),
			$o_atts
		);

		$content .= $title;

		$course_slug = get_post_field( 'post_name', $course_id );

		$content .= '<ul class="tree">';
		$last_unit = 0;
		$counter = 0;

		/**
		 * module
		 * $unitname & $paged - needed for "current" class
		 */
		$unitname = get_query_var( 'unitname' );
		$paged = get_query_var( 'paged' );
		$clickable = true;
		$last_module_id = false;

		foreach ( $units as $unit_id => $unit ) {
			
			$is_unit_visible = CoursePress_Data_Unit::is_unit_structure_visible( $course_id, $unit_id );
			if ( ! $is_unit_visible ) {
				continue;
			}
			//$unit_id, $course_id, $date_format = null, $student_id = 0 
			$the_unit = $unit['unit'];
			
			// AGREGADO POR TOMAS RUIZ PARA QUE APAREZCAN LOS MODULOS UNO POR UNO.
			$release_date_prometeo = CoursePress_Data_Course::strtotime(self::get_unit_availability_date_prometeo( $the_unit->ID, $course_id ));
			$now_prometeo = CoursePress_Data_Course::time_now();
			// PC::debug($release_date_prometeo);
			// PC::debug($now_prometeo);
			// PC::debug($release_date_prometeo > $now_prometeo);
			if ( $release_date_prometeo > $now_prometeo) {
				continue;
			}


			$previous_unit_id = CoursePress_Data_Unit::get_previous_unit_id( $course_id, $the_unit->ID );

			$is_unit_available = $is_course_available ? CoursePress_Data_Unit::is_unit_available( $course_id, $the_unit, $previous_unit_id ) : $is_course_available;

			$unit_link = CoursePress_Core::get_slug( 'courses/', true ) .
				$course_slug . '/' .
				CoursePress_Core::get_slug( 'unit/' ) .
				$unit['unit']->post_name;

			$estimation = CoursePress_Data_Unit::get_time_estimation( $unit_id, $units );

			if ( $last_module_id > 0 && $clickable ) {
				// Check if the last module is already answered.
				$is_last_module_done = CoursePress_Data_Module::is_module_done_by_student( $last_module_id, $student_id );

				if ( ! $is_last_module_done ) {
					$clickable = false;
				}
			}

			$unit_title = ( $is_unit_available && $enrolled && $clickable ) || $can_update_course ? '<a href="' . esc_url( $unit_link ) . '">' . esc_html( $unit['unit']->post_title ) . '</a>' : '<span>' . esc_html( $unit['unit']->post_title ) . '</span>';

			$is_current_unit = false;
			$classes = array( 'unit' );
			if ( $unitname == $unit['unit']->post_name ) {
				$classes[] = 'current-unit';
				$is_current_unit = true;
			}

			$content .= sprintf( '<li class="%s">', implode( ' ', $classes ) );

			// if ( $can_update_course ) {
				$content .= '<span class="fold"></span>';
			// }

			/**
			 * add enroled information to wrapper
			 */
			$content .= sprintf(
				'<div class="unit-title-wrapper" data-student-is-enroled="%d">',
				esc_attr( $enrolled )
			);
			$content .= '<div class="unit-title">' . $unit_title . '</div>';

			// $show_structure = false;
			$show_structure = true;

			// if (
			// 	$free_show
			// 	&& isset( $preview['structure'][ $unit_id ] )
			// 	&& is_array( $preview['structure'][ $unit_id ] )
			// 	&& isset( $preview['structure'][ $unit_id ]['unit_has_previews'] )
			// 	&& cp_is_true( $preview['structure'][ $unit_id ]['unit_has_previews'] )
			// ) {
			// 	if ( empty( $last_unit ) ) {
			// 		$unit_available = true;
			// 	} else {
			// 		$unit_available = CoursePress_Data_Unit::is_unit_available( $course_id, $unit_id, $last_unit );
			// 	}
			// 	if ( $unit_available ) {
			// 		$content .= '<div class="unit-link"><a href="' . esc_url( $unit_link ) . '">' . $free_text . '</a></div>';
			// 		$show_structure = true;
			// 	}
			// }
			$content .= '</div>';

			if (
				! $show_structure
				&& (
					( ! $can_update_course && $is_unit_only )
					|| ( ! $is_unit_available && ! $can_update_course )
					|| ( ! $clickable && ! $can_update_course )
				)
			) {
				continue;
			}

			if ( ! isset( $unit['pages'] ) ) {
				$unit['pages'] = array();
			}
			
			if ( ! $show_structure && false === $enrolled && false === $can_update_course ) {
				continue;
			}
			
			$content .= '<ul class="unit-structure-modules">';
			$count = 0;
			ksort( $unit['pages'] );
			
			//Secciones
			foreach ( $unit['pages'] as $key => $page ) {
				
				
				// Hide pages if it is not set as visible
				$show_page = CoursePress_Data_Unit::is_page_structure_visible( $course_id, $unit_id, $key, $student_id );
				
				if ( false === $enrolled && false === $can_update_course ) {
					if (
						! isset( $preview['structure'][ $unit_id ] )
						|| ! is_array( $preview['structure'][ $unit_id ] )
						|| ! isset( $preview['structure'][ $unit_id ][ $key ] )
						|| ! is_array( $preview['structure'][ $unit_id ][ $key ] )
						|| ! isset( $preview['structure'][ $unit_id ][ $key ]['page_has_previews'] )
						|| ! cp_is_true( $preview['structure'][ $unit_id ][ $key ]['page_has_previews'] )
					) {
						// PC::debug('llego aqui');
						// continue;
					}
				}

				//	if ( empty( $show_page ) ) { continue; }

				$count += 1;
				$page_link = trailingslashit( $unit_link ) . 'page/' . $key;
		
		
				$page_title = empty( $page['title'] ) ? sprintf( __( 'Untitled Page %s', 'cp' ), $count ) : $page['title'];
				$page_title = $enrolled ? '<a href="' . esc_url( $page_link ) . '">' . esc_html( $page_title ) . '</a>' : esc_html( $page_title );

				$classes = array(
					'unit-page',
					sprintf( 'unit-page-%d', $count ),
				);
				if ( $is_current_unit && $paged == $count ) {
					$classes[] = 'current-unit-page';
				}

				if ( $last_module_id > 0 && $clickable ) {
					// Check if the last module is already answered.
					$is_last_module_done = CoursePress_Data_Module::is_module_done_by_student( $last_module_id, $student_id );

					if ( ! $is_last_module_done ) {
						$clickable = false;
					}
				}

				if ( ! $clickable && ! $can_update_course ) {
					$page_title = sprintf( '<span>%s</span>', strip_tags( $page_title ) );
				}

				$content .= sprintf( '<li class="%s">', implode( ' ', $classes ) );
				
				/**
				 * page is visible?
				 */
				$heading_visible = isset( $page['visible'] ) && $page['visible'];

				if ( $heading_visible && ! empty( $page['modules'] ) ) {
					$preview_class = ( $free_show && ! $enrolled && ! empty( $preview['structure'][ $unit_id ] ) && is_array( $preview['structure'][ $unit_id ] ) ) ? $free_class : '';
					$content .= '<div class="unit-page-title-wrapper ' . esc_attr( $preview_class ) . '">';
					$content .= '<div class="unit-page-title">' . $page_title . '</div>';
					
					// if ( $free_show && ! $enrolled && ! empty( $preview['structure'][ $unit_id ] ) && is_array( $preview['structure'][ $unit_id ] ) ) {
					// 	$content .= '<div class="unit-page-link"><a href="' . esc_url( $page_link ) . '">' . $free_text . '</a></div>';
					// }

					if ( $time_estimates ) {
						$page_estimate = ! empty( $estimation['pages'][ $key ]['components']['hours'] ) ? str_pad( $estimation['pages'][ $key ]['components']['hours'], 2, '0', STR_PAD_LEFT ) . ':' : '';
						$page_estimate = isset( $estimation['pages'][ $key ]['components']['minutes'] ) ? $page_estimate . str_pad( $estimation['pages'][ $key ]['components']['minutes'], 2, '0', STR_PAD_LEFT ) . ':' : $page_estimate;
						$page_estimate = isset( $estimation['pages'][ $key ]['components']['seconds'] ) ? $page_estimate . str_pad( $estimation['pages'][ $key ]['components']['seconds'], 2, '0', STR_PAD_LEFT ) : '';
						$page_estimate = apply_filters( 'coursepress_page_estimation', $page_estimate, $estimation['pages'][ $key ] );
						$content .= '<div class="unit-page-estimate">' . esc_html( $page_estimate ) . '</div>';
					}

					$content .= '</div>';
				}

				if ( $enrolled && ! $clickable && ! $can_update_course ) {
					continue;
				}

				// Add Module Level.
				$structure_level = CoursePress_Data_Course::get_setting( $course_id, 'structure_level', 'unit' );
				if ( $deep || 'section' === $structure_level || 'unit' === $structure_level ) {
					$visibility_count = 0;
					$list_content = '<ul class="page-modules">';
					$prev_module_id = 0;

					foreach ( $page['modules'] as $m_key => $module ) {
						
						// Hide module if not set as visible
						$is_module_visible = CoursePress_Data_Unit::is_module_structure_visible( $course_id, $unit_id, $m_key, $student_id );
						if ( ! $is_module_visible ) {
							continue;
						}

						$classes = array(
							'module',
							sprintf( 'module-%d', $module->ID ),
						);
						$list_content .= sprintf( '<li class="%s">', implode( ' ', $classes ) );


//MODIFICADO POR TOMAS RUIZ: COLOQUE -1 A EL $KEY PARA HACER REFERENCIA A LA SECCION CORRECTA.
						$preview_class = ( $free_show && ! $enrolled && ! empty( $preview['structure'][ $unit_id ] ) && ! empty( $preview['structure'][ $unit_id ][ $key-1 ] ) && ! empty( $preview['structure'][ $unit_id ][ $key-1 ][ $m_key ] ) ) ? $free_class : '';
						$type_class = get_post_meta( $m_key, 'module_type', true );

						$attributes = CoursePress_Data_Module::attributes( $m_key );

						/**
						 * do not show title
						 */
						$show_title = isset( $attributes['show_title'] ) && cp_is_true( $attributes['show_title'] );
						if ( ! $show_title ) {
							continue;
						}

						$attributes['course_id'] = $course_id;

						// Get completion states
						$module_seen = CoursePress_Helper_Utility::get_array_val( $student_progress, 'completion/' . $unit_id . '/modules_seen/' . $m_key );
						$module_passed = CoursePress_Helper_Utility::get_array_val( $student_progress, 'completion/' . $unit_id . '/passed/' . $m_key );
						$module_answered = CoursePress_Helper_Utility::get_array_val( $student_progress, 'completion/' . $unit_id . '/answered/' . $m_key );

						$seen_class = isset( $module_seen ) && ! empty( $module_seen ) ? 'module-seen' : '';
						$passed_class = isset( $module_passed ) && ! empty( $module_passed ) && $attributes['assessable'] ? 'module-passed' : '';
						$answered_class = isset( $module_answered ) && ! empty( $module_answered ) && $attributes['mandatory'] ? 'not-assesable module-answered' : '';
						$completed_class = isset( $module_passed ) && ! empty( $module_passed ) && $attributes['assessable'] && $attributes['mandatory'] ? 'module-completed' : '';
						$completed_class = empty( $completed_class ) && isset( $module_passed ) && ! empty( $module_answered ) && ! $attributes['assessable'] && $attributes['mandatory'] ? 'module-completed' : '';

						if ( $prev_module_id > 0 ) {
							$is_done = CoursePress_Data_Module::is_module_done_by_student( $prev_module_id, $student_id );
							if ( false === $is_done ) {
								$clickable = false;
							} else {
								$last_module_id = $m_key;
							}
						}
						$prev_module_id = $m_key;

						$list_content .= '
							<div class="unit-page-module-wrapper ' . $preview_class . ' ' . $type_class . ' ' . $passed_class . ' ' . $answered_class . ' ' . $completed_class . ' ' . $seen_class . '">
							';
						$module_link = trailingslashit( $unit_link ) . 'page/' . $key . '/module_id/' . $m_key;
						$module_title = $module->post_title;
						$module_title = $enrolled ? '<a href="' . esc_url( $module_link ) . '">' . esc_html( $module_title ) . '</a>' : esc_html( $module_title );

						if ( ! $clickable && ! $can_update_course ) {
							$module_title = sprintf( '<span>%s</span>', $module->post_title );
						}


						$visibility_count += 1;
						$list_content .= sprintf(
							'<div class="module-title" data-title="%s">%s',
							esc_attr__( 'Preview', 'cp' ),
							$module_title
						);						
//AQUI ES DONDE SE COLOCA EL PREVIEW EN EL MODULO.
//ESTABA INICIALMENTE ANTES DEL $visibility_count += 1; 6 LINEAS ARRIBA, LO MOVI AQUI PARA MOSTRARLO MEJOR.
//MODIFICADO POR TOMAS RUIZ: COLOQUE -1 A EL $KEY PARA HACER REFERENCIA A LA SECCION CORRECTA.
						if ( 'focus' == $view_mode && $free_show && ! $enrolled && ! empty( $preview['structure'][ $unit_id ] ) && ! empty( $preview['structure'][ $unit_id ][ $key-1 ] ) && ! empty( $preview['structure'][ $unit_id ][ $key-1 ][ $m_key ] ) ) {
							$module_link = preg_replace( '/#module-/', '/module_id/', $module_link );
							// $list_content .= '<spam class="unit-module-preview-link"><a href="' . esc_url( $module_link ) . '">' . $free_text . '</a></spam>';
							// $list_content .= '<div class="unit-module-preview-link"><a href="' . esc_url( $module_link ) . '">' . $free_text . '</a></div>';
						}
						$list_content .= '</div>';
						$list_content .= '</div>';
						$list_content .= '</li>';
					}
					$list_content .= '</ul>'; // Modules

					if ( ! empty( $visibility_count ) ) {
						$content .= $list_content;
					}
				}

				$content .= '</li>'; // Page Title
			}
			$content .= '</ul>';

			$content .= '</li>'; // Unit

			$last_unit = $unit_id;
		}

		$content .= '</ul>';
		$content .= '</div>';

		return $content;
	}

	public static function get_unit_availability_date_prometeo( $unit_id, $course_id, $date_format = null, $student_id = 0 ) {
		if ( empty( $student_id ) ) {
			$student_id = get_current_user_id();
		}
		$is_open_ended = CoursePress_Data_Course::get_setting( $course_id, 'course_open_ended' );
		$course_start = CoursePress_Data_Course::get_setting( $course_id, 'course_start_date' );
		$course_end = CoursePress_Data_Course::get_setting( $course_id, 'course_end_date' );
		$start_date = CoursePress_Data_Course::strtotime( $course_start ); // Converts date to UTC.
		$end_date = CoursePress_Data_Course::strtotime( $course_end ); // Converts date to UTC.
		$is_open_ended = cp_is_true( $is_open_ended );

		// Use common current timestamp for CP
		$always_return_date = false; // Return empty value if unit is available!
		if ( empty( $date_format ) ) {
			$date_format = get_option( 'date_format' );
		} else {
			$always_return_date = true; // Return formatted date, even when unit is available.
		}
		$now = CoursePress_Data_Course::time_now();
		$is_available = true;
		$availability_date = '';
		$return_date = $start_date; // UTC value.

		// Check for course start/end dates.
		if ( $now < $start_date ) {
			// 1. Start date reached?
			$is_available = false;
			$availability_date = date_i18n( $date_format, $start_date );
		} elseif ( ! $is_open_ended && $now > $end_date ) {
			// 2. End date reached?
			// Check if student is currently enrolled
			$is_student = CoursePress_Data_Course::student_enrolled( $student_id, $course_id );
			
			if ( $is_student ) {
				$is_available = true;
			} else {
				$is_available = false;
				$availability_date = 'expired';
			}
		}
		
		// Course is active today, so check for unit-specific limitations.
		$status_type = get_post_meta( $unit_id, 'unit_availability', true );
		// PC::debug($unit_id);
		// PC::debug($status_type);
	
		if ( 'after_delay' == $status_type ) {
			$delay_val = get_post_meta( $unit_id, 'unit_delay_days', true );
			$delay_days = (int) $delay_val;

			if ( $delay_days > 0 ) {
				// MODIFICADO POR TOMAS RUIZ PARA MOSTRAR LOS MODULOS A MEDIDA QUE LOS LIBERAS
				// Delay is added to the base-date. In future this could be
				// changed to enrollment date or completion of prev-unit, etc.
				// $base_date = CoursePress_Data_Course::strtotime( $course_start ); // UTC value.
				$previous_unit_id = CoursePress_Data_Unit::get_previous_unit_id( $course_id, $unit_id );
				$key = 'enrolled_course_date_' . $course_id;
				$enrolled_date = get_user_option( $key, $student_id );
				$base_date = CoursePress_Data_Course::strtotime($enrolled_date);
				// PC::debug($base_date);
				// $base_date = CoursePress_Data_Course::strtotime( $course_start ); // UTC value.
				$release_date = $base_date + ($delay_days * DAY_IN_SECONDS);
				$return_date = $release_date; // UTC value.
				$availability_date = $release_date; // UTC value.

				// if ( $now < $release_date ) {
				// 	$is_available = false;
				// 	$availability_date = date_i18n( $date_format, $release_date );
				// }
			}
		} elseif ( 'on_date' == $status_type ) {
			$due_on = get_post_meta( $unit_id, 'unit_date_availability', true );
			$due_date = CoursePress_Data_Course::strtotime( $due_on ); // UTC value.
			$return_date = $due_date; // UTC value.

			// Unit-Start date reached?
			if ( $now < $due_date ) {
				$is_available = false;
				$availability_date = date_i18n( $date_format, $due_date );
			}
		}

		if ( $always_return_date ) {
			return $return_date;
		}

		return $availability_date;
	}

	public static function courses_student_dashboard_prometeo($atts){
		global $ultimatemember;
		$profile_id = um_profile_id();
		$username = get_userdata(um_profile_id())->user_login;
		$tab = $ultimatemember->profile->active_tab();
		$args = array(
			'name'             => $tab,
			'post_type'        => 'um_tab',
			'numberposts'      => 1,
			'suppress_filters' => 0,
		);
		$main_tab 	 = get_posts( $args );
		$post_id     = pp_lang_tab_id( $main_tab[0]->ID );
		$meta        = get_post_meta( $post_id );
		$show_roles  = array();
		if ( isset( $meta['_pp_show_roles'] ) ) {
			$show_roles = maybe_unserialize( $meta['_pp_show_roles'][0] );
		}
		um_fetch_user($profile_id);

		// $student_id = get_current_user_id();
		$student_id = $profile_id;
		$student_courses = CoursePress_Data_Student::get_enrolled_courses_ids( $student_id );

		$content = '
			<div class="student-dashboard-wrapper">';

		// Instructor Course List
		$show = 'dates,class_size';
		$course_list = do_shortcode( '[course_list_prometeo prometeo_button="true" show_browse_courses="false" instructor="' . $student_id . '" instructor_msg="" status="all" title_tag="h1" title_class="h1-title" list_wrapper_before="" show_divider="yes"  left_class="enroll-box-left" right_class="enroll-box-right" course_class="enroll-box" title_link="no" show="' . $show . '" show_title="no" admin_links="true" show_button="no" show_media="no"]' );
		// Si el perfil no es de un profesor y el usuario no esta logeado
		if (empty( $course_list ) && !is_user_logged_in()){
			$signup_redirect = apply_filters(
				'coursepress_signup_redirect_for_guest',
				! CP_IS_CAMPUS
			);

			if ( $signup_redirect ) {
				if ( CoursePress_Core::get_setting( 'general/use_custom_login' ) ) {
					$url = CoursePress_Core::get_slug( 'signup', true );
				} else {
					$url = wp_login_url();
				}

				wp_redirect( $url );
				exit;
			}
		}

		if ( ! empty( $course_list ) ) {
			$content .= '
				<div class="dashboard-managed-courses-list">
					<h1 class="title managed-courses-title">' . esc_html__( 'Courses you manage:', 'cp' ) . '</h1>
					<div class="course-list course-list-managed course course-student-dashboard">' .
					$course_list . '
					</div>
				</div>
				<div class="clearfix"></div>
			';
		}

		// Agregado por Tomás Ruiz: En caso de ser un profesor accesado por cualquier otro usuario, solo mostrar los cursos que gestiona pero no los que cursa.
		if (in_array($ultimatemember->user->get_role(), $show_roles) && get_current_user_id() != $student_id) {
			$content .= '
				</div>
			';
			return $content;
		}


		$course_list = do_shortcode( '[course_list_prometeo prometeo_button="true" show_browse_courses="false"  student="' . $student_id . '" student_msg="" status="incomplete" list_wrapper_before="" class="course course-student-dashboard" left_class="enroll-box-left" right_class="enroll-box-right" course_class="enroll-box" title_class="h1-title" title_link="no" show_media="no"]' );

		// Add some random courses.
		$show_random_courses = true;
		if ( empty( $course_list ) && $show_random_courses ) {
			// Random Courses
			$content .= '
				<div class="dashboard-random-courses-list">
					<h3 class="title suggested-courses">' . __( 'You are not enrolled in any courses.', 'cp' ) . '</h3>' .
					// esc_html__( 'Here are a few to help you get started:', 'cp' ) . '
					// <hr />
					// <div class="dashboard-random-courses">' . do_shortcode( '[course_random number="3" featured_title="" media_type="image"]' ) . '</div>
				'</div>
			';
		} else {
			// Course List
			$content .= '
				<div class="dashboard-current-courses-list">
					<h1 class="title enrolled-courses-title current-courses-title">' . __( 'Your current courses:', 'cp' ) . '</h1>
					<div class="course-list course-list-current course course-student-dashboard">' .
					$course_list . '
					</div>
				</div>
				<div class="clearfix"></div>
			';
		}
		// Completed courses
		$course_list = do_shortcode( '[course_list_prometeo prometeo_button="true" show_browse_courses="false"  student="' . $student_id . '" student_msg="" status="completed" list_wrapper_before="" title_link="no" title_tag="h1" title_class="h1-title" show_divider="yes" left_class="enroll-box-left" right_class="enroll-box-right"]' );
		if ( ! empty( $course_list ) ) {
			// Course List
			$content .= '
				<div class="dashboard-completed-courses-list">
					<h1 class="title completed-courses-title">' . __( 'Completed courses:', 'cp' ) . '</h1>
					<div class="course-list course-list-completed course course-student-dashboard">' .
					$course_list . '
					</div>
				</div>
				<div class="clearfix"></div>
			';
		}

		$content .= '
			</div>
		';
		return $content;
	}

	public static function course_unit_archive_submenu_prometeo( $atts ) {
		extract( shortcode_atts(
			array(
				'course_id' => CoursePress_Helper_Utility::the_course( true ),
			),
			$atts,
			'course_unit_archive_submenu'
		) );

		$course_id = (int) $course_id;

		if ( empty( $course_id ) ) { return ''; }

		$subpage = CoursePress_Helper_Utility::the_course_subpage();
		$course_status = get_post_status( $course_id );
		$course_base_url = CoursePress_Data_Course::get_course_url( $course_id );

		$content = '
		<div class="submenu-main-container cp-submenu">
			<ul id="submenu-main" class="submenu nav-submenu">
				<li class="submenu-item submenu-units ' . ( 'units' == $subpage ? 'submenu-active' : '' ) . '"><a href="' . esc_url_raw( $course_base_url . CoursePress_Core::get_slug( 'unit/' ) ) . '" class="course-units-link">' . esc_html__( 'Units', 'cp' ) . '</a></li>
		';

		$student_id = is_user_logged_in() ? get_current_user_id() : false;
		$enrolled = ! empty( $student_id ) ? CoursePress_Data_Course::student_enrolled( $student_id, $course_id ) : false;
		$instructors = CoursePress_Data_Course::get_instructors( $course_id );
		$is_instructor = in_array( $student_id, $instructors );

		if ( $enrolled || $is_instructor ) {
			$content .= '
				<li class="submenu-item submenu-notifications ' . ( 'notifications' == $subpage ? 'submenu-active' : '' ) . '"><a href="' . esc_url_raw( $course_base_url . CoursePress_Core::get_slug( 'notification' ) ) . '">' . esc_html__( 'Notifications', 'cp' ) . '</a></li>
			';
		}

		$pages = CoursePress_Data_Course::allow_pages( $course_id );

		if ( $pages['course_discussion'] && ( $enrolled || $is_instructor ) ) {
			$content .= '<li class="submenu-item submenu-discussions ' . ( 'discussions' == $subpage ? 'submenu-active' : '' ) . '"><a href="' . esc_url_raw( $course_base_url . CoursePress_Core::get_slug( 'discussion' ) ) . '">' . esc_html__( 'Discussions', 'cp' ) . '</a></li>';
		}

		if ( $pages['workbook'] && $enrolled ) {
			$content .= '<li class="submenu-item submenu-workbook ' . ( 'workbook' == $subpage ? 'submenu-active' : '' ) . '"><a href="' . esc_url_raw( $course_base_url . CoursePress_Core::get_slug( 'workbook' ) ) . '">' . esc_html__( 'Workbook', 'cp' ) . '</a></li>';
		}

		if ( $pages['grades'] && $enrolled ) {
			$content .= '<li class="submenu-item submenu-grades ' . ( 'grades' == $subpage ? 'submenu-active' : '' ) . '"><a href="' . esc_url_raw( $course_base_url . CoursePress_Core::get_slug( 'grades' ) ) . '">' . esc_html__( 'Grades', 'cp' ) . '</a></li>';
		}

		$content .= '<li class="submenu-item submenu-info"><a href="' . esc_url_raw( $course_base_url ) . '">' . esc_html__( 'Course Details', 'cp' ) . '</a></li>';

		$show_link = false;

		if ( CP_IS_PREMIUM ) {
			// CERTIFICATE CLASS.
			$show_link = CoursePress_Data_Certificate::is_enabled() && CoursePress_Data_Student::is_enrolled_in_course( $student_id, $course_id );
		}

		if ( is_user_logged_in() && $show_link ) {
			// COMPLETION LOGIC.
			if ( CoursePress_Data_Student::is_course_complete( get_current_user_id(), $course_id ) ) {
				$certificate = CoursePress_Data_Certificate::get_certificate_link( get_current_user_id(), $course_id, __( 'Certificate', 'cp' ) );

				$content .= '<li class="submenu-item submenu-certificate ' . ( 'certificate' == $subpage ? 'submenu-active' : '') . '">' . $certificate . '</li>';
			}
		}

		$content .= '
			</ul>
		</div>
		';

		return $content;
	}
	/**
	 * Shows the course join button.
	 *
	 * @since 1.0.0
	 * @param  array $atts Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public static function course_join_button_prometeo( $atts ) {
		global $coursepress, $enrollment_process_url, $signup_url, $wp_query;

		extract( shortcode_atts( array(
			'course_id' => CoursePress_Helper_Utility::the_course( true ),
			'access_text' => __( 'Start Learning', 'cp' ),
			'class' => '',
			'continue_learning_text' => __( 'Continue Learning', 'cp' ),
			'course_expired_text' => __( 'Not available', 'cp' ),
			'course_full_text' => __( 'Course Full', 'cp' ),
			'details_text' => __( 'Details', 'cp' ),
			'enrollment_closed_text' => __( 'Enrollments Closed', 'cp' ),
			'enrollment_finished_text' => __( 'Enrollments Finished', 'cp' ),
			'enroll_text' => __( 'Enroll Now!', 'cp' ),
			'instructor_text' => __( 'Access Course', 'cp' ),
			'list_page' => false,
			// 'not_started_text' => __( 'Not Available', 'cp' ),
			'not_started_text' => "Aún no inicia",
			'passcode_text' => __( 'Passcode Required', 'cp' ),
			'prerequisite_text' => __( 'Pre-requisite Required', 'cp' ),
			'signup_text' => __( 'Enroll Now!', 'cp' ),
		), $atts, 'course_join_button' ) );

		/**
		 * Check course ID
		 */
		$course_id = (int) $course_id;
		if ( empty( $course_id ) ) {
			return '';
		}

		/**
		 * check course
		 */
		$is_course = CoursePress_Data_Course::is_course( $course_id );
		if ( ! $is_course ) {
			return '';
		}
		
		$course = get_post( $course_id );

		$list_page = sanitize_text_field( $list_page );
		$list_page = cp_is_true( $list_page );
		$class = sanitize_html_class( $class );

		$course_url = CoursePress_Data_Course::get_course_url( $course_id );
		$can_update_course = CoursePress_Data_Capabilities::can_update_course( $course_id );

		
		if ( $can_update_course ) {
			$enroll_text = __( 'Enroll', 'cp' );
		}

		$now = CoursePress_Data_Course::time_now();
		$general_settings = CoursePress_Core::get_setting( 'general' );

		$course->enroll_type = CoursePress_Data_Course::get_setting( $course_id, 'enrollment_type' );
		$course->course_start_date = CoursePress_Data_Course::get_setting( $course_id, 'course_start_date' );
		$course->course_start_date = CoursePress_Data_Course::strtotime( $course->course_start_date );
		$course->course_end_date = CoursePress_Data_Course::get_setting( $course_id, 'course_end_date' );
		$course->enrollment_start_date = CoursePress_Data_Course::get_setting( $course_id, 'enrollment_start_date' );
		$course->enrollment_end_date = CoursePress_Data_Course::get_setting( $course_id, 'enrollment_end_date' );
		$course->open_ended_course = cp_is_true( CoursePress_Data_Course::get_setting( $course_id, 'course_open_ended' ) );
		$course->open_ended_enrollment = cp_is_true( CoursePress_Data_Course::get_setting( $course_id, 'enrollment_open_ended' ) );
		$course->prerequisite = CoursePress_Data_Course::get_prerequisites( $course_id );
		$course->is_paid = cp_is_true( CoursePress_Data_Course::get_setting( $course_id, 'payment_paid_course' ) );
		$course->course_started = ! $course->open_ended_course && ! empty( $course->course_end_date ) && CoursePress_Data_Course::strtotime( $course->course_start_date ) <= $now ? true : false;
		$course->enrollment_started = CoursePress_Data_Course::strtotime( $course->enrollment_start_date ) <= $now ? true : false;
		$course->course_expired = ! $course->open_ended_course && ! empty( $course->course_end_date ) && CoursePress_Data_Course::strtotime( $course->course_end_date ) <= $now ? true : false;
		$course->enrollment_expired = ! empty( $course->enrollment_end_date ) && CoursePress_Data_Course::strtotime( $course->enrollment_end_date ) <= $now ? true : false;
		$course->full = CoursePress_Data_Course::is_full( $course_id );
		$course_progress = 0;

		$button = '';
		$button_option = '';
		$button_url = $enrollment_process_url;
		$is_form = false;

		$student_enrolled = false;
		$student_id = false;
		$is_instructor = false;
		$is_custom_login = cp_is_true( $general_settings['use_custom_login'] );
		$course_link = esc_url( trailingslashit( get_permalink( $course_id ) ) . trailingslashit( CoursePress_Core::get_setting( 'slugs/units', 'units' ) ) );
		$continue_learning_link = null;

		if ( is_user_logged_in() ) {
			$student_id = get_current_user_id();
			$student_enrolled = CoursePress_Data_Course::student_enrolled( $student_id, $course_id );
			// $is_instructor = CoursePress_Data_Instructor::is_assigned_to_course( $course_id, $student_id );
			// MODIFICADO POR TOMAS RUIZ PORQUE .... NO FUNCIONABA
			$is_instructor = in_array($student_id, CoursePress_Data_Course::get_instructors($course_id));

			$course_progress = CoursePress_Data_Student::get_course_progress( $student_id, $course_id );
			if ( 100 === $course_progress ) {
				$continue_learning_text = __( 'Completed', 'cp' );
				$class .= ' course-completed-button';
			} else {
				$meta_key = CoursePress_Data_Course::get_last_seen_unit_meta_key( $course_id );
				$last_seen_unit = get_user_meta( $student_id, $meta_key, true );
				if ( is_array( $last_seen_unit ) && isset( $last_seen_unit['unit_id'] ) && isset( $last_seen_unit['page'] ) ) {

					$is_unit = CoursePress_Data_Unit::is_unit( $last_seen_unit['unit_id'] );
					if ( $is_unit ) {
						$continue_learning_link = $course_link = CoursePress_Data_Unit::get_url( $last_seen_unit['unit_id'], $last_seen_unit['page'] );
					}
				}
			}
		} else {
			$course_url = add_query_arg(
				array(
					'action' => 'enroll_student',
					'_wpnonce' => wp_create_nonce( 'enroll_student' ),
				),
				$course_url
			);
			if ( false === $is_custom_login ) {
				$signup_url = wp_login_url( $course_url );
			} else {
				$signup_url = CoursePress_Core::get_slug( 'login/', true );
				$signup_url = add_query_arg(
					array(
						'redirect_to' => urlencode( $course_url ),
						'_wpnonce' => wp_create_nonce( 'redirect_to' ),
					),
					$signup_url
				);
			}
		}

		$is_single = CoursePress_Helper_Utility::$is_singular;
		$buttons = apply_filters(
			'coursepress_course_enrollment_button_options',
			array(
				'full' => array(
					'label' => sanitize_text_field( $course_full_text ),
					'attr' => array(
						'class' => 'apply-button apply-button-full ' . $class,
					),
					'type' => 'label',
				),
				'expired' => array(
					'label' => sanitize_text_field( $course_expired_text ),
					'attr' => array(
						'class' => 'apply-button apply-button-finished ' . $class,
					),
					'type' => 'label',
				),
				'enrollment_finished' => array(
					'label' => sanitize_text_field( $enrollment_finished_text ),
					'attr' => array(
						'class' => 'apply-button apply-button-enrollment-finished ' . $class,
					),
					'type' => 'label',
				),
				'enrollment_closed' => array(
					'label' => sanitize_text_field( $enrollment_closed_text ),
					'attr' => array(
						'class' => 'apply-button apply-button-enrollment-closed ' . $class,
					),
					'type' => 'label',
				),
				'enroll' => array(
					'label' => sanitize_text_field( $enroll_text ),
					'attr' => array(
						'class' => $can_update_course ? 'apply-button' : 'apply-button enroll ' . $class,
						'data-link' => esc_url( $signup_url . '?course_id=' . $course_id ),
						'data-course-id' => $course_id,
					),
					'type' => 'form_button',
				),
				'signup' => array(
					'label' => sanitize_text_field( $signup_text ),
					'attr' => array(
						'class' => 'apply-button signup ' . ( $is_custom_login ? 'cp-custom-login ' : '' ) . $class,
						'data-link-old' => $signup_url,//esc_url( $signup_url . '?course_id=' . $course_id ),
						'data-course-id' => $course_id,
						'data-link' => $signup_url,
					),
					'type' => 'link',
				),
				'details' => array(
					'label' => sanitize_text_field( $details_text ),
					'attr' => array(
						'class' => 'apply-button apply-button-details ' . $class,
						'data-link' => esc_url( $course_url ),
					),
					'type' => 'button',
				),
				'prerequisite' => array(
					'label' => sanitize_text_field( $prerequisite_text ),
					'attr' => array(
						'class' => 'apply-button apply-button-prerequisite ' . $class,
					),
					'type' => 'label',
				),
				'passcode' => array(
					'label' => sanitize_text_field( $passcode_text ),
					'button_pre' => '<div class="passcode-box"><label>' . esc_html( $passcode_text ) . ' <input type="password" name="passcode" /></label></div>',
					'attr' => array(
						'class' => 'apply-button apply-button-passcode ' . $class,
					),
					'type' => 'form_submit',
				),
				'not_started' => array(
					'label' => sanitize_text_field( $not_started_text ),
					'attr' => array(
						'class' => 'apply-button apply-button-not-started  ' . $class,
					),
					'type' => 'label',
				),
				'access' => array(
					'label' => ! $is_instructor ? sanitize_text_field( $access_text ) : sanitize_text_field( $instructor_text ),
					'attr' => array(
						'class' => 'apply-button apply-button-enrolled apply-button-first-time ' . $class,
						'data-link' => $course_link,
					),
					'type' => 'link',
				),
				'continue' => array(
					'label' => ! $is_instructor ? sanitize_text_field( $continue_learning_text ) : sanitize_text_field( $instructor_text ),
					'attr' => array(
						'class' => 'apply-button apply-button-enrolled ' . $class,
						// MODIFICADO POR TOMAS RUIZ PARA QUE UNA VEZ TERMINADO EL CURSO, EL BOTON AMARILLO QUE DICE TERMINADO LLEVE A LA LISTA DE UNIDADES
						// 'data-link' => empty( $continue_learning_link )? CoursePress_Data_Student::get_last_visited_url( $course_id ) : $continue_learning_link,
						'data-link' => empty( $continue_learning_link )? $course_link : $continue_learning_link,
					),
					'type' => 'link',
				),
			),
			$course_id
		);
		
		$buttons = apply_filters( 'coursepress_coursetemplate_join_button', $buttons );

		
		// Determine the button option.
		if ( ! $student_enrolled && ! $is_instructor ) {
			// For vistors and non-enrolled students.
			$enrollment_start_date = CoursePress_Data_Course::strtotime( $course->enrollment_start_date );

			if ( $enrollment_start_date > $now && false === $course->open_ended_enrollment ) {
				// return ''; // Bail do not show the button
			}
			if ( $course->full ) {
				// COURSE FULL.
				$button_option = 'full';
			} elseif ( $course->course_expired && ! $course->open_ended_course ) {
				// COURSE EXPIRED.
				$button_option = 'expired';
			} elseif ( ! $course->enrollment_started && ! $course->open_ended_enrollment && ! $course->enrollment_expired ) {
				// ENROLMENTS NOT STARTED (CLOSED).
				$button_option = 'not_started';
				// $button_option = 'enrollment_closed';
			} elseif ( $course->enrollment_expired && ! $course->open_ended_enrollment ) {
				// ENROLMENTS FINISHED.
				$button_option = 'enrollment_finished';
			} elseif ( 'prerequisite' == $course->enroll_type ) {
				// PREREQUISITE REQUIRED.
				if ( ! empty( $student_id ) ) {
					$pre_course = ! empty( $course->prerequisite ) ? $course->prerequisite : false;
					$enrolled_pre = false;

					$prerequisites = maybe_unserialize( $pre_course );
					$prerequisites = empty( $prerequisites ) ? array() : $prerequisites;
					$prerequisites = is_array( $prerequisites ) ? $prerequisites : array();

					$completed = 0;
					$all_complete = false;

					foreach ( $prerequisites as $prerequisite ) {
						if ( CoursePress_Data_Course::student_enrolled( $student_id, $prerequisite ) && CoursePress_Data_Student::is_course_complete( $student_id, $prerequisite ) ) {
							$completed += 1;
						}
					}

					if ( count( $prerequisites ) == $completed ) {
						$all_complete = true;
					}

					if ( $all_complete ) {
						$button_option = 'enroll';
					} else {
						$button_option = 'prerequisite';
					}
				} else {
					$button_option = 'prerequisite';
				}
			}

			if ( empty( $button_option ) || 'enroll' == $button_option ) {

				$user_can_register = CoursePress_Helper_Utility::users_can_register();

				// Even if user is signed-in, you might wan't to restrict and force an upgrade.
				// Make sure you know what you're doing and that you don't block everyone from enrolling.
				$force_signup = apply_filters( 'coursepress_course_enrollment_force_registration', false );

				if ( ( empty( $student_id ) && $user_can_register && empty( $button_option ) ) || $force_signup ) {
					// If the user is allowed to signup, let them sign up
					$button_option = 'signup';
				} elseif ( ! empty( $student_id ) && empty( $button_option ) ) {

					// If the user is not enrolled, then see if they can enroll.
					switch ( $course->enroll_type ) {
						default:
						case 'anyone':
						case 'registered':
							$button_option = 'enroll';
							break;

						case 'passcode':
							$button_option = 'passcode';
							break;

						case 'prerequisite':
							$pre_course = ! empty( $course->prerequisite ) ? $course->prerequisite : false;
							$enrolled_pre = false;

							$prerequisites = maybe_unserialize( $pre_course );
							$prerequisites = empty( $prerequisites ) ? array() : $prerequisites;

							$completed = 0;
							$all_complete = false;

							foreach ( $prerequisites as $prerequisite ) {
								if ( CoursePress_Data_Course::student_enrolled( $student_id, $prerequisite ) && CoursePress_Data_Student::is_course_complete( $student_id, $course_id ) ) {
									$completed += 1;
								}
							}

							if ( count( $prerequisites ) == $completed ) {
								$all_complete = true;
							}

							if ( $all_complete ) {
								$button_option = 'enroll';
							} else {
								$button_option = 'prerequisite';
							}
							break;
					}
				}
			}
		} else {
			// For already enrolled students.
			$progress = CoursePress_Data_Student::get_course_progress( get_current_user_id(), $course_id );

			if ( $course->course_expired && ! $course->open_ended_course ) {
				// COURSE EXPIRED
				$button_option = 'expired';
			} elseif ( $course->course_start_date > $now ) {
				// COURSE HASN'T STARTED
				$button_option = 'not_started';
				//REMOVIDO POR TOMÁS RUIZ PARA QUE PUEDA SER MOSTRADO EN LA LISTA DE CURSOS.
				// } elseif ( ! $is_single && false === strpos( $_SERVER['REQUEST_URI'], CoursePress_Core::get_setting( 'slugs/student_dashboard', 'courses-dashboard' ) ) ) {
					// 	// SHOW DETAILS | Dashboard
					// 	$button_option = 'details';
				} else {
					if ( 0 < $progress ) {
						$button_option = 'continue';
					} else {
						$button_option = 'access';
					}
				}
			}
			
			// Make the option extendable.
			
		$button_option = apply_filters( 'coursepress_course_enrollment_button_option', $button_option );
		

		// Prepare the button.
		if ( ( ! $is_single && ! is_page() ) || $list_page ) {
			$button_url = get_permalink( $course_id );
			global $post;
			if ( CoursePress_Data_Course::is_course( $post ) ) {
				$button = '<button data-link="' . esc_url( $button_url ) . '" class="apply-button apply-button-details ' . esc_attr( $class ) . '">' . esc_html( $details_text ) . '</button>';
			} else {
				$button = '<a href="' . esc_url( $button_url ) . '" class="apply-button apply-button-details ' . esc_attr( $class ) . '">' . esc_html( $details_text ) . '</a>';
			}
		} else {
			//$button = apply_filters( 'coursepress_enroll_button_content', '', $course );
			if ( empty( $button_option ) || ( 'manually' == $course->enroll_type && ! ( 'access' == $button_option || 'continue' == $button_option ) ) ) {
				// AGREGADO POR TOMÁS RUIZ PARA QUE SE MUESTRE EL BOTÓN CON INSCRIPCIÓN CERRADA EN CASO DE QUE NO ESTÉ ABIERTO EL CURSO AÚN
				// return apply_filters( 'coursepress_enroll_button', $button, $course_id, $student_id, $button_option );
			}

			$button_attributes = '';
			foreach ( $buttons[ $button_option ]['attr'] as $key => $value ) {
				$button_attributes .= $key . '="' . esc_attr( $value ) . '" ';
			}
			$button_pre = isset( $buttons[ $button_option ]['button_pre'] ) ? $buttons[ $button_option ]['button_pre'] : '';
			$button_post = isset( $buttons[ $button_option ]['button_post'] ) ? $buttons[ $button_option ]['button_post'] : '';

			/**
			 * If there is no script, made a regular link instead of button.
			 */
			 $is_wp_script = wp_script_is( 'coursepress-front-js' );
			 if ( empty( $is_wp_script ) ) {
			 	
		 		//REMOVIDO POR TOMÁS RUIZ PARA QUE SE PUEDA MOSTRAR EL BOTON EN LA LISTA DE CURSOS
			 	//*
			 	 //* fix button on shortcode
				 
			 	// if ( 'enroll' == $button_option ) {
			 	// 	$button_option = 'details';
			 	// }
			 	$buttons[ $button_option ]['type'] = 'link';
			 }
			switch ( $buttons[ $button_option ]['type'] ) {
				case 'label':
					$button = '<span ' . $button_attributes . '>' . esc_html( $buttons[ $button_option ]['label'] ) . '</span>';
					break;

				case 'form_button':
					$button = '<button ' . $button_attributes . '>' . esc_html( $buttons[ $button_option ]['label'] ) . '</button>';
					$is_form = true;
					break;

				case 'form_submit':
					$button = '<input type="submit" ' . $button_attributes . ' value="' . esc_attr( $buttons[ $button_option ]['label'] ) . '" />';
					$is_form = true;
					break;

				case 'button':
					$button = '<button ' . $button_attributes . '>' . esc_html( $buttons[ $button_option ]['label'] ) . '</button>';
					break;
				case 'link':
			
					// AGREGADO POR TOMÁS RUIZ PARA INCLUIR LA CLASE PASADA POR PARABMETRO EN EL BOTON
					if (! preg_match('/'.$class.'/', $button_attributes)){
						$button_attributes = preg_replace('/class=\"(.+?)\"/', 'class="$1 '. $class .'"', $button_attributes);
					}
			

					$url = $buttons[ $button_option ]['attr']['data-link'];
					$format = '<a href="%s" %s>%s</a>';
					$button = sprintf( $format, $url, $button_attributes, $buttons[ $button_option ]['label'] );
					break;
			}

			$button = $button_pre . $button . $button_post;
		}

		// Wrap button in form if needed.
		if ( $is_form ) {
			$button = '<form name="enrollment-process" method="post" data-type="'. $button_option . '" action="' . $button_url . '">' . $button;
			$button .= sprintf( '<input type="hidden" name="student_id" value="%s" />', get_current_user_id() );

			if ( 'enroll' == $button_option ) {
				$button .= wp_nonce_field( 'enrollment_process', '_wpnonce', true, false );
			}

			$button .= '<input type="hidden" name="course_id" value="' . $course_id . '" />';
			$button .= '</form>';
		}
		return apply_filters(
			'coursepress_enroll_button',
			$button,
			$course_id,
			$student_id,
			$button_option
		);
	}

	

}

/**
 * Filtra que solo se muestren los instructores con cursos activos.
 *
 * @method filter_active_instructors
 *
 * @param  Array                    $instructors Lista de instructores
 *
 * @return Array                                 Instructores Filtrados
 */
function filter_active_instructors($instructors){
	$result = Array();
	for ($i=0; $i < count($instructors); $i++) { 
	 	if (count(CoursePress_Data_Instructor::get_assigned_courses_ids($instructors[$i]->ID, "publish")) >= 1){
	 		array_push($result, $instructors[$i]);
	 	}
	}
	return $result;
}

/**
 * Devuelve la lista de instructores ordenada con instructores destacados.
 *
 * @method instructors_order
 *
 * @param  [type]            $instructors [description]
 *
 * @return [type]                         [description]
 */	
function instructors_order($instructors){
	function cmpFavorite($a, $b) {
	    if ($a->is_favorited == $b->is_favorited) {
	        return 0;
	    }
		return $a->is_favorited ? -1: 1;
	    // return ($a < $b) ? -1 : 1;
	}
	// Obtener todos los criterios de orden
	for ($i=0; $i < count($instructors); $i++) { 
		$instructors[$i] = (object) array_merge( (array)$instructors[$i], array( 
			'courseCount' => CoursePress_Data_Instructor::get_course_count($instructors[$i]->ID), 
			"is_favorited" => (int)get_the_author_meta( 'is_favorited', $instructors[$i]->ID)
			) );
	}

	// Ordenar
	uasort($instructors, 'cmpFavorite');
	return $instructors;
}



//*******************************************
// ACTIONS
//*******************************************

add_action('comment_post', 'notify_discussion_comments', 10, 4);
function notify_discussion_comments (){
	// Esta dentro de un bloque try, en caso de que cualquier otro comentario en la base de datos no caiga aqui adentro.
	try {

		$args = func_get_args();
		$comment_id = $args[0];
		$student_id = $args[2]["user_id"];
		$discussion_id = $args[2]["comment_post_ID"];
		$parent_comment = $args[2]["comment_parent"];
		$course_id = (int) get_post_meta( $discussion_id, 'course_id', true );
		do_action('coursepress_after_add_comment', $comment_id, $student_id, $discussion_id, $course_id);
	} catch (Exception $e) {
		// en caso de que sea un comentario en cualquier otro area de la aplicación, no hacer nada.
	}
}

//add_action( 'wp_enqueue_scripts', 'register_css' );
add_action( 'wp_print_styles', 'register_css', 1000);

function register_css()
{



	wp_register_style( 'prometeo', plugins_url('prometeo-coursepress/prometeo-coursepress.css'));

	wp_enqueue_style('prometeo');
	wp_enqueue_script('prometeo',  plugins_url('prometeo-coursepress/prometeo-coursepress.js'));
}



/**
 * Register custom query vars
 *
 * @link https://codex.wordpress.org/Plugin_API/Filter_Reference/query_vars
 */
function myplugin_register_query_vars( $vars ) {
	$vars[] = 'tipo';
	$vars[] = 'tema';
	return $vars;
}
add_filter( 'query_vars', 'myplugin_register_query_vars' );




Prometeo_CoursePress::init();
/****************************************************************/
//Filters
/****************************************************************/


add_filter( 'coursepress_template_instructor_page', 'render_instructors_page_prometeo', 10, 3);



function render_instructors_page_prometeo($template, $instructor_id, $a){
	// um_set_requested_user($instructor_id);
	locate_user_profile();
	// $content = '<div class="um um-profile">';
	$content = do_shortcode("[ultimatemember form_id=392]");
	// $content .= '</div>';
	return $content;
}

add_filter('wp_mail_from', function($from_email){
	$sitename = strtolower( $_SERVER['SERVER_NAME'] );
	if ( substr( $sitename, 0, 4 ) == 'www.' ) {
		$sitename = substr( $sitename, 4 );
	}

	$from_email = 'contacto@' . $sitename;
	return $from_email;
});

add_filter( 'wp_mail_from_name', function($from_name){
	$from_name = "Prometeo Online";
	return $from_name;
});

function locate_user_profile() {
	global $post, $ultimatemember;
	$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$instructor_profile_slug = CoursePress_Core::get_setting(
		'slugs/instructor_profile',
		'instructor'
	);
	$dest = preg_replace("/.+?(?:". $instructor_profile_slug .")/", get_permalink($ultimatemember->permalinks->core['user']), $actual_link);
	
	// get_permalink($ultimatemember->permalinks->core['user'])
	wp_redirect($dest);
	//if ( um_queried_user() ) { // && um_is_core_page('user')

		// if ( um_get_option('permalink_base') == 'user_login' ) {

		// 	$user_id = username_exists( um_queried_user() );

		// 	// Try nice name
		// 	if ( !$user_id ) {

		// 		$slug = um_queried_user();
		// 		$slug = str_replace('.','-',$slug);
		// 		$the_user = get_user_by( 'slug', $slug );
		// 		if ( isset( $the_user->ID ) ){
		// 			$user_id = $the_user->ID;
		// 		}
				
		// 		if( !$user_id ){
		// 			$user_id = $ultimatemember->user->user_exists_by_email_as_username( um_queried_user() );
		// 		}

		// 		if( !$user_id ){
		// 			$user_id = $ultimatemember->user->user_exists_by_email_as_username( $slug );
		// 		}

		// 	}

		// }

		// if ( um_get_option('permalink_base') == 'user_id' ) {
		// 	$user_id = $ultimatemember->user->user_exists_by_id( um_queried_user() );

		// }

		// if ( in_array( um_get_option('permalink_base'), array('name','name_dash','name_dot','name_plus') ) ) {
		// 	$user_id = $ultimatemember->user->user_exists_by_name( um_queried_user() );

		// }

		// /** USER EXISTS SET USER AND CONTINUE **/

		// if ( $user_id ) {

		// 	um_set_requested_user( $user_id );

		// 	do_action('um_access_profile', $user_id );

		// } else {

		// 	exit( wp_redirect( um_get_core_page('user') ) );

		// }

	// } else if ( um_is_core_page('user') ) {

	// 	if ( is_user_logged_in() ) { // just redirect to their profile

	// 		$query = $ultimatemember->permalinks->get_query_array();

	// 		$url = um_user_profile_url();

	// 		if ( $query ) {
	// 			foreach( $query as $key => $val ) {
	// 				$url =  add_query_arg($key, $val, $url);
	// 			}
	// 		}

	// 		exit( wp_redirect( $url ) );
	// 	}else{

	// 		$redirect_to = apply_filters('um_locate_user_profile_not_loggedin__redirect', home_url() );
	// 		if( ! empty( $redirect_to ) ){
	// 			exit( wp_redirect( $redirect_to ) );
	// 		}

	// 	}

	// }
}

