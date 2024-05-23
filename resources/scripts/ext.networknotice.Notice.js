( function ( window, document ) {
	'use strict';

	const LOCAL_STORAGE_KEY = 'networknotice';

	function init() {
		if ( 'localStorage' in window ) {
			document.querySelectorAll( '[data-component="network-notice"]' ).forEach( function( notice ) {
				const key = notice.dataset.id;
				if ( isInStorage( key ) ) {
					notice.classList.add( 'd-none' );
				} else {
					const closeButton = notice.querySelector(
						'[data-component="network-notice-close-button"]'
					);
					closeButton.onclick = function() {
						notice.classList.add( 'd-none' );
						putIntoStorage( key );
					};
				}
			} );
		}
	}

	function getItemsFromStorage() {
		const items = localStorage.getItem( LOCAL_STORAGE_KEY );
		try {
			return items ? JSON.parse( items ) : [];
		} catch ( e ) {
			return [ ];
		}
	}

	function putIntoStorage( key ) {
		const items = getItemsFromStorage();
		if ( !items.includes( key ) ) {
			items.push( key );
			localStorage.setItem( LOCAL_STORAGE_KEY, JSON.stringify( items ) );
		}
	}

	function isInStorage( key ) {
		const items = getItemsFromStorage();
		return items.includes( key );
	}

	/**
	 * Check on document readyState
	 */
	if ( [ 'interactive', 'complete' ].includes( document.readyState ) ) {
		init();
	} else {
		document.addEventListener( 'readystatechange', () => {
			if ( [ 'interactive', 'complete' ].includes( document.readyState ) ) {
				init();
			}
		}, { once: true } );
	}
}( window, document ) );
