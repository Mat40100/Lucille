(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["EmbedUser"],{

/***/ "./assets/js/embedFormUserProduct.js":
/*!*******************************************!*\
  !*** ./assets/js/embedFormUserProduct.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function($, jQuery) {__webpack_require__(/*! core-js/modules/es.array.find */ "./node_modules/core-js/modules/es.array.find.js");

__webpack_require__(/*! core-js/modules/es.regexp.exec */ "./node_modules/core-js/modules/es.regexp.exec.js");

__webpack_require__(/*! core-js/modules/es.string.replace */ "./node_modules/core-js/modules/es.string.replace.js");

var $collectionHolder;
var $addTagButton = $('<button type="button" class="add_file_link">Add a tag</button>');
var $newLinkLi = $('<li></li>').append($addTagButton);
jQuery(document).ready(function () {
  // Get the ul that holds the collection of tags
  if ($('#orphan_user_product_files').length) {
    $collectionHolder = $('#orphan_user_product_files');
  } else {
    $collectionHolder = $('ul.files');
  } // add the "add a tag" anchor and li to the tags ul


  $collectionHolder.append($newLinkLi); // count the current form inputs we have (e.g. 2), use that as the new
  // index when inserting a new item (e.g. 2)

  $collectionHolder.data('index', $collectionHolder.find(':input').length);
  $addTagButton.on('click', function (e) {
    // add a new tag form (see next code block)
    addFileForm($collectionHolder, $newLinkLi);
  });
});

function addFileForm($collectionHolder, $newLinkLi) {
  // Get the data-prototype explained earlier
  var prototype = $collectionHolder.data('prototype'); // get the new index

  var index = $collectionHolder.data('index');
  var newForm = prototype; // You need this only if you didn't set 'label' => false in your tags field in TaskType
  // Replace '__name__label__' in the prototype's HTML to
  // instead be a number based on how many items we have
  // newForm = newForm.replace(/__name__label__/g, index);
  // Replace '__name__' in the prototype's HTML to
  // instead be a number based on how many items we have

  newForm = newForm.replace(/__name__/g, index); // increase the index with one for the next item

  $collectionHolder.data('index', index + 1); // Display the form in the page in an li, before the "Add a tag" link li

  var $newFormLi = $('<li></li>').append(newForm);
  $newLinkLi.before($newFormLi);
}
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ })

},[["./assets/js/embedFormUserProduct.js","runtime","vendors~EmbedUser"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvZW1iZWRGb3JtVXNlclByb2R1Y3QuanMiXSwibmFtZXMiOlsiJGNvbGxlY3Rpb25Ib2xkZXIiLCIkYWRkVGFnQnV0dG9uIiwiJCIsIiRuZXdMaW5rTGkiLCJhcHBlbmQiLCJqUXVlcnkiLCJkb2N1bWVudCIsInJlYWR5IiwibGVuZ3RoIiwiZGF0YSIsImZpbmQiLCJvbiIsImUiLCJhZGRGaWxlRm9ybSIsInByb3RvdHlwZSIsImluZGV4IiwibmV3Rm9ybSIsInJlcGxhY2UiLCIkbmV3Rm9ybUxpIiwiYmVmb3JlIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7Ozs7QUFBQSxJQUFJQSxpQkFBSjtBQUVBLElBQUlDLGFBQWEsR0FBR0MsQ0FBQyxDQUFDLGdFQUFELENBQXJCO0FBQ0EsSUFBSUMsVUFBVSxHQUFHRCxDQUFDLENBQUMsV0FBRCxDQUFELENBQWVFLE1BQWYsQ0FBc0JILGFBQXRCLENBQWpCO0FBRUFJLE1BQU0sQ0FBQ0MsUUFBRCxDQUFOLENBQWlCQyxLQUFqQixDQUF1QixZQUFXO0FBQzlCO0FBRUEsTUFBR0wsQ0FBQyxDQUFDLDRCQUFELENBQUQsQ0FBZ0NNLE1BQW5DLEVBQTJDO0FBQ3ZDUixxQkFBaUIsR0FBR0UsQ0FBQyxDQUFDLDRCQUFELENBQXJCO0FBQ0gsR0FGRCxNQUVLO0FBQ0RGLHFCQUFpQixHQUFHRSxDQUFDLENBQUMsVUFBRCxDQUFyQjtBQUNILEdBUDZCLENBUzlCOzs7QUFDQUYsbUJBQWlCLENBQUNJLE1BQWxCLENBQXlCRCxVQUF6QixFQVY4QixDQVk5QjtBQUNBOztBQUNBSCxtQkFBaUIsQ0FBQ1MsSUFBbEIsQ0FBdUIsT0FBdkIsRUFBZ0NULGlCQUFpQixDQUFDVSxJQUFsQixDQUF1QixRQUF2QixFQUFpQ0YsTUFBakU7QUFFQVAsZUFBYSxDQUFDVSxFQUFkLENBQWlCLE9BQWpCLEVBQTBCLFVBQVNDLENBQVQsRUFBWTtBQUNsQztBQUNBQyxlQUFXLENBQUNiLGlCQUFELEVBQW9CRyxVQUFwQixDQUFYO0FBQ0gsR0FIRDtBQUlILENBcEJEOztBQXNCQSxTQUFTVSxXQUFULENBQXFCYixpQkFBckIsRUFBd0NHLFVBQXhDLEVBQW9EO0FBQ2hEO0FBQ0EsTUFBSVcsU0FBUyxHQUFHZCxpQkFBaUIsQ0FBQ1MsSUFBbEIsQ0FBdUIsV0FBdkIsQ0FBaEIsQ0FGZ0QsQ0FJaEQ7O0FBQ0EsTUFBSU0sS0FBSyxHQUFHZixpQkFBaUIsQ0FBQ1MsSUFBbEIsQ0FBdUIsT0FBdkIsQ0FBWjtBQUVBLE1BQUlPLE9BQU8sR0FBR0YsU0FBZCxDQVBnRCxDQVFoRDtBQUNBO0FBQ0E7QUFDQTtBQUVBO0FBQ0E7O0FBQ0FFLFNBQU8sR0FBR0EsT0FBTyxDQUFDQyxPQUFSLENBQWdCLFdBQWhCLEVBQTZCRixLQUE3QixDQUFWLENBZmdELENBaUJoRDs7QUFDQWYsbUJBQWlCLENBQUNTLElBQWxCLENBQXVCLE9BQXZCLEVBQWdDTSxLQUFLLEdBQUcsQ0FBeEMsRUFsQmdELENBb0JoRDs7QUFDQSxNQUFJRyxVQUFVLEdBQUdoQixDQUFDLENBQUMsV0FBRCxDQUFELENBQWVFLE1BQWYsQ0FBc0JZLE9BQXRCLENBQWpCO0FBQ0FiLFlBQVUsQ0FBQ2dCLE1BQVgsQ0FBa0JELFVBQWxCO0FBQ0gsQyIsImZpbGUiOiJFbWJlZFVzZXIuanMiLCJzb3VyY2VzQ29udGVudCI6WyJ2YXIgJGNvbGxlY3Rpb25Ib2xkZXI7XG5cbnZhciAkYWRkVGFnQnV0dG9uID0gJCgnPGJ1dHRvbiB0eXBlPVwiYnV0dG9uXCIgY2xhc3M9XCJhZGRfZmlsZV9saW5rXCI+QWRkIGEgdGFnPC9idXR0b24+Jyk7XG52YXIgJG5ld0xpbmtMaSA9ICQoJzxsaT48L2xpPicpLmFwcGVuZCgkYWRkVGFnQnV0dG9uKTtcblxualF1ZXJ5KGRvY3VtZW50KS5yZWFkeShmdW5jdGlvbigpIHtcbiAgICAvLyBHZXQgdGhlIHVsIHRoYXQgaG9sZHMgdGhlIGNvbGxlY3Rpb24gb2YgdGFnc1xuXG4gICAgaWYoJCgnI29ycGhhbl91c2VyX3Byb2R1Y3RfZmlsZXMnKS5sZW5ndGgpIHtcbiAgICAgICAgJGNvbGxlY3Rpb25Ib2xkZXIgPSAkKCcjb3JwaGFuX3VzZXJfcHJvZHVjdF9maWxlcycpO1xuICAgIH1lbHNle1xuICAgICAgICAkY29sbGVjdGlvbkhvbGRlciA9ICQoJ3VsLmZpbGVzJyk7XG4gICAgfVxuXG4gICAgLy8gYWRkIHRoZSBcImFkZCBhIHRhZ1wiIGFuY2hvciBhbmQgbGkgdG8gdGhlIHRhZ3MgdWxcbiAgICAkY29sbGVjdGlvbkhvbGRlci5hcHBlbmQoJG5ld0xpbmtMaSk7XG5cbiAgICAvLyBjb3VudCB0aGUgY3VycmVudCBmb3JtIGlucHV0cyB3ZSBoYXZlIChlLmcuIDIpLCB1c2UgdGhhdCBhcyB0aGUgbmV3XG4gICAgLy8gaW5kZXggd2hlbiBpbnNlcnRpbmcgYSBuZXcgaXRlbSAoZS5nLiAyKVxuICAgICRjb2xsZWN0aW9uSG9sZGVyLmRhdGEoJ2luZGV4JywgJGNvbGxlY3Rpb25Ib2xkZXIuZmluZCgnOmlucHV0JykubGVuZ3RoKTtcblxuICAgICRhZGRUYWdCdXR0b24ub24oJ2NsaWNrJywgZnVuY3Rpb24oZSkge1xuICAgICAgICAvLyBhZGQgYSBuZXcgdGFnIGZvcm0gKHNlZSBuZXh0IGNvZGUgYmxvY2spXG4gICAgICAgIGFkZEZpbGVGb3JtKCRjb2xsZWN0aW9uSG9sZGVyLCAkbmV3TGlua0xpKTtcbiAgICB9KTtcbn0pO1xuXG5mdW5jdGlvbiBhZGRGaWxlRm9ybSgkY29sbGVjdGlvbkhvbGRlciwgJG5ld0xpbmtMaSkge1xuICAgIC8vIEdldCB0aGUgZGF0YS1wcm90b3R5cGUgZXhwbGFpbmVkIGVhcmxpZXJcbiAgICB2YXIgcHJvdG90eXBlID0gJGNvbGxlY3Rpb25Ib2xkZXIuZGF0YSgncHJvdG90eXBlJyk7XG5cbiAgICAvLyBnZXQgdGhlIG5ldyBpbmRleFxuICAgIHZhciBpbmRleCA9ICRjb2xsZWN0aW9uSG9sZGVyLmRhdGEoJ2luZGV4Jyk7XG5cbiAgICB2YXIgbmV3Rm9ybSA9IHByb3RvdHlwZTtcbiAgICAvLyBZb3UgbmVlZCB0aGlzIG9ubHkgaWYgeW91IGRpZG4ndCBzZXQgJ2xhYmVsJyA9PiBmYWxzZSBpbiB5b3VyIHRhZ3MgZmllbGQgaW4gVGFza1R5cGVcbiAgICAvLyBSZXBsYWNlICdfX25hbWVfX2xhYmVsX18nIGluIHRoZSBwcm90b3R5cGUncyBIVE1MIHRvXG4gICAgLy8gaW5zdGVhZCBiZSBhIG51bWJlciBiYXNlZCBvbiBob3cgbWFueSBpdGVtcyB3ZSBoYXZlXG4gICAgLy8gbmV3Rm9ybSA9IG5ld0Zvcm0ucmVwbGFjZSgvX19uYW1lX19sYWJlbF9fL2csIGluZGV4KTtcblxuICAgIC8vIFJlcGxhY2UgJ19fbmFtZV9fJyBpbiB0aGUgcHJvdG90eXBlJ3MgSFRNTCB0b1xuICAgIC8vIGluc3RlYWQgYmUgYSBudW1iZXIgYmFzZWQgb24gaG93IG1hbnkgaXRlbXMgd2UgaGF2ZVxuICAgIG5ld0Zvcm0gPSBuZXdGb3JtLnJlcGxhY2UoL19fbmFtZV9fL2csIGluZGV4KTtcblxuICAgIC8vIGluY3JlYXNlIHRoZSBpbmRleCB3aXRoIG9uZSBmb3IgdGhlIG5leHQgaXRlbVxuICAgICRjb2xsZWN0aW9uSG9sZGVyLmRhdGEoJ2luZGV4JywgaW5kZXggKyAxKTtcblxuICAgIC8vIERpc3BsYXkgdGhlIGZvcm0gaW4gdGhlIHBhZ2UgaW4gYW4gbGksIGJlZm9yZSB0aGUgXCJBZGQgYSB0YWdcIiBsaW5rIGxpXG4gICAgdmFyICRuZXdGb3JtTGkgPSAkKCc8bGk+PC9saT4nKS5hcHBlbmQobmV3Rm9ybSk7XG4gICAgJG5ld0xpbmtMaS5iZWZvcmUoJG5ld0Zvcm1MaSk7XG59Il0sInNvdXJjZVJvb3QiOiIifQ==