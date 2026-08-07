<?php
if (!defined('ABSPATH')) { exit; }
// cartimar-map-final.jpg has a large blank margin above the actual map (the
// source export was on a taller canvas than the design itself) — this
// pre-cropped copy strips that out so the image's real content lands at
// very close to the interactive layer's native 1672×941 ratio, instead of
// getting force-stretched into it via preserveAspectRatio="none" below.
$map_img = esc_url(get_template_directory_uri() . '/assets/images/cartimar-map-final-cropped.jpg');
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
      #cartimar-map-svg { cursor: grab; padding: 40px; background: #fff;}
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

      /* Pin-only zones — parking, roads, the hallway. These are plain text
         labels in the base image, not a distinct coloured block, so a full
         colour-fill highlight would look wrong sitting over plain background —
         a muted grey tint instead, so hover is still visible without looking
         like a mismatched shop zone. Rule order matters here: this must stay
         below the general hover/highlight/focus rules above so it wins the
         cascade at equal specificity. */
      #cartimar-map-svg .area-zone--pin-only:hover      .area-fill,
      #cartimar-map-svg .area-zone--pin-only.is-highlighted .area-fill,
      #cartimar-map-svg .area-zone--pin-only:focus      .area-fill {
        fill-opacity:   .22;
        stroke-width:    2;
        stroke-opacity:  .6;
      }

      /* Pin that drops onto whichever area is currently highlighted
         (see showPin()/hidePin() in directory.js). Hidden at rest. */
      #cartimar-map-svg .map-pin { opacity: 0; pointer-events: none; transition: opacity .15s ease; }
      #cartimar-map-svg .map-pin.is-visible { opacity: 1; }

      #cartimar-map-svg .map-pin__bounce {
        transform-box: fill-box;
        transform-origin: 50% 100%; /* pivot from the tip, which touches the map */
      }
      #cartimar-map-svg .map-pin.is-visible .map-pin__bounce {
        animation: map-pin-drop .6s cubic-bezier(.34,1.56,.64,1);
      }

      /* Casual jaunty tilt, like it's been playfully stuck in at an angle —
         pivots around the same tip point as the bounce, shadow stays flat
         and untilted underneath it so it still reads as "grounded". */
      #cartimar-map-svg .map-pin__tilt {
        transform-box: fill-box;
        transform-origin: 50% 100%;
        transform: rotate(-22deg);
      }

      #cartimar-map-svg .map-pin__shadow { fill: rgba(0,0,0,.25); }
      #cartimar-map-svg .map-pin__body {
        fill: #e8395a;
        stroke: #ffffff;
        stroke-width: 2;
        filter: drop-shadow(0 3px 4px rgba(0,0,0,.3));
      }
      #cartimar-map-svg .map-pin__dot { fill: #ffffff; }

      #cartimar-map-svg .map-pin__pulse {
        fill: #e8395a;
        opacity: 0;
        transform-box: fill-box;
        transform-origin: 50% 50%;
      }
      #cartimar-map-svg .map-pin.is-visible .map-pin__pulse {
        animation: map-pin-pulse 1.6s ease-out .55s infinite;
      }

      @keyframes map-pin-drop {
        0%   { transform: translateY(-46px) scale(.4); opacity: 0; }
        55%  { transform: translateY(6px)   scale(1.08); opacity: 1; }
        75%  { transform: translateY(-3px)  scale(.96); }
        100% { transform: translateY(0)     scale(1); }
      }
      @keyframes map-pin-pulse {
        0%   { transform: scale(.5); opacity: .55; }
        100% { transform: scale(2.4); opacity: 0; }
      }
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
  <path class="area-fill" d="M 1010.0,83.0 L 1010.0,83.0 Q 1010.0,75.0 1018.0,75.0 L 1137.0,75.0 Q 1145.0,75.0 1145.0,83.0 L 1145.0,146.0 Q 1145.0,154.0 1137.0,154.0 L 1055.5,154.0 Q 1049.0,154.0 1049.0,160.5 L 1049.0,160.5 Q 1049.0,167.0 1042.5,167.0 L 1018.0,167.0 Q 1010.0,167.0 1010.0,159.0 Z" fill="#5aaee0" stroke="#2a7eb0"></path>
  </g>

  <!-- GREENLAND PLANTS AND ORCHIDS CENTER -->
  <g class="area-zone" data-area="Greenland Plants and Orchids Center"
     tabindex="0" role="button" aria-label="Greenland Plants and Orchids Center">
    <title>Greenland Plants and Orchids Center</title>
    <rect class="area-fill" x="1160" y="75" width="250" height="80" rx="8" fill="#5aae6e" stroke="#2a7e4e"></rect>
  </g>

  <!-- CARTIMAR VILLA VILLAGE 2 -->
  <g class="area-zone" data-area="Cartimar Villa Village 2"
     tabindex="0" role="button" aria-label="Cartimar Villa Village 2">
    <title>Cartimar Villa Village 2</title>
    <rect class="area-fill" x="405" y="190" width="385" height="130" rx="8" fill="#8860d0" stroke="#5830a0"></rect>
  </g>

  <!-- CARTIMAR VILLA VILLAGE 1 (LEFT) -->
  <g class="area-zone" data-area="Cartimar Villa Village 1 (Left)"
     tabindex="0" role="button" aria-label="Cartimar Villa Village 1 Left">
    <title>Cartimar Villa Village 1 (Left)</title>
    <rect class="area-fill" x="805" y="190" width="250" height="130" rx="8" fill="#e0607a" stroke="#b03050"></rect>
  </g>

  <!-- CARTIMAR VILLA VILLAGE 1 (RIGHT) -->
  <g class="area-zone" data-area="Cartimar Villa Village 1 (Right)"
     tabindex="0" role="button" aria-label="Cartimar Villa Village 1 Right">
    <title>Cartimar Villa Village 1 (Right)</title>
    <rect class="area-fill" x="1085" y="215" width="325" height="75" rx="7" fill="#e0607a" stroke="#b03050"></rect>
  </g>

  <!-- PET CENTER -->
  <g class="area-zone" data-area="Pet Center"
     tabindex="0" role="button" aria-label="Pet Center">
    <title>Pet Center</title>
    <rect class="area-fill" x="1085" y="310" width="325" height="70" rx="7" fill="#e0607a" stroke="#b03050"></rect>
  </g>

  <!-- PLAZA -->
  <g class="area-zone" data-area="Plaza"
     tabindex="0" role="button" aria-label="Plaza">
    <title>Plaza</title>
    <rect class="area-fill" x="410" y="395" width="295" height="165" rx="8" fill="#3a72c8" stroke="#1a4298"></rect>
  </g>

  <!-- ADMIN -->
  <g class="area-zone" data-area="Admin"
     tabindex="0" role="button" aria-label="Admin">
    <title>Admin</title>
    <rect class="area-fill" x="720" y="395" width="80" height="165" rx="6" fill="#91e4e8" stroke="#15bcbe"></rect>
  </g>

  <!-- FOOD COURT -->
  <g class="area-zone" data-area="Food Court"
     tabindex="0" role="button" aria-label="Food Court">
    <title>Food Court</title>
    <rect class="area-fill" x="843" y="410" width="80" height="150" rx="6" fill="#e0607a" stroke="#b03050"></rect>
  </g>

  <!-- SAVE MORE GROCERY STORE -->
  <!-- AQUALAND CENTER -->
  <g class="area-zone" data-area="Aqualand Center"
     tabindex="0" role="button" aria-label="Aqualand Center">
    <title>Aqualand Center</title>
    <rect class="area-fill" x="1086" y="408" width="325" height="55" rx="8" fill="#7850c8" stroke="#4820a0"></rect>
  </g>

  <g class="area-zone" data-area="Save More Grocery Store"
     tabindex="0" role="button" aria-label="Save More Grocery Store">
    <title>Save More Grocery Store</title>
    <path class="area-fill" d="M 943.0,418.0 L 943.0,418.0 Q 943.0,410.0 951.0,410.0 L 1070.0,410.0 Q 1078.0,410.0 1078.0,418.0 L 1078.0,467.0 Q 1078.0,475.0 1086.0,475.0 L 1405.0,475.0 Q 1413.0,475.0 1413.0,483.0 L 1413.0,550.0 Q 1413.0,558.0 1405.0,558.0 L 951.0,558.0 Q 943.0,558.0 943.0,550.0 Z" fill="#7850c8" stroke="#4820a0"></path>
  </g>

  <!-- GRAINS & GROCERY -->
  <g class="area-zone" data-area="Grains &amp; Grocery"
     tabindex="0" role="button" aria-label="Grains and Grocery">
    <title>Grains &amp; Grocery</title>
    <rect class="area-fill" x="420" y="635" width="370" height="205" rx="8" fill="#e8a030" stroke="#b87010"></rect>
  </g>

  <!-- CARTIMAR MAIN BUILDING -->
  <g class="area-zone" data-area="Cartimar Main Building"
     tabindex="0" role="button" aria-label="Cartimar Main Building">
    <title>Cartimar Main Building</title>
    <rect class="area-fill" x="835" y="630" width="575" height="215" rx="8" fill="#28a8a5" stroke="#007870"></rect>
  </g>

  <!-- CARTIMAR CARPARK AND FRESH FOOD PLAZA -->
  <g class="area-zone" data-area="Cartimar Carpark and Fresh Food Plaza"
     tabindex="0" role="button" aria-label="Cartimar Carpark and Fresh Food Plaza">
    <title>Cartimar Carpark and Fresh Food Plaza</title>
    <rect class="area-fill" x="150" y="655" width="200" height="73" rx="7" fill="#8848b8" stroke="#582888"></rect>
  </g>

  <!-- GATEWAY UPPER -->
  <g class="area-zone" data-area="Gateway (Upper)"
     tabindex="0" role="button" aria-label="Gateway Upper">
    <title>Gateway (Upper)</title>
    <rect class="area-fill" x="1480" y="280" width="50" height="225" rx="7" fill="#9a8040" stroke="#6a5010"></rect>
  </g>

  <!-- GATEWAY LOWER -->
  <g class="area-zone" data-area="Gateway (Lower)"
     tabindex="0" role="button" aria-label="Gateway Lower">
    <title>Gateway (Lower)</title>
    <rect class="area-fill" x="1480" y="565" width="50" height="270" rx="7" fill="#9a8040" stroke="#6a5010"></rect>
  </g>

  <!-- ══════════════════════════════════════════════════════ -->
  <!-- PIN-ONLY ZONES — parking, roads, hallway. No colour fill  -->
  <!-- (see .area-zone--pin-only above); clicking still drops   -->
  <!-- the pin and filters the store list, same as any zone.    -->
  <!-- ══════════════════════════════════════════════════════ -->

  <!-- ROAD LOT & PARKING (OPEN) -->
  <g class="area-zone area-zone--pin-only" data-area="Road Lot &amp; Parking (Open)"
     tabindex="0" role="button" aria-label="Road Lot and Parking, Open">
    <title>Road Lot &amp; Parking (Open)</title>
    <rect class="area-fill" x="1060" y="165" width="360" height="35" fill="#888888" stroke="#555555"></rect>
  </g>

  <!-- ROAD LOT & PARKING (ROOFED) -->
  <g class="area-zone area-zone--pin-only" data-area="Road Lot &amp; Parking (Roofed)"
     tabindex="0" role="button" aria-label="Road Lot and Parking, Roofed">
    <title>Road Lot &amp; Parking (Roofed)</title>
    <rect class="area-fill" x="800" y="330" width="265" height="65" fill="#888888" stroke="#555555"></rect>
  </g>

  <!-- HALLWAY -->
  <g class="area-zone area-zone--pin-only" data-area="Hallway"
     tabindex="0" role="button" aria-label="Hallway">
    <title>Hallway</title>
  <rect class="area-fill" x="1078" y="290" width="340" height="20" fill="#888888" stroke="#555555"></rect>
  </g>

  <!-- CARTIMAR ROAD & PARKING (OPEN) -->
  <g class="area-zone area-zone--pin-only" data-area="Cartimar Road &amp; Parking (Open)"
     tabindex="0" role="button" aria-label="Cartimar Road and Parking, Open">
    <title>Cartimar Road &amp; Parking (Open)</title>
    <rect class="area-fill" x="400" y="330" width="395" height="55" fill="#888888" stroke="#555555"></rect>
  </g>

  <!-- CARTIMAR AVE & PARKING (OPEN) — LEFT, under Plaza/Admin -->
  <g class="area-zone area-zone--pin-only" data-area="Cartimar Ave &amp; Parking (Open) (Left)"
     tabindex="0" role="button" aria-label="Cartimar Avenue and Parking, Open, left side">
    <title>Cartimar Ave &amp; Parking (Open)</title>
    <rect class="area-fill" x="410" y="565" width="395" height="60" fill="#888888" stroke="#555555"></rect>
  </g>

  <!-- CARTIMAR AVE & PARKING (OPEN) — RIGHT, under Food Court/Save More -->
  <g class="area-zone area-zone--pin-only" data-area="Cartimar Ave &amp; Parking (Open) (Right)"
     tabindex="0" role="button" aria-label="Cartimar Avenue and Parking, Open, right side">
    <title>Cartimar Ave &amp; Parking (Open)</title>
    <rect class="area-fill" x="835" y="565" width="585" height="55" fill="#888888" stroke="#555555"></rect>
  </g>

  <!-- EXISTING ROAD -->
  <g class="area-zone area-zone--pin-only" data-area="Existing Road"
     tabindex="0" role="button" aria-label="Existing Road">
    <title>Existing Road</title>
    <rect class="area-fill" x="140" y="730" width="220" height="45" fill="#888888" stroke="#555555"></rect>
  </g>

  <!-- ══════════════════════════════════════════════════════ -->
  <!-- ACTIVE-AREA PIN — positioned + shown by directory.js's  -->
  <!-- showPin()/hidePin(), driven off the same setHighlight() -->
  <!-- calls that already highlight a zone.                    -->
  <!-- ══════════════════════════════════════════════════════ -->
  <g id="map-pin" class="map-pin">
    <g class="map-pin__bounce">
      <ellipse class="map-pin__shadow" cx="0" cy="3" rx="11" ry="4"></ellipse>
      <g class="map-pin__tilt">
        <circle class="map-pin__pulse" cx="0" cy="-32" r="14"></circle>
        <path class="map-pin__body" d="M-18,-32 A18,18 0 1,1 18,-32 C18,-14 0,-14 0,0 C0,-14 -18,-14 -18,-32 Z"></path>
        <circle class="map-pin__dot" cx="0" cy="-32" r="6"></circle>
      </g>
    </g>
  </g>

</svg>
