// This script fixes the shift that occurs in a centered layout
			// when the page grows and forces scrollbars to appear.
			$(function () {
				var body = $("body");

				var previousWidth = null;

				var resizeBody = function () {
					var currentWidth = body.width();
					if (currentWidth != previousWidth) {
						previousWidth = currentWidth;

						body.css("overflow", "hidden");
						var scrollBarWidth = body.width() - currentWidth;
						body.css("overflow", "auto");

						body.css("margin-left", scrollBarWidth + "px");
					}
				};
				setInterval(resizeBody, 100);
				resizeBody();
			});