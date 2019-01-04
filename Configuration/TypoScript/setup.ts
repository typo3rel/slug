
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


config.tx_extbase.persistence.classes {
    GOCHILLA\Slug\Domain\Model\Page {
        mapping {
            tableName = pages
            columns {
                uid.mapOnProperty = uid
                sys_language_uid.mapOnProperty = language
                hidden.mapOnProperty = hidden
                doctype.mapOnProperty = doctype
                is_siteroot.mapOnProperty = isSiteroot
                title.mapOnProperty = title
                slug.mapOnProperty = slug
                tx_slug_locked.mapOnProperty = slugLock
                l10n_parent.mapOnProperty = l10nParent
            }
        }
    }
} 