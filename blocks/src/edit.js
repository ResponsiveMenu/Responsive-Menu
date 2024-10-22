import { __ } from '@wordpress/i18n';
import {
	useBlockProps,
	InnerBlocks,
	InspectorControls,
	PanelColorSettings,
	FontSizePicker,
	MediaUpload,
	MediaUploadCheck,
	__experimentalFontFamilyControl as FontFamilyControl,
	useSettings
} from '@wordpress/block-editor';
import {
	PanelBody,
	Icon,
	PanelRow,
	TextControl,
	ToggleControl,
	RangeControl,
	SelectControl,
	ResponsiveWrapper,
	Button,
	FocalPointPicker,
	__experimentalToolsPanel as ToolsPanel,
	__experimentalToolsPanelItem as ToolsPanelItem,
	__experimentalUnitControl as UnitControl,
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
	__experimentalBoxControl as BoxControl,
} from '@wordpress/components';
import { useEffect, useRef } from '@wordpress/element';
import IconControl from './components/IconControl';
import parseIcon from './utils/parse-icon';
import { flattenIconsArray } from './utils/icon-functions';
import getIcons from './icons';
import './editor.scss';
import DynamicStyles from './styles';
export default function Edit({ attributes, setAttributes, clientId }) {
	const {
		id,
		menuContainerStyle,
		menuAnimation,
		menuBehaviour,
		breakpoint,
		activeMenu,
		hamburgerStyle,
		hamburgerText,
		blockStyles,
	} = attributes;
	const blockProps = useBlockProps({
		className: `rmp-block-navigator rmp-block-navigator-${id}`,
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
	useEffect(() => {
		if (!id) {
			setAttributes({ id: clientId });
		}
	});
	const menuRef = useRef();
    const rmpToggleMenu = () => {
        setAttributes({ activeMenu: !activeMenu });
    };
	const dynamicStyles = DynamicStyles(attributes);
	const renderCSS = (
		<style>
			{`
				.rmp-block-navigator-${id} {
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
	const iconsAll = flattenIconsArray(getIcons());
	const iconsObj = iconsAll.reduce((acc, value) => {
		acc[value?.name] = value?.icon;
		return acc;
	}, {});
	const renderSVG = (svg, size) => {
		let renderedIcon = iconsObj?.[svg];
		// Icons provided by third-parties are generally strings.
		if (typeof renderedIcon === 'string') {
			renderedIcon = parseIcon(renderedIcon);
		}

		return <Icon icon={renderedIcon} size={size} />;
	};
	const updateMenuContainerStyle = (type, value) => {
		const menuContainerStyleCopy = { ...menuContainerStyle };
		menuContainerStyleCopy[type] = value;
		setAttributes({ menuContainerStyle: menuContainerStyleCopy });
	};
	const updateMenuAnimation = (type, value) => {
		const menuAnimationCopy = { ...menuAnimation };
		menuAnimationCopy[type] = value;
		setAttributes({ menuAnimation: menuAnimationCopy });
	};
	const updateMenuBehaviour = (type, value) => {
		const menuBehaviourCopy = { ...menuBehaviour };
		menuBehaviourCopy[type] = value;
		setAttributes({ menuBehaviour: menuBehaviourCopy });
	};
	const updateHamburgerStyle = (type, value) => {
		const hamburgerStyleCopy = { ...hamburgerStyle };
		hamburgerStyleCopy[type] = value;
		setAttributes({ hamburgerStyle: hamburgerStyleCopy });
	};
	const updateHamburgerText = (type, value) => {
		const hamburgerTextCopy = { ...hamburgerText };
		hamburgerTextCopy[type] = value;
		setAttributes({ hamburgerText: hamburgerTextCopy });
	};
	const updateBreakpoint = (newBreakpoint) => {
		setAttributes({ breakpoint: Number(newBreakpoint) });
	};
	const updateActiveMenu = (newActiveMenu) => {
		setAttributes({ activeMenu: newActiveMenu });
	};
	return (
		<>
			<InspectorControls group="styles">
				<PanelColorSettings
					title={__('Color', 'responsive-menu')}
					colorSettings={[
						{
							label: __('Text', 'responsive-menu'),
							value: menuContainerStyle.color,
							onChange: (value) => {
								updateMenuContainerStyle('color', value);
							},
							disableCustomColors: false,
						},
						{
							label: __('Background', 'responsive-menu'),
							value: menuContainerStyle.background,
							onChange: (value) => {
								updateMenuContainerStyle('background', value);
							},
							disableCustomColors: false,
						},
					]}
					enableAlpha={true}
				/>
				<ToolsPanel label={__('Background', 'responsive-menu')}>
					<ToolsPanelItem
						hasValue={() => !!menuContainerStyle?.backgroundImage}
						label={__('Background Image')}
						onDeselect={() =>
							updateMenuContainerStyle('backgroundImage', '')
						}
					>
						<MediaUploadCheck>
							<MediaUpload
								title={__(
									'Background Image',
									'responsive-menu'
								)}
								onSelect={(media) => {
									updateMenuContainerStyle(
										'backgroundImage',
										media.url
									);
								}}
								allowedTypes={['image']}
								mode={'browse'}
								render={({ open }) => (
									<Button
										className={`rmp-select-image-component-btn ${'' !== menuContainerStyle?.backgroundImage ? 'rmp-select-image-component-img' : ''}`}
										onClick={open}
									>
										{'' ===
											menuContainerStyle?.backgroundImage &&
											__(
												'Choose an image',
												'responsive-menu'
											)}
										{menuContainerStyle?.backgroundImage && (
											<ResponsiveWrapper>
												<img
													src={
														menuContainerStyle?.backgroundImage
													}
													alt={__(
														'Background Image',
														'responsive-menu'
													)}
												/>
											</ResponsiveWrapper>
										)}
									</Button>
								)}
							/>
						</MediaUploadCheck>
					</ToolsPanelItem>
					<ToolsPanelItem
						hasValue={() =>
							!!menuContainerStyle?.backgroundPosition
						}
						label={__('Position')}
						onDeselect={() =>
							updateMenuContainerStyle('backgroundPosition', '')
						}
					>
						<FocalPointPicker
							url={menuContainerStyle?.backgroundImage}
							value={menuContainerStyle?.backgroundPosition}
							onDragStart={(value) => {
								updateMenuContainerStyle(
									'backgroundPosition',
									value
								);
							}}
							onDrag={(value) => {
								updateMenuContainerStyle(
									'backgroundPosition',
									value
								);
							}}
							onChange={(value) => {
								updateMenuContainerStyle(
									'backgroundPosition',
									value
								);
							}}
						/>
					</ToolsPanelItem>
					<ToolsPanelItem
						hasValue={() => !!menuContainerStyle?.backgroundSize}
						label={__('Size')}
						onDeselect={() =>
							updateMenuContainerStyle('backgroundSize', '')
						}
					>
						<ToggleGroupControl
							label={__('Size', 'responsive-menu')}
							value={menuContainerStyle?.backgroundSize}
							isBlock
							onChange={(value) => {
								updateMenuContainerStyle(
									'backgroundSize',
									value
								);
							}}
						>
							<ToggleGroupControlOption
								value="cover"
								label={__('Cover', 'responsive-menu')}
							/>
							<ToggleGroupControlOption
								value="contain"
								label={__('Contain', 'responsive-menu')}
							/>
							<ToggleGroupControlOption
								value="fixed"
								label={__('Fixed', 'responsive-menu')}
							/>
						</ToggleGroupControl>
					</ToolsPanelItem>
					<ToolsPanelItem
						hasValue={() => !!menuContainerStyle?.backgroundRepeat}
						label={__('Repeat')}
						onDeselect={() =>
							updateMenuContainerStyle('backgroundRepeat', '')
						}
					>
						<SelectControl
							label={__('Repeat', 'responsive-menu')}
							value={menuContainerStyle.backgroundRepeat}
							options={[
								{ label: 'Repeat', value: 'repeat' },
								{ label: 'No Repeat', value: 'no-repeat' },
								{ label: 'Repeat X', value: 'repeat-x' },
								{ label: 'Repeat Y', value: 'repeat-y' },
								{ label: 'Round', value: 'round' },
								{ label: 'Space', value: 'space' },
								{ label: 'Inherit', value: 'inherit' },
								{ label: 'Initial', value: 'initial' },
								{ label: 'Revert', value: 'revert' },
								{ label: 'Revert Layer', value: 'revert-layer' },
								{ label: 'Unset', value: 'unset' },
							]}
							onChange={(value) => updateMenuContainerStyle('backgroundRepeat', value)}
						/>
					</ToolsPanelItem>
				</ToolsPanel>
				<ToolsPanel label={__('Dimensions', 'responsive-menu')}>
					<ToolsPanelItem
						hasValue={() => !!menuContainerStyle?.padding}
						label={__('Padding')}
						onDeselect={() =>
							updateMenuContainerStyle('padding', {})
						}
					>
						<BoxControl
							label={__('Padding')}
							onChange={(value) => {
								updateMenuContainerStyle('padding', value);
							}}
							values={menuContainerStyle?.padding}
							allowReset={true}
						/>
					</ToolsPanelItem>
				</ToolsPanel>
				<PanelColorSettings
					title={__('Hamburger', 'responsive-menu')}
					colorSettings={[
						{
							label: __('Normal', 'responsive-menu'),
							value: hamburgerStyle.color,
							onChange: (value) => {
								updateHamburgerStyle('color', value);
							},
							disableCustomColors: false,
						},
						{
							label: __('Hover', 'responsive-menu'),
							value: hamburgerStyle.hoverColor,
							onChange: (value) => {
								updateHamburgerStyle('hoverColor', value);
							},
							disableCustomColors: false,
						},
						{
							label: __('Active', 'responsive-menu'),
							value: hamburgerStyle.activeColor,
							onChange: (value) => {
								updateHamburgerStyle('activeColor', value);
							},
							disableCustomColors: false,
						},
					]}
					enableAlpha={true}
				/>
				<PanelColorSettings
					title={__('Hamburger Background', 'responsive-menu')}
					colorSettings={[
						{
							label: __('Normal', 'responsive-menu'),
							value: hamburgerStyle.background,
							onChange: (value) => {
								updateHamburgerStyle('background', value);
							},
							disableCustomColors: false,
						},
						{
							label: __('Hover', 'responsive-menu'),
							value: hamburgerStyle.hoverBackground,
							onChange: (value) => {
								updateHamburgerStyle('hoverBackground', value);
							},
							disableCustomColors: false,
						},
						{
							label: __('Active', 'responsive-menu'),
							value: hamburgerStyle.activeBackground,
							onChange: (value) => {
								updateHamburgerStyle('activeBackground', value);
							},
							disableCustomColors: false,
						},
					]}
					enableAlpha={true}
				/>
				<PanelBody title={__('Menu Container Size', 'responsive-menu')}>
					<RangeControl
						label={__('Width (%)', 'responsive-menu')}
						value={menuContainerStyle.menuWidth}
						onChange={(width) =>
							updateMenuContainerStyle('menuWidth', width)
						}
						min={0}
						max={100}
						step={10}
					/>
					<RangeControl
						label={__('Max Width', 'responsive-menu')}
						value={menuContainerStyle.menuMaximumWidth}
						onChange={(width) =>
							updateMenuContainerStyle('menuMaximumWidth', width)
						}
						min={50}
						max={5000}
						step={20}
					/>
					<RangeControl
						label={__('Min Width', 'responsive-menu')}
						value={menuContainerStyle.menuMinimumWidth}
						onChange={(width) =>
							updateMenuContainerStyle('menuMinimumWidth', width)
						}
						min={50}
						max={5000}
						step={20}
					/>
				</PanelBody>
				<PanelBody title={__('Animation', 'responsive-menu')}>
					<SelectControl
						label={__('Type', 'responsive-menu')}
						value={menuAnimation.type}
						options={[
							{ label: 'Slide', value: 'slide' },
							{ label: 'Fade', value: 'fade' },
						]}
						onChange={(type) => updateMenuAnimation('type', type)}
					/>
					<SelectControl
						label={__('Direction', 'responsive-menu')}
						value={menuAnimation.direction}
						options={[
							{ label: 'Left', value: 'left' },
							{ label: 'Right', value: 'right' },
							...(menuAnimation.type !== 'fade' ? [{ label: 'Top', value: 'top' }] : []),
							...(menuAnimation.type !== 'fade' ? [{ label: 'Bottom', value: 'bottom' }] : []),
						]}
						onChange={(direction) =>
							updateMenuAnimation('direction', direction)
						}
						help={__(
							'Set the viewport side for container entry.',
							'responsive-menu'
						)}
					/>
					<RangeControl
						label={__('Transition Delay', 'responsive-menu')}
						value={menuAnimation.transitionDuration}
						onChange={(delay) =>
							updateMenuAnimation('transitionDuration', delay)
						}
						min={0}
						max={10}
						step={0.1}
						help={__(
							'Control the speed of animation for container entry and exit.',
							'responsive-menu'
						)}
					/>
				</PanelBody>
			</InspectorControls>
			<InspectorControls>
				<PanelBody title={__('Hamburger', 'responsive-menu')}>
					<RangeControl
						label={__('Breakpoint', 'responsive-menu')}
						value={breakpoint}
						onChange={updateBreakpoint}
						min={0}
						step={10}
						max={5000}
						help={__(
							'Set the breakpoint below which you want hamburger menu',
							'responsive-menu'
						)}
					/>
					<ToggleControl
						label={__('Preview', 'responsive-menu')}
						onChange={(value) => {
							updateActiveMenu(value)
						}}
						checked={activeMenu}
					/>
					<ToggleGroupControl
						label={__('Type', 'responsive-menu')}
						value={hamburgerStyle.type}
						isBlock
						onChange={(value) => {
							updateHamburgerStyle('type', value);
						}}
					>
						<ToggleGroupControlOption
							value="hamburger"
							label={__('Hamburger', 'responsive-menu')}
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
					{hamburgerStyle?.type &&
						'hamburger' === hamburgerStyle.type && (
							<>
								<RangeControl
									label={__(
										'Line Spacing',
										'responsive-menu'
									)}
									value={hamburgerStyle.lineSpacing}
									onChange={(value) =>
										updateHamburgerStyle(
											'lineSpacing',
											value
										)
									}
									min={0}
									step={1}
									help={__(
										"Set the margin between each individual button line and it's unit",
										'responsive-menu'
									)}
								/>
								<RangeControl
									label={__('Line Width', 'responsive-menu')}
									value={hamburgerStyle.lineWidth}
									onChange={(value) =>
										updateHamburgerStyle('lineWidth', value)
									}
									min={0}
									step={1}
									help={__(
										"Set the width of each individual button line and it's unit",
										'responsive-menu'
									)}
								/>
								<RangeControl
									label={__('Line Height', 'responsive-menu')}
									value={hamburgerStyle.lineHeight}
									onChange={(value) =>
										updateHamburgerStyle(
											'lineHeight',
											value
										)
									}
									min={0}
									step={1}
									help={__(
										"Set the height of each individual button line and it's unit",
										'responsive-menu'
									)}
								/>
							</>
						)}
					{hamburgerStyle?.type && 'icon' === hamburgerStyle.type && (
						<>
							<IconControl
								label={__('Normal', 'responsive-menu')}
								activeIcon={hamburgerStyle?.icon}
								value={hamburgerStyle?.icon}
								onChange={(value) =>
									updateHamburgerStyle(
										'icon',
										value?.iconName
									)
								}
								onClear={() => updateHamburgerStyle('icon', '')}
								withPanel={false}
								initialOpen={true}
							/>
							<IconControl
								label={__('Active', 'responsive-menu')}
								activeIcon={hamburgerStyle?.activeIcon}
								value={hamburgerStyle?.activeIcon}
								onChange={(value) =>
									updateHamburgerStyle(
										'activeIcon',
										value?.iconName
									)
								}
								onClear={() =>
									updateHamburgerStyle('activeIcon', '')
								}
								withPanel={false}
								initialOpen={true}
							/>
							<RangeControl
								label={__('Icon Size', 'responsive-menu')}
								value={hamburgerStyle.iconSize}
								onChange={(value) =>
									updateHamburgerStyle('iconSize', value)
								}
								min={0}
								step={1}
							/>
						</>
					)}
					{hamburgerStyle?.type === 'image' && (
						<>
							<MediaUploadCheck>
								<p>{__('Normal', 'responsive-menu')}</p>
								<MediaUpload
									label={__('Normal', 'responsive-menu')}
									onSelect={(media) =>
										updateHamburgerStyle('image', media.url)
									}
									allowedTypes={['image']}
									mode="browse"
									render={({ open }) => (
										<Button
											className={`rmp-select-image-component-btn ${hamburgerStyle?.image ? 'rmp-select-image-component-img' : ''}`}
											onClick={open}
										>
											{hamburgerStyle?.image ? (
												<ResponsiveWrapper>
													<img
														src={
															hamburgerStyle.image
														}
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
								{hamburgerStyle?.image && (
									<Button
										className="rmp-select-image-component-btn button-danger rmp-remove-image-component-btn"
										onClick={() =>
											updateHamburgerStyle('image', '')
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
										updateHamburgerStyle(
											'activeImage',
											media.url
										)
									}
									allowedTypes={['image']}
									mode="browse"
									render={({ open }) => (
										<Button
											className={`rmp-select-image-component-btn ${hamburgerStyle?.activeImage ? 'rmp-select-image-component-img' : ''}`}
											onClick={open}
										>
											{hamburgerStyle?.activeImage ? (
												<ResponsiveWrapper>
													<img
														src={
															hamburgerStyle.activeImage
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
								{hamburgerStyle?.activeImage && (
									<Button
										className="rmp-select-image-component-btn button-danger rmp-remove-image-component-btn"
										onClick={() =>
											updateHamburgerStyle(
												'activeImage',
												''
											)
										}
									>
										{__('Remove', 'responsive-menu')}
									</Button>
								)}
							</MediaUploadCheck>
							<RangeControl
								label={__('Image Size', 'responsive-menu')}
								value={hamburgerStyle.iconSize}
								onChange={(value) =>
									updateHamburgerStyle('iconSize', value)
								}
								min={0}
								step={1}
							/>
						</>
					)}
					<RangeControl
						label={__('Width', 'responsive-menu')}
						value={hamburgerStyle.width}
						onChange={(value) =>
							updateHamburgerStyle('width', value)
						}
						min={0}
						max={300}
						step={1}
					/>
					<RangeControl
						label={__('Height', 'responsive-menu')}
						value={hamburgerStyle.height}
						onChange={(value) =>
							updateHamburgerStyle('height', value)
						}
						min={0}
						max={300}
						step={1}
					/>
					<BoxControl
						label={__('Border Radius', 'responsive-menu')}
						values={hamburgerStyle.borderRadius}
						onChange={(value) =>
							updateHamburgerStyle('borderRadius', value)
						}
						min={0}
						max={300}
						step={1}
					/>
					<SelectControl
						label={__('Side', 'responsive-menu')}
						value={hamburgerStyle.side}
						options={[
							{ label: 'Left', value: 'left' },
							{ label: 'Right', value: 'right' },
						]}
						onChange={(side) => updateHamburgerStyle('side', side)}
					/>
				</PanelBody>
				<PanelBody title={__('Hamburger Text', 'responsive-menu')}>
					<TextControl
						label={__('Text', 'responsive-menu')}
						value={hamburgerText.text}
						onChange={(value) => {
							updateHamburgerText('text', value);
						}}
					/>
					<TextControl
						label={__('Active Text', 'responsive-menu')}
						value={hamburgerText.activeText}
						onChange={(value) => {
							updateHamburgerText('activeText', value);
						}}
					/>
					<ToggleGroupControl
						label={__('Text Position', 'responsive-menu')}
						value={hamburgerText.position}
						isBlock
						onChange={(value) => {
							updateHamburgerText('position', value);
						}}
					>
						<ToggleGroupControlOption
							value="left"
							label={__('left', 'responsive-menu')}
						/>
						<ToggleGroupControlOption
							value="top"
							label={__('top', 'responsive-menu')}
						/>
						<ToggleGroupControlOption
							value="right"
							label={__('Right', 'responsive-menu')}
						/>
						<ToggleGroupControlOption
							value="bottom"
							label={__('Bottom', 'responsive-menu')}
						/>
					</ToggleGroupControl>
					{hasfontFamilies && (
						<FontFamilyControl
							fontFamilies={fontFamiliesList}
							value={hamburgerText?.fontFamily}
							onChange={(fontFamily) =>
								updateHamburgerText('fontFamily', fontFamily)
							}
							size="__unstable-large"
							__nextHasNoMarginBottom
						/>
					)}
					<FontSizePicker
						value={hamburgerText.size}
						fallbackFontSize={14}
						onChange={(newFontSize) => {
							updateHamburgerText('size', newFontSize);
						}}
					/>
					<RangeControl
						label={__('Line Height', 'responsive-menu')}
						value={hamburgerText.lineHeight}
						onChange={(delay) =>
							updateHamburgerText('lineHeight', delay)
						}
						min={0}
						step={1}
					/>
				</PanelBody>
				<PanelBody title={__('Behaviour', 'responsive-menu')}>
					<p>{__('Hide menu on', 'responsive-menu')}</p>
					<ToggleControl
						label={__('Click Link', 'responsive-menu')}
						checked={menuBehaviour.linkClick}
						onChange={(value) => {
							updateMenuBehaviour('linkClick', value);
						}}
					/>
					<ToggleControl
						label={__('Click Page', 'responsive-menu')}
						checked={menuBehaviour.pageClick}
						onChange={(value) => {
							updateMenuBehaviour('pageClick', value);
						}}
					/>
					<ToggleControl
						label={__('Page Scroll', 'responsive-menu')}
						checked={menuBehaviour.pageScroll}
						onChange={(value) => {
							updateMenuBehaviour('pageScroll', value);
						}}
					/>
				</PanelBody>
			</InspectorControls>
			{hamburgerStyle?.type && renderCSS}
			<nav {...blockProps}>
				<button
					type="button"
					aria-controls={`rmp-block-container-${id}`}
					aria-label={__('Menu Trigger', 'responsive-menu')}
					id={`rmp-block-menu-trigger-${id}`}
					data-hide-link-click={menuBehaviour.linkClick ? true : false}
					data-hide-page-click={menuBehaviour.pageClick ? true : false}
					data-hide-on-scroll={menuBehaviour.pageScroll ? true : false}
					onClick={rmpToggleMenu}
					className={`rmp-block-menu-trigger rmp-menu-trigger-boring rmp-mobile-device-menu rmp-block-menu-trigger-position-${hamburgerStyle?.side} rmp-block-text-position-${hamburgerText?.position} ${activeMenu ? 'rmp-block-active' : ''}`}
				>
					<span className="rmp-block-trigger-box">
						{hamburgerStyle && hamburgerStyle.type === 'icon' && (
							<>
								{hamburgerStyle.icon && (
									<span className="rmp-block-trigger-icon rmp-block-trigger-icon-inactive">
										{renderSVG(hamburgerStyle.icon, hamburgerStyle?.iconSize)}
									</span>
								)}
								{hamburgerStyle.activeIcon && (
									<span className="rmp-block-trigger-icon rmp-block-trigger-icon-active">
										{renderSVG(hamburgerStyle.activeIcon, hamburgerStyle?.iconSize)}
									</span>
								)}
							</>
						)}
						{hamburgerStyle && hamburgerStyle.type === 'image' && (
							<>
								{hamburgerStyle.icon && (
									<span className="rmp-block-trigger-icon rmp-block-trigger-icon-inactive">
										<img
											src={hamburgerStyle?.image}
											alt={__('Hamburger Image', 'responsive-menu')}
										/>
									</span>
								)}
								{hamburgerStyle.activeIcon && (
									<span className="rmp-block-trigger-icon rmp-block-trigger-icon-active">
										<img
											src={hamburgerStyle?.activeImage}
											alt={__('Hamburger Active Image', 'responsive-menu')}
										/>
									</span>
								)}
							</>
						)}
						{hamburgerStyle && hamburgerStyle.type === 'hamburger' && (
							<span className="rmp-block-trigger-inner"></span>
						)}
					</span>
					{hamburgerText && ( hamburgerText.text || hamburgerText.activeText ) && (
						<span className="rmp-block-trigger-label">
							{hamburgerText && hamburgerText.text && (
								<span className="rmp-block-trigger-label-inactive">
									{hamburgerText?.text}
								</span>
							)}
							{hamburgerText && hamburgerText.activeText && (
								<span className="rmp-block-trigger-label-active">
									{hamburgerText?.activeText}
								</span>
							)}
						</span>
					)}
				</button>
				<div
					className={`rmp-block-container rmp-block-container-${id} rmp-block-container-direction-${menuAnimation?.direction} rmp-block-container-animation-${menuAnimation?.type} ${activeMenu ? 'rmp-block-active' : ''}`}
					id={`rmp-block-container-${id}`}
					ref={menuRef}
				>
					<InnerBlocks
						template={[
							[ 'core/heading', { level: 3, content: 'Responsive Menu', textAlign: 'center' } ],
							['core/paragraph', { content: 'Add more content here...', align: 'center' }],
							['rmp/menu-items', {}, [
								['core/navigation-link', { label: 'Home', url: '/' }],
								['core/navigation-link', { label: 'Custom Link', url: 'https://example.com' }]
							]]
						]}
						allowedBlocks={[
							'rmp/menu-items',
							'core/heading',
							'core/search',
							'core/social-links',
							'core/spacer',
							'core/image',
							'core/paragraph',
							'core/button',
						]}
						templateLock={false}
					/>
				</div>
			</nav>
		</>
	);
}
