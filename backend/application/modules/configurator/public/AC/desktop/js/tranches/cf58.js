'use strict';
ISO.moduleCreate('sliceCF58', function($el, param) {
      
      /*
        filter variables
      */
      //map structure: <jquery_object, filter_infos>
      var lamesMap;

      var lamesArray;

      //we keep track of the active filters
      var activeFilters;

      //lists of jquery_object
      var checked_energies;
      var checked_boites;

      //maps used by isBoite() function
      var energy_types;
      var boites_types;
      /*
        end filter variables
      */

      /*
        toggle variables
      */
      var _opened;
      var _toggle_button;
      var _toggle_content;
      var _lastTop;
      /*
        end toggle variables
      */

      init();

      function init() {

          //filter init
          lamesMap = {};
          lamesArray = [];
          activeFilters = [];
          checked_energies = 0;
          checked_boites = 0;
          energy_types = {};
          boites_types = {};

          $('.lame').each(indexLame);

          //toggle init
          _opened = true;
          _toggle_button = $(".parent-toggle");
          _toggle_content = $("#target-toggle");

          //specific fix
          $(".openToggle").trigger("click");

          //events binding function
          bindEvents();
      }

      function bindEvents() {

          //filter events
          $(".filtre").on("mouseup", onCheckBoxTriggered);
          $(".showAllFiltre").on("mouseup", resetFilters);
         
          //toggle events
          _toggle_button.on("click", onToggle);

          //specific fix
          $(".clickZone").on("mouseup", onLameSelectionRequest);

          //anchor initialisation
          $('.ancre', $el).scroller({
              cible: 'class'
          });
      }

      //maps initialization code
      function indexLame() {
          var lame = $(this);
          var compatible = lame.attr("compatible");

          if (compatible == "true") {
              var _energyType = lame.attr("energie");
              var _boiteType = lame.attr("boite");

              lamesArray.push(lame);

              lamesMap[lamesArray.length - 1] = {
                  energyType: _energyType,
                  boiteType: _boiteType
              };

              if (!energy_types[_energyType])
                  energy_types[_energyType] = true;

              if (!boites_types[_boiteType])
                  boites_types[_boiteType] = true;
          }
      }

      //this is used to define which lame should be displayed or not
      function updateLamesView() {
          for (var i in lamesMap) {
              var lame = lamesArray[i];
              var lame_infos = lamesMap[i];

              if (
                  (activeFilters.indexOf(lame_infos.energyType) >= 0 || checked_energies == 0) &&
                  (activeFilters.indexOf(lame_infos.boiteType) >= 0 || checked_boites == 0)
              ) {
                  lame.css("display", "block");
              } else {
                  lame.css("display", "none");
              }
          }
      }

      //this is called when a checkbox is clicked
      function onCheckBoxTriggered() {
          var check_box = $(this).find("input"),
              type = check_box.attr("value"),
              checked = $(this).hasClass("checked");

          if (!checked) {
              activeFilters.push(type);

              if (isBoite(type))
                  checked_boites++;
              else
                  checked_energies++;

              $(this).addClass("checked");
          } else {
              var index = activeFilters.indexOf(type);
              activeFilters.splice(index, 1);

              if (isBoite(type))
                  checked_boites--;
              else
                  checked_energies--;

              $(this).removeClass("checked");
          }

          updateLamesView();

      }

      function resetFilters() {
          $(".filtre").each(resetCheckBox);
      }

      function resetCheckBox() {
          var check_box = $(this).find("input"),
              type = check_box.attr("value"),
              checked = $(this).hasClass("checked");

          if (checked) {
              $(this).trigger("mouseup");
              check_box[0].checked = false;
          }
      }

      function isBoite(type) {
          if (boites_types[type])
              return true;
          else
              return false;
      }

      function onLameSelectionRequest() {
          $(this).find("label").trigger("click");
      }

      function onToggle() {
            _toggle_content.slideToggle(400, onToggleComplete);
      }

      function onToggleComplete() {
          if (_opened) {
              _toggle_button.removeClass("opened");

              $('html, body').animate({
                  scrollTop: _lastTop
              }, 500);
          } else {
              _toggle_button.addClass("opened");

              var _top = _toggle_button.offset().top;
              _lastTop = _top;

              $('html, body').animate({
                  scrollTop: _top
              }, 500);
          }

          _opened = !_opened;
      }
});
