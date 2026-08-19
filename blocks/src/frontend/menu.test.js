/**
 * Behavioural tests for the frontend runtime.
 *
 * These drive the exact markup `save.js` produces, so they are the substitute
 * for clicking around a real site: opening, closing, keyboard access, the
 * desktop switch and sub-menu behaviour are all asserted here.
 */

import ResponsiveMenu from './menu';

/**
 * Stub `matchMedia` with a controllable viewport width.
 *
 * @param {number} width Initial viewport width.
 * @return {Function} Setter that changes the width and notifies listeners.
 */
function mockMatchMedia(width) {
	const queries = [];
	let current = width;

	window.matchMedia = (query) => {
		const minWidth = parseInt(
			(query.match(/min-width:\s*(\d+)px/) || [])[1],
			10
		);
		const isHoverQuery = query.includes('hover');

		const mql = {
			get matches() {
				if (isHoverQuery) {
					return true;
				}
				return Number.isFinite(minWidth) ? current >= minWidth : false;
			},
			listeners: [],
			minWidth,
			addEventListener(type, listener) {
				this.listeners.push(listener);
			},
			removeEventListener(type, listener) {
				this.listeners = this.listeners.filter((l) => l !== listener);
			},
		};

		queries.push(mql);
		return mql;
	};

	return (nextWidth) => {
		current = nextWidth;
		queries.forEach((mql) =>
			mql.listeners.forEach((listener) =>
				listener({ matches: mql.matches })
			)
		);
	};
}

/**
 * Build the markup the block saves.
 *
 * @param {Object} options Data attributes to set on the nav / list.
 * @return {HTMLElement} The nav element, attached to the document.
 */
function renderMenu(options = {}) {
	const {
		breakpoint = 768,
		nav = {},
		list = {},
		submenus = true,
	} = options;

	document.body.innerHTML = `
		<nav class="rmp-block-navigator rmp-block-navigator-abc"
			data-breakpoint="${breakpoint}"
			data-direction="left"
			${Object.entries(nav)
				.map(([key, value]) => `${key}="${value}"`)
				.join(' ')}>
			<button type="button" class="rmp-block-menu-trigger"
				aria-controls="rmp-block-container-abc" aria-expanded="false">
				<span class="rmp-block-trigger-box"></span>
			</button>
			<div class="rmp-block-container rmp-block-container-abc"
				id="rmp-block-container-abc">
				<ul class="wp-block-rmp-menu-items"
					${Object.entries(list)
						.map(([key, value]) => `${key}="${value}"`)
						.join(' ')}
					data-submenu-icon="▼" data-submenu-active-icon="▲"
					data-submenu-icon-type="text">
					<li class="wp-block-navigation-item">
						<a href="/one">One</a>
					</li>
					${
						submenus
							? `
					<li class="wp-block-navigation-item wp-block-navigation-submenu">
						<a href="/two">Two</a>
						<ul class="wp-block-navigation__submenu-container">
							<li class="wp-block-navigation-item wp-block-navigation-submenu">
								<a href="/two-a">Two A</a>
								<ul class="wp-block-navigation__submenu-container">
									<li class="wp-block-navigation-item"><a href="/deep">Deep</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<li class="wp-block-navigation-item wp-block-navigation-submenu">
						<a href="/three">Three</a>
						<ul class="wp-block-navigation__submenu-container">
							<li class="wp-block-navigation-item"><a href="/three-a">Three A</a></li>
						</ul>
					</li>`
							: ''
					}
				</ul>
			</div>
		</nav>`;

	return document.querySelector('.rmp-block-navigator');
}

const trigger = () => document.querySelector('.rmp-block-menu-trigger');
const container = () => document.querySelector('.rmp-block-container');
const submenuItems = () =>
	Array.from(document.querySelectorAll('.wp-block-navigation-submenu'));

beforeEach(() => {
	document.body.className = '';
	mockMatchMedia(400);
});

