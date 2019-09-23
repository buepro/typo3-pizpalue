# TYPO3 user_customer

[![Latest Version](https://badgen.net/packagist/v/buepro/typo3-user_customer)](https://github.com/buepro/typo3-user_customer/releases)
[![Extension repository](https://badgen.net/badge/TYPO3/pizpalue/orange)](https://extensions.typo3.org/extension/pizpalue/)

---

This extension serves as a base to customize a TYPO3-website using the distribution 
[pizpalue](https://github.com/buepro/typo3-pizpalue).

## Usage

When starting a new project create a new git-branch and just commit to that branch. The master branch should always 
be used to start new projects.

To increase quality work progress might be committed and documented. Documentation has its home in the folder 
[`Documentation`](Documentation). A changelog can be created with the following steps:

1. In a shell go to the `Build` directory
1. Run `npm install` (only needed, if not already done)
1. Run `grunt doc`

## Customizations

### TypoScript (TS)

Customizations typically start by adapting the [ts constants](Configuration/TypoScript/constants.typoscript) and 
[ts setup](Configuration/TypoScript/setup.typoscript). Frequently used configurations are collected
in the folder [`Configuration/TypoScript/Default`](Configuration/TypoScript/Default). You might use them to get started 
by copying the needed fragments to 
[`Configuration/TypoScript/constants.typoscript`](Configuration/TypoScript/constants.typoscript) or 
[`Configuration/TypoScript/setup.typoscript`](Configuration/TypoScript/setup.typoscript). The inclusion from the default
TS (see `<INCLUDE_TYPOSCRIPT...`) might be deleted.

### CSS/SCSS

Style declarations are maintained in the folder [`Resources/Public/Scss`](Resources/Public/Scss). For stylesheets to be
embedded TS needs to be setup. See `page.includeCSS` for further details.

### Icon font

It might become handy to create a customized icon font. Ideally it contains all used icons from the website. To generate
an icon font the icons need to be available in svg-format. Unfortunately not all svg-formats lead to the desired result
hence some testing might be needed. An icon font might be created by following these steps:

1. Copy all svg-icons to the folder `Resources/Public/Icons/Font`
1. In a shell go to the `Build` directory
1. Run `npm install` (only needed, if not already done)
1. Run `grunt iconfont`

Upon creating the icon font its resources can be found in `Resources/Public/Fonts`. Next the font needs to be embedded
with the following TS setup:

```
page {
    includeCSSLibs {
        pizpalueicon >
        ucicon = EXT:user_customer/Resources/Public/Fonts/ucicon.min.css
        ucicon {
            fontLoader {
                families {
                    0 = UcIcon
                }
                enabled = 1
            }
        }
    }
}
```

Now your ready to use the icon font in the markup: `<i class="ucicon ucicon-custom1"></i>` would render an icon showing
the graphic defined by `custom1.svg`.

### Layouts/Templates/Partials

They are maintained in [`Resources/Private`](Resources/Private). As an example to add a new page template follow these 
steps:

1. Create the template in the directory [`Resources/Private/Templates/Page`](Resources/Private/Templates/Page)
2. Enable the template in the TS constant declaration

```
user_customer {
    page.fluidtemplate {
        templateRootPath = EXT:user_customer/Resources/Private/Templates/Page/
    }
}
```

## Coding guidelines

- Use the [coding guidelines defined by TYPO3](https://docs.typo3.org/typo3cms/CoreApiReference/CodingGuidelines/Index.html).
- Use **uc, Uc, uc-** as package related prefixes

## Frequently used

**For coding**
- [TypoScript reference](https://docs.typo3.org/typo3cms/TyposcriptReference/)
- [Fluid guide](https://docs.typo3.org/typo3cms/ExtbaseGuide/Fluid/)
- [Fluid view helper reference](https://docs.typo3.org/typo3cms/ViewHelperReference/)
- [VHS view helper reference](https://fluidtypo3.org/viewhelpers/vhs/)
- [TCA reference](https://docs.typo3.org/typo3cms/TCAReference/)
- [TSconfig reference](https://docs.typo3.org/typo3cms/TSconfigReference/)
- [Core API reference](https://docs.typo3.org/typo3cms/CoreApiReference/)

**For documentation**
- [Markdown cheatsheet](https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet)
- [reStructuredText & Sphinx](https://docs.typo3.org/typo3cms/HowToDocument/WritingReST/Index.html)
