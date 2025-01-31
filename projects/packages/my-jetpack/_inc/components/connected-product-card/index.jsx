import { getIconBySlug } from '@automattic/jetpack-components';
import { useConnection } from '@automattic/jetpack-connection';
import PropTypes from 'prop-types';
import React, { useCallback } from 'react';
import useMyJetpackNavigate from '../../hooks/use-my-jetpack-navigate';
import { useProduct } from '../../hooks/use-product';
import ProductCard from '../product-card';

const ConnectedProductCard = ( { admin, slug, children, showMenu = false } ) => {
	const { isRegistered, isUserConnected } = useConnection();
	const { detail, status, activate, deactivate, isFetching, installStandalonePlugin } =
		useProduct( slug );
	const { name, description, manageUrl, requiresUserConnection, standalonePluginInfo } = detail;

	const navigateToConnectionPage = useMyJetpackNavigate( '/connection' );

	const navigateToAddProductPage = useMyJetpackNavigate( `add-${ slug }` );

	/*
	 * Redirect to manage URL
	 */
	const onManage = useCallback( () => {
		window.location = manageUrl;
	}, [ manageUrl ] );

	/*
	 * Redirect only if connected
	 */
	const handleActivate = useCallback( () => {
		if ( ( ! isRegistered || ! isUserConnected ) && requiresUserConnection ) {
			navigateToConnectionPage();
			return;
		}

		activate();
	}, [
		activate,
		isRegistered,
		isUserConnected,
		requiresUserConnection,
		navigateToConnectionPage,
	] );

	const handleInstallStandalone = useCallback( () => {
		installStandalonePlugin().then( () => {
			window?.location?.reload();
		} );
	}, [ installStandalonePlugin ] );

	const Icon = getIconBySlug( slug );

	return (
		<ProductCard
			name={ name }
			description={ description }
			status={ status }
			icon={ <Icon opacity={ 0.4 } /> }
			admin={ admin }
			isFetching={ isFetching }
			onDeactivate={ deactivate }
			slug={ slug }
			onActivate={ handleActivate }
			onAdd={ navigateToAddProductPage }
			onManage={ onManage }
			onFixConnection={ navigateToConnectionPage }
			showMenu={ showMenu }
			onInstallStandalone={ handleInstallStandalone }
			onActivateStandalone={ handleInstallStandalone }
			hasStandalonePlugin={ standalonePluginInfo?.hasStandalonePlugin }
			isStandaloneInstalled={ standalonePluginInfo?.isStandaloneInstalled }
			isStandaloneActive={ standalonePluginInfo?.isStandaloneActive }
			isConnected={ isRegistered && isUserConnected }
		>
			{ children }
		</ProductCard>
	);
};

ConnectedProductCard.propTypes = {
	children: PropTypes.node,
	admin: PropTypes.bool.isRequired,
	slug: PropTypes.string.isRequired,
};

export default ConnectedProductCard;
