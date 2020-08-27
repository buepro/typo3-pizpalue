.. include:: ../Includes.txt

.. _integration:

===========
Integration
===========

.. _integration_templating:

Templating
==========

When creating templates structures dividing the available width might be defined (e.g. columns). By providing the system
with information regarding the available space within the structures the content rendering can be optimized. A typical
scenario is to render images in columns. For this purpose the following view helpers are available:

pp:render.bootstrap.column
--------------------------

.. code-block:: html

   <pp:render.bootstrap.column class="col col-md-8 col-xl-6" count="2">
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

pp.structure.wrap.column
------------------------

.. code-block:: html

   <pp.structure.wrap.column class="col col-md-8 col-xl-6" count="2">
      <div class="col col-md-8 col-xl-6">
         <div class="content-from-column">
            <v:content.render contentUids="{0: item.data.uid}" />
         </div>
      </div>
   </pp.structure.wrap.column>

The above code results in:

.. code-block:: html

   <div class="col col-md-8 col-xl-6">
      <div class="content-from-column">
         <v:content.render contentUids="{0: item.data.uid}" />
      </div>
   </div>

pp:structure.multiplier.getForColumn
------------------------------------

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
