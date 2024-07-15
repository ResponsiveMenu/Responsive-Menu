/**
 * External dependencies
 */
import { includes, merge, pickBy } from 'lodash';

export default function DynamicStyles(attributes) {
	const {
		menuContainerStyle,
		menuAnimation,
		hamburgerStyle,
		hamburgerText,
		menuStyle,
		submenuStyle,
		submenuIndentation,
		triggerIcon,
	} = attributes;

	const transformData = (data, fallback = {}) => {
		let output = {};
		merge(output, fallback, data);
		return `${output?.top} ${output?.right} ${output?.bottom} ${output?.left}`;
	};
	const transformPosition = (data, fallback = {}) => {
		let output = {};
		merge(output, fallback, data);
		if (typeof data === 'object' && Object.keys(data).length === 2) {
			return `${output?.x * 100 + '%'} ${output?.y ** 100 + '%'}`;
		}
		return data;
	};
	const transformBorder = (data, type, fallback = {}) => {
		let output = {};
		merge(output, processBorder(fallback), processBorder(data));
		let newvar = output[type];
		return `${newvar?.width} ${newvar?.style} ${newvar?.color}`;
	};

	const processBorder = (data) => {
		if (typeof data === 'object' && Object.keys(data).length === 3) {
			return {
				top: data,
				right: data,
				bottom: data,
				left: data,
			};
		}
		return data;
	};

	const styleProps = pickBy(
		{
			'--rmp--menu-container-text-color': menuContainerStyle?.color,
			'--rmp--menu-container-background-color':
				menuContainerStyle?.background,
			'--rmp--menu-container-background-image':
				'url(' + menuContainerStyle?.backgroundImage + ')',
			'--rmp--menu-container-background-position': transformPosition(
				menuContainerStyle?.backgroundPosition
			),
			'--rmp--menu-container-background-repeat':
				menuContainerStyle?.backgroundRepeat,
			'--rmp--menu-container-background-size':
				menuContainerStyle?.backgroundSize,
			'--rmp--menu-container-padding': transformData(
				menuContainerStyle?.padding
			),
			'--rmp--menu-container-width': menuContainerStyle?.menuWidth + '%',
			'--rmp--menu-container-max-width':
				menuContainerStyle?.menuMaximumWidth + 'px',
			'--rmp--menu-container-min-width':
				menuContainerStyle?.menuMinimumWidth + 'px',
			'--rmp--menu-container-animation-transition-duration':
				'transform ' + menuAnimation?.transitionDuration + 's',
			'--rmp--menu-hamburger-line-spacing':
				hamburgerStyle?.lineSpacing + 'px',
			'--rmp--menu-hamburger-line-width':
				hamburgerStyle?.lineWidth + 'px',
			'--rmp--menu-hamburger-line-height':
				hamburgerStyle?.lineHeight + 'px',
			'--rmp--menu-hamburger-width': hamburgerStyle?.width + 'px',
			'--rmp--menu-hamburger-height': hamburgerStyle?.height + 'px',
			'--rmp--menu-hamburger-color': hamburgerStyle?.color,
			'--rmp--menu-hamburger-hover-color': hamburgerStyle?.hoverColor,
			'--rmp--menu-hamburger-active-color': hamburgerStyle?.activeColor,
			'--rmp--menu-hamburger-background': hamburgerStyle?.background,
			'--rmp--menu-hamburger-hover-background':
				hamburgerStyle?.hoverBackground,
			'--rmp--menu-hamburger-active-background':
				hamburgerStyle?.activeBackground,
			'--rmp--menu-hamburger-border-radius': transformData(
				hamburgerStyle?.borderRadius
			),
			'--rmp--menu-hamburger-text-font': hamburgerText?.fontFamily,
			'--rmp--menu-hamburger-text-size': hamburgerText?.size,
			'--rmp--menu-hamburger-icon-size': hamburgerStyle?.iconSize + 'px',
			'--rmp--menu-hamburger-text-line-height':
				hamburgerText?.lineHeight + 'px',
			'--rmp--menu-hamburger-trigger-box-height': `${2 * hamburgerStyle?.lineSpacing + 2 * hamburgerStyle?.lineHeight}px`,
			'--rmp--menu-item-height': menuStyle?.itemHeight + 'px',
			'--rmp--menu-item-line-height': menuStyle?.lineHeight + 'px',
			'--rmp--menu-item-padding': transformData(menuStyle?.padding),
			'--rmp--menu-item-font-size': menuStyle?.fontSize,
			'--rmp--menu-item-font-wieght': menuStyle?.fontWieght,
			'--rmp--menu-item-font-family': menuStyle?.fontFamily,
			'--rmp--menu-item-text-align': menuStyle?.textAlign,
			'--rmp--menu-item-letter-spacing': menuStyle?.letterSpacing + 'px',
			'--rmp--menu-item-letter-case': menuStyle?.letterCase,
			'--rmp--menu-item-word-wrap': menuStyle?.wordWrap,
			'--rmp--menu-item-color': menuStyle?.color,
			'--rmp--menu-item-hover-color': menuStyle?.hoverColor,
			'--rmp--menu-item-active-color': menuStyle?.activeColor,
			'--rmp--menu-item-active-hover-color': menuStyle?.activeHoverColor,
			'--rmp--menu-item-background': menuStyle?.background,
			'--rmp--menu-item-hover-background': menuStyle?.backgroundHover,
			'--rmp--menu-item-active-background': menuStyle?.backgroundActive,
			'--rmp--menu-item-active-hover-background':
				menuStyle?.backgroundActiveHover,
			'--rmp--menu-item-border-top': transformBorder(
				menuStyle?.border,
				'top'
			),
			'--rmp--menu-item-border-right': transformBorder(
				menuStyle?.border,
				'right'
			),
			'--rmp--menu-item-border-bottom': transformBorder(
				menuStyle?.border,
				'bottom'
			),
			'--rmp--menu-item-border-left': transformBorder(
				menuStyle?.border,
				'left'
			),
			'--rmp--menu-item-hover-border-top': transformBorder(
				menuStyle?.borderHover,
				'top'
			),
			'--rmp--menu-item-hover-border-right': transformBorder(
				menuStyle?.borderHover,
				'right'
			),
			'--rmp--menu-item-hover-border-bottom': transformBorder(
				menuStyle?.borderHover,
				'bottom'
			),
			'--rmp--menu-item-hover-border-left': transformBorder(
				menuStyle?.borderHover,
				'left'
			),
			'--rmp--menu-item-active-border-top': transformBorder(
				menuStyle?.borderActive,
				'top'
			),
			'--rmp--menu-item-active-border-right': transformBorder(
				menuStyle?.borderActive,
				'right'
			),
			'--rmp--menu-item-active-border-bottom': transformBorder(
				menuStyle?.borderActive,
				'bottom'
			),
			'--rmp--menu-item-active-border-left': transformBorder(
				menuStyle?.borderActive,
				'left'
			),
			'--rmp--menu-item-active-hover-border-top': transformBorder(
				menuStyle?.borderActiveHover,
				'top'
			),
			'--rmp--menu-item-active-hover-border-right': transformBorder(
				menuStyle?.borderActiveHover,
				'right'
			),
			'--rmp--menu-item-active-hover-border-bottom': transformBorder(
				menuStyle?.borderActiveHover,
				'bottom'
			),
			'--rmp--menu-item-active-hover-border-left': transformBorder(
				menuStyle?.borderActiveHover,
				'left'
			),
			'--rmp--menu-subitem-line-height': submenuStyle?.lineHeight + 'px',
			'--rmp--menu-subitem-padding': transformData(submenuStyle?.padding),
			'--rmp--menu-subitem-font-size': submenuStyle?.fontSize,
			'--rmp--menu-subitem-font-wieght': submenuStyle?.fontWieght,
			'--rmp--menu-subitem-font-family': submenuStyle?.fontFamily,
			'--rmp--menu-subitem-text-align': submenuStyle?.textAlign,
			'--rmp--menu-subitem-letter-spacing':
				submenuStyle?.letterSpacing + 'px',
			'--rmp--menu-subitem-letter-case': submenuStyle?.letterCase,
			'--rmp--menu-subitem-word-wrap': submenuStyle?.wordWrap,
			'--rmp--menu-subitem-color': submenuStyle?.color,
			'--rmp--menu-subitem-hover-color': submenuStyle?.hoverColor,
			'--rmp--menu-subitem-active-color': submenuStyle?.activeColor,
			'--rmp--menu-subitem-active-hover-color':
				submenuStyle?.activeHoverColor,
			'--rmp--menu-subitem-background': submenuStyle?.backgroundColor,
			'--rmp--menu-subitem-hover-background':
				submenuStyle?.backgroundHoverColor,
			'--rmp--menu-subitem-active-background':
				submenuStyle?.backgroundActiveColor,
			'--rmp--menu-subitem-active-hover-background':
				submenuStyle?.backgroundActiveHoverColor,
			'--rmp--menu-subitem-border-top': transformBorder(
				submenuStyle?.border,
				'top'
			),
			'--rmp--menu-subitem-border-right': transformBorder(
				submenuStyle?.border,
				'right'
			),
			'--rmp--menu-subitem-border-bottom': transformBorder(
				submenuStyle?.border,
				'bottom'
			),
			'--rmp--menu-subitem-border-left': transformBorder(
				submenuStyle?.border,
				'left'
			),
			'--rmp--menu-subitem-hover-border-top': transformBorder(
				submenuStyle?.borderHover,
				'top'
			),
			'--rmp--menu-subitem-hover-border-right': transformBorder(
				submenuStyle?.borderHover,
				'right'
			),
			'--rmp--menu-subitem-hover-border-bottom': transformBorder(
				submenuStyle?.borderHover,
				'bottom'
			),
			'--rmp--menu-subitem-hover-border-left': transformBorder(
				submenuStyle?.borderHover,
				'left'
			),
			'--rmp--menu-subitem-active-border-top': transformBorder(
				submenuStyle?.borderActive,
				'top'
			),
			'--rmp--menu-subitem-active-border-right': transformBorder(
				submenuStyle?.borderActive,
				'right'
			),
			'--rmp--menu-subitem-active-border-bottom': transformBorder(
				submenuStyle?.borderActive,
				'bottom'
			),
			'--rmp--menu-subitem-active-border-left': transformBorder(
				submenuStyle?.borderActive,
				'left'
			),
			'--rmp--menu-subitem-active-hover-border-top': transformBorder(
				submenuStyle?.borderActiveHover,
				'top'
			),
			'--rmp--menu-subitem-active-hover-border-right': transformBorder(
				submenuStyle?.borderActiveHover,
				'right'
			),
			'--rmp--menu-subitem-active-hover-border-bottom': transformBorder(
				submenuStyle?.borderActiveHover,
				'bottom'
			),
			'--rmp--menu-subitem-active-hover-border-left': transformBorder(
				submenuStyle?.borderActiveHover,
				'left'
			),
			'--rmp--menu-subitem-indentation-child1':
				submenuIndentation?.childLevel1 + '%',
			'--rmp--menu-subitem-indentation-child2':
				submenuIndentation?.childLevel2 + '%',
			'--rmp--menu-subitem-indentation-child3':
				submenuIndentation?.childLevel3 + '%',
			'--rmp--menu-subitem-indentation-child4':
				submenuIndentation?.childLevel4 + '%',
			'--rmp--menu-subitem-trigger-icon-width': triggerIcon?.width + 'px',
			'--rmp--menu-subitem-trigger-icon-height':
				triggerIcon?.height + 'px',
			'--rmp--menu-subitem-trigger-icon-color': triggerIcon?.color,
			'--rmp--menu-subitem-trigger-icon-hover-color':
				triggerIcon?.hoverColor,
			'--rmp--menu-subitem-trigger-icon-active-color':
				triggerIcon?.activeColor,
			'--rmp--menu-subitem-trigger-icon-active-hover-color':
				triggerIcon?.activeHoverColor,
			'--rmp--menu-subitem-trigger-icon-background':
				triggerIcon?.backgroundColor,
			'--rmp--menu-subitem-trigger-icon-hover-background':
				triggerIcon?.backgroundHoverColor,
			'--rmp--menu-subitem-trigger-icon-active-background':
				triggerIcon?.backgroundActiveColor,
			'--rmp--menu-subitem-trigger-icon-active-hover-background':
				triggerIcon?.backgroundActiveHoverColor,
			'--rmp--menu-subitem-trigger-border-top': transformBorder(
				triggerIcon?.border,
				'top'
			),
			'--rmp--menu-subitem-trigger-border-right': transformBorder(
				triggerIcon?.border,
				'right'
			),
			'--rmp--menu-subitem-trigger-border-bottom': transformBorder(
				triggerIcon?.border,
				'bottom'
			),
			'--rmp--menu-subitem-trigger-border-left': transformBorder(
				triggerIcon?.border,
				'left'
			),
			'--rmp--menu-subitem-trigger-hover-border-top': transformBorder(
				triggerIcon?.borderHover,
				'top'
			),
			'--rmp--menu-subitem-trigger-hover-border-right': transformBorder(
				triggerIcon?.borderHover,
				'right'
			),
			'--rmp--menu-subitem-trigger-hover-border-bottom': transformBorder(
				triggerIcon?.borderHover,
				'bottom'
			),
			'--rmp--menu-subitem-trigger-hover-border-left': transformBorder(
				triggerIcon?.borderHover,
				'left'
			),
			'--rmp--menu-subitem-trigger-active-border-top': transformBorder(
				triggerIcon?.borderActive,
				'top'
			),
			'--rmp--menu-subitem-trigger-active-border-right': transformBorder(
				triggerIcon?.borderActive,
				'right'
			),
			'--rmp--menu-subitem-trigger-active-border-bottom': transformBorder(
				triggerIcon?.borderActive,
				'bottom'
			),
			'--rmp--menu-subitem-trigger-active-border-left': transformBorder(
				triggerIcon?.borderActive,
				'left'
			),
			'--rmp--menu-subitem-trigger-active-hover-border-top':
				transformBorder(triggerIcon?.borderActiveHover, 'top'),
			'--rmp--menu-subitem-trigger-active-hover-border-right':
				transformBorder(triggerIcon?.borderActiveHover, 'right'),
			'--rmp--menu-subitem-trigger-active-hover-border-bottom':
				transformBorder(triggerIcon?.borderActiveHover, 'bottom'),
			'--rmp--menu-subitem-trigger-active-hover-border-left':
				transformBorder(triggerIcon?.borderActiveHover, 'left'),
		},
		(value) =>
			typeof value !== 'undefined' &&
			'' !== value &&
			'px' !== value &&
			'%' !== value &&
			's' !== value &&
			'NaN' !== value &&
			'NaNpx' !== value &&
			'none' !== value &&
			!includes(value, 'undefined')
	);

	return styleProps;
}
