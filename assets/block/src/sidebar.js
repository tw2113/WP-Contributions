/**
 * Internal block libraries
 */

const { __ } = wp.i18n;

const {
	PluginSidebar,
	PluginSidebarMoreMenuItem
} = wp.editPost;

const {
	PanelBody,
	TextControl
} = wp.components;

const {
	Component,
	Fragment
} = wp.element;

const { withSelect } = wp.data;

const { compose } = wp.compose;

const { registerPlugin } = wp.plugins;

class WP_Contributions_Block extends Component {
	constructor() {
		super( ...arguments );

		this.state = {
			key: '_wp_contributions_my_plugin_field',
			value: '',
		}

		wp.apiFetch( { path: `/wp/v2/posts/${this.props.postId}`, method: 'GET' } ).then(
			( data ) => {
				this.setState( {
					value: data.meta._wp_contributions_my_plugin_field
				} );
				return data;
			},
			( err ) => {
				return err;
			}
		);
	}

	static getDerivedStateFromProps( nextProps, state ) {
		if ( ( nextProps.isPublishing || nextProps.isSaving ) && !nextProps.isAutoSaving ) {
			wp.apiRequest( { path: `/wp-contributions/v1/update-meta?id=${nextProps.postId}`, method: 'POST', data: state } ).then(
				( data ) => {
					return data;
				},
				( err ) => {
					return err;
				}
			);
		}
	}

	render() {
		return (
			<Fragment>
				<PluginSidebarMoreMenuItem
					target="wp-contributions-sidebar"
				>
					{ __( 'WP Contributions My Plugins' ) }
				</PluginSidebarMoreMenuItem>
				<PluginSidebar
					name="wp-contributions-sidebar"
					title={ __( 'WP Contributions My Plugins' ) }
				>
					<PanelBody>
						<TextControl
							label={ __( 'What\'s your plugin slug?' ) }
							value={ this.state.value }
							onChange={ ( value ) => {
								this.setState( {
									value
								} );
							} }
						/>
					</PanelBody>
				</PluginSidebar>
			</Fragment>
		)
	}
}

const HOC = withSelect( ( select, { forceIsSaving } ) => {
	const {
		getCurrentPostId,
		isSavingPost,
		isPublishingPost,
		isAutosavingPost,
	} = select( 'core/editor' );
	return {
		postId: getCurrentPostId(),
		isSaving: forceIsSaving || isSavingPost(),
		isAutoSaving: isAutosavingPost(),
		isPublishing: isPublishingPost(),
	};
} )( WP_Contributions_Block );

registerPlugin( 'wp-contributions-block', {
	icon: 'admin-site',
	render: HOC,
} );