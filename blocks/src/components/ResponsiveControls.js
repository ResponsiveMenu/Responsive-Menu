/**
 * Inspector controls that write to whichever device is currently selected.
 *
 * Each wrapper takes the responsive helper bundle plus the attribute group and
 * key it edits, so a panel never has to know whether it is editing desktop,
 * tablet or mobile. On a non-desktop device the control also gets a "reset to
 * inherited" button whenever it holds an override, which is the only way a user
 * can get back to inheriting once they have touched a value.
 */

import { __ } from '@wordpress/i18n';
import {
	BaseControl,
	Button,
	RangeControl,
	SelectControl,
	TextControl,
	ToggleControl,
	Tooltip,
	__experimentalBoxControl as BoxControl,
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
	__experimentalBorderBoxControl as BorderBoxControl,
} from '@wordpress/components';
import { PanelColorSettings } from '@wordpress/block-editor';
import { useInstanceId } from '@wordpress/compose';
import { rotateLeft } from '@wordpress/icons';

/**
 * The small "this device overrides the wider one" affordance.
 *
 * @param {Object} props
 * @param {Object} props.helpers Responsive helper bundle.
 * @param {string} props.group   Attribute group.
 * @param {string} props.name    Key inside the group.
 * @return {Element|null} Reset button, or null when there is nothing to reset.
 */
function ResetOverride({ helpers, group, name }) {
	if (!helpers.isResponsive || !helpers.isOverridden(group, name)) {
		return null;
	}

	return (
		<Tooltip
			text={__('Reset to inherited value', 'responsive-menu')}
		>
			<Button
				className="rmp-reset-override"
				icon={rotateLeft}
				size="small"
				onClick={() => helpers.reset(group, name)}
			/>
		</Tooltip>
	);
}

/**
 * Wraps a control with the override indicator.
 *
 * @param {Object}  props
 * @param {Object}  props.helpers  Responsive helper bundle.
 * @param {string}  props.group    Attribute group.
 * @param {string}  props.name     Key inside the group.
 * @param {Element} props.children The control itself.
 * @return {Element} Wrapped control.
 */
function Field({ helpers, group, name, children }) {
	const overridden =
		helpers.isResponsive && helpers.isOverridden(group, name);

	return (
		<div
			className={`rmp-responsive-field${overridden ? ' is-overridden' : ''}`}
		>
			{children}
			<ResetOverride helpers={helpers} group={group} name={name} />
		</div>
	);
}

/**
 * @param {Object} props         Control props plus `helpers`, `group`, `name`.
 * @param {Object} props.helpers Responsive helper bundle.
 * @param {string} props.group   Attribute group.
 * @param {string} props.name    Key inside the group.
 * @return {Element} RangeControl bound to the selected device.
 */
export function ResponsiveRange({ helpers, group, name, ...props }) {
	return (
		<Field helpers={helpers} group={group} name={name}>
			<RangeControl
				{...props}
				__nextHasNoMarginBottom
				value={helpers.get(group)[name]}
				onChange={(value) => helpers.update(group, name, value)}
			/>
		</Field>
	);
}

/**
 * @param {Object} props         Control props plus `helpers`, `group`, `name`.
 * @param {Object} props.helpers Responsive helper bundle.
 * @param {string} props.group   Attribute group.
 * @param {string} props.name    Key inside the group.
 * @return {Element} SelectControl bound to the selected device.
 */
export function ResponsiveSelect({ helpers, group, name, ...props }) {
	return (
		<Field helpers={helpers} group={group} name={name}>
			<SelectControl
				{...props}
				__nextHasNoMarginBottom
				value={helpers.get(group)[name]}
				onChange={(value) => helpers.update(group, name, value)}
			/>
		</Field>
	);
}

/**
 * @param {Object} props         Control props plus `helpers`, `group`, `name`.
 * @param {Object} props.helpers Responsive helper bundle.
 * @param {string} props.group   Attribute group.
 * @param {string} props.name    Key inside the group.
 * @return {Element} ToggleControl bound to the selected device.
 */
export function ResponsiveToggle({ helpers, group, name, ...props }) {
	return (
		<Field helpers={helpers} group={group} name={name}>
			<ToggleControl
				{...props}
				__nextHasNoMarginBottom
				checked={!!helpers.get(group)[name]}
				onChange={(value) => helpers.update(group, name, value)}
			/>
		</Field>
	);
}

