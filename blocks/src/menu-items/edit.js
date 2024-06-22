import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InnerBlocks,
	InspectorControls,
	__experimentalBlockVariationPicker,
	store as blockEditorStore,
	LineHeightControl,
	PanelColorSettings,
	MediaUpload,
	MediaUploadCheck,
	FontSizePicker,
	__experimentalFontFamilyControl as FontFamilyControl,
	__experimentalColorGradientSettingsDropdown as ColorGradientSettingsDropdown,
	__experimentalUseMultipleOriginColorsAndGradients as useMultipleOriginColorsAndGradients,
	useSettings,
} from '@wordpress/block-editor';
import {
	PanelBody,
	ColorPicker,
	PanelRow,
	TextControl,
	ToggleControl,
	RangeControl,
	RadioControl,
	Button,
	ResponsiveWrapper,
	SelectControl,
	__experimentalUseCustomUnits as useCustomUnits,
	__experimentalToolsPanel as ToolsPanel,
	__experimentalToolsPanelItem as ToolsPanelItem,
	__experimentalUnitControl as UnitControl,
	__experimentalVStack as VStack,
	__experimentalBorderControl as BorderControl,
	__experimentalBorderBoxControl as BorderBoxControl,
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
	__experimentalToggleGroupControlOptionIcon as ToggleGroupControlOptionIcon,
	__experimentalParseQuantityAndUnitFromRawValue as parseQuantityAndUnitFromRawValue,
	__experimentalInputControl as InputControl,
	__experimentalBoxControl as BoxControl,
} from '@wordpress/components';
import { useEffect, useState, useRef } from '@wordpress/element';
import {
	formatLowercase,
	formatCapitalize,
	formatUppercase,
	alignCenter,
	alignLeft,
	alignRight,
	alignJustify,
} from '@wordpress/icons';
import IconControl from '../components/IconControl';
import DynamicStyles from '../styles';