describe('opening and closing', () => {
	it('starts closed, and hidden from assistive technology', () => {
		const root = renderMenu();
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		expect(trigger().getAttribute('aria-expanded')).toBe('false');
		expect(container().getAttribute('aria-hidden')).toBe('true');
		expect(container().classList.contains('rmp-block-active')).toBe(false);
	});

	it('opens on click and exposes the panel', () => {
		const root = renderMenu();
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		trigger().click();

		expect(trigger().getAttribute('aria-expanded')).toBe('true');
		expect(container().hasAttribute('aria-hidden')).toBe(false);
		expect(container().classList.contains('rmp-block-active')).toBe(true);
		expect(root.classList.contains('rmp-block-active')).toBe(true);
	});

	it('closes again on a second click and returns focus', () => {
		const root = renderMenu();
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		trigger().click();
		// A browser focuses a button when it is clicked; jsdom does not, so
		// put focus where a real interaction would have left it.
		trigger().focus();
		trigger().click();

		expect(trigger().getAttribute('aria-expanded')).toBe('false');
		expect(container().getAttribute('aria-hidden')).toBe('true');
		expect(document.activeElement).toBe(trigger());
	});

	it('closes on Escape', () => {
		const root = renderMenu();
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		trigger().click();
		document.dispatchEvent(
			new window.KeyboardEvent('keydown', { key: 'Escape', bubbles: true })
		);

		expect(trigger().getAttribute('aria-expanded')).toBe('false');
	});

	it('does not close on Escape when that is switched off', () => {
		const root = renderMenu({ nav: { 'data-close-on-esc': 'false' } });
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		trigger().click();
		document.dispatchEvent(
			new window.KeyboardEvent('keydown', { key: 'Escape', bubbles: true })
		);

		expect(trigger().getAttribute('aria-expanded')).toBe('true');
	});

	it('closes when a link is clicked, if configured', () => {
		const root = renderMenu({ nav: { 'data-close-on-link': 'true' } });
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		trigger().click();
		container().querySelector('a[href]').click();

		expect(trigger().getAttribute('aria-expanded')).toBe('false');
	});

	it('ignores link clicks when that is not configured', () => {
		const root = renderMenu();
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		trigger().click();
		container().querySelector('a[href]').click();

		expect(trigger().getAttribute('aria-expanded')).toBe('true');
	});

	it('closes when the page behind is clicked, if configured', () => {
		const root = renderMenu({ nav: { 'data-close-on-body-click': 'true' } });
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		trigger().click();
		document.body.click();

		expect(trigger().getAttribute('aria-expanded')).toBe('false');
	});

	it('locks page scrolling only while open, and only if configured', () => {
		const root = renderMenu({ nav: { 'data-scroll-lock': 'true' } });
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		trigger().click();
		expect(document.body.classList.contains('rmp-block-menu-open')).toBe(
			true
		);

		trigger().click();
		expect(document.body.classList.contains('rmp-block-menu-open')).toBe(
			false
		);
	});

	it('binds each close trigger once, however often the menu is toggled', () => {
		const root = renderMenu({ nav: { 'data-close-on-link': 'true' } });
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		const link = container().querySelector('a[href]');
		let closes = 0;
		root.addEventListener('rmp-menu-close', () => {
			closes += 1;
		});

		for (let i = 0; i < 3; i++) {
			trigger().click();
			link.click();
		}

		// Three opens, three closes — not 1 + 2 + 3 from stacked listeners.
		expect(closes).toBe(3);
	});
});

describe('desktop mode', () => {
	it('switches to the inline layout above the breakpoint', () => {
		const setWidth = mockMatchMedia(1200);
		const root = renderMenu({ breakpoint: 768 });
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		expect(root.classList.contains('rmp-desktop-mode')).toBe(true);
		expect(container().hasAttribute('aria-hidden')).toBe(false);
		expect(trigger().getAttribute('aria-hidden')).toBe('true');

		setWidth(400);

		expect(root.classList.contains('rmp-desktop-mode')).toBe(false);
		expect(container().getAttribute('aria-hidden')).toBe('true');
		expect(trigger().hasAttribute('aria-hidden')).toBe(false);
	});

	it('closes an open panel when the viewport grows past the breakpoint', () => {
		const setWidth = mockMatchMedia(400);
		const root = renderMenu({ breakpoint: 768 });
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		trigger().click();
		expect(container().classList.contains('rmp-block-active')).toBe(true);

		setWidth(1200);

		expect(container().classList.contains('rmp-block-active')).toBe(false);
		expect(trigger().getAttribute('aria-expanded')).toBe('false');
	});
});

