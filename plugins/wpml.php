<?php

class Appful_Plugin_WPML {

	var $installed;

	public function __construct() {
		if ( class_exists( 'SitePress' ) ) {
			$this->installed = count( $this->languages() ) > 0;
		}
	}

	public function languages() {
		return array_keys( apply_filters( 'wpml_active_languages', null ) );
	}

	public function post_lang( $post_id ) {
		if ( $this->installed() ) {
			$lang_infos = apply_filters( 'wpml_post_language_details', null, $post_id );

			return $lang_infos["language_code"];
		}

		return null;
	}

	public function installed() {
		return $this->installed;
	}

	public function filterVar( $key ) {
		if ( $this->installed() ) {
			if ( ! $this->is_default() ) {
				return $key . "_" . $this->current();
			}
		}

		return $key;
	}

	public function filterVarForLang( $key, $lang ) {
		if ( $lang == null ) {
			return $this->filterVar( $key );
		}

		if ( $this->installed() ) {
			if ( $lang != $this->default_language() ) {
				return $key . "_" . $lang;
			}
		}

		return $key;
	}

	public function is_default() {
		return $this->current() == $this->default_language();
	}

	public function current() {
		$wpml_language = apply_filters( 'wpml_current_language', null );
		if ( $wpml_language != null ) {
			return $wpml_language;
		} else {
			return ICL_LANGUAGE_CODE;
		}
	}

	public function default_language() {
		global $sitepress;

		return $sitepress->get_default_language();
	}


}