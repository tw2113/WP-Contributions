
import { registerBlockType } from '@wordpress/blocks';

/**
 * Styles
 */
import './style.scss';

/**
 * Internal dependencies
 */
import Edit from './edit';

import metadata from './block.json';

/**
 * Block registration.
 */
registerBlockType( metadata.name, {
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
	edit: Edit,
} );
