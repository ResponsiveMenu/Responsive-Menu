/**
 * The inside of the hamburger trigger.
 *
 * Shared by `edit` and `save` on purpose: the editor preview is only
 * trustworthy if it renders the exact markup the frontend will, and a
 * duplicated JSX tree is how the two silently drift apart.
 */

import { Icon } from '@wordpress/components';

import parseIcon from '../utils/parse-icon';
import { flattenIconsArray } from '../utils/icon-functions';
import getIcons from '../icons';

let iconsByName = null;

/**
 * Icon library keyed by name, built once per page load.
 *
 * @return {Object} `{ [name]: icon }`.
 */
function getIconsByName() {
	if (!iconsByName) {
		iconsByName = flattenIconsArray(getIcons()).reduce((map, icon) => {
			map[icon?.name] = icon?.icon;
			return map;
		}, {});
	}

	return iconsByName;
}

/**
 * Render one icon from the library by name.
 *
 * @param {string} name Icon name.
 * @param {number} size Icon size in px.
 * @return {Element|null} Icon element.
 */
export function renderLibraryIcon(name, size) {
	if (!name) {
		return null;
	}

	let icon = getIconsByName()[name];

	// Icons contributed by third parties are stored as SVG strings.
	if ('string' === typeof icon) {
		icon = parseIcon(icon);
	}

	if (!icon) {
		return null;
	}

	return <Icon icon={icon} size={size} />;
}

export default function TriggerContent({ hamburgerStyle = {}, hamburgerText = {} }) {
	const { type, icon, activeIcon, image, activeImage, iconSize } =
		hamburgerStyle;

	return (
		<>
			<span className="rmp-block-trigger-box">
				{'icon' === type && (
					<>
						{icon && (
							<span className="rmp-block-trigger-icon rmp-block-trigger-icon-inactive">
								{renderLibraryIcon(icon, iconSize)}
							</span>
						)}
						{activeIcon && (
							<span className="rmp-block-trigger-icon rmp-block-trigger-icon-active">
								{renderLibraryIcon(activeIcon, iconSize)}
							</span>
						)}
					</>
				)}
				{'image' === type && (
					<>
						{image && (
							<span className="rmp-block-trigger-icon rmp-block-trigger-icon-inactive">
								<img
									src={image}
									alt=""
									aria-hidden="true"
								/>
							</span>
						)}
						{activeImage && (
							<span className="rmp-block-trigger-icon rmp-block-trigger-icon-active">
								<img
									src={activeImage}
									alt=""
									aria-hidden="true"
								/>
							</span>
						)}
					</>
				)}
				{'hamburger' === type && (
					<span className="rmp-block-trigger-inner"></span>
				)}
			</span>
			{(hamburgerText.text || hamburgerText.activeText) && (
				<span className="rmp-block-trigger-label">
					{hamburgerText.text && (
						<span className="rmp-block-trigger-label-inactive">
							{hamburgerText.text}
						</span>
					)}
					{hamburgerText.activeText && (
						<span className="rmp-block-trigger-label-active">
							{hamburgerText.activeText}
						</span>
					)}
				</span>
			)}
		</>
	);
}
