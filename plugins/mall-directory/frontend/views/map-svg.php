<?php
if (!defined('ABSPATH')) { exit; }
$map_img = esc_url(MALL_DIR_PLUGIN_URL . 'assets/images/cartimar-shop-directory-map.jpg');
?>
<svg id="cartimar-map-svg"
     viewBox="0 0 1672 941"
     width="1672"
     height="941"
     xmlns="http://www.w3.org/2000/svg"
     style="width:100%;height:auto;display:block;"
     aria-label="Cartimar Shopping Complex Interactive Map">

  <defs>
    <style>
      #cartimar-map-svg { cursor: grab; }
      #cartimar-map-svg.is-dragging { cursor: grabbing; }

      /* Zones are invisible by default; colour appears on interaction */
      #cartimar-map-svg .area-zone { cursor: pointer; }
      #cartimar-map-svg .area-zone .area-fill {
        fill-opacity:    0;
        stroke-width:    0;
        stroke-opacity:  0;
        transition: fill-opacity .22s ease,
                    stroke-width .22s ease,
                    stroke-opacity .22s ease;
      }
      #cartimar-map-svg .area-zone:hover      .area-fill,
      #cartimar-map-svg .area-zone.is-highlighted .area-fill {
        fill-opacity:   .38;
        stroke-width:    3;
        stroke-opacity:  .95;
      }
      #cartimar-map-svg .area-zone:focus { outline: none; }
      #cartimar-map-svg .area-zone:focus .area-fill {
        fill-opacity:   .32;
        stroke-width:    3;
        stroke-opacity:  .95;
        stroke-dasharray: 8 4;
      }
      #cartimar-map-svg text { pointer-events: none; user-select: none; }
    </style>
  </defs>

  <!-- ══════════════════════════════════════════════════════ -->
  <!-- BASE LAYER — original JPG (trees, roads, text, all)   -->
  <!-- The image coordinates exactly match the 1672×941 vBox -->
  <!-- ══════════════════════════════════════════════════════ -->
  <image href="<?php echo $map_img; ?>"
         x="0" y="0" width="1672" height="941"
         preserveAspectRatio="none"/>

  <!-- ══════════════════════════════════════════════════════ -->
  <!-- INTERACTIVE OVERLAY ZONES                             -->
  <!-- fill / stroke invisible at rest; shown on hover.     -->
  <!-- Coordinates match the pixel positions in the JPG.    -->
  <!-- ══════════════════════════════════════════════════════ -->

  <!-- AQUALAND ALLEY -->
  <g class="area-zone" data-area="Aqualand Alley"
     tabindex="0" role="button" aria-label="Aqualand Alley">
    <title>Aqualand Alley</title>
<rect class="area-fill" x="930" y="40" width="155" height="97" rx="8" fill="#5aaee0" stroke="#2a7eb0"></rect>
  </g>

  <!-- GREENLAND PLANTS AND ORCHIDS CENTER -->
  <g class="area-zone" data-area="Greenland Plants and Orchids Center"
     tabindex="0" role="button" aria-label="Greenland Plants and Orchids Center">
    <title>Greenland Plants and Orchids Center</title>
   <rect class="area-fill" x="1097" y="42" width="275" height="97" rx="8" fill="#5aae6e" stroke="#2a7e4e"></rect>
  </g>

  <!-- CARTIMAR VILLA VILLAGE 2 -->
  <g class="area-zone" data-area="Cartimar Villa Village 2"
     tabindex="0" role="button" aria-label="Cartimar Villa Village 2">
    <title>Cartimar Villa Village 2</title>
<rect class="area-fill" x="375" y="170" width="330" height="105" rx="8" fill="#e8a030" stroke="#b87010"></rect>
  </g>

  <!-- CARTIMAR VILLA VILLAGE 3 -->
  <g class="area-zone" data-area="Cartimar Villa Village 3"
     tabindex="0" role="button" aria-label="Cartimar Villa Village 3">
    <title>Cartimar Villa Village 3</title>
