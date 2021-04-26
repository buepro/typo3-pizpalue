.. include:: ../Includes.txt

.. _integration:

===========
Integration
===========

.. _integration_templating:

Templating
==========

Image variants
--------------

Image variants are used to define the image rendering taking into account different screen width. The available space
might be further modified by templates, layouts, partials and content elements. The resulting variants when rendering
a content element can be obtained with the `pp:structure.variants` view helper:

.. code-block:: html

   {pp:structure.variants(as: 'variants')}

.. hint::

   The `pp:structure.variants` view helper can be used with the attribute `initialVariants` to define the base variants
   to apply the variantsModifiers upon. For more details have a look to the `StructureVariantsUtility` class.

For debugging purposes the `pp:structure.variantsModifierStack` view helper might be used:

.. code-block:: html

   <f:if condition="{data.uid} === 6">
      {pp:structure.variantsModifierStack(as: '_stack')}
      <f:debug>{_stack}</f:debug>
   </f:if>


Structure elements
------------------

When creating templates structures dividing the available width might be defined (e.g. columns). By providing the system
with information regarding the available space within the structures the content rendering can be optimized. A typical
scenario is to render images in columns. For this purpose the following view helpers are available:

pp:render.bootstrap.column
~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: html

   <pp:render.bootstrap.column class="col col-md-8 col-xl-6" count="2" gutter="40" correction="2">
      <div class="content-from-column">
         <v:content.render contentUids="{0: item.data.uid}" />
      </div>
   </pp:render.bootstrap.column>

The above code results in:

.. code-block:: html

   <div class="col col-md-8 col-xl-6">
      <div class="content-from-column">
         <v:content.render contentUids="{0: item.data.uid}" />
      </div>
   </div>

.. note::

   The `count`-attribute is used when the width from the column isn't specified by the classes (e.g. when using `col`)

pp:structure.wrap.column
~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: html

   <pp:structure.wrap.column class="col col-md-8 col-xl-6" count="2" gutter="40" correction="2">
      <div class="col col-md-8 col-xl-6">
         <div class="content-from-column">
            <v:content.render contentUids="{0: item.data.uid}" />
         </div>
      </div>
   </pp:structure.wrap.column>

The above code results in:

.. code-block:: html

   <div class="col col-md-8 col-xl-6">
      <div class="content-from-column">
         <v:content.render contentUids="{0: item.data.uid}" />
      </div>
   </div>

pp:structure.multiplier.getForColumn
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block:: html

   <div class="pp-tile-col col-xl-4"><a id="c348"></a>
      {pp:structure.multiplier.getForColumn(class: 'col-xl-4', count: 2, as: '_colMultiplier')}
      <div class="pp-tile-row row no-gutters">
         {pp:structure.multiplier.getForColumn(multiplier: _colMultiplier, class: 'col-md-6 col-xl-12', count: 2, as: '_colMultiplier')}
         {bk2k:data.imageVariants(as:'_colVariants', variants: variants, multiplier: _colMultiplier)}
         <div class="pp-tile-col col-md-6 col-xl-12">
            <f:render partial="Media/Rendering/Image" arguments="{
               file: _secondaryMedia.files.0,
               data: _secondaryMedia.data,
               settings: settings,
               variants: _colVariants}" />
         </div>
      </div>
   </div>

SCSS-Mixins
===========

The extensions provides mixins to assist in customizing a web site. Following the most used ones are listed.

======================================================================================================= ===============================================================================================================================================================================
Mixin                                                                                                   Description
======================================================================================================= ===============================================================================================================================================================================
`pp-make-rainbow-background($start_color, $end_color, $count: 10)`                                      Used to create a background with two overlapping rainbows starting from each bottom corner.
`pp-make-spaces($properties, $value, $valueBreakpoint: xl, $scaling: $pp-space-scaling)`                Used to create any kind of responsive space (padding or margin). Usage: `pp-make-spaces(margin-top, 1.5rem)`
`pp-make-frame-spaces($value, $valueBreakpoint: xl, $sibling: '.frame', $scaling: $pp-space-scaling)`   Used to create responsive frame spaces with a space of @value at $valueBreakpoint. The space is controlled by the padding and collapsing is taken into account.
`pp-make-text-shadow-outline($thickness: 3px, $color: black, $blur-radius: 5px)`                        Used to create text with a shadowed outline
======================================================================================================= ===============================================================================================================================================================================