/**
 * @param {Object} props         Control props plus `helpers`, `group`, `name`.
 * @param {Object} props.helpers Responsive helper bundle.
 * @param {string} props.group   Attribute group.
 * @param {string} props.name    Key inside the group.
 * @return {Element} TextControl bound to the selected device.
 */
export function ResponsiveText({ helpers, group, name, ...props }) {
	return (
		<Field helpers={helpers} group={group} name={name}>
			<TextControl
				{...props}
				__nextHasNoMarginBottom
				value={helpers.get(group)[name] ?? ''}
				onChange={(value) => helpers.update(group, name, value)}
			/>
		</Field>
	);
}

/**
 * @param {Object} props         Control props plus `helpers`, `group`, `name`.
 * @param {Object} props.helpers Responsive helper bundle.
 * @param {string} props.group   Attribute group.
 * @param {string} props.name    Key inside the group.
 * @return {Element} BoxControl bound to the selected device.
 */
export function ResponsiveBox({ helpers, group, name, ...props }) {
	return (
		<Field helpers={helpers} group={group} name={name}>
			<BoxControl
				{...props}
				values={helpers.get(group)[name]}
				onChange={(value) => helpers.update(group, name, value)}
			/>
		</Field>
	);
}

/**
 * @param {Object}  props
 * @param {Object}  props.helpers Responsive helper bundle.
 * @param {string}  props.group   Attribute group.
 * @param {string}  props.name    Key inside the group.
 * @param {string}  props.label   Control label.
 * @param {Array}   props.options `[ { value, label } ]`.
 * @param {boolean} props.isBlock Render the options full width.
 * @return {Element} ToggleGroupControl bound to the selected device.
 */
export function ResponsiveToggleGroup({
	helpers,
	group,
	name,
	label,
	options,
	isBlock = true,
}) {
	return (
		<Field helpers={helpers} group={group} name={name}>
			<ToggleGroupControl
				label={label}
				isBlock={isBlock}
				__nextHasNoMarginBottom
				value={helpers.get(group)[name]}
				onChange={(value) => helpers.update(group, name, value)}
			>
				{options.map((option) => (
					<ToggleGroupControlOption
						key={option.value}
						value={option.value}
						label={option.label}
					/>
				))}
			</ToggleGroupControl>
		</Field>
	);
}

/**
 * @param {Object} props
 * @param {Object} props.helpers Responsive helper bundle.
 * @param {string} props.group   Attribute group.
 * @param {string} props.name    Key inside the group.
 * @param {string} props.label   Control label.
 * @param {Array}  props.colors  Palette passed to the colour picker.
 * @return {Element} BorderBoxControl bound to the selected device.
 */
export function ResponsiveBorderBox({ helpers, group, name, label, colors }) {
	return (
		<Field helpers={helpers} group={group} name={name}>
			<BorderBoxControl
				label={label}
				colors={colors}
				enableAlpha
				enableStyle
				__next40pxDefaultSize
				value={helpers.get(group)[name]}
				onChange={(value) => helpers.update(group, name, value)}
			/>
		</Field>
	);
}

/**
 * A colour panel whose swatches all write to the selected device.
 *
 * @param {Object} props
 * @param {Object} props.helpers Responsive helper bundle.
 * @param {string} props.group   Attribute group.
 * @param {string} props.title   Panel title.
 * @param {Array}  props.colors  `[ { name, label } ]` keys inside the group.
 * @return {Element} Colour panel.
 */
export function ResponsiveColors({ helpers, group, title, colors }) {
	const values = helpers.get(group);

	return (
		<PanelColorSettings
			title={title}
			enableAlpha
			colorSettings={colors.map(({ name, label }) => ({
				label,
				value: values[name],
				onChange: (value) => helpers.update(group, name, value),
				disableCustomColors: false,
			}))}
		/>
	);
}

/**
 * A plain labelled slot, for controls with no responsive wrapper of their own.
 *
 * @param {Object}  props
 * @param {string}  props.label    Label.
 * @param {Element} props.children Control.
 * @return {Element} Labelled control.
 */
export function ControlRow({ label, children }) {
	const id = useInstanceId(ControlRow, 'rmp-control');

	return (
		<BaseControl id={id} label={label} __nextHasNoMarginBottom>
			{children}
		</BaseControl>
	);
}