<rect class="area-fill" x="730" y="170" width="260" height="105" rx="8" fill="#8860d0" stroke="#5830a0"></rect>
  </g>

  <!-- CARTIMAR VILLA VILLAGE 1 — PET SHOPS -->
  <g class="area-zone" data-area="Cartimar Villa Village 1 (Pet Shops)"
     tabindex="0" role="button" aria-label="Cartimar Villa Village 1 Pet Shops">
    <title>Cartimar Villa Village 1 (Pet Shops)</title>
    <rect class="area-fill" x="1030" y="189" width="340" height="67" rx="7" fill="#e0607a" stroke="#b03050"></rect>
  </g>

  <!-- CARTIMAR VILLA VILLAGE 4 — PET SHOPS -->
  <g class="area-zone" data-area="Cartimar Villa Village 4 (Pet Shops)"
     tabindex="0" role="button" aria-label="Cartimar Villa Village 4 Pet Shops">
    <title>Cartimar Villa Village 4 (Pet Shops)</title>
    <rect class="area-fill" x="1030" y="280" width="340" height="60" rx="7" fill="#e0607a" stroke="#b03050"></rect>
  </g>

  <!-- PLAZA — VARIOUS SHOPS -->
  <g class="area-zone" data-area="Plaza (Various Shops)"
     tabindex="0" role="button" aria-label="Plaza - Various Shops">
    <title>Plaza (Various Shops)</title>
  <rect class="area-fill" x="378" y="345" width="285" height="142" rx="8" fill="#3a72c8" stroke="#1a4298"></rect>
  </g>

  <!-- ADMIN -->
  <g class="area-zone" data-area="Admin"
     tabindex="0" role="button" aria-label="Admin">
    <title>Admin</title>
<rect class="area-fill" x="680" y="347" width="77" height="142" rx="6" fill="#e06845" stroke="#b03815"></rect>
  </g>

  <!-- FOOD COURT -->
  <g class="area-zone" data-area="Food Court"
     tabindex="0" role="button" aria-label="Food Court">
    <title>Food Court</title>
 <rect class="area-fill" x="810" y="355" width="80" height="133" rx="6" fill="#e0607a" stroke="#b03050"></rect>
  </g>

  <!-- SAVE MORE GROCERY STORE -->
  <g class="area-zone" data-area="Save More Grocery Store"
     tabindex="0" role="button" aria-label="Save More Grocery Store">
    <title>Save More Grocery Store</title>
    <rect class="area-fill" x="910" y="358" width="460" height="128" rx="8" fill="#7850c8" stroke="#4820a0"></rect>
  </g>

  <!-- GRAINS & GROCERY -->
  <g class="area-zone" data-area="Grains &amp; Grocery"
     tabindex="0" role="button" aria-label="Grains and Grocery">
    <title>Grains &amp; Grocery</title>
<rect class="area-fill" x="375" y="560" width="390" height="200" rx="8" fill="#e8a030" stroke="#b87010"></rect>
  </g>

  <!-- CARTIMAR MAIN BUILDING -->
  <g class="area-zone" data-area="Cartimar Main Building"
     tabindex="0" role="button" aria-label="Cartimar Main Building">
    <title>Cartimar Main Building</title>
    <rect class="area-fill" x="810" y="560" width="560" height="200" rx="8" fill="#28a8a5" stroke="#007870"></rect>
  </g>

  <!-- CARTIMAR CARPARK AND FRESH FOOD PLAZA -->
  <g class="area-zone" data-area="Cartimar Carpark and Fresh Food Plaza"
     tabindex="0" role="button" aria-label="Cartimar Carpark and Fresh Food Plaza">
    <title>Cartimar Carpark and Fresh Food Plaza</title>
<rect class="area-fill" x="75" y="595" width="210" height="68" rx="7" fill="#8848b8" stroke="#582888"></rect>
  </g>

  <!-- GATEWAY UPPER -->
  <g class="area-zone" data-area="Gateway (Upper)"
     tabindex="0" role="button" aria-label="Gateway Upper">
    <title>Gateway (Upper)</title>
<rect class="area-fill" x="1438" y="250" width="54" height="194" rx="7" fill="#9a8040" stroke="#6a5010"></rect>
  </g>

  <!-- GATEWAY LOWER -->
  <g class="area-zone" data-area="Gateway (Lower)"
     tabindex="0" role="button" aria-label="Gateway Lower">
    <title>Gateway (Lower)</title>
  <rect class="area-fill" x="1438" y="495" width="54" height="265" rx="7" fill="#9a8040" stroke="#6a5010"></rect>
  </g>

</svg>
