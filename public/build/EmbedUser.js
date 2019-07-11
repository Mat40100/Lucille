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
var $addTagButton = $('<button type="button" class="btn btn-primary add_file_link m-2">Ajouter un fichier</button>');
var $newLinkLi = $('<div class="row justify-content-center align-items-center"></div>').append($addTagButton);
jQuery(document).ready(function () {
  // Get the ul that holds the collection of tags
  if ($('#orphan_user_product_files').length) {
    $collectionHolder = $('#orphan_user_product_files');
  } else {
    $collectionHolder = $('div.files');
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
  var newForm = prototype;
  newForm = newForm.replace(/__name__/g, index); // increase the index with one for the next item

  $collectionHolder.data('index', index + 1);
  $collectionHolder.attr('class', 'files row col-12  flex-column align-items-center justify-content-center');
  deleteButton = $('<button class="btn btn-danger">Supprimer</button>');
  var $newFormLi = $('<li></li>').append(newForm);
  $newFormLi = $newFormLi.children(0).append(deleteButton);
  $newFormLi.attr('class', 'row justify-content-center m-2');
  $newLinkLi.before($newFormLi);
  deleteButton.on('click', function (e) {
    $(this).parent().remove();
  });
}

$('.delete').on('click', function (e) {
  $(this).parent().remove();
});
/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js"), __webpack_require__(/*! jquery */ "./node_modules/jquery/dist/jquery.js")))

/***/ })

},[["./assets/js/embedFormUserProduct.js","runtime","vendors~EmbedUser~paymentFormJs","vendors~EmbedUser"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvanMvZW1iZWRGb3JtVXNlclByb2R1Y3QuanMiXSwibmFtZXMiOlsiJGNvbGxlY3Rpb25Ib2xkZXIiLCIkYWRkVGFnQnV0dG9uIiwiJCIsIiRuZXdMaW5rTGkiLCJhcHBlbmQiLCJqUXVlcnkiLCJkb2N1bWVudCIsInJlYWR5IiwibGVuZ3RoIiwiZGF0YSIsImZpbmQiLCJvbiIsImUiLCJhZGRGaWxlRm9ybSIsInByb3RvdHlwZSIsImluZGV4IiwibmV3Rm9ybSIsInJlcGxhY2UiLCJhdHRyIiwiZGVsZXRlQnV0dG9uIiwiJG5ld0Zvcm1MaSIsImNoaWxkcmVuIiwiYmVmb3JlIiwicGFyZW50IiwicmVtb3ZlIl0sIm1hcHBpbmdzIjoiOzs7Ozs7Ozs7Ozs7Ozs7QUFBQSxJQUFJQSxpQkFBSjtBQUVBLElBQUlDLGFBQWEsR0FBR0MsQ0FBQyxDQUFDLDZGQUFELENBQXJCO0FBQ0EsSUFBSUMsVUFBVSxHQUFHRCxDQUFDLENBQUMsbUVBQUQsQ0FBRCxDQUF1RUUsTUFBdkUsQ0FBOEVILGFBQTlFLENBQWpCO0FBRUFJLE1BQU0sQ0FBQ0MsUUFBRCxDQUFOLENBQWlCQyxLQUFqQixDQUF1QixZQUFXO0FBQzlCO0FBRUEsTUFBR0wsQ0FBQyxDQUFDLDRCQUFELENBQUQsQ0FBZ0NNLE1BQW5DLEVBQTJDO0FBQ3ZDUixxQkFBaUIsR0FBR0UsQ0FBQyxDQUFDLDRCQUFELENBQXJCO0FBQ0gsR0FGRCxNQUVLO0FBQ0RGLHFCQUFpQixHQUFHRSxDQUFDLENBQUMsV0FBRCxDQUFyQjtBQUNILEdBUDZCLENBUzlCOzs7QUFDQUYsbUJBQWlCLENBQUNJLE1BQWxCLENBQXlCRCxVQUF6QixFQVY4QixDQVk5QjtBQUNBOztBQUNBSCxtQkFBaUIsQ0FBQ1MsSUFBbEIsQ0FBdUIsT0FBdkIsRUFBZ0NULGlCQUFpQixDQUFDVSxJQUFsQixDQUF1QixRQUF2QixFQUFpQ0YsTUFBakU7QUFFQVAsZUFBYSxDQUFDVSxFQUFkLENBQWlCLE9BQWpCLEVBQTBCLFVBQVNDLENBQVQsRUFBWTtBQUNsQztBQUNBQyxlQUFXLENBQUNiLGlCQUFELEVBQW9CRyxVQUFwQixDQUFYO0FBQ0gsR0FIRDtBQUlILENBcEJEOztBQXNCQSxTQUFTVSxXQUFULENBQXFCYixpQkFBckIsRUFBd0NHLFVBQXhDLEVBQW9EO0FBQ2hEO0FBQ0EsTUFBSVcsU0FBUyxHQUFHZCxpQkFBaUIsQ0FBQ1MsSUFBbEIsQ0FBdUIsV0FBdkIsQ0FBaEIsQ0FGZ0QsQ0FJaEQ7O0FBQ0EsTUFBSU0sS0FBSyxHQUFHZixpQkFBaUIsQ0FBQ1MsSUFBbEIsQ0FBdUIsT0FBdkIsQ0FBWjtBQUVBLE1BQUlPLE9BQU8sR0FBR0YsU0FBZDtBQUVBRSxTQUFPLEdBQUdBLE9BQU8sQ0FBQ0MsT0FBUixDQUFnQixXQUFoQixFQUE2QkYsS0FBN0IsQ0FBVixDQVRnRCxDQVdoRDs7QUFDQWYsbUJBQWlCLENBQUNTLElBQWxCLENBQXVCLE9BQXZCLEVBQWdDTSxLQUFLLEdBQUcsQ0FBeEM7QUFDQWYsbUJBQWlCLENBQUNrQixJQUFsQixDQUF1QixPQUF2QixFQUFnQyx5RUFBaEM7QUFFQUMsY0FBWSxHQUFHakIsQ0FBQyxDQUFDLG1EQUFELENBQWhCO0FBRUEsTUFBSWtCLFVBQVUsR0FBR2xCLENBQUMsQ0FBQyxXQUFELENBQUQsQ0FBZUUsTUFBZixDQUFzQlksT0FBdEIsQ0FBakI7QUFFQUksWUFBVSxHQUFHQSxVQUFVLENBQUNDLFFBQVgsQ0FBb0IsQ0FBcEIsRUFBdUJqQixNQUF2QixDQUE4QmUsWUFBOUIsQ0FBYjtBQUNBQyxZQUFVLENBQUNGLElBQVgsQ0FBZ0IsT0FBaEIsRUFBeUIsZ0NBQXpCO0FBRUFmLFlBQVUsQ0FBQ21CLE1BQVgsQ0FBa0JGLFVBQWxCO0FBRUFELGNBQVksQ0FBQ1IsRUFBYixDQUFnQixPQUFoQixFQUF5QixVQUFTQyxDQUFULEVBQVc7QUFDaENWLEtBQUMsQ0FBQyxJQUFELENBQUQsQ0FBUXFCLE1BQVIsR0FBaUJDLE1BQWpCO0FBQ0gsR0FGRDtBQUdIOztBQUVEdEIsQ0FBQyxDQUFDLFNBQUQsQ0FBRCxDQUFhUyxFQUFiLENBQWdCLE9BQWhCLEVBQXlCLFVBQVNDLENBQVQsRUFBVztBQUNqQ1YsR0FBQyxDQUFDLElBQUQsQ0FBRCxDQUFRcUIsTUFBUixHQUFpQkMsTUFBakI7QUFDRixDQUZELEUiLCJmaWxlIjoiRW1iZWRVc2VyLmpzIiwic291cmNlc0NvbnRlbnQiOlsidmFyICRjb2xsZWN0aW9uSG9sZGVyO1xuXG52YXIgJGFkZFRhZ0J1dHRvbiA9ICQoJzxidXR0b24gdHlwZT1cImJ1dHRvblwiIGNsYXNzPVwiYnRuIGJ0bi1wcmltYXJ5IGFkZF9maWxlX2xpbmsgbS0yXCI+QWpvdXRlciB1biBmaWNoaWVyPC9idXR0b24+Jyk7XG52YXIgJG5ld0xpbmtMaSA9ICQoJzxkaXYgY2xhc3M9XCJyb3cganVzdGlmeS1jb250ZW50LWNlbnRlciBhbGlnbi1pdGVtcy1jZW50ZXJcIj48L2Rpdj4nKS5hcHBlbmQoJGFkZFRhZ0J1dHRvbik7XG5cbmpRdWVyeShkb2N1bWVudCkucmVhZHkoZnVuY3Rpb24oKSB7XG4gICAgLy8gR2V0IHRoZSB1bCB0aGF0IGhvbGRzIHRoZSBjb2xsZWN0aW9uIG9mIHRhZ3NcblxuICAgIGlmKCQoJyNvcnBoYW5fdXNlcl9wcm9kdWN0X2ZpbGVzJykubGVuZ3RoKSB7XG4gICAgICAgICRjb2xsZWN0aW9uSG9sZGVyID0gJCgnI29ycGhhbl91c2VyX3Byb2R1Y3RfZmlsZXMnKTtcbiAgICB9ZWxzZXtcbiAgICAgICAgJGNvbGxlY3Rpb25Ib2xkZXIgPSAkKCdkaXYuZmlsZXMnKTtcbiAgICB9XG5cbiAgICAvLyBhZGQgdGhlIFwiYWRkIGEgdGFnXCIgYW5jaG9yIGFuZCBsaSB0byB0aGUgdGFncyB1bFxuICAgICRjb2xsZWN0aW9uSG9sZGVyLmFwcGVuZCgkbmV3TGlua0xpKTtcblxuICAgIC8vIGNvdW50IHRoZSBjdXJyZW50IGZvcm0gaW5wdXRzIHdlIGhhdmUgKGUuZy4gMiksIHVzZSB0aGF0IGFzIHRoZSBuZXdcbiAgICAvLyBpbmRleCB3aGVuIGluc2VydGluZyBhIG5ldyBpdGVtIChlLmcuIDIpXG4gICAgJGNvbGxlY3Rpb25Ib2xkZXIuZGF0YSgnaW5kZXgnLCAkY29sbGVjdGlvbkhvbGRlci5maW5kKCc6aW5wdXQnKS5sZW5ndGgpO1xuXG4gICAgJGFkZFRhZ0J1dHRvbi5vbignY2xpY2snLCBmdW5jdGlvbihlKSB7XG4gICAgICAgIC8vIGFkZCBhIG5ldyB0YWcgZm9ybSAoc2VlIG5leHQgY29kZSBibG9jaylcbiAgICAgICAgYWRkRmlsZUZvcm0oJGNvbGxlY3Rpb25Ib2xkZXIsICRuZXdMaW5rTGkpO1xuICAgIH0pO1xufSk7XG5cbmZ1bmN0aW9uIGFkZEZpbGVGb3JtKCRjb2xsZWN0aW9uSG9sZGVyLCAkbmV3TGlua0xpKSB7XG4gICAgLy8gR2V0IHRoZSBkYXRhLXByb3RvdHlwZSBleHBsYWluZWQgZWFybGllclxuICAgIHZhciBwcm90b3R5cGUgPSAkY29sbGVjdGlvbkhvbGRlci5kYXRhKCdwcm90b3R5cGUnKTtcblxuICAgIC8vIGdldCB0aGUgbmV3IGluZGV4XG4gICAgdmFyIGluZGV4ID0gJGNvbGxlY3Rpb25Ib2xkZXIuZGF0YSgnaW5kZXgnKTtcblxuICAgIHZhciBuZXdGb3JtID0gcHJvdG90eXBlO1xuXG4gICAgbmV3Rm9ybSA9IG5ld0Zvcm0ucmVwbGFjZSgvX19uYW1lX18vZywgaW5kZXgpO1xuXG4gICAgLy8gaW5jcmVhc2UgdGhlIGluZGV4IHdpdGggb25lIGZvciB0aGUgbmV4dCBpdGVtXG4gICAgJGNvbGxlY3Rpb25Ib2xkZXIuZGF0YSgnaW5kZXgnLCBpbmRleCArIDEpO1xuICAgICRjb2xsZWN0aW9uSG9sZGVyLmF0dHIoJ2NsYXNzJywgJ2ZpbGVzIHJvdyBjb2wtMTIgIGZsZXgtY29sdW1uIGFsaWduLWl0ZW1zLWNlbnRlciBqdXN0aWZ5LWNvbnRlbnQtY2VudGVyJyk7XG5cbiAgICBkZWxldGVCdXR0b24gPSAkKCc8YnV0dG9uIGNsYXNzPVwiYnRuIGJ0bi1kYW5nZXJcIj5TdXBwcmltZXI8L2J1dHRvbj4nKTtcblxuICAgIHZhciAkbmV3Rm9ybUxpID0gJCgnPGxpPjwvbGk+JykuYXBwZW5kKG5ld0Zvcm0pO1xuXG4gICAgJG5ld0Zvcm1MaSA9ICRuZXdGb3JtTGkuY2hpbGRyZW4oMCkuYXBwZW5kKGRlbGV0ZUJ1dHRvbik7XG4gICAgJG5ld0Zvcm1MaS5hdHRyKCdjbGFzcycsICdyb3cganVzdGlmeS1jb250ZW50LWNlbnRlciBtLTInKTtcblxuICAgICRuZXdMaW5rTGkuYmVmb3JlKCRuZXdGb3JtTGkpO1xuXG4gICAgZGVsZXRlQnV0dG9uLm9uKCdjbGljaycsIGZ1bmN0aW9uKGUpe1xuICAgICAgICAkKHRoaXMpLnBhcmVudCgpLnJlbW92ZSgpO1xuICAgIH0pXG59XG5cbiQoJy5kZWxldGUnKS5vbignY2xpY2snLCBmdW5jdGlvbihlKXtcbiAgICQodGhpcykucGFyZW50KCkucmVtb3ZlKCk7XG59KTsiXSwic291cmNlUm9vdCI6IiJ9