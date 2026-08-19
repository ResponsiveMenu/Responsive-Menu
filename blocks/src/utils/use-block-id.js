/**
 * A block id that stays unique when a block is duplicated.
 *
 * The id keys the generated CSS (`.rmp-block-navigator-<id>`), and duplicating
 * or copy-pasting a block copies its attributes — including the id. Two blocks
 * on one page would then share a selector, and only the first would get its
 * stylesheet printed at all, because the handle is marked done once it has been
 * output. So an id that is already claimed by a different block is re-minted
 * from that block's own clientId.
 */

import { useEffect } from '@wordpress/element';

const claimedBy = new Map();

/**
 * Claim `id` for `clientId`, re-minting when it belongs to another block.
 *
 * @param {string}   id            Current id attribute.
 * @param {string}   clientId      This block's editor id.
 * @param {Function} setAttributes Block setter.
 */
export default function useBlockId(id, clientId, setAttributes) {
	useEffect(() => {
		const owner = id ? claimedBy.get(id) : undefined;

		if (!id || (owner && owner !== clientId)) {
			claimedBy.set(clientId, clientId);
			setAttributes({ id: clientId });
			return;
		}

		claimedBy.set(id, clientId);
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [id]);

	// Release the claim when the block goes away, so re-adding one that had
	// been deleted does not needlessly re-mint.
	useEffect(() => {
		return () => {
			if (id && claimedBy.get(id) === clientId) {
				claimedBy.delete(id);
			}
		};
		// eslint-disable-next-line react-hooks/exhaustive-deps
	}, [id]);
}
