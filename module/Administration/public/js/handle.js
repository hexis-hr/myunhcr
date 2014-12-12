/**
 * Handle function for handling data attributes
 */

if (typeof(console) == "undefined")
  console = {};

if (typeof(console.log) == "undefined")
  console.log = function () {};

var handleMap = {};

/** Used to handle interactive elements */
var handle = function (selector, callback) {

  if (typeof(handleMap[selector]) == 'undefined')
    handleMap[selector] = [];

  handleMap[selector].push(callback);

};

var handleElements = function () {

  $.each(handleMap, function (selector, callbackList) {

    if ($(selector).length > 0) {

      var handleCount = 0;

      $(selector).each(function () {

        if (typeof($(this).data().__handleIndex) == 'undefined')
          $(this).data().__handleIndex = {};

        if (typeof($(this).data().__handleIndex[selector]) == 'undefined')
          $(this).data().__handleIndex[selector] = 0;

        for (var i = $(this).data().__handleIndex[selector]; typeof(callbackList[i]) != 'undefined'; i++) {
          handleCount++;
          $(this).data().__handleIndex[selector] = i + 1;
          callbackList[i].apply(this);
        }

      });

      if (handleCount > 0)
        console.log("Handle for", selector, "(" + $(selector).length + " element(s))");

    }

  });

};

$(handleElements);
