import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { Icon } from '@wordpress/components';

export default function Save({ attributes }) {
	const { id, triggerIcon } = attributes;
	const blockProps = useBlockProps.save({
		className: `rmp-block-menu-items-${id} wp-block-navigation wp-block-rmp-menu-items`
	});
	return (
		<ul {...blockProps}>
			<InnerBlocks.Content />
		</ul>
	);
}