export default function Edit({ clientId, attributes, setAttributes }) {
	const {
		id,
		menuStyle,
		submenuStyle,
		submenuBehaviour,
		submenuIndentation,
		triggerIcon,
		blockStyles
	} = attributes;
	useEffect(() => {
		if (!id) {
			setAttributes({ id: clientId });
		}
	});
	const blockProps = useBlockProps({
		className: `rmp-block-menu-items-${id} wp-block-rmp-menu-items`,
	});
	const getFontFamiliesList = (fontFamilies) => {
		if (!fontFamilies) {
			return {};
		}

		if (!Array.isArray(fontFamilies)) {
			const { theme, custom } = fontFamilies;
			fontFamilies = theme !== undefined ? theme : [];
			if (custom !== undefined) {
				fontFamilies = [...fontFamilies, ...custom];
			}
		}

		if (!fontFamilies || 0 === fontFamilies.length) {
			return [];
		}

		return fontFamilies;
	};
	const [fontFamilies] = useSettings('typography.fontFamilies');
	const fontFamiliesList = getFontFamiliesList(fontFamilies);
	const hasfontFamilies = 0 < fontFamiliesList.length;
	const dynamicStyles = DynamicStyles(attributes);
	const renderCSS = (
		<style>
			{`
				.rmp-block-menu-items-${id} {
					${Object.entries(dynamicStyles)
						.map(([k, v]) => `${k}:${v}`)
						.join(';')}
				}
			`}
		</style>
	);
	const customStyles = JSON.stringify(dynamicStyles);
	useEffect(() => {
		if (customStyles !== JSON.stringify(blockStyles)) {
			setAttributes({
				blockStyles: dynamicStyles,
			});
		}
	}, [customStyles]);
	const updateMenuStyle = (type, value) => {
		const menuStyleCopy = { ...menuStyle };
		menuStyleCopy[type] = value;
		setAttributes({ menuStyle: menuStyleCopy });
	};
	const updateSubmenuStyle = (type, value) => {
		const submenuStyleCopy = { ...submenuStyle };
		submenuStyleCopy[type] = value;
		setAttributes({ submenuStyle: submenuStyleCopy });
	};
	const updateSubMenuBehaviour = (type, value) => {
		const submenuBehaviourCopy = { ...submenuBehaviour };
		submenuBehaviourCopy[type] = value;
		setAttributes({ submenuBehaviour: submenuBehaviourCopy });
	};
	const updateSubmenuIndentation = (type, value) => {
		const submenuIndentationCopy = { ...submenuIndentation };
		submenuIndentationCopy[type] = value;
		setAttributes({ submenuIndentation: submenuIndentationCopy });
	};
	const updateTriggerIcon = (type, value) => {
		const triggerIconCopy = { ...triggerIcon };
		triggerIconCopy[type] = value;
		setAttributes({ triggerIcon: triggerIconCopy });
	};
	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Item Styling', 'responsive-menu')}>
					<RangeControl
						label={__('Item Height', 'responsive-menu')}
						value={menuStyle.itemHeight}
						onChange={(value) =>
							updateMenuStyle('itemHeight', value)
						}
						min={0}
						step={1}
					/>
					<BoxControl
						label={__('Menu Item Padding', 'responsive-menu')}
						values={submenuStyle.padding}
						onChange={(value) => updateMenuStyle('padding', value)}
						min={0}
						max={300}
						step={1}
						units={['px']}
					/>
				</PanelBody>
				<ToolsPanel
					label={__('Menu typography', 'responsive-menu')}
					resetAll={() =>
						setAttributes({
							menuStyle: {
								...menuStyle,
								fontSize: '15px',
								lineHeight: undefined,
								fontWieght: undefined,
								fontFamily: undefined,
								letterSpacing: undefined,
								letterCase: undefined,
								textAlign: 'left',
								wordWrap: undefined,
							},
						})
					}
				>
					<ToolsPanelItem
						hasValue={() => !!menuStyle?.fontFamily}
						label={__('Font Family', 'responsive-menu')}
						onDeselect={() =>
							updateMenuStyle('fontFamily', undefined)
						}
					>
						{hasfontFamilies && (
							<FontFamilyControl
								fontFamilies={fontFamiliesList}
								value={menuStyle?.fontFamily}
								onChange={(fontFamily) =>
									updateMenuStyle('fontFamily', fontFamily)
								}
								size="__unstable-large"
								__nextHasNoMarginBottom
							/>
						)}
					</ToolsPanelItem>

					<ToolsPanelItem
						hasValue={() => !!menuStyle?.fontSize}
						label={__('Font size', 'responsive-menu')}
						onDeselect={() => updateMenuStyle('fontSize', '15px')}
					>
						<FontSizePicker
							onChange={(fontSize) =>
								updateMenuStyle('fontSize', fontSize)
							}
							value={menuStyle?.fontSize}
							withReset={false}
							__nextHasNoMarginBottom
						/>
					</ToolsPanelItem>

					<ToolsPanelItem
						className="single-column"
						hasValue={() => !!menuStyle?.lineHeight}
						label={__('Line height', 'responsive-menu')}
						onDeselect={() =>
							updateMenuStyle('lineHeight', undefined)
						}
					>
						<LineHeightControl
							__unstableInputWidth="100%"
							__nextHasNoMarginBottom={true}
							value={menuStyle?.lineHeight}
							onChange={(lineHeight) =>
								updateMenuStyle('lineHeight', lineHeight)
							}
						/>
					</ToolsPanelItem>

					<ToolsPanelItem
						hasValue={() => !!menuStyle?.fontWieght}
						label={__('Font weight', 'responsive-menu')}
						onDeselect={() =>
							updateMenuStyle('fontWieght', undefined)
						}
					>
						<RangeControl
							label={__('Font weight', 'responsive-menu')}
							value={menuStyle?.fontWieght}
							onChange={(fontWieght) =>
								updateMenuStyle('fontWieght', fontWieght)
							}
							min={100}
							max={900}
							step={100}
						/>
					</ToolsPanelItem>
					<ToolsPanelItem
						hasValue={() => !!menuStyle?.textAlign}
						label={__('Text Align', 'responsive-menu')}
						onDeselect={() =>
							updateMenuStyle('fontWieght', undefined)
						}
					>
						<ToggleGroupControl
							label={__('Text Align', 'responsive-menu')}
							value={menuStyle?.textAlign}
							onChange={(textAlign) => {
								updateMenuStyle('textAlign', textAlign);
							}}
						>
							<ToggleGroupControlOptionIcon
								value="left"
								icon={alignLeft}
								label={__('Left', 'responsive-menu')}
							/>
							<ToggleGroupControlOptionIcon
								value="center"
								icon={alignCenter}
								label={__('Center', 'responsive-menu')}
							/>
							<ToggleGroupControlOptionIcon
								value="right"
								icon={alignRight}
								label={__('Right', 'responsive-menu')}
							/>
							<ToggleGroupControlOptionIcon
								value="justify"
								icon={alignJustify}
								label={__('Justify', 'responsive-menu')}
							/>
						</ToggleGroupControl>
					</ToolsPanelItem>
					<ToolsPanelItem
						hasValue={() => !!menuStyle?.letterSpacing}
						label={__('Letter spacing', 'responsive-menu')}
						onDeselect={() =>
							updateMenuStyle('letterSpacing', undefined)
						}
					>
						<RangeControl
							label={__('Letter spacing', 'responsive-menu')}
							value={menuStyle?.letterSpacing}
							onChange={(letterSpacing) =>
								updateMenuStyle('letterSpacing', letterSpacing)
							}
							min={0}
							max={200}
							step={1}
						/>
					</ToolsPanelItem>
					<ToolsPanelItem
						hasValue={() => !!menuStyle?.letterCase}
						label={__('Letter case', 'responsive-menu')}
						onDeselect={() =>
							updateMenuStyle('letterCase', undefined)
						}
					>
						<ToggleGroupControl
							label={__('Letter case', 'responsive-menu')}
							value={menuStyle?.letterCase}
							onChange={(letterCase) => {
								updateMenuStyle('letterCase', letterCase);
							}}
						>
							<ToggleGroupControlOptionIcon
								value="capitalize"
								icon={formatCapitalize}
								label={__('Capitalize', 'responsive-menu')}
							/>
							<ToggleGroupControlOptionIcon
								value="lowercase"
								icon={formatLowercase}
								label={__('Lowercase', 'responsive-menu')}
							/>
							<ToggleGroupControlOptionIcon
								value="uppercase"
								icon={formatUppercase}
								label={__('Uppercase', 'responsive-menu')}
							/>
						</ToggleGroupControl>
					</ToolsPanelItem>
					<ToolsPanelItem
						hasValue={() => !!menuStyle?.wordWrap}
						label={__('Word wrap', 'responsive-menu')}
						onDeselect={() =>
							updateMenuStyle('wordWrap', undefined)
						}
					>
						<ToggleControl
							label={__('Word wrap', 'responsive-menu')}
							checked={menuStyle?.wordWrap}
							onChange={(wordWrap) => {
								updateMenuStyle('wordWrap', wordWrap);
							}}
							help={__(
								'Allow the menu items to wrap around to the next line.',
								'responsive-menu'
							)}
						/>
					</ToolsPanelItem>
				</ToolsPanel>
				<ToolsPanel
					label={__('Submenu typography', 'responsive-menu')}
					resetAll={() =>
						setAttributes({
							submenuStyle: {
								...submenuStyle,
								fontSize: '15px',
								lineHeight: undefined,
								fontWieght: undefined,
								fontFamily: undefined,
								letterSpacing: undefined,
								letterCase: undefined,
								textAlign: 'left',
							},
						})
					}
				>
					<ToolsPanelItem
						hasValue={() => !!submenuStyle?.fontFamily}
						label={__('Font Family', 'responsive-menu')}
						onDeselect={() =>
							updateSubmenuStyle('fontFamily', undefined)
						}
					>
						{hasfontFamilies && (
							<FontFamilyControl
								fontFamilies={fontFamiliesList}
								value={submenuStyle?.fontFamily}
								onChange={(fontFamily) =>
									updateSubmenuStyle('fontFamily', fontFamily)
								}
								size="__unstable-large"
								__nextHasNoMarginBottom
							/>
						)}
					</ToolsPanelItem>

					<ToolsPanelItem
						hasValue={() => !!submenuStyle?.fontSize}
						label={__('Font size', 'responsive-menu')}
						onDeselect={() =>
							updateSubmenuStyle('fontSize', '15px')
						}
					>
						<FontSizePicker
							onChange={(fontSize) =>
								updateSubmenuStyle('fontSize', fontSize)
							}
							value={submenuStyle?.fontSize}
							withReset={false}
							__nextHasNoMarginBottom
						/>
					</ToolsPanelItem>

					<ToolsPanelItem
						className="single-column"
						hasValue={() => !!submenuStyle?.lineHeight}
						label={__('Line height', 'responsive-menu')}
						onDeselect={() =>
							updateSubmenuStyle('lineHeight', undefined)
						}
					>
						<LineHeightControl
							__unstableInputWidth="100%"
							__nextHasNoMarginBottom={true}
							value={submenuStyle?.lineHeight}
							onChange={(lineHeight) =>
								updateSubmenuStyle('lineHeight', lineHeight)
							}
						/>
					</ToolsPanelItem>

					<ToolsPanelItem
						hasValue={() => !!submenuStyle?.fontWieght}
						label={__('Font weight', 'responsive-menu')}
						onDeselect={() =>
							updateSubmenuStyle('fontWieght', undefined)
						}
					>
						<RangeControl
							label={__('Font weight', 'responsive-menu')}
							value={submenuStyle?.fontWieght}
							onChange={(fontWieght) =>
								updateSubmenuStyle('fontWieght', fontWieght)
							}
							min={100}
							max={900}
							step={100}
						/>
					</ToolsPanelItem>
					<ToolsPanelItem
						hasValue={() => !!submenuStyle?.textAlign}
						label={__('Text Align', 'responsive-menu')}
						onDeselect={() =>
							updateSubmenuStyle('fontWieght', undefined)
						}
					>
						<ToggleGroupControl
							label={__('Text Align', 'responsive-menu')}
							value={submenuStyle?.textAlign}
							onChange={(textAlign) => {
								updateSubmenuStyle('textAlign', textAlign);
							}}
						>
							<ToggleGroupControlOptionIcon
								value="left"
								icon={alignLeft}
								label={__('Left', 'responsive-menu')}
							/>
							<ToggleGroupControlOptionIcon
								value="center"
								icon={alignCenter}
								label={__('Center', 'responsive-menu')}
							/>
							<ToggleGroupControlOptionIcon
								value="right"
								icon={alignRight}
								label={__('Right', 'responsive-menu')}
							/>
							<ToggleGroupControlOptionIcon
								value="justify"
								icon={alignJustify}
								label={__('Justify', 'responsive-menu')}
							/>
						</ToggleGroupControl>
					</ToolsPanelItem>
					<ToolsPanelItem
						hasValue={() => !!submenuStyle?.letterSpacing}
						label={__('Letter spacing', 'responsive-menu')}
						onDeselect={() =>
							updateSubmenuStyle('letterSpacing', undefined)
						}
					>
						<RangeControl
							label={__('Letter spacing', 'responsive-menu')}
							value={submenuStyle?.letterSpacing}
							onChange={(letterSpacing) =>
								updateSubmenuStyle(
									'letterSpacing',
									letterSpacing
								)
							}
							min={0}
							max={200}
							step={1}
						/>
					</ToolsPanelItem>
					<ToolsPanelItem
						hasValue={() => !!submenuStyle?.letterCase}
						label={__('Letter case', 'responsive-menu')}
						onDeselect={() =>
							updateSubmenuStyle('letterCase', undefined)
						}
					>
						<ToggleGroupControl
							label={__('Letter case', 'responsive-menu')}
							value={submenuStyle?.letterCase}
							onChange={(letterCase) => {
								updateSubmenuStyle('letterCase', letterCase);
							}}
						>
							<ToggleGroupControlOptionIcon
								value="capitalize"
								icon={formatCapitalize}
								label={__('Capitalize', 'responsive-menu')}
							/>
							<ToggleGroupControlOptionIcon
								value="lowercase"
								icon={formatLowercase}
								label={__('Lowercase', 'responsive-menu')}
							/>
							<ToggleGroupControlOptionIcon
								value="uppercase"
								icon={formatUppercase}
								label={__('Uppercase', 'responsive-menu')}
							/>
						</ToggleGroupControl>
					</ToolsPanelItem>
				</ToolsPanel>
				<PanelBody title={__('Sub Menu Behaviour', 'responsive-menu')}>
					<ToggleControl
						label={__('Use Accordion', 'responsive-menu')}
						checked={submenuBehaviour.useAccordion}
						onChange={(value) => {
							updateSubMenuBehaviour('useAccordion', value);
						}}
					/>
					<ToggleControl
						label={__(
							'Auto Expand All Sub Menus',
							'responsive-menu'
						)}
						checked={submenuBehaviour.autoExpandAllSubmenu}
						onChange={(value) => {
							updateSubMenuBehaviour(
								'autoExpandAllSubmenu',
								value
							);
						}}
					/>
					<ToggleControl
						label={__(
							'Auto Expand Current Sub Menus',
							'responsive-menu'
						)}
						checked={submenuBehaviour.autoExpandCurrentSubmenu}
						onChange={(value) => {
							updateSubMenuBehaviour(
								'autoExpandCurrentSubmenu',
								value
							);
						}}
					/>
					<ToggleControl
						label={__(
							'Expand Sub items on Parent Item Click',
							'responsive-menu'
						)}
						checked={submenuBehaviour.expandSubItemOnParentClick}
						onChange={(value) => {
							updateSubMenuBehaviour(
								'expandSubItemOnParentClick',
								value
							);
						}}
					/>
				</PanelBody>
			</InspectorControls>
			<InspectorControls group="styles">
				<PanelColorSettings
					title={__('Menu text', 'gutena-forms')}
					colorSettings={[
						{
							value: menuStyle.color,
							onChange: (value) => {
								updateMenuStyle('color', value);
							},
							label: __('Normal', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: menuStyle.hoverColor,
							onChange: (value) => {
								updateMenuStyle('hoverColor', value);
							},
							label: __('Hover', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: menuStyle.activeColor,
							onChange: (value) => {
								updateMenuStyle('activeColor', value);
							},
							label: __('Active', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: menuStyle.activeHoverColor,
							onChange: (value) => {
								updateMenuStyle('activeHoverColor', value);
							},
							label: __('Active hover', 'responsive-menu'),
							disableCustomColors: false,
						},
					]}
					enableAlpha={true}
				/>
				<PanelColorSettings
					title={__('Menu background', 'gutena-forms')}
					colorSettings={[
						{
							value: menuStyle.background,
							onChange: (value) => {
								updateMenuStyle('background', value);
							},
							label: __('Normal', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: menuStyle.backgroundHover,
							onChange: (value) => {
								updateMenuStyle('backgroundHover', value);
							},
							label: __('Hover', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: menuStyle.backgroundActive,
							onChange: (value) => {
								updateMenuStyle('backgroundActive', value);
							},
							label: __('Active', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: menuStyle.backgroundActiveHover,
							onChange: (value) => {
								updateMenuStyle(
									'backgroundActiveHover',
									value
								);
							},
							label: __('Active hover', 'responsive-menu'),
							disableCustomColors: false,
						},
					]}
					enableAlpha={true}
				/>
				<PanelColorSettings
					title={__('Submenu text', 'gutena-forms')}
					colorSettings={[
						{
							value: submenuStyle.color,
							onChange: (value) => {
								updateMenuStyle('color', value);
							},
							label: __('Normal', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: submenuStyle.hoverColor,
							onChange: (value) => {
								updateMenuStyle('hoverColor', value);
							},
							label: __('Hover', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: submenuStyle.activeColor,
							onChange: (value) => {
								updateMenuStyle('activeColor', value);
							},
							label: __('Active', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: submenuStyle.activeHoverColor,
							onChange: (value) => {
								updateMenuStyle('activeHoverColor', value);
							},
							label: __('Active hover', 'responsive-menu'),
							disableCustomColors: false,
						},
					]}
					enableAlpha={true}
				/>
				<PanelColorSettings
					title={__('Submenu background', 'gutena-forms')}
					colorSettings={[
						{
							value: submenuStyle.backgroundColor,
							onChange: (value) => {
								updateSubmenuStyle('backgroundColor', value);
							},
							label: __('Normal', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: submenuStyle.backgroundHoverColor,
							onChange: (value) => {
								updateSubmenuStyle(
									'backgroundHoverColor',
									value
								);
							},
							label: __('Hover', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: submenuStyle.backgroundActiveColor,
							onChange: (value) => {
								updateSubmenuStyle(
									'backgroundActiveColor',
									value
								);
							},
							label: __('Active', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: submenuStyle.backgroundActiveHoverColor,
							onChange: (value) => {
								updateSubmenuStyle(
									'backgroundActiveHoverColor',
									value
								);
							},
							label: __('Active hover', 'responsive-menu'),
							disableCustomColors: false,
						},
					]}
					enableAlpha={true}
				/>
				<PanelBody title={__('Menu Border', 'responsive-menu')}>
					<BorderBoxControl
						label={__('Normal')}
						onChange={(value) => {
							updateMenuStyle('border', value);
						}}
						value={menuStyle.border}
						enableAlpha={true}
					/>
					<BorderBoxControl
						label={__('Hover')}
						onChange={(value) => {
							updateMenuStyle('borderHover', value);
						}}
						value={menuStyle.borderHover}
						enableAlpha={true}
					/>
					<BorderBoxControl
						label={__('Active')}
						onChange={(value) => {
							updateMenuStyle('borderActive', value);
						}}
						value={menuStyle.borderActive}
						enableAlpha={true}
					/>
					<BorderBoxControl
						label={__('Active hover')}
						onChange={(value) => {
							updateMenuStyle('borderActiveHover', value);
						}}
						value={menuStyle.borderActiveHover}
						enableAlpha={true}
					/>
				</PanelBody>
				<PanelBody title={__('Submenu Border', 'responsive-menu')}>
					<BorderBoxControl
						label={__('Normal')}
						onChange={(value) => {
							updateSubmenuStyle('border', value);
						}}
						value={submenuStyle.border}
						enableAlpha={true}
					/>
					<BorderBoxControl
						label={__('Hover')}
						onChange={(value) => {
							updateSubmenuStyle('borderHover', value);
						}}
						value={submenuStyle.borderHover}
						enableAlpha={true}
					/>
					<BorderBoxControl
						label={__('Active')}
						onChange={(value) => {
							updateSubmenuStyle('borderActive', value);
						}}
						value={submenuStyle.borderActive}
						enableAlpha={true}
					/>
					<BorderBoxControl
						label={__('Active hover')}
						onChange={(value) => {
							updateSubmenuStyle('borderActiveHover', value);
						}}
						value={submenuStyle.borderActiveHover}
						enableAlpha={true}
					/>
				</PanelBody>
				<PanelBody title={__('Submenu indentation', 'responsive-menu')}>
					<SelectControl
						label={__('Side', 'responsive-menu')}
						value={submenuIndentation.side}
						options={[
							{ label: 'Left', value: 'left' },
							{ label: 'Right', value: 'right' },
						]}
						onChange={(side) =>
							updateSubmenuIndentation('side', side)
						}
						help={__(
							'You can set which side of the menu items the padding should be on.',
							'responsive-menu'
						)}
					/>
					<RangeControl
						label={__('Child level 1', 'responsive-menu')}
						value={submenuIndentation.childLevel1}
						onChange={(value) =>
							updateSubmenuIndentation('childLevel1', value)
						}
						min={0}
						step={1}
						max={100}
					/>
					<RangeControl
						label={__('Child level 2', 'responsive-menu')}
						value={submenuIndentation.childLevel2}
						onChange={(value) =>
							updateSubmenuIndentation('childLevel2', value)
						}
						min={0}
						step={1}
						max={100}
					/>
					<RangeControl
						label={__('Child level 3', 'responsive-menu')}
						value={submenuIndentation.childLevel3}
						onChange={(value) =>
							updateSubmenuIndentation('childLevel3', value)
						}
						min={0}
						step={1}
						max={100}
					/>
					<RangeControl
						label={__('Child level 4', 'responsive-menu')}
						value={submenuIndentation.childLevel4}
						onChange={(value) =>
							updateSubmenuIndentation('childLevel4', value)
						}
						min={0}
						step={1}
						max={100}
					/>
				</PanelBody>
				<PanelBody title={__('Trigger icon', 'responsive-menu')}>
					<ToggleGroupControl
						label={__('Type', 'responsive-menu')}
						value={triggerIcon.type}
						isBlock
						onChange={(value) => {
							updateTriggerIcon('type', value);
						}}
					>
						<ToggleGroupControlOption
							value="text"
							label={__('Text', 'responsive-menu')}
						/>
						<ToggleGroupControlOption
							value="icon"
							label={__('Icon', 'responsive-menu')}
						/>
						<ToggleGroupControlOption
							value="image"
							label={__('Image', 'responsive-menu')}
						/>
					</ToggleGroupControl>
					{triggerIcon?.type === 'text' && (
						<>
							<InputControl
								label={__('Text shape', 'responsive-menu')}
								value={triggerIcon.textShape}
								onChange={(value) => {
									updateTriggerIcon('textShape', value);
								}}
							/>
							<InputControl
								label={__(
									'Active text shape',
									'responsive-menu'
								)}
								value={triggerIcon.activeTextShape}
								onChange={(value) => {
									updateTriggerIcon('activeTextShape', value);
								}}
							/>
						</>
					)}
					{triggerIcon?.type === 'icon' && (
						<>
							<IconControl
								label={__('Normal', 'responsive-menu')}
								activeIcon={triggerIcon?.icon}
								value={triggerIcon?.icon}
								onChange={(value) =>
									updateTriggerIcon('icon', value?.iconName)
								}
								onClear={() => updateTriggerIcon('icon', '')}
								withPanel={false}
								initialOpen={true}
							/>
							<IconControl
								label={__('Active', 'responsive-menu')}
								activeIcon={triggerIcon?.activeIcon}
								value={triggerIcon?.activeIcon}
								onChange={(value) =>
									updateTriggerIcon(
										'activeIcon',
										value?.iconName
									)
								}
								onClear={() =>
									updateTriggerIcon('activeIcon', '')
								}
								withPanel={false}
								initialOpen={true}
							/>
						</>
					)}
					{triggerIcon?.type === 'image' && (
						<>
							<MediaUploadCheck>
								<p>{__('Normal', 'responsive-menu')}</p>
								<MediaUpload
									label={__('Normal', 'responsive-menu')}
									onSelect={(media) =>
										updateTriggerIcon('image', media.url)
									}
									allowedTypes={['image']}
									mode="browse"
									render={({ open }) => (
										<Button
											className={`rmp-select-image-component-btn ${triggerIcon?.image ? 'rmp-select-image-component-img' : ''}`}
											onClick={open}
										>
											{triggerIcon?.image ? (
												<ResponsiveWrapper>
													<img
														src={triggerIcon.image}
														alt={__(
															'Hamburger Image',
															'responsive-menu'
														)}
													/>
												</ResponsiveWrapper>
											) : (
												__(
													'Choose an image',
													'responsive-menu'
												)
											)}
										</Button>
									)}
								/>
								{triggerIcon?.image && (
									<Button
										className="rmp-select-image-component-btn button-danger rmp-remove-image-component-btn"
										onClick={() =>
											updateTriggerIcon('image', '')
										}
									>
										{__('Remove', 'responsive-menu')}
									</Button>
								)}
							</MediaUploadCheck>
							<p>{__('Active', 'responsive-menu')}</p>
							<MediaUploadCheck>
								<MediaUpload
									label={__('Active', 'responsive-menu')}
									onSelect={(media) =>
										updateTriggerIcon(
											'activeImage',
											media.url
										)
									}
									allowedTypes={['image']}
									mode="browse"
									render={({ open }) => (
										<Button
											className={`rmp-select-image-component-btn ${triggerIcon?.activeImage ? 'rmp-select-image-component-img' : ''}`}
											onClick={open}
										>
											{triggerIcon?.activeImage ? (
												<ResponsiveWrapper>
													<img
														src={
															triggerIcon.activeImage
														}
														alt={__(
															'Hamburger Active Image',
															'responsive-menu'
														)}
													/>
												</ResponsiveWrapper>
											) : (
												__(
													'Choose an image',
													'responsive-menu'
												)
											)}
										</Button>
									)}
								/>
								{triggerIcon?.activeImage && (
									<Button
										className="rmp-select-image-component-btn button-danger rmp-remove-image-component-btn"
										onClick={() =>
											updateTriggerIcon('activeImage', '')
										}
									>
										{__('Remove', 'responsive-menu')}
									</Button>
								)}
							</MediaUploadCheck>
						</>
					)}
					<RangeControl
						label={__('Width', 'responsive-menu')}
						value={triggerIcon.width}
						onChange={(value) => updateTriggerIcon('width', value)}
						min={0}
						step={1}
						max={400}
						help={__(
							'Set the width of the menu trigger items and their units.',
							'responsive-menu'
						)}
					/>
					<RangeControl
						label={__('Height', 'responsive-menu')}
						value={triggerIcon.height}
						onChange={(value) => updateTriggerIcon('hieght', value)}
						min={0}
						step={1}
						max={400}
						help={__(
							'Set the height of the menu trigger items and their units.',
							'responsive-menu'
						)}
					/>
					<SelectControl
						label={__('Side', 'responsive-menu')}
						value={triggerIcon.position}
						options={[
							{ label: 'Left', value: 'left' },
							{ label: 'Right', value: 'right' },
						]}
						onChange={(side) => updateTriggerIcon('position', side)}
					/>
				</PanelBody>
				<PanelColorSettings
					title={__('Trigger icon', 'gutena-forms')}
					colorSettings={[
						{
							value: triggerIcon.color,
							onChange: (value) => {
								updateTriggerIcon('color', value);
							},
							label: __('Normal', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: triggerIcon.hoverColor,
							onChange: (value) => {
								updateTriggerIcon('hoverColor', value);
							},
							label: __('Hover', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: triggerIcon.activeColor,
							onChange: (value) => {
								updateTriggerIcon('activeColor', value);
							},
							label: __('Active', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: triggerIcon.activeHoverColor,
							onChange: (value) => {
								updateTriggerIcon('activeHoverColor', value);
							},
							label: __('Active hover', 'responsive-menu'),
							disableCustomColors: false,
						},
					]}
					enableAlpha={true}
				/>
				<PanelColorSettings
					title={__('Trigger icon background', 'gutena-forms')}
					colorSettings={[
						{
							value: triggerIcon.backgroundColor,
							onChange: (value) => {
								updateTriggerIcon('backgroundColor', value);
							},
							label: __('Normal', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: triggerIcon.backgroundHoverColor,
							onChange: (value) => {
								updateTriggerIcon(
									'backgroundHoverColor',
									value
								);
							},
							label: __('Hover', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: triggerIcon.backgroundActiveColor,
							onChange: (value) => {
								updateTriggerIcon(
									'backgroundActiveColor',
									value
								);
							},
							label: __('Active', 'responsive-menu'),
							disableCustomColors: false,
						},
						{
							value: triggerIcon.backgroundActiveHoverColor,
							onChange: (value) => {
								updateTriggerIcon(
									'backgroundActiveHoverColor',
									value
								);
							},
							label: __('Active hover', 'responsive-menu'),
							disableCustomColors: false,
						},
					]}
					enableAlpha={true}
				/>
				<PanelBody title={__('Trigger icon border', 'responsive-menu')}>
					<BorderBoxControl
						label={__('Normal')}
						onChange={(value) => {
							updateTriggerIcon('border', value);
						}}
						value={triggerIcon.border}
						enableAlpha={true}
					/>
					<BorderBoxControl
						label={__('Hover')}
						onChange={(value) => {
							updateTriggerIcon('borderHover', value);
						}}
						value={triggerIcon.borderHover}
						enableAlpha={true}
					/>
					<BorderBoxControl
						label={__('Active')}
						onChange={(value) => {
							updateTriggerIcon('borderActive', value);
						}}
						value={triggerIcon.borderActive}
						enableAlpha={true}
					/>
					<BorderBoxControl
						label={__('Active hover')}
						onChange={(value) => {
							updateTriggerIcon('borderActiveHover', value);
						}}
						value={triggerIcon.borderActiveHover}
						enableAlpha={true}
					/>
				</PanelBody>
			</InspectorControls>
			{menuStyle && renderCSS}
			<ul {...blockProps}>
				<InnerBlocks
					allowedBlocks={[
						'core/navigation-link',
						'core/navigation-submenu',
						'core/button',
						'core/social-links',
						'core/home-link',
						'core/loginout',
					]}
				/>
			</ul>
		</>
	);
}
