# TYPO3 distribution pizpalue

This extension installs a bootstrap based web site with news management.
It is tailored for the Swiss market featuring German as default language and
additional translations to French and English.

Selected third party extensions further improve the user experience. As a result
the content might be flexibly arranged in columns, registers or accordions where images
might be presented in galleries or sliders.

Furthermore everything is on board to start search engine optimization.

Take advantage from the strong TYPO3 community and go for a flexible and independent
web site.

Further information can be found through the [TYPO3 extension repository](https://extensions.typo3.org/extension/pizpalue/).

## Installation

### With TYPO3

1. Go to the extension manager
2. Install extension news
3. Select 'Get preconfigured distribution'
4. Search for "Piz Palü Distribution" and install it

### With composer

Adding the extension to a composer based installation:

```
composer require buepro/typo3-pizpalue
```

Installing TYPO3 with pizpalue-distribution:

```
{
    "name": "buepro/typo3-cms-pizpalue",
    "description": "TYPO3 with pizpalue distribution",
    "type": "project",
    "repositories": [
        {
            "type": "composer",
            "url": "https://composer.typo3.org/"
        }
    ],
    "require": {
        "buepro/typo3-pizpalue": "~9.0"
    },
    "extra": {
        "typo3/cms": {
            "web-dir": "web"
        }
    },
    "license": "MIT",
    "authors": [
        {
            "name": "Roman",
            "email": "rb@buechler.pro"
        }
    ],
    "minimum-stability": "stable"
}
```

After the extension has been added install it in the extension manager.