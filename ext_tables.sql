CREATE TABLE pages (
	tx_pizpalue_background_image int(11) unsigned DEFAULT '0',
	tx_pizpalue_css text,
);

CREATE TABLE tt_content (
	tx_pizpalue_header_class varchar(255) DEFAULT '' NOT NULL,
	tx_pizpalue_subheader_class varchar(255) DEFAULT '' NOT NULL,
	layout varchar(255) DEFAULT '' NOT NULL,
	tx_pizpalue_layout_breakpoint varchar(15) DEFAULT '' NOT NULL,
	tx_pizpalue_classes varchar(255) DEFAULT '' NOT NULL,
	tx_pizpalue_style text,
	tx_pizpalue_attributes varchar(255) DEFAULT '' NOT NULL,
	tx_pizpalue_animation varchar(255) DEFAULT '' NOT NULL,
	tx_pizpalue_image_variants varchar(255) DEFAULT 'variants' NOT NULL,
	tx_pizpalue_background_image_variants varchar(255) DEFAULT 'pageVariants' NOT NULL,
	tx_pizpalue_image_scaling text,
	tx_pizpalue_image_aspect_ratio text,
	tx_pizpalue_scroll_navigation_enable tinyint(4) DEFAULT '0' NOT NULL,
	tx_pizpalue_scroll_navigation_title varchar(255) DEFAULT '' NOT NULL,
	tx_pizpalue_scroll_navigation_position int(11) unsigned DEFAULT '0' NOT NULL
);

CREATE TABLE tx_easyconf_configuration (
	logo_file_reference int(11) unsigned DEFAULT '0',
	logo_file_inverted_reference int(11) unsigned DEFAULT '0',
	appicon_generator_archive int(11) unsigned DEFAULT '0',
	appicon_generator_text text,
	menu_fast_items_first_content_uid varchar(100) DEFAULT '' NOT NULL,
	menu_fast_items_first_page_uid varchar(100) DEFAULT '' NOT NULL,
	menu_fast_items_second_content_uid varchar(100) DEFAULT '' NOT NULL,
	menu_fast_items_second_page_uid varchar(100) DEFAULT '' NOT NULL,
	menu_fast_items_third_content_uid varchar(100) DEFAULT '' NOT NULL,
	menu_fast_items_third_page_uid varchar(100) DEFAULT '' NOT NULL,
	menu_scroll_page_uid varchar(100) DEFAULT '' NOT NULL,
	social_channel_facebook varchar(100) DEFAULT '' NOT NULL,
	social_channel_twitter varchar(100) DEFAULT '' NOT NULL,
	social_channel_instagram varchar(100) DEFAULT '' NOT NULL,
	social_channel_github varchar(100) DEFAULT '' NOT NULL,
	social_channel_googleplus varchar(100) DEFAULT '' NOT NULL,
	social_channel_linkedin varchar(100) DEFAULT '' NOT NULL,
	social_channel_xing varchar(100) DEFAULT '' NOT NULL,
	social_channel_youtube varchar(100) DEFAULT '' NOT NULL,
	social_channel_vk varchar(100) DEFAULT '' NOT NULL,
	social_channel_vimeo varchar(100) DEFAULT '' NOT NULL,
	social_channel_rss varchar(100) DEFAULT '' NOT NULL,
	feature_contact_button_page_uid varchar(100) DEFAULT '' NOT NULL,
	cookie_content_href varchar(100) DEFAULT '' NOT NULL
);
