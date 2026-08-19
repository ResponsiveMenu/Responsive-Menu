/**
 * Editor UI for the Responsive Menu block.
 *
 * Two things make this more than a settings form:
 *
 * 1. Every visual control writes to the device selected in the block toolbar,
 *    so one menu carries desktop, tablet and mobile values (see utils/responsive).
 * 2. The canvas renders the *real* trigger markup and the *real* resolved styles
 *    for the selected device, so what the editor shows is what the frontend
 *    paints — including the switch to the inline desktop bar.
 */

import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InnerBlocks,
	InspectorControls,
	BlockControls,
	FontSizePicker,
	MediaUpload,
	MediaUploadCheck,
	__experimentalFontFamilyControl as FontFamilyControl,
	useSettings,
} from '@wordpress/block-editor';
import {
	PanelBody,
	ToolbarGroup,
	ToolbarButton,
	Notice,
	Button,
	RangeControl,
	ToggleControl,
	ColorPalette,
	ResponsiveWrapper,
	FocalPointPicker,
	__experimentalToolsPanel as ToolsPanel,
	__experimentalToolsPanelItem as ToolsPanelItem,
} from '@wordpress/components';
import { useEffect, useMemo, useState } from '@wordpress/element';
import { seen, unseen } from '@wordpress/icons';

import IconControl from './components/IconControl';
import TriggerContent from './components/TriggerContent';
import DeviceSwitcher, { DeviceNotice } from './components/DeviceSwitcher';
import {
	ResponsiveRange,
	ResponsiveSelect,
	ResponsiveToggle,
	ResponsiveText,
	ResponsiveBox,
	ResponsiveToggleGroup,
	ResponsiveColors,
	ControlRow,
} from './components/ResponsiveControls';
import { usePreviewDevice } from './utils/device-store';
import {
	createResponsiveHelpers,
	buildResetDevice,
	countOverrides,
	normaliseBreakpoints,
	DEVICE_PREVIEW_WIDTH,
} from './utils/responsive';
import DynamicStyles, { buildResponsiveStyles } from './styles';
import './editor.scss';

const MENU_TEMPLATE = [
	[
		'core/heading',
		{ level: 3, content: __('Responsive Menu', 'responsive-menu'), textAlign: 'center' },
	],
	['rmp/menu-items', {}, [
		['core/navigation-link', { label: __('Home', 'responsive-menu'), url: '/' }],
		['core/navigation-link', { label: __('About', 'responsive-menu'), url: '/about' }],
	]],
];

const ALLOWED_BLOCKS = [
	'rmp/menu-items',
	'core/heading',
	'core/search',
	'core/social-links',
	'core/spacer',
	'core/separator',
	'core/image',
	'core/paragraph',
	'core/buttons',
	'core/button',
	'core/columns',
	'core/group',
	'core/site-logo',
	'core/site-title',
];

/**
 * Font families available from the active theme, in the flat shape
 * FontFamilyControl expects.
 *
 * @param {Array|Object} fontFamilies theme.json font families.
 * @return {Array} Flat list of font families.
 */
function flattenFontFamilies(fontFamilies) {
	if (!fontFamilies) {
		return [];
	}

	if (Array.isArray(fontFamilies)) {
		return fontFamilies;
	}

	const { theme = [], custom = [] } = fontFamilies;

	return [...theme, ...custom];
}

