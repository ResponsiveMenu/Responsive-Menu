/**
 * Editor UI for the menu list.
 *
 * Mirrors `rmp/menu`: every visual control writes to the device selected in the
 * block toolbar, and the canvas renders with that device's resolved styles so
 * the preview matches the frontend rather than approximating it.
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
	Button,
	ResponsiveWrapper,
	SelectControl,
	ToggleControl,
} from '@wordpress/components';
import { useEffect, useMemo } from '@wordpress/element';
import {
	formatLowercase,
	formatCapitalize,
	formatUppercase,
	alignLeft,
	alignCenter,
	alignRight,
	alignJustify,
} from '@wordpress/icons';

import IconControl from '../components/IconControl';
import DeviceSwitcher, { DeviceNotice } from '../components/DeviceSwitcher';
import {
	ResponsiveRange,
	ResponsiveSelect,
	ResponsiveText,
	ResponsiveBox,
	ResponsiveToggleGroup,
	ResponsiveColors,
	ResponsiveBorderBox,
	ControlRow,
} from '../components/ResponsiveControls';
import { usePreviewDevice } from '../utils/device-store';
import {
	createResponsiveHelpers,
	buildResetDevice,
	countOverrides,
	normaliseBreakpoints,
	DEVICE_PREVIEW_WIDTH,
} from '../utils/responsive';
import DynamicStyles, { buildResponsiveStyles } from '../styles';
import { resolveTriggerIcons, ArrowIconTemplate } from './arrow-icons';
import { MENU_ITEM_BLOCKS } from './constants';

const TEXT_ALIGN_OPTIONS = [
	{ value: 'left', label: __('Left', 'responsive-menu'), icon: alignLeft },
	{ value: 'center', label: __('Center', 'responsive-menu'), icon: alignCenter },
	{ value: 'right', label: __('Right', 'responsive-menu'), icon: alignRight },
	{ value: 'justify', label: __('Justify', 'responsive-menu'), icon: alignJustify },
];

const LETTER_CASE_OPTIONS = [
	{ value: 'none', label: __('None', 'responsive-menu'), icon: formatCapitalize },
	{ value: 'uppercase', label: __('Uppercase', 'responsive-menu'), icon: formatUppercase },
	{ value: 'lowercase', label: __('Lowercase', 'responsive-menu'), icon: formatLowercase },
	{ value: 'capitalize', label: __('Capitalize', 'responsive-menu'), icon: formatCapitalize },
];

const FONT_WEIGHT_OPTIONS = [
	'100',
	'200',
	'300',
	'400',
	'500',
	'600',
	'700',
	'800',
	'900',
].map((weight) => ({ value: weight, label: weight }));

/**
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

export default function Edit({
	clientId,
	attributes,
	setAttributes,
	context = {},
}) {
	const { id, submenuBehaviour = {}, blockStyles } = attributes;

	const [device, setDevice] = usePreviewDevice();
	const [fontFamilies] = useSettings('typography.fontFamilies');
	const fontFamiliesList = flattenFontFamilies(fontFamilies);

	const helpers = createResponsiveHelpers(attributes, setAttributes, device);
	const menuStyle = helpers.get('menuStyle');
	const submenuStyle = helpers.get('submenuStyle');
	const submenuIndentation = helpers.get('submenuIndentation');
	const triggerIcon = helpers.get('triggerIcon');
	const desktopMenuStyle = helpers.get('desktopMenuStyle');

	const breakpoints = normaliseBreakpoints({
		breakpoint: context['rmp/breakpoint'],
		tabletBreakpoint: context['rmp/tabletBreakpoint'],
		mobileBreakpoint: context['rmp/mobileBreakpoint'],
	});

	useEffect(() => {
		if (!id) {
			setAttributes({ id: clientId });
		}
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, []);

	const responsiveStyles = useMemo(
		() => buildResponsiveStyles(attributes),
		// eslint-disable-next-line react-hooks/exhaustive-deps
		[
			attributes.menuStyle,
			attributes.submenuStyle,
			attributes.submenuIndentation,
			attributes.triggerIcon,
			attributes.desktopMenuStyle,
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
			'wp-block-navigation',
			'wp-block-rmp-menu-items',
			'block-editor-block-content-overlay',
			`rmp-block-menu-items-${id}`,
			`rmp-submenu-arrow-${triggerIcon.position || 'right'}`,
			`rmp-submenu-indent-${submenuIndentation.side || 'left'}`,
			`rmp-desktop-submenu-${desktopMenuStyle.submenuAnimation || 'fade'}`,
			isDesktopPreview ? 'rmp-editor-desktop-items' : '',
		]
			.filter(Boolean)
			.join(' '),
		style: previewVars,
	});

	const { inactive, active } = resolveTriggerIcons(triggerIcon);

	const updateBehaviour = (key, value) =>
		setAttributes({
			submenuBehaviour: { ...submenuBehaviour, [key]: value },
		});

	const overrideCount = countOverrides(attributes, device);
	const deviceNotice = (
		<DeviceNotice
			device={device}
			count={overrideCount}
			onReset={() => setAttributes(buildResetDevice(attributes, device))}
		/>
	);

	return (
		<>
			<BlockControls>
				<DeviceSwitcher
					device={device}
					onChange={setDevice}
					attributes={attributes}
				/>
			</BlockControls>

			<InspectorControls>
				{deviceNotice}

				<PanelBody title={__('Menu items', 'responsive-menu')}>
					<ResponsiveRange
						helpers={helpers}
						group="menuStyle"
						name="itemHeight"
						label={__('Minimum height', 'responsive-menu')}
						min={0}
						max={200}
						step={1}
					/>
					<ResponsiveRange
						helpers={helpers}
						group="menuStyle"
						name="lineHeight"
						label={__('Line height', 'responsive-menu')}
						min={0}
						max={200}
						step={1}
					/>
					<ResponsiveBox
						helpers={helpers}
						group="menuStyle"
						name="padding"
						label={__('Padding', 'responsive-menu')}
					/>
				</PanelBody>

				<TypographyPanel
					title={__('Menu typography', 'responsive-menu')}
					helpers={helpers}
					group="menuStyle"
					values={menuStyle}
					fontFamiliesList={fontFamiliesList}
				/>

				<PanelBody
					title={__('Sub-menu items', 'responsive-menu')}
					initialOpen={false}
				>
					<ResponsiveRange
						helpers={helpers}
						group="submenuStyle"
						name="lineHeight"
						label={__('Line height', 'responsive-menu')}
						min={0}
						max={200}
						step={1}
					/>
					<ResponsiveBox
						helpers={helpers}
						group="submenuStyle"
						name="padding"
						label={__('Padding', 'responsive-menu')}
					/>
				</PanelBody>

				<TypographyPanel
					title={__('Sub-menu typography', 'responsive-menu')}
					helpers={helpers}
					group="submenuStyle"
					values={submenuStyle}
					fontFamiliesList={fontFamiliesList}
				/>

				<PanelBody
					title={__('Sub-menu behaviour', 'responsive-menu')}
					initialOpen={false}
				>
					<ToggleControl
						__nextHasNoMarginBottom
						label={__('Accordion', 'responsive-menu')}
						help={__(
							'Opening one sub-menu closes its siblings.',
							'responsive-menu'
						)}
						checked={!!submenuBehaviour.useAccordion}
						onChange={(value) =>
							updateBehaviour('useAccordion', value)
						}
					/>
					<ToggleControl
						__nextHasNoMarginBottom
						label={__('Expand all sub-menus', 'responsive-menu')}
						checked={!!submenuBehaviour.autoExpandAllSubmenu}
						onChange={(value) =>
							updateBehaviour('autoExpandAllSubmenu', value)
						}
					/>
					<ToggleControl
						__nextHasNoMarginBottom
						label={__(
							'Expand the current sub-menu',
							'responsive-menu'
						)}
						checked={!!submenuBehaviour.autoExpandCurrentSubmenu}
						onChange={(value) =>
							updateBehaviour('autoExpandCurrentSubmenu', value)
						}
					/>
					<ToggleControl
						__nextHasNoMarginBottom
						label={__(
							'Expand child sub-menus with their parent',
							'responsive-menu'
						)}
						checked={!!submenuBehaviour.expandSubItemOnParentClick}
						onChange={(value) =>
							updateBehaviour('expandSubItemOnParentClick', value)
						}
					/>
					<ToggleControl
						__nextHasNoMarginBottom
						label={__(
							'Clicking the item opens its sub-menu',
							'responsive-menu'
						)}
						help={__(
							'The first click opens the sub-menu instead of following the link.',
							'responsive-menu'
						)}
						checked={!!submenuBehaviour.itemClickOpens}
						onChange={(value) =>
							updateBehaviour('itemClickOpens', value)
						}
					/>
					<SelectControl
						__nextHasNoMarginBottom
						label={__('Desktop dropdowns open on', 'responsive-menu')}
						value={submenuBehaviour.desktopTrigger || 'click'}
						options={[
							{ value: 'click', label: __('Click', 'responsive-menu') },
							{ value: 'hover', label: __('Hover', 'responsive-menu') },
						]}
						onChange={(value) =>
							updateBehaviour('desktopTrigger', value)
						}
						help={__(
							'Hover only applies on devices that actually have a pointer.',
							'responsive-menu'
						)}
					/>
				</PanelBody>

				<PanelBody
					title={__('Sub-menu indentation', 'responsive-menu')}
					initialOpen={false}
				>
					<ResponsiveToggleGroup
						helpers={helpers}
						group="submenuIndentation"
						name="side"
						label={__('Indent from', 'responsive-menu')}
						options={[
							{ value: 'left', label: __('Left', 'responsive-menu') },
							{ value: 'right', label: __('Right', 'responsive-menu') },
						]}
					/>
					{[1, 2, 3, 4].map((level) => (
						<ResponsiveRange
							key={level}
							helpers={helpers}
							group="submenuIndentation"
							name={`childLevel${level}`}
							/* translators: %d: sub-menu depth. */
							label={`${__('Level', 'responsive-menu')} ${level} (%)`}
							min={0}
							max={50}
							step={1}
						/>
					))}
				</PanelBody>

				<PanelBody
					title={__('Sub-menu arrow', 'responsive-menu')}
					initialOpen={false}
				>
					<ResponsiveToggleGroup
						helpers={helpers}
						group="triggerIcon"
						name="type"
						label={__('Type', 'responsive-menu')}
						options={[
							{ value: 'text', label: __('Text', 'responsive-menu') },
							{ value: 'icon', label: __('Icon', 'responsive-menu') },
							{ value: 'image', label: __('Image', 'responsive-menu') },
						]}
					/>
					{'text' === triggerIcon.type && (
						<>
							<ResponsiveText
								helpers={helpers}
								group="triggerIcon"
								name="textShape"
								label={__('Closed', 'responsive-menu')}
							/>
							<ResponsiveText
								helpers={helpers}
								group="triggerIcon"
								name="activeTextShape"
								label={__('Open', 'responsive-menu')}
							/>
						</>
					)}
					{'icon' === triggerIcon.type && (
						<>
							<IconControl
								label={__('Closed', 'responsive-menu')}
								value={triggerIcon.icon}
								onChange={(value) =>
									helpers.update(
										'triggerIcon',
										'icon',
										value?.iconName
									)
								}
								onClear={() =>
									helpers.update('triggerIcon', 'icon', '')
								}
							/>
							<IconControl
								label={__('Open', 'responsive-menu')}
								value={triggerIcon.activeIcon}
								onChange={(value) =>
									helpers.update(
										'triggerIcon',
										'activeIcon',
										value?.iconName
									)
								}
								onClear={() =>
									helpers.update(
										'triggerIcon',
										'activeIcon',
										''
									)
								}
							/>
						</>
					)}
					{'image' === triggerIcon.type && (
						<>
							<ImageField
								label={__('Closed', 'responsive-menu')}
								value={triggerIcon.image}
								onSelect={(url) =>
									helpers.update('triggerIcon', 'image', url)
								}
								onClear={() =>
									helpers.update('triggerIcon', 'image', '')
								}
							/>
							<ImageField
								label={__('Open', 'responsive-menu')}
								value={triggerIcon.activeImage}
								onSelect={(url) =>
									helpers.update(
										'triggerIcon',
										'activeImage',
										url
									)
								}
								onClear={() =>
									helpers.update(
										'triggerIcon',
										'activeImage',
										''
									)
								}
							/>
						</>
					)}
					<ResponsiveToggleGroup
						helpers={helpers}
						group="triggerIcon"
						name="position"
						label={__('Position', 'responsive-menu')}
						options={[
							{ value: 'left', label: __('Left', 'responsive-menu') },
							{ value: 'right', label: __('Right', 'responsive-menu') },
						]}
					/>
					<ResponsiveRange
						helpers={helpers}
						group="triggerIcon"
						name="width"
						label={__('Width', 'responsive-menu')}
						min={0}
						max={200}
						step={1}
					/>
					<ResponsiveRange
						helpers={helpers}
						group="triggerIcon"
						name="height"
						label={__('Height', 'responsive-menu')}
						min={0}
						max={200}
						step={1}
					/>
				</PanelBody>
			</InspectorControls>

			<InspectorControls group="styles">
				{deviceNotice}

				<ResponsiveColors
					helpers={helpers}
					group="menuStyle"
					title={__('Menu text', 'responsive-menu')}
					colors={[
						{ name: 'color', label: __('Normal', 'responsive-menu') },
						{ name: 'hoverColor', label: __('Hover', 'responsive-menu') },
						{ name: 'activeColor', label: __('Current', 'responsive-menu') },
						{
							name: 'activeHoverColor',
							label: __('Current hover', 'responsive-menu'),
						},
					]}
				/>
				<ResponsiveColors
					helpers={helpers}
					group="menuStyle"
					title={__('Menu background', 'responsive-menu')}
					colors={[
						{ name: 'background', label: __('Normal', 'responsive-menu') },
						{
							name: 'backgroundHover',
							label: __('Hover', 'responsive-menu'),
						},
						{
							name: 'backgroundActive',
							label: __('Current', 'responsive-menu'),
						},
						{
							name: 'backgroundActiveHover',
							label: __('Current hover', 'responsive-menu'),
						},
					]}
				/>
				<BorderPanel
					title={__('Menu borders', 'responsive-menu')}
					helpers={helpers}
					group="menuStyle"
				/>

				<ResponsiveColors
					helpers={helpers}
					group="submenuStyle"
					title={__('Sub-menu text', 'responsive-menu')}
					colors={[
						{ name: 'color', label: __('Normal', 'responsive-menu') },
						{ name: 'hoverColor', label: __('Hover', 'responsive-menu') },
						{ name: 'activeColor', label: __('Current', 'responsive-menu') },
						{
							name: 'activeHoverColor',
							label: __('Current hover', 'responsive-menu'),
						},
					]}
				/>
				<ResponsiveColors
					helpers={helpers}
					group="submenuStyle"
					title={__('Sub-menu background', 'responsive-menu')}
					colors={[
						{
							name: 'backgroundColor',
							label: __('Normal', 'responsive-menu'),
						},
						{
							name: 'backgroundHoverColor',
							label: __('Hover', 'responsive-menu'),
						},
						{
							name: 'backgroundActiveColor',
							label: __('Current', 'responsive-menu'),
						},
						{
							name: 'backgroundActiveHoverColor',
							label: __('Current hover', 'responsive-menu'),
						},
					]}
				/>
				<BorderPanel
					title={__('Sub-menu borders', 'responsive-menu')}
					helpers={helpers}
					group="submenuStyle"
				/>

				<ResponsiveColors
					helpers={helpers}
					group="triggerIcon"
					title={__('Arrow colour', 'responsive-menu')}
					colors={[
						{ name: 'color', label: __('Normal', 'responsive-menu') },
						{ name: 'hoverColor', label: __('Hover', 'responsive-menu') },
						{ name: 'activeColor', label: __('Open', 'responsive-menu') },
						{
							name: 'activeHoverColor',
							label: __('Open hover', 'responsive-menu'),
						},
					]}
				/>
				<ResponsiveColors
					helpers={helpers}
					group="triggerIcon"
					title={__('Arrow background', 'responsive-menu')}
					colors={[
						{
							name: 'backgroundColor',
							label: __('Normal', 'responsive-menu'),
						},
						{
							name: 'backgroundHoverColor',
							label: __('Hover', 'responsive-menu'),
						},
						{
							name: 'backgroundActiveColor',
							label: __('Open', 'responsive-menu'),
						},
						{
							name: 'backgroundActiveHoverColor',
							label: __('Open hover', 'responsive-menu'),
						},
					]}
				/>
				<BorderPanel
					title={__('Arrow borders', 'responsive-menu')}
					helpers={helpers}
					group="triggerIcon"
				/>

				<ResponsiveColors
					helpers={helpers}
					group="desktopMenuStyle"
					title={__('Desktop bar', 'responsive-menu')}
					colors={[
						{ name: 'color', label: __('Text', 'responsive-menu') },
						{ name: 'hoverColor', label: __('Text hover', 'responsive-menu') },
						{
							name: 'activeColor',
							label: __('Text current', 'responsive-menu'),
						},
						{
							name: 'background',
							label: __('Background', 'responsive-menu'),
						},
						{
							name: 'backgroundHover',
							label: __('Background hover', 'responsive-menu'),
						},
						{
							name: 'backgroundActive',
							label: __('Background current', 'responsive-menu'),
						},
					]}
				/>
				<ResponsiveColors
					helpers={helpers}
					group="desktopMenuStyle"
					title={__('Desktop dropdown', 'responsive-menu')}
					colors={[
						{ name: 'submenuColor', label: __('Text', 'responsive-menu') },
						{
							name: 'submenuHoverColor',
							label: __('Text hover', 'responsive-menu'),
						},
						{
							name: 'submenuBackground',
							label: __('Background', 'responsive-menu'),
						},
						{
							name: 'submenuBackgroundHover',
							label: __('Background hover', 'responsive-menu'),
						},
					]}
				/>
				<PanelBody
					title={__('Desktop layout', 'responsive-menu')}
					initialOpen={false}
				>
					<ResponsiveBox
						helpers={helpers}
						group="desktopMenuStyle"
						name="itemPadding"
						label={__('Item padding', 'responsive-menu')}
					/>
					<ResponsiveRange
						helpers={helpers}
						group="desktopMenuStyle"
						name="gap"
						label={__('Gap between items', 'responsive-menu')}
						min={0}
						max={100}
						step={1}
					/>
					<ResponsiveSelect
						helpers={helpers}
						group="desktopMenuStyle"
						name="justify"
						label={__('Alignment', 'responsive-menu')}
						options={[
							{ value: 'flex-start', label: __('Left', 'responsive-menu') },
							{ value: 'center', label: __('Center', 'responsive-menu') },
							{ value: 'flex-end', label: __('Right', 'responsive-menu') },
							{
								value: 'space-between',
								label: __('Space between', 'responsive-menu'),
							},
						]}
					/>
					<ResponsiveSelect
						helpers={helpers}
						group="desktopMenuStyle"
						name="dropdownAlign"
						label={__('Dropdown alignment', 'responsive-menu')}
						options={[
							{ value: 'left', label: __('Left', 'responsive-menu') },
							{ value: 'right', label: __('Right', 'responsive-menu') },
						]}
					/>
					<ResponsiveRange
						helpers={helpers}
						group="desktopMenuStyle"
						name="submenuMinWidth"
						label={__('Dropdown minimum width', 'responsive-menu')}
						min={100}
						max={800}
						step={10}
					/>
					<ResponsiveSelect
						helpers={helpers}
						group="desktopMenuStyle"
						name="submenuAnimation"
						label={__('Dropdown animation', 'responsive-menu')}
						options={[
							{ value: 'none', label: __('None', 'responsive-menu') },
							{ value: 'fade', label: __('Fade', 'responsive-menu') },
							{ value: 'slide', label: __('Slide', 'responsive-menu') },
						]}
					/>
					<ResponsiveRange
						helpers={helpers}
						group="desktopMenuStyle"
						name="submenuAnimationSpeed"
						label={__('Dropdown animation speed (ms)', 'responsive-menu')}
						min={0}
						max={1000}
						step={10}
					/>
				</PanelBody>
			</InspectorControls>

			<ArrowIconTemplate
				triggerIcon={triggerIcon}
				inactive={inactive}
				active={active}
			/>
			<ul
				{...blockProps}
				data-submenu-icon={inactive}
				data-submenu-active-icon={active}
				data-submenu-icon-type={triggerIcon.type}
			>
				<InnerBlocks
					allowedBlocks={MENU_ITEM_BLOCKS}
					renderAppender={InnerBlocks.ButtonBlockAppender}
				/>
			</ul>
		</>
	);
}