describe('sub-menus', () => {
	const arrowOf = (submenu) =>
		submenu.querySelector(':scope > .rmp-block-menu-subarrow');

	it('injects one toggle button per sub-menu', () => {
		const root = renderMenu();
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		expect(document.querySelectorAll('.rmp-block-menu-subarrow')).toHaveLength(
			3
		);
		expect(arrowOf(submenuItems()[0]).getAttribute('aria-expanded')).toBe(
			'false'
		);
	});

	it('renders a text arrow as text, never as markup', () => {
		const root = renderMenu({
			list: { 'data-submenu-icon': '<img src=x onerror=alert(1)>' },
		});
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		const arrow = arrowOf(submenuItems()[0]);
		expect(arrow.querySelector('img')).toBeNull();
		expect(arrow.textContent).toBe('<img src=x onerror=alert(1)>');
	});

	it('toggles a sub-menu and swaps the arrow', () => {
		const root = renderMenu();
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		const submenu = submenuItems()[0];
		const arrow = arrowOf(submenu);

		arrow.click();
		expect(submenu.classList.contains('rmp-block-active-submenu')).toBe(
			true
		);
		expect(arrow.getAttribute('aria-expanded')).toBe('true');
		expect(arrow.textContent).toBe('▲');

		arrow.click();
		expect(submenu.classList.contains('rmp-block-active-submenu')).toBe(
			false
		);
		expect(arrow.textContent).toBe('▼');
	});

	it('closes nested sub-menus when their parent closes', () => {
		const root = renderMenu();
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		const [parent, child] = submenuItems();

		arrowOf(parent).click();
		arrowOf(child).click();
		expect(child.classList.contains('rmp-block-active-submenu')).toBe(true);

		arrowOf(parent).click();
		expect(child.classList.contains('rmp-block-active-submenu')).toBe(
			false
		);
		expect(arrowOf(child).getAttribute('aria-expanded')).toBe('false');
	});

	it('closes siblings in accordion mode', () => {
		const root = renderMenu({ list: { 'data-use-accordion': 'true' } });
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		const submenus = submenuItems();
		const first = submenus[0];
		const third = submenus[2];

		arrowOf(first).click();
		arrowOf(third).click();

		expect(third.classList.contains('rmp-block-active-submenu')).toBe(true);
		expect(first.classList.contains('rmp-block-active-submenu')).toBe(
			false
		);
	});

	it('expands everything at boot when asked, accordion notwithstanding', () => {
		const root = renderMenu({
			list: { 'data-auto-expand': 'true', 'data-use-accordion': 'true' },
		});
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		submenuItems().forEach((submenu) => {
			expect(submenu.classList.contains('rmp-block-active-submenu')).toBe(
				true
			);
		});
	});

	it('tags nested lists with their depth', () => {
		const root = renderMenu();
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		expect(
			document.querySelectorAll('.rmp-block-submenu-level-1')
		).toHaveLength(2);
		expect(
			document.querySelectorAll('.rmp-block-submenu-level-2')
		).toHaveLength(1);
	});

	it('opens the sub-menu of the current page when configured', () => {
		const root = renderMenu({
			list: { 'data-auto-expand-current': 'true' },
		});
		submenuItems()[0].classList.add('current-menu-item');
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		expect(
			submenuItems()[0].classList.contains('rmp-block-active-submenu')
		).toBe(true);
		expect(
			submenuItems()[2].classList.contains('rmp-block-active-submenu')
		).toBe(false);
	});

	it('closes desktop dropdowns when the page is clicked', () => {
		const setWidth = mockMatchMedia(1200);
		const root = renderMenu({ breakpoint: 768 });
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);
		setWidth(1200);

		const submenu = submenuItems()[0];
		arrowOf(submenu).click();
		expect(submenu.classList.contains('rmp-block-active-submenu')).toBe(
			true
		);

		document.body.click();
		expect(submenu.classList.contains('rmp-block-active-submenu')).toBe(
			false
		);
	});
});

describe('resilience', () => {
	it('does nothing rather than throwing when the markup is incomplete', () => {
		document.body.innerHTML = '<nav class="rmp-block-navigator"></nav>';

		expect(
			() =>
				new ResponsiveMenu(
					document.querySelector('.rmp-block-navigator')
				)
		).not.toThrow();
	});

	it('is safe to construct twice over the same markup', () => {
		const root = renderMenu();
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);
		// eslint-disable-next-line no-new
		new ResponsiveMenu(root);

		// The second pass must not double up the injected arrows.
		expect(document.querySelectorAll('.rmp-block-menu-subarrow')).toHaveLength(
			3
		);
	});
});
