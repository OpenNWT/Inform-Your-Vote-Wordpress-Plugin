<?php

class Post_Import {

	/**
	* Reads the current csv line.
	*
	* @access public
	* @since 1.0
	* @param csv $csv The csv file.
	* @param array $headings   An array containing all the post headings.
	*/
	static function read_csv_line( $csv, $headings )
	{
		if ( ( $data = fgetcsv( $csv ) ) !== false ) {
			if ( count( $headings ) != count( $data ) ) {
				return false;
			}

			return array_combine( $headings, $data );
		} else {
			return false;
		}
	}

	/**
	* If the term is alerady present, it returns it or creates a new one
	*
	* @access public
	* @since 1.0
	* @param WP_Object $taxonomy
	* @param array $data
	* @param array $term_fields
	* @param array $parent_field
	* @param string $mode
	*/
	static function get_or_create_term( $taxonomy, $data, $term_fields, $parent_field, $mode ) {
		$args = array();
		$term = null;
		$name = $data['name'];
		$slug = isset( $data['slug'] ) ? $data['slug'] : '';
		$description = in_array( 'description', $term_fields ) ? $data['description'] : '';
		if ( empty( $data[$parent_field] ) ) {
			$parent = 0;
		} else {
			$parent_term = get_term_by( 'slug', $data[$parent_field], $taxonomy );
			$parent = $parent_term ? $parent_term->term_id : 0;
		}

		if ( term_exists( $name, $taxonomy ) )
		{
			$term = get_term_by( 'name', $name, $taxonomy, ARRAY_A );
		}

		if ( !empty( $slug ) ) {
			$args['slug'] = $slug;
			$term_slug = get_term_by( 'slug', $slug, $taxonomy, ARRAY_A );
			if ( $term_slug )
			{
				if ( $term ) {
					if ( $term_slug['term_id'] != $term['term_id'] && 'overwrite' == $mode ) {
						return false;
					}
				} else {
					$term = $term_slug;
				}
			}
		}

		if ( !$term ) {
			if ( $description ) {
				$args['description'] = $description;
			}
			if ( $parent ) {
				$args['parent'] = $parent;
			}

			$term = wp_insert_term( $name, $taxonomy, $args );
		} else {
			$args = array( 'name' => $name );
			if ( !empty( $slug ) && 'overwrite' == $mode) {
				$args['slug'] = $slug;
			}
			if ( !empty( $parent ) && ( !$term['parent'] || 'overwrite' == $mode ) ) {
				$args['parent'] = $parent;
			}
			if ( !empty( $description ) && ( !$term['description'] || 'overwrite' == $mode ) ) {
				$args['description'] = $description;
			}
			wp_update_term( $term['term_id'], $taxonomy, $args );

		}
		return $term;
	}

	/**
	* Adds the image data.
	*
	* @access public
	* @since 1.0
	* @param array $data
	* @param string $field_name
	* @param int $post_id
	*/
	static function add_image_data( $data, $field_name, $post_id = 0 ) {
		$attachment_id = 0;
		if ( !empty( $data["{$field_name}_base64"] ) && !empty( $data["{$field_name}_filename"] ) ) {
			$file_name = $data["{$field_name}_filename"];
			$src_name = tempnam( "tmp", "img" );
			$src = fopen( $src_name, 'wb' );
			fputs( $src, base64_decode( $data["{$field_name}_base64"] ) );
			fclose( $src );
		} elseif ( !empty( $data["{$field_name}_url"] ) ) {
			$file_name = $data["{$field_name}_url"];
			$src_name = download_url( $file_name );
			if ( is_wp_error( $src_name ) )
			{
				return 0;
			}
		} else {
			return 0;
		}
		$desc = !empty( $data["{$field_name}_description"] ) ? $data["{$field_name}_description"] : '';
		preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file_name, $matches );
		$file_array = array();
		$file_array['name'] = basename( $matches[0] );
		$file_array['tmp_name'] = $src_name;

		$id = media_handle_sideload( $file_array, $post_id, $desc );