export default function Edit({ attributes, setAttributes, clientId }) {
	const {
		id,
		breakpoint,
		tabletBreakpoint,
		mobileBreakpoint,
		menuBehaviour = {},
		blockStyles,
	} = attributes;

	const [device, setDevice] = usePreviewDevice();
	const [previewOpen, setPreviewOpen] = useState(false);
	const [fontFamilies] = useSettings('typography.fontFamilies');
	const fontFamiliesList = flattenFontFamilies(fontFamilies);

	const helpers = createResponsiveHelpers(attributes, setAttributes, device);
	const hamburgerStyle = helpers.get('hamburgerStyle');
	const hamburgerText = helpers.get('hamburgerText');
	const menuContainerStyle = helpers.get('menuContainerStyle');
	const menuAnimation = helpers.get('menuAnimation');
	const overlay = helpers.get('overlay');
	const triggerPosition = helpers.get('triggerPosition');

	const breakpoints = normaliseBreakpoints({
		breakpoint,
		tabletBreakpoint,
		mobileBreakpoint,
	});

	// A menu needs a stable id to key its generated CSS on. clientId is stable
	// for the life of the block, and is what the saved class name uses.
	useEffect(() => {
		if (!id) {
			setAttributes({ id: clientId });
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, []);

	// The saved style payload: desktop values plus tablet/mobile diffs. PHP
	// turns this back into one rule and two media queries at render time.
	const responsiveStyles = useMemo(
		() => buildResponsiveStyles(attributes),
		// eslint-disable-next-line react-hooks/exhaustive-deps
		[
			attributes.menuContainerStyle,
			attributes.menuAnimation,
			attributes.hamburgerStyle,
			attributes.hamburgerText,
			attributes.overlay,
			attributes.triggerPosition,
			attributes.responsive,
		]
	);

	const serialisedStyles = JSON.stringify(responsiveStyles);

	useEffect(() => {
		if (serialisedStyles !== JSON.stringify(blockStyles)) {
			setAttributes({ blockStyles: responsiveStyles });
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [serialisedStyles]);

	// The canvas shows one device at a time, so its values go on inline rather
	// than through the media queries the frontend uses.
	const previewVars = useMemo(
		() => DynamicStyles(attributes, device),
		// eslint-disable-next-line react-hooks/exhaustive-deps
		[serialisedStyles, device]
	);

	const previewWidth = DEVICE_PREVIEW_WIDTH[device];
	const isDesktopPreview =
		(previewWidth ?? Number.MAX_SAFE_INTEGER) >= breakpoints.breakpoint;

	const blockProps = useBlockProps({
		className: [
			'rmp-block-navigator',
			`rmp-block-navigator-${id}`,
			`rmp-block-trigger-position-${triggerPosition.type || 'relative'}`,
			isDesktopPreview ? 'rmp-desktop-mode' : '',
			previewOpen ? 'rmp-block-active' : 'rmp-editor-static',
		]
			.filter(Boolean)
			.join(' '),
		style: previewVars,
	});

	const updateBehaviour = (key, value) =>
		setAttributes({ menuBehaviour: { ...menuBehaviour, [key]: value } });

	const overrideCount = countOverrides(attributes, device);

	return (
		<>
			<BlockControls>
				<DeviceSwitcher
					device={device}
					onChange={setDevice}
					attributes={attributes}
				/>
				<ToolbarGroup>
					<ToolbarButton
						icon={previewOpen ? seen : unseen}
						isActive={previewOpen}
						label={
							previewOpen
								? __('Show editing view', 'responsive-menu')
								: __('Preview open menu', 'responsive-menu')
						}
						onClick={() => setPreviewOpen(!previewOpen)}
					/>
				</ToolbarGroup>
			</BlockControls>

			<InspectorControls>
				<DeviceNotice
					device={device}
					count={overrideCount}
					onReset={() =>
						setAttributes(buildResetDevice(attributes, device))
					}
				/>

				<PanelBody
					title={__('Breakpoints', 'responsive-menu')}
					initialOpen={true}
				>
					<ResponsiveRangeGlobal
						label={__('Collapse to hamburger below', 'responsive-menu')}
						value={breakpoint}
						onChange={(value) =>
							setAttributes({ breakpoint: Number(value) })
						}
						min={0}
						max={2560}
						step={1}
						help={__(
							'Above this width the menu is shown inline; below it, behind the hamburger.',
							'responsive-menu'
						)}
					/>
					<ResponsiveRangeGlobal
						label={__('Tablet styles below', 'responsive-menu')}
						value={tabletBreakpoint}
						onChange={(value) =>
							setAttributes({ tabletBreakpoint: Number(value) })
						}
						min={320}
						max={2560}
						step={1}
					/>
					<ResponsiveRangeGlobal
						label={__('Mobile styles below', 'responsive-menu')}
						value={mobileBreakpoint}
						onChange={(value) =>
							setAttributes({ mobileBreakpoint: Number(value) })
						}
						min={240}
						max={2560}
						step={1}
					/>
					{mobileBreakpoint >= tabletBreakpoint && (
						<Notice status="warning" isDismissible={false}>
							{__(
								'The mobile breakpoint must be smaller than the tablet breakpoint; it will be clamped on the frontend.',
								'responsive-menu'
							)}
						</Notice>
					)}
				</PanelBody>

				<PanelBody
					title={__('Hamburger', 'responsive-menu')}
					initialOpen={false}
				>
					<ResponsiveToggleGroup
						helpers={helpers}
						group="hamburgerStyle"
						name="type"
						label={__('Type', 'responsive-menu')}
						options={[
							{ value: 'hamburger', label: __('Lines', 'responsive-menu') },
							{ value: 'icon', label: __('Icon', 'responsive-menu') },
							{ value: 'image', label: __('Image', 'responsive-menu') },
						]}
					/>

					{'hamburger' === hamburgerStyle.type && (
						<>
							<ResponsiveRange
								helpers={helpers}
								group="hamburgerStyle"
								name="lineSpacing"
								label={__('Line Spacing', 'responsive-menu')}
								min={0}
								max={40}
								step={1}
							/>
							<ResponsiveRange
								helpers={helpers}
								group="hamburgerStyle"
								name="lineWidth"
								label={__('Line Width', 'responsive-menu')}
								min={0}
								max={100}
								step={1}
							/>
							<ResponsiveRange
								helpers={helpers}
								group="hamburgerStyle"
								name="lineHeight"
								label={__('Line Height', 'responsive-menu')}
								min={0}
								max={20}
								step={1}
							/>
						</>
					)}

					{'icon' === hamburgerStyle.type && (
						<>
							<IconControl
								label={__('Closed', 'responsive-menu')}
								value={hamburgerStyle.icon}
								onChange={(value) =>
									helpers.update(
										'hamburgerStyle',
										'icon',
										value?.iconName
									)
								}
								onClear={() =>
									helpers.update('hamburgerStyle', 'icon', '')
								}
							/>
							<IconControl
								label={__('Open', 'responsive-menu')}
								value={hamburgerStyle.activeIcon}
								onChange={(value) =>
									helpers.update(
										'hamburgerStyle',
										'activeIcon',
										value?.iconName
									)
								}
								onClear={() =>
									helpers.update(
										'hamburgerStyle',
										'activeIcon',
										''
									)
								}
							/>
							<ResponsiveRange
								helpers={helpers}
								group="hamburgerStyle"
								name="iconSize"
								label={__('Icon Size', 'responsive-menu')}
								min={0}
								max={200}
								step={1}
							/>
						</>
					)}

					{'image' === hamburgerStyle.type && (
						<>
							<ImageField
								label={__('Closed', 'responsive-menu')}
								value={hamburgerStyle.image}
								onSelect={(url) =>
									helpers.update('hamburgerStyle', 'image', url)
								}
								onClear={() =>
									helpers.update('hamburgerStyle', 'image', '')
								}
							/>
							<ImageField
								label={__('Open', 'responsive-menu')}
								value={hamburgerStyle.activeImage}
								onSelect={(url) =>
									helpers.update(
										'hamburgerStyle',
										'activeImage',
										url
									)
								}
								onClear={() =>
									helpers.update(
										'hamburgerStyle',
										'activeImage',
										''
									)
								}
							/>
							<ResponsiveRange
								helpers={helpers}
								group="hamburgerStyle"
								name="iconSize"
								label={__('Image Size', 'responsive-menu')}
								min={0}
								max={300}
								step={1}
							/>
						</>
					)}

					<ResponsiveRange
						helpers={helpers}
						group="hamburgerStyle"
						name="width"
						label={__('Width', 'responsive-menu')}
						min={0}
						max={300}
						step={1}
					/>
					<ResponsiveRange
						helpers={helpers}
						group="hamburgerStyle"
						name="height"
						label={__('Height', 'responsive-menu')}
						min={0}
						max={300}
						step={1}
					/>
					<ResponsiveBox
						helpers={helpers}
						group="hamburgerStyle"
						name="borderRadius"
						label={__('Border Radius', 'responsive-menu')}
						units={[{ value: 'px', label: 'px' }, { value: '%', label: '%' }]}
					/>
					<ResponsiveToggleGroup
						helpers={helpers}
						group="hamburgerStyle"
						name="side"
						label={__('Align', 'responsive-menu')}
						options={[
							{ value: 'left', label: __('Left', 'responsive-menu') },
							{ value: 'right', label: __('Right', 'responsive-menu') },
						]}
					/>
					<ResponsiveSelect
						helpers={helpers}
						group="triggerPosition"
						name="type"
						label={__('Position', 'responsive-menu')}
						options={[
							{ value: 'relative', label: __('In flow', 'responsive-menu') },
							{ value: 'absolute', label: __('Absolute', 'responsive-menu') },
							{ value: 'fixed', label: __('Fixed', 'responsive-menu') },
						]}
						help={__(
							'Fixed keeps the trigger visible while the page scrolls.',
							'responsive-menu'
						)}
					/>
					{'relative' !== triggerPosition.type && (
						<>
							<ResponsiveRange
								helpers={helpers}
								group="triggerPosition"
								name="distanceFromSide"
								label={__('Distance from side', 'responsive-menu')}
								min={0}
								max={500}
								step={1}
							/>
							<ResponsiveRange
								helpers={helpers}
								group="triggerPosition"
								name="top"
								label={__('Distance from top', 'responsive-menu')}
								min={0}
								max={500}
								step={1}
							/>
						</>
					)}
				</PanelBody>

				<PanelBody
					title={__('Hamburger Text', 'responsive-menu')}
					initialOpen={false}
				>
					<ResponsiveText
						helpers={helpers}
						group="hamburgerText"
						name="text"
						label={__('Closed label', 'responsive-menu')}
					/>
					<ResponsiveText
						helpers={helpers}
						group="hamburgerText"
						name="activeText"
						label={__('Open label', 'responsive-menu')}
					/>
					<ResponsiveToggleGroup
						helpers={helpers}
						group="hamburgerText"
						name="position"
						label={__('Position', 'responsive-menu')}
						options={[
							{ value: 'left', label: __('Left', 'responsive-menu') },
							{ value: 'top', label: __('Top', 'responsive-menu') },
							{ value: 'right', label: __('Right', 'responsive-menu') },
							{ value: 'bottom', label: __('Bottom', 'responsive-menu') },
						]}
					/>
					{fontFamiliesList.length > 0 && (
						<FontFamilyControl
							fontFamilies={fontFamiliesList}
							value={hamburgerText.fontFamily}
							onChange={(value) =>
								helpers.update(
									'hamburgerText',
									'fontFamily',
									value
								)
							}
							size="__unstable-large"
							__nextHasNoMarginBottom
						/>
					)}
					<ControlRow label={__('Size', 'responsive-menu')}>
						<FontSizePicker
							value={hamburgerText.size}
							fallbackFontSize={14}
							onChange={(value) =>
								helpers.update('hamburgerText', 'size', value)
							}
							__nextHasNoMarginBottom
						/>
					</ControlRow>
					<ResponsiveRange
						helpers={helpers}
						group="hamburgerText"
						name="lineHeight"
						label={__('Line Height', 'responsive-menu')}
						min={0}
						max={100}
						step={1}
					/>
					<ResponsiveRange
						helpers={helpers}
						group="hamburgerText"
						name="gap"
						label={__('Gap from icon', 'responsive-menu')}
						min={0}
						max={60}
						step={1}
					/>
				</PanelBody>

				<PanelBody
					title={__('Behaviour', 'responsive-menu')}
					initialOpen={false}
				>
					<p className="rmp-panel-hint">
						{__('Close the menu when…', 'responsive-menu')}
					</p>
					<GlobalToggle
						label={__('A link is clicked', 'responsive-menu')}
						checked={!!menuBehaviour.linkClick}
						onChange={(value) => updateBehaviour('linkClick', value)}
					/>
					<GlobalToggle
						label={__('The page behind is clicked', 'responsive-menu')}
						checked={!!menuBehaviour.pageClick}
						onChange={(value) => updateBehaviour('pageClick', value)}
					/>
					<GlobalToggle
						label={__('The page is scrolled', 'responsive-menu')}
						checked={!!menuBehaviour.pageScroll}
						onChange={(value) =>
							updateBehaviour('pageScroll', value)
						}
					/>
					<GlobalToggle
						label={__('Escape is pressed', 'responsive-menu')}
						checked={false !== menuBehaviour.escKey}
						onChange={(value) => updateBehaviour('escKey', value)}
					/>
					<GlobalToggle
						label={__('Swiped away', 'responsive-menu')}
						checked={!!menuBehaviour.swipe}
						onChange={(value) => updateBehaviour('swipe', value)}
						help={__(
							'Lets touch users swipe the panel back off screen.',
							'responsive-menu'
						)}
					/>
					<hr />
					<GlobalToggle
						label={__('Lock page scrolling while open', 'responsive-menu')}
						checked={!!menuBehaviour.scrollLock}
						onChange={(value) =>
							updateBehaviour('scrollLock', value)
						}
					/>
					<GlobalToggle
						label={__('Keep keyboard focus inside the menu', 'responsive-menu')}
						checked={false !== menuBehaviour.focusTrap}
						onChange={(value) =>
							updateBehaviour('focusTrap', value)
						}
						help={__(
							'Recommended: stops Tab reaching the page behind an open menu.',
							'responsive-menu'
						)}
					/>
				</PanelBody>

				<PanelBody
					title={__('Overlay', 'responsive-menu')}
					initialOpen={false}
				>
					<ResponsiveToggle
						helpers={helpers}
						group="overlay"
						name="enabled"
						label={__('Dim the page behind the menu', 'responsive-menu')}
					/>
					{overlay.enabled && (
						<ControlRow label={__('Overlay colour', 'responsive-menu')}>
							<ColorPalette
								value={overlay.color}
								enableAlpha
								onChange={(value) =>
									helpers.update('overlay', 'color', value)
								}
							/>
						</ControlRow>
					)}
				</PanelBody>
			</InspectorControls>

			<InspectorControls group="styles">
				<DeviceNotice
					device={device}
					count={overrideCount}
					onReset={() =>
						setAttributes(buildResetDevice(attributes, device))
					}
				/>

				<ResponsiveColors
					helpers={helpers}
					group="menuContainerStyle"
					title={__('Menu panel', 'responsive-menu')}
					colors={[
						{ name: 'color', label: __('Text', 'responsive-menu') },
						{
							name: 'background',
							label: __('Background', 'responsive-menu'),
						},
					]}
				/>

				<ToolsPanel
					label={__('Panel background image', 'responsive-menu')}
					resetAll={() =>
						helpers.updateMany('menuContainerStyle', {
							backgroundImage: '',
							backgroundPosition: '',
							backgroundSize: '',
							backgroundRepeat: '',
						})
					}
				>
					<ToolsPanelItem
						hasValue={() => !!menuContainerStyle.backgroundImage}
						label={__('Image', 'responsive-menu')}
						onDeselect={() =>
							helpers.update(
								'menuContainerStyle',
								'backgroundImage',
								''
							)
						}
					>
						<ImageField
							label={__('Image', 'responsive-menu')}
							value={menuContainerStyle.backgroundImage}
							onSelect={(url) =>
								helpers.update(
									'menuContainerStyle',
									'backgroundImage',
									url
								)
							}
							onClear={() =>
								helpers.update(
									'menuContainerStyle',
									'backgroundImage',
									''
								)
							}
						/>
					</ToolsPanelItem>
					{menuContainerStyle.backgroundImage && (
						<ToolsPanelItem
							hasValue={() =>
								!!menuContainerStyle.backgroundPosition
							}
							label={__('Focal point', 'responsive-menu')}
							onDeselect={() =>
								helpers.update(
									'menuContainerStyle',
									'backgroundPosition',
									''
								)
							}
						>
							<FocalPointPicker
								label={__('Focal point', 'responsive-menu')}
								url={menuContainerStyle.backgroundImage}
								value={menuContainerStyle.backgroundPosition}
								onChange={(value) =>
									helpers.update(
										'menuContainerStyle',
										'backgroundPosition',
										value
									)
								}
								__nextHasNoMarginBottom
							/>
						</ToolsPanelItem>
					)}
					<ToolsPanelItem
						hasValue={() => !!menuContainerStyle.backgroundSize}
						label={__('Size', 'responsive-menu')}
						onDeselect={() =>
							helpers.update(
								'menuContainerStyle',
								'backgroundSize',
								''
							)
						}
					>
						<ResponsiveToggleGroup
							helpers={helpers}
							group="menuContainerStyle"
							name="backgroundSize"
							label={__('Size', 'responsive-menu')}
							options={[
								{ value: 'cover', label: __('Cover', 'responsive-menu') },
								{ value: 'contain', label: __('Contain', 'responsive-menu') },
								{ value: 'auto', label: __('Auto', 'responsive-menu') },
							]}
						/>
					</ToolsPanelItem>
					<ToolsPanelItem
						hasValue={() => !!menuContainerStyle.backgroundRepeat}
						label={__('Repeat', 'responsive-menu')}
						onDeselect={() =>
							helpers.update(
								'menuContainerStyle',
								'backgroundRepeat',
								''
							)
						}
					>
						<ResponsiveSelect
							helpers={helpers}
							group="menuContainerStyle"
							name="backgroundRepeat"
							label={__('Repeat', 'responsive-menu')}
							options={[
								{ label: __('No repeat', 'responsive-menu'), value: 'no-repeat' },
								{ label: __('Repeat', 'responsive-menu'), value: 'repeat' },
								{ label: __('Repeat X', 'responsive-menu'), value: 'repeat-x' },
								{ label: __('Repeat Y', 'responsive-menu'), value: 'repeat-y' },
								{ label: __('Round', 'responsive-menu'), value: 'round' },
								{ label: __('Space', 'responsive-menu'), value: 'space' },
							]}
						/>
					</ToolsPanelItem>
				</ToolsPanel>

				<PanelBody
					title={__('Panel size & spacing', 'responsive-menu')}
					initialOpen={false}
				>
					<ResponsiveBox
						helpers={helpers}
						group="menuContainerStyle"
						name="padding"
						label={__('Padding', 'responsive-menu')}
					/>
					<ResponsiveRange
						helpers={helpers}
						group="menuContainerStyle"
						name="menuWidth"
						label={__('Width (%)', 'responsive-menu')}
						min={0}
						max={100}
						step={1}
					/>
					<ResponsiveRange
						helpers={helpers}
						group="menuContainerStyle"
						name="menuMaximumWidth"
						label={__('Maximum width (px)', 'responsive-menu')}
						min={50}
						max={2000}
						step={10}
					/>
					<ResponsiveRange
						helpers={helpers}
						group="menuContainerStyle"
						name="menuMinimumWidth"
						label={__('Minimum width (px)', 'responsive-menu')}
						min={0}
						max={2000}
						step={10}
					/>
					<ResponsiveToggle
						helpers={helpers}
						group="menuContainerStyle"
						name="autoHeight"
						label={__('Height fits content', 'responsive-menu')}
						help={__(
							'Off: the panel is full viewport height.',
							'responsive-menu'
						)}
					/>
					<ResponsiveRange
						helpers={helpers}
						group="menuContainerStyle"
						name="columns"
						label={__('Menu columns', 'responsive-menu')}
						min={1}
						max={4}
						step={1}
						help={__(
							'Splits the list of menu items into columns — usually only worth it on wider devices.',
							'responsive-menu'
						)}
					/>
				</PanelBody>

				<PanelBody
					title={__('Animation', 'responsive-menu')}
					initialOpen={false}
				>
					<ResponsiveSelect
						helpers={helpers}
						group="menuAnimation"
						name="type"
						label={__('Type', 'responsive-menu')}
						options={[
							{ label: __('Slide', 'responsive-menu'), value: 'slide' },
							{ label: __('Fade', 'responsive-menu'), value: 'fade' },
							{ label: __('None', 'responsive-menu'), value: 'none' },
						]}
					/>
					<ResponsiveSelect
						helpers={helpers}
						group="menuAnimation"
						name="direction"
						label={__('Opens from', 'responsive-menu')}
						options={[
							{ label: __('Left', 'responsive-menu'), value: 'left' },
							{ label: __('Right', 'responsive-menu'), value: 'right' },
							...('fade' === menuAnimation.type
								? []
								: [
										{ label: __('Top', 'responsive-menu'), value: 'top' },
										{ label: __('Bottom', 'responsive-menu'), value: 'bottom' },
									]),
						]}
					/>
					<ResponsiveRange
						helpers={helpers}
						group="menuAnimation"
						name="transitionDuration"
						label={__('Duration (seconds)', 'responsive-menu')}
						min={0}
						max={3}
						step={0.1}
					/>
				</PanelBody>

				<ResponsiveColors
					helpers={helpers}
					group="hamburgerStyle"
					title={__('Hamburger colour', 'responsive-menu')}
					colors={[
						{ name: 'color', label: __('Normal', 'responsive-menu') },
						{ name: 'hoverColor', label: __('Hover', 'responsive-menu') },
						{ name: 'activeColor', label: __('Open', 'responsive-menu') },
					]}
				/>

				<ResponsiveColors
					helpers={helpers}
					group="hamburgerStyle"
					title={__('Hamburger background', 'responsive-menu')}
					colors={[
						{ name: 'background', label: __('Normal', 'responsive-menu') },
						{
							name: 'hoverBackground',
							label: __('Hover', 'responsive-menu'),
						},
						{
							name: 'activeBackground',
							label: __('Open', 'responsive-menu'),
						},
					]}
				/>

				<ResponsiveColors
					helpers={helpers}
					group="hamburgerText"
					title={__('Hamburger text colour', 'responsive-menu')}
					colors={[
						{ name: 'color', label: __('Text', 'responsive-menu') },
					]}
				/>
			</InspectorControls>

			<div
				className={`rmp-block-preview-frame is-${device}`}
				style={previewWidth ? { maxWidth: `${previewWidth}px` } : undefined}
			>
				<nav {...blockProps}>
					<button
						type="button"
						className={`rmp-block-menu-trigger rmp-menu-trigger-boring rmp-mobile-device-menu rmp-block-menu-trigger-position-${hamburgerStyle.side} rmp-block-text-position-${hamburgerText.position} ${previewOpen ? 'rmp-block-active' : ''}`}
						onClick={() => setPreviewOpen(!previewOpen)}
						aria-expanded={previewOpen}
					>
						<TriggerContent
							hamburgerStyle={hamburgerStyle}
							hamburgerText={hamburgerText}
						/>
					</button>
					<div
						className={`rmp-block-container rmp-block-container-${id} rmp-block-container-direction-${menuAnimation.direction} rmp-block-container-animation-${menuAnimation.type} ${previewOpen ? 'rmp-block-active' : ''}`}
						id={`rmp-block-container-${id}`}
					>
						<InnerBlocks
							template={MENU_TEMPLATE}
							allowedBlocks={ALLOWED_BLOCKS}
							templateLock={false}
						/>
					</div>
				</nav>
			</div>
		</>
	);
}

/**
 * A range control for a setting that is the same on every device.
 *
 * @param {Object} props RangeControl props.
 * @return {Element} Range control.
 */
function ResponsiveRangeGlobal(props) {
	return <RangeControl {...props} __nextHasNoMarginBottom />;
}

/**
 * A toggle for a setting that is the same on every device.
 *
 * @param {Object} props ToggleControl props.
 * @return {Element} Toggle control.
 */
function GlobalToggle(props) {
	return <ToggleControl {...props} __nextHasNoMarginBottom />;
}

/**
 * Media-library image picker with a clear button.
 *
 * @param {Object}   props
 * @param {string}   props.label    Field label.
 * @param {string}   props.value    Current image URL.
 * @param {Function} props.onSelect Called with the chosen URL.
 * @param {Function} props.onClear  Called to clear the field.
 * @return {Element} Image picker.
 */
function ImageField({ label, value, onSelect, onClear }) {
	return (
		<MediaUploadCheck>
			<ControlRow label={label}>
				<MediaUpload
					title={label}
					onSelect={(media) => onSelect(media.url)}
					allowedTypes={['image']}
					mode="browse"
					render={({ open }) => (
						<Button
							className={`rmp-select-image-component-btn ${value ? 'rmp-select-image-component-img' : ''}`}
							onClick={open}
						>
							{value ? (
								<ResponsiveWrapper>
									<img src={value} alt="" />
								</ResponsiveWrapper>
							) : (
								__('Choose an image', 'responsive-menu')
							)}
						</Button>
					)}
				/>
				{value && (
					<Button
						className="rmp-select-image-component-btn button-danger rmp-remove-image-component-btn"
						onClick={onClear}
					>
						{__('Remove', 'responsive-menu')}
					</Button>
				)}
			</ControlRow>
		</MediaUploadCheck>
	);
}
