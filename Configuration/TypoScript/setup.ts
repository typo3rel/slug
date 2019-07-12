
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
    settings{
        additionalTables{
            
            /*
            tx_news_domain_model_news{
                label = News
                slugField = path_segment
                titleField = title
                icon = EXT:news/Resources/Public/Icons/news_domain_model_news.svg
            }
            */
        
        }
    }
}