/**
 * The typography controls shared by the menu and sub-menu panels.
 *
 * @param {Object} props
 * @param {string} props.title            Panel title.
 * @param {Object} props.helpers          Responsive helper bundle.
 * @param {string} props.group            Attribute group.
 * @param {Object} props.values           Resolved group values.
 * @param {Array}  props.fontFamiliesList Theme font families.
 * @return {Element} Panel.
 */
function TypographyPanel({ title, helpers, group, values, fontFamiliesList }) {
	return (
		<PanelBody title={title} initialOpen={false}>
			{fontFamiliesList.length > 0 && (
				<FontFamilyControl
					fontFamilies={fontFamiliesList}
					value={values.fontFamily}
					onChange={(value) =>
						helpers.update(group, 'fontFamily', value)
					}
					size="__unstable-large"
					__nextHasNoMarginBottom
				/>
			)}
			<ControlRow label={__('Size', 'responsive-menu')}>
				<FontSizePicker
					value={values.fontSize}
					fallbackFontSize={15}
					onChange={(value) =>
						helpers.update(group, 'fontSize', value)
					}
					__nextHasNoMarginBottom
				/>
			</ControlRow>
			<ResponsiveSelect
				helpers={helpers}
				group={group}
				name="fontWieght"
				label={__('Weight', 'responsive-menu')}
				options={[
					{ value: '', label: __('Default', 'responsive-menu') },
					...FONT_WEIGHT_OPTIONS,
				]}
			/>
			<ResponsiveToggleGroup
				helpers={helpers}
				group={group}
				name="textAlign"
				label={__('Alignment', 'responsive-menu')}
				options={TEXT_ALIGN_OPTIONS}
			/>
			<ResponsiveToggleGroup
				helpers={helpers}
				group={group}
				name="letterCase"
				label={__('Letter case', 'responsive-menu')}
				options={LETTER_CASE_OPTIONS}
			/>
			<ResponsiveRange
				helpers={helpers}
				group={group}
				name="letterSpacing"
				label={__('Letter spacing', 'responsive-menu')}
				min={0}
				max={20}
				step={0.5}
			/>
			<ResponsiveSelect
				helpers={helpers}
				group={group}
				name="wordWrap"
				label={__('Word wrap', 'responsive-menu')}
				options={[
					{ value: '', label: __('Default', 'responsive-menu') },
					{ value: 'normal', label: __('Normal', 'responsive-menu') },
					{
						value: 'break-word',
						label: __('Break long words', 'responsive-menu'),
					},
				]}
			/>
		</PanelBody>
	);
}

/**
 * The four border states of one attribute group.
 *
 * @param {Object} props
 * @param {string} props.title   Panel title.
 * @param {Object} props.helpers Responsive helper bundle.
 * @param {string} props.group   Attribute group.
 * @return {Element} Panel.
 */
function BorderPanel({ title, helpers, group }) {
	const states = [
		{ name: 'border', label: __('Normal', 'responsive-menu') },
		{ name: 'borderHover', label: __('Hover', 'responsive-menu') },
		{ name: 'borderActive', label: __('Current', 'responsive-menu') },
		{
			name: 'borderActiveHover',
			label: __('Current hover', 'responsive-menu'),
		},
	];

	return (
		<PanelBody title={title} initialOpen={false}>
			{states.map((state) => (
				<ResponsiveBorderBox
					key={state.name}
					helpers={helpers}
					group={group}
					name={state.name}
					label={state.label}
				/>
			))}
		</PanelBody>
	);
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
