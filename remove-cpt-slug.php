class remove_cpt_slug{
	public $cpt;
	function __construct($cpt) {
		$this->cpt = $cpt;

		add_filter( 'post_type_link', array($this,'cpt_remove_slug'), 10, 3 );
		add_action( 'pre_get_posts', array($this,'cpt_parse_request') );
	}
	function cpt_remove_slug( $post_link, $post, $leavename ) {
		$cpt = $this->cpt;

		if ( $cpt != $post->post_type || 'publish' != $post->post_status ) {
			return $post_link;
		}

		$post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );

		return $post_link;
	}

	function cpt_parse_request( $query ) {
		$cpt = $this->cpt;
		if ( ! $query->is_main_query() || 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
			return;
		}

		if ( ! empty( $query->query['name'] ) ) {
			$query->set( 'post_type', array( 'post', $cpt, 'page' ) );
		}
	}
}
