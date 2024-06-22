/**
 * External dependencies
 */
import { isEmpty } from 'lodash';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { edit } from '@wordpress/icons';
import { useState } from '@wordpress/element';
import {
	__experimentalColorGradientSettingsDropdown as ColorGradientSettingsDropdown,
	__experimentalUseGradient,
} from '@wordpress/block-editor';
import { Icon, PanelBody, BaseControl } from '@wordpress/components';

/**
 * Internal dependencies
 */
import InserterModal from '../inserters/inserter';
import getIcons from '../icons';
import parseIcon from '../utils/parse-icon';
import { flattenIconsArray } from '../utils/icon-functions';

const noop = () => {};

const IconControl = ({
	label = __('Select Icon', 'responsive-menu'),
	panelTitle = __('Select Icon', 'responsive-menu'),
	value = '',
	onChange = noop,
	onClear = noop,
	withPanel = false,
	initialOpen = false,
}) => {
	const [isInserterOpen, setInserterOpen] = useState(false);

	const iconsAll = flattenIconsArray(getIcons());
	const namedIcon = iconsAll.filter((i) => i.name === value);
	let printedIcon = !isEmpty(namedIcon) ? namedIcon[0].icon : '';

	// Icons provided by third-parties are generally strings.
	if (typeof printedIcon === 'string') {
		printedIcon = parseIcon(printedIcon);
	}

	const controls = (
		<>
			<BaseControl
				label={label}
				className="responsive-menu-icon-picker"
				__nextHasNoMarginBottom={true}
			>
				<div className="icon-picker">
					<div
						className="icon-picker__current"
						onClick={() => setInserterOpen(true)}
					>
						{!isEmpty(printedIcon) ? (
							<span className="icon-picker__icon">
								<span className="icon-picker__elm">
									<Icon icon={printedIcon} />
								</span>
							</span>
						) : (
							<span className="icon-picker__icon--empty">
								{__('Select icon', 'responsive-menu')}
							</span>
						)}
					</div>
					{!isEmpty(printedIcon) && (
						<span
							className="icon-picker__del"
							role="button"
							onClick={onClear}
						>
							<Icon icon={'no-alt'} />
						</span>
					)}
				</div>
			</BaseControl>
			<InserterModal
				isInserterOpen={isInserterOpen}
				setInserterOpen={setInserterOpen}
				value={value}
				onChange={onChange}
			/>
		</>
	);

	if (withPanel) {
		return (
			<PanelBody title={panelTitle} initialOpen={initialOpen}>
				{controls}
			</PanelBody>
		);
	}

	return controls;
};

export default IconControl;
