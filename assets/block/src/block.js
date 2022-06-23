/**
 * Block dependencies
 */

 import classnames from 'classnames';

 /**
  * Internal block libraries
  */
 const { __ } = wp.i18n;

 const { registerBlockType } = wp.blocks;
 const { serverSideRender: ServerSideRender } = wp;
 const {
	 RichText,
	 InspectorControls,
	 BlockControls
 } = wp.editor;

 const {
	 PanelBody,
	 TextControl,
	 Dashicon,
	 Toolbar,
	 Button,
	 Tooltip,
	 SelectControl
 } = wp.components;

 /**
  * Register block wp-contributions/my-plugin
  */
 export default registerBlockType( 'wp-contributions/my-plugin', {
	 title: __( 'WP Contributions' ),
	 description: __( 'Showcase your contributions to Wordpress.' ),
	 category: 'common',
	 icon: 'embed-photo',
	 keywords: [
		 __( 'Wordpress' ),
		 __( 'Plugins' ),
		 __( 'WP Contributions' ),
	 ],
	 attributes: {
		 slug: {
			 type: 'string'
		 },
		 preferred_username: {
			 type: 'string',
		 },
		 theme: {
			 type: 'boolean',
			 default: false,
		 },
		 contribution_type: {
			 type: 'string',
			 default: 'plugin',
		 }
	 },
	 edit: props => {
		 const onChangePlugin = value => {
			 props.setAttributes( { slug: value } );
		 };
		 const onChangeUsername = value => {
			 props.setAttributes( { preferred_username: value } );
		 };
		 const onChangeType = value => {
			 props.setAttributes( { contribution_type: value } );
		 };
		 const toggletheme = value => {
			 props.setAttributes( { theme: !props.attributes.theme } );
		 };
		 return [
			 !! props.isSelected && (
				 <BlockControls key="custom-controls">
					 <Toolbar
						 className='components-toolbar'
					 >
						 <Tooltip text={ __( 'Preview' )	}>
							 <Button
								 className={ classnames(
									 'components-icon-button',
									 'components-toolbar__control',
									 { 'is-active': props.attributes.theme },
								 ) }
								 onClick={ toggletheme }
							 >
								 <Dashicon icon="tablet" />
							 </Button>
						 </Tooltip>
					 </Toolbar>
				 </BlockControls>
			 ),
			 !! props.isSelected && (
				 <InspectorControls key="inspector">
					 <PanelBody title={ __( 'WP Contributions Settings' ) } >
						 <TextControl
							 label={ __( 'Author User' ) }
							 value={ props.attributes.preferred_username }
							 onChange={ onChangeUsername }
						 />
					 </PanelBody>
				 </InspectorControls>
			 ),
			 props.attributes.theme ?
				 <div className={ props.className }>
					 <div className="click-to-send">
						 <div className="ctt-text">
							 <SelectControl
								 className="ctt-type"
								 label="Contribution Type"
								 value={ props.attributes.contribution_type }
								 options={ [
									 { label: 'Plugin', value: 'plugin' },
									 { label: 'Theme', value: 'theme' },
									 { label: 'Core', value: 'core' },
									 { label: 'Codex', value: 'codex' },
								 ] }
								 onChange={ onChangeType }
							 />
							 <RichText
								 label="Slug"
								 format="string"
								 formattingControls={ [] }
								 placeholder={ __( 'Enter your contribution slug here.' ) }
								 onChange={ onChangePlugin }
								 value={ props.attributes.slug }
								 className="ctt-textbox"
							 />
						 </div>
						 <p>
							 <a className="ctt-btn">
								 { props.attributes.preferred_username }
							 </a>
						 </p>
					 </div>
				 </div>
			 :
			 <ServerSideRender
				 block="wp-contributions/my-plugin"
				 attributes={ {
					 slug: props.attributes.slug,
					 contribution_type: props.attributes.contribution_type
				 } }
			 />
		 ];
	 },
	 save() {
		 return null;
	 },
 });
