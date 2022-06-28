/**
 * Retrieves the translation of text.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-i18n/
 */
import { __ } from '@wordpress/i18n';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import { ServerSideRender } from '@wordpress/server-side-render';
import {
    useBlockProps,
    RichText,
    BlockControls,
	InspectorControls,
	AlignmentToolbar
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	Dashicon,
	Toolbar,
	ToolbarButton,
	Button,
	Tooltip,
	SelectControl
} from '@wordpress/components';
import { tablet } from '@wordpress/icons';

import classnames from 'classnames';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * Those files can contain any CSS code that gets applied to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './editor.scss';
/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( props ) {
	console.log('entered here ' + JSON.stringify(props.attributes));
	console.log('isselected ' + props.isSelected);

	const blockProps = useBlockProps();
	/*const {
		attributes: { slug, contribution_type, preferred_username, theme },
		className,
	} = props;

	useEffect = () => {

	};*/

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

	return (
		<div { ...blockProps }>
			{
				<BlockControls key="custom-controls">
					<Toolbar key="options-settings-toolbar" label={ __( 'Options' ) }>
						<ToolbarButton
							key="preview-toggle-btn"
							icon={ tablet }
							label={ __( 'Preview' )	}
							onClick={ toggletheme }
						/>
					</Toolbar>
				</BlockControls>
			}
			<InspectorControls key="inspector">
				<PanelBody key="block-settings" title={ __( 'WP Contributions Settings' ) } >
					<TextControl
						key="author-user-input"
						label={ __( 'Author User' ) }
						value={ props.attributes.preferred_username }
						onChange={ onChangeUsername }
					/>
				</PanelBody>
			</InspectorControls>
			{ ! props.attributes.theme ?
				<>
					<SelectControl
						key="select-contribution-type"
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
					<TextControl
						key="slug-text-control"
						label="Slug"
						format="string"
						placeholder={ __( 'Enter your contribution slug here.' ) }
						onChange={ onChangePlugin }
						value={ props.attributes.slug }
						className="ctt-textbox"
					/>
				</>
			:
			<MyServerRender myattr={ props.attributes } />
		}
		</div>
	);
}

const MyServerRender = (attr) => {
		console.log( attr.myattr );
		try {
			return (
				<ServerSideRender
					block="wp-contributions/block"
					attributes={ {
						slug: attr.myattr.slug,
						contribution_type: attr.myattr.contribution_type
					} }
				/>
			);
		} catch ( error ){
			console.log(error);
			return ( <p> We've gotten a problem here! </p> );
		}
}
