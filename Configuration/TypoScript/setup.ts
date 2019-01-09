
# Module configuration
module.tx_slug {
    persistence {
        storagePid = {$module.tx_slug.persistence.storagePid}
    }
    view {
        templateRootPaths.0 = EXT:slug/Resources/Private/Backend/Templates/
        templateRootPaths.1 = {$module.tx_slug.view.templateRootPath}
        partialRootPaths.0 = EXT:slug/Resources/Private/Backend/Partials/
        partialRootPaths.1 = {$module.tx_slug.view.partialRootPath}
        layoutRootPaths.0 = EXT:slug/Resources/Private/Backend/Layouts/
        layoutRootPaths.1 = {$module.tx_slug.view.layoutRootPath}
    }
}