		return $id;
	}

	/**
	* Returns the taxonomy terms.
	*
	* @access public
	* @since 1.0
	* @param array $taxonomies
	* @param array $data
	*/
	static function get_taxonomy_terms( $taxonomies, $data ) {
		$taxonomy_data = array();
		foreach ( $taxonomies as $taxonomy_name => $taxonomy_label ) {
			if ( !empty( $data[$taxonomy] ) ) {
				$term = get_term_by( 'slug', $data[$taxonomy_label], $taxonomy_name );
				$taxonomy_data[$taxonomy_name][] = $term->term_id;
			}
		}

		return $taxonomy_data;
	}

	/**
	* Gets the posts if already exists or creates one.
	*
	* @access public
	* @since 1.0
	* @param string $post_type
	* @param array $current_posts
	* @param array $data
	* @param array $post_fields
	* @param string $post_fields
	*/
	static function get_or_create_post( $post_type, $current_posts, $data, $post_fields, $mode ) {
		global $ed_post_types;
		$posts_by_title = $current_posts['post_title'];
		$posts_by_name = $current_posts['post_name'];
		$post = null;

		if($post_type != $ed_post_types['address'])
		{
			if ( isset( $post_fields['post_name'] ) && !empty( $data[$post_fields['post_name']] ) && !empty( $posts_by_name[$data[$post_fields['post_name']]] ) ) {
				$post = $posts_by_name[$data[$post_fields['post_name']]];
			}
			if ( isset( $post_fields['post_title'] ) && !empty( $data[$post_fields['post_title']] ) && !empty( $posts_by_title[$data[$post_fields['post_title']]] ) ) {
				$posts_title = $posts_by_title[$data[$post_fields['post_title']]];
				if ( count( $posts_title ) == 1 ) {
					if ( $post && $post->ID != $posts_title[0]->ID ) {
						return 0;
					} elseif ( !$post ) {
						$post = $posts_title[0];
					}
				} elseif ( $post && count( $posts_title ) > 1 ) {
					$found = false;
					foreach ( $posts_title as $post_title ) {
						if ( $post_title->ID == $post->ID ) {
							$found = true;
							break;
						}
					}

					if ( !$found )
					{
						return 0;
					}
				} else {
					return 0;
				}
			}
		}


		if ( !$post ) {
			$args = array( 'post_type' => $post_type, 'post_status' => 'publish' );
			foreach ( $post_fields as $post_name => $field_name ) {
				if ( isset( $data[$field_name] ) ) {
					$args[$post_name] = $data[$field_name];
				}
			}

			if ( empty( $args['post_name'] ) ) {
				$args['post_name'] = sanitize_title( isset( $args['post_title'] ) ? $args['post_title'] : '' );
			}

			$post_id = wp_insert_post( $args );
			return get_post( $post_id );
		}

		$args = array( 'post_type' => $post_type, 'ID' => $post->ID );
		$updated = false;
		foreach ( $post_fields as $post_name => $field_name ) {
			if ( 'overwrite' == $mode || empty( $post->$post_name ) ) {
				$args[$post_name] = $data[$field_name];
				$updated = true;
			}
		}

		if ( $updated ) {
			wp_update_post( $post, $args );
		}

		return $post;
	}

	/**
	*	Gets all the current posts.
	*
	* @access public
	* @since 1.0
	* @param string $post_type
	*/
	static function get_current_posts( $post_type ) {
		$query = new WP_Query( array( 'post_type' => $post_type, 'nopaging' => true ) );
		$posts = array('post_title' => array(), 'post_name' => array());
		while ( $query->have_posts() ) {
			$query->the_post();
			$post = $query->post;
			$posts['post_title'][$post->post_title][] = $post;
			$posts['post_name'][$post->post_name] = $post;
		}

		return $posts;
	}

	/**
	*	Applies the defaults to the fields with no value
	*
	* @since 1.0
	* @param array $data
	* @param array $default
	*/
	static function apply_defaults( $data, $defaults ) {
		foreach ( $defaults as $label => $value ) {
			if ( empty( $data[$label] ) ) {
				$data[$label] = $value ;
			}
		}

		return $data;
	}


	/**
	* Imports the posts
	*/
	static function import_post_csv( $csv, $mode, $post_type, $post_meta, $post_fields, $post_image_heading, $taxonomies, $default_values = array(), $required_fields = null ) {
		global $ed_post_types;
		$current_posts = array();
		$headings = fgetcsv( $csv );
		$found = true;
		if ( $required_fields === null ) {
			$required_fields = $post_fields;
		}
		foreach ( $required_fields as $field ) {
			$found &= in_array( $field, $headings );
		}

		if ( !$found ) {
			return false;
		}

		if($post_type != $ed_post_types['address']){
			$current_posts = self::get_current_posts( $post_type );
		}

		while ( ( $data = self::read_csv_line( $csv, $headings ) ) !== false ) {
			$data = self::apply_defaults( $data, $default_values );
			$post = self::get_or_create_post( $post_type, $current_posts, $data, $post_fields, $mode );

			if ( ! $post ) {
				continue;
			}

			if($post_type != $ed_post_types['address']){
				if ( $post_image_heading ) {
					$image_id = get_post_thumbnail_id( $post->ID );
					if ( 'overwrite' == $mode || empty( $image_id ) ) {
						$attachment_id = self::add_image_data( $data, $post_image_heading, $post->ID );
						set_post_thumbnail( $post, $attachment_id );
					}
				}

				foreach ( $taxonomies as $taxonomy_name => $taxonomy_label ) {
					if ( !empty( $data[$taxonomy_label] ) ) {
						$new_term = get_term_by( 'slug', $data[$taxonomy_label], $taxonomy_name );
						$existing_terms = wp_get_post_terms( $post->ID, $taxonomy_name, array( 'fields' => 'ids' ) );
						if ( 'overwite' == $mode || !$existing_terms ) {
							wp_set_object_terms( $post->ID, $new_term->term_id, $taxonomy_name );
						}
					}
				}
			}


			if ( $post_meta ) {
				$post_meta->update_field_values( $post->ID, $data, $mode );
			}

		}
	}

	/**
	* Imports the taxonomies.
	*/
	static function import_taxonomy_csv( $csv, $mode, $taxonomy, $taxonomy_name, $taxonomy_fields, $taxonomy_meta = null, $parent_field = null, $default_values = array(), $required_fields = null ) {
		$headings = fgetcsv( $csv );
		$found = true;
		if ( $required_fields === null ){
			$required_fields = $taxonomy_fields;
			if ( $parent_field )
			{
				$required_fields[] = $parent_field;
			}
		}

		foreach ( $required_fields as $field ) {
			$found &= in_array( $field, $headings ) || isset( $default_values[$field] );
		}

		if ( !$found )
		{
			return false;
		}

		while ( ( $data = self::read_csv_line( $csv, $headings ) ) !== false ) {
			$data = self::apply_defaults( $data, $default_values );
			$term = self::get_or_create_term( $taxonomy_name, $data, $taxonomy_fields, $parent_field, $mode );
			if ( $taxonomy_meta )
			{
				$taxonomy_meta->update_field_values( $term['term_id'], $data, $mode );
			}
		}

		return true;
	}
}
