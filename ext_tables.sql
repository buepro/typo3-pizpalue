#
# Table structure for table 'tt_content'
#
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
    tx_pizpalue_image_aspect_ratio text

);
